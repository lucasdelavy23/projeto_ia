USE lojavestuario;

-- =====================================================
-- 1. LISTA DE CLIENTES
-- =====================================================

SELECT
    id,
    nome,
    cpf,
    email,
    telefone,
    dataCadastro
FROM cliente
ORDER BY nome;


-- =====================================================
-- 2. LISTA DE PRODUTOS
-- =====================================================

SELECT
    id,
    nome,
    descricao,
    preco,
    quantidadeEstoque,
    ativo,
    dataCadastro
FROM produto
ORDER BY nome;


-- =====================================================
-- 3. LISTA DE PEDIDOS COM O RESPECTIVO CLIENTE
-- =====================================================

SELECT
    p.id AS pedido_id,
    p.dataPedido,
    p.status,
    p.valorTotal,
    c.id AS cliente_id,
    c.nome AS cliente
FROM pedido p
INNER JOIN cliente c
    ON p.cliente_id = c.id
ORDER BY p.id;


-- =====================================================
-- 4. DETALHES DE UM PEDIDO
-- Altere o valor abaixo para consultar outro pedido
-- =====================================================

SET @pedidoId = 1;

SELECT
    p.id AS pedido_id,
    p.dataPedido,
    p.status,
    c.id AS cliente_id,
    c.nome AS cliente,
    c.cpf,
    pr.id AS produto_id,
    pr.nome AS produto,
    pp.quantidade,
    pp.precoUnitario,
    pp.subtotal,
    p.valorTotal
FROM pedido p
INNER JOIN cliente c
    ON p.cliente_id = c.id
INNER JOIN pedido_produto pp
    ON p.id = pp.pedido_id
INNER JOIN produto pr
    ON pp.produto_id = pr.id
WHERE p.id = @pedidoId
ORDER BY pr.nome;