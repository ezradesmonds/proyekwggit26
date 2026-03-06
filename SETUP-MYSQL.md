# 🗄️ Setup MySQL Laragon — WGG Inventory

## Langkah 1 — Pastikan Laragon Berjalan
Buka Laragon → klik **Start All** (Apache + MySQL harus hijau ✅)

---

## Langkah 2 — Buat Database

Ada 2 cara:

### Cara A: Via HeidiSQL (GUI bawaan Laragon)
1. Di Laragon → klik **Database** → HeidiSQL terbuka
2. Login otomatis (user: `root`, password: kosong)
3. Klik kanan di panel kiri → **Create new** → **Database**
4. Isi nama: `wgg_inventory` → klik **OK**

### Cara B: Via Terminal
```bash
# Buka terminal di Laragon atau CMD
mysql -u root -p
# tekan Enter saja saat diminta password (kosong)

# Lalu jalankan:
CREATE DATABASE wgg_inventory CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

---

## Langkah 3 — Edit File `.env`

Buka file `.env` di root project Laravel kamu, ubah bagian DB menjadi:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wgg_inventory
DB_USERNAME=root
DB_PASSWORD=
```

> ⚠️ Password Laragon defaultnya **kosong**. Kalau kamu pernah set password MySQL, isi di `DB_PASSWORD=`.

---

## Langkah 4 — Jalankan Migration & Seeder

Buka terminal di folder project:

```bash
php artisan migrate
php artisan db:seed
```

Output yang benar:
```
  INFO  Running migrations.
  2024_01_01_000000_create_items_table ...... 10ms DONE

  INFO  Seeding database.
  Database\Seeders\ItemSeeder ............... DONE
```

---

## Langkah 5 — Jalankan Server

```bash
php artisan serve
```

Buka: **http://localhost:8000** 🎉

---

## ❌ Troubleshooting

### Error: `SQLSTATE[HY000] [1049] Unknown database 'wgg_inventory'`
→ Database belum dibuat. Ulangi Langkah 2.

### Error: `SQLSTATE[HY000] [1045] Access denied for user 'root'@'localhost'`
→ Password salah. Cek password MySQL Laragon kamu di:
`Laragon → Menu → MySQL → Reset Password`

### Error: `php_network_getaddresses: getaddrinfo failed`
→ MySQL Laragon belum jalan. Klik **Start All** di Laragon.

### Error: `No application encryption key`
```bash
php artisan key:generate
```

### Mau reset data dari awal?
```bash
php artisan migrate:fresh --seed
```
