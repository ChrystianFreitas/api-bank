<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $table = 'bank_accounts';

    protected $fillable = [
        'user_id',
        'agency',
        'agency_dv',
        'number_account',
        'number_account_dv',
        'balance',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transactions(){
        return $this->hasMany(Transaction::class, 'bank_account_id');
    }

    public function withdraw(float $value){
        if($value < $this->balance && $value < 0){
            return false;
        }else {
            $this->balance = $this->balance - $value;
            $this->save();
            return true;
        }
    }

    public function deposit(float $value){
        if($value < 0){
            return false;
        }else {
            $this->balance = $this->balance + $value;
            $this->save();
            return true;
        }
    }
}
