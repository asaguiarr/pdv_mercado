# TODO: Correções no Sistema PDV Laravel

## 1. Criar modelo CashMovement
- Criar app/Models/CashMovement.php com fillable e casts apropriados.

## 2. Corrigir processo de vendas no PdvController
- Remover decremento/incremento de estoque em addItem/removeItem (não tocar estoque até venda confirmada).
- Ajustar processSale: decrementar estoque apenas na confirmação.
- Para entregas, não decrementar estoque na venda, mas sim na entrega (implementar confirmação de entrega).
- Adicionar método de pagamento 'prazo' para vendas a prazo.
- Para vendas a prazo, definir payment_status como 'pending' e criar AccountsReceivable.
- Registrar movimento de caixa apenas para pagamentos em dinheiro.

## 3. Integrar contas a receber
- Garantir que vendas a prazo criem registro em accounts_receivable.
- Implementar método para receber pagamentos e quitar débitos.
- Atualizar saldo do cliente automaticamente.

## 4. Controle de fluxo de caixa
- Integrar vendas no relatório de fluxo de caixa.
- Garantir que abertura/fechamento de caixa considere vendas do dia.

## 5. Controle de estoque
- Decrementar estoque apenas em vendas confirmadas.
- Para entregas, decrementar na confirmação de entrega.
- Incrementar estoque em entradas (já implementado em StockController).
- Corrigir relatório de estoque para mostrar agregados corretos.

## 6. Atualizar testes PHPUnit
- Substituir @test por #[Test] em SaleProcessTest.php e StockTest.php.
- Corrigir referências a 'price' para 'sale_price' se houver.

## 7. Interface (Bootstrap)
- Adicionar card de "Contas a Receber" no dashboard.
- Adicionar links para "Contas a Receber" e "Fluxo de Caixa" no sidebar.
- Garantir que visualização de clientes mostre apenas Nome e Contato na listagem, e detalhes completos ao clicar.

## 8. Executar testes
- Rodar php artisan test após correções.

## 9. Documentar alterações
- Adicionar comentários no código indicando correções.
