<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Student;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Ramsey\Collection\Collection;

class TransactionController extends Controller
{
    // CRUD for transactions

    public function getTransactions(Request $request): JsonResponse
    {
        if ($request->attributes->get('currentGuard') !== 'admin') {
            $transactions = $request->user()->lessons()->with(['transactions'])->get()
                ->flatMap(function ($lesson) {
                    return $lesson->transactions;
                });

        } else {
            $transactions = Transaction::with(['transactional'])->get();
        }

        return response()->json($transactions);
    }

    public function getTransaction(Request $request): JsonResponse
    {
        return response()->json(Transaction::find($request->id));
    }

    public function updateTransaction(Request $request): JsonResponse
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

    public function createTransaction(Request $request): JsonResponse
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'amount' => 'required|numeric',
        ]);
        $lesson = Lesson::with(['transactions'])->find($request->lesson_id);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            if ($lesson->payed_price + $request->amount > $lesson->price) {
                return response()->json([
                    "message" => "Transaction amount exceeds lesson price",
                    "_t" => "error",
                ]);
            }
            $transaction = $user->transactions()->create([
                'lesson_id' => $request->lesson_id,
                'amount' => $request->amount,
                'infos'=> $request->infos,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
                ]);
            DB::commit();
            return response()->json([
                "message" => "Transaction created successfully",
                "transaction" => $transaction,
                "_t" => "success",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '',
                'error' => $e->getMessage(),
                "_t" => "error",
            ], 500);
        }
    }

    public function deleteTransaction(Request $request): JsonResponse
    {
        $transaction = Transaction::find($request->id);
        $transaction->delete();
        return response()->json(['message' => 'Transaction deleted']);
    }

    public function getStudentTransactions(Request $request): JsonResponse
    {
        return response()->json(Student::find($request->id)->transactions);
    }
}
