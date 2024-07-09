<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Accept a payment</title>
    <meta name="description" content="A demo of a payment on Stripe" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="style.css" />
    <script src="https://js.stripe.com/v3/"></script>
    <script >
    // This is your test publishable API key.
const stripe = Stripe("pk_test_51PK21y03gK3LJhEqnfmGeuH7Rq7MLy51m5ZI9SUzrWekRizW7UKm3LAy4KnYdvbr1A9sAaLLIBQfWehqIE10er84007lIzRSio");

initialize();

// Create a Checkout Session
async function initialize() {
  const fetchClientSecret = async () => {
    const response = await fetch(route("checkout"), {
      method: "POST",
    });
    const { clientSecret } = await response.json();
    return clientSecret;
  };

  const checkout = await stripe.initEmbeddedCheckout({
    fetchClientSecret,
  });

  // Mount Checkout
  checkout.mount('#checkout');
}</script>
  </head>
  <body>
    <!-- Display a payment form -->
      <div id="checkout">
        <!-- Checkout will insert the payment form here -->
      </div>
  </body>
</html>
