@extends('layouts.master')

@section('content')
    <div class="mt-5">
        <div class="row">
            <!-- Filter Column -->
            <div class="col-md-3">
                <h4>Filters</h4>
                <div class="list-group">
                    @forelse ($categories as $category)
                        <button type="button" class="list-group-item list-group-item-action">{{ $category['name'] }}</button>
                    @empty
                        <div class="list-group-item list-group-item-action">Không tìm thấy danh mục</div>
                    @endforelse
                </div>
                <div class="mt-4">
                    <h5>Price Range</h5>
                    <input type="range" class="form-range" min="0" max="1000" step="10">
                    <div class="d-flex justify-content-between">
                        <span>$0</span>
                        <span>$1000</span>
                    </div>
                </div>
            </div>

            <!-- Products Column -->
            <div class="col-md-9">
                <div class="d-flex justify-content-end align-items-center mb-3">
                    <select class="form-select w-auto">
                        <option selected>Sort by</option>
                        <option value="1">Price: Low to High</option>
                        <option value="2">Price: High to Low</option>
                        <option value="3">Newest</option>
                    </select>
                </div>
                <div class="row">
                    @forelse ($products as $product)
                        <div class="col-md-3 mb-3">
                            <div class="card border-0 hover-card" style="width: 13rem;">
                                <a href="/detail/{{ $product['id'] }}" class="text-decoration-none text-success">
                                    <img src="{{ $product['image'] }}" class="card-img-top" alt="No images"
                                        style="object-fit: contain;">
                                    <div class="mt-2">
                                        <h5 class="card-title">{{ $product['name'] }}</h5>
                                        <span class="badge text-bg-success">Số lượng: {{ $product['quantity'] }} cái</span>
                                        <span class="badge text-bg-danger ms-2">{{ $product['category_name'] }}</span><br>
                                        <div class="prices mt-2">
                                            <span>{{ number_format($product['price'], 0, ',', '.') }}đ</span>
                                            <span class="text-muted text-decoration-line-through ms-2">20.000đ</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center mb-4">Không tìm thấy sản phẩm</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
