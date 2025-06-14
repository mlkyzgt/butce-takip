# AI Sohbet Kayıtları - Bütçe Takip Uygulaması

---

### Kullanıcı:
PHP kayıt formunda kullanıcı adı ve email benzersizliğini nasıl kontrol ederim?

### ChatGPT:
Kayıt işleminden önce veritabanında `SELECT` sorgusu ile hem username hem email'in var olup olmadığını sorgular, varsa hata mesajı döndürürsünüz. Ayrıca veritabanı UNIQUE kısıtlaması olması hata durumunu da önler.

---

### Kullanıcı:
PDO ile veritabanına bağlanırken charset belirtmek neden önemli?

### ChatGPT:
Charset belirtmek, veritabanıyla iletişimde karakter kodlamasının doğru yapılmasını sağlar. Özellikle Türkçe gibi özel karakterler içeriyorsa, `utf8mb4` kullanmak verilerin doğru saklanması ve gösterilmesi için kritik öneme sahiptir.

---

### Kullanıcı:
Kullanıcı şifrelerini nasıl güvenli şekilde saklarım?

### ChatGPT:
PHP'nin `password_hash()` fonksiyonunu kullanarak şifreleri hash’leyip veritabanına kaydedebilirsiniz. Girişte ise `password_verify()` ile kontrol yaparsınız. Bu yöntem şifrelerin düz metin saklanmasını önler.

---

### Kullanıcı:
Kayıt olurken formda hangi validasyonları yapmalıyım?

### ChatGPT:
- Tüm alanların boş olmaması  
- Email formatının doğruluğu (filter_var ile)  
- Şifre uzunluğunun yeterli olması (en az 6 karakter önerilir)  
- Kullanıcı adı ve email’in benzersiz olması

---

### Kullanıcı:
Kullanıcı oturumu nasıl oluşturulur ve korunur?

### ChatGPT:
`session_start()` ile oturumu başlatır, giriş başarılı olunca kullanıcı bilgilerini `$_SESSION` değişkenine kaydedersiniz. Sayfaların başında session kontrolü yaparak yetkisiz erişimi engellersiniz.

---

### Kullanıcı:
Gelir ve gider işlemlerini nasıl ilişkilendiririm?

### ChatGPT:
`transactions` tablosunda her işlem için `user_id` alanı bulunur. Böylece her kullanıcının sadece kendi işlemleri listelenir ve yönetilir.

---

### Kullanıcı:
Bootstrap ile form tasarımını nasıl daha modern yapabilirim?

### ChatGPT:
Bootstrap 5’in hazır form sınıflarını kullanabilir, grid sistemiyle responsive düzen kurabilir, buton ve inputlara ekstra `rounded` ve `shadow` gibi sınıflar ekleyebilirsiniz.

---

### Kullanıcı:
Uygulamayı GitHub'a yüklerken hangi dosyaları gizlemeliyim?

### ChatGPT:
`config.php` gibi içinde hassas bilgiler olan dosyaları `.gitignore` dosyasıyla gizlemelisiniz. Ayrıca `node_modules` veya `vendor` klasörleri varsa onlar da eklenir.

---

### Kullanıcı:
Hata ayıklama ve loglama için ne önerirsin?

### ChatGPT:
PDO’da hata modunu `ERRMODE_EXCEPTION` yaparak hata yakalayabilirsiniz. Ayrıca özel hata günlük dosyaları oluşturabilir, hata mesajlarını kullanıcıya göstermeyip loglayabilirsiniz.

---

