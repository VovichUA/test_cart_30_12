@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Products</h1>
        <div class="row">
            @foreach($products as $product)
                <div class="col-md-12">
                    <div class="card mb-4">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">{{ $product->description }}</p>
                            <p class="card-text"><strong>Price:</strong> ${{ $product->price }}</p>
                            <button
                                    type="submit"
                                    data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}"
                                    data-price="{{ $product->price }}"
                                    class="btn btn-primary add-to-cart">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('.add-to-cart').on('click', function () {
                let productId = $(this).data('id');
                let productName = $(this).data('name');
                let productPrice = $(this).data('price');

                $.ajax({
                    url: '{{ route('cart.add') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_id: productId,
                        name: productName,
                        price: productPrice
                    },
                    success: function (response) {
                        alert(productName + ' was add!');
                    },
                    error: function (xhr) {
                        alert('Something went wrong!');
                    }
                });
            });
        });
    </script>
@endsection
