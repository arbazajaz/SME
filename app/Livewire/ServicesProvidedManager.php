<?php

namespace App\Livewire;

use App\Models\ServicesProvided;
use App\Models\Service;
use App\Models\Employee;
use Livewire\Component;
use Livewire\WithPagination;

class ServicesProvidedManager extends Component
{
    use WithPagination;

    public $sortBy = 'service_id';
    public $sortDirection = 'asc';
    public $perPage = 5;

    public $serviceProvidedId = null;
    public $service_id = '';
    public $employee_id = '';

    public $services = [];
    public $employees = [];

    public $isEditing = false;
    public $showForm = false;

    protected $rules = [
        'service_id' => 'required|exists:services,id',
        'employee_id' => 'required|exists:employees,id',
    ];

    public function mount()
    {
        $this->services = Service::all()->pluck('name', 'id')->toArray();
        $this->employees = Employee::all()->pluck('name', 'id')->toArray();
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
    public function servicesProvided()
    {
        return ServicesProvided::query()
            ->with(['service', 'employee'])
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
        $serviceProvided = ServicesProvided::findOrFail($id);
        $this->serviceProvidedId = $serviceProvided->id;
        $this->service_id = $serviceProvided->service_id;
        $this->employee_id = $serviceProvided->employee_id;
        $this->isEditing = true;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $serviceProvided = ServicesProvided::findOrFail($this->serviceProvidedId);
            $serviceProvided->update([
                'service_id' => $this->service_id,
                'employee_id' => $this->employee_id,
            ]);
            session()->flash('message', 'Service Provided updated successfully!');
        } else {
            ServicesProvided::create([
                'service_id' => $this->service_id,
                'employee_id' => $this->employee_id,
            ]);
            session()->flash('message', 'Service Provided created successfully!');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function delete($id)
    {
        ServicesProvided::findOrFail($id)->delete();
        session()->flash('message', 'Service Provided deleted successfully!');
    }

    public function resetForm()
    {
        $this->serviceProvidedId = null;
        $this->service_id = '';
        $this->employee_id = '';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.services-provided-manager');
    }
}