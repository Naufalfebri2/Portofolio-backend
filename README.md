# Portfolio — Backend

[![Tests](https://github.com/Naufalfebri2/Portofolio-backend/actions/workflows/tests.yml/badge.svg)](https://github.com/Naufalfebri2/Portofolio-backend/actions/workflows/tests.yml)

Backend and admin panel for my personal portfolio website, built with **Laravel 12** and **Livewire 3**. It serves both the public-facing pages (rendered server-side with Blade) and a REST API consumed by a separate [Next.js frontend](https://github.com/Naufalfebri2/Portofolio-frontend).

**Live demo:** _coming soon_
**Frontend repo:** https://github.com/Naufalfebri2/Portofolio-frontend

---

## Tech Stack

| Layer            | Technology                                                                 |
| ---------------- | --------------------------------------------------------------------------- |
| Framework        | Laravel 12, Livewire 3                                                    |
| Database         | PostgreSQL                                                                 |
| Frontend (Blade) | Tailwind CSS v3, Alpine.js                                                 |
| Auth             | Laravel Breeze (session-based, admin-only — public registration disabled) |
| Calendar UI      | Flatpickr                                                                  |
| Mail             | SMTP (Gmail)                                                               |
| Testing          | PHPUnit, Laravel's built-in testing utilities (31 feature/unit tests)     |
| CI/CD            | GitHub Actions (test suite runs on every push to `main`)                  |

## Features

### Public site

- Home, About, Projects (listing + detail), Contact pages — server-rendered Blade with SEO/Open Graph tags
- Contact form with interview/meeting date scheduling (Flatpickr), WhatsApp redirect after submit, and email notification to the site owner
- **Honeypot spam protection** on the contact form — a hidden field invisible to real visitors silently blocks automated bot submissions without creating a database record or sending an email
- **Dark / light mode** with system-preference detection and persisted user choice (Alpine.js + Tailwind `class` strategy)
- Scroll-triggered fade-in animations
- CV download with server-side tracking (single source of truth — the Next.js frontend links here rather than serving its own copy)
- Custom branded 404 and 500 error pages (the 500 page is fully self-contained with inline styles, so it renders correctly even if the underlying failure is a database outage)

### Admin panel (`/admin`)

- Dashboard with visitor analytics (page views, top pages, average duration, date range filter)
- Project CRUD with image gallery management
- Technology CRUD (categorized: Backend / Frontend / Mobile & Tools)
- Message inbox — read/unread status, delete, detail view, including the requested interview/meeting date
- Profile editor — bio, job title/role (drives the public Hero headline), social links, photo, resume upload
- Protected by session auth + `is_admin` flag middleware; public self-registration is disabled by design

### REST API (consumed by the Next.js frontend)

- `GET /api/projects`, `GET /api/projects/{slug}`
- `GET /api/technologies`
- `GET /api/profile`
- `POST /api/messages` — rate-limited (5/min), validated, honeypot-protected, triggers email notification
- `POST /api/pageview`, `POST /api/pageview/{id}/duration` — visitor analytics, rate-limited (60/min)

Full endpoint reference, including error-case examples, is available as a Postman collection in `/docs` (or on request). CORS is configured via the `FRONTEND_URL` environment variable, so allowed origins can be updated per environment without touching code.

### Automated backup

- Daily database + file backup (`php artisan backup:run`), scheduled via Laravel Scheduler at 00:00 WIB
- Bundles a `pg_dump` database dump with all uploaded files (profile photo, project images, resume) into a single zip
- Emails the archive as an attachment and retains the 7 most recent backups locally
- Requires a server-level cron entry (`* * * * * php artisan schedule:run`) to run unattended in production

### Testing & CI

- 31 automated tests covering contact form validation, honeypot spam blocking, and the backup command (including a faked `pg_dump` process so tests don't depend on a real PostgreSQL client or database connection)
- GitHub Actions runs the full test suite — including a Vite asset build, since several Blade views depend on compiled CSS/JS — on every push to `main`

```bash
php artisan test
```

## Getting Started

### Requirements

- PHP 8.3+
- Composer
- PostgreSQL
- Node.js & npm (for asset compilation)

### Installation

```bash
git clone https://github.com/Naufalfebri2/Portofolio-backend.git
cd Portofolio-backend

composer install
cp .env.example .env
php artisan key:generate

# Configure DB_*, MAIL_*, FRONTEND_URL, and CONTACT_WHATSAPP_NUMBER in .env, then:
php artisan migrate --seed
php artisan storage:link

npm install
npm run dev   # or npm run build for production assets
```

Run the app:

```bash
php artisan serve
```

### Running the backup command manually

```bash
php artisan backup:run
```

## Project Structure Highlights

```
app/Http/Controllers/Api/      REST API controllers for the Next.js frontend
app/Http/Controllers/Admin/    Admin panel controllers
app/Console/Commands/          Custom Artisan commands (e.g. RunBackup)
resources/views/components/    Livewire single-file components
resources/views/errors/        Custom branded 404 and 500 error pages
routes/web.php                 Public routes
routes/admin.php               Admin panel routes (auth + is_admin protected)
routes/api.php                 REST API routes
tests/Feature/                 Feature tests (contact form, backup command, auth)
.github/workflows/             CI configuration
```

## Security Notes

- Public user registration is disabled — this is a single-admin application by design
- `.env` and all sensitive credentials are excluded from version control
- Database credentials for backups are passed via environment variables, never exposed on the command line
- API endpoints are rate-limited to prevent abuse
- The contact form is protected against automated spam via an invisible honeypot field
- CORS is scoped to `api/*` routes only and restricted to the configured frontend origin

## Author

**Naufal Febriansyah** — Information Systems student, Universitas Pamulang
[GitHub](https://github.com/Naufalfebri2) · [LinkedIn](https://www.linkedin.com/in/naufal-febriansyah-7b75b31b5/)
