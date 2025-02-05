# 📌 Rezervasyon & Bilet Sistemi

## 📖 Uygulama Tanıtımı
Bu uygulama, etkinlikler için rezervasyon ve biletleme işlemlerini yöneten bir **Laravel 11** tabanlı **RESTful API** projesidir. Kullanıcılar etkinliklere rezervasyon yapabilir, bilet satın alabilir ve biletlerini transfer edebilir. Ayrıca sistem, **JWT authentication**, **rate limiting**, **otomatik rezervasyon süresi yönetimi** gibi gelişmiş özellikler içerir.

---

## 🚀 Kullanılan Teknolojiler
- **PHP**
- **Laravel 11 Framework**
- **MySQL**
- **RESTful API standartları**
- **JWT (JSON Web Token) authentication**

---

## 🛠️ Kurulum Talimatları

### 1️⃣ Projeyi Klonlayın
```bash
git clone https://github.com/mexikali/ticket-reservation-api.git
cd ticket-reservation-api/ticket-reservation-api
```

### 2️⃣ Gerekli Bağımlılıkları Kurun
```bash
composer install
```

### 3️⃣ Çevre Değişkenlerini Ayarlayın (.env)
```bash
cp .env.example .env
```
Daha sonra **.env** dosyanızı açarak **veritabanı bilgilerinizi** güncelleyin.

### 4️⃣ Uygulama Anahtarını Oluşturun
```bash
php artisan key:generate
```

### 5️⃣ Veritabanını Migrasyon ve Seeder ile Hazırlayın
```bash
php artisan migrate --seed
```
Bu komut:
- ✅ Gerekli tabloları oluşturur
- ✅ Varsayılan admin kullanıcısını ve örnek verileri ekler

### 6️⃣ Uygulamayı Çalıştırın
```bash
php artisan serve
```
API **http://127.0.0.1:8000** adresinde çalışacaktır.

---

## 🧪 Testler
```bash
php artisan test
```
Tüm testlerin başarılı olduğundan emin olun! ✅

---

## ⏳ Zamanlanmış İşlerin Çalıştırılması (Schedule Work)
Sistem, rezervasyonların süresini kontrol eden ve **15 dakika dolduğunda otomatik olarak koltukları serbest bırakan** bir schedule job içerir.
```bash
php artisan schedule:work
```
Alternatif olarak Linux **crontab** içine şu satırı ekleyebilirsiniz:
```bash
* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
```
Bu sayede rezervasyonlar **otomatik olarak süre sonunda iptal edilecektir**.

---

## 📜 API Kullanımı
Tüm API uç noktalarına erişmek için **Scramble** dokümantasyonu mevcuttur.

🔗 **API Dokümantasyonu:** [http://127.0.0.1:8000/docs/api](http://127.0.0.1:8000/docs/api)

---

## 🔐 Authentication API  
| HTTP Method | Endpoint | Açıklama |  
|------------|---------|----------|  
| POST | `/api/auth/register` | Kullanıcı kaydı oluşturur |  
| POST | `/api/auth/login` | Kullanıcı giriş yapar ve JWT token alır |  
| POST | `/api/auth/refresh` | JWT token yeniler |  
| POST | `/api/auth/logout` | Kullanıcı çıkış yapar |  

---

## 🎟️ Etkinlik İşlemleri  
| HTTP Method | Endpoint | Açıklama |  
|------------|---------|----------|  
| GET | `/api/events` | Tüm etkinlikleri listeler |  
| GET | `/api/events/{id}` | Belirli bir etkinliği getirir |  
| POST | `/api/events` | Yeni etkinlik oluşturur (Sadece Admin) |  
| PUT | `/api/events/{id}` | Etkinlik bilgilerini günceller (Sadece Admin) |  
| DELETE | `/api/events/{id}` | Etkinliği siler (Sadece Admin) |  

---

## 💺 Koltuk İşlemleri  
| HTTP Method | Endpoint | Açıklama |  
|------------|---------|----------|  
| GET | `/api/events/{id}/seats` | Belirli bir etkinliğin koltuklarını listeler |  
| GET | `/api/venues/{id}/seats` | Belirli bir mekanın koltuklarını listeler |  
| POST | `/api/seats/block` | Belirli koltukları geçici olarak rezerve eder |  
| DELETE | `/api/seats/release` | Bloke edilen koltukları serbest bırakır |  

---

## 📌 Rezervasyon İşlemleri  
| HTTP Method | Endpoint | Açıklama |  
|------------|---------|----------|  
| POST | `/api/reservations` | Yeni rezervasyon oluşturur |  
| GET | `/api/reservations` | Kullanıcının tüm rezervasyonlarını listeler |  
| GET | `/api/reservations/{id}` | Belirli bir rezervasyonu getirir |  
| POST | `/api/reservations/{id}/confirm` | Rezervasyonu onaylar ve bileti oluşturur |  
| DELETE | `/api/reservations/{id}` | Rezervasyonu iptal eder |  

---

## 🎫 Bilet İşlemleri  
| HTTP Method | Endpoint | Açıklama |  
|------------|---------|----------|  
| GET | `/api/tickets` | Kullanıcının biletlerini listeler |  
| GET | `/api/tickets/{id}` | Belirli bir bileti getirir |  
| GET | `/api/tickets/{id}/download` | Bileti PDF olarak indirir |  
| POST | `/api/tickets/{id}/transfer` | Bileti başka bir kullanıcıya transfer eder |  

---

## ⚖️ Önemli Kurallar: 

### 1️⃣ Rezervasyon İşlemleri  
- **Koltuk müsaitlik kontrolü yapılmalı.**  
- **Rezervasyon süresi 15 dakika olmalı.**  
- **Aynı koltuk eşzamanlı olarak başkası tarafından rezerve edilememeli.**  
- **Rezervasyon süresi dolduğunda koltuklar otomatik olarak serbest bırakılmalı.**  

### 2️⃣ Bilet İşlemleri  
- **Her bilet için benzersiz bir kod oluşturulmalı.**  
- **Bilet transferi sadece kullanılmamış biletler için yapılabilmeli.**  
- **Satın alınan biletler PDF formatında indirilebilmeli.**  
- **Bilet iptali, etkinlik başlangıç tarihinden en az 24 saat öncesine kadar yapılabilmeli.**  

