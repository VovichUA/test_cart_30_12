@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Shopping Cart</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(count($cart) > 0)
            <table class="table">
                <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($cart as $item)
                    <tr>
                        <td>{{ $item->product->name ?? $item['name'] }}</td>
                        <td>${{ $item->product->price ?? $item['price'] }}</td>
                        <td>
                            <form action="{{ route('cart.update', $item->id ?? $item['product_id']) }}" method="POST">
                                @csrf
                                <input
                                        type="number"
                                        id="quantity_{{ $item->id }}"
                                        name="quantity"
                                        value="{{ $item->quantity ?? $item['quantity'] }}"
                                        min="1"
                                        required
                                >
                                <button
                                        type="submit"
                                        data-id="{{ $item->id }}"
                                        class="btn btn-sm btn-primary update-cart">
                                    Update
                                </button>
                            </form>
                        </td>
                        <td>${{ ($item->product->price ?? $item['price']) * ($item->quantity ?? $item['quantity']) }}</td>
                        <td>
                            <form action="{{ route('cart.remove', $item->id ?? $item['product_id']) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p>Your cart is empty.</p>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('.update-cart').on('click', function () {

            })
        })
    </script>
@endsection
