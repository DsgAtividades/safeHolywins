# 🚀 GUIA RÁPIDO - CSS PADRÃO DO SISTEMA

## 📋 Componentes Prontos para Usar

---

## 🎴 CARD BÁSICO

```html
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="bi bi-box"></i> Título do Card
        </h6>
    </div>
    <div class="card-body">
        Conteúdo aqui
    </div>
</div>
```

---

## 📊 TABELA PADRÃO

```html
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="bg-dark">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Item 1</td>
                <td><span class="badge bg-success">Ativo</span></td>
                <td>
                    <button class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil"></i> Editar
                    </button>
                    <button class="btn btn-danger btn-sm">
                        <i class="bi bi-trash"></i> Excluir
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
```

---

## 🔘 BOTÕES

```html
<!-- Botão Primário -->
<button class="btn btn-primary">
    <i class="bi bi-plus"></i> Novo
</button>

<!-- Botão Secundário -->
<button class="btn btn-secondary">
    <i class="bi bi-arrow-left"></i> Voltar
</button>

<!-- Botão Sucesso -->
<button class="btn btn-success">
    <i class="bi bi-check"></i> Salvar
</button>

<!-- Botão Perigo -->
<button class="btn btn-danger">
    <i class="bi bi-trash"></i> Excluir
</button>

<!-- Botão Warning -->
<button class="btn btn-warning">
    <i class="bi bi-exclamation-triangle"></i> Atenção
</button>

<!-- Botões Pequenos -->
<button class="btn btn-primary btn-sm">
    <i class="bi bi-pencil"></i> Editar
</button>
```

---

## 🏷️ BADGES

```html
<span class="badge bg-success">Ativo</span>
<span class="badge bg-danger">Inativo</span>
<span class="badge bg-warning">Pendente</span>
<span class="badge bg-info">Em Estoque</span>
<span class="badge bg-primary">Novo</span>
<span class="badge bg-secondary">Processando</span>
```

---

## 📝 FORMULÁRIO

```html
<form action="" method="POST">
    <div class="mb-3">
        <label for="nome" class="form-label">Nome *</label>
        <input type="text" class="form-control" id="nome" name="nome" required>
    </div>
    
    <div class="mb-3">
        <label for="email" class="form-label">E-mail *</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    
    <div class="mb-3">
        <label for="status" class="form-label">Status *</label>
        <select class="form-select" id="status" name="status" required>
            <option value="">Selecione...</option>
            <option value="1">Ativo</option>
            <option value="0">Inativo</option>
        </select>
    </div>
    
    <div class="mb-3">
        <label for="observacao" class="form-label">Observação</label>
        <textarea class="form-control" id="observacao" name="observacao" rows="3"></textarea>
    </div>
    
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-check"></i> Salvar
    </button>
    <a href="lista.php" class="btn btn-secondary">
        <i class="bi bi-x"></i> Cancelar
    </a>
</form>
```

---

## 🔔 ALERTAS

```html
<!-- Sucesso -->
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle"></i> Operação realizada com sucesso!
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>

<!-- Erro -->
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-x-circle"></i> Erro ao processar operação!
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>

<!-- Warning -->
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle"></i> Atenção! Verifique os dados.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>

<!-- Info -->
<div class="alert alert-info alert-dismissible fade show" role="alert">
    <i class="bi bi-info-circle"></i> Informação importante.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
```

---

## 🪟 MODAL

```html
<!-- Botão para abrir modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#meuModal">
    <i class="bi bi-plus"></i> Abrir Modal
</button>

<!-- Modal -->
<div class="modal fade" id="meuModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-box"></i> Título do Modal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Conteúdo do modal -->
                <p>Conteúdo aqui...</p>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Confirmar
                </button>
            </div>
        </div>
    </div>
</div>
```

---

## 🍬 SWEETALERT2

```javascript
// Alerta Simples
Swal.fire({
    title: 'Sucesso!',
    text: 'Operação realizada com sucesso',
    icon: 'success',
    timer: 3000,
    showConfirmButton: false
});

// Confirmação
Swal.fire({
    title: 'Tem certeza?',
    text: "Esta ação não poderá ser desfeita!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#007bff',
    cancelButtonColor: '#dc3545',
    confirmButtonText: 'Sim, confirmar!',
    cancelButtonText: 'Cancelar'
}).then((result) => {
    if (result.isConfirmed) {
        // Ação confirmada
        window.location.href = 'processar.php?id=123';
    }
});

// Erro
Swal.fire({
    title: 'Erro!',
    text: 'Não foi possível processar a operação',
    icon: 'error',
    confirmButtonText: 'OK'
});

// Pergunta
Swal.fire({
    title: 'Deseja continuar?',
    text: "Você está prestes a realizar esta ação",
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Sim',
    cancelButtonText: 'Não'
});
```

---

## 📱 GRID RESPONSIVO

```html
<!-- 1 coluna em mobile, 2 em tablet, 3 em desktop -->
<div class="row">
    <div class="col-12 col-md-6 col-lg-4 mb-3">
        <div class="card">
            <div class="card-body">Card 1</div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-4 mb-3">
        <div class="card">
            <div class="card-body">Card 2</div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-4 mb-3">
        <div class="card">
            <div class="card-body">Card 3</div>
        </div>
    </div>
</div>

<!-- 1 coluna em mobile, 2 em desktop -->
<div class="row">
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-body">Conteúdo Esquerdo</div>
        </div>
    </div>
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-body">Conteúdo Direito</div>
        </div>
    </div>
</div>
```

---

## 🎨 CORES

```html
<!-- Texto -->
<p class="text-primary">Azul primário</p>
<p class="text-secondary">Cinza secundário</p>
<p class="text-success">Verde sucesso</p>
<p class="text-danger">Vermelho perigo</p>
<p class="text-warning">Amarelo aviso</p>
<p class="text-info">Azul informação</p>
<p class="text-muted">Cinza muted</p>

<!-- Background -->
<div class="bg-primary text-white p-3">Fundo azul</div>
<div class="bg-success text-white p-3">Fundo verde</div>
<div class="bg-danger text-white p-3">Fundo vermelho</div>
<div class="bg-warning p-3">Fundo amarelo</div>
<div class="bg-info text-white p-3">Fundo azul claro</div>
<div class="bg-light p-3">Fundo claro</div>
<div class="bg-dark text-white p-3">Fundo escuro</div>
```

---

## 🔍 ÍCONES MAIS USADOS

```html
<i class="bi bi-house"></i> <!-- Home -->
<i class="bi bi-plus"></i> <!-- Adicionar -->
<i class="bi bi-pencil"></i> <!-- Editar -->
<i class="bi bi-trash"></i> <!-- Excluir -->
<i class="bi bi-search"></i> <!-- Pesquisar -->
<i class="bi bi-eye"></i> <!-- Visualizar -->
<i class="bi bi-download"></i> <!-- Download -->
<i class="bi bi-upload"></i> <!-- Upload -->
<i class="bi bi-check"></i> <!-- Confirmar -->
<i class="bi bi-x"></i> <!-- Fechar -->
<i class="bi bi-arrow-left"></i> <!-- Voltar -->
<i class="bi bi-arrow-right"></i> <!-- Avançar -->
<i class="bi bi-person"></i> <!-- Usuário -->
<i class="bi bi-box"></i> <!-- Produto -->
<i class="bi bi-cart"></i> <!-- Carrinho -->
<i class="bi bi-cash"></i> <!-- Dinheiro -->
<i class="bi bi-credit-card"></i> <!-- Cartão -->
<i class="bi bi-printer"></i> <!-- Imprimir -->
<i class="bi bi-file-pdf"></i> <!-- PDF -->
<i class="bi bi-gear"></i> <!-- Configurações -->
```

**Mais ícones:** https://icons.getbootstrap.com/

---

## 📋 ESPAÇAMENTOS

```html
<!-- Margin -->
<div class="m-1">margin 0.25rem</div>
<div class="m-2">margin 0.5rem</div>
<div class="m-3">margin 1rem</div>
<div class="m-4">margin 1.5rem</div>
<div class="m-5">margin 3rem</div>

<!-- Padding -->
<div class="p-1">padding 0.25rem</div>
<div class="p-2">padding 0.5rem</div>
<div class="p-3">padding 1rem</div>
<div class="p-4">padding 1.5rem</div>
<div class="p-5">padding 3rem</div>

<!-- Específicos -->
<div class="mt-3">margin-top</div>
<div class="mb-3">margin-bottom</div>
<div class="ms-3">margin-start (left)</div>
<div class="me-3">margin-end (right)</div>
<div class="mx-3">margin horizontal</div>
<div class="my-3">margin vertical</div>
```

---

## 🎯 DICA RÁPIDA

**Estrutura básica de uma página:**

```php
<?php include 'includes/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            
            <!-- Cabeçalho da página -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="bi bi-box"></i> Título da Página
                </h1>
                <button class="btn btn-primary">
                    <i class="bi bi-plus"></i> Novo Item
                </button>
            </div>
            
            <!-- Card Principal -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <!-- Conteúdo aqui -->
                </div>
            </div>
            
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
```

---

**Documentação completa:** `CONFIGURACAO_CSS_PADRAO.md`

