<?php
namespace App\Http\Controllers;

use App\Http\Requests\InvoiceRequest;
use App\Services\InvoiceService;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use App\Models\Invoice; // Assuming you have an Invoice model

class InvoiceController extends Controller
{
    public function __construct(protected InvoiceService $invoiceService) {}

    public function index()
    {
        // Fetch invoices using the service, or directly if the service method is not suitable for Inertia response
        $invoices = $this->invoiceService->getAll(); // Adjust if necessary
        return Inertia::render('Invoice/InvoiceList', [
            'invoices' => $invoices
        ]);
    }

    public function create()
    {
        return Inertia::render('Invoice/InvoiceForm'); // Assuming you'll create an InvoiceForm.vue
    }

    public function store(InvoiceRequest $request)
    {
        $this->invoiceService->create($request->validated());
        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    public function show($id)
    {
        $invoice = $this->invoiceService->getById($id);
        return Inertia::render('Invoice/InvoiceShow', [
            'invoice' => $invoice
        ]);
    }

    public function edit($id)
    {
        $invoice = $this->invoiceService->getById($id);
        return Inertia::render('Invoice/InvoiceForm', [
            'invoice' => $invoice
        ]); // Assuming InvoiceForm.vue can handle existing data for editing
    }

    public function update(InvoiceRequest $request, $id)
    {
        $this->invoiceService->update($id, $request->validated());
        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    public function destroy($id) : JsonResponse // Kept as JsonResponse for AJAX delete
    {
        $this->invoiceService->delete($id);
        // Consider returning a redirect to index for non-AJAX or a specific success message for AJAX.
        return response()->json(['message' => 'Invoice deleted successfully']);
    }
}

