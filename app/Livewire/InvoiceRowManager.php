<?php

namespace App\Livewire;

use App\Models\InvoiceRow;
use App\Models\Invoice;
use App\Models\ClientAppointment;
use Livewire\Component;
use Livewire\WithPagination;

class InvoiceRowManager extends Component
{
    use WithPagination;

    public $sortBy = 'invoice_id';
    public $sortDirection = 'asc';
    public $perPage = 5;

    public $invoiceRowId = null;
    public $invoice_id = '';
    public $client_appointment_id = '';

    public $invoices = [];
    public $appointments = [];

    public $isEditing = false;
    public $showForm = false;

    protected $rules = [
        'invoice_id' => 'required|exists:invoices,id',
        'client_appointment_id' => 'required|exists:client_appointments,id',
    ];

    public function mount()
    {
        $this->invoices = Invoice::all()->pluck('number', 'id')->toArray();
        $this->appointments = ClientAppointment::all()->pluck('id', 'id')->toArray();
    }

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    #[\Livewire\Attributes\Computed]
    public function invoiceRows()
    {
        return InvoiceRow::query()
            ->with(['invoice', 'appointment'])
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function showCreateForm()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->isEditing = false;
    }

    public function showEditForm($id)
    {
        $invoiceRow = InvoiceRow::findOrFail($id);
        $this->invoiceRowId = $invoiceRow->id;
        $this->invoice_id = $invoiceRow->invoice_id;
        $this->client_appointment_id = $invoiceRow->client_appointment_id;
        $this->isEditing = true;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $invoiceRow = InvoiceRow::findOrFail($this->invoiceRowId);
            $invoiceRow->update([
                'invoice_id' => $this->invoice_id,
                'client_appointment_id' => $this->client_appointment_id,
            ]);
            session()->flash('message', 'Invoice Row updated successfully!');
        } else {
            InvoiceRow::create([
                'invoice_id' => $this->invoice_id,
                'client_appointment_id' => $this->client_appointment_id,
            ]);
            session()->flash('message', 'Invoice Row created successfully!');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function delete($id)
    {
        InvoiceRow::findOrFail($id)->delete();
        session()->flash('message', 'Invoice Row deleted successfully!');
    }

    public function resetForm()
    {
        $this->invoiceRowId = null;
        $this->invoice_id = '';
        $this->client_appointment_id = '';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.invoice-row-manager');
    }
}