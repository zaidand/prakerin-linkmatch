# Prakerin LinkMatch

Aplikasi manajemen PRAKERIN/PKL berbasis web untuk membantu sekolah mencocokkan (match) siswa dengan industri sesuai jurusan, mengelola kuota prakerin, logbook harian, penilaian industri, dan rekap nilai akhir.

## Fitur Utama

### 1) Multi-role (4 aktor)
- **Admin**
  - Aktivasi akun pengguna
  - Kelola **Jurusan**
  - Verifikasi **Industri**
  - Assign/penempatan siswa ke **kuota industri**
  - Cetak **Surat Pengantar**
  - Laporan & export **Nilai Akhir**
- **Guru Pembimbing**
  - Verifikasi pengajuan prakerin siswa
  - Monitoring & catatan monitoring
  - Review logbook (komentar)
  - Input & finalisasi nilai akhir
- **Siswa**
  - Lihat daftar industri yang relevan dengan jurusan + kuota aktif
  - Ajukan prakerin
  - Isi logbook harian + upload dokumentasi
  - Upload laporan akhir
- **Pembimbing Lapangan (Industri)**
  - Lengkapi profil industri + pilih jurusan yang diterima
  - Kelola kuota prakerin per periode
  - Konfirmasi penempatan (accept/reject)
  - Validasi logbook
  - Isi penilaian industri

### 2) Flow Status Pengajuan Prakerin
Secara garis besar:
1. Siswa mengajukan → `waiting_teacher_verification`
2. Guru menyetujui → `approved_by_teacher`
3. Admin assign kuota → `assigned_by_admin`
4. Industri konfirmasi → `accepted` atau `rejected`

### 3) Upload File & Storage
- Dokumentasi logbook disimpan ke `storage/app/public/logbooks`
- Laporan akhir disimpan ke `storage/app/public/final_reports`
> Pastikan menjalankan `php artisan storage:link`.

## Tech Stack
- **Backend:** Laravel 12 (PHP 8.2+)
- **Auth Scaffold:** Laravel Breeze (Blade)
- **Frontend:** TailwindCSS + AlpineJS + Vite
- **Database:** SQLite (default `.env.example`) atau MySQL
- **Notification:** Database + Email (MAIL bisa pakai `log` untuk dev)

---

## Prasyarat
- PHP **8.2+**
- Composer
- Node.js + npm
- Database: SQLite atau MySQL

---

## Instalasi Lokal

### 1) Clone & install dependency
```bash
git clone <repo-url>
cd prakerin-linkmatch
composer install
npm install
