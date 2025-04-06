<?php

require_once __DIR__ . '/../../../main.php';

class PaymentController extends Controller
{
    private $paymentModel;
    private $homeModel;
    private $memberModel;

    public function __construct()
    {
        require_once Config::getModelPath('member', 'paymentmodel.php');
        require_once Config::getModelPath('member', 'homemodel.php');
        require_once Config::getModelPath('staff', 'membermodel.php');

        $this->paymentModel = new PaymentModel();
        $this->homeModel = new HomeModel();
        $this->memberModel = new MemberModel();
    }

    public function paymentNotify()
    {
        if ($this->isPost()) {
            $payhere_response = $_POST;

            error_log("PayHere Response: " . print_r($payhere_response, true));

            if (!empty($payhere_response['transactionId'])) {
                $transaction_id = $this->sanitize($payhere_response['transactionId']);

                $this->jsonResponse([
                    'transaction_id' => $transaction_id,
                    'message' => 'Payment successful.'
                ]);
            } else {
                $this->jsonResponse([
                    'message' => 'Payment failed or was incomplete.'
                ], false);
            }
        } else {
            $this->jsonResponse(['message' => 'Invalid Request'], false);
        }
    }

    public static function proceedPayment()
    {
        require_once Config::getServicePath('paymentService.php');

        $paymentService = new PaymentService();
        $paymentService->createPayment(); // This already executes inside
    }

    public static function renewPayment()
    {
        require_once Config::getModelPath('member', 'homemodel.php');
        require_once Config::getModelPath('member', 'paymentmodel.php');
        require_once Config::getModelPath('staff', 'membermodel.php');

        $controller = new class extends Controller {}; // temporary to access helper methods

        $libraryData = HomeModel::getLibraryInfo();
        $fee = $libraryData['membership_fee'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transactionId = $controller->getPost('transactionId');
            $member_id = $controller->getPost('memberId');

            if (!$transactionId || !$member_id) {
                $controller->jsonResponse(['message' => 'Missing parameters'], false);
            }

            $result = PaymentModel::renewPayment($transactionId, $member_id, $fee);

            if ($result) {
                $updated = MemberModel::updateMembershipStatus($member_id);
                if ($updated) {
                    $controller->jsonResponse(['message' => 'Membership Renewed.']);
                }
            }

            $controller->jsonResponse(['message' => 'Payment failed or was incomplete.'], false);
        } else {
            $controller->jsonResponse(['message' => 'Invalid Request'], false);
        }
    }
}
