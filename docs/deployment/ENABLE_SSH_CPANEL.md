# 🔑 Cara Enable SSH di cPanel

SSH diperlukan agar GitHub Actions bisa auto-deploy ke cPanel.

---

## ✅ Cek Apakah SSH Sudah Enabled

### Cara 1: Via cPanel
1. Login ke cPanel
2. Cari **"SSH Access"** atau **"Terminal"**
3. Jika ada → SSH sudah enabled ✅
4. Jika tidak ada → Lanjut ke langkah enable

### Cara 2: Via Terminal Lokal
```bash
ssh medp7341@wonderfultoba.com
```

- Jika minta password → SSH enabled ✅
- Jika "Connection refused" → SSH belum enabled ❌

---

## 🔧 Cara Enable SSH

### Opsi 1: Via cPanel (Jika Ada Menu SSH)

1. Login cPanel
2. Cari **"SSH Access"**
3. Klik **"Manage SSH Keys"**
4. Jika belum ada key, klik **"Generate a New Key"**
5. Isi form:
   - Key Name: `github-deploy`
   - Key Password: (kosongkan atau isi)
   - Key Type: RSA
   - Key Size: 2048
6. Klik **Generate Key**
7. Klik **Go Back**
8. Klik **Manage** pada key yang baru dibuat
9. Klik **Authorize** (pindahkan ke authorized_keys)

### Opsi 2: Hubungi Hosting Provider

Jika tidak ada menu SSH di cPanel:

**Email/Ticket ke Support:**

```
Subject: Enable SSH Access for medp7341

Hi,

Saya ingin mengaktifkan SSH access untuk account cPanel saya:
- Username: medp7341
- Domain: wonderfultoba.com

SSH diperlukan untuk auto-deployment dari GitHub.

Terima kasih.
```

Biasanya hosting akan enable dalam 1-24 jam.

---

## 🧪 Test SSH Connection

Setelah SSH enabled, test dari komputer kamu:

```bash
ssh medp7341@wonderfultoba.com
```

Atau dengan port custom (jika bukan 22):

```bash
ssh -p 2222 medp7341@wonderfultoba.com
```

**Jika berhasil:**
- Akan minta password
- Masukkan password cPanel
- Masuk ke terminal server ✅

**Jika gagal:**
- "Connection refused" → SSH belum enabled
- "Permission denied" → Password salah
- "Port 22: Connection timed out" → Firewall block

---

## 🔍 Cek SSH Port

Default SSH port adalah **22**, tapi beberapa hosting menggunakan port custom.

### Cara Cek:

**1. Via cPanel:**
- Cari **"SSH Access"**
- Lihat informasi port

**2. Via Support:**
- Tanya ke hosting support

**3. Via Trial & Error:**
```bash
# Test port 22
ssh -p 22 medp7341@wonderfultoba.com

# Test port 2222
ssh -p 2222 medp7341@wonderfultoba.com

# Test port 2083
ssh -p 2083 medp7341@wonderfultoba.com
```

---

## 📝 Update GitHub Secrets

Setelah SSH enabled, pastikan GitHub Secrets sudah benar:

1. GitHub → **Settings** → **Secrets and variables** → **Actions**

2. Update/tambahkan:

```
CPANEL_HOST = wonderfultoba.com (atau IP)
CPANEL_USERNAME = medp7341
CPANEL_PASSWORD = [password cPanel kamu]
CPANEL_PORT = 22 (atau port yang benar)
```

---

## 🆘 Troubleshooting

### "SSH Access" tidak ada di cPanel

**Solusi:**
- Hubungi hosting provider
- Minta enable SSH access
- Atau upgrade paket hosting (beberapa shared hosting tidak support SSH)

### "Permission denied (publickey)"

**Solusi:**
- Pastikan menggunakan password authentication
- Atau setup SSH key di cPanel

### "Connection timed out"

**Solusi:**
- Cek firewall
- Cek port yang benar
- Hubungi hosting support

### Hosting Tidak Support SSH

**Alternatif:**
1. **FTP Deployment** (lebih lambat)
2. **cPanel API** (lebih kompleks)
3. **Manual deployment** via cPanel Git
4. **Upgrade hosting** yang support SSH

---

## ✅ Checklist

Sebelum lanjut ke auto-deploy, pastikan:

- [ ] SSH sudah enabled di cPanel
- [ ] Bisa login via SSH dari terminal
- [ ] Tahu SSH port yang benar
- [ ] GitHub Secrets sudah diisi
- [ ] Test connection berhasil

---

## 📞 Info Hosting

Jika menggunakan hosting populer:

**Niagahoster:**
- SSH: ✅ Tersedia (paket tertentu)
- Port: 22
- Cara enable: Via cPanel atau ticket

**Hostinger:**
- SSH: ✅ Tersedia (paket Business ke atas)
- Port: 22
- Cara enable: Via hPanel

**Rumahweb:**
- SSH: ✅ Tersedia (paket tertentu)
- Port: 22
- Cara enable: Via ticket support

**Dewaweb:**
- SSH: ✅ Tersedia
- Port: 22
- Cara enable: Via cPanel

---

Setelah SSH enabled, lanjut ke **SETUP_AUTO_DEPLOY.md** untuk setup GitHub Actions! 🚀
