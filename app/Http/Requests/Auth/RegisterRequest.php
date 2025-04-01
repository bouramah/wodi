<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }

    /**
     * Handle a passed validation attempt.
     */
    public function passedValidation(): void
    {
        //
    }

    /**
     * Register a new user.
     */
    public function register(): void
    {
        $user = \App\Models\User::create([
            'name' => $this->name,
            'phone_number' => $this->phone_number,
            'password' => Hash::make($this->password),
        ]);

        event(new Registered($user));

        Auth::login($user);
    }
}
