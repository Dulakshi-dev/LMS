<?php

class StaffPaymentController
{
    private $staffPaymentModel;


    public function __construct()
    {
        require_once Config::getModelPath('staffpaymentmodel.php');
        $this->staffPaymentModel = new StaffPaymentModel();

    }

    public function getAllPayments()
    {
        $resultsPerPage = 10;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
            $memberId = $_POST['memberid'] ?? null;
            $transactionid = $_POST['transactionid'] ?? null;

            if (!empty($memberId) || !empty($transactionid)) {
                $paymentsData = StaffPaymentModel::searchPayments($memberId, $transactionid, $page, $resultsPerPage);
             }else {
                $paymentsData = StaffPaymentModel::getAllPayments($page, $resultsPerPage);
            }
        
            $payments = $paymentsData['results'] ?? [];
            $total = $paymentsData['total'] ?? 0;
            $totalPages = ceil($total / $resultsPerPage);
        
            echo json_encode([
                "success" => true,
                "payments" => $payments,
                "total" => $total,
                "totalPages" => $totalPages,
                "currentPage" => $page
            ]);

        }else{
            echo json_encode(["success" => false, "message" => "Invalid request."]);

        }
    }

}
