# Sequence Diagram Pengelolaan Data (Admin)

Diagram ini menggambarkan alur proses pengelolaan data (Tambah, Edit, Hapus) untuk entitas seperti Crews, Package, dan Vendor yang dilakukan oleh aktor Admin.

```mermaid
sequenceDiagram
    autonumber
    actor Admin
    participant View as View (UI)
    participant Controller
    participant Model
    participant Database

    rect rgb(230, 240, 255)
    Note right of Admin: Scenario: Tambah Data (Create)
    Admin->>View: Klik tombol "Tambah Data"
    View->>Controller: Request Form Tambah (GET /resource/create)
    Controller-->>View: Tampilkan Form Tambah
    Admin->>View: Isi data pada form & Submit
    View->>Controller: Kirim Data Baru (POST /resource)
    Controller->>Model: Validasi & create(data)
    Model->>Database: INSERT Data
    Database-->>Model: Success/Result
    Model-->>Controller: Model Instance
    Controller-->>View: Redirect dengan Pesan Sukses
    end

    rect rgb(230, 255, 230)
    Note right of Admin: Scenario: Edit Data (Update)
    Admin->>View: Klik tombol "Edit Data"
    View->>Controller: Request Form Edit (GET /resource/{id}/edit)
    Controller->>Model: find({id})
    Model->>Database: SELECT Data by ID
    Database-->>Model: Return Data
    Model-->>Controller: Model Instance
    Controller-->>View: Tampilkan Form Edit beserta Data Lama
    Admin->>View: Ubah data pada form & Submit
    View->>Controller: Kirim Data Perubahan (PUT /resource/{id})
    Controller->>Model: Validasi & update(data)
    Model->>Database: UPDATE Data
    Database-->>Model: Success/Result
    Model-->>Controller: Success
    Controller-->>View: Redirect dengan Pesan Sukses
    end

    rect rgb(255, 230, 230)
    Note right of Admin: Scenario: Hapus Data (Delete)
    Admin->>View: Klik tombol "Hapus Data" & Konfirmasi
    View->>Controller: Request Hapus Data (DELETE /resource/{id})
    Controller->>Model: find({id}) & delete()
    Model->>Database: DELETE Data
    Database-->>Model: Success/Result
    Model-->>Controller: Success
    Controller-->>View: Redirect dengan Pesan Sukses
    end
```
