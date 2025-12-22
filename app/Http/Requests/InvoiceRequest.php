<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'plan' => 'required|string|max:255',
            'billing_cycle' => 'required|in:monthly,yearly',
            'due_date' => 'nullable|date',
            'status' => 'required|string|in:unpaid,paid,overdue',
            'payment_method' => 'nullable|string|max:100',
            'paid_at' => 'nullable|date',
        ];
    }
}
