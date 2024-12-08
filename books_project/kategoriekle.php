<?php
include('config.php');
include('header.php');
include('body.php');

// Veritabanı bağlantısını kontrol et
if (!$conn) {
    die("Veritabanına bağlanılamadı: " . mysqli_connect_error());
} else {
    echo "Veritabanı bağlantısı başarılı!<br>";
}

// Form gönderildiyse işlemleri yap
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kategoriAd = $_POST['kategori_ad'];

    // Kategori adı boş mu kontrol et
    if (empty($kategoriAd)) {
        echo "Kategori adı boş olamaz.";
    } else {
        $query = "INSERT INTO kategoriler (ad) VALUES (?)";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("s", $kategoriAd);
            if ($stmt->execute()) {
                echo "Kategori başarıyla eklendi!";
            } else {
                echo "Kategori ekleme hatası: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "SQL sorgusu hazırlama hatası: " . $conn->error;
        }
    }
}
?>

<div class="container mt-5">
    <form method="POST" action="" style="margin-top: 150px;">

        <div class="mb-3">
            <h1 style="color: white;">Kategori Ekle</h1>

            <label for="kategori_ad" class="form-label text-white">Kategori Adı</label>
            <input type="text" class="form-control" id="kategori_ad" name="kategori_ad" required>
        </div>
        <button type="submit" class="btn btn-primary">Kategori Ekle</button>
    </form>
</div>

<?php
include('footer.php');
?>
</body>

</html>