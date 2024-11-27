-- Inserts para a tabela CLIENTES
-- corrigir CEP e endereço
INSERT INTO `CLIENTES` (`Nome_Cliente`, `Tipo_Cliente`, `Documento_Cliente`, `Tel_Cliente`, `Email_Cliente`, `Senha_Cliente`, `CEP`, `Endereco_Cliente`, `EndNumero_Cliente`, `Complemento_Cliente`, `Bairro_Cliente`, `Cidade_Cliente`, `Estado_Cliente`, `DataNasc_Cliente`, `Status_Cliente`, `DataCadastro_Cliente`, `DataAlteracao_Cliente`) VALUES
('João Silva', 'PF', '123.456.789-00', '(11) 98765-4321', 'joao@gmail.com', '$2y$10$iAxPGOx9p8b8Kc926r9SDuoKhJymFGvwbTicO2XmZUqbJUqmS/bfS', '2185030', 'Avenida Joao Maria Fernandes', '480', 'Torre 13', 'Parque Novo Mundo', 'São Paulo', 'SP', '1990-01-01', '1', NOW(), NULL),
('Maria Oliveira', 'PF', '987.654.321-00', '(21) 97654-3210', 'maria@gmail.com', '$2y$10$7DH0hr91aS2JYlaiRtLgJ.CUw2MCCVn/.XdNVPvHWlMdth3TBYKda', '4355000', 'Avenida João Pedro Cardoso', '299', NULL, 'Parque Jabaquara', 'São Paulo', 'SP', '1985-03-15', '1', NOW(), NULL),
('Pedro Costa', 'PF', '654.321.987-00', '(31) 91234-5678', 'pedro@gmail.com', '$2y$10$/8y1TJv93Th4nFqIKkqQFuGNRL5X8mzhL2F2JLo8cOAXh4/ZUvfqK', '8383570', 'Rua Aleixo Vaquero', '125', NULL, 'Jardim Vale do Sol', 'São Paulo', 'SP', '1988-07-22', '1', NOW(), NULL),
('Ana Souza', 'PF', '321.654.987-00', '(41) 99876-5432', 'ana@gmail.com', '$2y$10$.e.IMuwjIShEnCeOJVOkseeNzsH8oiqGtXHgoOxDt1R31yAMCXlVa', '4407130', 'Travessa Serra do Ramalho', '25', NULL, 'Vila Império', 'São Paulo', 'SP', '1992-05-19', '1', NOW(), NULL),
('Lucas Pereira', 'PF', '987.321.654-00', '(51) 99123-4567', 'lucas@gmail.com', '$2y$10$04u7Y3t/.nPoQNyjpBX68et/YClW8l/ZDWGD/q2sb24e7qawYZNs6', '4236015', 'Rua Flor de São Francisco', '754', NULL, 'Vila Heliópolis', 'São Paulo', 'SP', '1994-12-08', '1', NOW(), NULL),
('Carla Almeida', 'PF', '123.987.654-00', '(61) 98345-6789', 'carla@gmail.com', '$2y$10$d5..zW2nlU6FpFmguiYgTOtPEADwGTdcc9wdAFmZOLXKU//VrXATu', '4370003', 'Avenida João Barreto de Menezes - lado ímpar', '327', NULL, 'Vila Santa Catarina', 'São Paulo', 'SP', '1991-03-14', '1', NOW(), NULL),
('Rafael Mendes', 'PF', '789.654.123-00', '(71) 99765-4321', 'rafael@gmail.com', '$2y$10$00qBwzVJiuPCRTlNSDv4fufxE6XkhBZvuKE97GTHqFbMeVD9HWsIO', '4182115', 'Rua B', '14', 'Mercadinho de Bairro', 'Jardim Santa Cruz (Sacomã)', 'São Paulo', 'SP', '1980-11-20', '1', NOW(), NULL),
('Juliana Ribeiro', 'PF', '456.789.123-00', '(81) 99234-5678', 'juliana@gmail.com', '$2y$10$R4vZXwgsrdszjt8rqRonWu.MrzkyBmxHcu.6XB5b3MuNAqbbv3UoK', '8010280', 'Praça Triángulo Mineiro', '61', 'Casa Rosa', 'Vila Doutor Eiras', 'São Paulo', 'SP', '1986-08-01', '1', NOW(), NULL),
('Diego Barros', 'PF', '654.123.789-00', '(91) 98876-5432', 'diego@gmail.com', '$2y$10$0Us6SsfvABaCPGLvG/atnuU1M27VzzXZNs4l0HlKYo/9H2ZtALNAO', '4421020', 'Rua Maria Balades Correa', '41', NULL, 'Jardim Luso', 'São Paulo', 'SP', '1982-02-28', '1', NOW(), NULL),
('Fernanda Lima', 'PF', '321.987.654-00', '(71) 99654-3210', 'fernanda@gmail.com', '$2y$10$U8DVGKYayz/gC5m3ukCB6O7DGdK/emTV5ZEysvBYR9IYTdf2xWM02', '3821070', 'Travessa Humberto Batista', '154', NULL, 'Vila Sílvia', 'São Paulo', 'SP', '1996-09-15', '1', NOW(), NULL);

-- Inserts para a tabela FUNCIONARIOS
INSERT INTO `FUNCIONARIOS` (`Nome_Funcionario`, `Tipo_Funcionario`, `Documento_Funcionario`, `Tel_Funcionario`, `Cargo_Funcionario`, `Email_Funcionario`, `Senha_Funcionario`, `Salario_Funcionario`, `DataAdmissao_Funcionario`, `Status_Funcionario`, `DataCadastro_Funcionario`, `DataAlteracao_Funcionario`) VALUES
('Carlos Silva', 'PF', '987.654.321-00', '(11) 91234-5678', 'Farmacêutico', 'carlos@gmail.com', '$2y$10$cJHKjZnR5Jplc7Rjm.DhKeC6wE0Gcf70g7W39r/.0AcODgTZt4HYa', 3500.00, '2022-01-15', '1', NOW(), NULL),
('Ana Martins', 'PF', '654.321.987-00', '(21) 91234-5678', 'Atendente de Farmácia', 'ana.martins@gmail.com', '$2y$10$FssxyzAjydalOolDo8EjQOD6S1PHN3vV0T/e6j7V8hzhKn54LLS7q', 1800.00, '2023-03-10', '1', NOW(), NULL),
('Lucas Pereira', 'PF', '321.654.987-00', '(31) 99876-5432', 'Auxilizar de Farmácia', 'lucas.pereira@gmail.com', '$2y$10$A.PyAEKGf1cb9KOPxhncC.VlCkkwUyPFW4ST27TIbvR5aoD.kzNre', 2200.00, '2021-11-25', '1', NOW(), NULL),
('Fernanda Lima', 'PF', '789.654.123-00', '(41) 99765-4321', 'Gerente', 'fernanda.lima@gmail.com', '$2y$10$FMJWulZKo7YdXNWnxLZMne/bvB6gSBSmWD3FkBr1qEhVrKnVzV6Om', 4500.00, '2020-09-05', '1', NOW(), NULL),
('Roberto Souza', 'PF', '123.789.456-00', '(51) 99123-4567', 'Subgerente', 'roberto.souza@gmail.com', '$2y$10$hn9YgN.h78SBQSfT53XereGkUFiKRAUHtUQoic21S.1SEYlG7Ta0y', 4000.00, '2019-06-20', '1', NOW(), NULL),
('Juliana Ribeiro', 'PF', '654.987.321-00', '(61) 98345-6789', 'Auxiliar Administrativo', 'juliana.ribeiro@gmail.com', '$2y$10$zfGJhecilqZVn60twhtMxuNus5Hs0K4j1PePupEKVVtv3D9UUn03y', 2500.00, '2023-02-15', '1', NOW(), NULL),
('Diego Costa', 'PF', '321.987.654-00', '(71) 99654-3210', 'Auxiliar de Limpeza', 'diego.costa@gmail.com', '$2y$10$P1ViT.t.PDbEX2NZ59iy1eRO5HLGCkoaHOMDJrNjuzpzaAVXinw4G', 1500.00, '2023-05-01', '1', NOW(), NULL),
('Mariana Rocha', 'PF', '123.456.789-00', '(81) 99234-5678', 'Consultor(a) de Dermocosméticos', 'mariana.rocha@gmail.com', '$2y$10$m.X16Cxf4UZgJAt5/nO3UOROcUU7TF0te93h4HX8n0E027BgxZUbO', 2800.00, '2022-10-30', '1', NOW(), NULL),
('Gustavo Almeida', 'PF', '987.654.321-00', '(91) 98876-5432', 'Farmacêutico', 'gustavo.almeida@gmail.com', '$2y$10$mKIGGy7//Qg5a.eC4ENIuuTzol3oERTIbbOvhY2xUMf0.SEVULy7m', 3600.00, '2021-08-18', '1', NOW(), NULL),
('Patrícia Mendes', 'PF', '654.321.987-00', '(71) 99123-9876', 'RH', 'patricia.mendes@gmail.com', '$2y$10$5OJVCR.H6ItcvNeEwXAMe.eiz9lZ1yq08nlv0IQbhS53VyOtcZc12', 1900.00, '2020-12-10', '1', NOW(), NULL);

-- Inserts para a tabela FORNECEDORES
-- corrigir CEP e endereço
INSERT INTO `FORNECEDORES` (`Nome_Fornecedor`, `CNPJ`, `Tel_Fornecedor`, `Email_Fornecedor`, `CEP`, `Endecero_Fornecedor`, `EndNumero_Fornecedor`, `Complemento_Fornecedor`, `Bairro_Fornecedor`, `Cidade_Fornecedor`, `Estado_Fornecedor`, `Status_Fornecedor`, `DataCadastro_Fornecedor`, `DataAlteracao_Fornecedor`) VALUES
('Farmac Distribuidora LTDA', '12.345.678/0001-90', '(11) 91234-5678', 'contato@farmac.com', '4407100', 'Rua Dom João Soares Coelho', '157', NULL, 'Vila Império', 'São Paulo', 'SP', '1', NOW(), NULL),
('Med Supply S.A.', '98.765.432/0001-10', '(21) 99876-5432', 'vendas@medsupply.com', '28990822', 'Rua Antônio Máximo', '574', NULL, 'Itaúna', 'Saquarema', 'RJ', '1', NOW(), NULL),
('Saúde e Vida LTDA', '65.432.109/0001-45', '(31) 98765-4321', 'atendimento@saudevida.com', '33805559', 'Rua Vital Augusto Guimarães', '24', NULL, 'São Pedro', 'Ribeirão das Neves', 'MG', '1', NOW(), NULL),
('Dermocosméticos Brasil', '23.456.789/0001-87', '(41) 97654-3210', 'contato@dermocosmeticos.com', '41100183', 'Avenida Thomaz Gonzaga', '12', NULL, 'Pernambués', 'Salvador', 'BA', '1', NOW(), NULL),
('Nutri Farma LTDA', '78.123.456/0001-32', '(51) 99123-4567', 'vendas@nutrifarma.com', '28893768', 'Rua C', '1', NULL, 'Liberdade', 'Rio das Ostras', 'RJ', '1', NOW(), NULL),
('Beleza Plus Distribuição', '89.012.345/0001-76', '(61) 98345-6789', 'contato@belezaplus.com', '88806068', 'Rua Joao Favorino Albino', '78', NULL, 'Vila Nova Esperança', 'Criciúma', 'SC', '1', NOW(), NULL),
('Pharma Solutions S.A.', '34.567.890/0001-21', '(71) 99765-4321', 'suporte@pharmasolutions.com', '28893648', 'Travessa São Jorge Três', '61', NULL, 'Liberdade', 'Rio das Ostras', 'RJ', '1', NOW(), NULL),
('Higiene & Cia LTDA', '90.123.456/0001-65', '(81) 99234-5678', 'sac@higieneecia.com', '76380181', 'Rua Joao Carrilho Leste - de 491/492 a 974/975', '492', NULL, 'Santa Luzia', 'Goianésia', 'GO', '1', NOW(), NULL),
('HomeoLife Produtos Naturais', '56.789.012/0001-43', '(91) 98876-5432', 'info@homeolife.com', '36400093', 'Rua André Rodrigues da Silva - até 491/492', '492', NULL, 'Campo Alegre', 'Conselheiro Lafaiete', 'MG', '1', NOW(), NULL),
('PetCare Distribuidora', '43.210.987/0001-09', '(71) 99123-9876', 'contato@petcare.com', '13470442', 'Rua Serra do Caparão', '36', NULL, 'Parque Liberdade', 'Americana', 'SP', '1', NOW(), NULL);

-- Inserts para a tabela PRODUTOS
INSERT INTO `PRODUTOS` (`Nome_Produto`, `Descricao_Produto`, `Categoria_Produto`, `PrecoVenda_Produto`, `PrecoCusto_Produto`, `QuantidadeEstoque_Produto`, `CodigoBarras_Produto`, `ID_Fornecedor`) VALUES
('Dipirona Sódica 500mg', 'Analgésico e antitérmico', 'Medicamentos de prescrição', 5.50, 2.50, 100, '7891234567890', 1),
('Paracetamol 750mg', 'Analgésico e antitérmico', 'Medicamentos isentos de prescrição', 4.90, 2.00, 200, '7891234567891', 2),
('Ibuprofeno 400mg', 'Anti-inflamatório', 'Medicamentos de prescrição', 12.00, 6.00, 150, '7891234567892', 3),
('Protetor Solar FPS 50', 'Dermocosmético para proteção solar', 'Dermocosméticos e cuidados pessoais', 45.00, 30.00, 50, '7891234567893', 4),
('Shampoo Anticaspa 200ml', 'Produto para cuidados capilares', 'Higiene e beleza', 22.90, 15.00, 80, '7891234567894', 5),
('Leite em Pó Infantil 800g', 'Alimento para bebês', 'Infantil e maternidade', 60.00, 40.00, 30, '7891234567895', 6),
('Ração para Cães Adultos 3kg', 'Alimento para pets', 'Produtos para animais', 75.00, 50.00, 20, '7891234567896', 7),
('Vitamina C 1000mg', 'Suplemento alimentar', 'Alimentos e nutrição', 35.00, 20.00, 120, '7891234567897', 8),
('Pomada Antisséptica 30g', 'Cuidados com ferimentos', 'Produtos de saúde', 18.00, 10.00, 60, '7891234567898', 9),
('Floral Calmante 30ml', 'Homeopatia para redução de estresse', 'Produtos naturais e homeopáticos', 40.00, 25.00, 40, '7891234567899', 10);

-- Inserts para a tabela VENDAS
INSERT INTO `VENDAS` (`ID_Cliente`, `ID_Funcionario`, `DataVenda_Venda`, `ValorTotal_Venda`, `FormaPagamento_Venda`) VALUES
(1, 1, '2023-11-01 10:30:00', 150.00, 'Crédito'),
(2, 2, '2023-11-02 14:00:00', 60.00, 'Débito'),
(3, 3, '2023-11-03 15:45:00', 80.00, 'PIX'),
(4, 4, '2023-11-04 11:20:00', 200.00, 'Dinheiro'),
(5, 5, '2023-11-05 16:10:00', 45.00, 'PIX'),
(6, 6, '2023-11-06 12:30:00', 300.00, 'Crédito'),
(7, 7, '2023-11-07 18:15:00', 90.00, 'Débito'),
(8, 8, '2023-11-08 09:50:00', 75.00, 'Dinheiro'),
(9, 9, '2023-11-09 10:15:00', 180.00, 'PIX'),
(10, 10, '2023-11-10 14:40:00', 250.00, 'Crédito');

-- Inserts para a tabela ITENSVENDIDOS
INSERT INTO `ITENSVENDIDOS` (`ID_Venda`, `ID_Produto`, `Quantidade_ItemVenda`, `PrecoUnitario_ItemVenda`) VALUES
(1, 1, 2, 5.50),
(1, 4, 1, 45.00),
(2, 2, 3, 4.90),
(2, 5, 2, 22.90),
(3, 3, 1, 12.00),
(3, 6, 1, 60.00),
(4, 7, 1, 75.00),
(5, 8, 1, 35.00),
(6, 9, 2, 18.00),
(7, 10, 1, 40.00);

-- Inserts para a tabela PRESCRICOES
INSERT INTO `PRESCRICOES` (`ID_Cliente`, `DataEmissao_Prescricao`, `DataRegistro_Prescricao`) VALUES
(1, '2023-10-15', '2023-10-15 10:00:00'),
(2, '2023-11-01', '2023-11-01 14:00:00'),
(3, '2023-09-23', '2023-09-23 12:30:00'),
(4, '2023-11-05', '2023-11-05 15:00:00'),
(5, '2023-08-10', '2023-08-10 11:20:00'),
(6, '2023-07-17', '2023-07-17 09:45:00'),
(7, '2023-10-21', '2023-10-21 13:15:00'),
(8, '2023-06-03', '2023-06-03 16:00:00'),
(9, '2023-05-11', '2023-05-11 17:10:00'),
(10, '2023-04-29', '2023-04-29 11:50:00');

-- Inserts para a tabela PEDIDOS
INSERT INTO `PEDIDOS` (`ID_Produto`, `ID_Fornecedor`, `Quantidade_Pedido`, `DataPedido_Pedido`, `DataEntrega_Pedido`) VALUES
(1, 1, 50, '2023-11-01 09:00:00', '2023-11-05 14:00:00'),
(2, 2, 100, '2023-11-02 10:30:00', '2023-11-06 15:00:00'),
(3, 3, 75, '2023-11-03 11:00:00', '2023-11-07 16:30:00'),
(4, 4, 30, '2023-11-04 08:30:00', '2023-11-08 14:30:00'),
(5, 5, 50, '2023-11-05 09:45:00', '2023-11-09 15:15:00'),
(6, 6, 40, '2023-11-06 10:15:00', '2023-11-10 16:00:00'),
(7, 7, 60, '2023-11-07 12:00:00', '2023-11-11 13:30:00'),
(8, 8, 30, '2023-11-08 09:30:00', '2023-11-12 14:45:00'),
(9, 9, 80, '2023-11-09 14:00:00', '2023-11-13 17:00:00'),
(10, 10, 20, '2023-11-10 15:30:00', '2023-11-14 12:00:00');

/*
senhas para os testes:
1. senha123
2. senha456
3. senha789
4. senha101
5. senha202
6. senha303
7. senha404
8. senha505
9. senha606
10. senha707
*/