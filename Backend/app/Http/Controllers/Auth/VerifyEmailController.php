<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\UnauthorizedException;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(Request $request)
    {
        $user = User::findOrFail(request()->route('id'));

        if (! hash_equals(sha1($user->getEmailForVerification()), (string) request()->route('hash'))) {
            throw new UnauthorizedException();
        }

        if ($user->hasVerifiedEmail()) {
            return \response()->json(['message' => 'email already verified']);
        }

        $user->markEmailAsVerified();

        event(new Verified($user));

        return \response()->json(['message' => 'email verified successfully']);

    }
}
