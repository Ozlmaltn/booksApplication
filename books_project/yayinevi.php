<?php
include('config.php');
include('header.php');
include('body.php');

$sql = "SELECT * FROM kitaplar";
$result = $conn->query($sql);
$books = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<div class="container mt-5">
    <form method="POST" action="" style="margin-top: 250px;">
        <h1 style="color: white;">Yayınevi ve Kitap İsimleri Ekle</h1>
        <div class="mb-3">
            <label for="kitap" class="form-label text-white">Kitaplar</label>
            <select class="form-select" id="kitap" name="kitap_id" required>
                <option value="" disabled selected>Kitap Seçin</option>
                <?php foreach ($books as $kitaplar): ?>
                    <option value="<?= $kitaplar['id'] ?>"><?= htmlspecialchars($kitaplar['ad']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="yayin" class="form-label text-white">Yayınevi</label>
            <select class="form-select" name="yayinevi_id" id="yayin" required>
                <option value="" disabled selected>Yayınevi Seçin</option>
                <?php foreach ($yayins as $yayınevleri): ?>
                    <option value="<?= $yayınevleri['yayinevi_id'] ?>"><?= htmlspecialchars($yayınevleri['ad']) ?></option>
                <?php endforeach; ?>
            </select><br>

            <label for="stok" class="form-label text-white">Stok</label>
            <input type="number" name="stok" class="form-control" required>

            <label for="fiyat" class="form-label text-white">Fiyat</label>
            <input type="number" name="fiyat" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Kaydet</button>
    </form>

    <?php
    if (!empty($_SESSION['kayitSonucu'])) {
        foreach ($_SESSION['kayitSonucu'] as $sonuc) {
            if ($sonuc['success']) {
                echo "<div class='alert alert-success'>{$sonuc['message']}</div>";
            } else {
                echo "<div class='alert alert-danger'>{$sonuc['message']}</div>";
            }
        }

        // Kayıt sonucları oturumdan temizleniyor
        unset($_SESSION['kayitSonucu']);
    }
    ?>



</div>


<?php
include('footer.php');
?>
</body>

</html>