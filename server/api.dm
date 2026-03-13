# Znaibot API Master Specification (Dynamic, Secure, Fast)

This is the implementation contract for a **fully dynamic** Laravel API for the Flutter app in this repository.

All app data must come from API + DB (no dummy content in production).

Based on:
- `instructions/znaibot.sql`
- Flutter contracts in `lib/utils/api.dart`, `lib/utils/api_model.dart`
- UI flows in `lib/pages/*`

---

## 1) Mandatory targets

1. Every page loads data from DB through API endpoints.
2. No production dependence on `DevApi` or local dummy records.
3. API is private, authenticated, encrypted, and not publicly exposed.
4. API is optimized for low latency and high concurrency.
5. Endpoint response fields must match Flutter parser keys exactly.

---

## 2) Dynamic data matrix by app page

## Login (`/`)
- Source: `users`
- API: `POST /auth/login`
- Output to app:
  - `id`
  - `name`
  - `linked_student_id` (for parent)
  - `nfc_code` (for student)
  - `token`

## Home info cards (История/Клубове/Новини)
- Source: existing CMS tables (`blogs`, `blog_categories`, `clubs`) or unified API view
- API: `GET /school/info`
- Output item fields: `title`, `description`, `category`

## Охрана page
- Lost items list/add/delete:
  - table: `lost_items` (new)
  - APIs:
    - `GET /security/lost-items`
    - `POST /security/lost-items`
    - `POST /security/lost-items/delete`
- Entry logs:
  - source: `nfc_logs` + `users`
  - API: `GET /security/entries`

## Родител page
- Meetings:
  - table: `parent_meetings` (new)
  - API: `POST /parents/meetings`
- Teacher locations:
  - source: `schedules` + `subject_teacher` + `subjects` + `users`
  - API: `GET /teachers/map`
- Student schedule:
  - source: `schedules` + class relations
  - API: `POST /schedule`

## Ученик page
- Teacher locations: `GET /teachers/map`
- NFC code from user profile (`users.nfc_id`): returned by login
- AI chat:
  - API proxy endpoint: `POST /ai/chat`
  - backend proxies to internal LLM endpoint (never expose direct LLM service publicly)

## Учител page
- Student list:
  - source: `users` role `student` + class relation
  - API: `GET /students`
- Meetings list:
  - source: `parent_meetings`
  - API: `POST /parents/meetings` (teacher scope variant allowed)
- Create meeting:
  - API: `POST /teacher/meetings/create`

## Стол page (new)
- Allergen dictionary:
  - source: `allergens`
  - API: `GET /food/allergens`
- Student allergen profiles:
  - source: `users` role `student` + `user_allergen`
  - API: `GET /food/students/allergens`
- Sync student allergens:
  - API: `POST /food/students/allergens/sync`

---

## 3) DB schema: existing + required additions

## 3.1 Existing (from `znaibot.sql`)
Required existing tables already present:
- `users`
- `allergens`
- `user_allergen`
- `nfc_logs`
- `nfc_readers`
- `klasses`
- `klas_users`
- `schedules`
- `subjects`
- `subject_teacher`
- `personal_access_tokens`

## 3.2 New required tables

### `lost_items`
```sql
CREATE TABLE `lost_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `image_path` VARCHAR(1024) NULL,
  `created_by_user_id` BIGINT UNSIGNED NULL,
  `deleted_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `lost_items_created_by_idx` (`created_by_user_id`),
  CONSTRAINT `lost_items_created_by_fk` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### `parent_meetings`
```sql
CREATE TABLE `parent_meetings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` BIGINT UNSIGNED NULL,
  `parent_id` BIGINT UNSIGNED NULL,
  `teacher_id` BIGINT UNSIGNED NOT NULL,
  `room` VARCHAR(64) NOT NULL,
  `floor` TINYINT UNSIGNED NOT NULL,
  `meeting_time` DATETIME NOT NULL,
  `note` TEXT NULL,
  `status` ENUM('scheduled','completed','cancelled') NOT NULL DEFAULT 'scheduled',
  `created_by_user_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `pm_student_idx` (`student_id`),
  KEY `pm_parent_idx` (`parent_id`),
  KEY `pm_teacher_idx` (`teacher_id`),
  KEY `pm_time_idx` (`meeting_time`),
  CONSTRAINT `pm_student_fk` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pm_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pm_teacher_fk` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pm_created_by_fk` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### `school_info_blocks` (unified dynamic source for login-home cards)
```sql
CREATE TABLE `school_info_blocks` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `category` ENUM('История','Клубове','Новини') NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `sib_category_active_idx` (`category`, `is_active`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 4) Endpoint contract (full)

All protected endpoints require:
- `Authorization: Bearer <token>`
- `X-API-KEY: <key>`
- `X-Timestamp: <unix-seconds>`
- `X-Nonce: <uuid-v4>`
- `X-Signature: <HMAC_SHA256>`

## 4.1 Auth
### `POST /auth/login`
Request:
```json
{
  "username": "string",
  "password": "string",
  "role": "ohrana|roditel|uchenik|uchitel|stol"
}
```
Response:
```json
{
  "id": 428,
  "name": "Анна Стоянова",
  "linked_student_id": null,
  "nfc_code": "NFC-TEST-004",
  "token": "plain_text_sanctum_token"
}
```
Role mapping:
- `ohrana` -> `security`
- `roditel` -> `parent`
- `uchenik` -> `student`
- `uchitel` -> `teacher`
- `stol` -> `vendor`

## 4.2 School info
### `GET /school/info`
```json
{
  "items": [
    {"title":"...","description":"...","category":"Новини"}
  ]
}
```

## 4.3 Security
### `GET /security/lost-items`
```json
{
  "items": [
    {"id":1,"title":"...","description":"...","image_path":"...","created_at":"2026-02-26T10:00:00Z"}
  ]
}
```

### `POST /security/lost-items`
- multipart for image support
- fields: `title`, `description`, optional `image`

### `POST /security/lost-items/delete`
```json
{"id": 1}
```

### `GET /security/entries`
```json
{
  "items": [
    {"name":"Иван Петров","role":"student","timestamp":"2026-02-26T11:20:00Z"}
  ]
}
```

## 4.4 Teacher map & schedules
### `GET /teachers/map`
```json
{
  "items": [
    {
      "teacher_name":"...",
      "subject":"...",
      "room":"203",
      "floor":2,
      "map_x":0.42,
      "map_y":0.38
    }
  ]
}
```

### `POST /schedule`
Request:
```json
{"student_id":"433"}
```
Response:
```json
{
  "items": [
    {"day":"Понеделник","time":"08:00 - 08:40","subject":"Математика","teacher":"...","room":"203"}
  ]
}
```

## 4.5 Students
### `GET /students`
```json
{
  "items": [
    {"id":"433","name":"Георги Стоянов","class_name":"7А"}
  ]
}
```

## 4.6 Meetings
### `POST /parents/meetings`
Request:
```json
{"student_id":"433"}
```
Response:
```json
{
  "items": [
    {
      "id":"1",
      "teacher":"Мария Тодорова",
      "room":"203",
      "floor":2,
      "time":"2026-03-01T16:00:00Z",
      "note":"Среща за прогрес",
      "map_x":0.42,
      "map_y":0.38
    }
  ]
}
```

### `POST /teacher/meetings/create`
Request:
```json
{
  "teacher": "Мария Тодорова",
  "room": "203",
  "floor": 2,
  "time": "2026-03-01T16:00:00Z",
  "note": "Среща за прогрес"
}
```

## 4.7 Food personnel
### `GET /food/allergens`
```json
{
  "items": [
    {"id":1,"name":"Глутен","description":"...","color":"#f59e0b"}
  ]
}
```

### `GET /food/students/allergens`
```json
{
  "items": [
    {
      "student_id":"428",
      "student_name":"Анна Стоянова",
      "class_name":"7А",
      "allergens":[{"id":12,"name":"Сулфити","description":"...","color":"#7c3aed"}],
      "notes_by_allergen_id":{"12":"free text"}
    }
  ]
}
```

### `POST /food/students/allergens/sync`
Request:
```json
{
  "student_id":"428",
  "allergen_ids":["1","10","12"]
}
```
Response: `204 No Content`

## 4.8 AI
### `POST /ai/chat`
Request:
```json
{"prompt":"string"}
```
Response:
```json
{"response":"string"}
```

---

## 5) Security architecture (maximum)

## 5.1 Zero-trust network exposure
- Do NOT expose API directly on public internet.
- Deploy behind private ingress:
  - VPN-only access (WireGuard/Tailscale), or
  - private VPC + API gateway + IP allowlist, or
  - Cloudflare Access + mTLS.

## 5.2 Layered request security
1. TLS 1.2+ mandatory.
2. API key middleware (`X-API-KEY`).
3. Bearer token middleware (Sanctum).
4. Signed request middleware (`timestamp + nonce + HMAC`).
5. Role/permission middleware.

## 5.3 Replay protection
- Redis set for nonces, TTL 120s.
- Reject reused nonce or stale timestamp.

## 5.4 Secrets
- API keys hashed at rest.
- Secrets in vault (not `.env` in repo).
- Automatic key rotation (dual-key overlap window).

## 5.5 App integrity controls (recommended)
- Device binding claim in token (optional).
- JWT/Sanctum token TTL + refresh strategy.
- Suspicious behavior lockouts.

## 5.6 Hardening
- `APP_DEBUG=false`
- strict CORS
- request size limits
- WAF + bot filtering
- immutable audit logs for auth, lost-item delete, allergen sync, meeting create

---

## 6) Performance architecture (maximum speed)

## 6.1 DB indexing
Required indexes:
- `users(role, status)`
- `users(klas_id)`
- `users(nfc_id)`
- `nfc_logs(created_at, user_id)`
- `schedules(klas_id, day)`
- `subject_teacher(teacher_id)`
- `user_allergen(user_id, allergen_id)` unique already present
- `parent_meetings(meeting_time, teacher_id, student_id)`
- `lost_items(created_at)`

## 6.2 Caching
- Cache dictionaries (`allergens`, school info) for 5–30 min.
- Cache teacher map by schedule version.
- Use cache tags for precise invalidation.

## 6.3 Query optimization
- Use eager loading (`with`) to avoid N+1.
- Paginate where lists can grow.
- Use read replicas for heavy read traffic (optional).

## 6.4 API payload optimization
- Return only fields used by app.
- Enable gzip/br compression.
- Use HTTP/2 or HTTP/3.

## 6.5 Async jobs
- Image processing for lost items in queue.
- Non-critical AI logging async.

## 6.6 Observability
- APM tracing + slow query log.
- p95/p99 latency per endpoint.
- Alert on 401/403/429/5xx spikes.

---

## 7) Laravel implementation structure

- `app/Models`: `User`, `Allergen`, `UserAllergen`, `LostItem`, `ParentMeeting`, `SchoolInfoBlock`, `NfcLog`
- `app/Http/Controllers/Api`:
  - `AuthController`
  - `SchoolInfoController`
  - `SecurityController`
  - `TeacherController`
  - `ParentController`
  - `FoodController`
  - `AiController`
- `app/Http/Middleware`:
  - `RequireApiKey`
  - `VerifyRequestSignature`
- `app/Http/Requests`: one request class per write endpoint
- `app/Policies`: role-locked access rules
- `routes/api.php`: versioned API group (`/api/v1/...`)

---

## 8) Validation + authorization rules

## Validation examples
- login role in: `ohrana,roditel,uchenik,uchitel,stol`
- meeting floor in: `1..4`
- allergen_ids array with `exists:allergens,id`
- student_id must exist and be role `student`

## Authorization matrix
- `security` + `admin` -> security endpoints
- `parent` + `admin` -> parent endpoints (scoped)
- `teacher` + `admin` -> teacher endpoints
- `vendor` (`stol`) + `admin` -> food endpoints
- `student` + `admin` -> AI/chat and student-scoped endpoints

---

## 9) Production operations checklist

1. Migrate DB including new tables.
2. Seed minimum required records (school info blocks, allergens).
3. Configure API key(s) and HMAC secrets.
4. Configure Redis for nonce replay protection + cache.
5. Configure queue worker and scheduler.
6. Enable TLS and private ingress policy.
7. Run smoke tests from Flutter app with `useDevMode=false`.

---

## 10) Acceptance test checklist (must pass)

1. Every page loads from API with non-empty dynamic payload in production.
2. App works with `DevApi` disabled by default.
3. Invalid API key/signature/nonce/timestamp returns 401.
4. Unauthorized role access returns 403.
5. `Стол` page reads/writes allergens correctly to DB.
6. Lost item create/delete persists in DB.
7. Parent meetings create/list persist in DB.
8. p95 read endpoints < 200ms under normal school load.
9. No endpoint leaks sensitive internal details in error payloads.

---

## 11) One-go implementation order for agents

1. Create Laravel project and connect existing `znaibot` DB.
2. Add missing tables (`lost_items`, `parent_meetings`, `school_info_blocks`).
3. Implement middleware chain: API key -> signature -> auth -> role policy.
4. Implement all endpoints in section 4 with exact response keys.
5. Add indexes + cache + compression + rate limits.
6. Add tests for auth, permissions, endpoint schemas, and allergen sync.
7. Deploy behind private ingress with TLS.
8. Validate against Flutter app flows for all 5 roles.

This specification is strict enough for another agent/team to implement in one pass and have the app fully dynamic, secure, and fast.
