<?php


class PaymentController extends Controller  // Controller that manages payment-related actions
{
    private $paymentModel;

    public function __construct()
    {
        require_once Config::getModelPath('staff', 'paymentmodel.php');
        $this->paymentModel = new PaymentModel();
    }

public function getAllPayments() // Get all payments (with search + pagination support)
{
    $resultsPerPage = 10;
    if ($this->isPost()) {
        $page = $this->getPost('page', 1);
        $memberId = $this->getPost('memberid');
        $transactionid = $this->getPost('transactionid');
        $paymentType = $this->getPost('paymentType');

        Logger::info("Fetching payments", [
            'page' => $page,
            'memberId' => $memberId,
            'paymentType' => $paymentType,
            'transactionId' => $transactionid
        ]);

        if (!empty($memberId) || !empty($transactionid) || !empty($paymentType)) {
            $paymentsData = PaymentModel::searchPayments($memberId, $transactionid, $paymentType, $page, $resultsPerPage);
        } else {
            $paymentsData = PaymentModel::getAllPayments($page, $resultsPerPage);
        }

        $payments = $paymentsData['results'] ?? [];
        $total = $paymentsData['total'] ?? 0;
        $totalPages = ceil($total / $resultsPerPage);

        // Calculate total amount
        $totalAmount = 0;
        foreach ($payments as $payment) {
            $totalAmount += floatval($payment['amount']);
        }

        $this->jsonResponse([ // Send response back as JSON
            "payments" => $payments,
            "total" => $total,
            "totalAmount" => $totalAmount, 
            "totalPages" => $totalPages,
            "currentPage" => $page
        ]);
    } else {
        Logger::warning("Invalid request to getAllPayments: not a POST request");
        $this->jsonResponse(["message" => "Invalid request."], false);
    }
}

}
