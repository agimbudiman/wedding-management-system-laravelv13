PRODUCT REQUIREMENT DOCUMENT 
SISTEM INFORMASI MANAJEMEN WEDDING ORGANIZER MENGGUNAKAN METODE PROTOTYPE DENGAN FRAMEWORK LARAVEL 13
(Studi Kasus: CV. Brilliant Bertaqwa Berdaya)

1.	Pendahuluan dan Latar Belakang
Sistem Manajemen Pernikahan dan Acara (Wedding & Event Management System) adalah platform berbasis web yang dirancang untuk membantu Event Organizer (EO) dalam mengelola seluruh siklus hidup perencanaan acara, mulai dari manajemen paket, penugasan kru (crew assignment), koordinasi vendor, penyusunan rundown, hingga pelacakan tugas (to-do list) dan pembayaran. Selain itu, sistem ini menyediakan portal akses bagi Klien (calon pengantin/penyelenggara) dan Tamu berbasis QR Code untuk memudahkan akses informasi seperti undangan digital, susunan acara (rundown), dokumentasi, buku tamu digital (guest book), dan testimoni.
2.	Pengguna Sistem (User Personas & Roles)
Sistem ini membagi hak akses ke dalam beberapa peran utama:
a.	Admin
1)	Memiliki kontrol penuh atas sistem.
2)	Mengelola master data (kategori acara, paket layanan, vendor, FAQ, dan pengaturan website).
3)	Mengelola pengguna manajemen (Staf/Kru) dan hak akses (Roles & Permissions).
b.	Kru Acara
1)	Leader: Kru yang ditugaskan sebagai penanggung jawab utama suatu event. Dapat mengedit detail event, rundown, tugas, catatan, dan memantau pembayaran.
2)	Member: Anggota kru yang bertugas membantu jalannya acara. Dapat melihat informasi event, mencentang tugas yang diberikan, dan berinteraksi dengan sistem.
c.	Klien
1)	Mengakses portal khusus menggunakan tautan token QR unik.
2)	Melihat detail acara mereka, melihat dan mencetak invoice pembayaran, serta melihat daftar tamu yang hadir.
3.	Fitur Utama
a.	Manajemen Paket & Item Layanan
1)	Manajemen Kategori Acara: Pengelompokan jenis acara (misal: Wedding, Engagement, Corporate Event).
2)	Paket Layanan: Pembuatan paket dengan harga awal (original price) dan harga akhir setelah diskon (final price).
3)	Item Paket: Daftar rincian layanan atau fasilitas yang didapatkan dari paket tersebut.
b.	Manajemen Acara
1)	Pendaftaran Event Baru: Memasukkan detail klien, pengantin pria/wanita, tanggal acara, lokasi (venue), link Google Maps, serta kategori acara.
2)	Token QR Otomatis: Generate token akses unik untuk Klien dan Tamu beserta opsi untuk mengaktifkan/menonaktifkan akses QR tersebut.
3)	Penugasan Kru (Event Crews): Menugaskan kru tertentu ke suatu event dan menentukan siapa yang menjadi pimpinan proyek (Leader).
c.	Event Planning Tools
1)	Susunan Acara (Event Rundown): Penyusunan jadwal acara per hari, lengkap dengan waktu mulai, waktu selesai, dan aktivitas/kegiatan.
2)	Manajemen Tugas (Event Todos): Pembuatan daftar tugas yang harus diselesaikan, dilengkapi dengan tanggal tenggat (due date) dan kru yang bertanggung jawab.
3)	Manajemen Vendor (Event Vendors): Menghubungkan vendor eksternal (Catering, Dekorasi, Fotografi, dll.) ke event yang bersangkutan.
4)	Catatan Event (Event Notes): Penyimpanan catatan internal atau informasi tambahan untuk koordinasi kru.
d.	Sistem Pembayaran
1)	Penerbitan Invoice: Invoice otomatis untuk setiap pemesanan event.
2)	Pencatatan Pembayaran: Pelacakan riwayat pembayaran (DP, angsuran, pelunasan), metode pembayaran, tanggal bayar, dokumen bukti bayar, dan status pembayaran (Pending, Approved, Rejected).
3)	Integrasi Online Payment (Opsional/Sesuai Skema): Tersedianya kolom untuk opsi integrasi pembayaran online (seperti Midtrans).
e.	Portal Klien
Fitur ini diakses secara publik menggunakan token unik QR Code tanpa memerlukan login akun:
1)	Halaman Undangan: Informasi dasar pengantin dan detail acara.
2)	Halaman Rundown: Susunan acara real-time untuk tamu dan klien.
3)	Halaman Dokumentasi: Galeri foto atau video dokumentasi acara.
4)	Formulir Buku Tamu digital (Guest Book): Pengisian nama dan alamat oleh tamu saat menghadiri acara.
5)	Halaman Testimoni & Rating: Tamu dapat memberikan umpan balik dan rating kepuasan setelah acara selesai.
