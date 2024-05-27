<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">



</head>

<body class="font-sans antialiased">
    <div style="display: flex; gap:2rem">
        @foreach ($products as $product)
            <div class="flex:1">
                <img src="{{ $product->image }}" style="width: 100%">
                <h1>{{ $product->name }}</h1>
                <p>{{ $product->price }}</p>
            </div>
            <hr>
        @endforeach
    </div>
    <p>
    <form action="{{ route('checkout') }}" method="POST">
        @csrf
        <button>Checkout</button>
    </form>
    </p>

</body>

</html>
