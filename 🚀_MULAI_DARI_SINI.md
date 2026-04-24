# 🚀 MULAI DARI SINI - Wonderfultoba Auto Deploy

Selamat datang! Ini panduan lengkap deploy otomatis ke cPanel.

---

## 📍 Kamu Ada di Sini

✅ Code sudah di GitHub: https://github.com/ridhorobipasi-creator/wonderfultoba  
✅ Server cPanel siap: wonderfultoba.com  
✅ Auto-deploy sudah dikonfigurasi  

**Tinggal pilih metode dan setup!**

---

## 🎯 Langkah 1: PILIH METODE DEPLOY

Buka file: **`PILIH_METODE_DEPLOY.md`**

Ada 2 pilihan:

### Metode A: FULL OTOMATIS (dengan SSH)
- Push ke GitHub → Langsung deploy (2-3 menit)
- Paling cepat dan mudah
- **Syarat: Hosting support SSH**

### Metode B: SEMI-OTOMATIS (tanpa SSH)  
- Push ke GitHub → Deploy otomatis via Cron (5-10 menit)
- Cocok untuk shared hosting
- **Tidak perlu SSH**

**👉 Baca `PILIH_METODE_DEPLOY.md` untuk memilih**

---

## 🎯 Langkah 2: IKUTI PANDUAN SESUAI METODE

### Jika Pilih Metode A (SSH):

1. **Cek SSH**: Baca `ENABLE_SSH_CPANEL.md`
2. **Setup Auto-Deploy**: Baca `SETUP_AUTO_DEPLOY.md`
3. **Test Deploy**: Push ke GitHub → Otomatis!

### Jika Pilih Metode B (Tanpa SSH):

1. **Setup cPanel Git**: Baca `DEPLOY_TANPA_SSH.md`
2. **Setup Cron Job**: Ikuti panduan di file yang sama
3. **Test Deploy**: Push ke GitHub → Tunggu 5 menit → Otomatis!

---

## 📚 Daftar File Panduan

| File | Untuk Apa |
|------|-----------|
| **`PILIH_METODE_DEPLOY.md`** | 👈 BACA INI DULU - Pilih metode |
| `SETUP_AUTO_DEPLOY.md` | Setup Metode A (SSH) |
| `DEPLOY_TANPA_SSH.md` | Setup Metode B (Tanpa SSH) |
| `ENABLE_SSH_CPANEL.md` | Cara enable SSH |
| `LANGKAH_DEPLOY_CPANEL.txt` | Backup: Deploy manual |
| `DEPLOYMENT_GUIDE_CPANEL.md` | Dokumentasi lengkap |
| `DEPLOY_CHECKLIST.md` | Checklist deployment |

---

## 🔧 File Teknis (Sudah Siap)

| File | Fungsi |
|------|--------|
| `server.js` | Entry point Node.js untuk cPanel |
| `deploy-cpanel.sh` | Script auto-deploy |
| `.github/workflows/deploy-cpanel-auto.yml` | GitHub Actions workflow |
| `.env.production.example` | Template environment variables |

**Kamu tidak perlu edit file-file ini!** Sudah siap pakai.

---

## ⚡ Quick Start (Ringkas)

### Untuk yang Mau Cepat:

**1. Cek SSH:**
```bash
ssh medp7341@wonderfultoba.com
```

**2A. Jika SSH Berhasil:**
- Baca: `SETUP_AUTO_DEPLOY.md`
- Setup GitHub Secrets
- Push → Otomatis deploy!

**2B. Jika SSH Gagal:**
- Baca: `DEPLOY_TANPA_SSH.md`
- Setup cPanel Git + Cron
- Push → Tunggu 5 menit → Otomatis!

---

## 🎯 Goal Akhir

Setelah setup selesai:

```
✅ Push code ke GitHub
✅ Otomatis deploy ke cPanel
✅ Website langsung update
✅ Tidak perlu login cPanel lagi
```

**Total effort setelah setup: 0 (tinggal push!)** 🎉

---

## 🆘 Butuh Bantuan?

### Masalah Umum:

**"SSH tidak bisa"**
→ Baca: `ENABLE_SSH_CPANEL.md`

**"Mau yang paling mudah"**
→ Pakai Metode B (Tanpa SSH)

**"Mau yang paling cepat"**
→ Pakai Metode A (SSH)

**"Bingung harus mulai dari mana"**
→ Baca: `PILIH_METODE_DEPLOY.md`

---

## 📞 Info Penting

**GitHub Repo:**  
https://github.com/ridhorobipasi-creator/wonderfultoba

**Domain:**  
https://wonderfultoba.com

**Admin Panel:**  
https://wonderfultoba.com/login

**Default Login:**
- Email: `admin@wonderfultoba.com`
- Password: `password123`

**cPanel:**
- Username: `medp7341`
- Domain: `wonderfultoba.com`

---

## ✅ Checklist Setup

- [ ] Baca `PILIH_METODE_DEPLOY.md`
- [ ] Pilih metode (A atau B)
- [ ] Ikuti panduan metode yang dipilih
- [ ] Setup database di cPanel
- [ ] Setup environment (.env)
- [ ] Test deploy pertama kali
- [ ] Verifikasi website jalan
- [ ] Test auto-deploy (push ke GitHub)

---

## 🚀 Siap Deploy?

**Langkah pertama:**  
👉 Buka file **`PILIH_METODE_DEPLOY.md`**

Selamat deploy! 🎉
