<?php
require_once "view/helpers.php";
require_once "model/PostModel.php";
require_once "model/ProductModel.php";

class Controller
{
    private $postModel;
    private $productModel;

    public function __construct()
    {
        $this->postModel = new PostModel();
        $this->productModel = new ProductModel();
    }
    public function index()
    {
        $posts = $this->postModel->getAllPosts();
        $products = $this->productModel->getAllProducts();
        renderView('view/index.php', compact('posts', 'products'), 'Home');
    }

    public function admin()
    {
        $posts = $this->postModel->getAllPosts();
        $products = $this->productModel->getAllProducts();
        renderView('view/admin/index.php', compact('posts', 'products'), 'Admin', 'admin');
    }

    public function payment()
    {
        renderView('view/payment.php', [], 'Payment');
    }

    public function product()
    {
        $products = $this->productModel->getAllProducts();
        renderView('view/product.php', compact('products'), 'Product');
    }

    public function detail()
    {
        renderView('view/detail.php', [], 'Detail');
    }
}
