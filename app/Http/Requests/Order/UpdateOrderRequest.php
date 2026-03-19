<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;

class UpdateOrderRequest extends FormRequest
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
            'customer_id' => 'sometimes|integer|exists:users,id',
            'status' => 'sometimes|string|in:pending,confirmed,processing,completed,cancelled',
            'total_amount' => 'sometimes|numeric|min:0',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'customer_id.integer' => 'ID khách hàng phải là số',
            'customer_id.exists' => 'Khách hàng này không tồn tại',
            'status.in' => 'Trạng thái không hợp lệ',
            'total_amount.numeric' => 'Tổng tiền phải là số',
            'total_amount.min' => 'Tổng tiền phải lớn hơn hoặc bằng 0',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'status_code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'message' => 'Dữ liệu không hợp lệ',
            'errors' => $validator->errors()
        ], Response::HTTP_UNPROCESSABLE_ENTITY);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
