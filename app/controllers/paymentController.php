<?php

class PaymentController
{
    private $paymentModel;
    private $homeModel;
    private $memberModel;


    public function __construct()
    {
        require_once Config::getModelPath('paymentmodel.php');
        require_once Config::getModelPath('homemodel.php');
        require_once Config::getModelPath('membermodel.php');

        $this->paymentModel = new PaymentModel();
        $this->homeModel = new HomeModel();
        $this->memberModel = new MemberModel();
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


    public static function proceedPayment()
    {
        require_once Config::getServicePath('paymentService.php');

        $paymentService = new PaymentService();
        $paymentData = $paymentService->createPayment();
    }


    public static function renewPayment()
    {
        $libraryData = HomeModel::getLibraryInfo();
        $fee = $libraryData['membership_fee'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $transactionId = $_POST['transactionId'];
            $member_id = $_POST['memberId'];

            $result = PaymentModel::renewPayment($transactionId, $member_id, $fee);

            if ($result) {
                $result2 = MemberModel::updateMembershipStatus($member_id);
                if ($result2) {
                    echo json_encode(['success' => true, 'message' => 'Membership Renewed.']);

                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Payment failed or was incomplete.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid Request']);
        }
    }
}
