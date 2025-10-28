# 🎯 Guia Rápido - Configuração de Cartões

## Como Ativar/Desativar CPF e Nome Obrigatórios

### 📍 Método 1: Interface Visual (RECOMENDADO)

1. Acesse: `http://localhost/hol/configuracoes_sistema.php`
2. Na seção "Configurações de Cartões":
   - **CPF Obrigatório**: Liga/Desliga o switch
   - **Nome Obrigatório**: Liga/Desliga o switch
   - **Custo Fixo**: Defina o valor em R$
3. Clique em "Salvar Configurações"
4. Recarregue a página

### 📍 Método 2: Edição Manual

Edite o arquivo: `config/config.php`

```php
// Para tornar CPF e Nome OPCIONAIS:
define('CARTAO_CPF_OBRIGATORIO', false);
define('CARTAO_NOME_OBRIGATORIO', false);

// Para tornar CPF e Nome OBRIGATÓRIOS:
define('CARTAO_CPF_OBRIGATORIO', true);
define('CARTAO_NOME_OBRIGATORIO', true);
```

---

## 🎫 Como Funciona no Cadastro

### ✅ CPF e Nome OPCIONAIS (Recomendado para eventos rápidos)

**Configuração:**
```php
define('CARTAO_CPF_OBRIGATORIO', false);
define('CARTAO_NOME_OBRIGATORIO', false);
```

**No Formulário:**
- Campos aparecem com "(Opcional)"
- Pode pular direto para o código do cartão
- Sistema preenche automaticamente

**Exemplo:**
1. Escaneia cartão: `123456`
2. Deixa nome e CPF em branco
3. ✓ Sistema cria automaticamente:
   - Nome: "Cartão 123456"
   - CPF: "00000167890" (gerado único)

---

### ⚠️ CPF e Nome OBRIGATÓRIOS (Controle completo)

**Configuração:**
```php
define('CARTAO_CPF_OBRIGATORIO', true);
define('CARTAO_NOME_OBRIGATORIO', true);
```

**No Formulário:**
- Campos aparecem com asterisco vermelho *
- Obrigatório preencher antes de continuar
- Validação completa

**Exemplo:**
1. Escaneia cartão: `123456`
2. Preenche nome: "João Silva"
3. Preenche CPF: "123.456.789-01"
4. ✓ Sistema valida e cria

---

## 🚀 Atalhos Rápidos

### Para Cadastro Rápido (Sem CPF/Nome):
```
Arquivo: config/config.php
Alterar para: false, false
```

### Para Cadastro Completo (Com CPF/Nome):
```
Arquivo: config/config.php
Alterar para: true, true
```

### Para Cadastro Misto (Apenas CPF):
```
Arquivo: config/config.php
CPF: true
Nome: false
```

---

## 📱 Resultado Visual

### Quando OPCIONAL:
```
┌─────────────────────────────────┐
│ Nome (Opcional)                 │
│ [____________]                  │
│ Se não informado, será usado:   │
│ "Cartão [código]"              │
└─────────────────────────────────┘
```

### Quando OBRIGATÓRIO:
```
┌─────────────────────────────────┐
│ Nome *                          │
│ [____________]                  │
│ O nome deve ter pelo menos 3    │
│ caracteres                      │
└─────────────────────────────────┘
```

---

## ✨ Configurações Atuais

**Status Atual:**
- CPF: **Opcional** (pode deixar em branco)
- Nome: **Opcional** (pode deixar em branco)
- Custo: **R$ 0,00**

Para verificar: Acesse `configuracoes_sistema.php`

---

## 🔐 Permissões Necessárias

- **Para Alocar Cartões:** `gerenciar_cartoes`
- **Para Alterar Configurações:** `acessar_configuracoes`

---

## 💡 Dicas

1. **Eventos Rápidos:** Use configurações opcionais
2. **Eventos Controlados:** Use configurações obrigatórias
3. **Flexibilidade:** Pode alternar a qualquer momento
4. **Backup:** As configurações ficam salvas no arquivo

---

## 📋 Checklist de Uso

**Antes do Evento:**
- [ ] Definir se CPF/Nome serão obrigatórios
- [ ] Configurar em `configuracoes_sistema.php`
- [ ] Testar cadastro de 1 cartão
- [ ] Verificar se está funcionando

**Durante o Evento:**
- [ ] Usar sempre o mesmo padrão
- [ ] Não alterar configurações no meio do evento

**Após o Evento:**
- [ ] Verificar relatórios
- [ ] Conferir histórico de transações

---

**Acesso Rápido:**
- Interface: `http://localhost/hol/configuracoes_sistema.php`
- Cadastro: `http://localhost/hol/alocar_cartao_mobile.php`
- Config Manual: `config/config.php`

