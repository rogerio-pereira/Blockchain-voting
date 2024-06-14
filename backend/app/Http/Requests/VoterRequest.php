<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class VoterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user.name' => ['required', 'string', 'max:255'],
            'user.email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'user.password' => ['required', 'confirmed', Password::defaults()],

            'voter.voting_district_id' => 'required|numeric|exists:voting_districts,id',
            'voter.address' => 'required',
            'voter.address_2' => 'nullable',
            'voter.city' => 'required',
            'voter.state' => 'required|size:2',
            'voter.zipcode' => 'required|numeric|min:10000|max:99999',
        ];
    }
}
