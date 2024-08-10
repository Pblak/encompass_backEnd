<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    // CRUD for transactions

    public function getTransactions(Request $request)
    {
        $user = app('user_guard');
        dd($user);
        return response()->json(Transaction::all());
    }

    public function getTransaction(Request $request)
    {
        return response()->json(Transaction::find($request->id));
    }

    public function updateTransaction(Request $request)
    {
        // update a transaction
        DB::beginTransaction();
        try {
            $transaction = Transaction::find($request->id);
            $transaction->update($request->all());
            DB::commit();
            return response()->json($transaction);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createTransaction(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric',
            'type' => 'required|string',
            'description' => 'required|string',
        ]);
        $transaction = Transaction::create($request->all());
        return response()->json($transaction);
    }

    public function deleteTransaction(Request $request)
    {
        $transaction = Transaction::find($request->id);
        $transaction->delete();
        return response()->json(['message' => 'Transaction deleted']);
    }

    public function getStudentTransactions(Request $request)
    {
        return response()->json(Student::find($request->id)->transactions);
    }
}
