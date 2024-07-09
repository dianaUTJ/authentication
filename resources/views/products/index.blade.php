<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    @vite('resources/css/app.css')

</head>

<body class="font-sans antialiased" style="margin: 2%">
    @if (\Session::has('success'))
        <div class="alert alert-success bg-green-200 border border-green-600 text-green-800 px-4 py-3 rounded relative" role="alert">
            <p>{{ \Session::get('success') }}</p>
        </div>
        </div>
    @elseif (\Session::has('error'))
        <div class="alert alert-danger bg-red-200 border border-red-600 text-red-800 px-4 py-3 rounded relative" role="alert">
            <p>{{ \Session::get('error') }}</p>
        </div>
        </div>
    @endif
    <h1>Products</h1>
    <div class="grid grid-cols-5 gap-4 mt-8">
        @foreach ($products as $product)
            <div class="bg-white shadow-md rounded-lg p-4">
                <img src="{{ $product->image }}" class="w-full">
                <p class="text-gray-800 font-bold mt-2">{{ $product->name }}</p>
                <p class="text-gray-600">{{ $product->price }}</p>
                <form action="{{ route('addToCart') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">
                        Add to Cart
                    </button>
                </form>
            </div>
        @endforeach
    </div>
    <p>
        <br>
    <form action="{{ route('cart') }}" method="POST">
        @csrf
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Checkout
        </button>
        </form>
    </p>
    <!-- Display a payment form -->
    <div id="checkout">
        <!-- Checkout will insert the payment form here -->
      </div>
      <input id="card-holder-name" type="text">

      <!-- Stripe Elements Placeholder -->
      <div id="card-element"></div>
      <div id="checkout">
        <!-- Checkout will insert the payment form here -->
        </div>
      <button id="card-button">
          Process Payment
      </button>
    </body>
    <script src="https://js.stripe.com/v3/"></script>

    <script>
        const stripe = Stripe(env('STRIPE_PUBLIC_KEY'));

        const elements = stripe.elements();
        const cardElement = elements.create('card');

        cardElement.mount('#card-element');
        checkout.mount('#checkout');

    </script>
</html>
