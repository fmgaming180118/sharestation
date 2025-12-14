# 🔌 ShareStation - Platform Peer-to-Peer EV Charging

<div align="center">
  
  [![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
  [![PHP](https://img.shields.io/badge/PHP-8.5-blue.svg)](https://php.net)
  [![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.x-38B2AC.svg)](https://tailwindcss.com)
  [![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
</div>

---

## 📋 Deskripsi Project

**ShareStation** adalah platform peer-to-peer emergency charging per-menit pertama di Indonesia yang dirancang untuk mengatasi keterbatasan infrastruktur SPKLU (Stasiun Pengisian Kendaraan Listrik Umum). Platform ini memungkinkan pemilik EV (Electric Vehicle) untuk berbagi akses charging tanpa investasi besar di infrastruktur, dengan sistem berbasis daya kendaraan listrik dalam hitungan menit.

### 🎯 Tujuan Utama
- Mengatasi anxiety range EV driver dengan menyediakan solusi charging darurat
- Menciptakan peluang penghasilan pasif bagi masyarakat (warga) yang memiliki akses listrik
- Menghubungkan pemilik charging station dengan pengguna EV secara real-time
- Menyediakan sistem booking, payment tracking, dan review yang transparan

---

## 🎥 Video Demo

[![ShareStation Demo](https://img.youtube.com/vi/YOUR_VIDEO_ID/maxresdefault.jpg)](https://youtu.be/YOUR_VIDEO_ID)

**Link YouTube:** []()

> 📝 **Note:** Ganti `YOUR_VIDEO_ID` dengan ID video YouTube Anda

---

## ✨ Fitur Utama

### 👨‍💼 Admin Dashboard
- 📊 Monitoring total users dan active stations
- 👥 User management (CRUD operations)
- 🏢 Station management dengan detail ports
- 📧 Contact message handling
- 📈 Real-time statistics

### 🚗 Driver Dashboard
- 🗺️ Interactive map dengan OpenStreetMap & Leaflet.js
- 📍 Geolocation untuk menemukan stasiun terdekat
- 🔍 Filter stasiun berdasarkan jarak dan ketersediaan
- 📱 Booking charging ports secara real-time
- 🧾 Transaction history dan invoice
- ⭐ Review & rating system

### 🏠 Warga (Owner) Dashboard
- 📊 Income tracking dari charging station
- 🔌 Port management (status, pricing, power output)
- 💰 Transaction monitoring
- 📈 Performance analytics
- 👤 Profile management

---

## 🛠️ Tech Stack

### Backend
- **Framework:** Laravel 12.x
- **Language:** PHP 8.5
- **Database:** MySQL 8.0
- **Authentication:** Laravel Sanctum + Session

### Frontend
- **CSS Framework:** Tailwind CSS 3.x
- **JavaScript:** Vanilla JS + Alpine.js
- **Icons:** Font Awesome 6.4
- **Maps:** Leaflet.js 1.9.4 + OpenStreetMap
- **Build Tool:** Vite 5.x

### Additional Libraries
- **Chart.js** - Data visualization
- **SweetAlert2** - Beautiful alerts
- **Nominatim API** - Reverse geocoding

---

## 📦 Installation Guide

### Prerequisites
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL/MariaDB
- Git

### Step 1: Clone Repository
```bash
git clone https://github.com/fmgaming180118/sharestation.git
cd sharestation
```

### Step 2: Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### Step 3: Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Database Configuration
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sharestation
DB_USERNAME=root
DB_PASSWORD=
```

### Step 5: Run Migrations & Seeders
```bash
# Create database first in MySQL
mysql -u root -e "CREATE DATABASE sharestation"

# Run migrations and seeders
php artisan migrate:fresh --seed
```

### Step 6: Build Assets
```bash
# Development
npm run dev

# Production
npm run build
```

### Step 7: Start Development Server
```bash
php artisan serve
```

Aplikasi akan berjalan di `http://127.0.0.1:8000`

---

## 👤 Default Login Credentials

Setelah menjalankan seeder, gunakan kredensial berikut:

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@sharestation.com | password |
| **Driver** | driver@sharestation.com | password |
| **Warga (Owner)** | warga@sharestation.com | password |

**Additional Test Accounts:**
- **Driver:** driver@test.com / password (Irwansyah)
- **Owner:** owner@test.com / password (Ismail bin mail)

> 📝 **Note:** Semua akun menggunakan password yang sama: `password`

---

## 🗂️ Database Structure

### Users Table
- Role-based access: `admin`, `warga`, `driver`
- Authentication dengan Laravel Hash
- Profile information (name, email, phone, address)

### Stations Table
- Owner/Host information
- Geolocation (latitude, longitude)
- Operational hours & amenities
- Active status tracking

### Ports Table
- Station relationship
- Connector type (Fast Charging, Regular Charging)
- Power output (kW)
- Price per kWh
- Real-time status (available, busy, maintenance)

### Transactions Table
- Booking records
- Duration tracking
- Payment status
- Confirmation codes

### Reviews Table
- Rating (1-5 stars)
- Comments
- User & station relationship

---

## 📁 Project Structure

```
sharestation/
├── app/
│   ├── Http/Controllers/      # All controllers
│   ├── Models/                 # Eloquent models
│   └── Enums/                  # Enum definitions
├── database/
│   ├── migrations/             # Database migrations
│   └── seeders/                # Database seeders
├── resources/
│   ├── views/                  # Blade templates
│   │   ├── admin/              # Admin views
│   │   ├── driver/             # Driver views
│   │   ├── owner/              # Owner views
│   │   └── landingpage/        # Public pages
│   ├── css/                    # Stylesheets
│   └── js/                     # JavaScript files
├── public/
│   ├── storage/icons/          # App icons & images
│   └── storage/images/         # User uploads
├── routes/
│   └── web.php                 # Web routes
└── storage/
    └── app/public/             # Public storage
```

---

## 🚀 Deployment

### Production Build
```bash
# Build frontend assets
npm run build

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
```

### Server Requirements
- PHP >= 8.2 with extensions: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath
- MySQL >= 5.7 / MariaDB >= 10.3
- Composer
- Node.js & NPM

---

## 🐛 Known Issues & Roadmap

### Current Features ✅
- ✅ Multi-role authentication (Admin, Warga, Driver)
- ✅ Interactive map dengan geolocation
- ✅ Real-time station availability
- ✅ Booking & transaction system
- ✅ Review & rating
- ✅ Dynamic data from database

### Roadmap v2.0
- [ ] Payment gateway integration (Midtrans/Xendit)
- [ ] Push notification system
- [ ] Mobile app (Flutter)
- [ ] Real-time port availability dengan WebSocket
- [ ] QR Code scanning untuk booking
- [ ] Multi-language support
- [ ] Advanced analytics dashboard

---

## 🤝 Contributing

Kontribusi sangat diterima! Silakan fork repository ini dan buat pull request untuk perubahan yang Anda usulkan.

### Development Workflow
1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📄 License

This project is licensed under the MIT License.

---

## 👥 Team

**HIMALESS Team** - Babak Semifinal EV Hackathon 2025

---

## 📞 Contact & Support

- **Email:** support@sharestation.com
- **GitHub:** [ShareStation Repository](https://github.com/fmgaming180118/sharestation)

---

## 🙏 Acknowledgments

- Laravel Framework Team
- OpenStreetMap Contributors
- Leaflet.js Community
- Font Awesome & Tailwind CSS Team

---

<div align="center">
  <p>Made with ❤️ by HIMALESS Team</p>
  <p>© 2025 ShareStation. All rights reserved.</p>
</div>
