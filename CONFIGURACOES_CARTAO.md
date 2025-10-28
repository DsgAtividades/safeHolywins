# Configurações de Cadastro de Cartões

## 📋 Visão Geral

Este documento explica como funcionam as configurações de cadastro de cartões no sistema, permitindo tornar CPF e Nome opcionais ou obrigatórios.

## 🎯 Objetivo

Permitir flexibilidade no cadastro de cartões, onde em alguns eventos pode ser necessário cadastro rápido sem dados pessoais, enquanto em outros pode ser necessário o cadastro completo.

## ⚙️ Arquivo de Configuração

As configurações estão no arquivo: **`config/config.php`**

### Configurações Disponíveis

```php
// Define se CPF é obrigatório no cadastro de cartões
// true = CPF obrigatório | false = CPF opcional
define('CARTAO_CPF_OBRIGATORIO', false);

// Define se Nome é obrigatório no cadastro de cartões
// true = Nome obrigatório | false = Nome opcional
define('CARTAO_NOME_OBRIGATORIO', false);

// Valor fixo do custo do cartão
define('CARTAO_CUSTO_FIXO', 0.00);
```

## 🖥️ Interface de Configuração

Acesse: **`configuracoes_sistema.php`**

Esta página permite alterar as configurações de forma visual através de um painel administrativo.

**Permissão necessária:** `acessar_configuracoes`

### Como Usar a Interface

1. Acesse a página de configurações
2. Use os switches para ativar/desativar obrigatoriedade
3. Defina o custo fixo do cartão
4. Clique em "Salvar Configurações"
5. Recarregue a página para aplicar as alterações

## 🎫 Comportamento do Cadastro

### Quando CPF é OPCIONAL (`CARTAO_CPF_OBRIGATORIO = false`)

- Campo CPF aparece como opcional no formulário
- Se não for informado, o sistema gera automaticamente um CPF único baseado em timestamp
- Formato gerado: `00000123456` (11 dígitos)
- Se for informado, será validado normalmente

### Quando Nome é OPCIONAL (`CARTAO_NOME_OBRIGATORIO = false`)

- Campo Nome aparece como opcional no formulário
- Se não for informado, o sistema usa: `Cartão [código_do_cartão]`
- Exemplo: `Cartão 123456`
- Se for informado, será usado o nome digitado

### Quando CPF é OBRIGATÓRIO (`CARTAO_CPF_OBRIGATORIO = true`)

- Campo CPF é obrigatório no formulário
- Validação de 11 dígitos
- Não pode ser vazio

### Quando Nome é OBRIGATÓRIO (`CARTAO_NOME_OBRIGATORIO = true`)

- Campo Nome é obrigatório no formulário
- Mínimo de 3 caracteres
- Não pode ser vazio

## 📱 Página de Alocação de Cartões

**Arquivo:** `alocar_cartao_mobile.php`

Esta página utiliza as configurações automaticamente:

- Mostra indicador visual (Opcional) ou asterisco vermelho (*)
- Ajusta validações do formulário
- Exibe mensagens de ajuda explicando o comportamento

## 🔒 Validações de Segurança

O sistema mantém validações importantes:

1. **Código do Cartão:** Sempre obrigatório
2. **Cartão Disponível:** Verifica se está disponível (não usado)
3. **CPF Único:** Mesmo quando opcional, gera CPF único
4. **Transações:** Registra no histórico normalmente

## 💾 Banco de Dados

### Estrutura da Tabela `pessoas`

```sql
id_pessoa (PK) - INT
nome - VARCHAR (será preenchido automaticamente se vazio)
cpf - VARCHAR (será gerado automaticamente se vazio)
telefone - VARCHAR (opcional)
```

### Estrutura da Tabela `cartoes`

```sql
id (PK) - INT
codigo - VARCHAR (único)
usado - TINYINT (0 = disponível, 1 = usado)
id_pessoa - INT (FK para pessoas)
```

## 🔄 Fluxo de Cadastro

### Com Configurações OPCIONAIS

1. Usuário escaneia QR Code do cartão
2. Pode preencher ou deixar nome/CPF em branco
3. Sistema valida apenas o código do cartão
4. Se CPF vazio → gera automaticamente
5. Se nome vazio → usa "Cartão [código]"
6. Cria registro na tabela pessoas
7. Vincula cartão à pessoa
8. Cria saldo inicial
9. Registra no histórico

### Com Configurações OBRIGATÓRIAS

1. Usuário escaneia QR Code do cartão
2. **Deve** preencher nome e CPF
3. Sistema valida todos os campos
4. Verifica se CPF já existe
5. Atualiza dados se CPF existir, ou cria novo
6. Vincula cartão à pessoa
7. Cria saldo inicial
8. Registra no histórico

## 📊 Relatórios e Histórico

Todos os cadastros são registrados normalmente:

- **Tabela:** `historico_transacoes_sistema`
- **Tipo:** "Custo Cartão"
- **Registro:** Nome do operador, grupo, valor, pessoa, cartão

## ⚠️ Avisos Importantes

1. **Backup:** Faça backup do arquivo `config/config.php` antes de alterar
2. **Permissões:** Apenas administradores devem ter acesso a `acessar_configuracoes`
3. **Consistência:** Mantenha as configurações durante todo o evento
4. **CPFs Gerados:** CPFs gerados automaticamente são únicos mas fictícios

## 🛠️ Solução de Problemas

### Erro: "CPF é obrigatório"

- Verifique se `CARTAO_CPF_OBRIGATORIO` está como `false`
- Recarregue a página após alterar configurações

### Erro: "Nome é obrigatório"

- Verifique se `CARTAO_NOME_OBRIGATORIO` está como `false`
- Recarregue a página após alterar configurações

### Configurações não aplicam

- Certifique-se de salvar o arquivo `config/config.php`
- Verifique permissões de escrita do arquivo
- Limpe cache do navegador

## 📝 Exemplo Prático

### Cenário 1: Evento Rápido (Configurações Opcionais)

```
1. Operador escaneia cartão 123456
2. Deixa nome e CPF em branco
3. Sistema cria:
   - Nome: "Cartão 123456"
   - CPF: "00000167234" (gerado)
4. Cartão pronto para uso
```

### Cenário 2: Evento com Controle (Configurações Obrigatórias)

```
1. Operador escaneia cartão 789012
2. Preenche:
   - Nome: "João Silva"
   - CPF: "12345678901"
3. Sistema cria/atualiza pessoa
4. Vincula cartão
5. Cartão pronto para uso
```

## 🔗 Arquivos Relacionados

- `config/config.php` - Arquivo de configurações
- `alocar_cartao_mobile.php` - Página de alocação
- `configuracoes_sistema.php` - Interface de administração
- `includes/conexao.php` - Conexão com banco de dados

## 📞 Suporte

Para dúvidas ou problemas, consulte a documentação técnica do sistema ou entre em contato com o administrador do sistema.

---

**Última atualização:** 2025-01-20
**Versão do Sistema:** 1.0.0

