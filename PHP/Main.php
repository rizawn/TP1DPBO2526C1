<?php
require_once __DIR__ . '/Film.php';
session_start();

// Session menyimpan array berisi objek Film, tanpa database.
if (!isset($_SESSION['dataFilm'])) {
    $_SESSION['dataFilm'] = [];
}
$data = &$_SESSION['dataFilm'];
$pesan = $_SESSION['pesan'] ?? '';
unset($_SESSION['pesan']);

function bacaTeks($input, $nama) {
    return isset($input[$nama]) && is_string($input[$nama]) ? trim($input[$nama]) : '';
}

function cariIndex($data, $idFilm) {
    for ($i = 0; $i < count($data); $i++) {
        if ($data[$i]->getId() === $idFilm) return $i;
    }
    return -1;
}

function aman($teks) {
    return htmlspecialchars((string)$teks, ENT_QUOTES, 'UTF-8');
}

function simpanGambar($gambarLama, &$pesan) {
    $file = $_FILES['gambar'] ?? null;
    if ($file === null || (isset($file['error']) && $file['error'] === UPLOAD_ERR_NO_FILE)) {
        if ($gambarLama !== '') return $gambarLama;
        $pesan = 'Pilih gambar film terlebih dahulu.';
        return false;
    }
    if (!is_array($file) || !isset($file['error'], $file['tmp_name'])
        || $file['error'] !== UPLOAD_ERR_OK || !is_string($file['tmp_name'])
        || !is_uploaded_file($file['tmp_name'])) {
        $pesan = 'Gambar gagal diunggah.';
        return false;
    }
    // Periksa isi gambar dan tentukan ekstensi, bukan memakai nama dari pengguna.
    $info = @getimagesize($file['tmp_name']);
    $ekstensi = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif'];
    if ($info === false || !isset($ekstensi[$info['mime']])) {
        $pesan = 'Gambar harus berupa JPG, PNG, atau GIF.';
        return false;
    }
    $folder = __DIR__ . '/images';
    if (!is_dir($folder) && !mkdir($folder, 0755)) {
        $pesan = 'Folder gambar tidak dapat dibuat.';
        return false;
    }
    $path = 'images/' . uniqid('film_', true) . '.' . $ekstensi[$info['mime']];
    if (!move_uploaded_file($file['tmp_name'], __DIR__ . '/' . $path)) {
        $pesan = 'Gambar gagal disimpan.';
        return false;
    }
    return $path;
}

$edit = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = bacaTeks($_POST, 'aksi');
    $idFilm = bacaTeks($_POST, 'id_film');
    $posisi = cariIndex($data, $idFilm);
    $berhasil = false;

    if ($idFilm === '') {
        $pesan = 'ID film tidak boleh kosong.';
    } elseif ($aksi === 'hapus') {
        if ($posisi === -1) {
            $pesan = 'Film tidak ditemukan.';
        } else {
            array_splice($data, $posisi, 1);
            $pesan = 'Film berhasil dihapus.';
            $berhasil = true;
        }
    } elseif ($aksi === 'tambah' || $aksi === 'update') {
        $judul = bacaTeks($_POST, 'judul');
        $genre = bacaTeks($_POST, 'genre');
        $durasi = bacaTeks($_POST, 'durasi');
        if ($aksi === 'update' && $posisi !== -1) $edit = $data[$posisi];

        if ($aksi === 'tambah' && $posisi !== -1) {
            $pesan = 'ID sudah digunakan.';
        } elseif ($aksi === 'update' && $posisi === -1) {
            $pesan = 'Film tidak ditemukan.';
        } elseif ($judul === '' || $genre === '') {
            $pesan = 'Judul dan genre tidak boleh kosong.';
        } elseif (!preg_match('/^[0-9]{1,3}$/D', $durasi) || (int)$durasi < 1) {
            $pesan = 'Durasi harus bilangan bulat 1-999.';
        } else {
            $gambarLama = $aksi === 'update' ? $data[$posisi]->getGambar() : '';
            $gambar = simpanGambar($gambarLama, $pesan);
            if ($gambar !== false) {
                if ($aksi === 'tambah') {
                    $data[] = new Film($idFilm, $judul, $genre, (int)$durasi, $gambar);
                    $pesan = 'Film berhasil ditambahkan.';
                } else {
                    $data[$posisi]->setJudul($judul);
                    $data[$posisi]->setGenre($genre);
                    $data[$posisi]->setDurasi((int)$durasi);
                    $data[$posisi]->setGambar($gambar);
                    $pesan = 'Film berhasil diupdate.';
                }
                $berhasil = true;
            }
        }
    } else {
        $pesan = 'Aksi tidak tersedia.';
    }

    // Redirect agar refresh halaman tidak mengulangi pengiriman form.
    if ($berhasil) {
        $_SESSION['pesan'] = $pesan;
        header('Location: Main.php');
        exit;
    }
}

if (isset($_GET['edit_id'])) {
    $posisi = cariIndex($data, bacaTeks($_GET, 'edit_id'));
    if ($posisi === -1) $pesan = 'Film tidak ditemukan.';
    else $edit = $data[$posisi];
}

$hasil = $data;
if (isset($_GET['cari_id'])) {
    $posisi = cariIndex($data, bacaTeks($_GET, 'cari_id'));
    $hasil = [];
    if ($posisi === -1) $pesan = 'Film tidak ditemukan.';
    else $hasil[] = $data[$posisi];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Film Bioskop</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 24px auto; padding: 0 16px; }
        label { display: block; margin: 12px 0; }
        input, button { padding: 6px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #bbb; padding: 10px; text-align: left; }
        th { background: #eee; }
        img { max-width: 100px; max-height: 140px; }
        td form { display: inline; }
    </style>
</head>
<body>
    <h1>Data Film Bioskop</h1>
    <?php if ($pesan !== ''): ?><p><?= aman($pesan) ?></p><?php endif; ?>

    <h2><?= $edit !== null ? 'Update Film' : 'Tambah Film' ?></h2>
    <form action="Main.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="aksi" value="<?= $edit !== null ? 'update' : 'tambah' ?>">
        <label>ID film:
            <input name="id_film" value="<?= $edit !== null ? aman($edit->getId()) : '' ?>"
                   <?= $edit !== null ? 'readonly' : '' ?> required>
        </label>
        <label>Judul:
            <input name="judul" value="<?= $edit !== null ? aman($edit->getJudul()) : '' ?>" required>
        </label>
        <label>Genre:
            <input name="genre" value="<?= $edit !== null ? aman($edit->getGenre()) : '' ?>" required>
        </label>
        <label>Durasi (menit):
            <input type="number" name="durasi" min="1" max="999" step="1"
                   value="<?= $edit !== null ? aman($edit->getDurasi()) : '' ?>" required>
        </label>
        <label>Gambar (JPG, PNG, GIF):
            <input type="file" name="gambar" accept="image/jpeg,image/png,image/gif"
                   <?= $edit === null ? 'required' : '' ?>>
        </label>
        <?php if ($edit !== null): ?>
            <p>Kosongkan gambar jika tetap memakai gambar lama.</p>
            <img src="<?= aman($edit->getGambar()) ?>" alt="Gambar film saat ini">
        <?php endif; ?>
        <button type="submit"><?= $edit !== null ? 'Update' : 'Tambah' ?></button>
        <?php if ($edit !== null): ?><a href="Main.php">Batal</a><?php endif; ?>
    </form>

    <h2>Daftar Film</h2>
    <form action="Main.php" method="get">
        <label>Cari berdasarkan ID:
            <input name="cari_id" value="<?= aman(bacaTeks($_GET, 'cari_id')) ?>" required>
        </label>
        <button type="submit">Cari</button>
        <a href="Main.php">Tampilkan semua</a>
    </form>
    <table>
        <thead><tr><th>ID</th><th>Judul</th><th>Genre</th><th>Durasi</th><th>Gambar</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php if (count($hasil) === 0): ?>
            <tr><td colspan="6">Data masih kosong.</td></tr>
        <?php endif; ?>
        <?php foreach ($hasil as $film): ?>
            <tr>
                <td><?= aman($film->getId()) ?></td>
                <td><?= aman($film->getJudul()) ?></td>
                <td><?= aman($film->getGenre()) ?></td>
                <td><?= aman($film->getDurasi()) ?> menit</td>
                <td><img src="<?= aman($film->getGambar()) ?>" alt="<?= aman($film->getJudul()) ?>"></td>
                <td>
                    <a href="Main.php?edit_id=<?= rawurlencode($film->getId()) ?>">Update</a>
                    <form action="Main.php" method="post">
                        <input type="hidden" name="aksi" value="hapus">
                        <input type="hidden" name="id_film" value="<?= aman($film->getId()) ?>">
                        <button type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
