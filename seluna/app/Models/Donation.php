<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'user_id', 
        'campaign_id', 
        'order_id',
        'amount', 
        'payment_method', 
        'snap_token',
        'payment_url',
        'status', 
        'midtrans_status',
        'verified_by',
        'verified_at',
        'proof_path',
        'fee_amount',
        'net_amount'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
