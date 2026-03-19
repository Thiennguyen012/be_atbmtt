<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;

class StoreOrderRequest extends FormRequest
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
            'order_name' => 'required|string|max:255',
            'shipping_address' => 'required|string',
            'status' => 'required|string|in:pending,processing,shipped,in_transit,delivered,cancelled',
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'shipping_date' => 'nullable|date',
            'estimated_delivery_date' => 'nullable|date',
            'delivery_date' => 'nullable|date',
            // Thông tin người nhận
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:20',
            'receiver_email' => 'required|email',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'order_name.required' => 'Tên đơn hàng là bắt buộc',
            'order_name.string' => 'Tên đơn hàng phải là chuỗi ký tự',
            'order_name.max' => 'Tên đơn hàng không được vượt quá 255 ký tự',
            'shipping_address.required' => 'Địa chỉ giao hàng là bắt buộc',
            'shipping_address.string' => 'Địa chỉ giao hàng phải là chuỗi ký tự',
            'status.required' => 'Trạng thái đơn hàng là bắt buộc',
            'status.in' => 'Trạng thái phải là một trong: pending, processing, shipped, in_transit, delivered, cancelled',
            'total_amount.required' => 'Tổng tiền là bắt buộc',
            'total_amount.numeric' => 'Tổng tiền phải là số',
            'total_amount.min' => 'Tổng tiền phải lớn hơn hoặc bằng 0',
            'shipping_date.date' => 'Ngày giao hàng không hợp lệ',
            'estimated_delivery_date.date' => 'Ngày giao hàng dự kiến không hợp lệ',
            'delivery_date.date' => 'Ngày giao hàng thực tế không hợp lệ',
            // Thông tin người nhận
            'receiver_name.required' => 'Tên người nhận là bắt buộc',
            'receiver_name.string' => 'Tên người nhận phải là chuỗi ký tự',
            'receiver_name.max' => 'Tên người nhận không được vượt quá 255 ký tự',
            'receiver_phone.required' => 'Số điện thoại người nhận là bắt buộc',
            'receiver_phone.string' => 'Số điện thoại phải là chuỗi ký tự',
            'receiver_phone.max' => 'Số điện thoại không được vượt quá 20 ký tự',
            'receiver_email.required' => 'Email người nhận là bắt buộc',
            'receiver_email.email' => 'Email phải có định dạng hợp lệ',
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
