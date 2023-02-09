<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'invoices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'partner_id',
        'package_id',
        'invoiced_date',
        'due_date',
        'amount',
        'is_paid',
        'payment_method',
        'desc',
        'amount_paid',
        'date_paid'
    ];

    /**
     * Get partner (company) information
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Get Package information
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
