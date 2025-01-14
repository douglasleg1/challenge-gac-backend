<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    /**
     * Depósito de saldo.
     */
    public function deposit(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
        ]);

        DB::beginTransaction();
        try {
            $user = User::findOrFail($request->user_id);
            $balanceBefore = $user->balance;

            if ($balanceBefore < 0) {
                $user->balance += $request->amount;
            } else {
                $user->balance += $request->amount;
            }

            $user->save();

            Transaction::create([
                'user_id' => $user->id,
                'type' => 'deposit',
                'amount' => $request->amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $user->balance,
                'status' => 'completed',
            ]);

            DB::commit();
            return response()->json(['status' => 'success', 'balance' => $user->balance], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * Transferir saldo entre usuários.
     */
    public function transfer(Request $request)
    {
        $request->validate([
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
        ]);

        DB::beginTransaction();
        try {
            $sender = User::findOrFail($request->sender_id);
            $receiver = User::findOrFail($request->receiver_id);

            if ($sender->balance < $request->amount) {
                return response()->json(['status' => 'error', 'message' => 'Saldo insuficiente.'], 400);
            }

            $balanceBeforeSender = $sender->balance;
            $balanceBeforeReceiver = $receiver->balance;

            $sender->balance -= $request->amount;
            $receiver->balance += $request->amount;

            $sender->save();
            $receiver->save();

            Transaction::create([
                'user_id' => $sender->id,
                'type' => 'transfer',
                'amount' => $request->amount,
                'balance_before' => $balanceBeforeSender,
                'balance_after' => $sender->balance,
                'status' => 'completed',
            ]);

            Transaction::create([
                'user_id' => $receiver->id,
                'type' => 'transfer',
                'amount' => $request->amount,
                'balance_before' => $balanceBeforeReceiver,
                'balance_after' => $receiver->balance,
                'status' => 'completed',
            ]);

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Transferência realizada com sucesso.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * Reverter uma transação.
     */
    public function revertTransaction($transactionId)
    {
        DB::beginTransaction();
        try {
            $transaction = Transaction::findOrFail($transactionId);

            if ($transaction->status == 'reverted') {
                return response()->json(['status' => 'error', 'message' => 'Transação já revertida.'], 400);
            }

            $user = $transaction->user;
            if ($transaction->type == 'deposit') {
                $user->balance -= $transaction->amount;
            } elseif ($transaction->type == 'transfer') {
                $otherUser = User::findOrFail($transaction->user_id == $user->id ? $transaction->receiver_id : $transaction->sender_id);
                $otherUser->balance -= $transaction->amount;
                $user->balance += $transaction->amount;
            }

            $user->save();
            $transaction->status = 'reverted';
            $transaction->save();

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Transação revertida com sucesso.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    public function listTransfers(Request $request)
    {
        $userId = auth()->id(); 
    
        $transfers = Transaction::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get(); // Adicione o método get() para executar a consulta
    
        return response()->json([
            'status' => 'success',
            'data' => $transfers,
        ], 200);
    }
}
