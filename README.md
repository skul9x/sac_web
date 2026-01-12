# 🔋 SAC Web - Quản lý Pin Feliz

Giao diện web quản lý pin xe điện Feliz với Tailwind CSS. Được thiết kế để chạy trên hosting miễn phí (free.nf).

## 📁 Cấu trúc

```
sac_web/
├── index.php           # Giao diện chính với Tailwind CSS
├── functions.php       # Logic xử lý dữ liệu và API
├── battery_data.json   # Lưu trữ dữ liệu pin (auto-generated)
└── .gitignore
```

## 🚀 Deploy

1. Upload `index.php` và `functions.php` lên hosting
2. File `battery_data.json` sẽ tự động được tạo

## 📊 Dữ liệu

```json
{
    "ah_now": 77,
    "ah_70": 147
}
```

- `ah_now`: Số Ah tổng đã đi (đọc từ ODO pin)
- `ah_70`: Mốc hết pin (0%) - tăng thêm 70 mỗi lần sạc đầy

## ⚡ Tính năng

### Cập nhật & Sạc đầy
- Cập nhật `ah_now` và `ah_70`
- Tính hiệu suất km/Ah khi nhập quãng đường
- Nút "Sạc đầy" (+70Ah)

### Tính toán & Tiên lượng
- ⏱️ Ước tính thời gian sạc đầy
- 💡 Tính dòng sạc cần thiết để đầy đúng giờ
- 📈 Mô phỏng % pin sau khi sạc

### Thao tác nâng cao
- 🔌 Cộng Ah sạc lẻ (sạc một phần)

## 🔗 API Endpoints

| Action | Method | Params |
|--------|--------|--------|
| Đọc dữ liệu | GET | `battery_data.json` |
| Cập nhật | POST | `update_main`, `ah_now`, `ah_70` |
| Sạc đầy | POST | `add_70` |
| Cộng Ah lẻ | POST | `add_custom_ah`, `charging_current`, `start_time`, `end_time` |

---
Made with ❤️ for Feliz riders
