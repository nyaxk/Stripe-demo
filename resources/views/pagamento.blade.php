<?php
$req = Request();
use Illuminate\Support\Facades\Crypt;

if (!$req->has('total') || !$req->has('nome-do-produto')) {
redirect('/');
}

$produtoInfo = [
'valor' => preg_replace('/[^\d]/', '', $req->input('total')),
'nome' => $req->input('nome-do-pedido'),
];

if ($produtoInfo['valor'] <= 0.0) { redirect('/'); } ?> <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="{{ asset('styles/pagamento.css') }}">
        <script defer src="https://use.fontawesome.com/releases/v5.14.0/js/all.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
        <script src="https://js.stripe.com/v3/"></script>
        <script src="https://polyfill.io/v3/polyfill.min.js?version=3.52.1&features=fetch"></script>
        <title>Stripe gateway</title>
    </head>

    <body>
        <div class="notification is-info">
            Essa aplicação está no modo <strong>SANDBOX</strong>, utilize algum <a
                href="https://www.userede.com.br/desenvolvedores/pt/produto/e-Rede#tutorial-cartao"
                target="_blank">DESTES</a>
            cartões de créditos para testar a integração, caso queira teste os erros de pagamento, <a
                href="https://www.userede.com.br/desenvolvedores/pt/produto/e-Rede#tutorial-erros"
                target="_blank">AQUI</a> está a lista
            de
            <strong>VALORES</strong> e seus respectivos <strong>ERROS</strong>
        </div>
        <form class="payment">
            <div class="box is-main">
                <div class="box-head">
                    <p class="subtitle is-4 is-box-head-title"><strong>PAGAMENTO</strong></p>
                </div>
                <div class="box">
                    <div id="box-body" class="box-body">
                        <input class="input-spacing" type="text" id="email" placeholder="Email address" />
                        <div class="card-wrapper"></div>
                        <div id="card-element"></div>
                        <button id="submit">
                            <div class="spinner hidden" id="spinner"></div>
                            <span id="button-text">Pay</span>
                        </button>

                    </div>
                </div>
            </div>
        </form>
        <div id="modal" class="modal">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title" id="modal-title"><strong></strong></p>
                </header>
                <section class="modal-card-body">
                    <span class="subtitle is-5 is-modal" id="modal-message"></span>
                </section>
                <footer class="modal-card-foot">
                    <button id="delete" class="button is-ok"><strong>Fechar</strong></button>
                </footer>
            </div>
        </div>
        <script src="{{ asset('styles/card.js') }}"></script>
        <script>
            var stripe = Stripe(
                "pk_test_51HC9gZBouUwG68emQeb1kYVT3Q75NpzFpcJyapp8ScJdJNSsBUnOfNdKSHwmVhD2oZFg5ywNvkQdMS3eh0gRtSBq00zIdjk3DS"
            );
            var purchase = {
                product: "{{ $produtoInfo['nome'] }}",
                productData: "<?= Crypt::encryptString(json_encode($produtoInfo)) ?>"
            };
            document.querySelector("button").disabled = true;
            fetch('/api/create', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(purchase)
            }).then((result) => {
                return result.json();
            }).then((data) => {
                var elements = stripe.elements();
                var style = {
                    base: {
                        color: "#32325d",
                        fontFamily: 'Arial, sans-serif',
                        fontSmoothing: "antialiased",
                        fontSize: "16px",
                        "::placeholder": {
                            color: "#32325d"
                        }
                    },
                    invalid: {
                        fontFamily: 'Arial, sans-serif',
                        color: "#fa755a",
                        iconColor: "#fa755a"
                    }
                };
                var card = elements.create("card", {
                    style: style
                });
                card.mount("#card-element");
                card.on("change", function(event) {
                    document.querySelector("button").disabled = event.empty;
                    if (event.error) {
                        document.getElementById('modal').classList.add('is-active');
                        document.getElementById('modal-title').innerHTML = "Error";
                        document.getElementById('modal-message').innerHTML = event.error.message;
                    }
                });
                var form = document.querySelector('.payment');
                form.addEventListener("submit", (event) => {
                    event.preventDefault();
                    stripe.confirmCardPayment(data.clientSecret, {
                            receipt_email: document.getElementById('email').value,
                            payment_method: {
                                card: card
                            }
                        })
                        .then((result) => {
                            if (result.error) {
                                document.getElementById('modal').classList.add('is-active');
                                document.getElementById('modal-title').innerHTML = "Error";
                                document.getElementById('modal-message').innerHTML = result.error
                                    .message;
                            } else {
                                console.log(result);
                                document.getElementById('modal').classList.add('is-active');
                                document.getElementById('modal-title').innerHTML = "Success";
                                document.getElementById('modal-message').innerHTML =
                                    `Payment succeeded, see the result in your
                                <a href=https://dashboard.stripe.com/test/payments/"${result.paymentIntent.id}" target="_blank">Stripe dashboard.</a> Refresh the page to pay again.`;
                                document.querySelector("button").disabled = true;
                            }
                        })
                    /******/
                })
            })
            document.querySelector('#delete').addEventListener('click', (event) => {
                document.getElementById('modal').classList.remove('is-active');
            })
            var loading = function(isLoading) {
                if (isLoading) {
                    document.querySelector("button").disabled = true;
                    document.querySelector("#spinner").classList.remove("hidden");
                    document.querySelector("#button-text").classList.add("hidden");
                } else {
                    document.querySelector("button").disabled = false;
                    document.querySelector("#spinner").classList.add("hidden");
                    document.querySelector("#button-text").classList.remove("hidden");
                }
            };

        </script>

    </body>

    </html>
