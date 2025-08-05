# 🔧 Hướng dẫn Setup WebSocket cho Location Tracking

## ❗ LỖI HIỆN TẠI

Bạn gặp lỗi "You must pass your app key when you instantiate Pusher" vì thiếu cấu hình WebSocket.

## 🚀 GIẢI QUYẾT TỪNG BƯỚC

### Bước 1: Thêm Environment Variables

Copy nội dung từ file `ENV_CONFIG_REQUIRED.txt` và **dán vào cuối file `.env`** của bạn:

```env
# Broadcast Configuration
BROADCAST_CONNECTION=reverb

# Laravel Reverb Configuration
REVERB_APP_ID=local-app
REVERB_APP_KEY=ogfyrRCX8uVRd1dccWt8lzd/tLO5QavL2n+xRrALEEw=
REVERB_APP_SECRET=HVPDqUMHVZ01bh1bUMorQ2LdCnyYg8z3oS7dYgddy14=
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

# Queue Configuration (cần thiết cho broadcasting)
QUEUE_CONNECTION=database

# Pusher Configuration (fallback - giống với Reverb)
PUSHER_APP_ID=local-app
PUSHER_APP_KEY=ogfyrRCX8uVRd1dccWt8lzd/tLO5QavL2n+xRrALEEw=
PUSHER_APP_SECRET=HVPDqUMHVZ01bh1bUMorQ2LdCnyYg8z3oS7dYgddy14=
PUSHER_HOST=localhost
PUSHER_PORT=8080
PUSHER_SCHEME=http

# Vite Configuration (cho frontend)
VITE_REVERB_APP_KEY=ogfyrRCX8uVRd1dccWt8lzd/tLO5QavL2n+xRrALEEw=
VITE_REVERB_HOST=localhost
VITE_REVERB_PORT=8080
VITE_REVERB_SCHEME=http
```

### Bước 2: Cài đặt Package Pusher (cần thiết)

```bash
composer require pusher/pusher-php-server
```

### Bước 3: Tạo Queue Tables

```bash
php artisan queue:table
php artisan queue:failed-table
php artisan migrate
```

### Bước 4: Build Frontend Assets

```bash
npm run build
# hoặc
npm run dev
```

### Bước 5: Khởi động Services (3 terminal riêng biệt)

```bash
# Terminal 1: Laravel Reverb WebSocket Server
php artisan reverb:start

# Terminal 2: Queue Worker (xử lý broadcasting)
php artisan queue:work

# Terminal 3: Laravel Development Server
php artisan serve
```

## 🧪 KIỂM TRA HOẠT ĐỘNG

1. **Login với account demo:**

   - Leader: leader@example.com / password
   - Captain: captain1@example.com / password

2. **Test flow:**
   - Captain login → vào Location Tracking → ấn "Bắt đầu theo dõi"
   - Leader login (tab khác) → xem dashboard → sẽ thấy captain realtime

## ⚠️ LƯU Ý QUAN TRỌNG

- **Cần MapBox Token**: Để hiển thị bản đồ, bạn cần đăng ký tài khoản MapBox (miễn phí) và thêm token vào `VITE_MAPBOX_TOKEN`
- **HTTPS trong Production**: WebSocket cần HTTPS khi deploy production
- **Browser Permissions**: Browser cần cấp quyền truy cập location

## 🔍 TROUBLESHOOTING

### Nếu vẫn lỗi "app key":

1. Kiểm tra file .env đã thêm đủ variables chưa
2. Restart server: `php artisan serve`
3. Clear cache: `php artisan config:clear`

### Nếu WebSocket không kết nối:

1. Kiểm tra Reverb server đang chạy
2. Kiểm tra port 8080 không bị chiếm
3. Kiểm tra firewall không block port 8080
