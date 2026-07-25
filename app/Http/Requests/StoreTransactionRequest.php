<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
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
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'transaction_date' => ['required', 'date'],
            'type' => [
                'required',
                'string',
                'in:income,expense',
                function ($attribute, $value, $fail) {
                    $category = Category::find($this->category_id);
                    if ($category && $category->type !== $value) {
                        $fail('Tipe transaksi harus sesuai dengan tipe kategori yang dipilih (' . ($category->type === 'income' ? 'Pemasukan' : 'Pengeluaran') . ').');
                    }
                }
            ],
        ];
    }
}
