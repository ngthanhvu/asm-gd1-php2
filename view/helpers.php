<?php

function renderView($view, $data = [], $title = "My App", $layout = "master")
{
    // Ra biến từ bảng thành đơn
    extract($data);
    ob_start();
    require $view;
    $content = ob_get_clean();

    // Sử dụng layout tương ứng
    require "view/layouts/{$layout}.php";
}
