<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicesProvided extends Model
{
    protected $fillable = ['service_id', 'employee_id'];

    // Belongs to Service (Many-to-One)
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'service_id');
    }

    // Belongs to Employee (Many-to-One)
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
