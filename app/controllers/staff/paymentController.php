<?php

class PaymentController extends Controller
{
    private $paymentModel;

    public function __construct()
    {
        require_once Config::getModelPath('staff', 'paymentmodel.php');
        $this->paymentModel = new PaymentModel();
    }

    public function getAllPayments()
    {
        $resultsPerPage = 10;
        if ($this->isPost()) {
            $page = $this->getPost('page', 1);
            $memberId = $this->getPost('memberid');
            $transactionid = $this->getPost('transactionid');

            if (!empty($memberId) || !empty($transactionid)) {
                $paymentsData = PaymentModel::searchPayments($memberId, $transactionid, $page, $resultsPerPage);
            } else {
                $paymentsData = PaymentModel::getAllPayments($page, $resultsPerPage);
            }

            $payments = $paymentsData['results'] ?? [];
            $total = $paymentsData['total'] ?? 0;
            $totalPages = ceil($total / $resultsPerPage);

            $this->jsonResponse([
                "payments" => $payments,
                "total" => $total,
                "totalPages" => $totalPages,
                "currentPage" => $page
            ]);
        } else {
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }
}
