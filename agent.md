# Djumbai — Agent Context & Learning Loops (`agent.md`)

Este ficheiro funciona como a memória contínua e a base de conhecimento estruturada da plataforma **Djumbai** para assistentes de inteligência artificial (IAs), garantindo contexto total sobre a arquitetura, convenções, decisões técnicas e histórico de aprendizado do projeto.

---

## 📌 1. Visão Geral do Projeto

**Djumbai** (palavra de origem cívica/comunitária) é uma plataforma digital para reporte, acompanhamento transparente e moderação de dificuldades e infraestruturas comunitárias na República de Angola (Luanda e municípios adjacentes).

- **Público Alvo:** Cidadãos/Moradores e Administradores Municipais.
- **Stack Técnica:** PHP 8.x Vanilla MVC (Sem Composer, sem frameworks pesados).
- **Servidor:** Apache (XAMPP / Linux / Windows) com `mod_rewrite`.
- **Base de Dados:** MySQL / MariaDB (PDO com prepared statements estritos e UTF8MB4).
- **Frontend:** HTML5, CSS3 Custom Properties, JavaScript Vanilla (Sem bibliotecas externas, ícones SVG offline).

---

## 🏗️ 2. Arquitetura & Fluxo do Sistema

### Estrutura de Diretórios
```
c:/xampp/htdocs/djumbai/
├── .htaccess                  # Redirecionamento da raiz -> /public
├── .env                       # Configurações de ambiente (APP_ENV, DB_*, SESSION_LIFETIME)
├── database/
│   └── djumbai.sql            # Schema completo DDL/DML com tabelas e dados de teste
├── public/
│   ├── .htaccess              # Front Controller Rewrite -> index.php
│   ├── index.php              # Entrada única da aplicação (Front Controller & Router)
│   ├── css/
│   │   ├── style.css          # Design System Global (Variáveis de cores, componentes)
│   │   └── admin.css          # Estilos específicos da Dashboard Admin
│   └── assets/
│       ├── icons/             # Ícones SVG offline (inlinados via icon() helper)
│       └── images/uploads/    # Uploads sanitizados de ocorrências (JPG, PNG, WEBP)
├── app/
│   ├── core/
│   │   ├── Controller.php     # Base Controller (render, redirect, json, requireAuth)
│   │   ├── Model.php          # Base Model (find, create, update, delete)
│   │   ├── Router.php         # Roteador regex para URLs limpas
│   │   ├── Env.php            # Leitor de variáveis do .env
│   │   ├── SecurityHeaders.php# Cabeçalhos CSP, HSTS, X-Frame-Options
│   │   ├── SecurityLogger.php # Registo de auditoria de segurança
│   │   ├── RateLimiter.php    # Proteção anti-bruteforce/spam
│   │   └── helpers.php        # Funções globais (e(), icon(), csrfField(), url(), timeAgo())
│   ├── controllers/
│   │   ├── AuthController.php # Autenticação, login, cadastro, logout, ban-check
│   │   ├── HomeController.php # Página inicial e "Como Funciona"
│   │   ├── UserController.php # Painel do cidadão (/perfil) e gestão de notificações
│   │   ├── ProblemaController.php# Listagem, detalhes, criação, confirmação e comentários
│   │   └── AdminController.php# Dashboard admin, moderação de utilizadores, comentários e definições
│   ├── models/
│   │   ├── UserModel.php     # Operações de utilizadores (tipo = cidadao/admin, status = ativo/banido)
│   │   ├── ProblemaModel.php  # CRUD de problemas, estatísticas, confirmações, comentários e respostas
│   │   └── CategoriaModel.php # Leitura de categorias comunitárias
│   └── views/
│       ├── layouts/
│       │   ├── main.php       # Layout principal (Portal público e cidadão)
│       │   └── admin.php      # Layout administrativo (SaaS Console)
│       ├── components/
│       │   ├── welcome_modal.php# Modal compacto de boas-vindas no login
│       │   └── logout_modal.php # Modal de confirmação de término de sessão
│       ├── home/              # Páginas iniciais e informativas
│       ├── auth/              # Login e Cadastro
│       ├── user/              # Dashboard do cidadão (/perfil)
│       ├── problemas/         # Ocorrências: index, show (com comentários/respostas), criar
│       └── admin/             # Telas admin: dashboard, problemas, usuarios, comentarios, definicoes
└── storage/
    └── config/
        └── site_settings.json # Definições operacionais do sistema
```

---

## 🔐 3. Regras de Segurança & Blindagem Aplicadas

1. **Prevenção de XSS:**
   - Todo a saída de texto do utilizador DEVE utilizar a função `e($val)` (abreviação de `htmlspecialchars($val, ENT_QUOTES, 'UTF-8')`).
2. **Proteção CSRF:**
   - Todos os formulários POST enviam `<?= csrfField() ?>`.
   - A verificação no Controller chama `verifyCsrf()`.
3. **Controle de Acesso / Sessão:**
   - `requireAuth()` obriga login.
   - `requireAdmin()` obriga que `$_SESSION['user_tipo'] === 'admin'`.
   - Utilizadores com `status === 'banido'` são impedidos de fazer login.
4. **Uploads Sanitizados:**
   - Validação estrita de extensão (whitelist: `jpg, jpeg, png, webp`).
   - Análise de MIME type real via `finfo_file` e verificação de imagem com `getimagesize`.
   - Nomeação aleatória criptográfica (`djumbai_` + hex aleatório) e permissões `0644`.

---

## 🎨 4. Design System & Convenções de Interface

- **Paleta de Cores Angolana (Variáveis CSS):**
  - `--brand-terra`: `#B4451F` (Sienna / Terra Quente)
  - `--brand-dendem`: `#D99A1E` (Dendém Gold / Amarelo Âmbar)
  - `--brand-baia`: `#1F6F6B` (Azul Baía / Verde-Água Escuro)
  - `--brand-capim`: `#3F5A3C` (Verde Capim)
  - `--surface-dark`: `#1A1410` (Escuro Elegante)
- **Zero Emojis / Ícones 100% Offline:**
  - Ícones renderizados através da função `icon('nome-do-icone', $tamanho, $classe, $cor)`.
  - SVG inlinado extraído da pasta `public/assets/icons/`.

---

## 🔁 5. Ciclos de Aprendizado (Learning Loops)

- **Loop 1 — Navegação do Cidadão Autenticado:**
  - Quando um cidadão está logado, a navegação principal é simplificada:
    - O logótipo `djumbai` direciona diretamente para o seu painel (`/perfil`).
    - Os links públicos de topo desaparecem; mantêm-se apenas os botões de ação (ex: `Explorar Reportes` e `Sair`).
    - O rodapé passa a ser minimalista (apenas marca e copyright).
- **Loop 2 — Respostas a Comentários:**
  - A tabela `comentarios` possui a coluna `parent_id INT UNSIGNED DEFAULT NULL`.
  - Comentários pai têm `parent_id IS NULL`; respostas têm `parent_id = id_do_comentario_pai`.
  - Respostas a um comentário disparam uma notificação automática para o autor do comentário original.
- **Loop 3 — Modal de Término de Sessão:**
  - O link de logout é interceptado pelo componente `logout_modal.php`.
  - A ação de confirmação de saída executa `window.location.href = '/djumbai/public/logout'` diretamente para evitar loops de `preventDefault()`.
- **Loop 4 — Gestão de Utilizadores pelo Admin:**
  - A listagem de utilizadores (`/admin/usuarios`) filtra apenas contas do tipo `cidadao` (`WHERE u.tipo = 'cidadao'`).
  - O admin pode suspender/banir contas ou emitir Avisos de Bons Modos com modelos pré-definidos.
- **Loop 5 — Regra Estrita para Visitantes (Apoiar / Comentar):**
  - Visitantes não autenticados NÃO PODEM apoiar/confirmar nem comentar/responder em nenhuma ocorrência.
  - Qualquer tentativa dispara o modal `auth_prompt_modal.php` com o título *"Desejas entrar ou participar?"*:
    - **Entrar (Fazer Login)** -> Encaminha para `/login`.
    - **Participar (Criar Conta Grátis)** -> Encaminha para `/cadastro`.

---

## 🧪 6. Credenciais de Teste / Desenvolvimento

- **Administrador:** `admin@djumbai.ao` / `Admin@2026`
- **Cidadãos de Teste:**
  - `maria@djumbai.ao` / `Teste@2026`
  - `joao@djumbai.ao` / `Teste@2026`
  - `ana@djumbai.ao` / `Teste@2026`
