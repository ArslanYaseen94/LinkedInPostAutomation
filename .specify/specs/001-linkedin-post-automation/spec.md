  # Spec: LinkedIn Post Automation

  ## Current Status: ACTIVE DEVELOPMENT ✅
  - ✅ Core posting functionality
  - ✅ Image & Video uploads (public/LPA/)
  - ✅ Edit drafts feature (draft-only conditions)
  - ✅ LinkedIn OAuth setup wizard
  - ✅ Auto-detecting redirect URI with copy button
  - ✅ Queue worker running
  - ✅ MySQL database configured
  - ✅ User authentication (register/login)
  - ✅ LinkedIn branding (logo + favicon)

  ## Summary
  Laravel application for creating, scheduling, and publishing LinkedIn posts with image/video support. Includes dashboard for content management and automated queue processing with conditional edit/publish buttons.

  ## Goals
  - Author posts with text, images, videos, and optional links
  - Queue and publish posts manually or on schedule
  - Provide real-time status tracking with conditional actions
  - Edit drafts before publishing (hidden for non-draft posts)
  - Support media uploads with accessibility (alt text)
  - Auto-detect app domain for redirect URI

  ## Non-Goals (for MVP)
  - Multi-account management (single LinkedIn account per user)
  - Complex approval workflows
  - Advanced analytics

  ## Users
  - Authenticated users (registration + login required)
  - Each user has own posts and LinkedIn credentials
  - Users can only view/edit their own posts

  ## Functional Requirements

  ### 1. Post Fields & Database Schema
  - text (required, max 3000 chars)
  - link_url (optional URL)
  - image_path (optional - saved to public/LPA/)
  - image_alt_text (optional - accessibility)
  - video_path (optional - saved to public/LPA/)
  - video_alt_text (optional - accessibility)
  - status: draft | queued | sent | failed
  - scheduled_for (optional datetime)
  - sent_at (nullable datetime)
  - linkedin_urn (nullable - post ID from LinkedIn)
  - last_error (nullable error text)
  - user_id (FK - who owns this post)

  ### 2. Dashboard Actions

  #### Create Draft
  - Form with text area (3000 char limit)
  - Image upload (JPG, PNG, GIF - max 5MB)
  - Image alt text field
  - Video upload (MP4, WebM, Ogg - max 100MB)
  - Video alt text field
  - Link URL field (optional)
  - Schedule datetime (optional)

  #### Edit Draft
  - **⚠️ CONDITION:** Only shows if `status === 'draft'`
  - Same form fields as create
  - Shows current media previews
  - Updates existing post
  - **Hidden for:** queued, sent, failed posts

  #### Actions per Post Status
  | Status | Edit Button | Publish Button | Visual |
  |--------|-------------|---|---------|
  | draft | ✅ Visible (blue) | ✅ Visible (green) | Editable |
  | queued | ❌ Hidden | ❌ Hidden | Processing |
  | sent | ❌ Hidden | ❌ Hidden | Completed |
  | failed | ❌ Hidden | ❌ Hidden | Error shown |

  ### 3. LinkedIn OAuth Integration

  #### Setup Page with Auto-Detection
  - **Redirect URI:** Auto-generates from `config('app.url')`
  - **Copy Button:** 📋 Click to copy, shows "✓ Copied!" feedback
  - **Instructions:** Step-by-step to LinkedIn Developer Portal
  - **Link:** https://www.linkedin.com/developers/apps

  #### Steps Shown to User
  1. Go to LinkedIn Developer Portal
  2. Select your app → Click "Auth" tab
  3. Scroll to "Authorized redirect URLs for your app"
  4. Click "Add redirect URL"
  5. Copy the URL below and paste it

  ### 4. Media Upload

  #### Image Files
  - Formats: JPG, PNG, GIF
  - Max: 5MB
  - Saved to: `public/LPA/{timestamp}_{uniqid}.{ext}`
  - With alt text for accessibility

  #### Video Files
  - Formats: MP4, WebM, Ogg
  - Max: 100MB
  - Saved to: `public/LPA/{timestamp}_{uniqid}.{ext}`
  - With alt text for accessibility

  ### 5. Publishing Flow
  1. Draft created with optional media + link
  2. Click "Publish now" → status changes to queued
  3. Job processes:
    - Validates LinkedIn is connected
    - Calls LinkedIn API
    - Appends media info to text if present
    - Updates status: `sent` + URN OR `failed` + error
  4. Dashboard shows result

  ### 6. Scheduler
  - Command: `php artisan linkedin:queue-due-posts`
  - Finds: draft posts where `scheduled_for <= now()`
  - Action: Changes status to queued, dispatches job

  ### 7. Queue Worker
  - Command: `php artisan queue:work`
  - Processes: `PublishLinkedInPost` job
  - Updates: status + URN or error message

  ### 8. Authentication & Authorization
  - Laravel Breeze with register/login
  - All routes protected with `auth` middleware
  - Ownership checks: users only edit/view own posts
  - Returns 403 Forbidden for violations

  ## Acceptance Criteria (All Complete ✅)
  - ✅ Can create draft with text
  - ✅ Can create draft with text + image
  - ✅ Can create draft with text + video  
  - ✅ Can add link URL to posts
  - ✅ Can schedule posts for future publishing
  - ✅ Can edit draft before publishing
  - ✅ Edit button visible only for draft posts
  - ✅ Edit button hidden for queued/sent/failed posts
  - ✅ Publish button visible only for draft posts
  - ✅ Publish button hidden for queued/sent/failed posts
  - ✅ "Publish now" changes status to queued
  - ✅ Queue job publishes to LinkedIn and records URN
  - ✅ Failed publishes show error in UI
  - ✅ Images stored in public/LPA/ with alt text
  - ✅ Videos stored in public/LPA/ with alt text
  - ✅ Redirect URI auto-detected from app URL
  - ✅ Copy button works for redirect URI
  - ✅ Setup instructions with LinkedIn Portal link visible
  - ✅ App branded with LinkedIn logo + favicon
  - ✅ Users must authenticate (register/login)
  - ✅ Users can only manage their own posts
  - ✅ 403 errors for ownership violations

  ## Conditions & Business Rules

  ### Edit Post Visibility & Functionality
  ```php
  IF post->status === 'draft' AND post->user_id === auth()->id()
    THEN show Edit button (blue)
    ELSE hide Edit button
  ```

  ### Publish Post Visibility & Functionality
  ```php
  IF post->status === 'draft' AND post->user_id === auth()->id()
    THEN show Publish now button (green)
    ELSE hide Publish now button / show "—"
  ```

  ### Edit Post Authorization
  ```php
  IF request->user()->id !== post->user_id
    THEN return 403 Forbidden
  IF post->status !== 'draft'
    THEN return 403 Forbidden
  ```

  ### Publish Post Authorization
  ```php
  IF request->user()->id !== post->user_id
    THEN return 403 Forbidden
  IF post->status !== 'draft'
    THEN return 403 Forbidden
  THEN set status = 'queued' and dispatch job
  ```

  ### Status Transitions
  - Only `draft` posts can be edited
  - Only `draft` posts can be published
  - Only `queued` posts can reach `sent` or `failed`
  - No post can return to `draft` after publishing

  ### Media Upload Constraints
  - Image: JPG/PNG/GIF, max 5MB
  - Video: MP4/WebM/Ogg, max 100MB
  - Stored in: `public/LPA/` directory
  - Alt text optional but recommended

  ## Database
  - **Type:** MySQL
  - **Host:** 127.0.0.1:3306
  - **Database:** linkpostautomation
  - **Auth:** root (XAMPP default)
  - **Tables:** users, posts, linkedin_accounts, linkedin_app_credentials, migrations, jobs,_cache

  ## Test Plan - Manual Testing
  1. ✅ Create draft with text only → verify saves
  2. ✅ Create draft with image + alt text → verify stored in public/LPA
  3. ✅ Create draft with video + alt text → verify stored in public/LPA
  4. ✅ Edit draft → change content → verify updates
  5. ✅ Try to edit sent post → verify 403 error
  6. ✅ Click Edit button on draft → verify form shows
  7. ✅ Click Edit button on sent post → verify button hidden
  8. ✅ Publish draft → verify status changes to queued
  9. ✅ Wait for queue job → verify status sent + LinkedIn URN recorded
  10. ✅ Test with invalid LinkedIn token → verify failed + error message shown
  11. ✅ Setup Link OAuth → verify auto-detected redirect URI
  12. ✅ Copy redirect URI → verify clipboard works
  13. ✅ Try to publish another user's post → verify 403
  14. ✅ Logout → try accessing post → verify redirected to login

