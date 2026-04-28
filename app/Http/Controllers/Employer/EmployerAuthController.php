<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employer\LoginRequest;
use App\Http\Requests\Employer\RegisterRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployerAuthController extends Controller
{
    public function create(): View
    {
        return view('employer.auth.login');
    }

    public function createRegister(): View
    {
        return view('employer.auth.register');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        if (! Auth::attempt($request->validated(), $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Невалидна е-пошта или лозинка.']);
        }

        $request->session()->regenerate();

        $user = $request->user();

        if ($user === null || $user->is_admin || $user->company_id === null) {
            Auth::logout();

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Овој профил нема пристап до employer панелот.']);
        }

        if (! $user->hasVerifiedEmail()) {
            return redirect()
                ->route('employer.verification.notice')
                ->with('status', 'Пратена е порака за потврда. Потврдете ја е-поштата за да продолжите.');
        }

        return redirect()->route('employer.dashboard');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = DB::transaction(function () use ($request, $validated): User {
            $company = Company::create([
                'name' => $validated['company_name'],
                'phone' => $validated['company_phone'],
                'email' => $validated['company_email'],
                'description' => $validated['company_description'] ?: null,
                'logo_path' => $request->hasFile('company_logo')
                    ? $request->file('company_logo')->store('companies', 'public')
                    : null,
            ]);

            return User::create([
                'name' => $validated['contact_name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'is_admin' => false,
                'company_id' => $company->id,
            ]);
        });

        Auth::login($user);
        $request->session()->regenerate();
        $user->sendEmailVerificationNotification();

        return redirect()
            ->route('employer.verification.notice')
            ->with('status', 'Регистрацијата е скоро готова. Проверете ја е-поштата и потврдете ја вашата компанија.');
    }

    public function verificationNotice(): View
    {
        abort_unless(request()->user() !== null, 403);

        return view('employer.auth.verify-email');
    }

    public function verifyEmail(Request $request): RedirectResponse
    {
        $user = User::query()
            ->whereKey($request->route('id'))
            ->where('is_admin', false)
            ->whereNotNull('company_id')
            ->first();

        if ($user === null || ! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            abort(403, 'Невалиден линк за потврда.');
        }

        if (! $user->hasVerifiedEmail()) {
            $user->forceFill([
                'email_verified_at' => now(),
            ])->save();

            event(new Verified($user));
        }

        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect()
            ->route('employer.login')
            ->with('status', 'Е-поштата е потврдена. Најавете се за да продолжите кон employer панелот.');
    }

    public function resendVerification(Request $request): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user !== null, 403);

        if ($user->hasVerifiedEmail()) {
            return redirect()
                ->route('employer.dashboard')
                ->with('status', 'Е-поштата е веќе потврдена.');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('status', 'Испративме нов линк за потврда на вашата е-пошта.');
    }

    public function destroy(): RedirectResponse
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('employer.login');
    }
}
