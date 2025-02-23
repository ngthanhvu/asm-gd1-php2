@extends('layouts.master')
@section('content')
    <div class="container mt-5 text-center">
        <i class="bi bi-check-circle-fill text-success" style="font-size: 80px;"></i>
        <h1 class="text-success fw-bold mt-3">Giao dịch thành công!</h1>
        @if (isset($_GET['code']))
            <p class="text-center">Mã đơn hàng: {{ $_GET['code'] }}</p>
        @else
            <p class="text-center"></p>
        @endif
        <div class="mt-4">
            <a href="/" class="btn btn-success me-2"><i class="fa-solid fa-house-chimney"></i> Về trang chủ</a>
            <a href="/profile#history" class="btn btn-outline-secondary"><i class="fa-regular fa-clock"></i> Xem lịch
                sử</a>
        </div>
    </div>
@endsection
