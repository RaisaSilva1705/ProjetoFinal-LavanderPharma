INSERT INTO CARGOS_FUNCIONARIOS (Cargo, Descricao)
VALUES ('Administrador', 'Acesso irrestrito ao sistema.');

INSERT INTO MODULOS (Modulo) VALUES 
-- Sem grupo
('Home'),
('Caixa PDV'),
('Minhas Comissões'),
('Configurações'),
-- Pessoas
('Clientes'),
('Usuários'),
('Funcionários'),
('Fornecedores'),
-- Cadastros
('Cargos'),
('Caixas'),
('Forma Pgto'),
-- Produtos
('Categorias'),
('Produtos'),
('Entradas'),
('Saídas'),
('Estoque'),
('Trocas'),
-- Financeiro
('Contas à Receber'),
('Despesas'),
('Compras'),
('Vendas'),
('Fluxo de Caixa'),
('Comissões'),
('Contas Vencidas'),
-- Relatórios
('Relatório de Vendas'),
('Relatório de Clientes'),
('Relatório de Recebimentos'),
('Relatório de Despesas'),
('Relatório de Lucro'),
('Relatório de Produtos'),
('Relatório de Estoque'),
('Relatório de Entrada/Saída'),
('Relatório de Caixas'),
('Relatório de Comissões'),
('Relatório de Trocas'),
('Relatório de Vendas Produtos'),
-- Vendas
('Orçamentos'),
('Contas Pendentes'),
('Todas as Vendas'),
('Atualizar Vendas');

INSERT INTO CARGOS_MODULOS (ID_Cargo, ID_Modulo, Acesso_Permitido)
SELECT 1, ID_Modulo, TRUE FROM MODULOS;

INSERT INTO FUNCIONARIOS (ID_Funcionario, Nome, Email, ID_Cargo)
VALUES (1, 'Administrador Geral', 'admin@admin.com', 1);

INSERT INTO USUARIOS (ID_Funcionario, Usuario, Senha, Data_Cadastro)
VALUES (1, 'admin', '$2y$10$ywTQsi8pt7ttAVku9vKPXuDhA.VuIqZkMRzLUKOgJKd.mmmD/yzUO', CURDATE());

INSERT INTO TARJAS_MEDICAMENTOS (Tarja) VALUES
('Medicamento Isento de Prescrição'),
('Amarela'),
('Amarela e Vermelha s/ Retenção de Prescrição'),
('Amarela e Vermelha c/ Retenção de Prescrição'),
('Amarela e Preta'),
('Vermelha s/ Retenção de Prescrição'),
('Vermelha c/ Retenção de Prescrição'),
('Preta');

INSERT INTO CATEGORIAS (Categoria) VALUES
('Medicamento'),
('Cosmético'),
('Higiene Pessoal'),
('Suplemento Alimentar'),
('Dispositivo Médico'),
('Alimento Funcional'),
('Materiais para Curativo'),
('Infantil'),
('Dermocosmético'),
('Equipamento Médico');

INSERT INTO CATEGORIAS_MEDICAMENTOS (Categoria_Med) VALUES
('Antibiótico'),
('Analgésico'),
('Anti-inflamatório'),
('Antialérgico'),
('Antifúngico'),
('Antiviral'),
('Ansiolítico'),
('Antipirético'),
('Anti-hipertensivo');

INSERT INTO UNIDADES (Unidade, Abreviacao, Tipo) VALUES
('Caixa', 'cx', 'Contagem'),
('Comprimido', 'cp', 'Contagem'),
('Frasco', 'fr', 'Volume');

INSERT INTO PRODUTOS (ID_Categoria, Nome, ID_Unidade, NCM, EAN_GTIN) VALUES
(1, 'Paracetamol 750mg 20cp', 2, '30049099', '7896422500080'),
(9, 'Creme Hidratante Neutrogena 200ml', 3, '33049990', '7891010246124');

INSERT INTO MEDICAMENTOS (ID_Produto, ID_CategoriaMed, ID_Tarja, Tipo, Prin_Ativo) VALUES
(1, 2, 1, 'Genérico', 'Paracetamol');

INSERT INTO LOTES (Nome_Lote, ID_Produto, Preco_Unitario, Data_Validade) VALUES
('L202505A', 1, 4.50, '2025-12-31'),
('L202506B', 2, 0.00, '2026-01-15');
/* 22.90 */

INSERT INTO ESTOQUE (ID_Produto, ID_Lote, Quantidade, Preco_Atual, Data_Entrada, Data_Atualizacao) VALUES
(1, 1, 100, 4.50, NOW(), NOW()),
(2, 2, 50, 22.90, NOW(), NOW());

INSERT INTO CAIXAS (Caixa) VALUES
('Caixa01'),
('Caixa02');

INSERT INTO FORMAS_PAGAMENTO (Tipo) VALUES
('Dinheiro'), ('Cartão de Crédito'), ('Cartão de Débito'), ('PIX');