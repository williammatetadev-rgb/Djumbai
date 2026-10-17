-- ============================================================
-- DJUMBAI – Base de Dados Completa
-- Versão: 1.0  |  Motor: MySQL 5.7+ / MariaDB 10.4+
-- Charset: utf8mb4  |  Collation: utf8mb4_unicode_ci
-- ============================================================
-- TABELAS (11):
--   provincias · municipios · bairros · categorias
--   estados_problema · user · problemas · confirmacoes
--   comentarios · historico_estados · notificacoes
-- ============================================================

SET NAMES utf8mb4;
SET foreign_key_checks = 0;
SET time_zone = '+01:00';

-- ── Criar / seleccionar a base de dados ────────────────────
DROP DATABASE IF EXISTS djumbai;

CREATE DATABASE djumbai
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE djumbai;

-- ============================================================
-- BLOCO 1 – GEOGRAFIA (sem dependências externas)
-- ============================================================

-- ── 1. PROVÍNCIAS ──────────────────────────────────────────
CREATE TABLE provincias (
  id        TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  nome      VARCHAR(60)      NOT NULL,
  latitude  DECIMAL(10,8)             DEFAULT NULL,
  longitude DECIMAL(11,8)             DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_prov_nome (nome)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='18 províncias de Angola';

-- ── 2. MUNICÍPIOS ──────────────────────────────────────────
CREATE TABLE municipios (
  id           SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  nome         VARCHAR(80)       NOT NULL,
  provincia_id TINYINT UNSIGNED  NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_mun (nome, provincia_id),
  KEY idx_mun_prov (provincia_id),
  CONSTRAINT fk_mun_prov
    FOREIGN KEY (provincia_id) REFERENCES provincias (id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Municípios de cada província';

-- ── 3. BAIRROS ─────────────────────────────────────────────
CREATE TABLE bairros (
  id           SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  nome         VARCHAR(80)       NOT NULL,
  municipio_id SMALLINT UNSIGNED NOT NULL,
  latitude     DECIMAL(10,8)              DEFAULT NULL,
  longitude    DECIMAL(11,8)              DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_bairro (nome, municipio_id),
  KEY idx_bairro_mun (municipio_id),
  CONSTRAINT fk_bai_mun
    FOREIGN KEY (municipio_id) REFERENCES municipios (id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Bairros de cada município';

-- ============================================================
-- BLOCO 2 – LOOKUP TABLES (categorias e estados)
-- ============================================================

-- ── 4. CATEGORIAS ──────────────────────────────────────────
CREATE TABLE categorias (
  id   TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  nome VARCHAR(60)      NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_cat_nome (nome)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Tipos de problema: Água, Estradas, Lixo…';

-- ── 5. ESTADOS DO PROBLEMA ─────────────────────────────────
-- cor = código hex da identidade Djumbai
CREATE TABLE estados_problema (
  id   TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  nome VARCHAR(40)      NOT NULL,
  cor  CHAR(7)          NOT NULL DEFAULT '#6B5A4A',
  PRIMARY KEY (id),
  UNIQUE KEY uq_est_nome (nome)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Pendente · Em análise · Resolvido · Rejeitado';

-- ============================================================
-- BLOCO 3 – UTILIZADORES
-- ============================================================

-- ── 6. USER (moradores e administradores) ──────────────────
CREATE TABLE user (
  id         INT UNSIGNED   NOT NULL AUTO_INCREMENT,
  nome       VARCHAR(100)   NOT NULL,
  email      VARCHAR(150)            DEFAULT NULL,
  telefone   VARCHAR(20)             DEFAULT NULL,
  bi_numero  VARCHAR(30)             DEFAULT NULL,
  nome_completo_bi VARCHAR(150)      DEFAULT NULL,
  data_nascimento  DATE              DEFAULT NULL,
  idade            TINYINT UNSIGNED  DEFAULT NULL,
  estado_civil     VARCHAR(30)       DEFAULT 'Solteiro(a)',
  nacionalidade    VARCHAR(50)       DEFAULT 'Angolana',
  bi_frente  VARCHAR(255)            DEFAULT NULL,
  bi_verso   VARCHAR(255)            DEFAULT NULL,
  bi_status  ENUM('nao_enviado','pendente','verificado','rejeitado') NOT NULL DEFAULT 'nao_enviado',
  bi_nota    VARCHAR(255)            DEFAULT NULL,
  bi_avaliado_em DATETIME            DEFAULT NULL,
  senha_hash VARCHAR(255)   NOT NULL,
  tipo       ENUM('cidadao','admin') NOT NULL DEFAULT 'cidadao',
  status     ENUM('ativo','banido')   NOT NULL DEFAULT 'ativo',
  ultimo_acesso DATETIME DEFAULT NULL,
  bairro_id  SMALLINT UNSIGNED       DEFAULT NULL,
  criado_em  DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_user_email (email),
  UNIQUE KEY uq_user_telefone (telefone),
  KEY idx_user_bairro (bairro_id),
  KEY idx_user_tipo   (tipo),
  CONSTRAINT fk_user_bairro
    FOREIGN KEY (bairro_id) REFERENCES bairros (id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Moradores (cidadao) e gestores (admin)';

-- ============================================================
-- BLOCO 4 – NÚCLEO DO SISTEMA
-- ============================================================

-- ── 7. PROBLEMAS ───────────────────────────────────────────
CREATE TABLE problemas (
  id               INT UNSIGNED      NOT NULL AUTO_INCREMENT,
  titulo           VARCHAR(150)      NOT NULL,
  descricao        TEXT              NOT NULL,
  foto             VARCHAR(255)               DEFAULT NULL,
  referencia_local VARCHAR(200)               DEFAULT NULL,
  user_id          INT UNSIGNED      NOT NULL,
  categoria_id     TINYINT UNSIGNED  NOT NULL,
  bairro_id        SMALLINT UNSIGNED NOT NULL,
  estado_id        TINYINT UNSIGNED  NOT NULL,
  latitude         DECIMAL(10,8)              DEFAULT NULL,
  longitude        DECIMAL(11,8)              DEFAULT NULL,
  criado_em        DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  resolvido_em     DATETIME                   DEFAULT NULL,
  PRIMARY KEY (id),
  KEY idx_prob_user     (user_id),
  KEY idx_prob_bairro   (bairro_id),
  KEY idx_prob_estado   (estado_id),
  KEY idx_prob_cat      (categoria_id),
  KEY idx_prob_criado   (criado_em),
  CONSTRAINT fk_prob_user
    FOREIGN KEY (user_id)      REFERENCES user             (id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_prob_cat
    FOREIGN KEY (categoria_id) REFERENCES categorias       (id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_prob_bairro
    FOREIGN KEY (bairro_id)    REFERENCES bairros          (id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_prob_estado
    FOREIGN KEY (estado_id)    REFERENCES estados_problema (id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Centro do sistema – cada linha é um problema reportado';

-- ── 8. CONFIRMAÇÕES ────────────────────────────────────────
-- Botão "Também acontece aqui" – cada par (problema, user) é único
CREATE TABLE confirmacoes (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  problema_id INT UNSIGNED NOT NULL,
  user_id     INT UNSIGNED NOT NULL,
  parent_id   INT UNSIGNED DEFAULT NULL,
  criado_em   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_conf (problema_id, user_id),   -- 1 voto por utilizador
  KEY idx_conf_user (user_id),
  CONSTRAINT fk_conf_prob
    FOREIGN KEY (problema_id) REFERENCES problemas (id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_conf_user
    FOREIGN KEY (user_id) REFERENCES user (id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Votos "Também acontece aqui" – ativado na V2';

-- ── 9. COMENTÁRIOS ─────────────────────────────────────────
CREATE TABLE comentarios (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  problema_id INT UNSIGNED NOT NULL,
  user_id     INT UNSIGNED NOT NULL,
  parent_id   INT UNSIGNED DEFAULT NULL,
  texto       TEXT         NOT NULL,
  criado_em   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_com_prob (problema_id),
  KEY idx_com_user (user_id),
  CONSTRAINT fk_com_prob
    FOREIGN KEY (problema_id) REFERENCES problemas (id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_com_user
    FOREIGN KEY (user_id) REFERENCES user (id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Conversa dentro de cada problema – ativado na V2';

-- ── 10. HISTÓRICO DE ESTADOS ───────────────────────────────
-- Regista cada mudança: quem mudou, de que estado, para que estado
CREATE TABLE historico_estados (
  id                 INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  problema_id        INT UNSIGNED     NOT NULL,
  estado_anterior_id TINYINT UNSIGNED          DEFAULT NULL,  -- NULL = criação
  estado_novo_id     TINYINT UNSIGNED NOT NULL,
  admin_id           INT UNSIGNED     NOT NULL,
  observacao         TEXT                       DEFAULT NULL,
  criado_em          DATETIME         NOT NULL  DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_hist_prob  (problema_id),
  KEY idx_hist_admin (admin_id),
  CONSTRAINT fk_hist_prob
    FOREIGN KEY (problema_id)        REFERENCES problemas       (id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_hist_ant
    FOREIGN KEY (estado_anterior_id) REFERENCES estados_problema(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_hist_novo
    FOREIGN KEY (estado_novo_id)     REFERENCES estados_problema(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_hist_admin
    FOREIGN KEY (admin_id)           REFERENCES user            (id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Auditoria de cada mudança de estado pelo admin';

-- ── 11. NOTIFICAÇÕES ───────────────────────────────────────
CREATE TABLE notificacoes (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id     INT UNSIGNED NOT NULL,
  parent_id   INT UNSIGNED DEFAULT NULL,
  problema_id INT UNSIGNED NOT NULL,
  mensagem    VARCHAR(255) NOT NULL,
  lida        TINYINT(1)   NOT NULL DEFAULT 0,
  criado_em   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_notif_user (user_id),
  KEY idx_notif_lida (user_id, lida),   -- útil para "notificações não lidas"
  CONSTRAINT fk_notif_user
    FOREIGN KEY (user_id)     REFERENCES user      (id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_notif_prob
    FOREIGN KEY (problema_id) REFERENCES problemas (id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Avisa o morador quando o estado do seu problema muda – V3';

-- ============================================================
-- DADOS DE SEMENTE (SEED DATA)
-- ============================================================

-- ── Províncias de Angola (18) ─────────────────────────────
INSERT INTO provincias (nome) VALUES
  ('Luanda'),         -- 1
  ('Benguela'),       -- 2
  ('Huambo'),         -- 3
  ('Bié'),            -- 4
  ('Malanje'),        -- 5
  ('Kwanza Norte'),   -- 6
  ('Kwanza Sul'),     -- 7
  ('Uíge'),           -- 8
  ('Zaire'),          -- 9
  ('Cabinda'),        -- 10
  ('Moxico'),         -- 11
  ('Lunda Norte'),    -- 12
  ('Lunda Sul'),      -- 13
  ('Huíla'),          -- 14
  ('Namibe'),         -- 15
  ('Cunene'),         -- 16
  ('Cuando Cubango'), -- 17
  ('Bengo');          -- 18

-- ── Municípios ────────────────────────────────────────────
-- Luanda (provincia_id = 1)
INSERT INTO municipios (nome, provincia_id) VALUES
  ('Luanda',        1),  -- 1
  ('Belas',         1),  -- 2
  ('Cacuaco',       1),  -- 3
  ('Cazenga',       1),  -- 4
  ('Icolo e Bengo', 1),  -- 5
  ('Kilamba Kiaxi', 1),  -- 6
  ('Quissama',      1),  -- 7
  ('Viana',         1);  -- 8

-- Outras províncias (para o sistema não ficar só com Luanda)
INSERT INTO municipios (nome, provincia_id) VALUES
  ('Benguela',  2),  -- 9
  ('Lobito',    2),  -- 10
  ('Baia Farta',2),  -- 11
  ('Huambo',    3),  -- 12
  ('Kuito',     4),  -- 13
  ('Malanje',   5);  -- 14

-- ── Bairros de Luanda ─────────────────────────────────────
-- Município Luanda (municipio_id = 1)
INSERT INTO bairros (nome, municipio_id) VALUES
  ('Alvalade',         1),  -- 1
  ('Ingombota',        1),  -- 2
  ('Maianga',          1),  -- 3
  ('Rangel',           1),  -- 4
  ('Sambizanga',       1),  -- 5
  ('Samba',            1),  -- 6
  ('Miramar',          1),  -- 7
  ('Mutamba',          1),  -- 8
  ('Cidade Alta',      1),  -- 9
  ('Bairro Operário',  1),  -- 10
  ('Marçal',           1),  -- 11
  ('Bairro Azul',      1);  -- 12

-- Município Belas (municipio_id = 2)
INSERT INTO bairros (nome, municipio_id) VALUES
  ('Talatona',    2),  -- 13
  ('Camama',      2),  -- 14
  ('Kilamba',     2),  -- 15
  ('Benfica',     2),  -- 16
  ('Morro Bento', 2);  -- 17

-- Município Cacuaco (municipio_id = 3)
INSERT INTO bairros (id, nome, municipio_id) VALUES
  (18, 'Cacuaco Sede / Vila', 3),
  (19, 'Funda',               3),
  (20, 'Cidade do Sequele',   3),
  (40, 'Kicolo',              3),
  (41, 'Bela Vista',          3),
  (42, 'Vidrul',              3),
  (43, 'Mulenvos de Cacuaco', 3),
  (44, 'Cerâmica',            3),
  (45, 'Balumuka',            3);

-- Município Cazenga (municipio_id = 4)
INSERT INTO bairros (nome, municipio_id) VALUES
  ('Cazenga',       4),  -- 21
  ('Hoji-ya-Henda', 4),  -- 22
  ('Tala Hady',     4);  -- 23

-- Município Kilamba Kiaxi (municipio_id = 6)
INSERT INTO bairros (nome, municipio_id) VALUES
  ('Kilamba Kiaxi', 6),  -- 24
  ('Palanca',       6),  -- 25
  ('Golfe',         6),  -- 26
  ('Cassenda',      6);  -- 27

-- Município Viana (municipio_id = 8)
INSERT INTO bairros (nome, municipio_id) VALUES
  ('Viana Centro', 8),   -- 28
  ('Mulenvos',     8),   -- 29
  ('Terra Nova',   8),   -- 30
  ('Prenda',       8);   -- 31

-- ── Categorias ────────────────────────────────────────────
INSERT INTO categorias (nome) VALUES
  ('Água'),             -- 1
  ('Estradas'),         -- 2
  ('Iluminação'),       -- 3
  ('Lixo e Resíduos'),  -- 4
  ('Saneamento'),       -- 5
  ('Transportes'),      -- 6
  ('Segurança'),        -- 7
  ('Saúde'),            -- 8
  ('Outros');           -- 9

-- ── Estados do Problema (cores da identidade Djumbai) ─────
INSERT INTO estados_problema (nome, cor) VALUES
  ('Pendente',    '#D99A1E'),   -- 1 Dendém  / amarelo-torrado
  ('Em análise',  '#1F6F6B'),   -- 2 Baía    / teal
  ('Resolvido',   '#3F5A3C'),   -- 3 Capim   / verde
  ('Rejeitado',   '#B4451F');   -- 4 Terra   / vermelho

-- ── Utilizadores de Teste ──────────────────────────────────
-- ATENÇÃO: estas são senhas de TESTE para desenvolvimento local.
-- Antes de ir para produção, gera novos hashes com:
--   php -r "echo password_hash('SuaSenha@123', PASSWORD_BCRYPT);"
--
-- admin@djumbai.ao  → Admin@2026
-- maria@djumbai.ao  → Teste@2026
-- joao@djumbai.ao   → Teste@2026
-- ana@djumbai.ao    → Teste@2026

INSERT INTO user (nome, email, telefone, senha_hash, tipo, bairro_id) VALUES
  (
    'Admin Djumbai',
    'admin@djumbai.ao',
    '+244 923 000 001',
    -- senha: Admin@2026 (bcrypt cost 12)
    '$2y$12$Jb3PWVPoCidz8IhpwfxmNeKf9.KJjQYYmWKV0K5kGHsgIuwfLIfeu',
    'admin',
    18  -- bairro: Cacuaco Sede / Vila
  ),
  (
    'Maria Kiluanje',
    'maria@djumbai.ao',
    '+244 912 111 222',
    -- senha: Teste@2026
    '$2y$12$s2cDImI5WurFGatXxk8MOu7Rxd1.GOTr60OI73jB.S.IsxC878jZm',
    'cidadao',
    20  -- bairro: Cidade do Sequele
  ),
  (
    'João Mbemba',
    'joao@djumbai.ao',
    '+244 922 333 444',
    -- senha: Teste@2026
    '$2y$12$s2cDImI5WurFGatXxk8MOu7Rxd1.GOTr60OI73jB.S.IsxC878jZm',
    'cidadao',
    42  -- bairro: Vidrul
  ),
  (
    'Ana Tomás',
    'ana@djumbai.ao',
    '+244 931 555 666',
    -- senha: Teste@2026
    '$2y$12$s2cDImI5WurFGatXxk8MOu7Rxd1.GOTr60OI73jB.S.IsxC878jZm',
    'cidadao',
    19  -- bairro: Funda
  );

-- ── Problemas de Teste (Cacuaco) ──────────────────────────
INSERT INTO problemas
  (titulo, descricao, foto, referencia_local,
   user_id, categoria_id, bairro_id, estado_id, latitude, longitude)
VALUES
  (
    'Postes de iluminação apagados no Bloco 8 do Sequele',
    'Vários postes de iluminação pública estão sem energia ao longo da avenida do Bloco 8. À noite a visibilidade é quase nula e há registo de furtos a quem regressa tarde do trabalho.',
    NULL,
    'Avenida Principal do Bloco 8, Cidade do Sequele, Cacuaco',
    2, 3, 20, 1, -8.761200, 13.415000
  ),
  (
    'Buraco profundo na Estrada Nacional junto à Vidrul',
    'Cratera aberta na faixa direita em direção a Cacuaco Centro. As viaturas são forçadas a invadir a faixa contrária com perigo constante de colisão frontal.',
    NULL,
    'Estrada de Cacuaco, 200m após o desvio da fábrica Vidrul',
    3, 2, 42, 2, -8.775400, 13.368200
  ),
  (
    'Rotura de conduta com desperdício de água potável na Funda',
    'Uma tubagem principal da EPAL rebentou perto da rotunda da Funda. A água está a jorrar há 48 horas enquanto várias casas da vizinhança continuam com as torneiras secas.',
    NULL,
    'Rotunda de acesso à Vila da Funda, Cacuaco',
    4, 1, 19, 2, -8.845100, 13.548700
  ),
  (
    'Depósito irregular de lixo junto à paragem do Kicolo',
    'Grande volume de resíduos acumulado que começa a obstruir a vala de drenagem e a berma da via. Cheiro intenso com proliferação de moscas próximo dos vendedores.',
    NULL,
    'Paragem do Kicolo, próximo da pedonal, Cacuaco',
    2, 4, 40, 1, -8.798100, 13.342100
  ),
  (
    'Vala de drenagem entupida na Bela Vista',
    'A vala principal que recebe as águas pluviais está assoreada com terra e entulho. Risco de inundação de dezenas de residências com o aproximar das chuvas.',
    NULL,
    'Rua da Missão, Bairro Bela Vista, Cacuaco',
    3, 5, 41, 3, -8.782000, 13.355000
  );

-- ── Confirmações ("Também acontece aqui") ─────────────────
-- Regra: um utilizador não pode confirmar o seu próprio problema
INSERT INTO confirmacoes (problema_id, user_id) VALUES
  (1, 3), (1, 4),   -- Problema Iluminação Sequele: confirmado por João e Ana
  (2, 2), (2, 4),   -- Problema Buraco Vidrul: confirmado por Maria e Ana
  (3, 2), (3, 3),   -- Problema Água Funda: confirmado por Maria e João
  (4, 3), (4, 4),   -- Problema Lixo Kicolo: confirmado por João e Ana
  (5, 2), (5, 4);   -- Problema Drenagem Bela Vista: confirmado por Maria e Ana

-- ── Comentários ───────────────────────────────────────────
INSERT INTO comentarios (problema_id, user_id, texto) VALUES
  (1, 3, 'Confirmo! O trecho entre o bloco 7 e 8 fica completamente às escuras a partir das 19h.'),
  (1, 4, 'Já foram reportados vários assaltos nessa mesma zona na semana passada.'),
  (2, 2, 'Passei por lá ontem e quase rebentei o pneu. Muito perigoso à noite!'),
  (3, 3, 'A pressão da água na Funda caiu a zero por causa dessa mesma rotura.'),
  (4, 4, 'O lixo já está a invadir a faixa de rodagem na paragem do Kicolo.');

-- ── Histórico de Estados ──────────────────────────────────
-- Regista as mudanças de estado feitas pelo admin
INSERT INTO historico_estados
  (problema_id, estado_anterior_id, estado_novo_id, admin_id, observacao)
VALUES
  -- Problema 2: Pendente → Em análise
  (2, 1, 2, 1,
   'Problema reportado ao INEA e brigada municipal de conservação de estradas.'),
  -- Problema 3: Pendente → Em análise
  (3, 1, 2, 1,
   'Comunicação enviada à EPAL Cacuaco. Equipa técnica a caminho para reparação.'),
  -- Problema 5: Pendente → Resolvido
  (5, 1, 3, 1,
   'Desobstrução e limpeza da vala concluída pelos serviços comunitários da Administração de Cacuaco.');

-- ── Notificações ──────────────────────────────────────────
INSERT INTO notificacoes (user_id, problema_id, mensagem, lida) VALUES
  (
    3, 2,
    'O estado do seu reporte "Buraco profundo na Estrada Nacional junto à Vidrul" foi actualizado para Em análise.',
    0   -- não lida
  ),
  (
    3, 5,
    'O seu reporte "Vala de drenagem entupida na Bela Vista" foi marcado como Resolvido pela Administração.',
    1   -- já lida
  );

-- ── Reactivar verificação de chaves estrangeiras ──────────
SET foreign_key_checks = 1;

-- ============================================================
-- VERIFICAÇÃO RÁPIDA
-- Corre estas queries para confirmar que tudo foi inserido:
-- ============================================================
/*
  SELECT COUNT(*) AS total_provincias  FROM provincias;       -- 18
  SELECT COUNT(*) AS total_municipios  FROM municipios;       -- 14
  SELECT COUNT(*) AS total_bairros     FROM bairros;          -- 31
  SELECT COUNT(*) AS total_categorias  FROM categorias;       -- 9
  SELECT COUNT(*) AS total_estados     FROM estados_problema; -- 4
  SELECT COUNT(*) AS total_users       FROM user;             -- 4
  SELECT COUNT(*) AS total_problemas   FROM problemas;        -- 5
  SELECT COUNT(*) AS total_confirm     FROM confirmacoes;     -- 7
  SELECT COUNT(*) AS total_coments     FROM comentarios;      -- 5
  SELECT COUNT(*) AS total_historico   FROM historico_estados;-- 3
  SELECT COUNT(*) AS total_notif       FROM notificacoes;     -- 2

  -- Ver problemas com toda a informação ligada:
  SELECT
    p.id,
    p.titulo,
    u.nome        AS reportado_por,
    c.nome        AS categoria,
    b.nome        AS bairro,
    e.nome        AS estado,
    e.cor         AS cor_estado,
    (SELECT COUNT(*) FROM confirmacoes WHERE problema_id = p.id) AS confirmacoes,
    (SELECT COUNT(*) FROM comentarios  WHERE problema_id = p.id) AS comentarios
  FROM problemas p
  JOIN user            u ON u.id = p.user_id
  JOIN categorias      c ON c.id = p.categoria_id
  JOIN bairros         b ON b.id = p.bairro_id
  JOIN estados_problema e ON e.id = p.estado_id
  ORDER BY p.criado_em DESC;
*/
