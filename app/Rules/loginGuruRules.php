<?php

namespace App\Rules;

use Closure;
use App\Models\guru;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Validation\ValidationRule;

class loginGuruRules implements ValidationRule
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $email = $this->request->input('email');
        $password = $this->request->input('password');
        $loginStatus = false;

        $guru = guru::where('email', $email)->first();

        if ($guru && Hash::check($password, $guru->password)) {
            $loginStatus = true;
            Session::put('loginStatus', true);
            Session::put('ambilUser', $guru);
        }

        if (! $loginStatus) {
            $fail('Email atau password salah.');
        }
    }
}
