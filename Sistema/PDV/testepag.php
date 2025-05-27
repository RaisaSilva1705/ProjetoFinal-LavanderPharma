<script src="https://js.stripe.com/v3/"></script>

<form id="payment-form">
  <div id="card-element"><!-- Stripe Card Element --></div>
  <button type="submit">Pagar</button>
  <div id="card-errors"></div>
</form>

<script>
  const stripe = Stripe('pk_test_51RSlge4ToMOv4RGlhw0FyiV720VdnyMmTlaToeMHHT7bioNw3SFqOlcKtwLKGBp9twSQaRr8XYlaExMcNKKeUyYQ00IWoP1VGH');
  const elements = stripe.elements();
  const card = elements.create('card');
  card.mount('#card-element');

  const form = document.getElementById('payment-form');

  form.addEventListener('submit', async (event) => {
    event.preventDefault();

    // Ajuste a URL para sua real rota do backend que gera o clientSecret
    const response = await fetch('http://localhost/htdocs/Farmácia/Dev/Exec/stripe_pagamento.php');

    if (!response.ok) {
      console.error('Erro ao buscar clientSecret:', response.statusText);
      return;
    }

    const data = await response.json();

    if (!data.clientSecret) {
      console.error('clientSecret não retornado:', data);
      return;
    }

    const { paymentIntent, error } = await stripe.confirmCardPayment(data.clientSecret, {
      payment_method: {
        card: card
      }
    });

    if (error) {
      document.getElementById('card-errors').textContent = error.message;
    } else {
      alert('Pagamento efetuado com sucesso!');
      console.log(paymentIntent);
    }
  });
</script>
