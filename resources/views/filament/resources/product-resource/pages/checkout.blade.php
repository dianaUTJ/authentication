@vite('resources/css/app.css')

<x-filament-panels::page>
    <div>
        <br>

        <form id="payment-form">

            <!-- Stripe Elements Placeholder -->
            <div id="payment-element"></div>
            <br>
            <div id="error-message" class="bg-red-300">
                <!-- Display error message to your customers here -->
            </div>

            <br>
            <x-filament::button size="lg" type=submit>
                Pagar
            </x-filament::button>
        </form>

    </div>
    @push('scripts')
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            const stripe = Stripe('{{ env('STRIPE_KEY') }}')
            const elements = stripe.elements({
                clientSecret: '{{ $clientSecret }}'
            })
            const cardElement = elements.create('payment')

            cardElement.mount('#payment-element')

            const form = document.getElementById('payment-form');

            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                const {
                    error
                } = await stripe.confirmPayment({
                    //`Elements` instance that was used to create the Payment Element
                    elements,
                    confirmParams: {
                        return_url: 'https://authentication.test/admin/checkout-success',
                    },
                });

                if (error) {
                    // This point will only be reached if there is an immediate error when
                    // confirming the payment. Show error to your customer (for example, payment
                    // details incomplete)
                    const messageContainer = document.querySelector('#error-message');
                    messageContainer.textContent = error.message;
                } else {
                    // Your customer will be redirected to your `return_url`. For some payment
                    // methods like iDEAL, your customer will be redirected to an intermediate
                    // site first to authorize the payment, then redirected to the `return_url`.

                }
            });
        </script>
    @endpush
</x-filament-panels::page>
