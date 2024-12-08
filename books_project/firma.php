<?php
include('config.php');
include('header.php');
include('body.php');
// Veritabanı bağlantısını kontrol et
if (!$conn) {
    die("Veritabanına bağlanılamadı: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $yayineviAd = $_POST['yayinevi_ad'];

    if (empty($yayineviAd)) {
        echo "Yayınevi adı boş olamaz.";
    } else {
        $query = "INSERT INTO yayınevleri (ad) VALUES (?)";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("s", $yayineviAd);
            if ($stmt->execute()) {
                echo "Yayınevi başarıyla eklendi!";
            } else {
                echo "Yayınevi ekleme hatası: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "SQL sorgusu hazırlama hatası: " . $conn->error;
        }
    }
}
?>

<div class="container mt-5">

    <form method="POST" action="" style="margin-top: 200px; margin-bottom: 400px;">
        <h1 style="color: white;">Yayınevi Ekle</h1>
        <div class="mb-3">
            <label for="yayinevi_ad" class="form-label text-white">Yayınevi Adı</label>
            <input type="text" class="form-control" id="yayinevi_ad" name="yayinevi_ad" required>
        </div>
        <button type="submit" class="btn btn-primary">Yayınevi Ekle</button>
    </form>
</div>

<?php include('footer.php'); ?>