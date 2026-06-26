<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureEnrollmentFormFilled
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'student') {
            $form = Auth::user()->enrollmentForm;

            // If no form exists, or if the form is missing program or year_level (even in draft)
            if (!$form || empty($form->program) || empty($form->year_level)) {
                return redirect()
                    ->route('student.forms.show')
                    ->withErrors(['form' => 'Please select your Program and Year Level in your enrollment form and save/submit it first before accessing other sections.']);
            }
        }

        return $next($request);
    }
}
