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

$query = "SELECT kitaplar.id AS kitap_id, kitaplar.ad AS kitap_ad, kitaplar.fiyat AS kitaplar_fiyat, kategoriler.kategori_id, kategoriler.ad AS kategori_ad 
FROM kitaplar 
JOIN kategoriler ON kitaplar.kategori_id = kategoriler.kategori_id";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Sorgu başarısız: " . mysqli_error($conn));
} else {
    echo "Sorgu başarılı!<br>";
}
?>

<div class="container " style="margin-top: 100px;">

    <?php while ($row = $result->fetch_assoc()): ?>
        <h3 class="text-white"><?php echo htmlspecialchars($row['kategori_ad']); ?></h3> <!-- Kategori Adını Yazdır -->
        <table class="table">
            <thead>
                <tr>
                    <th>Kitap Adı</th>
                    <th>Fiyat</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo htmlspecialchars($row['kitap_ad']); ?></td> <!-- Kitap Adı -->
                    <td><?php echo htmlspecialchars($row['kitaplar_fiyat']); ?></td> <!-- Kitap Fiyatı -->
                </tr>
            </tbody>
        </table>
    <?php endwhile; ?>
</div>

<?php
include('footer.php');
?>
</body>

</html>