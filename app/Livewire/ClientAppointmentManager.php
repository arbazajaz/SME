<?php

namespace App\Livewire;

use App\Models\ClientAppointment;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Service;
use Livewire\Component;
use Livewire\WithPagination;

class ClientAppointmentManager extends Component
{
    use WithPagination;

    public $sortBy = 'appointment_date';
    public $sortDirection = 'asc';
    public $perPage = 5;

    public $appointmentId = null;
    public $client_id = '';
    public $service_id = '';
    public $employee_id = '';
    public $appointment_date = '';
    public $expenses = '';

    public $clients = [];
    public $services = [];
    public $employees = [];

    public $isEditing = false;
    public $showForm = false;

    protected $rules = [
        'client_id' => 'required|exists:clients,id',
        'service_id' => 'required|exists:services,id',
        'employee_id' => 'required|exists:employees,id',
        'appointment_date' => 'required|date',
        'expenses' => 'required|numeric|min:0',
    ];

    public function mount()
    {
        $this->clients = Client::all()->pluck('name', 'id')->toArray();
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
    public function appointments()
    {
        return ClientAppointment::query()
            ->with(['client', 'service', 'employee'])
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
        $appointment = ClientAppointment::findOrFail($id);
        $this->appointmentId = $appointment->id;
        $this->client_id = $appointment->client_id;
        $this->service_id = $appointment->service_id;
        $this->employee_id = $appointment->employee_id;
        $this->appointment_date = $appointment->appointment_date;
        $this->expenses = $appointment->expenses;
        $this->isEditing = true;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $appointment = ClientAppointment::findOrFail($this->appointmentId);
            $appointment->update([
                'client_id' => $this->client_id,
                'service_id' => $this->service_id,
                'employee_id' => $this->employee_id,
                'appointment_date' => $this->appointment_date,
                'expenses' => $this->expenses,
            ]);
            session()->flash('message', 'Appointment updated successfully!');
        } else {
            ClientAppointment::create([
                'client_id' => $this->client_id,
                'service_id' => $this->service_id,
                'employee_id' => $this->employee_id,
                'appointment_date' => $this->appointment_date,
                'expenses' => $this->expenses,
            ]);
            session()->flash('message', 'Appointment created successfully!');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function delete($id)
    {
        ClientAppointment::findOrFail($id)->delete();
        session()->flash('message', 'Appointment deleted successfully!');
    }

    public function resetForm()
    {
        $this->appointmentId = null;
        $this->client_id = '';
        $this->service_id = '';
        $this->employee_id = '';
        $this->appointment_date = '';
        $this->expenses = '';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.client-appointment-manager');
    }
}