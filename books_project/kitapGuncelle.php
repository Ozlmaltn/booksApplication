<?php
session_start();

// Çıktı tamponlamayı başlatıyoruz
ob_start();

include("header.php");
include("body.php");
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Veritabanı bağlantısı
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

$kitap_id = isset($_GET['kitap_id']) ? (int) $_GET['kitap_id'] : 0;
$kitap_ad = $kitaplar_fiyat = $kitap_stok = $yayinevi_id = "";

if ($kitap_id) {
    // Kitap bilgilerini veritabanından çek
    $sql = "SELECT id, ad, Fiyat, stok, yayinevi_id FROM kitaplar WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $kitap_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $kitap_ad = $row['ad'];
        $kitaplar_fiyat = $row['Fiyat'];
        $kitap_stok = $row['stok'];
        $yayinevi_id = $row['yayinevi_id'];
    } else {
        $_SESSION['errors'][] = "Kitap bulunamadı!";
        header('Location: kitaplar.php');
        exit();
    }
}

?>

<div class="container" style="margin-top: 150px;margin-bottom: 150px;">
    <h1 class="mb-4 text-white">Kitap Güncelle</h1>
    <?php if (isset($_SESSION['errors'])): ?>
        <div class="alert alert-danger">
            <?php
            foreach ($_SESSION['errors'] as $error) {
                echo "<p>$error</p>";
            }
            unset($_SESSION['errors']);
            ?>
        </div>
    <?php endif; ?>
    <form method="POST" action="">
        <input type="hidden" name="kitap_id" value="<?= $kitap_id ?>">

        <div class="mb-3">
            <label for="kitap_ad" class="form-label text-white">Kitap Adı</label>
            <input type="text" class="form-control" id="kitap_ad" name="kitap_ad"
                value="<?= htmlspecialchars($kitap_ad) ?>" required>
        </div>

        <div class="mb-3">
            <label for="kitaplar_fiyat" class="form-label text-white">Fiyat</label>
            <input type="number" class="form-control" id="kitaplar_fiyat" name="kitaplar_fiyat"
                value="<?= htmlspecialchars($kitaplar_fiyat) ?>" required>
        </div>

        <div class="mb-3">
            <label for="kitap_stok" class="form-label text-white">Stok</label>
            <input type="number" class="form-control" id="kitap_stok" name="kitap_stok"
                value="<?= htmlspecialchars($kitap_stok) ?>" required>
        </div>

        <div class="mb-3">
            <label for="yayinevi_id" class="form-label text-white">Yayınevi</label>
            <select class="form-select" id="yayinevi_id" name="yayinevi_id" required>
                <option value="">Yayınevi Seçin</option>
                <?php
                // Yayınevlerini veritabanından çek
                $sqlYayin = "SELECT yayinevi_id, ad FROM yayınevleri";
                $resultYayin = $conn->query($sqlYayin);

                while ($yayınevi = $resultYayin->fetch_assoc()) {
                    $selected = $yayinevi_id == $yayınevi['yayinevi_id'] ? 'selected' : '';
                    echo "<option value='{$yayınevi['yayinevi_id']}' $selected>" . htmlspecialchars($yayınevi['ad']) . "</option>";
                }
                ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Güncelle</button>
    </form>
</div>

<?php
// Kitap güncellenme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // $_SESSION["kayitSonucu"] dizisini kontrol edip başlatıyoruz
    if (!isset($_SESSION["kayitSonucu"])) {
        $_SESSION["kayitSonucu"] = [];
    }

    if (isset($_POST['kitap_id'], $_POST['kitap_ad'], $_POST['kitaplar_fiyat'], $_POST['kitap_stok'], $_POST['yayinevi_id'])) {
        $kitap_id = (int) $_POST['kitap_id'];
        $kitap_ad = $_POST['kitap_ad'];
        $kitaplar_fiyat = $_POST['kitaplar_fiyat'];
        $kitap_stok = $_POST['kitap_stok'];
        $yayinevi_id = (int) $_POST['yayinevi_id'];

        if (empty($kitap_ad) || empty($kitaplar_fiyat) || empty($kitap_stok) || empty($yayinevi_id)) {
            $_SESSION['errors'][] = "Kitap bilgileri boş olamaz!";
            header("Location: kitapGuncelle.php?kitap_id=$kitap_id");
            exit();
        }

        $sql = "UPDATE kitaplar SET ad = ?, Fiyat = ?, yayinevi_id = ?, stok = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siiii", $kitap_ad, $kitaplar_fiyat, $yayinevi_id, $kitap_stok, $kitap_id);

        if ($stmt->execute()) {
            $_SESSION["kayitSonucu"][] = ["success" => true, "message" => "Kitap başarıyla güncellendi!"];
        } else {
            $_SESSION["kayitSonucu"][] = ["success" => false, "message" => "Kitap güncellenemedi: " . $stmt->error];
        }

        $stmt->close();

        // Yönlendirmeyi doğru şekilde yap
        header("Location: kitaplar.php");
        exit();
    }
}

include("footer.php");

// Çıktı tamponlamayı sonlandırıyoruz
ob_end_flush();
?>