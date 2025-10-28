# 🔓 Como Acessar a Página de Configurações

## ❌ Problema
Você não consegue acessar `configuracoes_sistema.php` mesmo tendo permissão.

## ✅ Solução Rápida

### Opção 1: Executar Script PHP (RECOMENDADO) ⚡

1. **Acesse no navegador:**
   ```
   http://localhost/hol/database/adicionar_permissao_configuracoes.php
   ```

2. **O script vai:**
   - ✅ Criar a permissão `acessar_configuracoes`
   - ✅ Adicionar ao grupo Administrador
   - ✅ Mostrar confirmação

3. **Faça logout e login novamente** 🔄

4. **Acesse a página:**
   ```
   http://localhost/hol/configuracoes_sistema.php
   ```

---

### Opção 2: Executar SQL Manualmente 🗄️

1. **Abra o phpMyAdmin ou seu gerenciador MySQL**

2. **Selecione o banco de dados:** `holywins`

3. **Execute este SQL:**
   ```sql
   -- Criar a permissão
   INSERT INTO permissoes (nome, descricao) 
   VALUES ('acessar_configuracoes', 'Permite acessar e modificar as configurações do sistema')
   ON DUPLICATE KEY UPDATE descricao = 'Permite acessar e modificar as configurações do sistema';

   -- Adicionar ao grupo Administrador
   SET @permissao_id = (SELECT id FROM permissoes WHERE nome = 'acessar_configuracoes' LIMIT 1);
   SET @grupo_admin_id = (SELECT id FROM grupos WHERE nome LIKE '%dministrador%' ORDER BY id LIMIT 1);
   
   INSERT IGNORE INTO grupo_permissoes (grupo_id, permissao_id)
   VALUES (@grupo_admin_id, @permissao_id);
   ```

4. **Faça logout e login novamente** 🔄

5. **Acesse a página:**
   ```
   http://localhost/hol/configuracoes_sistema.php
   ```

---

### Opção 3: Usar Interface do Sistema 🖱️

1. **Acesse:** `gerenciar_permissoes.php`

2. **Crie nova permissão:**
   - **Nome:** `acessar_configuracoes`
   - **Descrição:** `Permite acessar e modificar as configurações do sistema`

3. **Acesse:** `gerenciar_grupos.php`

4. **Edite o grupo Administrador:**
   - Marque a permissão: `acessar_configuracoes`

5. **Faça logout e login novamente** 🔄

6. **Acesse a página:**
   ```
   http://localhost/hol/configuracoes_sistema.php
   ```

---

## 🎯 Verificar se Funcionou

### No Menu:

Após fazer login, você deve ver no menu lateral na seção **Administrativa**:

```
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
👥 Usuários
📊 Grupos
🔒 Permissões
⚙️ Configurações do Sistema  ← NOVO!
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
```

### Acessando Diretamente:

Se não aparecer no menu, tente acessar diretamente:
```
http://localhost/hol/configuracoes_sistema.php
```

---

## 🔍 Troubleshooting

### 🔴 "Acesso negado"

**Causa:** Permissão não foi adicionada ou usuário não fez logout

**Solução:**
1. Execute o script de permissão novamente
2. Faça logout (IMPORTANTE!)
3. Faça login novamente
4. Tente acessar

---

### 🔴 "Página não encontrada"

**Causa:** Arquivo não existe ou caminho errado

**Solução:**
1. Verifique se o arquivo existe: `c:\wamp64\www\hol\configuracoes_sistema.php`
2. Acesse com o caminho completo: `http://localhost/hol/configuracoes_sistema.php`

---

### 🔴 Não aparece no menu

**Causa:** Permissão não foi vinculada ao grupo ou cache do navegador

**Solução:**
1. Faça logout e login novamente
2. Limpe cache do navegador (Ctrl + F5)
3. Verifique se a permissão está vinculada ao grupo

---

### 🔴 "Call to undefined function temPermissao()"

**Causa:** Arquivo de funções não foi incluído

**Solução:**
1. Verifique se `includes/verifica_permissao.php` existe
2. Verifique se a função `temPermissao()` está definida
3. Recarregue a página

---

## 📊 Verificar Permissões no Banco

Execute este SQL para verificar:

```sql
-- Verificar se a permissão existe
SELECT * FROM permissoes WHERE nome = 'acessar_configuracoes';

-- Verificar se está vinculada ao grupo
SELECT 
    g.nome as grupo,
    p.nome as permissao,
    p.descricao
FROM grupo_permissoes gp
JOIN grupos g ON gp.grupo_id = g.id
JOIN permissoes p ON gp.permissao_id = p.id
WHERE p.nome = 'acessar_configuracoes';

-- Verificar permissões do seu usuário
SELECT 
    u.nome as usuario,
    g.nome as grupo,
    p.nome as permissao
FROM usuarios u
JOIN grupos g ON u.grupo_id = g.id
JOIN grupo_permissoes gp ON g.id = gp.grupo_id
JOIN permissoes p ON gp.permissao_id = p.id
WHERE u.usuario = 'SEU_USUARIO';
```

---

## ✅ Checklist

Siga esta lista para garantir que tudo está correto:

- [ ] Executei o script de permissão
- [ ] A permissão `acessar_configuracoes` foi criada
- [ ] A permissão foi adicionada ao grupo Administrador
- [ ] Fiz logout do sistema
- [ ] Fiz login novamente
- [ ] O menu mostra "Configurações do Sistema"
- [ ] Consigo acessar `configuracoes_sistema.php`

---

## 🎉 Tudo Funcionando!

Se seguiu todos os passos, agora você deve conseguir:

✅ Ver "Configurações do Sistema" no menu  
✅ Acessar a página de configurações  
✅ Alterar configurações de cartões (CPF/Nome opcional)  

---

## 📞 Arquivos de Ajuda

| Arquivo | Localização | Descrição |
|---------|-------------|-----------|
| Script PHP | `/database/adicionar_permissao_configuracoes.php` | Adiciona permissão automaticamente |
| Script SQL | `/database/adicionar_permissao_configuracoes.sql` | SQL para executar manualmente |
| Menu | `/includes/header.php` | Onde o menu é renderizado |
| Página | `/configuracoes_sistema.php` | Página de configurações |

---

## 🚀 Começar a Usar

Após resolver o acesso, veja:
- 📖 `README_CONFIGURACAO_CARTAO.md` - Como usar as configurações
- 🚀 `GUIA_RAPIDO_CONFIGURACAO_CARTAO.md` - Guia rápido

---

**Precisa de Ajuda?**

1. Verifique todos os passos acima
2. Execute os scripts de permissão
3. Faça logout e login
4. Se persistir, verifique as permissões no banco de dados

**Status:** ✅ Implementado e Pronto para Uso

