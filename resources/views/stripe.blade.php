@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@else (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<form action="/stripe/checkout" method="POST">
    <input type="hidden" name="_token" value="{{csrf_token()}}" />
    <button type="submit" class="btn btn-primary">Checkout</button>
</form>
