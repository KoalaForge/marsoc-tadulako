# Formulir Data Anggota (untuk halaman Personnel)

Dokumen ini punya tiga bagian:

1. **Formulir** — teks yang dikirim ke anggota (WhatsApp / chat).
2. **Penjelasan tiap isian** — arti, contoh, dan tampil di mana di situs (bisa dikirim juga kalau anggota bingung).
3. **Untuk admin** — memindahkan jawaban anggota ke admin, dan apa yang diisi admin sendiri.

---

## 1. Formulir (salin dan kirim ke anggota)

```
*FORMULIR DATA ANGGOTA M.R.T*

Halo! Isi data di bawah ini untuk ditampilkan di halaman
Personnel situs M.R.T. Semua isian cukup singkat.
Boleh dikosongkan kalau belum tahu, tulis "-".

*JANGAN* kirim nama lengkap, alamat rumah, nomor HP,
KTP, atau data pribadi lain. Yang diminta hanya data
di bawah ini.

──────────────
*A. DATA UNTUK DITAMPILKAN*

1. Callsign yang diusulkan:
   (nama panggilan di lapangan, mis. RAIDER-09)

2. Peran / jabatan di M.R.T:
   (mis. Perwira Logistik, Corpsman / Medis, Anggota)

3. Bulan & tahun bergabung:
   (format tahun-bulan, mis. 2025-03)

4. Era impresi yang dipakai (pilih SATU):
   [ ] Early MARSOC (Afghanistan)
   [ ] MultiCam (sebelum 2025)
   [ ] Return to MARPAT (2025 sampai sekarang)
   [ ] Belum menentukan

5. Fokus impresi:
   (peran yang kamu bawakan, mis. MARSOC - Team Lead,
   Corpsman / Medis, Logistik / Combat Support)

6. Senjata utama (replika airsoft yang paling sering dipakai):
   (mis. Mk18 Mod 1)

7. Senjata kedua (boleh kosong):
   (mis. Glock 19)

8. Kota / kabupaten domisili:
   (cukup kota, BUKAN alamat rumah. Mis. Palu)

9. Bio singkat (1-3 kalimat):
   (siapa kamu di M.R.T, apa yang kamu kerjakan.
   Mis. "Mengurus pengecekan gear dan logistik
   lapangan sebelum setiap latihan.")

──────────────
*B. FOTO*

Kirim 1 foto TERPISAH (kirim sebagai "Dokumen"/file,
bukan foto biasa, supaya tidak pecah):
- Hanya kamu sendiri, wajah terlihat jelas
- Posisi berdiri/setengah badan, bentuk potret (tegak)
- Terang, tidak blur, tanpa filter berlebihan
- Boleh memakai seragam / helm impresi

──────────────
*C. PERSETUJUAN* (jawab YA atau TIDAK)

a. Saya setuju data pada bagian A ditampilkan di situs
   M.R.T: ........

b. Saya setuju foto saya ditampilkan di situs M.R.T: ........

Saya tahu data ini bisa dihapus atau diubah kapan saja
dengan menghubungi admin.

──────────────
Terima kasih! 🫡
```

---

## 2. Penjelasan tiap isian (bahasa awam)

Isian dalam formulir sama dengan yang tampil di kartu anggota di situs.

| # | Isian | Artinya | Contoh | Tampil di situs sebagai |
|---|---|---|---|---|
| 1 | **Callsign** | Nama panggilan di lapangan, pengganti nama asli. Biasanya "KATA-ANGKA". Admin yang memastikan tidak ada yang kembar. | `RAIDER-09` | Judul besar di kartu dan di alamat halaman profil (`/personnel/raider-09`) |
| 2 | **Peran / jabatan** | Tugasmu di komunitas. | `Perwira Logistik` | Di bawah callsign |
| 3 | **Bergabung** | Bulan dan tahun masuk. Hanya bulan, tanpa tanggal. | `2025-03` | Kolom "Bergabung" |
| 4 | **Era impresi** | Tampilan seragam dan gear yang kamu bawakan. Pilih satu era supaya tampilannya konsisten (aturan M.R.T: campuran era terlihat lebih lemah). | `Return to MARPAT` | Kolom "Impression Era" di halaman profil |
| 5 | **Fokus impresi** | Peran spesifik yang kamu bawakan di lapangan, lebih rinci dari era. | `Corpsman / Medis` | Kolom "Impression" di kartu |
| 6 | **Senjata utama** | Replika airsoft yang paling sering kamu bawa. Tulis seperti nama di toko/forum. | `Mk18 Mod 1` | Kolom "Primary" |
| 7 | **Senjata kedua** | Senjata cadangan (biasanya pistol). Kosongkan kalau tidak ada. | `Glock 19` | Kolom "Secondary" di halaman profil |
| 8 | **Kota / kabupaten** | Hanya sebagai patokan lokasi. Admin mengubahnya menjadi koordinat perkiraan ("grid reference"), jadi **alamat rumah tidak pernah ditampilkan**. | `Palu` | Baris koordinat di bagian bawah kartu |
| 9 | **Bio singkat** | Perkenalan singkat, 1 sampai 3 kalimat, gaya bebas tapi sopan. | *Mengurus pengecekan gear, consumable, dan logistik lapangan sebelum setiap training day.* | Paragraf di halaman profil |
| B | **Foto** | Foto potret untuk kartu dan halaman profil. | — | Thumbnail kecil di kartu, foto lebih besar di profil |
| C | **Persetujuan** | Karena situs ini publik, data hanya ditampilkan kalau anggota setuju. Jawaban terpisah untuk data dan untuk foto. | `YA` / `TIDAK` | — |

Beberapa catatan untuk anggota:
- **Tidak perlu** menulis bahasa Inggris. Admin yang menerjemahkan kalau perlu.
- **Tidak wajib** mengisi semuanya. Isian kosong tidak akan ditampilkan.
- **Foto tidak ditampilkan** kalau jawaban 3b "TIDAK", walaupun foto sudah dikirim.
- Kalau jawaban 3a "TIDAK", anggota tidak muncul di halaman Personnel sama sekali.
- Mau diubah atau dihapus? Cukup hubungi admin.

---

## 3. Untuk admin

### Yang diisi anggota → di mana dimasukkan

Buka **Admin → Komunitas & Arsip → Personnels → Create** (atau Edit anggota yang sudah ada).

| Jawaban anggota | Kolom di admin | Catatan |
|---|---|---|
| 1. Callsign | **Callsign** | Wajib dan unik. Pastikan belum dipakai orang lain. Jarang diubah karena jadi bagian alamat halaman. |
| 2. Peran / jabatan | Tab **ID** → **Peran (id)** | Wajib. Isi tab **EN** bila perlu terjemahan. |
| 3. Bergabung | **Bergabung (YYYY-MM)** | Contoh `2025-03` |
| 4. Era | **Era Impresi** | Pilih dari daftar (terisi dari menu Referensi Impresi → Eras). "Belum menentukan" → kosongkan. |
| 5. Fokus impresi | **Fokus Impresi (id)** | |
| 6. Senjata utama | **Platform Primer** | Teks bebas. |
| 7. Senjata kedua | **Platform Sekunder** | Kosongkan kalau tidak ada (jangan isi tanda "—"). |
| 8. Kota | **Grid Reference** | Ubah kota menjadi koordinat perkiraan, misalnya Palu = `00.89°S 119.87°E`. **Jangan** pakai koordinat rumah. |
| 9. Bio | **Bio (id)** | Wajib di tab ID. |
| B. Foto | **Portrait** | Unggah. Foto otomatis dipotong rasio 3:4. |
| C-a. Data boleh tampil | **Dipublikasikan** | Jawaban TIDAK → matikan. |
| C-b. Foto boleh tampil | **Tampilkan foto di situs** | Aktifkan hanya jika jawabannya YA. Kolom "Foto publik" di tabel membantu mengecek siapa yang sudah setuju. |

### Yang diisi admin sendiri (tidak ditanyakan ke anggota)
- **Member ID** — nomor anggota yang diterbitkan tim (mis. `MRT-010`).
- **Status** — `Aktif` atau `Masa Percobaan`.
- **Urutan** — posisi di daftar roster.
- **Terjemahan EN** — opsional. Kalau kosong, halaman EN memakai teks Indonesia. Widget "Terjemahan belum lengkap" di dashboard akan menunjukkan mana yang kosong.

### Privasi, ringkasnya
- Data yang dikumpulkan sengaja minimal: tidak ada nama asli, alamat, nomor HP, atau KTP.
- Foto hanya tampil jika **izin foto aktif** dan foto sudah diunggah.
- Simpan jawaban persetujuan anggota (screenshot chat) sebagai bukti.
- Data demo bisa dihapus semuanya dengan `php artisan content:purge-synthetic --force` sebelum memasukkan data asli.
