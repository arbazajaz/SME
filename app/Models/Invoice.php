<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['id', 'number', 'cost', 'discount', 'total'];

    // One-to-Many: An Invoice can have many InvoiceRows
    public function invoiceRows()
    {
        return $this->hasMany(InvoiceRow::class, 'id');
    }

}
