<?php
// Veritabanı bağlantısı
include('config.php');
include('header.php');
include('body.php');

// Veritabanı bağlantısını kontrol et
if (!$conn) {
    die("Veritabanına bağlanılamadı: " . mysqli_connect_error());
}

// Kitaplar, yayınevleri ve stok bilgilerini çekmek için JOIN sorgusu
$query = "
    SELECT kitaplar.id AS kitap_id, kitaplar.ad AS kitap_ad, kitaplar.fiyat AS kitaplar_fiyat, kitaplar.stok AS kitap_stok, yayınevleri.ad AS yayinevi_ad
    FROM kitaplar
    JOIN yayınevleri ON kitaplar.yayinevi_id = yayınevleri.yayinevi_id;
";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Sorgu başarısız: " . mysqli_error($conn));
}

?>

<div class="container" style="margin-top: 150px;">
    <h1>Kitap Listesi</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Kitap Adı</th>
                <th>Fiyat</th>
                <th>Stok</th>
                <th>Yayınevi</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['kitap_ad']); ?></td> <!-- Kitap Adı -->
                    <td><?php echo htmlspecialchars($row['kitaplar_fiyat']); ?></td> <!-- Kitap Fiyatı -->
                    <td><?php echo htmlspecialchars($row['kitap_stok']); ?></td> <!-- Stok -->
                    <td><?php echo htmlspecialchars($row['yayinevi_ad']); ?></td> <!-- Yayınevi -->
                    <td>
                        <form method="POST" action=""> <input type="hidden" name="kitap_id" value="<?= $row['kitap_id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm mr-2">Sil</button>
                        </form>
                        <a href="kitapGuncelle.php?kitap_id=<?= $row['kitap_id'] ?>"
                            class="btn btn-success btn-sm">Güncelle</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php
include('footer.php');
?>