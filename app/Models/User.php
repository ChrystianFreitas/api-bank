<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Class User
 * @method static Builder getUserByValidToken($token)
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static $this create(array $attributes);
 * @mixin Builder
 */

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'token_api'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function bankAccount(){
        return $this->hasOne(BankAccount::class, 'user_id');
    }

    public function transactions(){
        return $this->hasMany(Transaction::class, 'user_id');
    }

    /**
     * @param Builder $query
     * @param $token
     * @return Builder
     */
    public function scopeGetUserByValidToken(Builder $query, string $token){
        return $query->where('token_api', '=', $token)
            ->where('token_api_expired', '=', '0');
    }

    public function createToken(){
        $account = $this->bankAccount()->first();
        $this->token_api = Hash::make($account->agency . $account->bank_account . Str::random(10));
        $this->token_api_expired = false;
        $this->save();
        return $this->token_api;
    }

    public function invalidateToken(){
        $this->token_api_expired = true;
        $this->save();
        return true;
    }
}
