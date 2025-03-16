<?php

namespace App\Livewire;

use App\Models\Service;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceManager extends Component
{
    use WithPagination;

    public $sortBy = 'name';
    public $sortDirection = 'asc';
    public $perPage = 5;

    public $serviceId = null;
    public $name = '';
    public $description = '';
    public $hourly_rate = '';

    public $isEditing = false;
    public $showForm = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'hourly_rate' => 'required|numeric|min:0',
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
    public function services()
    {
        return Service::query()
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
        $service = Service::findOrFail($id);
        $this->serviceId = $service->id;
        $this->name = $service->name;
        $this->description = $service->description;
        $this->hourly_rate = $service->hourly_rate;
        $this->isEditing = true;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $service = Service::findOrFail($this->serviceId);
            $service->update([
                'name' => $this->name,
                'description' => $this->description,
                'hourly_rate' => $this->hourly_rate,
            ]);
            session()->flash('message', 'Service updated successfully!');
        } else {
            Service::create([
                'name' => $this->name,
                'description' => $this->description,
                'hourly_rate' => $this->hourly_rate,
            ]);
            session()->flash('message', 'Service created successfully!');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function delete($id)
    {
        Service::findOrFail($id)->delete();
        session()->flash('message', 'Service deleted successfully!');
    }

    public function resetForm()
    {
        $this->serviceId = null;
        $this->name = '';
        $this->description = '';
        $this->hourly_rate = '';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.service-manager');
    }
}