<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class VerificationController extends Controller
{
    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (! hash_equals($hash, sha1($user->email))) {
            throw ValidationException::withMessages([
                'email' => ['The email verification link is invalid.'],
            ]);
        }

        $user->markEmailAsVerified();

        return redirect('/home')->with('status', 'Your email has been verified!');
    }
}
