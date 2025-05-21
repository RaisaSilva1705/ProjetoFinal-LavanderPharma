<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Finalizar Venda</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

  <div class="container">
    <h2>Caixa - Venda atual: R$ <span id="valorTotal">50.00</span></h2>
    <button class="btn btn-primary mt-3" onclick="abrirPopup()">Finalizar Venda</button>
  </div>

<!-- Modal de Pagamento -->
<div class="modal fade" id="popupPagamento" tabindex="-1" aria-labelledby="popupPagamentoLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">Formas de Pagamento</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
</div>
<div class="modal-body">
<div class="mb-3 row">
<label class="col-sm-4 col-form-label">Dinheiro:</label>
<div class="col-sm-8">
<input type="text" class="form-control forma" data-id="1">
</div>
</div>
<div class="mb-3 row">
<label class="col-sm-4 col-form-label">Débito:</label>
<div class="col-sm-8">
<input type="text" class="form-control forma" data-id="2">
</div>
</div>
<div class="mb-3 row">
<label class="col-sm-4 col-form-label">Crédito:</label>
<div class="col-sm-8">
<input type="text" class="form-control forma" data-id="3">
</div>
</div>
<div class="mb-3 row">
<label class="col-sm-4 col-form-label">PIX:</label>
<div class="col-sm-8">
<input type="text" class="form-control forma" data-id="4">
</div>
</div>

<div class="text-end fw-bold mt-3" id="troco">Troco: R$ 0,00</div>
</div>
<div class="modal-footer">
<button class="btn btn-success w-100" onclick="confirmarPagamento()">Confirmar Pagamento</button>
</div>
</div>
</div>
</div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    const valorTotal = 50.00;
    const popup = new bootstrap.Modal(document.getElementById('popupPagamento'));

    function abrirPopup() {
      popup.show();
      document.addEventListener('keydown', atalhoPagamento);
    }

    function calcularTroco() {
      const inputs = document.querySelectorAll('.forma');
      let totalPago = 0;

      inputs.forEach(input => {
        const valor = parseFloat(input.value.replace(',', '.')) || 0;
        totalPago += valor;
      });

      const troco = totalPago - valorTotal;
      document.getElementById('troco').innerText = "Troco: R$ " + troco.toFixed(2).replace('.', ',');
    }

    document.querySelectorAll('.forma').forEach(input => {
      input.addEventListener('blur', function () {
        let valorTexto = this.value.trim();

        // Corrige vírgulas e remove caracteres inválidos
        let valorNumerico = parseFloat(valorTexto.replace(',', '.').replace(/[^\d.]/g, ''));

        if (!isNaN(valorNumerico)) {
          this.value = valorNumerico.toFixed(2).replace('.', ',');
        } else {
          this.value = "";
        }

        calcularTroco();
      });
    });

    function confirmarPagamento() {
      const inputs = document.querySelectorAll('.forma');
      let totalPago = 0;
      let pagamentos = [];

      inputs.forEach(input => {
        const valor = parseFloat(input.value.replace(',', '.')) || 0;
        if (valor > 0) {
          pagamentos.push({
            id_forma: input.dataset.id,
            valor: valor
          });
          totalPago += valor;
          input.value = ""; // limpa o input
        }
      });

      if (totalPago >= valorTotal) {
        alert("Pagamento registrado com sucesso!\n\nFormas de Pagamento:\n" +
          pagamentos.map(p => `ID ${p.id_forma}: R$ ${p.valor.toFixed(2).replace('.', ',')}`).join("\n"));
        popup.hide();
      } else {
        alert("Valor pago insuficiente!");
      }
    }

    function atalhoPagamento(e) {
      const inputs = document.querySelectorAll('.forma');
      const teclas = ['1', '2', '3', '4'];

      if (document.activeElement.tagName === 'INPUT') return;

      if (teclas.includes(e.key)) {
        let index = parseInt(e.key) - 1;
        if (inputs[index]) {
          inputs.forEach(i => i.value = ""); // limpa todos
          inputs[index].value = valorTotal.toFixed(2).replace('.', ',');
          calcularTroco();
        }
      }

      if (e.key === "Escape") {
        popup.hide();
        document.removeEventListener('keydown', atalhoPagamento);
      }
    }
  </script>

</body>
</html>
