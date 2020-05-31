<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentLogs extends Model
{
    protected $table = 'paymentlogs';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount', 'plan', 'charge_id', 'stripe_id', 'quantity'
    ];

    public function savePaymentData($data)
    {
        $this->amount = $data['amount'];
        $this->plan = $data['plan'];
        $this->charge_id = $data['charge_id'];
        $this->stripe_id = $data['stripe_id'];
        $this->quantity = $data['quantity'];
        $this->save();
        return true;
    }
}
