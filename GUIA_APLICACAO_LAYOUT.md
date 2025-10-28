# 🎯 GUIA RÁPIDO - APLICAR LAYOUT PREMIUM

## Como aplicar o novo layout nas páginas existentes

---

## 📝 TEMPLATE BÁSICO DE PÁGINA

```php
<?php
require_once 'includes/conexao.php';
require_once 'includes/verifica_permissao.php';
require_once 'includes/funcoes.php';

// Verificar permissão
if (!temPermissao('nome_permissao')) {
    header('Location: index.php');
    exit;
}

// Lógica da página aqui...

include 'includes/header.php';
?>

<div class="container-fluid py-4">
    <!-- Cabeçalho da Página -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800 font-weight-bold">
                <i class="bi bi-icon-name text-primary"></i> Título da Página
            </h1>
            <p class="text-muted mb-0">Descrição breve da funcionalidade</p>
        </div>
    </div>

    <!-- Conteúdo Principal -->
    <div class="card hover-lift shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 font-weight-bold text-primary">
                <i class="bi bi-icon"></i> Seção Principal
            </h5>
        </div>
        <div class="card-body">
            <!-- Seu conteúdo aqui -->
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
```

---

## 🎨 COMPONENTES PRONTOS

### 1. CARD COM ESTATÍSTICA

```html
<div class="col-md-6 col-xl-3">
    <div class="card hover-lift">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h6 class="text-muted text-uppercase mb-0" style="font-size: 0.75rem;">
                        Título
                    </h6>
                </div>
                <div class="icon-shape bg-primary text-white rounded-circle p-3">
                    <i class="bi bi-icon-name" style="font-size: 1.5rem;"></i>
                </div>
            </div>
            <h2 class="mb-0 font-weight-bold">1.234</h2>
            <div class="mt-3">
                <a href="#" class="btn btn-primary btn-sm w-100">
                    <i class="bi bi-arrow-right-circle"></i> Ver Detalhes
                </a>
            </div>
        </div>
    </div>
</div>
```

---

### 2. TABELA PREMIUM

```html
<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 font-weight-bold text-primary">
                <i class="bi bi-table"></i> Lista de Itens
            </h5>
            <a href="novo.php" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Novo
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Coluna 1</th>
                        <th>Coluna 2</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Dado 1</td>
                        <td>Dado 2</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
```

---

### 3. FORMULÁRIO PREMIUM

```html
<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 font-weight-bold text-primary">
            <i class="bi bi-plus-square"></i> Adicionar Novo
        </h5>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Campo 1</label>
                    <input type="text" class="form-control" name="campo1" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Campo 2</label>
                    <input type="text" class="form-control" name="campo2" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Descrição</label>
                    <textarea class="form-control" name="descricao" rows="3"></textarea>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2 justify-content-end">
                <a href="lista.php" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Salvar
                </button>
            </div>
        </form>
    </div>
</div>
```

---

### 4. MODAL PREMIUM

```html
<div class="modal fade" id="meuModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-icon"></i> Título do Modal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Conteúdo do modal -->
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Fechar
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

### 5. BOTÕES DE AÇÃO

```html
<!-- Botão Primário -->
<button class="btn btn-primary">
    <i class="bi bi-plus"></i> Adicionar
</button>

<!-- Botão Secundário -->
<button class="btn btn-secondary">
    <i class="bi bi-arrow-left"></i> Voltar
</button>

<!-- Botão Sucesso -->
<button class="btn btn-success">
    <i class="bi bi-check"></i> Confirmar
</button>

<!-- Botão Perigo -->
<button class="btn btn-danger">
    <i class="bi bi-trash"></i> Excluir
</button>

<!-- Botão Info -->
<button class="btn btn-info text-white">
    <i class="bi bi-info-circle"></i> Informação
</button>

<!-- Botão Warning -->
<button class="btn btn-warning text-white">
    <i class="bi bi-exclamation-triangle"></i> Atenção
</button>
```

---

### 6. BADGES COLORIDOS

```html
<!-- Status Ativo -->
<span class="badge bg-success">
    <i class="bi bi-check-circle"></i> Ativo
</span>

<!-- Status Inativo -->
<span class="badge bg-danger">
    <i class="bi bi-x-circle"></i> Inativo
</span>

<!-- Status Pendente -->
<span class="badge bg-warning">
    <i class="bi bi-clock"></i> Pendente
</span>

<!-- Status Em Progresso -->
<span class="badge bg-info">
    <i class="bi bi-arrow-clockwise"></i> Em Progresso
</span>
```

---

### 7. ALERTAS MODERNOS

```html
<!-- Alerta de Sucesso -->
<div class="alert alert-success" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i>
    Operação realizada com sucesso!
</div>

<!-- Alerta de Erro -->
<div class="alert alert-danger" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    Ocorreu um erro ao processar a solicitação.
</div>

<!-- Alerta de Informação -->
<div class="alert alert-info" role="alert">
    <i class="bi bi-info-circle-fill me-2"></i>
    Informação importante para o usuário.
</div>
```

---

### 8. GRID DE AÇÕES RÁPIDAS

```html
<div class="card shadow-lg">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 font-weight-bold text-primary">
            <i class="bi bi-lightning-charge"></i> Ações Rápidas
        </h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="#" class="btn btn-primary w-100 py-3 hover-lift">
                    <i class="bi bi-plus-square d-block mb-2" style="font-size: 2rem;"></i>
                    <strong>Adicionar</strong>
                </a>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="#" class="btn btn-success w-100 py-3 hover-lift">
                    <i class="bi bi-check-square d-block mb-2" style="font-size: 2rem;"></i>
                    <strong>Confirmar</strong>
                </a>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="#" class="btn btn-info w-100 py-3 text-white hover-lift">
                    <i class="bi bi-eye d-block mb-2" style="font-size: 2rem;"></i>
                    <strong>Visualizar</strong>
                </a>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="#" class="btn btn-warning w-100 py-3 text-white hover-lift">
                    <i class="bi bi-gear d-block mb-2" style="font-size: 2rem;"></i>
                    <strong>Configurar</strong>
                </a>
            </div>
        </div>
    </div>
</div>
```

---

## 🎯 CLASSES ÚTEIS

### Animações:
```html
<div class="fade-in">Aparece suavemente</div>
<div class="hover-lift">Flutua ao passar mouse</div>
<div class="pulse">Pulsa continuamente</div>
```

### Sombras:
```html
<div class="shadow-sm">Sombra pequena</div>
<div class="shadow-md">Sombra média</div>
<div class="shadow-lg">Sombra grande</div>
```

### Espaçamentos:
```html
<div class="py-6">Padding vertical 4rem</div>
<div class="my-6">Margin vertical 4rem</div>
```

---

## 🔄 CHECKLIST DE MIGRAÇÃO

Ao atualizar uma página existente:

- [ ] Verificar se o `header.php` está incluído
- [ ] Substituir `container` por `container-fluid`
- [ ] Adicionar cabeçalho da página com ícone
- [ ] Adicionar classe `hover-lift` aos cards
- [ ] Atualizar botões com ícones Bootstrap Icons
- [ ] Usar classes `shadow-sm` ou `shadow-lg`
- [ ] Adicionar `bg-white` nos card-headers
- [ ] Usar `font-weight-bold` nos títulos
- [ ] Adicionar `text-primary` nos ícones
- [ ] Verificar responsividade (col-md, col-lg)

---

## 🎨 ÍCONES RECOMENDADOS

### Por Categoria:

**Dashboard/Início:**
- `bi bi-house-door`
- `bi bi-graph-up`
- `bi bi-speedometer2`

**Pessoas/Usuários:**
- `bi bi-people`
- `bi bi-person-circle`
- `bi bi-person-plus`

**Produtos:**
- `bi bi-box-seam`
- `bi bi-basket`
- `bi bi-tag`

**Vendas:**
- `bi bi-cart-check`
- `bi bi-receipt`
- `bi bi-cash-coin`

**Relatórios:**
- `bi bi-file-earmark-bar-graph`
- `bi bi-graph-up-arrow`
- `bi bi-pie-chart`

**Configurações:**
- `bi bi-gear`
- `bi bi-sliders`
- `bi bi-tools`

**Ações:**
- `bi bi-plus-circle` - Adicionar
- `bi bi-pencil` - Editar
- `bi bi-trash` - Excluir
- `bi bi-eye` - Visualizar
- `bi bi-download` - Baixar
- `bi bi-upload` - Enviar
- `bi bi-search` - Buscar
- `bi bi-filter` - Filtrar

---

## 💡 DICAS FINAIS

### 1. Consistência:
- Use sempre os mesmos padrões de ícones
- Mantenha os espaçamentos consistentes
- Use a mesma estrutura de cabeçalho

### 2. Performance:
- Evite animações em muitos elementos simultaneamente
- Use `will-change` com cuidado
- Otimize imagens e ícones

### 3. Acessibilidade:
- Sempre use labels nos formulários
- Adicione `aria-label` em botões com apenas ícones
- Mantenha contraste adequado

### 4. Responsividade:
- Teste em diferentes tamanhos de tela
- Use classes `col-md`, `col-lg` adequadamente
- Considere `d-none d-md-block` para ocultar em mobile

---

## 📚 RECURSOS ADICIONAIS

### Bootstrap Icons:
https://icons.getbootstrap.com/

### Bootstrap 5.3 Docs:
https://getbootstrap.com/docs/5.3/

### Google Fonts (Inter):
https://fonts.google.com/specimen/Inter

---

## ✅ EXEMPLO PRÁTICO

Vamos aplicar o layout em uma página de listagem:

```php
<?php
require_once 'includes/conexao.php';
require_once 'includes/verifica_permissao.php';
require_once 'includes/funcoes.php';

if (!temPermissao('visualizar_produtos')) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM produtos ORDER BY nome");
$produtos = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="container-fluid py-4">
    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800 font-weight-bold">
                <i class="bi bi-box-seam text-primary"></i> Produtos
            </h1>
            <p class="text-muted mb-0">Gerencie o catálogo de produtos</p>
        </div>
        <a href="produtos_novo.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Novo Produto
        </a>
    </div>

    <!-- Tabela -->
    <div class="card shadow-sm hover-lift">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 font-weight-bold text-primary">
                <i class="bi bi-list-ul"></i> Lista de Produtos
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Preço</th>
                            <th>Estoque</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td><?= escapar($produto['nome']) ?></td>
                            <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                            <td>
                                <span class="badge bg-<?= $produto['estoque'] > 10 ? 'success' : 'danger' ?>">
                                    <?= $produto['estoque'] ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-<?= $produto['ativo'] ? 'success' : 'danger' ?>">
                                    <?= $produto['ativo'] ? 'Ativo' : 'Inativo' ?>
                                </span>
                            </td>
                            <td>
                                <a href="produtos_editar.php?id=<?= $produto['id'] ?>" 
                                   class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="produtos_excluir.php?id=<?= $produto['id'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Deseja excluir?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
```

---

**Pronto! Agora você pode aplicar o layout premium em qualquer página do sistema! 🚀**

