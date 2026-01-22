<?php

namespace App\Http\Requests\Aset;

use Illuminate\Foundation\Http\FormRequest;

class StoreMutationRequest extends FormRequest
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
            'aset_id' => 'required|exists:asets,id',
            'type' => 'required|string',
            'person_in_charge' => 'required|string',
            'destination_location' => 'required|string',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ];
    }
}
