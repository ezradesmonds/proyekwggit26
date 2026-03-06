# 🧊 WGG Inventory Simulator
**Strengthened Through Every Season · Petra Christian University · 2026**

Inventory Simulator berbasis web menggunakan **Laravel 11** + **Tailwind CSS** + **GSAP**.

---

## ⚡ Setup dari Nol (Langkah demi Langkah)

### 1. Install Laravel Project Baru

```bash
composer create-project laravel/laravel wgg-inventory
cd wgg-inventory
```

### 2. Copy File-file dari Folder Ini

Salin file berikut ke posisi yang sesuai di project Laravel kamu:

| File dari ZIP ini | Tujuan di Laravel project |
|---|---|
| `app/Models/Item.php` | `app/Models/Item.php` |
| `app/Http/Controllers/ItemController.php` | `app/Http/Controllers/ItemController.php` |
| `database/migrations/..._create_items_table.php` | `database/migrations/` (hapus migration lain yg tidak perlu) |
| `database/seeders/ItemSeeder.php` | `database/seeders/ItemSeeder.php` |
| `database/seeders/DatabaseSeeder.php` | `database/seeders/DatabaseSeeder.php` (**replace** isi file lama) |
| `resources/views/layouts/app.blade.php` | `resources/views/layouts/app.blade.php` |
| `resources/views/inventory/index.blade.php` | `resources/views/inventory/index.blade.php` (buat folder `inventory` dulu) |
| `routes/web.php` | `routes/web.php` (**replace** isi file lama) |

### 3. Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Setup Database (SQLite — paling simpel)

```bash
# Buat file database SQLite
touch database/database.sqlite

# Pastikan .env sudah ada baris ini:
# DB_CONNECTION=sqlite
# (hapus/comment baris DB_HOST, DB_PORT, DB_DATABASE, dll)
```

Atau kalau mau **MySQL**:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wgg_inventory
DB_USERNAME=root
DB_PASSWORD=
```
Lalu buat database-nya di MySQL:
```sql
CREATE DATABASE wgg_inventory;
```

### 5. Jalankan Migration & Seeder

```bash
php artisan migrate
php artisan db:seed
```

### 6. Jalankan Server

```bash
php artisan serve
```

Buka browser: **http://localhost:8000** 🎉

---

## 📁 Struktur File Penting

```
wgg-inventory/
├── app/
│   ├── Http/Controllers/
│   │   └── ItemController.php     ← CRUD logic
│   └── Models/
│       └── Item.php               ← Model + stock status accessor
├── database/
│   ├── migrations/
│   │   └── ..._create_items_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── ItemSeeder.php         ← Data contoh
├── resources/views/
│   ├── layouts/
│   │   └── app.blade.php          ← Layout utama (nav, footer, GSAP)
│   └── inventory/
│       └── index.blade.php        ← Halaman inventory lengkap
└── routes/
    └── web.php                    ← API routes CRUD
```

---

## 🎨 Tech Stack

| Tech | Kegunaan |
|---|---|
| **Laravel 11** | Backend framework, routing, ORM Eloquent |
| **Tailwind CSS** (CDN) | Utility-first CSS styling |
| **GSAP 3** | Hero animations, scroll reveal, row transitions |
| **SQLite / MySQL** | Database |
| **Fetch API** | AJAX CRUD tanpa page reload (untuk tambah & hapus) |

---

## ✨ Fitur

- ➕ **Tambah barang** — nama, stok, kategori, stok minimum
- ✏️ **Edit barang** — via animated modal
- 🗑️ **Hapus barang** — dengan konfirmasi modal + GSAP exit animation
- 📊 **Statistik realtime** — total barang, stok aman/rendah/habis
- 🎨 **Visual stock bar** — indikator stok per baris
- 🔍 **Search & Filter** — nama, kategori, kondisi stok
- 🍞 **Toast notifications** — feedback setiap aksi
- 🌊 **Animated background** — ripple + floating ice blobs (sesuai tema WGG)
- 🚀 **GSAP animations** — hero reveal, scroll trigger, row append

---

## 🛠️ Troubleshooting

**`php artisan migrate` error "no such file"** → Pastikan `database/database.sqlite` sudah dibuat dengan `touch database/database.sqlite`

**CSRF error saat submit** → Pastikan `<meta name="csrf-token">` ada di layout (sudah ada) dan `apiFetch()` mengirim header `X-CSRF-TOKEN`

**Tailwind tidak load** → Cek koneksi internet (menggunakan CDN). Untuk production, install via npm: `npm install -D tailwindcss` lalu setup Vite.
