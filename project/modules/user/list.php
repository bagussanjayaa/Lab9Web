<?php
/**
 * project/modules/user/list.php - Menampilkan Daftar Data Barang
 * Telah direvisi untuk mengandalkan koneksi global ($conn) dan menggunakan logika gambar.
 */

// Pastikan koneksi database tersedia (dari index.php)
if (!isset($conn)) {
    die("<p style='color:red;'>Error: Koneksi database tidak tersedia. Pastikan config/database.php telah dimuat di index.php.</p>");
}

// Cek hak akses
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    echo "<p>Akses ditolak. Silakan login.</p>";
    return;
}

// Definisikan path gambar dasar yang sama seperti di add.php
$base_image_path = 'assets/gambar/';
?>

<h2>Data Barang</h2>
<p><a href="index.php?page=user/add">Tambah Barang</a></p>

<table>
    <thead>
        <tr>
            <th>Gambar</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Harga Jual</th>
            <th>Harga Beli</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Ambil data dari tabel data_barang
        $sql = "SELECT id_barang, kategori, nama, gambar, harga_beli, harga_jual, stok FROM data_barang ORDER BY id_barang DESC";
        $result = $conn->query($sql); // Menggunakan variabel $conn yang telah dimuat

        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                
                // --- LOGIKA GAMBAR ---
                $raw_path = $row['gambar'];
                $gambar_tag = 'Tidak Ada Gambar';

                if ($raw_path) {
                    // Logic untuk mengatasi inkonsistensi path (misal hanya nama file)
                    if (strpos($raw_path, $base_image_path) === 0) {
                        $final_path = $raw_path; // Path sudah lengkap
                    } else {
                        // Tambahkan prefix jika hanya nama file yang tersimpan
                        $final_path = $base_image_path . basename($raw_path);
                    }
                    $gambar_tag = '<img src="' . htmlspecialchars($final_path) . '" alt="' . htmlspecialchars($row['nama']) . '">';
                }
                
                // --- LOGIKA HARGA ---
                $harga_jual_rp = 'Rp. ' . number_format($row['harga_jual'], 0, ',', '.');
                $harga_beli_rp = 'Rp. ' . number_format($row['harga_beli'], 0, ',', '.');
                
                echo "<tr>";
                
                // Kolom Gambar: Menampilkan tag <img>
                echo "<td>" . $gambar_tag . "</td>";
                
                // Kolom Nama Barang
                echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                
                // Kolom Kategori
                echo "<td>" . htmlspecialchars($row['kategori']) . "</td>";
                
                // Kolom Harga Jual
                echo "<td>" . $harga_jual_rp . "</td>";
                
                // Kolom Harga Beli
                echo "<td>" . $harga_beli_rp . "</td>";
                
                // Kolom Stok
                echo "<td>" . htmlspecialchars($row['stok']) . "</td>";
                
                // Kolom Aksi
                echo "<td>
                        <a href='index.php?page=user/edit&id=" . $row['id_barang'] . "'>Ubah</a> | 
                        <a href='index.php?page=user/delete&id=" . $row['id_barang'] . "' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>Hapus</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>Tidak ada data barang.</td></tr>";
        }
        ?>
    </tbody>
</table>