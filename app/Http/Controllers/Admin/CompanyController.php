<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEmployerAccountRequest;
use App\Http\Requests\Admin\StoreCompanyRequest;
use App\Http\Requests\Admin\UpdateEmployerAccountRequest;
use App\Http\Requests\Admin\UpdateCompanyRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CompanyController extends Controller
{
    private const NO_PUBLIC_CALL_TOKEN = '__NO_PUBLIC_CALL__';

    public function index(): View
    {
        return view('admin.companies.index', [
            'companies' => Company::latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.companies.create', [
            'companyPhonePrimary' => '',
            'companyPublishCallPhone' => true,
        ]);
    }

    public function store(StoreCompanyRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['phone'] = $this->mergeCompanyPhones(
            (string) ($data['phone'] ?? ''),
            isset($data['publish_call_phone']) ? (bool) $data['publish_call_phone'] : false
        );

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $this->uploadLogo($request);
        }

        unset($data['logo']);
        unset($data['publish_call_phone']);

        Company::create($data);

        return redirect()
            ->route('admin.companies.index')
            ->with('status', 'Компанијата е успешно додадена.');
    }

    public function edit(Company $company): View
    {
        $phoneData = $this->splitCompanyPhones($company->phone);

        return view('admin.companies.edit', [
            'company' => $company,
            'companyPhonePrimary' => $phoneData['primary'],
            'companyPublishCallPhone' => $phoneData['publish'],
        ]);
    }

    public function update(UpdateCompanyRequest $request, Company $company): RedirectResponse
    {
        $data = $request->validated();
        $data['phone'] = $this->mergeCompanyPhones(
            (string) ($data['phone'] ?? ''),
            isset($data['publish_call_phone']) ? (bool) $data['publish_call_phone'] : false
        );

        if ($request->hasFile('logo')) {
            $this->deleteLogoFile($company->logo_path);
            $data['logo_path'] = $this->uploadLogo($request);
        } elseif ($request->boolean('remove_logo')) {
            $this->deleteLogoFile($company->logo_path);
            $data['logo_path'] = null;
        }

        unset($data['logo']);
        unset($data['remove_logo']);
        unset($data['publish_call_phone']);

        $company->update($data);

        return redirect()
            ->route('admin.companies.index')
            ->with('status', 'Податоците за компанијата се ажурирани.');
    }

    public function destroy(Company $company): RedirectResponse
    {
        $this->deleteLogoFile($company->logo_path);

        $company->delete();

        return redirect()
            ->route('admin.companies.index')
            ->with('status', 'Компанијата е избришана.');
    }

    public function storeEmployerAccount(StoreEmployerAccountRequest $request, Company $company): RedirectResponse
    {
        if ($company->user()->exists()) {
            return back()->withErrors([
                'email' => 'Оваа компанија веќе има employer акаунт.',
            ], 'employerAccount');
        }

        $validated = $request->validated();

        User::create([
            'name' => $company->name,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => false,
            'company_id' => $company->id,
            'email_verified_at' => now(),
        ]);

        return redirect()
            ->route('admin.companies.edit', $company)
            ->with('status', 'Employer акаунтот е успешно креиран.');
    }

    public function updateEmployerAccount(UpdateEmployerAccountRequest $request, Company $company): RedirectResponse
    {
        $user = $company->user;

        if ($user === null) {
            return back()->withErrors([
                'email' => 'За оваа компанија нема employer акаунт за ажурирање.',
            ], 'employerAccount');
        }

        $validated = $request->validated();
        $data = [
            'email' => $validated['email'],
        ];

        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()
            ->route('admin.companies.edit', $company)
            ->with('status', 'Employer акаунтот е успешно ажуриран.');
    }

    private function uploadLogo(StoreCompanyRequest|UpdateCompanyRequest $request): string
    {
        $file = $request->file('logo');
        $directory = public_path('storage/companies');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $originalName = preg_replace('/\s+/', '_', (string) $file->getClientOriginalName());
        $filename = time().'_'.$originalName;

        while (file_exists($directory.'/'.$filename)) {
            $filename = time().'_'.uniqid().'_'.$originalName;
        }

        $file->move($directory, $filename);

        return 'storage/companies/'.$filename;
    }

    private function deleteLogoFile(?string $logoPath): void
    {
        if (! $logoPath) {
            return;
        }

        $rawPath = ltrim(trim($logoPath), '/');
        $candidates = [
            public_path($rawPath),
            public_path(str_starts_with($rawPath, 'storage/') ? $rawPath : 'storage/'.$rawPath),
        ];

        foreach (array_unique($candidates) as $fullPath) {
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
    }

    /**
     * @return array{primary:string,publish:bool}
     */
    private function splitCompanyPhones(?string $storedPhone): array
    {
        $parsed = $this->parseStoredCompanyPhones($storedPhone);
        $phones = $parsed['phones'];

        if ($phones === []) {
            return [
                'primary' => '',
                'publish' => $parsed['publish'],
            ];
        }

        return [
            'primary' => $phones[0],
            'publish' => $parsed['publish'],
        ];
    }

    private function mergeCompanyPhones(string $primaryPhone, bool $publishCallPhone): string
    {
        $primaryCandidates = $this->tokenizePhones($primaryPhone);
        $primary = $primaryCandidates[0] ?? '';
        $all = $primary !== '' ? [$primary] : [];

        if (! $publishCallPhone) {
            $all[] = self::NO_PUBLIC_CALL_TOKEN;
        }

        return implode(' | ', $all);
    }

    /**
     * @return array{phones: array<int, string>, publish: bool}
     */
    private function parseStoredCompanyPhones(?string $storedPhone): array
    {
        $publish = true;
        $phones = [];

        foreach ($this->tokenizePhones($storedPhone) as $token) {
            if ($this->isNoPublicCallToken($token)) {
                $publish = false;
                continue;
            }

            $phones[] = $token;
        }

        return [
            'phones' => array_values(array_unique($phones)),
            'publish' => $publish,
        ];
    }

    /**
     * @return array<int, string>
     */
    private function tokenizePhones(?string $raw): array
    {
        if ($raw === null) {
            return [];
        }

        $parts = preg_split('/(?:\r\n|\r|\n|,|;|\|)+/', $raw) ?: [];

        return collect($parts)
            ->map(fn (string $phone): string => trim($phone))
            ->filter()
            ->values()
            ->all();
    }

    private function isNoPublicCallToken(string $token): bool
    {
        return mb_strtoupper(trim($token)) === self::NO_PUBLIC_CALL_TOKEN;
    }
}
