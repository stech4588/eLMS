<?php
namespace App\Services;

use App\Models\Invoice;

class InvoiceService
{
    public function getAll() {
        return Invoice::with(['user', 'details.course'])->get();
    }

    public function getById(int $id) {
        return Invoice::with(['user', 'details.course'])->findOrFail($id);
    }

    public function create(array $data) {
        return Invoice::create($data);
    }

    public function update(int $id, array $data) {
        $invoice = Invoice::findOrFail($id);
        $invoice->update($data);
        return $invoice;
    }

    public function delete(int $id): bool {
        return Invoice::findOrFail($id)->delete();
    }
}
