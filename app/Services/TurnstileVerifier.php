<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TurnstileVerifier
{
    public function passes(?string $token, Request $request): bool
    {
        if (! config('services.turnstile.enabled')) {
            return true;
        }

        $secret = config('services.turnstile.secret_key');

        if (blank($token) || blank($secret)) {
            Log::warning('candidate_turnstile_rejected', ['reason' => 'missing_token_or_configuration']);

            return false;
        }

        try {
            $result = Http::asForm()->timeout(3)->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $secret,
                'response' => $token,
                'remoteip' => $request->ip(),
            ])->json();
        } catch (ConnectionException $exception) {
            Log::warning('candidate_turnstile_rejected', ['reason' => 'verification_unavailable']);

            return false;
        }

        $expectedHost = parse_url((string) config('app.url'), PHP_URL_HOST);
        $hostMatches = blank($expectedHost) || in_array($expectedHost, ['localhost', '127.0.0.1'], true)
            || ($result['hostname'] ?? null) === $expectedHost;

        if (($result['success'] ?? false) !== true || ($result['action'] ?? null) !== 'candidate_registration' || ! $hostMatches) {
            Log::warning('candidate_turnstile_rejected', ['reason' => 'invalid_verification']);

            return false;
        }

        return true;
    }
}
