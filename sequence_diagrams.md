# Sequence Diagram Aktivitas Kunci - Wedding Management System

Dokumen ini berisi sequence diagram untuk tiga alur aktivitas utama yang berjalan pada sistem manajemen pernikahan (*Wedding Management System*).

---

## 1. Alur Reservasi Pernikahan & Pembayaran DP (Integrasi Midtrans)

Alur ini menjelaskan bagaimana klien melakukan reservasi tanggal pernikahan di halaman depan (*landing page*), melakukan pengecekan ketersediaan tanggal, memperoleh Snap Token dari Midtrans, melakukan pembayaran, hingga sistem memvalidasi pembayaran dan mendaftarkan event beserta data pembayarannya. Alur ini juga menyertakan *fallback* jika pengguna menutup browser sebelum halaman memproses status sukses secara manual.

```mermaid
sequenceDiagram
autonumber
actor Klien as Klien (Browser)
participant Controller as ReservationController
participant Cache as Laravel Cache
participant DB as Database (SQL)
participant Midtrans as Midtrans API
participant Email as Mailtrap (SMTP)

%% --- Tahap 1: Cek Ketersediaan ---
rect rgb(240, 245, 255)
    Note over Klien, DB: 1. Cek Ketersediaan Tanggal
    Klien->>Controller: GET /reservasi/check-availability?date={tanggal}
    Controller->>DB: Count Event di tanggal terkait
    DB-->>Controller: Jumlah Event terdaftar
    Controller-->>Klien: JSON { success: true/false, message }
end

%% --- Tahap 2: Request Token & Simpan Cache ---
rect rgb(245, 240, 255)
    Note over Klien, Midtrans: 2. Pembuatan Transaksi & Snap Token
    Klien->>Controller: POST /reservasi/token (client_name, date, package_id, dll)
    Controller->>DB: Re-check ketersediaan tanggal (Server-side)
    Controller->>Midtrans: Request Snap Token (Invoice, DP Amount)
    Midtrans-->>Controller: Kembalikan Snap Token
    Controller->>Cache: Simpan form data di Cache (reservation_payload_{invoice_no}) selama 24 jam
    Controller-->>Klien: JSON { success: true, snap_token, invoice_no, amount }
end

%% --- Tahap 3: Proses Pembayaran & Penyimpanan Transaksi ---
rect rgb(240, 255, 240)
    Note over Klien, Email: 3. Pembayaran & Registrasi Event (Jalur Utama Frontend)
    Klien->>Klien: Buka Midtrans Snap Popup & Selesaikan Pembayaran
    Klien->>Controller: POST /reservasi (detail form & snap_token)
    Controller->>Midtrans: Verifikasi status transaksi (getTransactionStatus)
    Midtrans-->>Controller: Status Pembayaran (settlement/capture)
    
    alt Status = Paid
        Controller->>DB: Mulai Transaksi Database
        Controller->>DB: Simpan Event baru (Status: 'In Queue')
        Controller->>DB: Simpan Payment baru (Status: 'Paid')
        Controller->>Cache: Hapus cache payload (reservation_payload_{invoice_no})
        Controller->>DB: Kirim Notifikasi Internal (Event & Payment)
        Controller->>Email: Kirim Notifikasi Email via Mailtrap (MidtransOrderPaidMail)
        Controller-->>Klien: JSON { success: true, booking_code, invoice_no }
    else Status != Paid
        Controller-->>Klien: JSON { success: false, message: 'Pembayaran belum diselesaikan' }
    end
end

%% --- Tahap 4: Webhook Fallback ---
rect rgb(255, 245, 240)
    Note over Midtrans, Email: 4. Fallback Webhook (Jika Klien Menutup Browser Sebelum Submit)
    Midtrans->>Controller: POST /midtrans/notification (order_id, transaction_status)
    Controller->>Midtrans: Verifikasi Signature Key
    alt Signature Valid & Status = Paid
        Controller->>DB: Cek apakah Payment dengan invoice_no sudah terbuat
        alt Belum Terbuat (Browser ditutup klien prematur)
            Controller->>Cache: Ambil data payload dari Cache
            Controller->>DB: Mulai Transaksi Database
            Controller->>DB: Simpan Event baru (Status: 'In Queue')
            Controller->>DB: Simpan Payment baru (Status: 'Paid')
            Controller->>DB: Kirim Notifikasi Internal (Event & Payment)
            Controller->>Email: Kirim Notifikasi Email via Mailtrap
            Controller->>Cache: Hapus cache payload
        end
    end
    Controller-->>Midtrans: JSON { success: true }
end
```

---

## 2. Alur Akses Undangan Online & Buku Tamu (QR Code)

Alur ini menjelaskan interaksi ketika Tamu Undangan atau Klien melakukan pemindaian QR Code unik untuk mengakses *landing page* undangan online, melihat rundown, dokumentasi, mengisi buku tamu, hingga memberikan testimoni (rating dan ulasan).

```mermaid
sequenceDiagram
autonumber
actor User as Tamu / Klien (Scan QR)
participant Controller as ClientAccessController
participant DB as Database (SQL)

%% --- Redirect QR ---
rect rgb(240, 245, 255)
    Note over User, DB: 1. Scan & Identifikasi QR Token
    User->>Controller: GET /qr/{client|guest}/{token}
    Controller->>DB: Cari Event berdasarkan Token & status QR aktif
    DB-->>Controller: Data Event ditemukan
    alt Tipe: Client
        Controller-->>User: Tampilkan Dashboard Klien (Menu Undangan, Buku Tamu, dll)
    else Tipe: Guest
        Controller-->>User: Redirect langsung ke Form Buku Tamu
    end
end

%% --- Buku Tamu ---
rect rgb(245, 240, 255)
    Note over User, DB: 2. Pengisian Buku Tamu (Guest Book)
    User->>Controller: POST /buku-tamu/{token} (nama, alamat)
    Controller->>DB: Simpan EventGuestBook (event_id, nama, alamat)
    DB-->>Controller: Status Sukses
    Controller-->>User: Redirect ke QR Guest dengan pesan sukses
end

%% --- Testimoni ---
rect rgb(240, 255, 240)
    Note over User, DB: 3. Pengisian Testimoni & Ulasan Acara
    User->>Controller: POST /testimoni/{token} (rating, testimony)
    Controller->>DB: Update atau Buat EventTestimonial (event_id, rating, testimony)
    DB-->>Controller: Status Sukses
    Controller-->>User: Kembali dengan pesan ulasan berhasil disimpan
end
```

---

## 3. Alur Pelaksanaan Acara & Manajemen Crew

Alur ini menjelaskan aktivitas dari sisi tim manajemen (Administrator/Team Leader) dalam mengatur kelancaran hari pelaksanaan acara: menugaskan crew & vendor, memulai acara (*In Progress*), menandai tugas (*checklist/todo*), dan mengakhiri acara (*Completed*) yang secara otomatis memicu perhitungan poin kerja crew.

```mermaid
sequenceDiagram
autonumber
actor Admin as Admin / Team Leader
actor Crew as Crew Terassign
participant Controller as EventController
participant DB as Database (SQL)
participant Notify as Notification Engine

%% --- Penugasan Crew & Vendor ---
rect rgb(240, 245, 255)
    Note over Admin, Notify: 1. Penugasan Crew & Vendor ke Event
    Admin->>Controller: POST /event/{id}/crew (management_user_ids, leader_id)
    Controller->>DB: Sinkronisasi tabel pivot event_crew (is_leader)
    Controller->>Notify: Kirim CrewAssignedNotification ke masing-masing Crew
    Notify-->>Crew: Notifikasi penugasan baru di HP/Email
    Controller-->>Admin: Kembali dengan sukses
end

%% --- Memulai Event ---
rect rgb(245, 240, 255)
    Note over Admin, DB: 2. Mengubah Status Event Menjadi 'In Progress'
    Admin->>Controller: POST /event/{id}/start
    Controller->>DB: Update event.status = 'In Progress'
    Controller-->>Admin: Halaman direfresh, status terupdate
end

%% --- Eksekusi Checklist / To-do ---
rect rgb(240, 255, 240)
    Note over Crew, Notify: 3. Manajemen To-do List / Tugas Lapangan
    Crew->>Controller: POST /event/todo/{todo_id}/toggle
    Controller->>DB: Toggle is_completed pada EventTodo
    alt is_completed = true (Tugas Selesai)
        Controller->>Notify: Kirim TodoCompletedNotification ke Team Leader / Admin
        Notify-->>Admin: Notifikasi tugas selesai
    end
    Controller-->>Crew: JSON { success: true, is_completed }
end

%% --- Mengakhiri Event & Update KPI Crew ---
rect rgb(255, 240, 245)
    Note over Admin, DB: 4. Mengakhiri Event & Akumulasi Kerja Crew
    Admin->>Controller: POST /event/{id}/end
    Controller->>DB: Mulai Transaksi Database
    Controller->>DB: Update event.status = 'Completed'
    loop Setiap Crew yang ditugaskan pada Event ini
        Controller->>DB: Increment total_events_handled (+1) di tabel management_users
    end
    DB-->>Controller: Transaksi Selesai
    Controller-->>Admin: Halaman direfresh, event selesai
end
```
