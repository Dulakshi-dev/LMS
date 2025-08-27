<?php

require_once __DIR__ . '/../../../main.php';

class PaymentController extends Controller
{
    private $paymentModel;
    private $homeModel;
    private $memberModel;

    public function __construct()
    {   // Load required models for payments, library info, and member details
        require_once Config::getModelPath('member', 'paymentmodel.php');
        require_once Config::getModelPath('member', 'homemodel.php');
        require_once Config::getModelPath('staff', 'membermodel.php');

        $this->paymentModel = new PaymentModel();
        $this->homeModel = new HomeModel();
        $this->memberModel = new MemberModel();
    }
    // Function called by payment gateway (PayHere) to notify payment status
    public function paymentNotify()
    {
        if (!$this->isPost()) { // Only allow POST requests
            Logger::warning('Invalid paymentNotify request method.', ['method' => $_SERVER['REQUEST_METHOD']]);
            $this->jsonResponse(['message' => 'Invalid Request'], false);
            return;
        }

        $payhere_response = $_POST;
        // Log the received response from PayHere
        Logger::info('Received PayHere Response.', ['response' => $payhere_response]);
        // Check if transactionId exists to confirm payment success
        if (!empty($payhere_response['transactionId'])) {
            $transaction_id = $this->sanitize($payhere_response['transactionId']);
            Logger::info("Payment successful for transactionId: {$transaction_id}");

            $this->jsonResponse([  // Respond back with success
                'transaction_id' => $transaction_id,
                'message' => 'Payment successful.'
            ]);
        } else {    // Payment failed or incomplete
            Logger::error('Payment failed or incomplete.', ['response' => $payhere_response]);
            $this->jsonResponse([
                'message' => 'Payment failed or was incomplete.'
            ], false);
        }
    }
     // Function to start a payment process
    public static function proceedPayment()
    {
        require_once Config::getServicePath('paymentService.php');

        try {
            $paymentService = new PaymentService();
            $paymentService->createPayment(); // This already executes inside
            Logger::info('Payment process started successfully.');
        } catch (\Exception $e) {
            Logger::error('Error during payment processing.', ['exception' => $e->getMessage()]);
            // Optionally handle error or rethrow
        }
    }
    // Function to renew member's library payment/membership
    public static function renewPayment()
    {
        require_once Config::getModelPath('member', 'homemodel.php');
        require_once Config::getModelPath('member', 'paymentmodel.php');
        require_once Config::getModelPath('staff', 'membermodel.php');
        require_once __DIR__ . '/../../core/Logger.php';
         // Create temporary controller object to access helper methods
        $controller = new class extends Controller {}; // temporary to access helper methods

        $libraryData = HomeModel::getLibraryInfo(); // Get membership fee from library info
        $fee = $libraryData['membership_fee'] ?? 0;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {  // Only allow POST requests
            Logger::warning('Invalid renewPayment request method.', ['method' => $_SERVER['REQUEST_METHOD']]);
            $controller->jsonResponse(['message' => 'Invalid Request'], false);
            return;
        }

        $transactionId = $controller->getPost('transactionId'); // Get transactionId and memberId from request
        $member_id = $controller->getPost('memberId');

        if (!$transactionId || !$member_id) { // Check if required parameters are present
            Logger::warning('Missing parameters for renewPayment.', [
                'transactionId' => $transactionId,
                'memberId' => $member_id,
            ]);
            $controller->jsonResponse(['message' => 'Missing parameters'], false);
            return;
        }

        Logger::info('Attempting to renew membership payment.', [
            'transactionId' => $transactionId,
            'memberId' => $member_id,
            'fee' => $fee
        ]);
        
        // Call model to renew payment
        $result = PaymentModel::renewPayment($transactionId, $member_id, $fee);

        if ($result) { // Update membership status after successful payment
            $updated = MemberModel::updateMembershipStatus($member_id);
            if ($updated) {
                Logger::info("Membership renewed for member ID: {$member_id}");
                $controller->jsonResponse(['message' => 'Membership Renewed.']);
                return;
            } else {
                Logger::error("Failed to update membership status for member ID: {$member_id}");
            }
        } else {
            Logger::error('Payment renewal failed or incomplete.', [
                'transactionId' => $transactionId,
                'memberId' => $member_id,
            ]);
        }
         // If payment failed or status update failed
        $controller->jsonResponse(['message' => 'Payment failed or was incomplete.'], false);
    }
}
