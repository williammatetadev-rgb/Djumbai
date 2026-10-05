# 🇦🇴 Djumbai — Plataforma Cívica Digital de Angola

**Djumbai** é uma solução web MVC em PHP desenvolvida para o reporte, acompanhamento transparente e moderação comunitária de dificuldades infraestruturais e sociais em Angola (ruas danificadas, iluminação pública, saneamento, abastecimento de água e saúde comunitária).

---

## 🚀 Como o Sistema Funciona & Comunica

O sistema opera sob o padrão **Front Controller MVC (Model-View-Controller)** com arquitetura limpa em PHP Vanilla sem dependências de terceiros.

```
       [ Cidadão / Navegador ]
                 │
                 ▼ (HTTP Request)
  [ c:\xampp\htdocs\djumbai\.htaccess ] (Redireciona para /public/)
                 │
                 ▼
  [ public/index.php ] ───▶ [ SecurityHeaders & Session Guard ]
                 │
                 ▼
          [ Router.php ] ───▶ Corresponde à URL (ex: /problemas/1)
                 │
                 ▼
     [ Controller Correspondente ] (ex: ProblemaController)
         │               │
         ▼               ▼
   [ Model (PDO) ]   [ View (PHP/HTML) ]
         │               │
         ▼               ▼
   [ MySQL/MariaDB ]  [ Layout (main.php / admin.php) ]
                         │
                         ▼ (HTTP Response Renderizada)
               [ Resposta ao Navegador ]
```

---

## 🛠️ Requisitos do Sistema

- **Servidor Web:** Apache 2.4+ com módulo `mod_rewrite` ativado.
- **Linguagem:** PHP 8.1 ou superior (Extensões: `pdo_mysql`, `fileinfo`, `mbstring`).
- **Base de Dados:** MySQL 8.0+ ou MariaDB 10.4+.
- **Ambiente Recomendado:** XAMPP / WAMP / LAMP / Docker.

---

## 💻 Instalação & Configuração

1. **Clonar / Copiar o Projeto:**
   Coloque a pasta `djumbai` no seu diretório do servidor web (ex: `C:\xampp\htdocs\djumbai`).

2. **Configurar a Base de Dados:**
   - Crie uma base de dados no MySQL chamada `djumbai`.
   - Importe o ficheiro `database/djumbai.sql`.
   - Execute o script de migração para assegurar a inclusão das colunas de segurança:
     ```bash
     php scratch/migrate.php
     ```

3. **Configurar o Ficheiro `.env`:**
   Verifique ou edite as credenciais no ficheiro `.env` na raiz do projeto:
   ```ini
   APP_ENV=development
   APP_DEBUG=true

   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_NAME=djumbai
   DB_USER=root
   DB_PASS=

   SESSION_LIFETIME=7200
   ```

4. **Aceder no Navegador:**
   Navegue para: `http://localhost/djumbai/` (Redireciona automaticamente para `http://localhost/djumbai/public/`).

---

## 🔑 Credenciais de Acesso de Teste

| Função | E-mail | Palavra-passe | Nível de Permissão |
|---|---|---|---|
| **Administrador** | `admin@djumbai.ao` | `Admin@2026` | Acesso Total (Dashboard Admin, Moderação, Definições) |
| **Cidadão** | `maria@djumbai.ao` | `Teste@2026` | Morador Ativo (Reportar, Comentar, Responder, Apoiar) |
| **Cidadão** | `joao@djumbai.ao` | `Teste@2026` | Morador Ativo (Reportar, Comentar, Responder, Apoiar) |
| **Cidadão** | `ana@djumbai.ao` | `Teste@2026` | Morador Ativo (Reportar, Comentar, Responder, Apoiar) |

---

## 📡 Tabela de Rotas & Endpoints

### 🌐 Portal Público & Autenticação
- `GET /` — Página Inicial com estatísticas e causas em destaque.
- `GET /como-funciona` — Guia interativo da plataforma.
- `GET /login` & `POST /login` — Formulário e autenticação segura.
- `GET /cadastro` & `POST /cadastro` — Registo de novos cidadãos.
- `GET /logout` — Término de sessão com modal de confirmação.

### 👤 Painel do Cidadão (`/perfil`)
- `GET /perfil` — Dashboard do cidadão com estatísticas pessoais e notificações.
- `POST /notificacoes/{id}/ler` — Marcar notificação como lida.

### 📋 Ocorrências & Comunidade
- `GET /problemas` — Explorar e filtrar reportes por categoria e estado.
- `GET /problemas/{id}` — Detalhes da ocorrência com apoio e comentários encadeados.
- `GET /reportar` & `POST /reportar` — Submeter nova ocorrência com upload de imagem.
- `POST /confirmar/{id}` — Apoiar/Votar numa ocorrência (Toggle via AJAX/Fetch).
- `POST /problemas/{id}/comentar` — Publicar comentário ou responder a um comentário existente.

### 🛡️ Centro de Despacho Admin (`/admin`)
- `GET /admin` — Visão geral da administração e gráficos de despacho.
- `GET /admin/problemas` — Gestão e alteração de estado das ocorrências.
- `POST /admin/problemas/{id}/estado` — Atualizar estado (Pendente, Em análise, Resolvido, Rejeitado).
- `GET /admin/usuarios` — Gestão de membros registados.
- `POST /admin/usuarios/{id}/banir` — Suspender ou reativar um utilizador.
- `POST /admin/usuarios/{id}/notificar` — Enviar notificação de conduta cívica.
- `GET /admin/comentarios` — Moderação global de comentários.
- `POST /admin/comentarios/{id}/eliminar` — Apagar comentário.
- `GET /admin/definicoes` & `POST /admin/definicoes` — Configurações operacionais do sistema.

---

## 🛡️ Protocolos de Segurança Implementados

- **Output Encoding:** Escaping global via `e()` para mitigar vulnerabilidades XSS.
- **CSRF Token:** Validação de tokens de sessão em todos os formulários modificadores (`POST`).
- **Uploads Seguros:** Validação tripla (extensão, MIME type e integridade binária) com armazenamento sob permissões restritas.
- **Rate Limiting:** Limite de requisições por IP/utilizador para evitar spam.
- **Cabeçalhos de Segurança:** `SecurityHeaders.php` aplica `X-Frame-Options`, `X-Content-Type-Options`, `Content-Security-Policy` e `X-XSS-Protection`.

---

## 📜 Licença & Direitos

© <?= date('Y') ?> **Djumbai**. Plataforma Cívica Digital desenvolvida para a comunidade de Angola. Todos os direitos reservados.
