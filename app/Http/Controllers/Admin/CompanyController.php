<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCompanyRequest;
use App\Http\Requests\Admin\UpdateCompanyRequest;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(): View
    {
        return view('admin.companies.index', [
            'companies' => Company::latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.companies.create');
    }

    public function store(StoreCompanyRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $this->uploadLogo($request);
        }

        unset($data['logo']);

        Company::create($data);

        return redirect()
            ->route('admin.companies.index')
            ->with('status', 'Компанијата е успешно додадена.');
    }

    public function edit(Company $company): View
    {
        return view('admin.companies.edit', [
            'company' => $company,
        ]);
    }

    public function update(UpdateCompanyRequest $request, Company $company): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $this->deleteLogoFile($company->logo_path);
            $data['logo_path'] = $this->uploadLogo($request);
        } elseif ($request->boolean('remove_logo')) {
            $this->deleteLogoFile($company->logo_path);
            $data['logo_path'] = null;
        }

        unset($data['logo']);
        unset($data['remove_logo']);

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
}
