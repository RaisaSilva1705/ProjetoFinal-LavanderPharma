<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../../Dev/Exec/config.php";

// Incluir o arquivo de conexão
include DEV_PATH . 'Exec/conexao.php';
include DEV_PATH . "Exec/validar_sessao.php";
include DEV_PATH . "Exec/validar_acesso.php";

// Confere se não há ID_Caixa aberto
if (!isset($_SESSION['ID_Caixa'])){
    header("Location: caixa_pdv.php");
    exit();
}

// Busca dados da empresa (quant_max_parcelas e valor_min_parcelas)
$sqlInfoParcelas =  "SELECT Quant_Max_Parcelas, Valor_Min_Parcelas FROM CONFIGURACOES";
$stmtInfoParcelas = $conn->prepare($sqlInfoParcelas);
$stmtInfoParcelas->execute();
$resultInfoParcelas = $stmtInfoParcelas->get_result();
$infoParcelas = $resultInfoParcelas->fetch_assoc();

// Cancela a venda
if (isset($_POST['cancelar_venda'])) {
    unset($_SESSION['carrinho']);
    unset($_SESSION['ultimo_produto']);
    header("Location: pdv.php");
    exit;
}

// Inicializa o carrinho
if (!isset($_SESSION['carrinho'])) $_SESSION['carrinho'] = [];

// Remove item do carrinho
if (isset($_GET['remover'])) {
    $index = intval($_GET['remover']);
    if (isset($_SESSION['carrinho'][$index])) {
        unset($_SESSION['carrinho'][$index]);
        $_SESSION['carrinho'] = array_values($_SESSION['carrinho']); // reorganiza os índices
    }
}

// Adiciona item no carrinho
if (isset($_POST['codigo'])) {
    $codigo = $_POST['codigo'];

    $stmt = $conn->prepare("SELECT P.Nome, 
                                   L.Preco_Unitario, 
                                   P.Foto 
                            FROM PRODUTOS P LEFT JOIN LOTES L 
                                ON P.ID_Produto = L.ID_Produto
                            WHERE EAN_GTIN = ?");
    $stmt->bind_param("s", $codigo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($nome, $preco, $foto);
        $stmt->fetch();
        $quantidade = max(1, intval($_POST['quantidade']));

        // Verificação se o valor do produto não é nulo ou 0
        if ($preco === null || $preco <= 0) {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Produto sem preço definido!</div>";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }

        $produtoEncontrado = false;

        foreach ($_SESSION['carrinho'] as &$item) {
            if ($item['codigo'] === $codigo) {
                $item['quantidade'] += $quantidade;
                $produtoEncontrado = true;
                break;
            }
        }
        unset($item); // evita problemas com referências

        if (!$produtoEncontrado) {
            $_SESSION['carrinho'][] = [
                'codigo' => $codigo,
                'nome' => $nome,
                'preco' => $preco,
                'foto' => $foto ?: 'sem-imagem.png',
                'quantidade' => $quantidade
            ];
        }

        // Salva info do último produto
        $_SESSION['ultimo_produto'] = [
            'descricao' => $nome,
            'preco' => $preco,
            'foto' => $foto
        ];

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } 
    else {
        $_SESSION['msg'] = "<div class='alert alert-primary'>Produto não encontrado!</div>";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Frente de Caixa</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="<?php echo DEV_URL ?>CSS/global.css">
        <style>
            .modalPix{
                display:none; 
                position:fixed; 
                top:0; 
                left:0; 
                width:100%; 
                height:100%; 
                background-color:rgba(0,0,0,0.5); 
                justify-content:center; 
                align-items:center;
            }
        </style>
    </head>
    <body class="bg-light">

        <div class="content">
            <!-- Banner -->
            <div class="container-fluid bg-secondary text-white text-center p-4">
                <h3>Frente de Caixa</h3>
                <?php
                    // Verifica se $_SESSION["msg"] não é nulo e imprime a mensagem
                    if(isset($_SESSION["msg"]) && $_SESSION["msg"] != null){
                        echo "<script>alert('" . $_SESSION["msg"] . "');</script>";
                        // Limpa a mensagem para evitar que seja exibida novamente
                        $_SESSION["msg"] = null;
                    }
                ?>
            </div>
            <div class="container mt-4">

                <!-- TOPO -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <?php 
                            $sql = "SELECT COUNT(ID_Venda) + 1 AS num FROM VENDAS;";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                $temp = $result->fetch_assoc();
                                $id_venda = $temp['num'];
                            }
                            else {
                                $id_venda = 1;
                            }
                        ?>
                        <label for="id_venda" class="form-label">Nº Venda:</label>
                        <input type="text" name="id_venda" id="id_venda" class="form-control" value="<?php echo $id_venda ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="data" class="form-label">Data Venda:</label>
                        <input type="text" name="data" id="data" class="form-control" value="<?= date('d/m/Y H:i') ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <?php 
                            // Busca clientes
                            $sqlCli = "SELECT ID_Cliente, Nome FROM CLIENTES";
                            $clientes = $conn->query($sqlCli);
                        ?>
                        <label for="id_cliente" class="form-label">Cliente:</label>
                        <select class="form-select" name="id_cliente" id="id_cliente" required>
                            <option value="">Selecione</option>
                            <?php while($cliente = $clientes->fetch_assoc()): ?>
                                <option value="<?= $cliente['ID_Cliente'] ?>"><?= htmlspecialchars($cliente['Nome']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="funcionario" class="form-label">Vendedor:</label>
                        <input type="text" class="form-control" name="funcionario" id="funcionario" value="<?php echo $_SESSION['Nome'] ?>" readonly>
                    </div>
                </div>

                <!-- CENTRO -->
                <div class="row mb-3">
                    <!-- COLUNA PRODUTO -->
                    <div class="col-md-7 border p-2">
                        <form action="#" method="POST">
                            <div class="row">
                                <!-- COLUNA IMAGEM -->
                                <div class="col-md-5 m-3">
                                    <div class="col-md-5 text-center">
                                        <img src='<?php echo DEV_URL?>Imagens/ImgSistema/sem-imagem.jpg' id="foto" name="foto" class="product-img mb-2" alt="Imagem da Embalagem do Produto" height="280px" width="280px">
                                    </div>
                                </div>
                                <!-- COLUNA INFO -->
                                <div class="col-md-6 m-2 mt-4">
                                    <div class="col-md-10">
                                        <label for="descricao">Descrição:</label>
                                        <input type="text" id="descricao" name="descricao" class="form-control input-big" autocomplete="off">
                                        <div id="sugestoes_nome" class="list-group mt-1" style="position: absolute; z-index: 1000;"></div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-5">
                                            <label for="quantidade">Quantidade:</label>
                                            <input type="number" id="quantidade" name="quantidade" class="form-control input-big" value="1" min="1">
                                        </div>
                                        <div class="col-5">
                                            <label for="preco">Preço Unitário:</label>
                                            <input type="text" id="preco" name="preco" class="form-control" value="R$ 0,00" readonly>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-md-6">
                                            <label for="codigo">Código Barras:</label>
                                            <input type="text" id="codigo" name="codigo" class="form-control">
                                        </div>
                                        <div class="col-md-4 mt-4" >
                                            <button type="submit" class="btn btn-primary w-100">Adicionar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-5 border p-2" style="height: 350px; overflow-y: auto;">
                        <!-- COLUNA VALORES/LOGO -->
                        <table class="table table-bordered table-striped table-sm mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 200px;">Nome</th>
                                    <th>Valor</th>
                                    <th>Quant</th>
                                    <th>Total</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody style="min-height: 240px;">
                                <?php
                                    $totalGeral = 0;
                                    $totalItens = 0;
                                    $linhasDesejadas = 8; // Número total de linhas que você quer
                                    $linhasOcupadas = 0;

                                    if (!empty($_SESSION['carrinho'])):
                                        foreach ($_SESSION['carrinho'] as $index => $item):
                                            $preco = ($item['preco'] == null) ?  0.00 : $item['preco'];
                                            $subtotal = $preco * $item['quantidade'];
                                            $totalGeral += $subtotal;
                                            $totalItens += $item['quantidade'];
                                            $linhasOcupadas++;
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['nome']) ?></td>
                                        <td>R$ <?= number_format($preco, 2, ',', '.') ?></td>
                                        <td><?= $item['quantidade'] ?></td>
                                        <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                                        <td>
                                            <a href="?remover=<?= $index ?>" class="btn btn-sm btn-danger">Remover</a>
                                        </td>
                                    </tr>
                                    <?php
                                        endforeach;

                                        // Preenche o restante com linhas vazias
                                        for ($i = 0; $i < $linhasDesejadas - $linhasOcupadas; $i++): ?>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                    <?php endfor;

                                    else: 
                                ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Nenhum item adicionado ao carrinho</td>
                                </tr>
                                <?php for ($i = 0; $i < $linhasDesejadas - 1; $i++): ?>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                <?php endfor; ?>
                            <?php endif;

                                ?>
                            </tbody>
                            <tfoot class="table-secondary">
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Total Geral:</strong></td>
                                    <td colspan="2"><strong>R$ <?= number_format($totalGeral, 2, ',', '.') ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- RODAPÉ -->
                <div class="row">
                    <div class="col-md-2">
                        <label>Total Bruto:</label>
                        <input type="text" id="total_bruto" class="form-control" value="R$ <?= number_format($totalGeral, 2, ',', '.') ?>" readonly>
                    </div>
                    <div class="col-md-1">
                        <label>Qtd. Itens:</label>
                        <input type="text" class="form-control" value="<?= $totalItens ?>" readonly>
                    </div>

                    <div class="col-md-4 d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-success" onclick="selecionarForma(1)">(1) Dinheiro</button>
                        <button type="button" class="btn btn-success" onclick="selecionarForma(2)">(2) Crédito</button>
                        <button type="button" class="btn btn-success" onclick="selecionarForma(3)">(3) Débito</button>
                        <button type="button" class="btn btn-success" onclick="selecionarForma(4)">(4) Pix</button>
                    </div>

                    <div class="col-md-5 d-flex align-items-center justify-content-end gap-2">
                        <form action="pdv.php" method="POST">
                            <input type="hidden" name="cancelar_venda" value="1">
                            <button class="btn btn-danger" type="submit">Cancelar Venda</button>
                        </form>
                        <form action="finalizarcaixa_pdv.php" method="POST">
                            <input type="hidden" name="finalizar_caixa" value="1">
                            <button class="btn btn-secondary" type="submit">Fechar Caixa</button>
                        </form>
                    </div>
                </div>

                <div class="row mt-3">
                    
                </div>

            </div>

            <!-- Modal de Pagamento -->
            <div class="modal fade modal-lg" id="popupPagamento" tabindex="-1" aria-labelledby="popupPagamentoLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Formas de Pagamento</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>
                        <div class="modal-body">
                            <?php 
                                // Busca formas de pagamento cadastradas
                                $sqlFoPags = "SELECT ID_Forma_Pag, Tipo FROM FORMAS_PAGAMENTO";
                                $foPags = $conn->query($sqlFoPags);
                            ?>
                            <?php while($foPag = $foPags->fetch_assoc()): ?>
                                <div class="mb-3 row campo-forma" data-id="<?= $foPag['ID_Forma_Pag']; ?>" style="display: none;">
                                    <label class="col-sm-4 col-form-label"><?= $foPag['ID_Forma_Pag']; ?> - <?= $foPag['Tipo']; ?></label>
                                    <div class="col-sm-8 d-flex align-items-center">
                                        <input type="text" class="form-control forma" data-id="<?= $foPag['ID_Forma_Pag'] ?>" placeholder="R$">
                                        <?php if($foPag['ID_Forma_Pag'] == 2 && $totalGeral >= $infoParcelas['Valor_Min_Parcelas']): ?>
                                            <label class="me-2 mb-0">Qnt. Parcelas:</label>
                                            <input type="number" class="form-control parcela" id="parcelas" name="quant_parcelas" min="1" max="<?= $infoParcelas['Quant_Max_Parcelas']?>">
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>

                            <div class="text-end fw-bold mt-3" id="troco">Troco: R$ 0,00</div>
                        </div>
                        
                        <div class="modal-footer">
                            <input type="hidden" name="id_cliente_hidden" id="id_cliente_hidden" value="">
                            <button class="btn btn-success w-100" id="confirmarPagamento" type="submit">Confirmar Pagamento</button>
                            <div id="card-errors"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal PIX -->
            <div id="modalPix" class="modalPix">
                
                <div style="background:white; padding:20px; border-radius:10px; text-align:center;">
                    <h3>Escaneie o QR Code PIX:</h3>
                    <img id="pixImg" src="" width="300">
                    <br><br>
                    <button id="fecharModalPix">Confirmar pagamento</button>
                </div>
            </div>

        </div>
        

        <script> 
            // MUDAR OS VALORES POR CÓDIGO DE BARRAS
            document.getElementById('codigo').addEventListener('change', function () {
                const codigo = this.value;

                if (codigo.trim() === '') return;

                fetch('../../Dev/Exec/busca_produto.php?codigo=' + encodeURIComponent(codigo))
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('descricao').value = data.nome;
                            document.getElementById('preco').value = "R$ " + parseFloat(data.preco).toFixed(2).replace('.', ',');
                            document.getElementById('foto').src = '../../Dev/Imagens/imgProdutos/' + data.foto;
                        } else {
                            alert(data.msg);
                            document.getElementById('descricao').value = '';
                            document.getElementById('preco').value = 'R$ 0,00';
                            document.getElementById('foto').src = '../../Dev/Imagens/imgSistema/sem-imagem.jpg';
                        }
                    })
                    .catch(err => {
                        alert('Erro ao buscar produto.');
                        console.error(err);
                    });
            });

            // MUDAR OS VALORES POR NOME DO PRODUTO
            const campoDescricaoProduto = document.getElementById('descricao');
            const sugestoesDiv = document.getElementById('sugestoes_nome');

            campoDescricaoProduto.addEventListener('input', function(){
                const termo = this.value.trim();

                if (termo.length < 2) {
                    sugestoesDiv.innerHTML = '';
                    return;
                }

                fetch('../../Dev/Exec/busca_produto.php?nome=' + encodeURIComponent(termo))
                    .then(response => response.json())
                    .then(produtos => {
                        sugestoesDiv.innerHTML = '';

                        produtos.forEach(produto => {
                            const item = document.createElement('a');
                            item.classList.add('list-group-item', 'list-group-item-action');
                            item.textContent = produto.Nome;
                            item.dataset.codigo = produto.EAN_GTIN;

                            item.addEventListener('click', function(){
                                document.getElementById('codigo').value = this.dataset.codigo;
                                campoDescricaoProduto.value = this.textContent;
                                sugestoesDiv.innerHTML = '';

                                // Força o evento de 'change' no campo código para carregar dados
                                document.getElementById('codigo').dispatchEvent(new Event('change'));
                            });

                            sugestoesDiv.appendChild(item);
                        });
                    });
            });

            // Esconde sugestões se clicar fora
            document.addEventListener('click', function(e){
                if (!campoDescricaoProduto.contains(e.target) && !sugestoesDiv.contains(e.target)) {
                    sugestoesDiv.innerHTML = '';
                }
            });

            // -------------------------------------------------------------------------
            // -------------------------------------------------------------------------

            let formasSelecionadas = [];

            function selecionarForma(id) {
                /*if (formasSelecionadas.includes(id)) return;

                if (formasSelecionadas.length >= 2) {
                    alert("Só é possível usar até 2 formas de pagamento por venda.");
                    return;
                }*/

                formasSelecionadas.push(id);
                mostrarInputsSelecionados();
                abrirPopup();
            }

            function mostrarInputsSelecionados() {
                const ultimoSelecionado = formasSelecionadas[formasSelecionadas.length - 1];
                
                document.querySelectorAll('.campo-forma').forEach(div => {
                    const idForma = parseInt(div.dataset.id);
                    div.style.display = (idForma === ultimoSelecionado) ? 'flex' : 'none';
                });
            }

            // -------------------------------------------------------------------------
            // -------------------------------------------------------------------------
            
            let totalPago = 0;
            let formas_pagamento = [];
            let mapaFormas = {}; // para evitar entradas duplicadas no banco

            // Confirmação de pagamento com STRIPE
            document.getElementById('confirmarPagamento').addEventListener('click', async function(event){
                event.preventDefault();

                document.querySelectorAll('.forma').forEach(function(input){
                    const style = window.getComputedStyle(input.closest('.campo-forma'));
                    if (style.display === 'none') return; // Ignora campos ocultos
                    
                    const id = parseInt(input.dataset.id);
                    const valor = parseFloat(input.value.replace(',', '.'));
                    if (!isNaN(valor) && valor > 0) {
                        totalPago += valor;

                        if (!mapaFormas[id]){
                            mapaFormas[id] = {
                                id_forma_pag: id,
                                valor: 0,
                                quant_vezes: (valor >= <?= $infoParcelas['Valor_Min_Parcelas'] ?>) ? document.getElementById('parcelas').value : 1
                            };
                        }
                        mapaFormas[id].valor += valor;
                    }
                });

                formas_pagamento = Object.values(mapaFormas);
                console.log(formas_pagamento);

                if (totalPago < <?= $totalGeral ?>) {
                    alert("Pagamento Concluído. Ainda faltam R$ " + (<?= $totalGeral ?> - totalPago).toFixed(2).replace('.', ','));
                    calcularTroco();
                    return;
                }

                let erroPagamento = false;

                for (let forma of formas_pagamento) {
                    if (forma.id_forma_pag !== 1) {  // apenas Crédito (2), Débito (3) ou PIX (4)
                        let response;

                        if (forma.id_forma_pag === 4){ // PIX

                            // -------- STRIPE -> precisa de verificação com CNPJ --------
                            /*response = await fetch('http://localhost/htdocs/Farmácia/Dev/Exec/stripe_pagamento.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({
                                    valor: forma.valor,
                                    tipo: 'pix'
                                })
                            });*/

                            // Simulação: gera um QR Code fake com o valor da venda
                            const qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=Pagamento_PIX_VALOR_' + encodeURIComponent(forma.valor);

                            // Abre o Modal do PIX
                            const modalPix = document.getElementById('modalPix');
                            const pixImg = document.getElementById('pixImg');
                            pixImg.src = qrCodeUrl;

                            modalPix.style.display = 'flex';
                            modalPix.style.zIndex = 9999;

                            await new Promise((resolve) => {
                                document.getElementById('fecharModalPix').onclick = function(){
                                    modalPix.style.display = 'none';
                                    resolve();
                                }
                            })

                        }
                        else { // Cartão
                            response = await fetch('http://localhost/htdocs/Farmácia/Dev/Exec/stripe_pagamento.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({
                                    valor: forma.valor,
                                    tipo: 'cartao'
                                })
                            });
                        
                            const result = await response.json();
                            // console.log(result);

                            if (!result.sucesso) {
                                document.getElementById('card-errors').textContent = 'Erro no pagamento: ' + result.mensagem;
                                erroPagamento = true;
                                break; // Cancela, não grava no banco
                            }
                        }
                    }
                }

                if (!erroPagamento) {
                    enviarJson(formas_pagamento);
                }
            });

            // pega o valor total pago 
            function calcularTotalPago() {
                const inputs = document.querySelectorAll('.forma');
                let totalPagoPag = 0;

                inputs.forEach(input => {
                    const valor = parseFloat(input.value.replace(',', '.')) || 0;
                    totalPagoPag += valor;
                });

                return totalPagoPag;
            }

            // APÓS CONFIRMAR PAGAMENTO, MONTA JSON E ENVIA
            function enviarJson(formas_pagamento){
                let dadosVenda = {
                    valor_total: <?= $totalGeral ?>,
                    total_pago: calcularTotalPago(),
                    total_itens: <?= $totalItens ?>,
                    id_cliente: document.getElementById('id_cliente').value || null,
                    id_funcionario: <?= $_SESSION['ID_Funcionario'] ?> || null,
                    desconto: 0.00,
                    formas_pagamento: formas_pagamento
                };

                fetch('finalizarvenda_pdv.php', { 
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(dadosVenda)
                })
                .then(response => response.json())
                .then(data => {
                    if(data.sucesso){
                        totalPago = 0;
                        formas_pagamento = [];
                        console.log('Venda finalizada:', data);
                        alert('Venda finalizada com sucesso!');
                        window.open(`cupomNfiscal.php?ID_Venda=${data.id_venda}`, '_blank');
                        location.reload();
                    }
                    else {
                        console.error('Erro ao finalizar:', data);
                        alert('Erro ao finalizar venda: ' + (data.erro || 'Desconhecido'));
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao finalizar venda!');
                });
            }

            // -------------------------------------------------------------------------
            // -------------------------------------------------------------------------
            
            // SCRIPT MODAL
            const valorTotal = parseFloat("<?= $totalGeral ?>");
            const popup = new bootstrap.Modal(document.getElementById('popupPagamento'));

            function abrirPopup() {
                popup.show();
                document.addEventListener('keydown', atalhoPagamento);
            }

            // calcula o troco automaticamente
            function calcularTroco() {
                /*const inputs = document.querySelectorAll('.forma');
                let totalPago = 0;

                inputs.forEach(input => {
                    const valor = parseFloat(input.value.replace(',', '.')) || 0;
                    totalPago += valor;
                });*/
                const totalPagoAtual = calcularTotalPago();
                const troco = totalPagoAtual - valorTotal;
                document.getElementById('troco').innerText = "Troco: R$ " + troco.toFixed(2).replace('.', ',');
            }

            // -------------------------------------------------------------------------
            // -------------------------------------------------------------------------

            // corrige valores inteiro (10) para números flutuantes (10,00)
            document.querySelectorAll('.forma').forEach(input => {
                input.addEventListener('blur', function () {
                    let valorTexto = this.value.trim();

                    // Corrige vírgulas e remove caracteres inválidos
                    let valorNumerico = parseFloat(valorTexto.replace(',', '.').replace(/[^\d.]/g, ''));

                    if (!isNaN(valorNumerico))
                        this.value = valorNumerico.toFixed(2).replace('.', ',');
                    else
                        this.value = "";

                    calcularTroco();
                });
            });

            // -------------------------------------------------------------------------
            // -------------------------------------------------------------------------

            // atalhos para selecionar a forma de pagamento mais rápido (corrigir futuramente)
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