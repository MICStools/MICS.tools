<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Spatie\Honeypot\ProtectAgainstSpam;
use Spatie\Honeypot\Exceptions\SpamException;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function __construct()
    {
        // Apply honeypot + throttle only to the password reset method
        $this->middleware(ProtectAgainstSpam::class)->only('sendResetLinkEmail');
        $this->middleware('throttle:3,10')->only('sendResetLinkEmail');
    }

    /**
     * Handle the form POST for sending a password reset email
     */
    public function sendResetLinkEmail(Request $request)
    {
    
        // Uncomment this line temporarily to test exception handling
        //throw new SpamException();

        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }
}
