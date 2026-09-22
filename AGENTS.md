# AGENTS.md

Documentação deste sistema para uso de **agentes de IA** (Cline, GitHub Copilot, Cursor etc.).
Leia este arquivo antes de propor ou executar qualquer alteração no código.

---

## 1. Visão geral

Sistema de **painel administrativo** de uma **loja de vestuário**, desenvolvido em
**PHP puro** (sem framework e sem Composer) com **MySQL** e interface em **Bootstrap 5**.

Estado atual: **projeto em estágio inicial**. O `index.php` da raiz já contém o layout
completo do painel administrativo (header, menu lateral e cards de indicadores). O módulo
`clientes` já carrega dados reais do banco (`cliente`) com **htmx**, no padrão `pages/` +
`partials/`; `produtos` e `pedidos` ainda são *stubs* que imprimem texto. A camada de
conexão (`src/includes/bd.php`, PDO) já existe, mas **não há** CRUD, autenticação nem templates.

---

## 2. Ambiente de desenvolvimento

O projeto roda em **XAMPP no Windows**.

| Item | Valor |
|---|---|
| Sistema operacional | Windows |
| Stack web | **XAMPP** (Apache + PHP) |
| Apache | `C:\xampp\apache` — porta **80** |
| PHP | **8.2.12** (ZTS, VC++ 2019 x64) |
| Executável do PHP | `C:\xampp\php\php.exe` (não está no PATH) |
| **DocumentRoot do Apache** | `C:/Users/lucas/OneDrive/Área de Trabalho/projeto_ia` (a raiz deste repositório) |
| URL da aplicação | **http://localhost/** |

### Banco de dados

| Item | Valor |
|---|---|
| SGBD | **MySQL Server 26.7** (instalação standalone) |
| Serviço do Windows | `MySQL267` |
| Porta | **3306** |
| Diretório de dados | `C:/ProgramData/MySQL/MySQL Server 26.7/Data` |
| Banco da aplicação | **`lojavestuario`** |
| Usuário | `root` (possui senha definida fora do repositório) |

> **ATENÇÃO — existem dois servidores MySQL nesta máquina.** O XAMPP também instala o
> **MariaDB 10.4.32** em `C:\xampp\mysql`, mas o banco `lojavestuario` **não** está nele
> (a pasta `C:\xampp\mysql\data` contém apenas `mysql`, `performance_schema`,
> `phpmyadmin` e `test`). O banco real está no **MySQL Server 26.7**. Use sempre o
> cliente do MySQL 26.7:
> `"C:\Program Files\MySQL\MySQL Server 26.7\bin\mysql.exe"`.
> O cliente do XAMPP (`C:\xampp\mysql\bin\mysql.exe`) **falha** contra esse servidor com
> o erro `Plugin caching_sha2_password could not be loaded`.

### phpMyAdmin

O `DocumentRoot` do Apache foi apontado para a raiz deste projeto e **não existe**
diretiva `Alias /phpmyadmin` no `httpd.conf`. Portanto **`http://localhost/phpmyadmin`
não funciona**. Para administrar o banco use o **MySQL Workbench**
(`C:\Program Files\MySQL\MySQL Workbench 8.0 CE`) ou o cliente de linha de comando.

---

## 3. Estrutura de diretórios

```
projeto_ia/                        <- DocumentRoot do Apache (http://localhost/)
├── index.php                      <- Dashboard: só o conteúdo do <main> + includes
├── clientes/                      <- Módulo de clientes (padrão pages/ + partials/)
│   ├── index.php                  <- Front controller: página inteira x parcial (?partial=)
│   ├── pages/index.php            <- A página (HTML interno do <main> + alvo do htmx)
│   └── partials/table.php         <- Parcial com a tabela de clientes (resposta do htmx)
├── produtos/index.php             <- Página de produtos (usa os fragmentos)
├── pedidos/index.php              <- Página de pedidos (usa os fragmentos)
├── sql/
│   ├── 001_create.sql             <- DDL: cria o banco e as 4 tabelas
│   ├── 002_insert.sql             <- Dados de exemplo (carga inicial)
│   └── 003_select.sql             <- Consultas de exemplo
├── src/
│   ├── assets/css/app.css         <- CSS global (importa Bootstrap + Font Awesome)
│   ├── config/bd.local.php        <- Credenciais locais do banco (NÃO versionado)
│   └── includes/                  <- Fragmentos de layout reutilizados por todas as páginas
│       ├── head.php               <- <!doctype html> até </header> (define $pagina + htmx)
│       ├── aside.php              <- .container-fluid até a abertura de <main>
│       ├── footer.php             <- fechamento de </main> até </html>
│       └── bd.php                 <- Conexão PDO reutilizável (função bd())
├── .gitignore                     <- Ignora src/config/bd.local.php
├── AGENTS.md                      <- Este arquivo
└── README.md
```

---

## 4. Banco de dados

Banco: **`lojavestuario`** (4 tabelas). Todo script começa com `USE lojavestuario;`.

### 4.1 `cliente`

| Coluna | Tipo | Restrições |
|---|---|---|
| `id` | INT AUTO_INCREMENT | PK — `pk_cliente` |
| `nome` | VARCHAR(150) | NOT NULL |
| `cpf` | VARCHAR(14) | NOT NULL, UNIQUE — `uk_cliente_cpf` |
| `email` | VARCHAR(150) | NOT NULL, UNIQUE — `uk_cliente_email` |
| `telefone` | VARCHAR(20) | — |
| `dataCadastro` | DATETIME | DEFAULT CURRENT_TIMESTAMP |

### 4.2 `produto`

| Coluna | Tipo | Restrições |
|---|---|---|
| `id` | INT AUTO_INCREMENT | PK — `pk_produto` |
| `nome` | VARCHAR(150) | NOT NULL |
| `descricao` | VARCHAR(500) | — |
| `preco` | DECIMAL(10,2) | NOT NULL |
| `quantidadeEstoque` | INT | NOT NULL, DEFAULT 0 |
| `ativo` | BOOLEAN | NOT NULL, DEFAULT TRUE |
| `dataCadastro` | DATETIME | DEFAULT CURRENT_TIMESTAMP |

### 4.3 `pedido`

| Coluna | Tipo | Restrições |
|---|---|---|
| `id` | INT AUTO_INCREMENT | PK — `pk_pedido` |
| `cliente_id` | INT | NOT NULL, FK → `cliente(id)` — `fk_pedido_cliente` |
| `dataPedido` | DATETIME | DEFAULT CURRENT_TIMESTAMP |
| `status` | VARCHAR(30) | NOT NULL |
| `valorTotal` | DECIMAL(10,2) | NOT NULL, DEFAULT 0.00 |

### 4.4 `pedido_produto` (associativa N:N entre `pedido` e `produto`)

| Coluna | Tipo | Restrições |
|---|---|---|
| `id` | INT AUTO_INCREMENT | PK — `pk_pedido_produto` |
| `pedido_id` | INT | NOT NULL, FK → `pedido(id)` — `fk_pedido_produto_pedido` |
| `produto_id` | INT | NOT NULL, FK → `produto(id)` — `fk_pedido_produto_produto` |
| `quantidade` | INT | NOT NULL |
| `precoUnitario` | DECIMAL(10,2) | NOT NULL |
| `subtotal` | DECIMAL(10,2) | NOT NULL |

### 4.5 Valores válidos de `pedido.status`

`PENDENTE` · `PAGO` · `ENVIADO` · `ENTREGUE` · `CANCELADO`

### 4.6 Carga inicial (`sql/002_insert.sql`)

Envolvida em `START TRANSACTION` / `COMMIT`, com **IDs explícitos**.

| Tabela | Registros | Observação |
|---|---|---|
| `produto` | 50 | peças de vestuário (camisetas, calças, vestidos, jaquetas…) |
| `cliente` | 10 | Ana Souza … João Ribeiro (`000.000.001-00` …) |
| `pedido` | 50 | 5 pedidos para cada cliente |
| `pedido_produto` | 250 | 5 produtos por pedido |

> `pedido_produto.subtotal` é redundante por definição
> (`subtotal = quantidade * precoUnitario`). Mantenha essa consistência ao inserir dados.

---

## 5. Convenções de código

### PHP
- **PHP puro**: sem framework, sem Composer, sem autoload. Não introduza dependências via
  Composer sem alinhamento explícito com o autor.
- Indentação com **TAB** (como no `index.php`).
- Arquivos iniciam com `<?php` e usam *short echo tags* (`<?= ... ?>`) no HTML.
- Interface e textos em **pt-BR** (`<html lang="pt-BR">`).
- **Conexão com o banco**: sempre `require_once __DIR__ . '/../src/includes/bd.php';` e a
  função `bd()`, que devolve um **PDO** singleton (`ERRMODE_EXCEPTION`, `FETCH_ASSOC`,
  `EMULATE_PREPARES` desligado, `charset=utf8mb4`) com `try/catch` em `PDOException`.
  Nunca espalhe `new PDO(...)` pelas páginas.
- **Credenciais**: ficam em `src/config/bd.local.php`, que está no `.gitignore`. O `bd.php`
  carrega esse arquivo se ele existir e traz apenas o padrão de fallback. **Nunca** versione
  senhas — nem neste AGENTS.md.

### SQL
- Palavras-chave em **MAIÚSCULAS** (`CREATE TABLE`, `NOT NULL`, `DEFAULT`, `INNER JOIN`).
- Tabelas em **snake_case e no singular** (`cliente`, `pedido_produto`).
- Colunas em **camelCase** (`dataCadastro`, `quantidadeEstoque`, `valorTotal`).
- Prefixos de constraint: **`pk_`**, **`fk_`**, **`uk_`**
  (ex.: `pk_cliente`, `fk_pedido_cliente`, `uk_cliente_email`).
- Scripts numerados em ordem de execução (`001_create.sql`, `002_insert.sql`, …).
- Cada script começa com `USE lojavestuario;`.
- Separadores de seção no formato:

```sql
-- =====================================================
-- N. TÍTULO DA SEÇÃO
-- =====================================================
```

### Front-end
- **Bootstrap 5.3.3** + **Font Awesome 6.5.2** + **htmx 2.0.4**.
- O **htmx** é carregado por `<script>` com `defer` dentro de `src/includes/head.php`, logo
  está disponível em todas as páginas. A listagem de clientes usa `hx-get` + `hx-trigger`.
- Todo CSS global fica em **`src/assets/css/app.css`**. Os CDNs são importados **dentro
  desse arquivo** via `@import url(...)`, e o `index.php` referencia **somente**
  `src/assets/css/app.css`. **Não** adicione `<link>` de CDN direto no HTML.
- Ícones: Font Awesome (`<i class="fa-solid fa-users fa-fw me-2"></i>`).
- Layout: `navbar` no topo, `aside` com `nav-pills` à esquerda, `main` com `card` /
  `shadow-sm` para os blocos.

---

## 6. Layout, fragmentos e roteamento

### 6.1 Fragmentos de layout (`src/includes/`)

Todas as páginas do sistema são montadas com **3 fragmentos reutilizáveis**. Cada página
fornece **apenas o HTML interno do `<main>`**:

| Arquivo | Conteúdo |
|---|---|
| `src/includes/head.php` | do `<?php $pagina = ...` até o `</header>` |
| `src/includes/aside.php` | de `<div class="container-fluid">` até a abertura de `<main ...>` |
| `src/includes/footer.php` | fechamento de `</main>`, os `</div>`, o `<script>` do Bootstrap e `</body></html>` |

Padrão de uso (exemplo de `clientes/index.php`):

```php
<?php $pagina = 'clientes'; ?>
<?php require __DIR__ . '/../src/includes/head.php'; ?>
<?php require __DIR__ . '/../src/includes/aside.php'; ?>

				<div class="mb-4">
					<h1 class="h3 mb-1">Clientes</h1>
				</div>

<?php require __DIR__ . '/../src/includes/footer.php'; ?>
```

- O `head.php` define `$pagina` com *guard*: `$pagina = $pagina ?? ($_GET['pagina'] ?? 'inicio')`.
  A raiz usa a query string; os módulos apenas declaram `$pagina = 'clientes'` (etc.) antes do include.
- **Não** duplique `<!doctype>`, `<header>`, `<aside>` ou `<main>` nas páginas novas: use os fragmentos.
- **Caminhos absolutos do site.** Nos fragmentos os links partem da raiz
  (`/src/assets/css/app.css`, `/index.php?pagina=inicio`, `/clientes/`) para funcionarem
  igual na raiz e dentro de `/clientes/`, `/produtos/` e `/pedidos/`.

### 6.2 Roteamento

O `index.php` da raiz é o **dashboard**, um front controller simples baseado em *query string*:

- Página padrão: `inicio` — `$pagina` vem de `$_GET['pagina']`, tratado dentro do `head.php`.
- O menu (`aside.php`) aponta para as **páginas reais**: `/index.php?pagina=inicio` (dashboard),
  `/clientes/`, `/produtos/` e `/pedidos/`. Cada item marca o ativo comparando `$pagina`:

```php
class="nav-link <?= $pagina === 'clientes' ? 'active' : 'text-dark' ?>"
```

- Os valores possíveis de `$pagina` são `inicio` (raiz), `clientes`, `produtos` e `pedidos`
  — os três últimos declarados no topo do `index.php` de cada módulo.
- A query string `?pagina=` serve apenas para o dashboard da raiz; os módulos são acessados
  diretamente pela própria pasta (`/clientes/`, `/produtos/`, `/pedidos/`).
- Não há `.htaccess` nem rotas amigáveis.

### 6.3 Padrão de módulo com htmx (`clientes/`)

O módulo `clientes` é o **modelo de referência** para páginas que carregam dados do banco:

| Arquivo | Papel |
|---|---|
| `clientes/index.php` | **Front controller**. Se vier `?partial=<nome>` e o nome estiver na *lista branca* (`$partialsPermitidos`), devolve **só o parcial**; caso contrário, a página completa. |
| `clientes/pages/index.php` | A página: `$pagina = 'clientes'`, os 3 fragmentos de layout e o **alvo do htmx**. |
| `clientes/partials/table.php` | Resposta do htmx: `require_once` do `bd.php`, a consulta e a tabela (só o fragmento, **nunca** `<!doctype>`). |

O carregamento é disparado pelo próprio elemento alvo da página:

```html
<div id="tabela-clientes" class="card border-0 shadow-sm"
		hx-get="/clientes/?partial=table"
		hx-trigger="load"
		hx-target="this"
		hx-swap="innerHTML">
```

- O conteúdo inicial desse `div` é o *spinner* ("Carregando clientes..."), substituído pela
  tabela quando o htmx recebe a resposta; não é preciso CSS adicional no `app.css`.
- **Lista branca obrigatória**: todo parcial novo precisa entrar em `$partialsPermitidos` no
  front controller do módulo (impede *path traversal* via `?partial=`).
- O parcial também responde em `/clientes/partials/table.php` (acesso direto), porque o
  `require_once` usa `__DIR__`.
- **Sempre** `htmlspecialchars()` na saída de dados vindos do banco.

---

## 7. Comandos úteis

Ambiente **Windows + PowerShell**. Os executáveis `php` e `mysql` **não estão no PATH**,
use o caminho completo.

```powershell
# Iniciar / parar a stack XAMPP (Apache + MySQL do XAMPP)
C:\xampp\xampp_start.exe
C:\xampp\xampp_stop.exe

# Validar a sintaxe de um arquivo PHP
& 'C:\xampp\php\php.exe' -l index.php

# Servidor de desenvolvimento alternativo (sem passar pelo Apache)
& 'C:\xampp\php\php.exe' -S localhost:8000

# Cliente MySQL correto (MySQL Server 26.7) — pede a senha do root
& 'C:\Program Files\MySQL\MySQL Server 26.7\bin\mysql.exe' -u root -p -D lojavestuario

# Importar os scripts na ordem (cria banco, tabelas e a massa de dados)
Get-Content sql\001_create.sql | & 'C:\Program Files\MySQL\MySQL Server 26.7\bin\mysql.exe' -u root -p
Get-Content sql\002_insert.sql | & 'C:\Program Files\MySQL\MySQL Server 26.7\bin\mysql.exe' -u root -p

# Verificar se a aplicação está respondendo
Invoke-WebRequest -Uri 'http://localhost/' -UseBasicParsing

# Git
git status
git add .
git commit -m "Descrição da alteração"
git push origin main
```

> **Nunca** execute comandos interativos que fiquem esperando entrada (ex.: `git log`
> sem `--no-pager`, `mysql` sem `-e`/`-p`). Use flags não interativas
> (`--no-pager`, `--non-interactive`, `-e "SQL"`).

---

## 8. Armadilhas conhecidas (leia antes de alterar)

1. **Dois servidores MySQL na máquina.** O XAMPP tem o MariaDB em `C:\xampp\mysql`, mas o
   banco da aplicação (`lojavestuario`) está no **MySQL Server 26.7** (serviço `MySQL267`,
   porta 3306). Use o cliente do MySQL 26.7; o cliente do XAMPP falha com
   `caching_sha2_password`.
2. **`php` não está no PATH.** Sempre use `C:\xampp\php\php.exe`.
3. **O `DocumentRoot` do Apache é a raiz deste projeto.** Não crie uma cópia do projeto
   dentro de `C:\xampp\htdocs`; a aplicação é servida em `http://localhost/` apontando
   para este repositório. Cuidado com caminhos relativos.
4. **phpMyAdmin indisponível** em `http://localhost/phpmyadmin` (não há `Alias` no
   Apache). Use o MySQL Workbench (`C:\Program Files\MySQL\MySQL Workbench 8.0 CE`) ou a CLI.
5. **Caminho com acento e espaço.** O projeto fica em
   `...\OneDrive\Área de Trabalho\projeto_ia`. Sempre coloque caminhos entre aspas no
   PowerShell e prefira `/` em arquivos de configuração.
6. **Sincronização do OneDrive.** A pasta está dentro do OneDrive, que pode travar
   arquivos durante a sincronização. Se um arquivo aparecer bloqueado, aguarde a
   sincronização em vez de forçar.
7. **Credenciais não versionadas.** A senha do `root` **não** está no repositório: ela vive
   em `src/config/bd.local.php`, que é ignorado pelo `.gitignore`. Se esse arquivo não
   existir, o `bd.php` usa o padrão de fallback e a conexão pode falhar. Não invente valores
   nem versione credenciais (nem neste AGENTS.md, nem em scripts de exemplo).
8. **`src/assets/css/app.css` é o único ponto de CSS.** Não espalhe estilos pelo HTML.
9. **Use caminhos absolutos do site nos fragmentos.** `/src/...` e `/index.php?...` funcionam
   tanto na raiz quanto em `/clientes/`; caminhos relativos quebram nos módulos.
10. **`README.md` está com codificação irregular** (UTF-16 / caracteres nulos). Evite
    reescrevê-lo às cegas; prefira editar/recriar com cuidado ou pedir confirmação.

---

## 9. Estado atual e próximos passos

**Concluído**

- [x] Layout do painel administrativo (`index.php`)
- [x] Layout desmembrado em fragmentos reutilizáveis (`src/includes/`)
- [x] CSS centralizado em `src/assets/css/app.css`
- [x] Modelagem do banco (`sql/001_create.sql`)
- [x] Massa de dados de exemplo (`sql/002_insert.sql`)
- [x] Consultas de exemplo (`sql/003_select.sql`)
- [x] Ligar as pastas `clientes/`, `produtos/` e `pedidos/` ao menu (links diretos)
- [x] Camada de conexão com o banco (PDO) reutilizável (`src/includes/bd.php`)
- [x] Dependência do htmx incluída globalmente (`src/includes/head.php`)
- [x] Módulo `clientes` com `pages/` + `partials/` e listagem via htmx

**Pendente / candidatos naturais**

- [ ] CRUD de clientes, produtos e pedidos
- [ ] Aplicar o mesmo padrão `pages/` + `partials/` em `produtos` e `pedidos`
- [ ] Substituir os números fixos dos cards de indicadores por dados reais
- [ ] Autenticação (login e ação "Sair")
- [ ] Paginação e filtros nas listagens

---

## 10. Git

- Branch principal: **`main`**
- Remote `origin`: `https://github.com/lucasdelavy23/projeto_ia.git`
- Mensagens de commit em **português** (ex.: `Primeiro commit`)
- Antes de finalizar uma alteração, rode `& 'C:\xampp\php\php.exe' -l <arquivo>` nos
  arquivos PHP modificados.

