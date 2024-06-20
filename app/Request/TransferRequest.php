<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\Validator;
/**
 * @OA\Schema(
 *     schema="TransferRequest",
 *     required={"value", "payer", "payeer"},
 *     @OA\Property(property="value", type="number", format="float", example=100.50),
 *     @OA\Property(property="payer", type="integer", example=1),
 *     @OA\Property(property="payeer", type="integer", example=2)
 * )
 */
class TransferRequest extends FormRequest
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
            'value' => [
                'required',
                'numeric',
                'min:0.01',
                function ($attribute, $value, $fail) {
                    if ($value <= 0) {
                        $fail('O campo valor deve ser maior que zero.');
                    }
                },
            ],
            'payer' => 'required|exists:users,id',
            'payeer' => 'required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'value.required' => 'O campo valor é obrigatório.',
            'value.numeric' => 'O campo valor deve ser numérico.',
            'value.min' => 'O campo valor deve ser pelo menos 0.01.',
            'value.custom' => 'O campo valor deve ser maior que zero.',

            'payer.required' => 'O campo pagador é obrigatório.',
            'payer.exists' => 'O pagador informado não existe.',

            'payeer.required' => 'O campo lojista é obrigatório.',
            'payeer.exists' => 'O lojista informado não existe.',
        ];
    }
}
