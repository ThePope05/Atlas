# Atlas Framework — Implementation Roadmap

A phased plan to take Atlas from v0.5 to a production-ready framework.

---

## Phase 1: Core Security & Error Handling

These are prerequisites for everything else. Without them, nothing built on top is trustworthy.

### 1.1 CSRF Protection
**Files:** new `libraries/Classes/Security/CsrfGuard.php`, edit `FluxCompileEngine.php`, edit `router.php`

- Generate a per-session CSRF token (`bin2hex(random_bytes(32))`) and store it in `$_SESSION['_csrf_token']`.
- Add a `@csrf` Flux directive that compiles to a hidden input: `<input type="hidden" name="_token" value="<?= $_SESSION['_csrf_token'] ?>">`.
- Validate the token on every POST request in `router.php` (before dispatching). Reject with 403 if missing or mismatched.
- Provide a `csrf_token()` global helper for use outside templates (AJAX headers, etc.).

### 1.2 Global Exception Handler
**Files:** new `libraries/Classes/ErrorHandler.php`, edit `router.php`, edit `Model.php`

- Register a global exception handler via `set_exception_handler()` in `router.php`.
- In development (`APP_ENV=local`): render a detailed error page with stack trace, file, line, and request context.
- In production (`APP_ENV=production`): render a generic 500 error page and log the full exception to `storage/logs/error.log`.
- Fix `Model.php` constructor: replace `echo` with `throw new RuntimeException(...)`. Remove the bare catch that swallows the PDOException.

### 1.3 Request & Response Objects
**Files:** new `libraries/Classes/Http/Request.php`, new `libraries/Classes/Http/Response.php`, edit `Router.php`, edit `Controller.php`

**Request class:**
- Wraps `$_GET`, `$_POST`, `$_FILES`, `$_SERVER`, `$_COOKIE`.
- Methods: `input(string $key, $default = null)`, `all()`, `method()`, `isPost()`, `isGet()`, `header(string $name)`, `ip()`, `url()`, `file(string $key)`.
- Single instance created in `router.php` and passed through to controllers.

**Response class:**
- Methods: `json(array $data, int $status = 200)`, `html(string $content, int $status = 200)`, `redirect(string $url, int $status = 302)`, `status(int $code)`, `header(string $name, string $value)`, `send()`.
- Controllers return `Response` objects instead of echoing directly.
- Update `Controller::redirect()` to use `Response::redirect()` internally.

---

## Phase 2: Middleware & Routing Upgrades

Build the infrastructure that all future features (auth, validation, API) depend on.

### 2.1 Middleware Pipeline
**Files:** new `libraries/Classes/Http/Middleware.php`, new `libraries/Classes/Http/MiddlewareStack.php`, edit `Router.php`, edit `Route.php`

- Define a `Middleware` interface: `public function handle(Request $request, Closure $next): Response`.
- `MiddlewareStack` executes middleware in order, passing the request through each one.
- Routes accept an optional middleware array:
  ```php
  Route::Get("/admin", [AdminController::class, "index"], [AuthMiddleware::class]);
  Route::Post("/api/data", [ApiController::class, "store"], [AuthMiddleware::class, CsrfMiddleware::class]);
  ```
- Global middleware (applied to all routes) is registered in `app/index.php`.
- Move CSRF validation from `router.php` into a `CsrfMiddleware` class.
- Move rate limiting from `router.php` into a `ThrottleMiddleware` class.

### 2.2 Named Route Parameters
**Files:** edit `Route.php`, edit `Router.php`

- Support `{param}` syntax in route URIs: `/user/{id}`, `/post/{id}/comment/{commentId}`.
- Router extracts named parameters via regex and passes them as named arguments to the controller method.
- Optional parameters with `?`: `/user/{id?}` (matches `/user` and `/user/5`).
- Type constraints: `/user/{id:int}` compiles to `(\d+)`, `/post/{slug:string}` compiles to `([a-zA-Z0-9_-]+)`.
- Example:
  ```php
  Route::Get("/user/{id}", [UserController::class, "show"]);
  // UserController::show(int $id)
  ```

### 2.3 Additional HTTP Methods
**Files:** edit `RouteActions.php`, edit `Route.php`, edit `Router.php`, edit `router.php`

- Add `Put`, `Patch`, `Delete`, `Options` to the `RouteActions` enum.
- Add static factory methods: `Route::Put()`, `Route::Patch()`, `Route::Delete()`.
- Since HTML forms only support GET/POST, support a `_method` hidden field for PUT/PATCH/DELETE (method spoofing).
- Add a `@method('PUT')` Flux directive that outputs a hidden `_method` input.
- Router checks `$_POST['_method']` before falling back to `$_SERVER['REQUEST_METHOD']`.

### 2.4 Route Groups
**Files:** edit `Route.php`

- Group routes by prefix, middleware, or namespace:
  ```php
  Route::group(['prefix' => '/api', 'middleware' => [AuthMiddleware::class]], function () {
      Route::Get("/users", [UserController::class, "index"]);
      Route::Post("/users", [UserController::class, "store"]);
  });
  ```
- Nested groups inherit and merge parent attributes.

---

## Phase 3: Validation & Input Handling

### 3.1 Validation Layer
**Files:** new `libraries/Classes/Validation/Validator.php`, new `libraries/Classes/Validation/ValidationException.php`

- Rule syntax: `['field' => 'required|string|max:255|email']`.
- Built-in rules: `required`, `string`, `int`, `email`, `min:{n}`, `max:{n}`, `in:{a},{b},{c}`, `unique:{table},{column}`, `confirmed`, `nullable`, `date`, `url`, `boolean`, `array`.
- `Validator::make(array $data, array $rules): Validator` — returns a validator instance.
- `$validator->validate()` — throws `ValidationException` on failure.
- `$validator->errors()` — returns an associative array of field => error messages.
- Error messages are customizable per rule and per field.

### 3.2 Form Request Validation
**Files:** new `libraries/Classes/Http/FormRequest.php`

- Abstract class that controllers can type-hint to auto-validate before the method runs:
  ```php
  class StoreUserRequest extends FormRequest {
      public function rules(): array {
          return ['name' => 'required|string|max:100', 'email' => 'required|email|unique:users,email'];
      }
  }
  ```
- If validation fails, redirect back with errors and old input (for web), or return 422 JSON (for API).

---

## Phase 4: Model & Database Improvements

### 4.1 Active Record Methods on Model
**Files:** edit `Model.php`

Add convenience methods that scope to `$this->table`:

```php
public function all(): array                                    // SELECT * FROM table
public function find(int $id): ?array                          // WHERE id = $id LIMIT 1
public function where(string $col, $op, $val = null): self     // Proxy to QueryBuilder
public function create(array $data): int                       // INSERT using $fillable filter
public function update(int $id, array $data): int              // UPDATE WHERE id = $id
public function destroy(int $id): int                          // DELETE WHERE id = $id
```

- `create()` and `update()` filter input through `$fillable` — only allowed columns are written.
- Chain support: `$this->model->where('active', 1)->get()`.

### 4.2 Relationships
**Files:** new `libraries/Classes/Database/Relations/HasMany.php`, `BelongsTo.php`, `HasOne.php`, edit `Model.php`

- `hasMany(string $related, string $foreignKey, string $localKey = 'id')` — e.g. User has many Posts.
- `belongsTo(string $related, string $foreignKey, string $ownerKey = 'id')` — e.g. Post belongs to User.
- `hasOne(string $related, string $foreignKey, string $localKey = 'id')` — e.g. User has one Profile.
- Returns query builder scoped to the relationship, executed lazily.

### 4.3 Pagination
**Files:** new `libraries/Classes/Database/Paginator.php`, edit `QueryBuilder.php`

- `$this->db->table('posts')->paginate(int $perPage = 15): Paginator`.
- `Paginator` holds: `items`, `currentPage`, `lastPage`, `total`, `perPage`.
- Methods: `items()`, `nextPageUrl()`, `previousPageUrl()`, `hasMorePages()`, `links()` (returns HTML).
- Page number read from `$_GET['page']`.

### 4.4 Query Builder Additions
**Files:** edit `QueryBuilder.php`

- `orWhere(string $column, $operator, $value = null)` — OR conditions.
- `whereIn(string $column, array $values)` — WHERE IN (...).
- `whereNull(string $column)` / `whereNotNull(string $column)`.
- `groupBy(string ...$columns)` / `having(string $column, $operator, $value)`.
- `raw(string $expression)` — raw SQL expression (for complex queries).
- `transaction(Closure $callback)` — wraps callback in BEGIN/COMMIT with auto-ROLLBACK on exception.
- `sum(string $column)`, `avg(string $column)`, `min(string $column)`, `max(string $column)` — aggregate helpers.

### 4.5 Database Seeders
**Files:** new `libraries/Classes/Database/Seeder.php`, edit `Atlas` CLI

- Seeders live in `app/db/seeders/` (and `modules/*/db/seeders/`).
- Each seeder has a `run(QueryBuilder $db)` method.
- CLI command: `php Atlas db:seed` runs all seeders, `php Atlas db:seed --class=UserSeeder` runs one.

---

## Phase 5: Flux Template Engine Upgrades

### 5.1 Missing Control Structures
**Files:** edit `FluxCompileEngine.php`

Add support for:
- `@elseif($condition)` — compiles to `<?php elseif ($condition): ?>`.
- `@else` — compiles to `<?php else: ?>`.
- `@while($condition)` / `@endwhile`.
- `@switch($var)` / `@case($val)` / `@default` / `@endswitch`.
- `{!! $html !!}` — raw unescaped output (for trusted HTML, explicitly opted in).

### 5.2 Template Layouts & Sections
**Files:** edit `FluxCompileEngine.php`, new layout compilation logic

- `@extends('layout')` — declares a parent layout.
- `@section('content')` / `@endsection` — defines a named section.
- `@yield('content')` — in the parent layout, outputs the child section.
- This enables a single base layout with header/footer, reused across all pages.

### 5.3 Built-in Directives
**Files:** edit `FluxCompileEngine.php`

- `@csrf` — outputs CSRF hidden input.
- `@method('PUT')` — outputs method spoofing hidden input.
- `@auth` / `@guest` / `@endauth` / `@endguest` — conditional rendering based on authentication state.
- `@json($data)` — outputs JSON-encoded data safely for embedding in `<script>` tags.
- `@include('partial', ['key' => 'value'])` — alias for `@component` but semantically for partials.

---

## Phase 6: Authentication System

### 6.1 Session-Based Authentication
**Files:** new `libraries/Classes/Auth/Auth.php`, new `libraries/Classes/Auth/AuthMiddleware.php`, new `libraries/Classes/Auth/GuestMiddleware.php`

**Auth class (static helper):**
- `Auth::attempt(array $credentials): bool` — checks email/password against users table, starts session.
- `Auth::login(array $user): void` — sets session data.
- `Auth::logout(): void` — destroys session.
- `Auth::check(): bool` — is user logged in?
- `Auth::user(): ?array` — returns current user data.
- `Auth::id(): ?int` — returns current user ID.

**Password hashing:**
- `password_hash()` / `password_verify()` (bcrypt via PHP built-in).
- Never store plain-text passwords.

**Middleware:**
- `AuthMiddleware` — redirects to login if not authenticated.
- `GuestMiddleware` — redirects to home if already authenticated.

### 6.2 CLI Auth Scaffolding
**Files:** edit `Atlas` CLI

- `php Atlas make:auth` — generates:
  - `LoginController`, `RegisterController`, `LogoutController`
  - Login and register views
  - User migration schema
  - Auth routes

---

## Phase 7: CLI Tool Expansion

### 7.1 New Scaffolding Commands
**Files:** edit `Atlas` CLI

- `php Atlas make:middleware {Name}` — generates middleware class.
- `php Atlas make:seeder {Name}` — generates seeder class.
- `php Atlas make:request {Name}` — generates form request validation class.
- `php Atlas make:view {Name}` — already exists, but add `--layout` flag to extend a base layout.

### 7.2 New Utility Commands
**Files:** edit `Atlas` CLI

- `php Atlas db:seed` — run database seeders.
- `php Atlas db:status` — show which migrations have run and which are pending.
- `php Atlas routes:list` — already exists, but add middleware and method columns to the output.
- `php Atlas serve` — alias for `localhost` (more conventional name).

---

## Phase 8: Testing & Quality

### 8.1 PHPUnit Integration
**Files:** new `phpunit.xml`, new `tests/` directory, edit `composer.json`

- Add PHPUnit as a dev dependency.
- Create a `TestCase` base class that boots the framework, sets up an in-memory SQLite database, and provides helpers.
- `php Atlas test` runs the test suite.

### 8.2 Test Helpers
**Files:** new `tests/TestCase.php`

- `$this->get('/path')` / `$this->post('/path', $data)` — simulate HTTP requests.
- `$this->assertResponseStatus(200)`.
- `$this->assertViewContains('text')`.
- `$this->assertDatabaseHas('table', ['column' => 'value'])`.
- `$this->assertRedirectTo('/path')`.

---

## Phase 9: Logging & Session Management

### 9.1 Logger
**Files:** new `libraries/Classes/Logging/Logger.php`

- PSR-3 inspired interface: `info()`, `warning()`, `error()`, `debug()`.
- Writes to `storage/logs/atlas.log` with timestamps.
- Log rotation: new file per day (`atlas-2026-02-18.log`).
- Accessible via a `Log` static helper or through the container.

### 9.2 Session Manager
**Files:** new `libraries/Classes/Session/Session.php`

- Wraps `$_SESSION` with a clean API: `Session::get('key')`, `Session::set('key', 'value')`, `Session::forget('key')`, `Session::has('key')`.
- Flash messages: `Session::flash('success', 'Saved!')` — available on next request only, then auto-cleared.
- Used by validation (flash old input), auth (flash errors), and CSRF.

---

## Implementation Order

The phases are designed to build on each other:

```
Phase 1  Security & Error Handling     ← Foundation, do first
  │
Phase 2  Middleware & Routing          ← Depends on Request/Response from Phase 1
  │
Phase 3  Validation                    ← Depends on middleware pipeline
  │
Phase 4  Model & Database             ← Independent, can overlap with Phase 3
  │
Phase 5  Template Upgrades            ← Independent, can overlap with Phase 3-4
  │
Phase 6  Authentication               ← Depends on sessions, middleware, validation
  │
Phase 7  CLI Expansion                ← Add commands as each phase completes
  │
Phase 8  Testing                      ← Add after core features stabilize
  │
Phase 9  Logging & Sessions           ← Can start alongside Phase 2, used by Phase 6
```

Phases 3, 4, and 5 are largely independent and can be worked on in parallel. Phase 6 (auth) is the biggest consumer of earlier phases and should come after middleware and validation are solid.

---

## Files That Will Be Created

```
libraries/Classes/Security/CsrfGuard.php
libraries/Classes/ErrorHandler.php
libraries/Classes/Http/Request.php
libraries/Classes/Http/Response.php
libraries/Classes/Http/Middleware.php
libraries/Classes/Http/MiddlewareStack.php
libraries/Classes/Http/FormRequest.php
libraries/Classes/Validation/Validator.php
libraries/Classes/Validation/ValidationException.php
libraries/Classes/Database/Relations/HasMany.php
libraries/Classes/Database/Relations/BelongsTo.php
libraries/Classes/Database/Relations/HasOne.php
libraries/Classes/Database/Paginator.php
libraries/Classes/Database/Seeder.php
libraries/Classes/Auth/Auth.php
libraries/Classes/Auth/AuthMiddleware.php
libraries/Classes/Auth/GuestMiddleware.php
libraries/Classes/Logging/Logger.php
libraries/Classes/Session/Session.php
tests/TestCase.php
phpunit.xml
```

## Files That Will Be Modified

```
router.php                                  (CSRF, error handler, method spoofing)
app/index.php                               (global middleware registration)
libraries/Classes/Mvc/Controller.php        (Request/Response integration)
libraries/Classes/Mvc/Model.php             (active record methods, error handling)
libraries/Classes/Routing/Router.php        (middleware, named params, method dispatch)
libraries/Classes/Routing/Route.php         (groups, middleware, param syntax)
libraries/Constants/RouteActions.php        (PUT, PATCH, DELETE, OPTIONS)
libraries/Classes/FileCompiler/FluxCompileEngine.php  (new directives, layouts)
libraries/Classes/Database/QueryBuilder.php (orWhere, whereIn, transactions, aggregates)
Atlas                                       (new CLI commands)
composer.json                               (PHPUnit dev dependency)
```
