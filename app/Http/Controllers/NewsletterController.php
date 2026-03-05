<?php

namespace App\Http\Controllers;

use App\Mail\WaitlistWelcome;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Resend;

class NewsletterController extends Controller
{
    /**
     * Add an email to the waitlist (Resend contacts) and send a welcome email via Resend.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
        ]);

        $key = config('services.resend.key');
        if (! $key) {
            Log::warning('Newsletter signup attempted but RESEND_API_KEY is not set.');

            return back()->with('newsletter_error', 'Signup is temporarily unavailable. Please try again later.');
        }

        try {
            $resend = Resend::client($key);
            $resend->contacts->create([
                'email' => $validated['email'],
                'unsubscribed' => false,
            ]);

            $segmentId = config('services.resend.segment_id');
            if ($segmentId) {
                $resend->contacts->segments->add($validated['email'], $segmentId);
            }

            try {
                Mail::mailer('resend')
                    ->to($validated['email'])
                    ->send(new WaitlistWelcome);
            } catch (\Throwable $mailError) {
                Log::warning('Waitlist welcome email failed (contact was added): '.$mailError->getMessage(), [
                    'email' => $validated['email'],
                ]);
            }
        } catch (\Throwable $e) {
            $message = $e->getMessage();
            if (str_contains($message, 'already exists') || str_contains($message, 'already in')) {
                return back()->with('newsletter_success', true);
            }
            Log::error('Newsletter signup failed: '.$message, [
                'email' => $validated['email'],
                'exception' => $e,
            ]);

            return back()->with('newsletter_error', 'Something went wrong. Please try again later.');
        }

        return back()->with('newsletter_success', true);
    }
}
