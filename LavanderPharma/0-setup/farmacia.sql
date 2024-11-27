-- -----------------------------------------------------
-- Criando BATABASE `lavanderpharma`
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `lavanderpharma` DEFAULT CHARACTER SET UTF8MB4;
USE `lavanderpharma`;
/*drop database lavanderpharma;*/

-- -----------------------------------------------------
-- Table `CEPS`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `CEPS` (
    `ID_CEP` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `CEP` VARCHAR(10) UNIQUE,
    `Endereco_CEP` VARCHAR(255) DEFAULT NULL,
    `Bairro_CEP` VARCHAR(255) DEFAULT NULL,
    `Cidade_CEP` VARCHAR(255) DEFAULT NULL,
    `Estado_CEP` CHAR(2) DEFAULT NULL
) ENGINE = InnoDB;
/*drop table CEPS;*/
/*select * from CEPS;*/

-- -----------------------------------------------------
-- Table `CLIENTES`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `CLIENTES` (
    `ID_Cliente` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `Nome_Cliente` VARCHAR(255) DEFAULT NULL,
    `Tipo_Cliente` ENUM('PJ', 'PF') DEFAULT NULL,
    `Documento_Cliente` VARCHAR(18) DEFAULT NULL,
    `Tel_Cliente` VARCHAR(20) DEFAULT NULL,
    `Email_Cliente` VARCHAR(100) DEFAULT NULL,
    `Senha_Cliente` VARCHAR(100) DEFAULT NULL,
    `CEP` VARCHAR(10) DEFAULT NULL,
    `Endereco_Cliente` VARCHAR(255) DEFAULT NULL,
    `EndNumero_Cliente` VARCHAR(255) DEFAULT NULL,
    `Complemento_Cliente` VARCHAR(100) DEFAULT NULL,
    `Bairro_Cliente` VARCHAR(255) DEFAULT NULL,
    `Cidade_Cliente` VARCHAR(255) DEFAULT NULL,
    `Estado_Cliente` CHAR(2) DEFAULT NULL,
    `DataNasc_Cliente` DATE DEFAULT NULL,
    `Status_Cliente` ENUM('0', '1') DEFAULT NULL,
    `DataCadastro_Cliente` DATETIME DEFAULT NULL,
    `DataAlteracao_Cliente` DATETIME DEFAULT NULL,
    FOREIGN KEY (`CEP`) REFERENCES `CEPS` (`CEP`)
) ENGINE = InnoDB;
/* drop table CLIENTES; */
/* select * from CLIENTES; */

-- -----------------------------------------------------
-- Table `FUNCIONARIOS`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `FUNCIONARIOS` (
    `ID_Funcionario` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `Nome_Funcionario` VARCHAR(255) DEFAULT NULL,
    `Tipo_Funcionario` ENUM('PJ', 'PF') DEFAULT NULL,
    `Documento_Funcionario` VARCHAR(18) DEFAULT NULL,
    `Tel_Funcionario` VARCHAR(20) DEFAULT NULL,
    `Cargo_Funcionario` ENUM('Farmacêutico',
                             'Auxilizar de Farmácia',
                             'Atendente de Farmácia',
                             'Gerente',
                             'Subgerente',
                             'RH',
                             'Auxiliar Administrativo',
                             'Auxiliar de Limpeza',
                             'Consultor(a) de Dermocosméticos') DEFAULT NULL,
    `Email_Funcionario` VARCHAR(255) DEFAULT NULL,
    `Senha_Funcionario` VARCHAR(100) DEFAULT NULL,
    `Salario_Funcionario` DECIMAL(10,2) DEFAULT NULL,
    `DataAdmissao_Funcionario` DATE DEFAULT NULL,
    `Status_Funcionario` ENUM('0', '1') DEFAULT NULL,
    `DataCadastro_Funcionario` DATETIME DEFAULT NULL,
    `DataAlteracao_Funcionario` DATETIME DEFAULT NULL
) ENGINE = InnoDB;
/* drop table FUNCIONARIOS; */
/* select * from FUNCIONARIOS; */

-- -----------------------------------------------------
-- Table `FORNECEDORES`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `FORNECEDORES` (
    `ID_Fornecedor` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `Nome_Fornecedor` VARCHAR(255) DEFAULT NULL,
    `CNPJ` VARCHAR(18) DEFAULT NULL,
    `Tel_Fornecedor` VARCHAR(20) DEFAULT NULL,
    `Email_Fornecedor` VARCHAR(255) DEFAULT NULL,
    `CEP` VARCHAR(10) DEFAULT NULL,
    `Endecero_Fornecedor` VARCHAR(255) DEFAULT NULL,
    `EndNumero_Fornecedor` VARCHAR(10) DEFAULT NULL,
    `Complemento_Fornecedor` VARCHAR(100) DEFAULT NULL,
    `Bairro_Fornecedor` VARCHAR(255) DEFAULT NULL,
    `Cidade_Fornecedor` VARCHAR(255) DEFAULT NULL,
    `Estado_Fornecedor` CHAR(2) DEFAULT NULL,
    `Status_Fornecedor` ENUM('0', '1') DEFAULT NULL,
    `DataCadastro_Fornecedor` DATETIME DEFAULT NULL,
    `DataAlteracao_Fornecedor` DATETIME DEFAULT NULL,
    FOREIGN KEY (`CEP`) REFERENCES `CEPS` (`CEP`)
) ENGINE = InnoDB;
/* drop table FORNECEDORES; */
/* select * from FORNECEDORES; */

-- -----------------------------------------------------
-- Table `PRODUTOS`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `PRODUTOS` (
    `ID_Produto` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `Nome_Produto` VARCHAR(255) DEFAULT NULL,
    `Descricao_Produto` VARCHAR(255) DEFAULT NULL,
    `Categoria_Produto` ENUM('Medicamentos de prescrição',
                             'Medicamentos isentos de prescrição',
                             'Medicamentos genéricos e similares',
                             'Produtos de saúde',
                             'Dermocosméticos e cuidados pessoais',
                             'Higiene e beleza',
                             'Infantil e maternidade',
                             'Alimentos e nutrição',
                             'Produtos de conveniência',
                             'Cuidados com os idosos',
                             'Produtos para animais',
                             'Produtos naturais e homeopáticos') DEFAULT NULL,
    `PrecoVenda_Produto` DECIMAL(10,2) DEFAULT NULL,
    `PrecoCusto_Produto` DECIMAL(10,2) DEFAULT NULL,
    `QuantidadeEstoque_Produto` INT DEFAULT NULL,
    `CodigoBarras_Produto` VARCHAR(13) DEFAULT NULL,
    `ID_Fornecedor` INT DEFAULT NULL,
    FOREIGN KEY (`ID_Fornecedor`) REFERENCES `FORNECEDORES` (`ID_Fornecedor`)
) ENGINE = InnoDB;
/* drop table PRODUTOS; */
/* select * from PRODUTOS; */

-- -----------------------------------------------------
-- Table `VENDAS`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `VENDAS` (
    `ID_Venda` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `ID_Cliente` INT DEFAULT NULL,
    `ID_Funcionario` INT DEFAULT NULL,
    `DataVenda_Venda` DATETIME DEFAULT NULL,
    `ValorTotal_Venda` DECIMAL(10,2) DEFAULT NULL,
    `FormaPagamento_Venda` ENUM('Dinheiro', 'Crédito', 'Débito', 'PIX') DEFAULT NULL,
    FOREIGN KEY (`ID_Cliente`) REFERENCES `CLIENTES` (`ID_Cliente`),
    FOREIGN KEY (`ID_Funcionario`) REFERENCES `FUNCIONARIOS` (`ID_Funcionario`)
) ENGINE = InnoDB;
/* drop table VENDAS; */
/* select * from VENDAS; */

-- -----------------------------------------------------
-- Table `ITENSVENDIDOS`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ITENSVENDIDOS` (
    `ID_ItemVenda` INT AUTO_INCREMENT PRIMARY KEY,
    `ID_Venda` INT DEFAULT NULL,
    `ID_Produto` INT DEFAULT NULL,
    `Quantidade_ItemVenda` INT DEFAULT NULL,
    `PrecoUnitario_ItemVenda` DECIMAL(10,2) DEFAULT NULL,
    FOREIGN KEY (`ID_Venda`) REFERENCES `VENDAS` (`ID_Venda`),
    FOREIGN KEY (`ID_Produto`) REFERENCES `PRODUTOS` (`ID_Produto`)
) ENGINE = InnoDB;
/* drop table ITENSVENDIDOS; */
/* select * from ITENSVENDIDOS; */

-- -----------------------------------------------------
-- Table `PRESCRICOES`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `PRESCRICOES` (
    `ID_Prescricao` INT AUTO_INCREMENT PRIMARY KEY,
    `ID_Cliente` INT DEFAULT NULL,
    `DataEmissao_Prescricao` DATE DEFAULT NULL,
    `DataRegistro_Prescricao` DATETIME DEFAULT NULL,
    FOREIGN KEY (`ID_Cliente`) REFERENCES `CLIENTES` (`ID_Cliente`)
) ENGINE = InnoDB;
/* drop table PRESCRICOES; */
/* select * from PRESCRICOES; */

-- -----------------------------------------------------
-- Table `PEDIDOS`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `PEDIDOS` (
    `ID_Pedido` INT AUTO_INCREMENT PRIMARY KEY,
    `ID_Produto` INT DEFAULT NULL,
    `ID_Fornecedor` INT DEFAULT NULL,
    `Quantidade_Pedido` INT DEFAULT NULL,
    `DataPedido_Pedido` DATETIME DEFAULT NULL,
    `DataEntrega_Pedido` DATETIME DEFAULT NULL,
    FOREIGN KEY (`ID_Produto`) REFERENCES `PRODUTOS` (`ID_Produto`),
    FOREIGN KEY (`ID_Fornecedor`) REFERENCES `FORNECEDORES` (`ID_Fornecedor`)
) ENGINE = InnoDB;
/* drop table PEDIDOS; */
/* select * from PEDIDOS; */