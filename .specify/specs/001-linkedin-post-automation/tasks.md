# Tasks: LinkedIn Post Automation (MVP)

## Foundation
- [ ] Add LinkedIn env placeholders and `config/services.php` config block
- [ ] Add `Post` model + migration
- [ ] Add `LinkedInClient` service skeleton

## Async publishing
- [ ] Add `PublishLinkedInPost` job that calls service and updates status
- [ ] Add artisan command `linkedin:queue-due-posts`
- [ ] Schedule the command from `routes/console.php` (every minute)

## UI
- [ ] Dashboard route + Blade views (list + create draft)
- [ ] Actions: “Publish now” and “Schedule”

## Verification
- [ ] Manual test plan run-through (happy path + failure path)

