<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $isAdmin = $request->user()->hasRole('SuperAdmin') || $request->user()->hasRole('Administrador');
        
        if ($request->user()->hasVerifiedEmail()) {
            $route = $isAdmin
                ? route('admin.dashboard', absolute: false)
                : route('dashboard', absolute: false);
            return redirect()->intended($route.'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        $route = $isAdmin
            ? route('admin.dashboard', absolute: false)
            : route('dashboard', absolute: false);
        return redirect()->intended($route.'?verified=1');
    }
}
