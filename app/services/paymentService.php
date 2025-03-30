<?php
require_once '../../main.php'; // Include database connection

$libraryData = HomeModel::getLibraryInfo();

class PaymentService
{
    private $merchantId;
    private $merchantSecret;
    private $sandboxUrl;

    public function __construct()
    {
        $this->merchantId = "1227820"; // Your PayHere Merchant ID
        $this->merchantSecret = "MjkxNDc2MDQyOTIzOTM4NDQ5NDUzNTY2NzU3ODU0MTAxMTY3Njg1OA"; // Your PayHere Merchant Secret Key
        $this->sandboxUrl = "https://sandbox.payhere.lk/pay/checkout"; // Sandbox URL
     }

    public function createPayment()
    {
        $libraryData = HomeModel::getLibraryInfo();
        $libraryName = $libraryData['name']; 
        $libraryAddress = $libraryData['address']; 
        $libraryEmail = $libraryData['email']; 
        $libraryPhone = $libraryData['mobile']; 
        $libraryFee = $libraryData['membership_fee']; 

            $merchantId = "1227820"; 
            $merchantSecret = "MjkxNDc2MDQyOTIzOTM4NDQ5NDUzNTY2NzU3ODU0MTAxMTY3Njg1OA=="; 
            $membershipFee = $libraryFee; // Fixed annual membership fee
            $currency = "LKR"; 
            $orderId = uniqid(); 
        
            $hash = strtoupper(md5($merchantId . $orderId . number_format($membershipFee, 2, '.', '') . $currency . strtoupper(md5($merchantSecret)))); 
        
            $payment = []; 
            $payment["sandbox"] = true; 
            $payment["merchant_id"] = $merchantId; 
            $payment["return_url"] = "http://localhost/library/membership-success.php"; 
            $payment["cancel_url"] = "http://localhost/library/membership-cancel.php"; 
            $payment["notify_url"] =  Config::indexPathMember() . "?action=payment_notify";
            $payment["order_id"] = $orderId; 
            $payment["items"] = "Annual Membership Fee"; 
            $payment["amount"] = number_format($membershipFee, 2, '.', ''); 
            $payment["currency"] = $currency; 
            $payment["hash"] = $hash; 
            $payment["first_name"] = $libraryName; 
            $payment["last_name"] = ""; 
            $payment["email"] = $libraryEmail; 
            $payment["phone"] = $libraryPhone; 
            $payment["address"] = $libraryAddress; 
            $payment["city"] = "Colombo"; 
            $payment["country"] = "Sri Lanka"; 
        
            $json = ["status" => "success", "payment" => $payment]; 
        
        
        echo json_encode($json); 
        
    }
}
?>
