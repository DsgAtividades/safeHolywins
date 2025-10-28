# Configuração do CSS Padrão do Sistema

## 📋 Visão Geral

Este documento descreve a configuração completa do CSS padrão aplicado em todo o sistema ERP de Festa Junina. Todas as páginas agora utilizam o arquivo `css/sistema-padrao.css` como base de estilo.

---

## 🎨 Arquivo CSS Padrão

**Localização:** `/css/sistema-padrao.css`

Este arquivo contém todos os estilos padrões do sistema, incluindo:
- Sidebar (Menu Lateral)
- Layout Principal
- Cards (Cartões)
- Tabelas
- Botões
- Badges (Etiquetas)
- Formulários
- Alertas
- Modais
- Responsividade
- Animações e Transições
- Estilos de Impressão
- Custom Scrollbar
- Acessibilidade

---

## 📚 Bibliotecas e CDNs Utilizados

### CSS
```html
<!-- Bootstrap CSS 5.3.0 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons 1.10.0 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

<!-- SweetAlert2 -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<!-- CSS Padrão do Sistema -->
<link href="/hol/css/sistema-padrao.css" rel="stylesheet">
```

### JavaScript
```html
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- QR Code library -->
<script src="https://cdn.jsdelivr.net/npm/html5-qrcode/dist/html5-qrcode.min.js"></script>

<!-- jQuery Mask (apenas mobile) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
```

---

## 📁 Arquivos de Header Atualizados

Todos os arquivos de header foram atualizados para incluir o CSS padrão:

1. **`includes/header.php`** - Header principal desktop
2. **`includes/header_mobile.php`** - Header para páginas mobile
3. **`includes/header_.php`** - Header alternativo
4. **`includes/header_erro.php`** - Header para páginas de erro

---

## 🎨 Paleta de Cores Padrão

```css
:root {
    --bs-primary: #007bff;    /* Azul principal */
    --bs-secondary: #6c757d;  /* Cinza */
    --bs-success: #28a745;    /* Verde */
    --bs-danger: #dc3545;     /* Vermelho */
    --bs-warning: #ffc107;    /* Amarelo */
    --bs-info: #17a2b8;       /* Azul claro */
    --bs-light: #f8f9fa;      /* Cinza claro */
    --bs-dark: #343a40;       /* Cinza escuro */
}
```

---

## 🧩 Componentes Principais

### 1. Sidebar (Menu Lateral)
- **Background:** `#4a90e2`
- **Link Normal:** `#ffffff`
- **Link Hover:** `#357abd`
- **Link Ativo:** `#2c5aa0`

### 2. Cards
```html
<div class="card shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="bi bi-icon-name"></i> Título
        </h6>
    </div>
    <div class="card-body">
        <!-- Conteúdo -->
    </div>
</div>
```

### 3. Tabelas
```html
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="bg-dark">
            <tr>
                <th>Coluna 1</th>
                <th>Coluna 2</th>
            </tr>
        </thead>
        <tbody>
            <!-- Dados -->
        </tbody>
    </table>
</div>
```

### 4. Modais
```html
<div class="modal fade" id="modalId" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-icon"></i> Título
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Conteúdo -->
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Salvar
                </button>
            </div>
        </div>
    </div>
</div>
```

### 5. Botões com Ícones
```html
<button type="button" class="btn btn-primary">
    <i class="bi bi-plus"></i> Novo Item
</button>

<button type="button" class="btn btn-success btn-sm">
    <i class="bi bi-pencil"></i> Editar
</button>

<button type="button" class="btn btn-danger btn-sm">
    <i class="bi bi-trash"></i> Excluir
</button>
```

### 6. Badges
```html
<span class="badge bg-success">Ativo</span>
<span class="badge bg-danger">Inativo</span>
<span class="badge bg-info">Em Estoque</span>
<span class="badge bg-warning">Baixo Estoque</span>
```

### 7. Alertas
```html
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle"></i> Mensagem de sucesso
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
```

### 8. SweetAlert2
```javascript
// Alerta simples
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
    }
});
```

---

## 📱 Responsividade

O sistema utiliza os breakpoints padrão do Bootstrap 5.3.0:

- **xs:** < 576px (mobile)
- **sm:** ≥ 576px (mobile grande)
- **md:** ≥ 768px (tablet)
- **lg:** ≥ 992px (desktop)
- **xl:** ≥ 1200px (desktop grande)
- **xxl:** ≥ 1400px (extra grande)

### Exemplo de uso:
```html
<div class="row">
    <div class="col-12 col-md-6 col-lg-4">
        <!-- Conteúdo -->
    </div>
</div>
```

---

## ✅ Boas Práticas

### SEMPRE:
✅ Usar Bootstrap 5.3.0  
✅ Usar Bootstrap Icons para ícones  
✅ Usar SweetAlert2 para alertas  
✅ Usar `/hol/css/sistema-padrao.css`  
✅ Falar português pt-BR  
✅ Manter código limpo e separado  
✅ Layout responsivo (mobile-first)  
✅ Validar permissões em controllers  
✅ Iniciar arquivos sem espaços  

### NUNCA:
❌ Misturar PHP dentro de strings JavaScript  
❌ Usar estilos inline desnecessários  
❌ Criar arquivos com espaços no início  
❌ Esquecer de validar permissões  
❌ Usar cores fora da paleta sem motivo  

---

## 📝 Checklist para Nova Página

- [ ] Incluir header correto (`header.php` ou `header_mobile.php`)
- [ ] Incluir footer correto (`footer.php` ou `footer_mobile.php`)
- [ ] Usar `.card` para blocos de conteúdo
- [ ] Usar `.table` para tabelas
- [ ] Usar ícones Bootstrap Icons
- [ ] Validar permissões
- [ ] Testar responsividade mobile
- [ ] Usar SweetAlert2 para alertas
- [ ] Sem espaços no início do arquivo
- [ ] Código em português pt-BR

---

## 🔧 Como Usar em Nova Página

### Desktop:
```php
<?php include 'includes/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-icon"></i> Título da Página
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Seu conteúdo aqui -->
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
```

### Mobile:
```php
<?php include 'includes/header_mobile.php'; ?>

<div class="container py-3">
    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0">
                <i class="bi bi-icon"></i> Título da Página
            </h6>
        </div>
        <div class="card-body">
            <!-- Seu conteúdo aqui -->
        </div>
    </div>
</div>

<?php include 'includes/footer_mobile.php'; ?>
```

---

## 📞 Suporte

Para mais informações sobre os padrões de CSS e componentes, consulte:
- **Arquivo CSS:** `/css/sistema-padrao.css`
- **Bootstrap 5.3 Docs:** https://getbootstrap.com/docs/5.3/
- **Bootstrap Icons:** https://icons.getbootstrap.com/
- **SweetAlert2 Docs:** https://sweetalert2.github.io/

---

**Última Atualização:** Outubro 2025  
**Versão:** 1.0  
**Framework:** Bootstrap 5.3.0

