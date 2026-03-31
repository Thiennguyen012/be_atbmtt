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
            'order_name' => 'sometimes|string|max:255',
            'shipping_address' => 'sometimes|string|max:500',
            'total_amount' => 'sometimes|numeric|min:0',
            'notes' => 'sometimes|string|nullable',
            'shipping_date' => 'sometimes|date|nullable',
            'estimated_delivery_date' => 'sometimes|date|nullable',
            'delivery_date' => 'sometimes|date|nullable',
            'receiver_name' => 'sometimes|string|max:255',
            'receiver_phone' => 'sometimes|string|max:20',
            'receiver_email' => 'sometimes|email|max:255|nullable',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'order_name.string' => 'Tên đơn hàng phải là chuỗi',
            'order_name.max' => 'Tên đơn hàng không được vượt quá 255 ký tự',
            'shipping_address.string' => 'Địa chỉ giao hàng phải là chuỗi',
            'shipping_address.max' => 'Địa chỉ giao hàng không được vượt quá 500 ký tự',
            'total_amount.numeric' => 'Tổng tiền phải là số',
            'total_amount.min' => 'Tổng tiền phải lớn hơn hoặc bằng 0',
            'notes.string' => 'Ghi chú phải là chuỗi',
            'shipping_date.date' => 'Ngày giao hàng phải là định dạng ngày hợp lệ',
            'estimated_delivery_date.date' => 'Ngày dự kiến giao hàng phải là định dạng ngày hợp lệ',
            'delivery_date.date' => 'Ngày giao hàng thực tế phải là định dạng ngày hợp lệ',
            'receiver_name.string' => 'Tên người nhận phải là chuỗi',
            'receiver_name.max' => 'Tên người nhận không được vượt quá 255 ký tự',
            'receiver_phone.string' => 'Số điện thoại người nhận phải là chuỗi',
            'receiver_phone.max' => 'Số điện thoại người nhận không được vượt quá 20 ký tự',
            'receiver_email.email' => 'Email người nhận phải là định dạng email hợp lệ',
            'receiver_email.max' => 'Email người nhận không được vượt quá 255 ký tự',
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
