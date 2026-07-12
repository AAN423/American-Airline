# American Airlines PTFS — Website Panduan Setup

Website ini pakai **PHP + MySQL** (cocok untuk hosting gratis InfinityFree) dan login **wajib pakai Discord**. Ikuti langkah di bawah urut dari atas.

---

## 1. Buat Aplikasi Discord (untuk Login)

1. Buka https://discord.com/developers/applications → **New Application** → kasih nama (misal "AA PTFS Web").
2. Masuk ke tab **OAuth2** (bagian General):
   - Catat **Client ID** dan **Client Secret**.
   - Di bagian **Redirects**, klik **Add Redirect** dan isi:
     ```
     https://namadomainkamu.rf.gd/auth/discord_callback.php
     ```
     (Ganti `namadomainkamu.rf.gd` dengan domain InfinityFree kamu yang sebenarnya. Harus **PERSIS SAMA** dengan yang nanti diisi di `config.php`.)
3. Masuk ke tab **Bot**:
   - Klik **Add Bot** (kalau belum ada).
   - Klik **Reset Token** → catat **Bot Token** (hanya muncul sekali, simpan baik-baik).
   - Scroll ke bawah, di bagian **Privileged Gateway Intents**, **AKTIFKAN**:
     - ✅ **SERVER MEMBERS INTENT** (wajib, untuk cek role member)
4. Invite bot ke server Discord vEZY/AA PTFS kamu:
   - Masih di tab OAuth2 → **URL Generator**.
   - Centang scope: `bot`
   - Centang permission: `View Channels` (minimal, bot ini cuma dipakai buat cek data member lewat API, bukan untuk kirim chat)
   - Copy URL yang muncul di bawah, buka di browser, pilih server kamu, klik Authorize.
5. Ambil **Guild ID (Server ID)**:
   - Di Discord, aktifkan Developer Mode (User Settings → Advanced → Developer Mode).
   - Klik kanan nama server kamu → **Copy Server ID**.

---

## 2. Setup Role Admin di Discord

Pastikan role berikut **sudah ada** di server Discord kamu, dengan nama **persis**:
```
CEO, COO, CDO, CPA, OM
```
User yang punya salah satu role ini otomatis dapat akses ke tab **Admin** di website. Kalau nama role kamu beda, tinggal ubah di `config.php` bagian `ADMIN_ROLE_NAMES`.

---

## 3. Setup Hosting di InfinityFree

1. Daftar/login ke https://infinityfree.net, buat akun hosting baru dengan domain pilihanmu.
2. Masuk ke **Control Panel (cPanel)** hosting kamu.
3. Buat database:
   - Klik **MySQL Databases** → buat database baru.
   - Catat: **DB Host** (contoh `sqlXXX.infinityfree.com`), **Database Name**, **Username**, **Password**.
4. Buka **phpMyAdmin** dari cPanel:
   - Pilih database yang baru dibuat.
   - Klik tab **Import** → upload file `database.sql` yang ada di folder ini → klik **Go**.
   - Ini akan membuat tabel `users` dan `recruitment`.

---

## 4. Isi `config.php`

Buka file `config.php`, isi semua bagian `ISI_...` dengan data kamu:

```php
define('DB_HOST', 'sqlXXX.infinityfree.com');
define('DB_NAME', 'epiz_xxxxxxxx_aaptfs');
define('DB_USER', 'epiz_xxxxxxxx');
define('DB_PASS', 'password_database_kamu');

define('DISCORD_CLIENT_ID', '...');
define('DISCORD_CLIENT_SECRET', '...');
define('DISCORD_REDIRECT_URI', 'https://namadomainkamu.rf.gd/auth/discord_callback.php');

define('DISCORD_BOT_TOKEN', '...');
define('DISCORD_GUILD_ID', '...');

define('SITE_URL', 'https://namadomainkamu.rf.gd');
```

⚠️ **Penting:** `DISCORD_REDIRECT_URI` di sini harus **sama persis** dengan yang kamu daftarkan di Discord Developer Portal langkah 1.

---

## 5. Upload File ke InfinityFree

1. Buka **File Manager** di cPanel InfinityFree (atau pakai FTP, misal FileZilla dengan data FTP dari cPanel).
2. Masuk ke folder `htdocs`.
3. Upload **semua isi** folder proyek ini (bukan foldernya, tapi isinya) ke dalam `htdocs`, sehingga strukturnya jadi:
   ```
   htdocs/
     ├── config.php
     ├── index.php
     ├── dashboard.php
     ├── logout.php
     ├── auth/
     ├── includes/
     └── assets/
   ```
4. Selesai! Buka `https://namadomainkamu.rf.gd` di browser.

---

## 6. Cara Pemakaian

- **Login**: User klik "Login dengan Discord" di halaman awal → diarahkan ke Discord → setelah izin, otomatis masuk ke Dashboard. Kalau akun Discord-nya belum join server kamu, akan ditolak dengan pesan error.
- **Tab Home**: Berisi About Us (sudah diisi sesuai teks yang kamu kasih).
- **Tab Recruitment**: Kalau belum ada recruitment yang dibuka, tampil pesan "Recruitment is currently closed". Kalau admin sudah buka, tampil kartu jabatan + tombol Apply.
- **Tab Admin** (hanya muncul untuk role CEO/COO/CDO/CPA/OM):
  - Form untuk buka recruitment baru: Jabatan, Penjelasan (opsional), Link Apply, Dibuka Sampai Tanggal.
  - Tabel riwayat semua recruitment yang pernah dibuat, dengan tombol **Tutup** untuk menutup recruitment yang masih aktif secara manual.
  - Recruitment otomatis dianggap tertutup kalau tanggal "Dibuka Sampai" sudah lewat.

---

## 7. Troubleshooting

| Masalah | Kemungkinan Penyebab |
|---|---|
| "Koneksi database gagal" | Cek `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` di `config.php` |
| Redirect ke Discord tapi balik lagi ke login dengan error "Login Discord gagal" | Cek `DISCORD_CLIENT_ID`/`DISCORD_CLIENT_SECRET`, atau Redirect URI tidak sama persis dengan yang didaftarkan di Discord |
| "Akun Discord kamu belum tergabung di server" padahal sudah join | Cek `DISCORD_GUILD_ID` benar, dan bot sudah di-invite ke server tersebut |
| Tab Admin tidak muncul padahal sudah punya role CEO/dll | Cek nama role di Discord **persis sama** (case tidak masalah) dengan yang ada di `ADMIN_ROLE_NAMES` pada `config.php`. Coba logout lalu login lagi supaya role ke-refresh |
| Error terkait cURL/SSL di InfinityFree | Biasanya cURL sudah aktif di InfinityFree, tapi kalau bermasalah hubungi live chat support InfinityFree untuk aktifkan cURL |

Kalau ada perubahan role Discord, user harus **logout lalu login lagi** supaya statusnya ke-update (karena status admin disimpan di session saat login).
