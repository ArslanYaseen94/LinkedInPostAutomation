# Plan: LinkedIn Post Automation (Laravel 12)

## Architecture
- **UI**: simple Blade pages (dashboard + create/edit).
- **Domain**: `Post` model + status transitions.
- **Integration**: `App\Services\LinkedIn\LinkedInClient` (HTTP client wrapper) behind a small interface.
- **Async**: `PublishLinkedInPost` job (queue).
- **Scheduling**: use Laravel scheduler hook from `routes/console.php`.

## Data model
- `posts` table:
  - `text` (string/text)
  - `link_url` (nullable)
  - `status` (string)
  - `scheduled_for` (nullable datetime)
  - `sent_at` (nullable datetime)
  - `last_error` (nullable text)

## Config
- `config/services.php` add `linkedin` section reading env vars.
- `.env.example` add LinkedIn keys placeholders.

## Rollout steps
- Create model + migration.
- Implement service skeleton (no real API calls yet) + job wiring.
- Add dashboard + actions (queue publish).
- Add scheduler to auto-queue scheduled drafts.

