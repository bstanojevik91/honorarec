<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCompanyRequest;
use App\Http\Requests\Admin\UpdateCompanyRequest;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
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
        $data['logo_path'] = $request->file('logo')?->store('companies', 'public');
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
            if ($company->logo_path) {
                Storage::disk('public')->delete($company->logo_path);
            }

            $data['logo_path'] = $request->file('logo')->store('companies', 'public');
        }

        unset($data['logo']);

        $company->update($data);

        return redirect()
            ->route('admin.companies.index')
            ->with('status', 'Податоците за компанијата се ажурирани.');
    }

    public function destroy(Company $company): RedirectResponse
    {
        if ($company->logo_path) {
            Storage::disk('public')->delete($company->logo_path);
        }

        $company->delete();

        return redirect()
            ->route('admin.companies.index')
            ->with('status', 'Компанијата е избришана.');
    }
}
