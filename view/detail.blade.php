@extends('layouts.master')
@section('content')
<div class="row mt-3">
    <!-- Hình ảnh sản phẩm -->
    <div class="col-md-6">
        <div class="d-flex flex-column align-items-center">
            <img id="mainImage" src="http://localhost:8000/<?php echo $product['image']; ?>" class="img-fluid rounded" alt="Product Image">
            <div class="d-flex mt-3">
                <?php foreach ($products_varriants as $variant): ?>
                    <img src="http://localhost:8000/<?php echo $variant['image']; ?>" class="img-thumbnail me-2 thumbnail" width="60" height="60" data-image="http://localhost:8000/<?php echo $variant['image']; ?>">
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Thông tin sản phẩm -->
    <div class="col-md-6">
        <h2 id="productName"><?php echo $product['name']; ?></h2>
        <p class="text-muted">Danh mục: <?php echo $product['category_name']; ?></p>
        <p class="text-muted" id="productSku">SKU: <?php echo $product['sku']; ?></p>

        <?php if (!empty($products_varriants)): ?>
            <div class="mb-3">
                <strong>Size:</strong>
                <div class="d-flex flex-wrap gap-2">
                    <?php foreach ($products_varriants as $variant): ?>
                        <button class="btn btn-outline-danger size-btn" data-size="<?php echo $variant['size_name']; ?>" data-color="<?php echo $variant['color_name']; ?>" data-price="<?php echo $variant['price']; ?>" data-quantity="<?php echo $variant['quantity']; ?>" data-sku="<?php echo $variant['sku']; ?>">
                            <?php echo $variant['size_name']; ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="mb-3">
                <strong>Màu sắc:</strong>
                <div class="d-flex flex-wrap gap-2">
                    <?php foreach ($products_varriants as $variant): ?>
                        <button class="btn btn-outline-danger color-btn" data-size="<?php echo $variant['size_name']; ?>" data-color="<?php echo $variant['color_name']; ?>">
                            <?php echo $variant['color_name']; ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <strong>Số lượng:</strong> <span id="productQuantity"><?php echo $product['quantity']; ?> cái</span>
        </div>
        <div class="mb-3">
            <strong>Giá tiền:</strong> <span id="productPrice" class="fs-4 text-danger"><?php echo number_format($product['price'], 0, ',', '.'); ?>đ</span>
        </div>

        <div class="mb-3 d-flex align-items-center">
            <strong class="me-2">Số lượng:</strong>
            <button class="btn btn-outline-secondary" onclick="updateQuantity(-1)">-</button>
            <input type="text" id="quantity" class="form-control text-center mx-2" value="1" style="width: 50px;">
            <button class="btn btn-outline-secondary" onclick="updateQuantity(1)">+</button>
        </div>

        <form action="/cart/create" method="POST">
            <input type="hidden" name="sku" id="sku_add_cart" value="<?php echo !empty($products_varriants) ? '' : $product['sku']; ?>">
            <input type="hidden" name="quantity" id="quantity_add_cart" value="1">
            <input type="hidden" name="price" id="price_add_cart" value="<?php echo !empty($products_varriants) ? '' : $product['price']; ?>">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <input type="hidden" name="variant_id" id="variant_id_add_cart" value="">
            <button class="btn btn-danger w-50">Thêm Vào Giỏ Hàng</button>
        </form>


    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sizeButtons = document.querySelectorAll(".size-btn");
        const colorButtons = document.querySelectorAll(".color-btn");
        const productPrice = document.getElementById("productPrice");
        const productQuantity = document.getElementById("productQuantity");
        const productSku = document.getElementById("productSku");
        const hiddenSku = document.getElementById("sku_add_cart");
        const hiddenPrice = document.getElementById("price_add_cart");
        const hiddenQuantity = document.getElementById("quantity_add_cart");
        const hiddenVariantId = document.getElementById("variant_id_add_cart");

        let selectedSize = null;
        let selectedColor = null;

        const variants = <?php echo json_encode($products_varriants); ?>;

        function updateVariant() {
            const matchedVariant = variants.find(variant =>
                variant.size_name === selectedSize && variant.color_name === selectedColor
            );

            if (matchedVariant) {
                productPrice.textContent = new Intl.NumberFormat("vi-VN").format(matchedVariant.price) + "đ";
                productQuantity.textContent = matchedVariant.quantity + " cái";
                productSku.textContent = "SKU: " + matchedVariant.sku;
                document.getElementById("mainImage").src = "http://localhost:8000/" + matchedVariant.image;

                // Cập nhật dữ liệu vào form
                hiddenSku.value = matchedVariant.sku;
                hiddenPrice.value = matchedVariant.price;
                hiddenQuantity.value = document.getElementById("quantity").value;
                hiddenVariantId.value = matchedVariant.id;
            } else {
                // Nếu không có variant, dùng dữ liệu sản phẩm chính
                hiddenSku.value = "<?php echo $product['sku']; ?>";
                hiddenPrice.value = "<?php echo $product['price']; ?>";
                hiddenQuantity.value = document.getElementById("quantity").value;
                hiddenVariantId.value = "";
            }
        }

        sizeButtons.forEach(button => {
            button.addEventListener("click", function() {
                sizeButtons.forEach(btn => btn.classList.remove("active"));
                selectedSize = this.getAttribute("data-size");
                this.classList.add("active");
                updateVariant();
            });
        });

        colorButtons.forEach(button => {
            button.addEventListener("click", function() {
                colorButtons.forEach(btn => btn.classList.remove("active"));
                selectedColor = this.getAttribute("data-color");
                this.classList.add("active");
                updateVariant();
            });
        });

        // Nếu không có variant, đặt giá trị mặc định từ sản phẩm chính khi tải trang
        if (variants.length === 0) {
            updateVariant();
        }
    });


    document.addEventListener("DOMContentLoaded", function() {
        const quantityInput = document.getElementById("quantity");
        const hiddenQuantity = document.getElementById("quantity_add_cart");

        function updateQuantity(change) {
            let newQuantity = parseInt(quantityInput.value) + change;
            if (newQuantity >= 1) {
                quantityInput.value = newQuantity;
                hiddenQuantity.value = newQuantity;
            }
        }

        document.querySelectorAll(".btn-outline-secondary").forEach(button => {
            button.addEventListener("click", function() {
                let change = this.textContent === "+" ? 1 : -1;
                updateQuantity(change);
            });
        });

        quantityInput.addEventListener("input", function() {
            let newQuantity = parseInt(quantityInput.value);
            if (isNaN(newQuantity) || newQuantity < 1) {
                quantityInput.value = 1;
                hiddenQuantity.value = 1;
            } else {
                hiddenQuantity.value = newQuantity;
            }
        });
    });
</script>
@endsection