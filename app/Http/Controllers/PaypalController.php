<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PaypalController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000|max:300000',
        ]);

//        $total = round($request->amount / env('VITE_EUR_DZD'), 2);
//        return $this->handlecreateOrder((object)['amount' => $total]);
    }

    public function createOrder(Request $request): JsonResponse
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'amount' => 'required|numeric',
        ]);
        $lesson = Lesson::with(['transactions'])->find($request->lesson_id);

        if ($lesson->payed_price + $request->amount > $lesson->price) {
            return response()->json([
                "message" => "Transaction amount exceeds lesson price",
                "_t" => "error",
            ]);
        }

        $accessToken = $this->generateAccessToken();
        $url = $this->getBaseUrl() . "/v2/checkout/orders";

        $response = Http::withHeaders([
            'Authorization' => "Bearer $accessToken",
            "Content-Type" => "application/json",
        ])->withBody(json_encode([
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $request->amount,
                    ],
                ],
            ],
        ]), 'application/json')->post($url);
        $data = [...$response->json(), ...$request->toArray()];
        $data['id'] = $response->json()['id'];
        if ($response->successful()) {
            return response()->json($data, 200);
        }
        return response()->json(['aze'], 500); //error
    }

    // use the orders api to capture payment for an order
    public function capturePayment(Request $request): JsonResponse
    {
        $url = $this->getBaseUrl() . "/v2/checkout/orders/$request->orderID/capture";
        $accessToken = $this->generateAccessToken();
        $response = Http::withHeaders([
            'Authorization' => "Bearer $accessToken",
            "Content-Type" => "application/json",
        ])->withBody('{}', 'application/json')->post($url);

        if ($response->successful()) {
            $data = new Request([
                'lesson_id' => $request->lesson_id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
                'infos' => $response->json()
            ]);

            return (new TransactionController)->createTransaction($data);
        }
        return response()->json($request, 500); //error
    }

// generate an access token using client id and app secret
    private function generateAccessToken()
    {

        $auth = base64_encode(env('VITE_CLIENT_PAYPAL_ID') . ":" . env('VITE_CLIENT_PAYPAL_SECRET'));
        $url = $this->getBaseUrl() . "/v1/oauth2/token";

        $response = Http::withHeaders([
            'Authorization' => "Basic $auth"
        ])->asForm()
            ->post($url, [
                'grant_type' => 'client_credentials'
            ]);
        $data = $response->json();

        if ($response->successful()) {
            return $data['access_token'];
        }
        return null;
    }

    private function getBaseUrl(): string
    {
        if (env('APP_ENV') == 'production') return "https://api-m.paypal.com";

        return "https://api-m.sandbox.paypal.com";
    }
}
