<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\MailModel;
use App\Core\BladeServiceProvider;

class OrderController
{
    private $orderModel;
    private $mailModel;
    private $productModel;

    public function  __construct()
    {
        $this->orderModel = new OrderModel();
        $this->mailModel = new MailModel();
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        $title = 'Đơn hàng';
        $orders = $this->orderModel->getAllOrders();
        BladeServiceProvider::render('admin.orders.index', compact('orders', 'title'));
    }

    public function createOrder()
    {
        $user_id = $_SESSION['user']['id'];
        $status = "pending";
        $payment_method = $_POST['payment_method'];
        $total_amount = $_POST['total_amount'];
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $code = rand(100000, 999999);

        $compact_address = "$name, $phone, $address, $email";

        $_SESSION['order_data'] = [
            'user_id' => $user_id,
            'status' => $status,
            'payment_method' => $payment_method,
            'total_amount' => $total_amount,
            'compact_address' => $compact_address,
            'email' => $email,
            'code' => $code
        ];

        if ($payment_method == "cod") {
            $order_id = $this->orderModel->createOrder($user_id, $status, $payment_method, $total_amount, $compact_address, $code);
            $this->mailModel->send($email, "Xác nhận đơn hàng", "mail_order", ['order_id' => $order_id, 'code' => $code]);
            var_dump("Created Order ID:", $order_id);
            $products = $this->orderModel->getOrderDetailsById($order_id);
            if (is_array($products) && !empty($products)) {
                foreach ($products as $product) {
                    $this->productModel->UpdateQuanityAfterBuy($product['id'], $product['quantity']);
                }
            } else {
                echo "Không có sản phẩm nào trong đơn hàng!";
            }
            header('Location: /success?code=' . $code);
        } elseif ($payment_method == "vnpay") {
            echo '<form id="vnpayForm" action="/payment/create" method="POST">';
            echo '<input type="hidden" name="amount" value="' . $total_amount . '">';
            echo '</form>';
            echo '<script>document.getElementById("vnpayForm").submit();</script>';
        } elseif ($payment_method == "momo") {
            echo '<form id="momoForm" action="/payment/momo/create" method="POST">';
            echo '<input type="hidden" name="amount" value="' . $total_amount . '">';
            echo '</form>';
            echo '<script>document.getElementById("momoForm").submit();</script>';
        }
    }

    public function updateStatus()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $status = $_POST['status'];
            $order_id = $_POST['order_id'];

            $shipping_address = $this->orderModel->updateOrder($order_id, $status);

            $parts = explode(", ", $shipping_address);

            $email = end($parts);

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->mailModel->send(
                    $email,
                    "Cập nhật trạng thái đơn hàng",
                    "mail_status",
                    [
                        'name' => 'Khách hàng',
                        'order_id' => $order_id,
                        'status' => $status
                    ]
                );
            } else {
                var_dump("Invalid email format!");
            }

            $_SESSION['message_orders'] = "Order status updated successfully!";
            header('Location: /admin/orders');
        }
    }


    public function deleteOrder($order_id)
    {
        $this->orderModel->detailOrder($order_id);
        $_SESSION['message_orders'] = "Order deleted successfully!";
        header('Location: /admin/orders');
    }

    public function getOrdersById($id)
    {
        // $orders = $this->orderModel->getOrderById($id);
        $orders = $this->orderModel->detailOrder($id);
        if ($orders) {
            echo json_encode($orders);
        } else {
            echo json_encode(['success' => false]);
        }
    }
}
