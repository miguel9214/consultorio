<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoices extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'invoice_number',
        'due_date',
        'total_amount',
        'taxes',
        'discounts',
        'amount_paid',
        'consultation_id'
    ];
}
