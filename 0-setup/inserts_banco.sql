-- Inserts para a tabela CLIENTES
-- corrigir CEP e endereço
INSERT INTO `CLIENTES` (`Nome`, `Tipo`, `Documento`, `Tel`, `Email`, `Senha`, `Status`, `Data_Cadastro`, `Data_Alteracao`) VALUES
('João da Silva', 'PF', '123.456.789-00', '(11) 98765-4321', 'joao.silva@email.com',
'$2y$10$iAxPGOx9p8b8Kc926r9SDuoKhJymFGvwbTicO2XmZUqbJUqmS/bfS', 'Ativo', NOW(), NOW()),
('Maria Oliveira', 'PF', '987.654.321-00', '(21) 97654-3210', 'maria.oliveira@email.com',
'$2y$10$7DH0hr91aS2JYlaiRtLgJ.CUw2MCCVn/.XdNVPvHWlMdth3TBYKda', 'Ativo', NOW(), NOW()),
('Farmácia Saúde LTDA', 'PJ', '12.345.678/0001-90', '(31) 3654-7890', 'contato@farmaciasaude.com',
'$2y$10$/8y1TJv93Th4nFqIKkqQFuGNRL5X8mzhL2F2JLo8cOAXh4/ZUvfqK', 'Ativo', NOW(), NOW()),
('Carlos Mendes', 'PF', '741.852.963-00', '(41) 99885-7744', 'carlos.mendes@email.com',
'$2y$10$.e.IMuwjIShEnCeOJVOkseeNzsH8oiqGtXHgoOxDt1R31yAMCXlVa', 'Ativo', NOW(), NOW()),
('Ana Souza', 'PF', '852.963.741-00', '(51) 98574-1236', 'ana.souza@email.com',
'$2y$10$04u7Y3t/.nPoQNyjpBX68et/YClW8l/ZDWGD/q2sb24e7qawYZNs6', 'Ativo', NOW(), NOW()),
('Super Farma LTDA', 'PJ', '98.765.432/0001-12', '(61) 3344-5566', 'contato@superfarma.com',
'$2y$10$d5..zW2nlU6FpFmguiYgTOtPEADwGTdcc9wdAFmZOLXKU//VrXATu', 'Ativo', NOW(), NOW()),
('Pedro Lima', 'PF', '369.258.147-00', '(71) 91234-5678', 'pedro.lima@email.com',
'$2y$10$00qBwzVJiuPCRTlNSDv4fufxE6XkhBZvuKE97GTHqFbMeVD9HWsIO', 'Ativo', NOW(), NOW()),
('Juliana Ramos', 'PF', '159.753.486-00', '(81) 97654-1234', 'juliana.ramos@email.com',
'$2y$10$R4vZXwgsrdszjt8rqRonWu.MrzkyBmxHcu.6XB5b3MuNAqbbv3UoK', 'Ativo', NOW(), NOW()),
('Drogaria Central', 'PJ', '23.456.789/0001-45', '(91) 3222-3344', 'contato@drogariacentral.com',
'$2y$10$0Us6SsfvABaCPGLvG/atnuU1M27VzzXZNs4l0HlKYo/9H2ZtALNAO', 'Ativo', NOW(), NOW()),
('Fernanda Costa', 'PF', '357.159.486-00', '(11) 95432-6789', 'fernanda.costa@email.com',
'$2y$10$U8DVGKYayz/gC5m3ukCB6O7DGdK/emTV5ZEysvBYR9IYTdf2xWM02', 'Ativo', NOW(), NOW());

-- Inserts para a tabela CLIENDERECOS
INSERT INTO `CLI_ENDERECOS` (`ID_Cliente`, `CEP`, `Endereco`, `End_Numero`, `Complemento`, `Bairro`, `Cidade`, `Estado`) VALUES
(1, '01001000', 'Praça da Sé', '123', 'Apto 45', 'Sé', 'São Paulo', 'SP'),
(2, '20031144', 'Avenida Rio Branco', '789', 'Sala 12', 'Centro', 'Rio de Janeiro', 'RJ'),
(3, '30190922', 'Rua da Bahia', '456', NULL, 'Lourdes', 'Belo Horizonte', 'MG'),
(4, '40020000', 'Avenida Sete de Setembro', '321', 'Casa 2', 'Barra', 'Salvador', 'BA'),
(5, '50010220', 'Rua da Aurora', '852', NULL, 'Boa Vista', 'Recife', 'PE'),
(6, '70040010', 'Esplanada dos Ministérios', 'S/N', 'Bloco A', 'Zona Cívico-Administrativa', 'Brasília', 'DF'),
(7, '80010010', 'Rua XV de Novembro', '147', NULL, 'Centro', 'Curitiba', 'PR'),
(8, '90010140', 'Rua dos Andradas', '369', 'Apto 1003', 'Centro Histórico', 'Porto Alegre', 'RS'),
(9, '66010000', 'Avenida Presidente Vargas', '963', NULL, 'Campina', 'Belém', 'PA'),
(10, '64000000', 'Avenida Frei Serafim', '753', 'Loja 1', 'Centro', 'Teresina', 'PI');

-- Inserts para a tabela CARGOS_FUNCIONARIOS
INSERT INTO `CARGOS_FUNCIONARIOS` (`Cargo`, `Descricao`) VALUES
('Farmacêutico', 'Responsável pela dispensação de medicamentos e orientação aos clientes'),
('Atendente de Farmácia', 'Atendimento ao público e auxílio na venda de produtos'),
('Auxiliar de Farmácia', 'Auxilia nas atividades internas da farmácia'),
('Gerente', 'Responsável pela gestão geral da farmácia'),
('Subgerente', 'Auxilia o gerente e supervisiona as operações'),
('Auxiliar Administrativo', 'Trabalha na parte administrativa da farmácia'),
('Auxiliar de Limpeza', 'Responsável pela higiene e organização do ambiente'),
('Consultor(a) de Dermocosméticos', 'Especialista em produtos dermatológicos e cosméticos'),
('RH', 'Responsável pela gestão de recursos humanos');

-- Inserts para a tabela FUNCIONARIOS
INSERT INTO `FUNCIONARIOS` (`Nome`, `Tipo`, `Documento`, `Telefone`, `ID_Cargo`, `Email`, `Senha`, `Salario`, `Data_Admissao`, `Data_Demissão`, `Status`, `OBS`, `Data_Cadastro`, `Data_Alteracao`) VALUES
('Carlos Silva', 'PF', '987.654.321-00', '(11) 91234-5678', 1, 'carlos@gmail.com',
'$2y$10$cJHKjZnR5Jplc7Rjm.DhKeC6wE0Gcf70g7W39r/.0AcODgTZt4HYa', 3500.00, '2022-01-15', NULL, 'Ativo', 'Farmacêutico responsável turno manhã', NOW(), NULL),
('Ana Martins', 'PF', '654.321.987-01', '(21) 91234-5678', 2, 'ana.martins@gmail.com',
'$2y$10$jPbLuwygPiOwEYIPcXMgROPokXcEz8CyP2D3TY/Pc48L7qWyRKsHq', 1800.00, '2023-03-10', NULL, 'Ativo', 'Atendente com experiência', NOW(), NULL),
('Lucas Pereira', 'PF', '321.654.987-00', '(31) 99876-5432', 3, 'lucas.pereira@gmail.com',
'$2y$10$A.PyAEKGf1cb9KOPxhncC.VlCkkwUyPFW4ST27TIbvR5aoD.kzNre', 2200.00, '2021-11-25', NULL, 'Ativo', NULL, NOW(), NULL),
('Fernanda Lima', 'PF', '789.654.123-00', '(41) 99765-4321', 4, 'fernanda.lima@gmail.com',
'$2y$10$FMJWulZKo7YdXNWnxLZMne/bvB6gSBSmWD3FkBr1qEhVrKnVzV6Om', 4500.00, '2020-09-05', NULL, 'Ativo', 'Gerente da filial principal', NOW(), NULL),
('Roberto Souza', 'PF', '123.789.456-00', '(51) 99123-4567', 5, 'roberto.souza@gmail.com',
'$2y$10$hn9YgN.h78SBQSfT53XereGkUFiKRAUHtUQoic21S.1SEYlG7Ta0y', 4000.00, '2019-06-20', NULL, 'Ativo', NULL, NOW(), NULL),
('Juliana Ribeiro', 'PF', '654.987.321-00', '(61) 98345-6789', 6, 'juliana.ribeiro@gmail.com',
'$2y$10$zfGJhecilqZVn60twhtMxuNus5Hs0K4j1PePupEKVVtv3D9UUn03y', 2500.00, '2023-02-15', NULL, 'Ativo', 'Atua no setor administrativo', NOW(), NULL),
('Diego Costa', 'PF', '321.987.654-00', '(71) 99654-3210', 7, 'diego.costa@gmail.com',
'$2y$10$P1ViT.t.PDbEX2NZ59iy1eRO5HLGCkoaHOMDJrNjuzpzaAVXinw4G', 1500.00, '2023-05-01', NULL, 'Ativo', 'Responsável pela limpeza', NOW(), NULL),
('Mariana Rocha', 'PF', '123.456.789-01', '(81) 99234-5678', 8, 'mariana.rocha@gmail.com',
'$2y$10$m.X16Cxf4UZgJAt5/nO3UOROcUU7TF0te93h4HX8n0E027BgxZUbO', 2800.00, '2022-10-30', NULL, 'Ativo', 'Consultora em dermocosméticos', NOW(), NULL),
('Gustavo Almeida', 'PF', '987.654.321-01', '(91) 98876-5432', 1, 'gustavo.almeida@gmail.com',
'$2y$10$mKIGGy7//Qg5a.eC4ENIuuTzol3oERTIbbOvhY2xUMf0.SEVULy7m', 3600.00, '2021-08-18', NULL, 'Ativo', 'Farmacêutico responsável turno tarde', NOW(), NULL),
('Patrícia Mendes', 'PF', '654.321.987-02', '(71) 99123-9876', 9, 'patricia.mendes@gmail.com',
'$2y$10$5OJVCR.H6ItcvNeEwXAMe.eiz9lZ1yq08nlv0IQbhS53VyOtcZc12', 1900.00, '2020-12-10', NULL, 'Ativo', 'RH - Recrutamento e Seleção', NOW(), NULL);

-- Inserts para a tabela FORNECEDORES
INSERT INTO `FORNECEDORES` (`Nome_Fantasia`, `Nome`, `CNPJ`, `Tel`, `Email`, `CEP`, `Endereco`, `End_Numero`, `Complemento`, `Bairro`, `Cidade`, `Estado`, `Status`, `OBS`) VALUES 
('MedPharma', 'MedPharma Distribuidora LTDA', '12.345.678/0001-11', '(11) 98765-4321', 'contato@medpharma.com', 
 '01001000', 'Rua das Flores', '123', NULL, 'Centro', 'São Paulo', 'SP', 'Ativo', 'Especializada em medicamentos hospitalares'),
('Genéricos Pharma', 'Genéricos Pharma LTDA', '22.345.678/0001-22', '(21) 99876-5432', 'vendas@genericospharma.com', 
 '20040002', 'Av. Brasil', '456', 'Sala 3', 'Copacabana', 'Rio de Janeiro', 'RJ', 'Ativo', 'Fornece genéricos e similares'),
('BioMedic', 'BioMedic S/A', '32.345.678/0001-33', '(31) 97654-3210', 'sac@biomedic.com', 
 '30130010', 'Rua dos Laboratórios', '789', NULL, 'Savassi', 'Belo Horizonte', 'MG', 'Ativo', 'Atende farmácias de manipulação'),
('AntiAlergo', 'AntiAlergo Distribuição LTDA', '42.345.678/0001-44', '(41) 96543-2109', 'contato@antialergo.com', 
 '80060012', 'Av. Saúde', '1011', 'Bloco B', 'Batel', 'Curitiba', 'PR', 'Ativo', 'Especializada em anti-alérgicos'),
('NutriPharma', 'NutriPharma Comércio LTDA', '52.345.678/0001-55', '(51) 95432-1098', 'suporte@nutripharma.com', 
 '90050020', 'Rua das Vitaminas', '1213', NULL, 'Centro Histórico', 'Porto Alegre', 'RS', 'Ativo', 'Fornece suplementos e vitaminas');

-- Inserindo categorias de produtos
INSERT INTO `CATEGORIAS` (`Categoria`) VALUES 
('Analgésicos'),
('Antibióticos'),
('Antialérgicos'),
('Higiene Pessoal'),
('Vitaminas e Suplementos'),
('Antissépticos e Desinfetantes'),
('Soluções e Higiene Ocular');

-- Inserindo produtos
INSERT INTO `PRODUTOS` (`ID_Categoria`, `ID_Fornecedor`, `Nome`, `Med`, `Marca`, `Descricao`, `Status`, `Quant_Minima`, `NCM`, `EAN_GTIN`) VALUES 
(1, 1, 'Paracetamol 750mg', TRUE, 'MedPharma', 'Analgésico para alívio da dor e febre', 'Ativo', 10, '30041012', '7891234567890'),
(1, 2, 'Dipirona Sódica 500mg', TRUE, 'Genéricos Pharma', 'Analgésico e antitérmico', 'Ativo', 10, '30041013', '7891234567891'),
(2, 3, 'Amoxicilina 500mg', TRUE, 'BioMedic', 'Antibiótico de amplo espectro', 'Ativo', 5, '30041014', '7891234567892'),
(3, 1, 'Loratadina 10mg', TRUE, 'AntiAlergo', 'Antialérgico para rinite e urticária', 'Ativo', 5, '30041015', '7891234567893'),
(5, 2, 'Vitamina C 500mg', FALSE, 'NutriPharma', 'Suplemento de vitamina C para imunidade', 'Ativo', 10, '30049099', '7891234567894'),
(6, 3, 'Álcool 70%', FALSE, 'HigiSan', 'Desinfetante para higienização', 'Ativo', 15, '22071090', '7891234567895'),
(7, 1, 'Soro Fisiológico 500ml', FALSE, 'BioClean', 'Solução estéril para limpeza', 'Ativo', 20, '30049099', '7891234567896');

-- Inserindo categorias de medicamentos
INSERT INTO `FAIXAS_MEDICAMENTOS` (`Faixa`) VALUES 
('Medicamento Isento de Prescrição'),
('Amarela'),
('Amarela e Vermelha s/ Retenção de Prescrição'),
('Amarela e Vermelha c/ Retenção de Prescrição'),
('Amarela e Preta'),
('Vermelha s/ Retenção de Prescrição'),
('Vermelha c/ Retenção de Prescrição'),
('Preta');

-- Inserindo medicamentos (associados a produtos já inseridos)
INSERT INTO `MEDICAMENTOS` (`ID_Produto`, `ID_CategoriaMed`, `Prin_Ativo`, `OBS`) VALUES 
(1, 1, 'Paracetamol', 'Uso adulto e pediátrico acima de 12 anos'),
(2, 1, 'Dipirona Sódica', 'Uso adulto e pediátrico acima de 6 anos'),
(3, 7, 'Amoxicilina', 'Uso adulto e pediátrico conforme prescrição médica'),
(4, 1, 'Loratadina', 'Uso diário recomendado para alergias'),
(5, 1, 'Ácido Ascórbico', 'Suplemento alimentar sem necessidade de prescrição');

-- Inserindo estoque (lotes e datas fictícias)
INSERT INTO `ESTOQUE` (`ID_Produto`, `Quantidade`, `Data_Compra`, `Preco_Compra`, `Preco_Venda`, `Lote`, `Vencimento_Lote`) VALUES 
(1, 50, '2025-01-10', 8.50, 12.99, 'L12345', '2026-06-15'),
(2, 100, '2025-02-05', 6.00, 8.50, 'L67890', '2026-07-20'),
(3, 30, '2025-01-20', 18.00, 24.90, 'L54321', '2026-05-30'),
(4, 40, '2025-01-25', 10.50, 15.00, 'L98765', '2027-01-10'),
(5, 60, '2025-02-10', 14.00, 18.75, 'L24680', '2027-03-05'),
(6, 20, '2025-01-25', 10.50, 15.00, 'L98767', '2027-01-10'),
(7, 30, '2025-02-10', 14.00, 18.75, 'L24683', '2027-03-05');

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