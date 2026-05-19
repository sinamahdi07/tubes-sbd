# Normalisasi Database PlayMart ke 3NF

Dokumen ini bisa dipakai saat demo untuk menjelaskan bahwa struktur database PlayMart sudah disusun ke Third Normal Form (3NF).

## Prinsip 3NF Yang Dipakai

1. Setiap tabel menyimpan satu jenis entitas atau relasi.
2. Setiap kolom non-key bergantung langsung pada primary key tabelnya.
3. Tidak ada repeating group di satu kolom.
4. Tidak ada ketergantungan transitif antar kolom non-key.
5. Data turunan seperti total pembayaran tidak disimpan sebagai kolom permanen, tetapi dihitung dari tabel detail.

## Entitas Utama

| Tabel | Primary Key | Fungsi |
| --- | --- | --- |
| `users` | `id` | Data akun user/admin |
| `developers` | `developer_id` | Master developer |
| `publishers` | `publisher_id` | Master publisher |
| `games` | `game_id` | Data inti game |
| `game_details` | `game_detail_id` | Detail tambahan 1 game |
| `genres` | `genre_id` | Master genre |
| `categories` | `category_id` | Master category |
| `platforms` | `platform_id` | Master platform |
| `game_screenshots` | `screenshot_id` | Screenshot milik game |
| `game_trailers` | `trailer_id` | Trailer milik game |
| `carts` | `id` | Game yang ada di cart user |
| `payments` | `id` | Header transaksi |
| `payment_items` | `id` | Detail game yang dibeli |
| `game_reviews` | `id` | Review user untuk game |
| `friendships` | `id` | Relasi pertemanan user |

## Relasi Many-to-Many

Relasi many-to-many tidak disimpan sebagai list di satu kolom. Semua dipisah menjadi pivot table:

| Pivot Table | Relasi |
| --- | --- |
| `game_genres` | `games` ke `genres` |
| `game_categories` | `games` ke `categories` |
| `game_platforms` | `games` ke `platforms` |

## Bukti Normalisasi

### Games

Tabel `games` hanya menyimpan data inti game:

- `title`
- `description`
- `price`
- `release_date`
- `thumbnail_url`
- `developer_id`
- `publisher_id`

Nama developer dan publisher tidak disimpan berulang di `games`; keduanya dipisah ke tabel master `developers` dan `publishers`.

### Genre, Category, Platform

Genre, category, dan platform tidak disimpan langsung di `games` karena satu game bisa punya banyak genre/category/platform. Karena itu relasinya dipindah ke tabel pivot:

- `game_genres`
- `game_categories`
- `game_platforms`

Ini memenuhi 1NF dan 3NF karena tidak ada multi-value attribute di tabel `games`.

### Game Details

Kolom detail seperti `discount`, `short_description`, `website`, dan `minimum_requirements` dipisah ke `game_details`, karena ini detail tambahan dari satu game.

Relasi:

`games.game_id` -> `game_details.game_id`

### Screenshot dan Trailer

Screenshot dan trailer dipisah ke tabel sendiri:

- `game_screenshots`
- `game_trailers`

Tabel lama `screenshots` sudah tidak dipakai dan dihapus oleh migration normalisasi supaya tidak ada duplikasi konsep.

### Cart

Cart punya unique constraint:

`user_id + game_id`

Artinya satu user hanya bisa punya satu baris cart untuk satu game. Ini sesuai aturan aplikasi bahwa user tidak bisa menambahkan game yang sama berkali-kali.

### Payments

Tabel `payments` hanya menyimpan header transaksi:

- `user_id`
- `payment_code`
- `method`
- `status`
- `paid_at`

Kolom turunan seperti `subtotal`, `discount_total`, dan `total` sudah tidak disimpan di tabel `payments`.

Total transaksi dihitung dari `payment_items`:

`unit_price * quantity * (1 - discount_percent / 100)`

Dengan begitu, tidak ada data turunan yang menyebabkan anomali update.

### Payment Items

Tabel `payment_items` menyimpan detail item transaksi:

- `payment_id`
- `game_id`
- `title`
- `unit_price`
- `discount_percent`
- `quantity`

`title`, `unit_price`, dan `discount_percent` adalah snapshot saat checkout. Ini sengaja disimpan karena data transaksi harus tetap valid walaupun harga/nama game berubah di masa depan.

Kolom turunan `line_total` sudah tidak disimpan permanen dan dihitung oleh aplikasi.

### Reviews

Tabel `game_reviews` menyimpan review berdasarkan relasi user dan game:

- `game_id`
- `user_id`
- `is_recommended`
- `body`

Ada unique constraint `game_id + user_id`, sehingga satu user hanya punya satu review untuk satu game.

## Kesimpulan

Struktur database PlayMart sudah memenuhi 3NF secara praktis:

- Data master dipisah ke tabel masing-masing.
- Relasi many-to-many memakai pivot table.
- Detail transaksi dipisah dari header transaksi.
- Data turunan pembayaran tidak disimpan di tabel utama.
- Tabel duplikat screenshot sudah dihapus.
- Constraint unik ditambahkan untuk menjaga aturan bisnis.
