<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employer\UpdateCompanyProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CompanyProfileController extends Controller
{
    public function edit(): View
    {
        $company = request()->user()->company;

        abort_unless($company !== null, 404);

        return view('employer.company.edit', [
            'company' => $company,
        ]);
    }

    public function update(UpdateCompanyProfileRequest $request): RedirectResponse
    {
        $company = $request->user()->company;

        abort_unless($company !== null, 404);

        $company->update($request->validated());

        return redirect()
            ->route('employer.company.edit')
            ->with('status', 'Бројот за повикување е успешно ажуриран.');
    }
}
