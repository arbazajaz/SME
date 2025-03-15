<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceRow extends Model
{
    protected $fillable = ['invoice_id', 'client_appointment_id'];

    // Belongs to Invoice (Many-to-One)
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    // Belongs to ClientAppointment (Many-to-One)
    public function appointment()
    {
        return $this->belongsTo(ClientAppointment::class, 'client_appointment_id', 'id');
    }

}
