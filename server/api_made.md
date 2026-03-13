# Znaibot Mobile API - Implementation Documentation

This document describes what was implemented in the Laravel project to support the mobile app, how to run it, and how the API works end-to-end.

---

## 1) What was implemented

Implemented a versioned mobile API group under:

- `/api/v1/*`

with the following architecture:

1. API Key middleware (`X-API-KEY`)
2. Request signature middleware (`X-Timestamp`, `X-Nonce`, `X-Signature`)
3. Sanctum auth (`Authorization: Bearer <token>`) for protected routes
4. API role authorization middleware (`security`, `parent`, `student`, `teacher`, `vendor`, `admin`)

All endpoints requested in `api.dm` were added.

Authentication flow is now **universal login**:

- login checks only credentials (`username` + `password`)
- API returns the user's real role from DB in response (`role`)
- client should route UI based on returned `role`

---

## 2) Files added and updated

## Added

### Migrations
- `database/migrations/2026_02_26_120000_create_api_dynamic_tables.php`
- `database/migrations/2026_02_26_120100_add_location_fields_to_subject_teacher_table.php`
- `database/migrations/2026_02_26_120200_add_api_performance_indexes.php`

### Models
- `app/Models/LostItem.php`
- `app/Models/ParentMeeting.php`
- `app/Models/SchoolInfoBlock.php`

### Middleware
- `app/Http/Middleware/RequireApiKey.php`
- `app/Http/Middleware/VerifyRequestSignature.php`
- `app/Http/Middleware/EnsureApiRole.php`
- `app/Http/Middleware/AuthorizePublicContentToken.php`
- `app/Http/Middleware/LogApiFailures.php`

### API Controllers
- `app/Http/Controllers/Api/AuthController.php`
- `app/Http/Controllers/Api/SchoolInfoController.php`
- `app/Http/Controllers/Api/SecurityController.php`
- `app/Http/Controllers/Api/TeacherController.php`
- `app/Http/Controllers/Api/ParentController.php`
- `app/Http/Controllers/Api/FoodController.php`
- `app/Http/Controllers/Api/AiController.php`

### API Request Validators
- `app/Http/Requests/Api/LoginRequest.php`
- `app/Http/Requests/Api/StoreLostItemRequest.php`
- `app/Http/Requests/Api/DeleteLostItemRequest.php`
- `app/Http/Requests/Api/ScheduleRequest.php`
- `app/Http/Requests/Api/ParentMeetingsRequest.php`
- `app/Http/Requests/Api/TeacherMeetingCreateRequest.php`
- `app/Http/Requests/Api/FoodAllergenSyncRequest.php`
- `app/Http/Requests/Api/AiChatRequest.php`

### Config
- `config/api_security.php`

## Updated
- `routes/api.php` (all `/api/v1` route registration)
- `app/Http/Kernel.php` (middleware aliases)
- `app/Models/SubjectTeacher.php` (location fields + schedule relation)
- `app/Exceptions/Handler.php` (force JSON API errors with status codes)

### Compatibility model
- `app/Models/Vendor.php`
	- Added as compatibility layer for existing code paths expecting `App\Models\Vendor`
	- Uses `users` table via `User` inheritance + role scope `vendor`

---

## 3) Database changes

## New tables

1. `lost_items`
	 - Lost and found records with soft deletes and optional image path
2. `parent_meetings`
	 - Parent-teacher/student meetings with room/floor/time/status
3. `school_info_blocks`
	 - Dynamic source for home cards (История, Клубове, Новини)

## Existing tables enhanced

1. `subject_teacher`
	 - Added fields: `room`, `floor`, `map_x`, `map_y`
2. Performance indexes
	 - `users(role, status)`
	 - `users(klas_id)`
	 - `users(nfc_id)`
	 - `nfc_logs(created_at, user_id)`
	 - `schedules(klas_id, day_of_week)`
	 - plus index on `subject_teacher.teacher_id`

---

## 4) API security model

Every API v1 request requires:

1. `X-API-KEY`
2. `X-Timestamp` (unix seconds)
3. `X-Nonce` (uuid-v4)
4. `X-Signature` (HMAC SHA256)

Protected routes also require:

5. `Authorization: Bearer <sanctum_token>`

Special case for login screen cards:

- `GET /api/v1/school/info` accepts either:
	- `Authorization: Bearer <API_PUBLIC_CONTENT_TOKEN>` (standalone content token), or
	- `Authorization: Bearer <sanctum_token>` (regular user token)
- API key + signature are still required for this endpoint.

## Signature payload format

Server validates this exact payload string:

`METHOD|/api/v1/path|timestamp|nonce|sha256(raw_body)`

Then computes:

`hash_hmac('sha256', payload, API_HMAC_SECRET)`

Nonce replay protection:

- Nonce stored in cache with TTL (default 120 sec)
- Reused nonce is rejected with 401
- Old/new timestamp outside allowed skew is rejected with 401

---

## 5) Role mapping from mobile login

For app UI routing, use the role returned by login:

- `admin`
- `security`
- `parent`
- `student`
- `teacher`
- `vendor`

Legacy role alias mapping (kept for compatibility with old mobile naming):

- `ohrana` -> `security`
- `roditel` -> `parent`
- `uchenik` -> `student`
- `uchitel` -> `teacher`
- `stol` -> `vendor`

Admin can access all role-scoped API groups.

---

## 6) Endpoint list and behavior

All paths below are under `/api/v1`.

## Auth

- `POST /auth/login`
	- Validates `username` + `password` (role is not required)
	- The `username` value can be either actual username or email (backend checks both)
	- Returns:
		- `id`
		- `name`
		- `role`
		- `linked_student_id`
		- `nfc_code`
		- `token` (Sanctum plain token)

- `GET /auth/me`
	- Requires Bearer token
	- Returns current authenticated user profile (`id`, `name`, `role`, `linked_student_id`, `nfc_code`)

- `POST /auth/logout`
	- Requires Bearer token
	- Revokes current access token
	- Returns HTTP 204

## School info

- `GET /school/info`
	- Source: `school_info_blocks`
	- Cached for 10 minutes
	- Returns `items[]` with `title`, `description`, `category`

- `GET /clubs`
	- Source: `clubs` table (`status = 1`)
	- Cached for 10 minutes
	- Public endpoint with content-token middleware
	- Returns `items[]` with `id`, `title`, `description`, `category`

## Security

- `GET /security/lost-items`
- `POST /security/lost-items` (multipart image supported)
- `POST /security/lost-items/delete` (soft delete)
- `GET /security/entries` (from `nfc_logs` + `users`)
- `POST /nfc/logs` (insert NFC вход/изход log от външен клиент)

### `POST /nfc/logs`
Insert endpoint за NFC клиент (врата вход/изход).

Request JSON:
```json
{
	"nfc_id": "NFC-TEST-004",
	"direction": "in",
	"reader_title": "Главен вход",
	"read_at": "2026-03-04T08:10:00+02:00"
}
```

Allowed values for `direction`:
- `in`, `entry`, `door_in`, `вход`
- `out`, `exit`, `door_out`, `изход`

Optional fields:
- `nfc_reader_id` (ако клиентът вече знае reader id)
- `reader_title` (ако няма id, backend създава/ползва reader по title+type)
- `read_at` (ако липсва, се ползва текущо време от сървъра)

Response:
```json
{
	"status": "ok",
	"id": 123,
	"direction": "in",
	"timestamp": "2026-03-04T06:10:00.000000Z",
	"reader": {
		"id": 2,
		"title": "Главен вход",
		"type": "door_in"
	},
	"user": {
		"id": 433,
		"name": "Георги Стоянов",
		"role": "student"
	},
	"unknown": false
}
```

Client example (cURL):
```bash
curl -X POST "https://your-host/api/v1/nfc/logs" \
	-H "Content-Type: application/json" \
	-H "X-API-KEY: <api-key>" \
	-H "X-Timestamp: <unix-seconds>" \
	-H "X-Nonce: <uuid-v4>" \
	-H "X-Signature: <hmac-sha256>" \
	-d '{"nfc_id":"NFC-TEST-004","direction":"in","reader_title":"Главен вход"}'
```

## Teachers / Schedules / Students

- `GET /teachers/map`
	- Uses schedule + subject_teacher + subject + teacher
	- Returns `teacher_name`, `subject`, `room`, `floor`, `map_x`, `map_y`

- `POST /schedule`
	- `student_id` is optional
	- Behavior:
		- for student user: uses authenticated student
		- for parent user: uses linked student by default
		- for parent user without linked student: returns empty `items[]` (no 422)
		- for teacher user: returns teacher schedule view without requiring `student_id`
		- when needed and missing for other roles: returns JSON 422
	- Returns day/time/subject/teacher/room list

- `GET /students`
	- Teacher-scoped student list (or full for admin)
	- Returns `id`, `name`, `class_name`

## Meetings

- `POST /parents/meetings`
	- Parent/teacher/admin scoped list
	- `student_id` is optional and safely cast to int when present
	- For parent role, linked student is used by default when `student_id` is missing
	- Validation keeps `nullable|integer|exists:users,id` with role=student scope
	- Returns `id`, `teacher`, `room`, `floor`, `time`, `note`, `map_x`, `map_y`

- `POST /teacher/meetings/create`
	- Creates new `parent_meetings` row

## Food / Allergens

- `GET /food/allergens`
	- Cached dictionary from `allergens`

- `GET /food/students/allergens`
	- Returns students + class + allergen list + notes map

- `POST /food/students/allergens/sync`
	- Syncs pivot table `user_allergen`
	- Returns HTTP 204

## AI

- `POST /ai/chat`
	- Proxies prompt to internal LLM endpoint
	- Returns `{ "response": "..." }`

---

## 7) Commands to run (Windows PowerShell)

Run these from project root (`e:\var\www\karavelov\znaibot`):

## 7.1 Install dependencies

```powershell
composer install
npm install
```

## 7.2 Configure environment

```powershell
Copy-Item .env.example .env -Force
php artisan key:generate
```

Then edit `.env` and set DB + API security values:

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=znaibot
DB_USERNAME=YOUR_DB_USER
DB_PASSWORD=YOUR_DB_PASS

CACHE_DRIVER=redis
QUEUE_CONNECTION=database

API_KEYS_PLAIN=your_mobile_api_key
API_KEYS_HASHED=
API_HMAC_SECRET=your_super_secret_hmac_key
API_NONCE_TTL_SECONDS=120
API_ALLOWED_TIME_SKEW_SECONDS=120
API_PUBLIC_CONTENT_TOKEN=standalone-login-screen-token

MQTT_NFC_ENABLED=true
MQTT_NFC_HOST=127.0.0.1
MQTT_NFC_PORT=1883
MQTT_NFC_USERNAME=
MQTT_NFC_PASSWORD=
MQTT_NFC_CLIENT_ID=znaibot-nfc-listener
MQTT_NFC_TOPIC=znaibot/nfc/logs
MQTT_NFC_QOS=1
MQTT_NFC_TLS=false
MQTT_NFC_KEEP_ALIVE=60

INTERNAL_LLM_ENDPOINT=http://internal-llm.local/chat
INTERNAL_LLM_TOKEN=optional_token
```

If you prefer hashed API keys at rest, compute SHA256 and put hashes in `API_KEYS_HASHED` (comma-separated), then do not use plain values in `API_KEYS_PLAIN`.

## 7.3 Run migrations

```powershell
php artisan migrate
```

## 7.4 Seed minimum required data

Create at least:
- users for each role (`security`, `parent`, `student`, `teacher`, `vendor`)
- allergens
- school_info_blocks

Then run existing seeders if available:

```powershell
php artisan db:seed
```

## 7.5 Start app

```powershell
php artisan serve
npm run dev
```

## 7.6 Start MQTT NFC listener

```powershell
php artisan nfc:mqtt-listen
```

Single-message test mode:

```powershell
php artisan nfc:mqtt-listen --once
```

Expected payload on topic `MQTT_NFC_TOPIC`:

```json
{
	"nfc_id": "NFC-TEST-004",
	"direction": "in",
	"reader_title": "Главен вход",
	"read_at": "2026-03-04T08:10:00+02:00"
}
```

API base URL becomes:

- `http://127.0.0.1:8000/api/v1`

Health endpoint:

- `GET /api/v1/`
	- With correct API headers: returns `{ "service": "znaibot-api", "version": "v1", "status": "ok" }`

---

## 8) Example request flow

## Step A: Login

1. Build headers:
	 - `X-API-KEY`
	 - `X-Timestamp`
	 - `X-Nonce`
	 - `X-Signature`
2. Call `POST /api/v1/auth/login`
3. Save returned token

## Step B: Access protected endpoint

For example `GET /api/v1/school/info`, send the same security headers plus:

- `Authorization: Bearer <token>`

---

## 9) HTTP status conventions

- `200` success
- `201` created
- `204` no content (sync/delete style success)
- `401` invalid API key/signature/timestamp/nonce/token
- `403` role not allowed
- `404` route not found
- `405` wrong HTTP method
- `422` validation failed
- `502` AI upstream failed
- `503` AI endpoint not configured

## API error response contract (standardized)

All API errors under `/api/*` now return JSON (no HTML framework pages):

```json
{
	"message": "Human readable error",
	"status_code": 401,
	"error_code": "SOME_CODE"
}
```

Validation errors:

```json
{
	"message": "Validation failed",
	"status_code": 422,
	"errors": {
		"field": ["..."]
	}
}
```

---

## 10) Notes and operational recommendations

1. Production
	 - Set `APP_DEBUG=false`
	 - Use HTTPS only
	 - Put API behind private ingress/VPN/WAF

2. Replay protection
	 - Use Redis cache in production for reliable nonce checks across instances

3. Mobile implementation
	 - Signature must be generated from raw request body exactly
	 - Nonce must be unique per request

4. Performance
	 - Dictionary endpoints are cached (`school info`, `allergens`)
	 - Added DB indexes for common query paths

5. API diagnostics
	 - Failed `/api/*` requests are logged via `LogApiFailures` middleware
	 - Sensitive headers/tokens/signatures are redacted in logs
	 - Helps investigate 4xx/5xx without leaking secrets

---

## 11) Quick smoke test checklist

1. Login with username/email + password (without role input)
2. Call one endpoint per role group
3. Verify invalid signature returns 401
4. Verify reused nonce returns 401
5. Verify forbidden role returns 403
6. Verify allergen sync updates `user_allergen`
7. Verify lost-item create/delete updates `lost_items`
8. Verify unknown API route returns JSON 404 (not HTML)
9. Verify wrong method returns JSON 405 (not HTML)

---

## 12) Important environment note

In the current shell session where implementation was done, `php` command was not available in PATH. If this happens on your machine, install PHP or add PHP executable directory to PATH, then run commands above.

