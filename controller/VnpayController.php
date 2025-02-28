<?php

namespace App\Controllers;

use App\Core\BladeServiceProvider;
use App\Models\OrderModel;
use App\Models\MailModel;

class VnPayController
{
    private $vnp_TmnCode;
    private $vnp_HashSecret;
    private $vnp_Url;
    private $vnp_Returnurl;
    private $orderModel;
    private $mailModel;


    public function __construct()
    {
        $this->vnp_TmnCode = $_ENV['VNPAY_TMN_CODE'];
        $this->vnp_HashSecret = $_ENV['VNPAY_HASH_SECRET'];
        $this->vnp_Url = $_ENV['VNPAY_URL'];
        $this->vnp_Returnurl = $_ENV['VNPAY_RETURN_URL'];
        $this->orderModel = new OrderModel();
        $this->mailModel = new MailModel();
    }

    public function createPayment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $order_id = time();
            $order_amount = $_POST['amount'];

            $vnp_Params = array(
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $this->vnp_TmnCode,
                "vnp_Amount" => $order_amount * 100,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $_SERVER['REMOTE_ADDR'],
                "vnp_Locale" => "vn",
                "vnp_OrderInfo" => "Thanh toan don hang #$order_id",
                "vnp_OrderType" => "billpayment",
                "vnp_ReturnUrl" => $this->vnp_Returnurl,
                "vnp_TxnRef" => $order_id
            );

            ksort($vnp_Params);
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($vnp_Params as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&';
                    $query .= '&';
                }
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $query .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }

            $vnp_SecureHash = hash_hmac('sha512', $hashdata, $this->vnp_HashSecret);
            $query .= '&vnp_SecureHash=' . $vnp_SecureHash;

            $paymentUrl = $this->vnp_Url . "?" . $query;
            header('Location: ' . $paymentUrl);
            exit;
        }
    }

    public function vnpayReturn()
    {
        $inputData = $_GET;
        $secureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);

        $hashData = "";
        foreach ($inputData as $key => $value) {
            $hashData .= urlencode($key) . "=" . urlencode($value) . "&";
        }
        $hashData = rtrim($hashData, "&");

        $checkSum = hash_hmac('sha512', $hashData, $this->vnp_HashSecret);

        if ($checkSum === $secureHash) {
            if ($inputData['vnp_ResponseCode'] == '00') {
                echo "Giao dịch thanh cong";
                $amount = $inputData['vnp_Amount'] / 100;
                $code = $_SESSION['order_data']['code'];
                $order_id = $this->orderModel->createOrder(
                    $_SESSION['order_data']['user_id'],
                    "completed",
                    $_SESSION['order_data']['payment_method'],
                    $amount,
                    $_SESSION['order_data']['address_id'],
                    $_SESSION['order_data']['new_address_data'],
                    $code
                );
                $this->mailModel->send(
                    $_SESSION['order_data']['email'],
                    "Xác nhận đơn hàng",
                    "mail_order",
                    ['order_id' => $order_id, 'code' => $code]
                );
                unset($_SESSION['order_data']);
                header("Location: /success?code=$code");
            } else {
                header("Location: /errors");
                echo "Giao dịch không thành công.";
            }
        } else {
            echo "Chữ ký không hợp lệ!";
        }
    }
}
