INSERT INTO CARGOS_FUNCIONARIOS (ID_Cargo, Cargo, Descricao)
VALUES (1, 'Administrador', 'Acesso irrestrito ao sistema.');

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
