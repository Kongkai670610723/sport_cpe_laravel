# SPORT.CPE — Pure Laravel E-Commerce Application (261342 Database Systems)

> **วิชา**: 261342 Database Systems (ภาควิชาวิศวกรรมคอมพิวเตอร์ คณะวิศวกรรมศาสตร์ มหาวิทยาลัยเชียงใหม่)  
> **กลุ่ม Term Project**: Project 2  
> **สมาชิกในกลุ่ม**:  
> 1. นายณภัทร จิตเจริญ (670610686)  
> 2. นายภูรินท์ อินทจักร์ (670610723)  
> 3. นายวรภพ เกิดทองสุก (670610729)  
> **ฐานข้อมูล**: MySQL 8.0 `sport_cpe_db` (7 ตารางระดับ 3NF: `categories`, `users`, `promotions`, `products`, `promotion_products`, `orders`, `order_items`)  
> **Figma Prototype**: [SportsShop - Low-Fi Prototype (11 Frames)](https://www.figma.com/design/6ETgU6OQVA3jAn5g673JSr/SportsShop---Low-Fi-Prototype?node-id=0-1&t=FEyHQze3KxA35akT-1)

---

## 🛠️ Tech Stack & Architecture (Pure Laravel MVC)
* **Backend Framework**: **Laravel 10.x (PHP 8.1+)**
  * **Controllers**: `app/Http/Controllers/` (`HomeController`, `ProductController`, `CartController`, `CheckoutController`, `OrderController`, `AdminController`, `VoucherController`, `ProfileController`, `AuthController`)
  * **Eloquent ORM Models**: `app/Models/` (`Category`, `Product`, `Promotion`, `Order`, `OrderItem`, `User`)
  * **Routing**: `routes/web.php`
  * **Migrations & Schema**: `database/migrations/2026_10_23_000001_create_sport_cpe_tables.php` และ `database/schema.sql`
* **Frontend Templating**: **Laravel Blade Templates (`.blade.php`) + Tailwind CSS**
  * `resources/views/layouts/app.blade.php` (Master Layout)
  * `resources/views/home.blade.php` (หน้าแรกและแคตตาล็อกสินค้าแนะนำ)
  * `resources/views/products/index.blade.php` & `show.blade.php` (รายการและรายละเอียดสินค้า)
  * `resources/views/cart/index.blade.php` (ตะกร้าสินค้าพร้อมคำนวณโปรโมชัน 2 แถม 1 ฝั่ง Server-side)
  * `resources/views/checkout/index.blade.php` (หน้าสั่งซื้อและคำนวณส่วนลดคูปอง)
  * `resources/views/orders/complete.blade.php` (หน้ารับรองการสั่งซื้อสำเร็จ)
  * `resources/views/profile/show.blade.php` (โปรไฟล์สมาชิกและประวัติคำสั่งซื้อ)
  * `resources/views/vouchers/index.blade.php` (ศูนย์รวมคูปองส่วนลด)
  * `resources/views/admin/dashboard.blade.php` (แดชบอร์ดแอดมิน: KPI, แจ้งเตือนสต็อกต่ำ, ปรับสถานะบิล)
  * `resources/views/auth/login.blade.php` & `register.blade.php`
* **Database**: **MySQL 8.0** (`sport_cpe_db` บน Docker Container พอร์ต `3306`)
* **Development Tools**: DBeaver, Docker Desktop, VS Code, Figma

---

## 🚀 วิธีการติดตั้งและรันโปรเจกต์ (How to Run SPORT.CPE)

### วิธีที่ 1: รันผ่าน Laravel Sail (Docker & Ubuntu WSL2 ตามมาตรฐาน Lab 261343) ✨ แนะนำ
1. เปิด Docker Desktop บน Windows
2. เปิด Terminal **Ubuntu 26.04 (WSL2)** และเข้าไปที่โฟลเดอร์โปรเจกต์:
   ```bash
   cd "/mnt/c/CPE Season 3/Demo/subject/Database-Lecture/project/sport-cpe-laravel"
   ```
3. สั่งรัน Container:
   ```bash
   ./sail up -d
   ```
4. ติดตั้ง dependencies และสร้าง Application Key (ทำเฉพาะครั้งแรก):
   ```bash
   ./sail composer install
   ./sail artisan key:generate
   ```
5. รัน Migration และ Seed ข้อมูล 128 สินค้า:
   ```bash
   ./sail artisan migrate --seed
   ```
6. เปิดเบราว์เซอร์เข้าใช้งานได้ทันทีที่: **`http://localhost:8000`**

---

### วิธีที่ 2: รันแบบ Standalone (กรณีมี PHP 8.1+ และ Composer ในเครื่อง)
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

---

## 🛡️ ฟีเจอร์เด่นด้านวิศวกรรมฐานข้อมูล (Database Engineering Highlights)

### 1. โครงสร้างฐานข้อมูล 3NF (Third Normal Form) 7 ตาราง
* แยกตารางจัดเก็บข้อมูลอย่างเป็นระบบเพื่อขจัดความซ้ำซ้อน (Data Redundancy) และความผิดปกติในการจัดการข้อมูล (Update, Insert, Delete Anomalies)
* ความสัมพันธ์แบบกลุ่มต่อกลุ่ม (M:N) ระหว่าง `promotions` และ `products` ถูกเชื่อมด้วย Junction Table `promotion_products`

### 2. ACID Transactions (ความถูกต้องของธุรกรรมการเงินและสต็อก)
ใน `OrderController.php` ใช้คำสั่ง `DB::transaction()` ครอบการสั่งซื้อ เพื่อรับประกันคุณสมบัติ ACID:
* หากขั้นตอนใดขั้นตอนหนึ่งล้มเหลว (เช่น สินค้าไม่พอ) ระบบจะ Rollback คืนค่าทั้งหมด
* หักลบสต็อกสินค้า (`stock_quantity`) ในตาราง `products` ทันทีที่การสั่งซื้อสำเร็จ

### 3. Server-Side Promotion Calculation (การคำนวณโปรโมชันด้วย PHP)
* โปรซื้อ 2 แถม 1 คำนวณอัตโนมัติใน `CartController` ด้วยฟังก์ชัน `intdiv($quantity, 3) * $price`
* รองรับโค้ดส่วนลด เช่น `SPORT10` (ลด 10%), `SAVE15` (ลด 15%) และ `PERKS20` (ลด 20%)

### 4. Admin Inventory & Order Management
* **Low Stock Alert**: ตรวจจับสินค้าที่มีสต็อก `stock_quantity <= 10` ชิ้น พร้อมฟังก์ชันเติมสต็อกทันที
* **Real-time Order Status**: อัปเดตสถานะบิล (`Pending` ➔ `Paid` ➔ `Shipped` ➔ `Delivered`) ลงฐานข้อมูล MySQL


---

## 🔑 บัญชีสำหรับทดสอบระบบ (Test Accounts)

| บทบาท (Role) | อีเมล (Email) | รหัสผ่าน (Password) | รายละเอียด |
| :--- | :--- | :--- | :--- |
| **Admin (ผู้ดูแลระบบ)** | `admin@sportcpe.com` | `password123` *(หรือ `admin123`)* | เข้าหน้า Admin Dashboard (`http://localhost:8000/admin.html`) เพื่อเติมสต็อก (`+ Restock`), จัดการสินค้าโปร `Buy 2 Get 1 Free`, และอัปเดตสถานะคำสั่งซื้อ |
| **Customer (ลูกค้า)** | `w@gmail.com` | `123456` | บัญชีลูกค้าตั้งต้นสำหรับทดสอบการเก็บคูปอง (`SAVE15`, `PERKS20`, `SPORT10`), ซื้อสินค้าโปร `Buy 2 Get 1 Free`, และสั่งซื้อสินค้าแบบ ACID Transaction |

---
