<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\ValidationException;
use Hyperf\Validation\Validator;


/**
 * @OA\Schema(
 *     schema="StoreUserRequest",
 *     required={"name", "document", "email"},
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="document", type="string", example="12345678901"),
 *     @OA\Property(property="email", type="string", format="email", example="johndoe@example.com")
 * )
 */
class StoreUserRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'document' => [
                'required',
                'string',
                'max:14',
                'min:11',
                'unique:users',
                'regex:/^\d{11}$|^\d{14}$/'
            ],
            'email' => 'required|string|email|max:255|unique:users',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo Nome Completo é obrigatório.',
            'name.string' => 'O campo Nome Completo deve ser uma string.',
            'name.max' => 'O campo Nome Completo não pode ter mais de 255 caracteres.',

            'document.required' => 'O campo document é obrigatório.',
            'document.string' => 'O campo document deve ser uma string.',
            'document.max' => 'O campo document não pode ter mais de 14 caracteres.',
            'document.min' => 'O campo document não pode ter menos de 11 caracteres.',
            'document.unique' => 'O document informado já está em uso.',
            'document.regex' => 'O campo document deve conter exatamente 11 ou 14 dígitos.',

            'email.required' => 'O campo E-mail é obrigatório.',
            'email.string' => 'O campo E-mail deve ser uma string.',
            'email.email' => 'O campo E-mail deve ser um endereço de e-mail válido.',
            'email.max' => 'O campo E-mail não pode ter mais de 255 caracteres.',
            'email.unique' => 'O E-mail informado já está em uso.',
        ];
    }
}
