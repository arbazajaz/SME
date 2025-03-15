<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['id', 'name', 'description', 'hourly_rate'];

    // Many-to-Many: A Service can be provided by many Employees (via ServicesProvided)
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'services_provided', 'id', 'employee_id')
            ->withTimestamps();
    }

    // One-to-Many: A Service can have many ClientAppointments
    public function appointments()
    {
        return $this->hasMany(ClientAppointment::class, 'id', 'id');
    }
}
