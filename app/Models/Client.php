<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name', 'address', 'email', 'mobile'];

    // One-to-Many: A Client can have many ClientAppointments
    public function appointments()
    {
        return $this->hasMany(ClientAppointment::class, 'id');
    }
}
