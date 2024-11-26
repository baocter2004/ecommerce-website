@extends('client.layouts.master')

@section('content')
    <div class="bg-light py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-0">
                    <a href="{{ route('client.index') }}">Home</a>
                    <span class="mx-2 mb-0">/</span>
                    <a href="{{ route('client.cart') }}">Cart</a>
                    <span class="mx-2 mb-0">/</span>
                    <strong class="text-black">Checkout</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="site-section">
        <div class="container">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('client.checkout') }}" method="POST">
                @csrf
                <div class="row mb-5">
                    <div class="col-md-6">
                        <h2 class="h3 mb-3 text-black">Billing Details</h2>
                        <div class="p-3 p-lg-5 border">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="name" class="text-black">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', $dataUser ? $dataUser->name : '') }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="shipping_address" class="text-black">Shipping Address <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="shipping_address" name="shipping_address"
                                        placeholder="Street address" value="{{ old('shipping_address') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control" name="appartment"
                                    placeholder="Apartment, suite, unit etc. (optional)" value="{{ old('appartment') }}">
                            </div>

                            <div class="form-group row mb-5">
                                <div class="col-md-6">
                                    <label for="email" class="text-black">Email Address <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email', $dataUser ? $dataUser->email : '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="text-black">Phone <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        placeholder="Phone Number"
                                        value="{{ old('phone', $dataUser ? $dataUser->phone : '') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="order_note" class="text-black">Order Notes</label>
                                <textarea name="order_note" id="order_note" cols="30" rows="5" class="form-control"
                                    placeholder="Write your notes here...">{{ old('order_note') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h2 class="h3 mb-3 text-black">Your Order</h2>
                        <div class="p-3 p-lg-5 border">
                            <table class="table site-block-order-table mb-5">
                                <thead>
                                    <th>Product</th>
                                    <th>Total</th>
                                </thead>
                                <tbody>
                                    @foreach ($cart->items as $item)
                                        <tr>
                                            <td>{{ $item->product->name }} <strong class="mx-2">x</strong>
                                                {{ $item->quantity }}</td>
                                            <td>{{ number_format($item->price * $item->quantity, 2) }} VND</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td class="text-black font-weight-bold"><strong>Cart Subtotal</strong></td>
                                        <td class="text-black">
                                            {{ number_format($cart->items->sum(fn($item) => $item->price * $item->quantity), 2) }}
                                            VND
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-black font-weight-bold"><strong>Total</strong></td>
                                        <td class="text-black font-weight-bold">
                                            <strong>{{ number_format($cart->items->sum(fn($item) => $item->price * $item->quantity), 2) }}VND</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="form-group">
                                <label for="discount_code" class="text-black mb-3">Discount Code</label>
                                <input type="text" class="form-control" id="discount_code" name="discount_code"
                                    placeholder="Enter your code">
                            </div>

                            <div class="form-group">
                                <label for="payment_method" class="text-black">Payment Method</label>
                                <select class="form-control" id="payment_method" name="payment_method">
                                    <option value="cash_payment">Cash Payment</option>
                                </select>
                            </div>                            

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">Place Order</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
