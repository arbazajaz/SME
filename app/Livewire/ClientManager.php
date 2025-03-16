<?php

namespace App\Livewire;

use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;

class ClientManager extends Component
{
    use WithPagination;

    public $sortBy = 'name';
    public $sortDirection = 'asc';
    public $perPage = 5;

    public $clientId = null;
    public $name = '';
    public $address = '';
    public $email = '';
    public $mobile = '';

    public $isEditing = false;
    public $showForm = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:clients,email',
        'mobile' => 'required|string|max:255',
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
    public function clients()
    {
        return Client::query()
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
        $client = Client::findOrFail($id);
        $this->clientId = $client->id;
        $this->name = $client->name;
        $this->address = $client->address;
        $this->email = $client->email;
        $this->mobile = $client->mobile;
        $this->isEditing = true;
        $this->showForm = true;
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->isEditing) {
            $rules['email'] = 'required|string|email|max:255|unique:clients,email,' . $this->clientId;
        }

        $this->validate($rules);

        if ($this->isEditing) {
            $client = Client::findOrFail($this->clientId);
            $client->update([
                'name' => $this->name,
                'address' => $this->address,
                'email' => $this->email,
                'mobile' => $this->mobile,
            ]);
            session()->flash('message', 'Client updated successfully!');
        } else {
            Client::create([
                'name' => $this->name,
                'address' => $this->address,
                'email' => $this->email,
                'mobile' => $this->mobile,
            ]);
            session()->flash('message', 'Client created successfully!');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function delete($id)
    {
        Client::findOrFail($id)->delete();
        session()->flash('message', 'Client deleted successfully!');
    }

    public function resetForm()
    {
        $this->clientId = null;
        $this->name = '';
        $this->address = '';
        $this->email = '';
        $this->mobile = '';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.client-manager');
    }
}