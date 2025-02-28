@extends('layouts.master')
@section('content')
<div class="border p-4 rounded w-50 mx-auto">
    <form method="POST">
        <h3 class="text-center mb-4">Forgot Password</h3>
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
        </div>
        <button type="submit" class="btn w-100 text-white" style="background-color: #FE5722;">Send Reset Link</button>
    </form>
</div>

@endsection