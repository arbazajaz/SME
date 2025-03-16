<?php

namespace App\Livewire;

use App\Models\Employee;
use Livewire\Component;
use Livewire\WithPagination;

class EmployeeManager extends Component
{
    use WithPagination;

    public $sortBy = 'name';
    public $sortDirection = 'asc';
    public $perPage = 5;

    public $employeeId = null;
    public $id = '';
    public $payroll_number = '';
    public $name = '';
    public $mobile = '';

    public $isEditing = false;
    public $showForm = false;

    protected $rules = [
        'payroll_number' => 'required|string|max:255|unique:employees,payroll_number',
        'name' => 'required|string|max:255',
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
    public function employees()
    {
        return Employee::query()
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
        $employee = Employee::findOrFail($id);
        $this->employeeId = $employee->id;
        $this->payroll_number = $employee->payroll_number;
        $this->name = $employee->name;
        $this->mobile = $employee->mobile;
        $this->isEditing = true;
        $this->showForm = true;
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->isEditing) {
            $rules['payroll_number'] = 'required|string|max:255|unique:employees,payroll_number,' . $this->employeeId;
        }

        $this->validate($rules);

        if ($this->isEditing) {
            $employee = Employee::findOrFail($this->employeeId);
            $employee->update([
                'payroll_number' => $this->payroll_number,
                'name' => $this->name,
                'mobile' => $this->mobile,
            ]);
            session()->flash('message', 'Employee updated successfully!');
        } else {
            Employee::create([
                'payroll_number' => $this->payroll_number,
                'name' => $this->name,
                'mobile' => $this->mobile,
            ]);
            session()->flash('message', 'Employee created successfully!');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function delete($id)
    {
        Employee::findOrFail($id)->delete();
        session()->flash('message', 'Employee deleted successfully!');
    }

    public function resetForm()
    {
        $this->employeeId = null;
        $this->id = '';
        $this->payroll_number = '';
        $this->name = '';
        $this->mobile = '';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.employee-manager');
    }
}