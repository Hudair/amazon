<script src="https://js.stripe.com/v3/"></script>
<script>
    var style = {
        base: {
          color: "#32325d",
          border: "1px solid #ccc",
          fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
          fontSmoothing: "antialiased",
          fontSize: "16px",
          "::placeholder": {
            color: "#aab7c4"
          }
        },
        invalid: {
          color: "#fa755a",
          iconColor: "#fa755a"
        }
      };

    const stripe = Stripe("{{ config('services.stripe.key') }}");
    const elements = stripe.elements();
    const cardElement = elements.create('card', { style: style });

    cardElement.mount('#card-element');

    const cardHolderName = document.getElementById('card-holder-name');
    const cardButton = document.getElementById('card-button');
    const clientSecret = cardButton.dataset.secret;

    cardButton.addEventListener('click', async (e) => {
        e.preventDefault();

        var parentNode = $('.login-box').length > 0 ? $(".login-box") : $(".wrapper");

        $('.loader').show();
        parentNode.addClass('blur-filter');

        const { setupIntent, error } = await stripe.handleCardSetup(
            clientSecret, cardElement, {
                payment_method_data: {
                    billing_details: { name: cardHolderName.value }
                }
            }
        );

        if (error) {
          $('.loader').hide();
          parentNode.removeClass('blur-filter');

          $('#stripe-errors').text(error.message).removeClass('hide');
        }
        else {
          var form = $("#stripe-form");

          // append the token to the form
          form.append($('<input type="hidden" name="payment">').val(setupIntent.payment_method));

          // submit the form
          form.get(0).submit();
        }
    });
</script>