<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientAppointment extends Model
{
    protected $fillable = ['client_id', 'service_id', 'employee_id', 'appointment_date', 'expenses'];

    // Belongs to Client (Many-to-One)
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    // Belongs to Service (Many-to-One)
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    // Belongs to Employee (Many-to-One)
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    // One-to-Many: A ClientAppointment can have many InvoiceRows
    public function invoiceRows()
    {
        return $this->hasMany(InvoiceRow::class, 'client_appointment_id', 'id');
    }

}
