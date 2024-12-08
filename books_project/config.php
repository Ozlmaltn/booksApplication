<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

//db bağlantısı
$host = 'localhost';
$dbname = 'book_application';
$username = 'root';
$password = '';

// Veritabanı bağlantısını başlatıyoruz
$conn = mysqli_connect($host, $username, $password, $dbname);

// Bağlantı kontrolü
if (!$conn) {
    die("Veritabanına bağlanılamadı: " . mysqli_connect_error());
}
$conn->set_charset("utf8");


// Yayınevlerini veritabanından çekmek için SQL sorgusu
$sqlYayin = "SELECT yayinevi_id, ad FROM yayınevleri"; // yayinevleri tablosundan yayınevleri verisini alıyoruz
$resultYayin = $conn->query($sqlYayin);

// Yayınevleri verilerini kontrol etme
if ($resultYayin) {
    $yayins = $resultYayin->fetch_all(MYSQLI_ASSOC);
} else {
    die("Yayınevi verileri alınamadı: " . $conn->error);
}

// Kitapları çekmek için SQL sorgusu
$sqlKitap = "SELECT id, ad FROM kitaplar";
$resultKitap = $conn->query($sqlKitap);
$books = $resultKitap->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['kitapAdi'], $_POST['fiyat'], $_POST['kategori'])) {
        $kitapAdi = $_POST['kitapAdi'];
        $fiyat = $_POST['fiyat'];
        $kategori = $_POST['kategori'];

        // Hata kontrolü
        if (empty($kitapAdi)) {
            $_SESSION['errors']['kitapAdi'] = 'Kitap adı boş olamaz.';
        }

        if (empty($fiyat) || $fiyat <= 0) {
            $_SESSION['errors']['fiyat'] = 'Fiyat geçerli bir değer olmalıdır.';
        }
        if (empty($kategori)) {
            $_SESSION['errors']['kategori'] = 'Kategori seçilmelidir.';
        }

        // Hata varsa işlem yapılmaz ve bilgi sayfasına yönlendirilir
        if (!empty($_SESSION['errors'])) {
            header("Location: information.php");
            exit();
        }

        $sql = "INSERT INTO kitaplar (ad,Fiyat,kategori_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sii", $kitapAdi, $fiyat, $kategori);
            if ($stmt->execute()) {
                $_SESSION['kayitSonucu'][] = ['success' => true, 'message' => "Kitap başarıyla eklendi!"];
            } else {
                $_SESSION['kayitSonucu'][] = ['success' => false, 'message' => "Kitap ekleme hatası: " . $stmt->error];
            }
            $stmt->close();
        } else {
            $_SESSION['kayitSonucu'][] = ['success' => false, 'message' => "SQL sorgusu hazırlama hatası: " . $conn->error];
        }
        header("Location: kitapekle.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['kitap_id'], $_POST['yayinevi_id'], $_POST['stok'], $_POST['fiyat'])) {
            $kitap_id = $_POST['kitap_id'];
            $yayinevi_id = $_POST['yayinevi_id'];
            $stok = $_POST['stok'];
            $fiyat = $_POST['fiyat'];

            // SQL sorgusuyla veritabanına ekleme işlemi
            $sql = "INSERT INTO yayin (kitap_id, yayinevi_id, stok, fiyat) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("iiii", $kitap_id, $yayinevi_id, $stok, $fiyat);
                if ($stmt->execute()) {
                    $_SESSION['kayitSonucu'][] = ['success' => true, 'message' => "Yayın başarıyla eklendi!"];
                } else {
                    $_SESSION['kayitSonucu'][] = ['success' => false, 'message' => "Yayın ekleme hatası: " . $stmt->error];
                }
                $stmt->close();
            } else {
                $_SESSION['kayitSonucu'][] = ['success' => false, 'message' => "SQL sorgusu hazırlama hatası: " . $conn->error];
            }
            header("Location: yayinevi.php");
            exit();
        }
    }



}

?>