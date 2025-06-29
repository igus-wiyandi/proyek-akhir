<?php

namespace App\Rules;

use Closure;
use App\Models\admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
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
        $email = $this->request->input('email');
        $password = $this->request->input('password');
        $loginStatus = false;

        $admin = admin::where('email', $email)->first();

        if ($admin && Hash::check($password, $admin->password)) {
            $loginStatus = true;
            Session::put('loginStatus', true);
            Session::put('ambilUser', $admin);
            Session::put('isAdmin', true);
        }

        if (! $loginStatus) {
            $fail('Email atau password salah.');
        }
    }
}
