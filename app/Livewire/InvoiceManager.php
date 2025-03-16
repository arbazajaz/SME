<?php

namespace App\Livewire;

use App\Models\Invoice;
use Livewire\Component;
use Livewire\WithPagination;

class InvoiceManager extends Component
{
    use WithPagination;

    public $sortBy = 'number';
    public $sortDirection = 'asc';
    public $perPage = 5;

    public $invoiceId = null;
    public $number = '';
    public $cost = '';
    public $discount = '';
    public $total = '';

    public $isEditing = false;
    public $showForm = false;

    protected $rules = [
        'number' => 'required|string|max:255|unique:invoices,number',
        'cost' => 'required|numeric|min:0',
        'discount' => 'required|numeric|min:0',
        'total' => 'required|numeric|min:0',
    ];

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
    public function invoices()
    {
        return Invoice::query()
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
        $invoice = Invoice::findOrFail($id);
        $this->invoiceId = $invoice->id;
        $this->number = $invoice->number;
        $this->cost = $invoice->cost;
        $this->discount = $invoice->discount;
        $this->total = $invoice->total;
        $this->isEditing = true;
        $this->showForm = true;
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->isEditing) {
            $rules['number'] = 'required|string|max:255|unique:invoices,number,' . $this->invoiceId;
        }

        $this->validate($rules);

        if ($this->isEditing) {
            $invoice = Invoice::findOrFail($this->invoiceId);
            $invoice->update([
                'number' => $this->number,
                'cost' => $this->cost,
                'discount' => $this->discount,
                'total' => $this->total,
            ]);
            session()->flash('message', 'Invoice updated successfully!');
        } else {
            Invoice::create([
                'number' => $this->number,
                'cost' => $this->cost,
                'discount' => $this->discount,
                'total' => $this->total,
            ]);
            session()->flash('message', 'Invoice created successfully!');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function delete($id)
    {
        Invoice::findOrFail($id)->delete();
        session()->flash('message', 'Invoice deleted successfully!');
    }

    public function resetForm()
    {
        $this->invoiceId = null;
        $this->number = '';
        $this->cost = '';
        $this->discount = '';
        $this->total = '';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.invoice-manager');
    }
}