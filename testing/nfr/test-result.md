# NFR Test Result

## Smart & Simple Mini Soccer Booking System

Dokumen ini berisi hasil inspeksi terhadap Non-Functional Requirements (NFR) berdasarkan aspek inspeksi pada source code project. Status PASS menunjukkan kriteria terpenuhi berdasarkan pemeriksaan source code, sedangkan FAIL menunjukkan kriteria belum terpenuhi secara konsisten. NFR-012 diberi status NOT VERIFIED karena pengujian performa dengan data berukuran besar belum dilakukan.

## Summary

| Status | Jumlah |
|---|---:|
| Total NFR | 12 |
| PASS | 5 |
| FAIL | 6 |
| NOT VERIFIED | 1 |

## Detail Hasil Pengujian

| ID | Aspek | Kriteria | Related Test Condition | Status | Evidence |
|---|---|---|---|---|---|
| NFR-001 | Readability | Nama variabel dan fungsi mudah dipahami | Memeriksa penggunaan nama seperti `$booking_date`, `$start_time`, dan `$field_id` pada source code | PASS | Source code menunjukkan penggunaan nama variabel yang deskriptif |
| NFR-002 | Readability | Penamaan dan format coding konsisten | Menjalankan PHP_CodeSniffer pada folder `admin`, `auth`, `config`, dan `user` | FAIL | PHP_CodeSniffer menemukan 146 errors dan 198 warnings |
| NFR-003 | Maintainability | Kode dipisahkan berdasarkan fungsi/modul | Memeriksa struktur folder `admin`, `auth`, `config`, dan `user` | PASS | Struktur project memisahkan fungsi berdasarkan modul |
| NFR-004 | Maintainability | Teknologi database digunakan secara konsisten | Memeriksa penggunaan koneksi database pada `config/db.php` dan file lainnya | FAIL | PDO dan MySQLi digunakan secara bersamaan |
| NFR-005 | Security | Password disimpan menggunakan hashing | Memeriksa proses registrasi pada `auth/register.php` | PASS | Password diproses menggunakan `password_hash()` |
| NFR-006 | Security | Query yang menerima input pengguna menggunakan prepared statement | Memeriksa query login dan query yang menerima input pengguna | FAIL | Tidak semua query menggunakan prepared statement; ditemukan penggunaan query langsung pada `auth/login.php` |
| NFR-007 | Input Validation | Data booking divalidasi sebelum diproses | Memeriksa validasi tanggal, waktu, dan data booking pada `user/booking.php` | PASS | Validasi tanggal dan waktu diterapkan sebelum proses booking |
| NFR-008 | Input Validation | Upload pembayaran memiliki validasi file yang memadai | Memeriksa validasi file pada `user/payment.php` | FAIL | Ekstensi file divalidasi, tetapi validasi ukuran dan MIME belum lengkap |
| NFR-009 | Error Handling | Kesalahan booking ditangani dan memberikan feedback kepada pengguna | Memeriksa penanganan kesalahan pada `user/booking.php` | PASS | Terdapat validasi dan pesan kesalahan kepada pengguna |
| NFR-010 | Error Handling | Error database ditangani secara konsisten | Memeriksa mekanisme error database pada konfigurasi dan modul terkait | FAIL | Penanganan error database belum diterapkan secara konsisten di seluruh modul |
| NFR-011 | Performance | Query booking menggunakan kondisi yang diperlukan dan parameter/prepared statement | Memeriksa query pengecekan konflik booking pada `user/booking.php` | PASS | Query menggunakan kondisi yang diperlukan dan prepared statement |
| NFR-012 | Performance | Efisiensi database tetap baik pada data berukuran besar | Menguji efisiensi query menggunakan dataset yang lebih besar | NOT VERIFIED | Pengujian menggunakan data berukuran besar belum dilakukan |

## Hasil Per Aspek

### 1. Readability

- Total test condition: 2
- PASS: 1
- FAIL: 1

**Kesimpulan:** Struktur dan penamaan variabel cukup mudah dipahami, tetapi konsistensi coding masih perlu diperbaiki.

### 2. Maintainability

- Total test condition: 2
- PASS: 1
- FAIL: 1

**Kesimpulan:** Pemisahan kode berdasarkan fungsi sudah baik, tetapi penggunaan PDO dan MySQLi secara bersamaan mengurangi konsistensi pemeliharaan.

### 3. Security

- Total test condition: 2
- PASS: 1
- FAIL: 1

**Kesimpulan:** Password sudah menggunakan hashing, tetapi penggunaan prepared statement belum konsisten pada query yang menerima input pengguna.

### 4. Input Validation

- Total test condition: 2
- PASS: 1
- FAIL: 1

**Kesimpulan:** Validasi booking sudah diterapkan, tetapi validasi upload pembayaran masih perlu diperkuat.

### 5. Error Handling

- Total test condition: 2
- PASS: 1
- FAIL: 1

**Kesimpulan:** Kesalahan pada proses booking sudah memberikan feedback, tetapi penanganan error database belum konsisten.

### 6. Performance

- Total test condition: 2
- PASS: 1
- FAIL: 0
- NOT VERIFIED: 1

**Kesimpulan:** Query pengecekan konflik booking sudah menggunakan kondisi dan prepared statement yang sesuai. Namun, performa pada dataset berukuran besar belum diverifikasi.

## Catatan Static Analysis

### PHPStan

Perintah yang digunakan:

```bash
vendor/bin/phpstan analyse
```

Hasil:

- 12 file dianalisis
- Ditemukan 29 static analysis issues/errors

Hasil lengkap tersedia pada:

```text
testing/phpstan/result.txt
```

### PHP_CodeSniffer

Perintah yang digunakan:

```bash
vendor/bin/phpcs admin auth config user
```

Hasil:

- 12 file diperiksa
- 146 errors
- 198 warnings
- Total 344 findings

Hasil lengkap tersedia pada:

```text
testing/phpcs/result.txt
```

> Catatan: angka 29 PHPStan issues dan 344 PHPCS findings merupakan hasil static analysis, bukan jumlah test case. Perhitungan test condition pada dokumen ini menggunakan 12 NFR sebagai 12 kondisi inspeksi.

## Final Conclusion

Dari 12 NFR yang diperiksa, terdapat 5 NFR berstatus PASS, 6 NFR berstatus FAIL, dan 1 NFR berstatus NOT VERIFIED. Hasil inspeksi menunjukkan bahwa project telah memiliki beberapa aspek yang sudah terpenuhi, terutama pada penamaan variabel, pemisahan modul, password hashing, validasi booking, penanganan error booking, dan query pengecekan konflik booking. Perbaikan masih diperlukan pada konsistensi coding, konsistensi teknologi database, keamanan query, validasi upload pembayaran, konsistensi error handling database, serta pengujian performa pada data berukuran besar.
