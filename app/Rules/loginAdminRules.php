<?php

namespace App\Rules;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\ValidationRule;

class loginAdminRules implements ValidationRule
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $credentials = [
            'email'    => $this->request->input('email'),
            'password' => $this->request->input('password'),
        ];

        if (!Auth::attempt($credentials)) {
            $fail('Email atau password salah.');
            return;
        }

        if (Auth::user()->role !== 'admin') {
            Auth::logout();
            $fail('Akun ini bukan akun admin.');
        }
    }
}
