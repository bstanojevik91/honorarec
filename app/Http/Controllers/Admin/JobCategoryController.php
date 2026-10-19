<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobCategory;
use App\Support\CategoryName;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class JobCategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => JobCategory::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (is_string($request->input('name'))) {
            $request->merge(['name' => CategoryName::display($request->input('name'))]);
        }

        $data = $request->validate(['name' => ['required', 'string', 'max:255']], [
            'name.required' => 'Внесете име на категоријата.',
            'name.string' => 'Името на категоријата мора да биде текст.',
            'name.max' => 'Името на категоријата може да содржи најмногу 255 знаци.',
        ]);

        $duplicateMessage = 'Категорија со ова име веќе постои.';
        if (JobCategory::canonicalName($data['name']) !== null) {
            throw ValidationException::withMessages(['name' => $duplicateMessage]);
        }

        try {
            JobCategory::create($data);
        } catch (UniqueConstraintViolationException $exception) {
            // The unique normalized key also handles concurrent admin submissions.
            throw ValidationException::withMessages(['name' => $duplicateMessage]);
        }

        return redirect()->route('admin.categories.index')->with('status', 'Категоријата е успешно додадена.');
    }
}
