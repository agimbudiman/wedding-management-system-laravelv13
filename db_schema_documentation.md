# Dokumentasi Skema Database `wedding_system`

Berikut adalah daftar semua tabel beserta struktur data, tipe data, ukuran, keterangan (Primary Key, Nullable, Default, Auto Increment), serta Foreign Key jika ada.

## Tabel: `cache`

| No | Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|---|
| 1 | `key` | `varchar(255)` | Tidak | - | **Primary Key** |
| 2 | `value` | `mediumtext` | Tidak | - | - |
| 3 | `expiration` | `int` | Tidak | - | - |

---

## Tabel: `cache_locks`

| No | Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|---|
| 1 | `key` | `varchar(255)` | Tidak | - | **Primary Key** |
| 2 | `owner` | `varchar(255)` | Tidak | - | - |
| 3 | `expiration` | `int` | Tidak | - | - |

---

## Tabel: `event_categories`

| No | Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|---|
| 1 | `id` | `bigint unsigned` | Tidak | - | **Primary Key**, Auto Increment |
| 2 | `name` | `varchar(255)` | Tidak | - | - |
| 3 | `slug` | `varchar(255)` | Tidak | - | - |
| 4 | `image` | `varchar(255)` | Ya | - | - |
| 5 | `description` | `text` | Ya | - | - |
| 6 | `created_at` | `timestamp` | Ya | - | - |
| 7 | `updated_at` | `timestamp` | Ya | - | - |

---

## Tabel: `event_crews`

| No | Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|---|
| 1 | `id` | `bigint unsigned` | Tidak | - | **Primary Key**, Auto Increment |
| 2 | `event_id` | `bigint unsigned` | Tidak | - | Foreign Key: Menghubungkan ke `events.id` (On Delete: cascade) |
| 3 | `management_user_id` | `bigint unsigned` | Tidak | - | Foreign Key: Menghubungkan ke `management_users.id` (On Delete: cascade) |
| 4 | `is_leader` | `tinyint(1)` | Tidak | `0` | - |
| 5 | `created_at` | `timestamp` | Ya | - | - |
| 6 | `updated_at` | `timestamp` | Ya | - | - |

**Foreign Keys:**
- `event_id` -> `events(id)` (On Delete: `cascade`)
- `management_user_id` -> `management_users(id)` (On Delete: `cascade`)

---

## Tabel: `event_guest_books`

| No | Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|---|
| 1 | `id` | `bigint unsigned` | Tidak | - | **Primary Key**, Auto Increment |
| 2 | `event_id` | `bigint unsigned` | Tidak | - | Foreign Key: Menghubungkan ke `events.id` (On Delete: cascade) |
| 3 | `name` | `varchar(50)` | Tidak | - | - |
| 4 | `address` | `varchar(255)` | Tidak | - | - |
| 5 | `created_at` | `timestamp` | Ya | - | - |
| 6 | `updated_at` | `timestamp` | Ya | - | - |

**Foreign Keys:**
- `event_id` -> `events(id)` (On Delete: `cascade`)

---

## Tabel: `event_notes`

| No | Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|---|
| 1 | `id` | `bigint unsigned` | Tidak | - | **Primary Key**, Auto Increment |
| 2 | `event_id` | `bigint unsigned` | Tidak | - | Foreign Key: Menghubungkan ke `events.id` (On Delete: cascade) |
| 3 | `content` | `longtext` | Ya | - | - |
| 4 | `created_at` | `timestamp` | Ya | - | - |
| 5 | `updated_at` | `timestamp` | Ya | - | - |

**Foreign Keys:**
- `event_id` -> `events(id)` (On Delete: `cascade`)

---

## Tabel: `event_rundowns`

| No | Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|---|
| 1 | `id` | `bigint unsigned` | Tidak | - | **Primary Key**, Auto Increment |
| 2 | `event_id` | `bigint unsigned` | Tidak | - | Foreign Key: Menghubungkan ke `events.id` (On Delete: cascade) |
| 3 | `day` | `int` | Tidak | `1` | - |
| 4 | `time_start` | `time` | Tidak | - | - |
| 5 | `time_end` | `time` | Ya | - | - |
| 6 | `activity` | `varchar(255)` | Tidak | - | - |
| 7 | `created_at` | `timestamp` | Ya | - | - |
| 8 | `updated_at` | `timestamp` | Ya | - | - |

**Foreign Keys:**
- `event_id` -> `events(id)` (On Delete: `cascade`)

---

## Tabel: `event_testimonials`

| No | Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|---|
| 1 | `id` | `bigint unsigned` | Tidak | - | **Primary Key**, Auto Increment |
| 2 | `event_id` | `bigint unsigned` | Tidak | - | Foreign Key: Menghubungkan ke `events.id` (On Delete: cascade) |
| 3 | `rating` | `tinyint unsigned` | Tidak | - | - |
| 4 | `testimony` | `text` | Tidak | - | - |
| 5 | `created_at` | `timestamp` | Ya | - | - |
| 6 | `updated_at` | `timestamp` | Ya | - | - |

**Foreign Keys:**
- `event_id` -> `events(id)` (On Delete: `cascade`)

---

## Tabel: `event_todos`

| No | Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|---|
| 1 | `id` | `bigint unsigned` | Tidak | - | **Primary Key**, Auto Increment |
| 2 | `event_id` | `bigint unsigned` | Tidak | - | Foreign Key: Menghubungkan ke `events.id` (On Delete: cascade) |
| 3 | `title` | `varchar(255)` | Tidak | - | - |
| 4 | `category` | `varchar(255)` | Tidak | - | - |
| 5 | `due_date` | `date` | Ya | - | - |
| 6 | `management_user_id` | `bigint unsigned` | Ya | - | Foreign Key: Menghubungkan ke `management_users.id` (On Delete: set null) |
| 7 | `is_completed` | `tinyint(1)` | Tidak | `0` | - |
| 8 | `created_at` | `timestamp` | Ya | - | - |
| 9 | `updated_at` | `timestamp` | Ya | - | - |

**Foreign Keys:**
- `event_id` -> `events(id)` (On Delete: `cascade`)
- `management_user_id` -> `management_users(id)` (On Delete: `set null`)

---

## Tabel: `event_vendors`

| No | Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|---|
| 1 | `id` | `bigint unsigned` | Tidak | - | **Primary Key**, Auto Increment |
| 2 | `event_id` | `bigint unsigned` | Tidak | - | Foreign Key: Menghubungkan ke `events.id` (On Delete: cascade) |
| 3 | `vendor_id` | `bigint unsigned` | Tidak | - | Foreign Key: Menghubungkan ke `vendors.id` (On Delete: cascade) |
| 4 | `created_at` | `timestamp` | Ya | - | - |
| 5 | `updated_at` | `timestamp` | Ya | - | - |

**Foreign Keys:**
- `event_id` -> `events(id)` (On Delete: `cascade`)
- `vendor_id` -> `vendors(id)` (On Delete: `cascade`)

---

## Tabel: `events`

| No | Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|---|
| 1 | `id` | `bigint unsigned` | Tidak | - | **Primary Key**, Auto Increment |
| 2 | `category_id` | `bigint unsigned` | Tidak | - | Foreign Key: Menghubungkan ke `event_categories.id` (On Delete: cascade) |
| 3 | `name` | `varchar(50)` | Tidak | - | - |
| 4 | `slug` | `varchar(255)` | Ya | - | - |
| 5 | `groom_name` | `varchar(50)` | Ya | - | - |
| 6 | `bride_name` | `varchar(50)` | Ya | - | - |
| 7 | `client_name` | `varchar(50)` | Tidak | - | - |
| 8 | `client_address` | `text` | Ya | - | - |
| 9 | `date` | `date` | Tidak | - | - |
| 10 | `venue` | `varchar(255)` | Tidak | - | - |
| 11 | `google_maps_link` | `text` | Ya | - | - |
| 12 | `personalization` | `json` | Ya | - | - |
| 13 | `type` | `varchar(255)` | Tidak | - | - |
| 14 | `status` | `varchar(255)` | Tidak | `Upcoming` | - |
| 15 | `client_qr_token` | `varchar(255)` | Ya | - | - |
| 16 | `is_client_qr_active` | `tinyint(1)` | Tidak | `0` | - |
| 17 | `guest_qr_token` | `varchar(255)` | Ya | - | - |
| 18 | `is_guest_qr_active` | `tinyint(1)` | Tidak | `0` | - |
| 19 | `created_at` | `timestamp` | Ya | - | - |
| 20 | `updated_at` | `timestamp` | Ya | - | - |

**Foreign Keys:**
- `category_id` -> `event_categories(id)` (On Delete: `cascade`)

---

## Tabel: `failed_jobs`

| No | Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|---|
| 1 | `id` | `bigint unsigned` | Tidak | - | **Primary Key**, Auto Increment |
| 2 | `uuid` | `varchar(255)` | Tidak | - | - |
| 3 | `connection` | `text` | Tidak | - | - |
| 4 | `queue` | `text` | Tidak | - | - |
| 5 | `payload` | `longtext` | Tidak | - | - |
| 6 | `exception` | `longtext` | Tidak | - | - |
| 7 | `failed_at` | `timestamp` | Tidak | `CURRENT_TIMESTAMP` | - |

---

## Tabel: `faqs`

| No | Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|---|
| 1 | `id` | `bigint unsigned` | Tidak | - | **Primary Key**, Auto Increment |
| 2 | `question` | `varchar(255)` | Tidak | - | - |
| 3 | `answer` | `text` | Tidak | - | - |
| 4 | `order` | `int` | Tidak | `0` | - |
| 5 | `is_active` | `tinyint(1)` | Tidak | `1` | - |
| 6 | `created_at` | `timestamp` | Ya | - | - |
| 7 | `updated_at` | `timestamp` | Ya | - | - |

---

## Tabel: `job_batches`

| No | Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|---|
| 1 | `id` | `varchar(255)` | Tidak | - | **Primary Key** |
| 2 | `name` | `varchar(255)` | Tidak | - | - |
| 3 | `total_jobs` | `int` | Tidak | - | - |
| 4 | `pending_jobs` | `int` | Tidak | - | - |
| 5 | `failed_jobs` | `int` | Tidak | - | - |
| 6 | `failed_job_ids` | `longtext` | Tidak | - | - |
| 7 | `options` | `mediumtext` | Ya | - | - |
| 8 | `cancelled_at` | `int` | Ya | - | - |
| 9 | `created_at` | `int` | Tidak | - | - |
| 10 | `finished_at` | `int` | Ya | - | - |

---

## Tabel: `jobs`

| No | Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|---|
| 1 | `id` | `bigint unsigned` | Tidak | - | **Primary Key**, Auto Increment |
| 2 | `queue` | `varchar(255)` | Tidak | - | - |
| 3 | `payload` | `longtext` | Tidak | - | - |
| 4 | `attempts` | `tinyint unsigned` | Tidak | - | - |
| 5 | `reserved_at` | `int unsigned` | Ya | - | - |
| 6 | `available_at` | `int unsigned` | Tidak | - | - |
| 7 | `created_at` | `int unsigned` | Tidak | - | - |

---

## Tabel: `management_users`

| No | Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|---|
| 1 | `id` | `bigint unsigned` | Tidak | - | **Primary Key**, Auto Increment |
| 2 | `name` | `varchar(50)` | Tidak | - | - |
| 3 | `email` | `varchar(50)` | Tidak | - | - |
| 4 | `email_verified_at` | `timestamp` | Ya | - | - |
| 5 | `password` | `varchar(255)` | Tidak | - | - |
| 6 | `role` | `varchar(20)` | Tidak | - | - |
| 7 | `role_id` | `bigint unsigned` | Ya | - | Foreign Key: Menghubungkan ke `roles.id` (On Delete: set null) |
| 8 | `avatar` | `varchar(255)` | Ya | - | - |
| 9 | `birth_date` | `date` | Ya | - | - |
| 10 | `gender` | `varchar(255)` | Ya | - | - |
| 11 | `phone_number` | `varchar(255)` | Ya | - | - |
| 12 | `address` | `text` | Ya | - | - |
| 13 | `status` | `varchar(255)` | Tidak | `Available` | - |
| 14 | `total_events_handled` | `int` | Tidak | `0` | - |
| 15 | `joined_at` | `date` | Ya | - | - |
| 16 | `remember_token` | `varchar(100)` | Ya | - | - |
| 17 | `created_at` | `timestamp` | Ya | - | - |
| 18 | `updated_at` | `timestamp` | Ya | - | - |

**Foreign Keys:**
- `role_id` -> `roles(id)` (On Delete: `set null`)

---

## Tabel: `migrations`

| No | Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|---|
| 1 | `id` | `int unsigned` | Tidak | - | **Primary Key**, Auto Increment |
| 2 | `migration` | `varchar(255)` | Tidak | - | - |
| 3 | `batch` | `int` | Tidak | - | - |

---

## Tabel: `notifications`

| No | Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|---|
| 1 | `id` | `char(36)` | Tidak | - | **Primary Key** |
| 2 | `type` | `varchar(255)` | Tidak | - | - |
| 3 | `notifiable_type` | `varchar(255)` | Tidak | - | - |
| 4 | `notifiable_id` | `bigint unsigned` | Tidak | - | - |
| 5 | `data` | `text` | Tidak | - | - |
| 6 | `read_at` | `timestamp` | Ya | - | - |
| 7 | `created_at` | `timestamp` | Ya | - | - |
| 8 | `updated_at` | `timestamp` | Ya | - | - |

---

## Tabel: `package_items`

| No | Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|---|
| 1 | `id` | `bigint unsigned` | Tidak | - | **Primary Key**, Auto Increment |
| 2 | `package_id` | `bigint unsigned` | Tidak | - | Foreign Key: Menghubungkan ke `packages.id` (On Delete: cascade) |
| 3 | `name` | `varchar(50)` | Tidak | - | - |
| 4 | `created_at` | `timestamp` | Ya | - | - |
| 5 | `updated_at` | `timestamp` | Ya | - | - |

**Foreign Keys:**
- `package_id` -> `packages(id)` (On Delete: `cascade`)

---

## Tabel: `packages`

| No | Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|---|
| 1 | `id` | `bigint unsigned` | Tidak | - | **Primary Key**, Auto Increment |
| 2 | `category_id` | `bigint unsigned` | Tidak | - | Foreign Key: Menghubungkan ke `event_categories.id` (On Delete: cascade) |
| 3 | `name` | `varchar(50)` | Tidak | - | - |
| 4 | `original_price` | `decimal(15,2)` | Tidak | `0.00` | - |
| 5 | `final_price` | `decimal(15,2)` | Tidak | `0.00` | - |
| 6 | `created_at` | `timestamp` | Ya | - | - |
| 7 | `updated_at` | `timestamp` | Ya | - | - |

**Foreign Keys:**
- `category_id` -> `event_categories(id)` (On Delete: `cascade`)

---

## Tabel: `password_reset_tokens`

| No | Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|---|
| 1 | `email` | `varchar(255)` | Tidak | - | **Primary Key** |
| 2 | `token` | `varchar(255)` | Tidak | - | - |
| 3 | `created_at` | `timestamp` | Ya | - | - |

---

## Tabel: `payments`

| No | Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|---|
| 1 | `id` | `bigint unsigned` | Tidak | - | **Primary Key**, Auto Increment |
| 2 | `invoice_no` | `varchar(255)` | Tidak | - | - |
| 3 | `event_id` | `bigint unsigned` | Tidak | - | Foreign Key: Menghubungkan ke `events.id` (On Delete: cascade) |
| 4 | `package_id` | `bigint unsigned` | Ya | - | Foreign Key: Menghubungkan ke `packages.id` (On Delete: set null) |
| 5 | `custom_package_name` | `varchar(50)` | Ya | - | - |
| 6 | `custom_package_price` | `decimal(15,2)` | Ya | - | - |
| 7 | `payment_type` | `varchar(255)` | Tidak | - | - |
| 8 | `amount` | `decimal(15,2)` | Tidak | - | - |
| 9 | `payment_date` | `date` | Tidak | - | - |
| 10 | `notes` | `text` | Ya | - | - |
| 11 | `proof_document` | `varchar(255)` | Ya | - | - |
| 12 | `status` | `varchar(255)` | Tidak | `Pending` | - |
| 13 | `snap_token` | `varchar(255)` | Ya | - | - |
| 14 | `created_at` | `timestamp` | Ya | - | - |
| 15 | `updated_at` | `timestamp` | Ya | - | - |

**Foreign Keys:**
- `event_id` -> `events(id)` (On Delete: `cascade`)
- `package_id` -> `packages(id)` (On Delete: `set null`)

---

## Tabel: `permissions`

| No | Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|---|
| 1 | `id` | `bigint unsigned` | Tidak | - | **Primary Key**, Auto Increment |
| 2 | `name` | `varchar(255)` | Tidak | - | - |
| 3 | `display_name` | `varchar(255)` | Tidak | - | - |
| 4 | `module` | `varchar(255)` | Ya | - | - |
| 5 | `created_at` | `timestamp` | Ya | - | - |
| 6 | `updated_at` | `timestamp` | Ya | - | - |

---

## Tabel: `role_permission`

| No | Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|---|
| 1 | `role_id` | `bigint unsigned` | Tidak | - | **Primary Key**, Foreign Key: Menghubungkan ke `roles.id` (On Delete: cascade) |
| 2 | `permission_id` | `bigint unsigned` | Tidak | - | **Primary Key**, Foreign Key: Menghubungkan ke `permissions.id` (On Delete: cascade) |

**Foreign Keys:**
- `permission_id` -> `permissions(id)` (On Delete: `cascade`)
- `role_id` -> `roles(id)` (On Delete: `cascade`)

---

## Tabel: `roles`

| No | Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|---|
| 1 | `id` | `bigint unsigned` | Tidak | - | **Primary Key**, Auto Increment |
| 2 | `name` | `varchar(255)` | Tidak | - | - |
| 3 | `display_name` | `varchar(255)` | Tidak | - | - |
| 4 | `description` | `text` | Ya | - | - |
| 5 | `created_at` | `timestamp` | Ya | - | - |
| 6 | `updated_at` | `timestamp` | Ya | - | - |

---

## Tabel: `sessions`

| No | Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|---|
| 1 | `id` | `varchar(255)` | Tidak | - | **Primary Key** |
| 2 | `user_id` | `bigint unsigned` | Ya | - | - |
| 3 | `ip_address` | `varchar(45)` | Ya | - | - |
| 4 | `user_agent` | `text` | Ya | - | - |
| 5 | `payload` | `longtext` | Tidak | - | - |
| 6 | `last_activity` | `int` | Tidak | - | - |

---

## Tabel: `users`

| No | Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|---|
| 1 | `id` | `bigint unsigned` | Tidak | - | **Primary Key**, Auto Increment |
| 2 | `name` | `varchar(50)` | Tidak | - | - |
| 3 | `email` | `varchar(255)` | Tidak | - | - |
| 4 | `email_verified_at` | `timestamp` | Ya | - | - |
| 5 | `password` | `varchar(255)` | Tidak | - | - |
| 6 | `remember_token` | `varchar(100)` | Ya | - | - |
| 7 | `created_at` | `timestamp` | Ya | - | - |
| 8 | `updated_at` | `timestamp` | Ya | - | - |

---

## Tabel: `vendors`

| No | Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|---|
| 1 | `id` | `bigint unsigned` | Tidak | - | **Primary Key**, Auto Increment |
| 2 | `name` | `varchar(50)` | Tidak | - | - |
| 3 | `category` | `varchar(255)` | Tidak | - | - |
| 4 | `phone` | `varchar(255)` | Tidak | - | - |
| 5 | `address` | `text` | Tidak | - | - |
| 6 | `logo` | `varchar(255)` | Ya | - | - |
| 7 | `created_at` | `timestamp` | Ya | - | - |
| 8 | `updated_at` | `timestamp` | Ya | - | - |

---

## Tabel: `website_settings`

| No | Kolom | Tipe Data | Nullable | Default | Keterangan |
|---|---|---|---|---|---|
| 1 | `id` | `bigint unsigned` | Tidak | - | **Primary Key**, Auto Increment |
| 2 | `key` | `varchar(255)` | Tidak | - | - |
| 3 | `value` | `text` | Ya | - | - |
| 4 | `type` | `varchar(255)` | Tidak | `text` | - |
| 5 | `group` | `varchar(255)` | Tidak | `general` | - |
| 6 | `created_at` | `timestamp` | Ya | - | - |
| 7 | `updated_at` | `timestamp` | Ya | - | - |

---

