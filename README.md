# 🔋 Quản lý pin Feliz (Feliz Battery Management)

Một ứng dụng web đơn giản, trực quan và tiện ích giúp người lái xe điện Feliz (hoặc các dòng xe tương tự sử dụng chỉ số Ah) theo dõi dung lượng pin thực tế, ước tính hiệu suất và lên kế hoạch sạc pin một cách thông minh.

## 🌟 Tính năng chính

1. **Hiển thị phần trăm pin trực quan**:
   - Tính toán chính xác phần trăm pin dựa trên số Ah đã đi (`ah_now`) và mốc hết pin tương ứng (`ah_70`).
   - Giao diện thanh pin trực quan thay đổi màu sắc theo mức độ pin (Xanh lá > 35%, Vàng 20% - 35%, Đỏ <= 20%).

2. **Cập nhật & Tính hiệu suất (km/Ah)**:
   - Cho phép cập nhật số Ah tổng hiện tại.
   - Nhập quãng đường vừa đi (km) để tự động tính hiệu suất tiêu thụ điện (`km/Ah`) và ước tính tổng quãng đường dự kiến đi được từ khi đầy pin đến khi cạn.
   - Nút **Sạc Đầy** nhanh giúp đặt lại mốc hết pin mới (+70Ah so với số Ah hiện tại).

3. **Tính toán & Tiên lượng thông minh**:
   - **Ước tính thời gian sạc đầy**: Tính toán thời gian cần thiết để sạc đầy pin dựa trên dòng sạc của bộ sạc (A).
   - **Tính dòng sạc cần thiết**: Nhập thời gian muốn đầy pin, ứng dụng sẽ gợi ý dòng sạc (A) phù hợp để pin đầy đúng giờ.
   - **Tính thử % pin sạc được**: Mô phỏng lượng phần trăm pin tăng thêm sau một khoảng thời gian sạc cụ thể với dòng sạc tùy chọn.

4. **Thao tác Nâng cao**:
   - **Cộng Ah sạc lẻ**: Hỗ trợ cộng thêm một lượng Ah nhất định vào mốc dung lượng pin khi sạc nhanh/sạc ngắn giữa chừng mà không muốn đặt lại mốc sạc đầy hoàn toàn.

---

## 🛠️ Công nghệ sử dụng

- **Backend**: PHP (Xử lý logic tính toán, đọc/ghi dữ liệu từ file JSON).
- **Frontend**: HTML5, TailwindCSS (qua CDN), Javascript thuần.
- **Lưu trữ**: Dữ liệu được lưu trữ cục bộ dưới dạng file JSON (`battery_data.json`), không yêu cầu cơ sở dữ liệu phức tạp (SQL/NoSQL).

---

## 📁 Cấu trúc thư mục

```text
.
├── battery_data.json  # File lưu trữ dữ liệu pin hiện tại (chứa ah_now và ah_70)
├── functions.php      # Chứa toàn bộ hàm xử lý logic và tính toán
├── index.php          # Giao diện chính của ứng dụng web
└── README.md          # Hướng dẫn sử dụng dự án
```

---

## 🚀 Hướng dẫn cài đặt và sử dụng

### Yêu cầu hệ thống
- Thiết bị đã cài đặt **PHP 7.4** trở lên.
- Trình chủ web hỗ trợ PHP (ví dụ: Apache, Nginx, hoặc sử dụng PHP Built-in Server).

### Chạy ứng dụng nội bộ (Local Development)

1. Mở Terminal / Command Prompt tại thư mục dự án:
   ```bash
   cd /path/to/sac
   ```

2. Khởi động server PHP built-in:
   ```bash
   php -S localhost:8000
   ```

3. Mở trình duyệt và truy cập:
   ```text
   http://localhost:8000
   ```

---

## 🔒 Bảo mật và Riêng tư

- Ứng dụng đã được cấu hình thuộc tính `autocomplete="off"` ở tất cả các biểu mẫu (forms) trên trang chủ để ngăn trình duyệt tự động lưu và hiển thị lại lịch sử dữ liệu nhập của người dùng.
- Toàn bộ dữ liệu được lưu trữ và xử lý cục bộ trên máy chủ của bạn thông qua tệp tin `battery_data.json`.
