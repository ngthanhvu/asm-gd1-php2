@extends('layouts.admin')
@section('content')
    <div class="p-3 mb-4 rounded-3 bg-light">
        <h2>Tạo kích cỡ</h2>
    </div>
    <div class="p-3 mb-4 rounded-3 bg-light w-50 mx-auto">
        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Tên kích cỡ</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Nhập tên kích cỡ">
                <?php
                if (isset($errors['name'])) {
                    echo '<p class="text-danger">' . $errors['name'] . '</p>';
                }
                ?>
            </div>
            <button type="submit" class="btn btn-primary">Tạo kích cỡ</button>
        </form>
    </div>
@endsection
