<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
protected $fillable = [
    'user_id', 'address_id', 'order_number',
    'total_amount', 'status', 'ordered_at', 'delivery_date',
    'delivery_address', 'invoice_address'
];
    protected $casts = [
        'ordered_at'    => 'datetime',
        'delivery_date' => 'datetime',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}