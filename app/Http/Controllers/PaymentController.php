<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kkiapay\Kkiapay;

class PaymentController extends Controller
{
    public function initiatePayment(Request $request)
    {
        $amount = $request->input('amount');
        $name = $request->input('name');
        $email = $request->input('email');
        $phone = $request->input('phone');

        $kkiapay = new Kkiapay;

        $transactionRef = uniqid();

        $paymentLink = $kkiapay->getPaymentUrl([
            'amount' => $amount,
            'reason' => 'Achat de produits',
            'payer_name' => $name,
            'payer_email' => $email,
            'payer_phone' => $phone,
            'transaction_id' => $transactionRef
        ]);

        return response()->json([
            'payment_link' => $paymentLink,
            'transaction_ref' => $transactionRef,
        ]);
    }

    public function verifyPayment(Request $request)
    {
        $transactionRef = $request->input('transaction_ref');

        $kkiapay = new Kkiapay();
        $paymentStatus = $kkiapay->verifyTransaction($transactionRef);

        if ($paymentStatus->status === 'SUCCESS') {
            // Paiement validé
            return response()->json([
                'message' => 'Paiement réussi',
                'data' => $paymentStatus
            ], 200);
        } else {
            // Paiement échoué ou en attente
            return response()->json([
                'message' => 'Paiement échoué ou en attente',
                'data' => $paymentStatus
            ], 400);
        }
    }
}
