<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    public function getBalance(Request $request)
    {
        $request->validate([
            'token' => 'required'
        ]);
        $user = User::getUserByValidToken($request->token)->first();

        if(empty($user) || !$user){
            return response()->json([
                'message' => 'Acesso negado!'
            ], 400);
        }

        $bankAccount = $user->bankAccount()->first();

        return response()->json([
            "balance" => floatval($bankAccount->balance)
        ], 200);
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'value' => 'required'
        ]);
        $user = User::getUserByValidToken($request->token)->first();
        $value = $request->value;

        if(empty($user) || !$user){
            return response()->json([
                'message' => 'Acesso negado!'
            ], 400);
        }

        $bankAccount = $user->bankAccount()->first();

        if($value < 0 ){
            return response()->json([
                'message' => 'O valor fornecido para saque tem que ser superior a 0!'
            ], 400);

        }else if($value > floatval($bankAccount->balance)){
            return response()->json([
                'message' => 'Saldo insuficiente!'
            ], 400);
        }else{
            $beforeBalance = $bankAccount->balance;
            $withdraw = $bankAccount->withdraw($value);

            if($withdraw){
                $transaction = Transaction::create([
                    "user_id" => $user->id,
                    "bank_account_id" => $bankAccount->id,
                    "type" => "withdraw",
                    "transaction_amount" => $value,
                    "balance_before_transaction" => $beforeBalance,
                    "balance_after_transaction" => $bankAccount->balance,
                ]);

                return response()->json([
                    "message" => "O saque foi realizado com sucesso!"
                ], 200);
            }

        }
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'value' => 'required',
            'number_account' => 'required',
            'number_account_dv' => 'required',
            'agency' => 'required',
            'agency_dv' => 'required',
        ]);

        $agency = $request->agency;
        $agency_dv = $request->agency_dv;
        $number_account = $request->number_account;
        $number_account_dv = $request->number_account_dv;
        $value = $request->value;

        $bankAccount = BankAccount::where('agency', '=', $agency)
            ->where('agency_dv', "=", $agency_dv)
            ->where('number_account', "=", $number_account)
            ->where('number_account_dv', "=", $number_account_dv)
            ->first();

        if(!$bankAccount || empty($bankAccount)){
            return response()->json([
                'message' => 'Os dados fornecidos estão incorretos!',
            ], 400);
        }

        $user = $bankAccount->user()->first();

        if($value < 0 ){
            return response()->json([
                'message' => 'O valor fornecido para deposito tem que ser superior a 0!'
            ], 400);

        }else{
            $beforeBalance = $bankAccount->balance;
            $deposit = $bankAccount->deposit($value);

            if($deposit){
                $transaction = Transaction::create([
                    "user_id" => $user->id,
                    "bank_account_id" => $bankAccount->id,
                    "type" => "withdraw",
                    "transaction_amount" => $value,
                    "balance_before_transaction" => $beforeBalance,
                    "balance_after_transaction" => $bankAccount->balance,
                ]);
                return response()->json([
                    "message" => "O depósito foi realizado com sucesso!"
                ], 200);
            }
        }
    }

    public function depositOwnAccount(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'value' => 'required'
        ]);
        $user = User::getUserByValidToken($request->token)->first();
        $value = $request->value;

        if(empty($user) || !$user){
            return response()->json([
                'message' => 'Acesso negado!'
            ], 400);
        }

        $bankAccount = $user->bankAccount()->first();

        if($value < 0 ){
            return response()->json([
                'message' => 'O valor fornecido para deposito tem que ser superior a 0!'
            ], 400);

        }else{
            $beforeBalance = $bankAccount->balance;
            $deposit = $bankAccount->deposit($value);

            if($deposit){
                $transaction = Transaction::create([
                    "user_id" => $user->id,
                    "bank_account_id" => $bankAccount->id,
                    "type" => "deposit",
                    "transaction_amount" => $value,
                    "balance_before_transaction" => $beforeBalance,
                    "balance_after_transaction" => $bankAccount->balance,
                ]);

                return response()->json([
                    "message" => "O depósito foi realizado com sucesso!"
                ], 200);
            }

        }
    }
}
