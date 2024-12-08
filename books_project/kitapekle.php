<?php

include("config.php");
include("header.php");
include("body.php");
include("footer.php");

$sql = "SELECT * FROM kategoriler";
$result = $conn->query($sql);
$categories = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();

?>


<body>
    <div class="container mt-5">

        <form action="kitap.php" method="POST" style="margin-top: 150px;">
            <h1 class="text-white">Kitap Ekle</h1>
            <div class="mb-3">
                <label for="kitapAdi" class="form-label text-white">Kitap Adı</label>
                <input type="text" class="form-control" id="kitapAdi" name="kitapAdi" required>
            </div>
            <div class="mb-3">
                <label for="yazar" class="form-label text-white">Yazar</label>
                <input type="text" class="form-control" id="yazar" name="yazar">
            </div>
            <div class="mb-3">
                <label for="yayinevleri" class="form-label color text-white">Yayınevi</label>
                <input type="text" class="form-control" id="yayinevleri" name="yayinevleri">

            </div>
            <div class="mb-3">
                <label for="fiyat" class="form-label text-white">Fiyat</label>
                <input type="number" class="form-control" id="fiyat" name="fiyat" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="kategori" class="form-label  text-white">Kategori</label>
                <select class="form-select " id="kategori" name="kategori" required>
                    <option value="" disabled selected>Kategori Seçin</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['kategori_id'] ?>"><?= htmlspecialchars($category['ad']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Kitap Ekle</button>
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
</body>

</html>