<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('styles/pedido.css') }}">
    <script defer src="https://use.fontawesome.com/releases/v5.14.0/js/all.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <title>Stripe gateway</title>
</head>

<body>
    <div class="notification is-info">
        Essa aplicação está no modo <strong>SANDBOX</strong>, utilize algum <a
            href="https://www.userede.com.br/desenvolvedores/pt/produto/e-Rede#tutorial-cartao"
            target="_blank">DESTES</a>
        cartões de créditos para testar a integração, caso queira teste os erros de pagamento, <a
            href="https://www.userede.com.br/desenvolvedores/pt/produto/e-Rede#tutorial-erros" target="_blank">AQUI</a> está a lista
        de
        <strong>VALORES</strong> e seus respectivos <strong>ERROS</strong>
    </div>
    <div class="invoice">
        <div class="box is-main">
            <form action="{{ route('pagamento') }}" method="post">
                @csrf
                <div class="box-head">
                    <p class="subtitle is-4 is-box-head-title"><strong>PEDIDO</strong></p>
                </div>
                <div class="box">
                    <div class="box-body">
                        <span class="subtitle is-6 is-cartao">Nome do pedido</span>
                        <div class="control has-icons-left">
                            <input class="input is-small" type="text" name="nome-do-pedido"
                                placeholder="Ex: Conjunto de sofás" required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-receipt"></i>
                            </span>
                        </div>
                        <span class="subtitle is-6">Total</span>
                        <div class="control has-icons-left">
                            <input class="input is-small" type="currency" name="total" placeholder="R$ 0,00" required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </span>
                        </div>
                        <button class="button is-pay is-fullwidth">PROCEDER</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
<script type="text/javascript">
    var currencyInput = document.querySelector('input[type="currency"]')
    var currency = 'BRL' // https://www.currency-iso.org/dam/downloads/lists/list_one.xml

    // format inital value
    onBlur({
        target: currencyInput
    })

    // bind event listeners
    currencyInput.addEventListener('focus', onFocus)
    currencyInput.addEventListener('blur', onBlur)


    function localStringToNumber(s) {
        return Number(String(s).replace(/[^0-9.-]+/g, ""))
    }

    function onFocus(e) {
        var value = e.target.value;
        e.target.value = value ? localStringToNumber(value) : ''
    }

    function onBlur(e) {
        var value = e.target.value

        var options = {
            maximumFractionDigits: 2,
            currency: currency,
            style: "currency",
            currencyDisplay: "symbol"
        }

        e.target.value = value ?
            localStringToNumber(value).toLocaleString(undefined, options) :
            ''
    }

</script>

</html>
