# 🌐 Atlas Framework

**Version:** 0.9.9 <br>
**Type:** PHP MVC web framework <br>
**PHP:** 8.1+ <br>

## 🗣 Overview

Atlas is a modular PHP framework for backend web development. It provides an MVC architecture, a custom template engine called Flux, a SQL query builder, database schema management, a module system, and SCSS compilation support.

## 📁 Project Structure

```
Atlas/
├── app/
│   ├── Controllers/         # Application controllers
│   ├── Models/              # Application models
│   └── index.php            # App bootstrap (creates Router, loads modules, processes URI)
├── config/
│   ├── example.config.php   # Config template (loads .env, defines DB_* constants)
│   ├── config.php           # Actual config (gitignored, copy from example)
│   ├── modules.json         # Module enable/disable configuration
│   └── php.ini              # PHP runtime settings
├── libraries/
│   ├── Classes/
│   │   ├── Database/        # QueryBuilder, SchemaBuilder, SchemaEngine
│   │   ├── FileCompiler/    # CompileEngine, FluxCompileEngine, ViewEngine, ComponentEngine
│   │   ├── ModuleLoader/    # ModuleEngine
│   │   ├── Mvc/             # Controller, Model, ModuleController abstracts
│   │   ├── Routing/         # Router, Route
│   │   └── Templates/       # Code generation templates for CLI scaffolding
│   └── Constants/           # RouteActions enum, GlobalFunctions, and other enums
├── modules/                 # Self-contained application modules
├── public/
│   ├── cache/compiled/      # Compiled Flux templates (auto-generated)
│   ├── css/                 # Stylesheets
│   └── js/                  # JavaScript
├── router.php               # Entry point: URL validation, rate limiting, then dispatches to app/
├── routes.php               # Route definitions
├── composer.json             # PSR-4 autoloading
├── package.json              # Node dependencies (SCSS)
├── .env.example              # Environment variable template
└── Atlas                     # CLI tool for scaffolding and running the dev server
```

## 🔁 Request Lifecycle

1. **`router.php`** receives every request (PHP built-in server or web server rewrite).
2. If the request has an `HTTP_REFERER`, it is treated as a static asset request — the file is served directly via `CompileEngine`.
3. Otherwise, the URL is validated against a character whitelist and rate-limited via a session-based token bucket.
4. **`app/index.php`** is loaded — it creates the `Router`, loads modules via `ModuleEngine`, includes `routes.php`, and calls `$router->ProcessUri()`.
5. The **Router** matches the URI against registered routes (exact match or segment-boundary prefix). On match, it instantiates the controller and calls the method with any extra path segments as arguments.
6. Controllers call `$this->view()` which compiles and renders Flux templates, or `$this->redirect()` for local redirects.

## ⭕ Core Components

### Routing

Routes are registered via static helpers on `Route`:

```php
use Libraries\Classes\Routing\Route;
use App\Controllers\WelcomeController;

Route::Get("/welcome", [WelcomeController::class, "WelcomePage"]);
Route::Post("/submit", [SomeController::class, "handleSubmit"]);
```

- Routes match by exact URI or segment boundary (e.g. `/welcome` matches `/welcome` and `/welcome/foo` but not `/welcomepage`).
- Extra path segments after the route URI are passed as arguments to the controller method.
- Supported HTTP methods: `GET`, `POST` (via `RouteActions` enum).

### Controllers

Extend the abstract `Controller` class:

```php
namespace App\Controllers;

use Libraries\Classes\Mvc\Controller;

class WelcomeController extends Controller
{
    public function WelcomePage()
    {
        $this->view("welcome", ["title" => "Hello"]);
    }
}
```

**Available methods:**

- `view(string $viewName, array $data = [])` — renders a Flux template from `app/views/`
- `redirect(string $url)` — local redirect only (paths starting with `/`, blocks `//` and external URLs)

### Models

Extend the abstract `Model` class:

```php
namespace App\Models;

use Libraries\Classes\Mvc\Model;

class WelcomeModel extends Model { }
```

- Automatically creates a PDO connection using `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` constants.
- Exposes `$this->db` as a `QueryBuilder` instance for database operations.

### QueryBuilder

Fluent SQL abstraction with parameterized queries:

```php
// SELECT
$results = $this->db->table('users')
    ->select('id', 'name', 'email')
    ->where('active', '=', 1)
    ->orderBy('name', 'ASC')
    ->limit(10)
    ->get();

// INSERT
$this->db->table('users')->insert([
    'name' => 'John',
    'email' => 'john@example.com',
]);

// UPDATE (requires WHERE clause — throws RuntimeException without one)
$this->db->table('users')
    ->where('id', '=', 1)
    ->update(['name' => 'Jane']);

// DELETE (requires WHERE clause)
$this->db->table('users')
    ->where('id', '=', 1)
    ->delete();

// JOIN
$this->db->table('users')
    ->join('orders', 'users.id', '=', 'orders.user_id')
    ->select('users.name', 'orders.total')
    ->get();
```

**Safety features:**

- All values use prepared statements (SQL injection safe).
- `DELETE` and `UPDATE` without a `WHERE` clause throw a `RuntimeException`.

### SchemaBuilder

Database migration DSL:

```php
$schema = new SchemaBuilder($pdo);

$schema->create('users', [
    'id' => ['type' => 'int', 'primary', 'auto_increment', 'unsigned'],
    'name' => ['type' => 'varchar', 'length' => 100, 'not_null'],
    'email' => ['type' => 'varchar', 'length' => 255, 'unique', 'not_null'],
    'bio' => ['type' => 'text', 'default' => null],
    'created_at' => ['type' => 'datetime', 'default' => 'CURRENT_TIMESTAMP'],
]);

$schema->addColumn('users', 'role', ['type' => 'varchar', 'length' => 50, 'default' => 'user'], 'email');
$schema->modifyColumn('users', 'name', ['type' => 'varchar', 'length' => 200, 'not_null']);
$schema->drop('users');
```

**Supported column types:** `int`, `bigint`, `bool`, `varchar`, `text`, `datetime`, `date`, `float`, `decimal` <br>
**Column flags:** `primary`, `auto_increment`, `unsigned`, `not_null`, `unique`, `default`, `foreign`

### Flux Template Engine

Custom template syntax compiled to PHP and cached in `public/cache/compiled/`:

```html
<!-- Variable output (auto-escaped with htmlspecialchars) -->
<h1>{{ $title }}</h1>

<!-- Components -->
@component('header') @component('sidebar', 'moduleName')

<!-- Control structures -->
@foreach($items as $item)
<p>{{ $item->name }}</p>
@endforeach @if($user->isAdmin())
<span>Admin</span>
@endif @for($i = 0; $i < 10; $i++)
<p>{{ $i }}</p>
@endfor
```

- Templates are compiled once and cached. Re-compiled only when the source file is modified.
- `{{ }}` output is automatically escaped via `htmlspecialchars()` with `ENT_QUOTES` and `UTF-8`.
- Template rendering uses an isolated closure scope — only the passed `$data` array keys are available as variables.

### Module System

Self-contained modules live in `modules/` and are configured via `config/modules.json`:

```json
[
	{ "name": "Blog", "enabled": true },
	{ "name": "Shop", "enabled": false }
]
```

Each module has its own controllers, views, components, and route file. Modules extend `ModuleController` instead of `Controller`.

## 🔧 Configuration

### Environment Variables

Copy `.env.example` to `.env` in the project root:

```
DB_HOST=127.0.0.1
DB_USER=root
DB_PASS=
DB_NAME=atlas_test_db
URLROOT=http://localhost:
PORT=8000
```

The config file (`config/config.php`, copied from `config/example.config.php`) loads `.env` automatically and falls back to defaults if variables are missing.

**Important:** `.env` and `config/config.php` are gitignored. Never commit credentials.

### PHP Settings

`config/php.ini` sets:

- `error_reporting = E_ALL`
- `display_errors = Off` (errors logged, not shown to users)
- `log_errors = On`

## 💻 CLI Tool

The `Atlas` file in the project root is a CLI tool for scaffolding and running the dev server:

```bash
php Atlas dev                   # Start the dev server
php Atlas make [name] [option]  # Scaffolds template files
php Atlas db                    # Run migrations
php Atlas db:refresh            # Run migrations
```

Options to use for the scaffold/create commands are:

```bash
-m                              #model
-v                              #view
-c                              #controller
-d                              #schema
-mod                            #model
-comp                           #component
```

## 🔐 Security Notes

- **URL validation** uses a whitelist approach — only safe characters are allowed through. Double-encoding is blocked.
- **SQL injection** is prevented by parameterized queries in `QueryBuilder`.
- **XSS** is mitigated by automatic `htmlspecialchars()` escaping in `{{ }}` template output.
- **Open redirects** are blocked — `redirect()` only allows local paths.
- **Template rendering** uses isolated closure scope to prevent variable injection.
- **Rate limiting** is session-based (token bucket, 100 tokens, 1/sec refill). Suitable for basic abuse prevention, not DDoS protection.
- **CSRF protection** is not yet built into the framework — implement token validation manually for POST routes handling sensitive actions.

## 📦 Dependencies

**Composer** (`composer.json`):

- PSR-4 autoloading only, no third-party PHP packages required.

**Node** (`package.json`):

- SCSS compilation support (see package.json for specific packages).
