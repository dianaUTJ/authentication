<div>
    <br>
    <br>
    <h1> Completar compra</h1>
    <br>
    <form id="payment-form">

            <span>Your card</span>
            <!-- Stripe Elements Placeholder -->
            <div id="message"></div>
            <div id="error-message">
                <!-- Display error message to your customers here -->
            </div>
        <x-filament::button size="lg" type=submit>
            Pay
        </x-filament::button>
    </form>

</div>
@push('scripts')
    {{-- <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ env('STRIPE_KEY') }}')
       const clientSecret = new URLSearchParams(window.location.search).get(
  'payment_intent_client_secret'
);

// Retrieve the PaymentIntent
stripe.retrievePaymentIntent(clientSecret).then(({paymentIntent}) => {
  const message = document.querySelector('#message')

  // Inspect the PaymentIntent `status` to indicate the status of the payment
  // to your customer.
  //
  // Some payment methods will [immediately succeed or fail][0] upon
  // confirmation, while others will first enter a `processing` state.
  //
  // [0]: https://stripe.com/docs/payments/payment-methods#payment-notification
  switch (paymentIntent.status) {
    case 'succeeded':
      message.innerText = 'Success! Payment received.';
      break;

    case 'processing':
      message.innerText = "Payment processing. We'll update you when payment is received.";
      break;

    case 'requires_payment_method':
      message.innerText = 'Payment failed. Please try another payment method.';
      // Redirect your user back to your payment page to attempt collecting
      // payment again
      break;

    default:
      message.innerText = 'Something went wrong.';
      break;
  }
    </script> --}}
@endpush
