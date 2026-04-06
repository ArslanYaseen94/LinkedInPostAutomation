# LINKPOSTAUTOMATION Constitution

## Core Principles

### 1) Spec-first, production-minded
We write/update specs before coding. Every implementation must map to an explicit requirement and include a clear acceptance criteria / test plan.

### 2) Safety around credentials and automation
No secrets in git. Tokens/cookies stay in `.env` (never committed). Any automation must have rate limits, retries, and an explicit “dry run” path.

### 3) Queue-first for all external calls
All LinkedIn API/browser automation runs via queued jobs. HTTP requests must be idempotent where possible and resilient (timeouts, exponential backoff, and structured error reporting).

### 4) Observability is not optional
Every run emits structured logs with a correlation id. Store execution history (what was posted, when, status, error) so failures can be re-tried safely.

### 5) No 500s for expected user flows
OAuth errors (invalid scope, user canceled, redirect mismatch, invalid state) must be handled gracefully with user-facing messages and must not crash with a 500.

### 5) Keep it simple, but extensible
Start with one integration path (API-first). If browser automation is needed, isolate it behind an interface so we can swap implementations without touching business logic.

## Constraints & Security

- Use Laravel 12 + queues + scheduler as the backbone.
- Never scrape or automate in ways that violate platform terms for the user’s account; add guardrails (per-minute posting limit, account-level disable switch).
- Sensitive config keys:
  - `LINKEDIN_CLIENT_ID`, `LINKEDIN_CLIENT_SECRET`, `LINKEDIN_REDIRECT_URI`
  - Optional: `LINKEDIN_ACCESS_TOKEN` / `LINKEDIN_REFRESH_TOKEN` (or stored encrypted in DB later)

## Development Workflow

- Prefer small, composable services (`App\\Services\\LinkedIn\\...`).
- No long-running synchronous controllers for posting; controllers only enqueue work and return immediately.
- Add a minimal “admin dashboard” page for manual triggering, status viewing, and last-run logs.
- Add/maintain feature tests for critical auth + OAuth flows.

## Governance

This constitution is the top-level guidance for this repo. If implementation conflicts with it, update the spec + constitution first, then implement.

**Version**: 2.0.0 | **Ratified**: 2026-03-31 | **Last Amended**: 2026-04-01

### Recent Amendments (v2.0)
- ✅ Added conditional UI rules (buttons only for drafts)
- ✅ Added media upload & storage constraints  
- ✅ Added auto-detecting redirect URI with copy button
- ✅ Added encryption requirement for LinkedIn secrets
- ✅ Added status machine & state transitions
- ✅ Added comprehensive error handling rules
- ✅ Added queue configuration & retry logic
- ✅ Added status-gated edit/publish functionality
- ✅ Added 403 authorization checks for ownership
