<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Crypt;

class gStripe extends Controller
{
    /**
     * Integration class
     */
    /**
     * Global vars
     */
    private $amount;
    private $obj;
    private function Settings(Request $r)
    {
        $productInfo = json_decode(Crypt::decryptString($r->input('productData')), true);
        $this->amount = $productInfo["valor"];
        Stripe::setApiKey(getenv('STRIPE_API_KEY'));
    }

    public function CreateOrder(Request $r)
    {
        try {
            $this->Settings($r);
            $paymentIntent = PaymentIntent::create([
                'amount' => $this->amount,
                'currency' => 'brl',
              ]);
            $output = [
                'clientSecret' => $paymentIntent->client_secret,
                'amount' => $this->amount
            ];
            return json_encode($output);
        } catch (\Exception $ex) {
            return json_encode(['error' => $ex->getMessage()]);
        }
    }
}
