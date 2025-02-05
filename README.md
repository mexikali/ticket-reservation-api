
    <h1>📌 Rezervasyon & Bilet Sistemi</h1>

    <h2>📖 Uygulama Tanıtımı</h2>
    <p>Bu uygulama, etkinlikler için rezervasyon ve biletleme işlemlerini yöneten bir <strong>Laravel 11</strong> tabanlı <strong>RESTful API</strong> projesidir. Kullanıcılar etkinliklere rezervasyon yapabilir, bilet satın alabilir ve biletlerini transfer edebilir. Ayrıca sistem, <strong>JWT authentication</strong>, <strong>rate limiting</strong>, <strong>otomatik rezervasyon süresi yönetimi</strong> gibi gelişmiş özellikler içerir.</p>

    <hr>

    <h2>🚀 Kullanılan Teknolojiler</h2>
    <ul>
        <li><strong>PHP</strong></li>
        <li><strong>Laravel 11 Framework</strong></li>
        <li><strong>MySQL</strong></li>
        <li><strong>RESTful API standartları</strong></li>
        <li><strong>JWT (JSON Web Token) authentication</strong></li>
    </ul>

    <hr>

    <h2>🛠️ Kurulum Talimatları</h2>

    <h3>1️⃣ Projeyi Klonlayın</h3>
    <pre><code>git clone https://github.com/kullanici/rezervasyon-sistemi.git
cd rezervasyon-sistemi</code></pre>

    <h3>2️⃣ Gerekli Bağımlılıkları Kurun</h3>
    <pre><code>composer install</code></pre>

    <h3>3️⃣ Çevre Değişkenlerini Ayarlayın (.env)</h3>
    <pre><code>cp .env.example .env</code></pre>
    <p>Daha sonra <strong>.env</strong> dosyanızı açarak <strong>veritabanı bilgilerinizi</strong> güncelleyin.</p>

    <h3>4️⃣ Uygulama Anahtarını Oluşturun</h3>
    <pre><code>php artisan key:generate</code></pre>

    <h3>5️⃣ Veritabanını Migrasyon ve Seeder ile Hazırlayın</h3>
    <pre><code>php artisan migrate --seed</code></pre>
    <p>Bu komut:</p>
    <ul>
        <li>✅ Gerekli tabloları oluşturur</li>
        <li>✅ Varsayılan admin kullanıcısını ve örnek verileri ekler</li>
    </ul>

    <h3>6️⃣ Uygulamayı Çalıştırın</h3>
    <pre><code>php artisan serve</code></pre>
    <p>API <strong>http://127.0.0.1:8000</strong> adresinde çalışacaktır.</p>

    <hr>

    <h2>🧪 Testler</h2>
    <pre><code>php artisan test</code></pre>
    <p>Tüm testlerin başarılı olduğundan emin olun! ✅</p>

    <hr>

    <h2>⏳ Zamanlanmış İşlerin Çalıştırılması (Schedule Work)</h2>
    <p>Sistem, rezervasyonların süresini kontrol eden ve <strong>15 dakika dolduğunda otomatik olarak koltukları serbest bırakan</strong> bir schedule job içerir.</p>
    
    <pre><code>php artisan schedule:work</code></pre>

    <p>Alternatif olarak Linux <strong>crontab</strong> içine şu satırı ekleyebilirsiniz:</p>
    <pre><code>* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1</code></pre>

    <p>Bu sayede rezervasyonlar <strong>otomatik olarak süre sonunda iptal edilecektir</strong>.</p>

    <hr>

    <h2>📜 API Kullanımı</h2>
    <p>Tüm API uç noktalarına erişmek için <strong>Swagger/OpenAPI</strong> dokümantasyonu mevcuttur.</p>

    <p>🔗 <strong>API Dokümantasyonu:</strong> <a href="http://127.0.0.1:8000/docs/api">http://127.0.0.1:8000/docs/api</a></p>

    <hr>

    <h2>🔐 Authentication API</h2>
    <table border="1">
        <tr>
            <th>HTTP Method</th>
            <th>Endpoint</th>
            <th>Açıklama</th>
        </tr>
        <tr>
            <td>POST</td>
            <td>/api/auth/register</td>
            <td>Kullanıcı kaydı oluşturur</td>
        </tr>
        <tr>
            <td>POST</td>
            <td>/api/auth/login</td>
            <td>Kullanıcı giriş yapar ve JWT token alır</td>
        </tr>
        <tr>
            <td>POST</td>
            <td>/api/auth/refresh</td>
            <td>JWT token yeniler</td>
        </tr>
        <tr>
            <td>POST</td>
            <td>/api/auth/logout</td>
            <td>Kullanıcı çıkış yapar</td>
        </tr>
    </table>

    <hr>

    <h2>🎫 Bilet İşlemleri</h2>
    <table border="1">
        <tr>
            <th>HTTP Method</th>
            <th>Endpoint</th>
            <th>Açıklama</th>
        </tr>
        <tr>
            <td>GET</td>
            <td>/api/tickets</td>
            <td>Kullanıcının biletlerini listeler</td>
        </tr>
        <tr>
            <td>GET</td>
            <td>/api/tickets/{id}</td>
            <td>Belirli bir bileti getirir</td>
        </tr>
        <tr>
            <td>GET</td>
            <td>/api/tickets/{id}/download</td>
            <td>Bileti PDF olarak indirir</td>
        </tr>
        <tr>
            <td>POST</td>
            <td>/api/tickets/{id}/transfer</td>
            <td>Bileti başka bir kullanıcıya transfer eder</td>
        </tr>
    </table>

    <hr>

    <h2>⚖️ Önemli İş Kuralları</h2>
    <h3>1️⃣ Rezervasyon İşlemleri</h3>
    <ul>
        <li>Koltuk müsaitlik kontrolü yapılmalı</li>
        <li>Rezervasyon süresi <strong>15 dakika</strong> olmalı</li>
        <li>Aynı koltuk eşzamanlı olarak başkası tarafından rezerve edilememeli</li>
        <li>Rezervasyon süresi dolduğunda koltuklar <strong>otomatik olarak serbest bırakılmalı</strong></li>
    </ul>

    <hr>

