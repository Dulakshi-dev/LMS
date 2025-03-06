<?php

class PaymentController
{
    private $paymentModel;

    public function __construct()
    {
        require_once Config::getModelPath('paymentmodel.php');
        $this->paymentModel = new PaymentModel();
    }

    public function paymentNotify()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
            $payhere_response = $_POST;

            error_log("PayHere Response: " . print_r($payhere_response, true));

            if (isset($payhere_response['transactionId']) && !empty($payhere_response['transactionId'])) {
                $transaction_id = $payhere_response['transactionId'];

                echo json_encode([
                    'success' => true,
                    'transaction_id' => $transaction_id,
                    'message' => 'Payment successful.'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Payment failed or was incomplete.'
                ]);
            }
        }
    }

}
