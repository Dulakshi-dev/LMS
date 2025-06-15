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

            Logger::info("Fetching payments", [
                'page' => $page,
                'memberId' => $memberId,
                'transactionId' => $transactionid
            ]);

            if (!empty($memberId) || !empty($transactionid)) {
                $paymentsData = PaymentModel::searchPayments($memberId, $transactionid, $page, $resultsPerPage);
                Logger::info("Payments search performed", [
                    'memberId' => $memberId,
                    'transactionId' => $transactionid,
                    'resultsCount' => count($paymentsData['results'] ?? [])
                ]);
            } else {
                $paymentsData = PaymentModel::getAllPayments($page, $resultsPerPage);
                Logger::info("All payments fetched", [
                    'page' => $page,
                    'resultsCount' => count($paymentsData['results'] ?? [])
                ]);
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
            Logger::warning("Invalid request to getAllPayments: not a POST request");
            $this->jsonResponse(["message" => "Invalid request."], false);
        }
    }
}
