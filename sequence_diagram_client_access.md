# Sequence Diagram - Client Access (Read Data)

Dokumen ini berisi sequence diagram sederhana untuk alur membaca (*read*) data halaman Client Access setelah melakukan pemindaian QR Code.

---

## Alur Pembacaan Data Klien & Tamu

```mermaid
sequenceDiagram
    autonumber
    actor User as Klien / Tamu
    participant System as Sistem Web
    participant Controller as ClientAccessController
    participant DB as Database

    %% --- 1. Scan QR & Redirect ---
    Note over User, DB: 1. Scan QR Code & Validasi Akses
    User->>System: Scan QR Code (Client / Guest)
    System->>Controller: Kirim token ke method Redirect
    Controller->>DB: Cari Event & validasi token
    DB-->>Controller: Return data Event
    Controller-->>System: Tampilkan halaman utama / portal
    System-->>User: Tampilkan Halaman Portal / Menu Utama

    %% --- 2. Membaca Konten (Read Halaman) ---
    Note over User, DB: 2. Membaca Informasi/Halaman (Undangan, Rundown, Dokumentasi, dll)
    User->>System: Pilih Menu (Undangan / Rundown / Dokumentasi / Buku Tamu / Testimoni)
    System->>Controller: Request halaman spesifik menggunakan token
    Controller->>DB: Query data relasi Event yang dibutuhkan
    DB-->>Controller: Return data yang diminta
    Controller-->>System: Render view halaman dengan data
    System-->>User: Tampilkan halaman informasi lengkap
```

### Rincian Endpoint Pembacaan Data (Read):

| Aktivitas | Route / URL | Controller Method | Data yang Dibaca |
|-----------|-------------|-------------------|-------------------|
| **Scan QR (Akses Awal)** | `GET /qr/client/{token}` atau `/qr/guest/{token}` | `clientQrRedirect` / `guestQrRedirect` | Status keaktifan QR Event |
| **Buka Undangan** | `GET /invitation/{token}` | `showInvitation` | Detail acara & Nama Pengantin |
| **Lihat Rundown** | `GET /rundown/{token}` | `showRundown` | Agenda/jadwal acara (`rundowns`) |
| **Lihat Dokumentasi** | `GET /documentation/{token}` | `showDocumentation` | Foto & Galeri acara |
| **Lihat Buku Tamu** | `GET /guest-book/{token}` | `showGuestBook` | Daftar hadir tamu (`guestBooks`) |
| **Lihat Testimoni** | `GET /testimonial/{token}` | `showTestimonial` | Penilaian & kesan pesan (`testimonial`) |


