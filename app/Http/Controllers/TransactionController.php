<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Student;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    // CRUD for transactions

    public function getTransactions(Request $request)
    {
        $transactions = $request->attributes->get('currentGuard') !=='admin'?$request->user()->transactions:Transaction::all();
        return response()->json($transactions);
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
            'lesson_id' => 'required|exists:lessons,id',
            'amount' => 'required|numeric',
        ]);
        $lesson = Lesson::with(['transactions'])->find($request->lesson_id);

        DB::beginTransaction();
        try {
            if ($lesson->payed_price + $request->amount > $lesson->price) {
                return response()->json([
                    "message" => "Transaction amount exceeds lesson price",
                    "_t" => "error",
                ]);
            }
            $request->user()->transactions()->create($request->all());
            DB::commit();
            return response()->json([
                "message" =>  "Transaction created successfully",
                "_t" => "success",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
