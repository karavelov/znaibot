# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
# Backend
php artisan serve          # Start development server
php artisan migrate        # Run database migrations
php artisan db:seed        # Seed the database
php artisan tinker         # Interactive REPL

# Frontend
npm run dev                # Start Vite dev server (hot reload)
npm run build              # Build production assets

# Testing
php artisan test                          # Run all tests
php artisan test --filter TestName        # Run a specific test
php artisan test tests/Feature/ExampleTest.php  # Run a specific file

# Code Style
./vendor/bin/pint          # Format PHP code (Laravel Pint)
```

## Architecture Overview

This is a **Laravel 10** quiz/test platform with a Bulgarian-speaking admin interface.

### Routing Structure

- **Frontend** (`routes/web.php`): Public-facing site
- **Admin** (`routes/admin.php`): All admin routes are prefixed with `/adm` (e.g., `/adm/dashboard`, `/adm/quizzes`)
- **Auth** (`routes/auth.php`): Laravel Breeze authentication
- **Breadcrumbs** (`routes/breadcrumbs.php`): Breadcrumb definitions via `diglactic/laravel-breadcrumbs`

### Controller Organization

- `app/Http/Controllers/Frontend/` — Public pages (quizzes, blog, leaderboard, etc.)
- `app/Http/Controllers/Backend/` — Admin panel controllers
- `app/Http/Controllers/Auth/` — Authentication (includes `SocialiteController` for OAuth)
- `app/Http/Livewire/` — Livewire components for quiz CRUD admin forms and some frontend views

### Key Domain Models

The core quiz flow:
- **Quiz** → `belongsToMany` → **Question** (via `question_quiz` pivot)
- **Test** (a completed quiz attempt) → `belongsTo` User + Quiz; records `result`, `time_spent`, `total_points`
- **Answer** → links Test, Question, and Option; stores `correct` flag and optional `textarea_response`
- **Option** → multiple choice answers for a Question (has `correct` boolean)
- **Question** — supports two types: multiple-choice (via `Option`) and open-ended (`uses_textarea = true`)

The rank/gamification system:
- **Rank** — defined by `required_points`; users are promoted automatically after quiz submission
- After each quiz, `HeliosTestsController::checkAndUpdateRank()` sums all `total_points` from the user's Tests and assigns the highest qualifying Rank

### Special Features

- **OpenAI validation** (`HeliosTestsController::validateWithOpenAI`): Open-ended textarea questions are evaluated via OpenAI `gpt-3.5-turbo` before the quiz is submitted. Requires `OPENAI_API_KEY` in `.env`. Responses and feedback are in Bulgarian.
- **Social login**: `SocialiteController` handles OAuth via Facebook, Google, and GitHub.
- **Livewire** is used for quiz and question admin forms (`app/Http/Livewire/Quiz/`, `app/Http/Livewire/Question/`) and some front-end components.
- **DataTables** (Yajra) powers the admin list views — each has a corresponding `app/DataTables/` class.
- **Image uploads** are handled through `app/Traits/ImageUploadTrait.php`.

### Authorization

- `app/Http/Middleware/RoleMiddleware.php` — enforces `role:admin` on all `/adm` routes. Users have a `role` column (`admin` or regular user).
- Users must be authenticated (`auth` middleware) for quiz submission, results, and profile pages.
- Quizzes have both `published` (visible) and `public` (accessible to guests) boolean flags.

### Frontend Stack

Tailwind CSS + Alpine.js, compiled with Vite (`resources/css/app.css`, `resources/js/app.js`).

### Global Helpers

`app/Helper/helpers.php` (autoloaded) provides:
- `setActive(array $routes)` — returns `'active'` CSS class for nav links
- `limitText($text, $limit)` — wraps `Str::limit()`

### Testing Notes

The `phpunit.xml` has SQLite in-memory commented out — tests run against the configured DB connection, not an in-memory database. Set up a separate test database in `.env` or use the `.env.example` as a reference.
