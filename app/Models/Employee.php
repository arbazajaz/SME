<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['payroll_number', 'name', 'mobile'];

    // Many-to-Many: An Employee can provide many Services (via ServicesProvided)
    public function services()
    {
        return $this->belongsToMany(Service::class, 'services_provided', 'id', 'service_id')
            ->withTimestamps();
    }

    // One-to-Many: An Employee can have many ClientAppointments
    public function appointments()
    {
        return $this->hasMany(ClientAppointment::class, 'id');
    }
}
