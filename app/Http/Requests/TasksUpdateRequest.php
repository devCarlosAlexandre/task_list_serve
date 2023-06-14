<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TasksUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|exists:tasks,id|numeric',
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:3',
            'user_id' => 'required|exists:users,id|numeric',
            'attachment' => 'sometimes|file|max:5000|mimes:jpeg,png,docx,xlsx',
            'status' => 'sometimes|nullable',
            'date_done' =>  'sometimes|nullable',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }
}
