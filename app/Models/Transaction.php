<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'bank_account_id',
        'type',
        'transaction_amount',
        'balance_before_transaction',
        'balance_after_transaction',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transactions(){
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }
}
