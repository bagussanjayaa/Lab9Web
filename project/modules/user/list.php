<?php
if (!isset($conn)) {
    die("<p style='color:red;'>Error: Koneksi database tidak tersedia. Pastikan config/database.php telah dimuat di index.php.</p>");
}

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    echo "<p>Akses ditolak. Silakan login.</p>";
    return;
}

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
        $sql = "SELECT id_barang, kategori, nama, gambar, harga_beli, harga_jual, stok FROM data_barang ORDER BY id_barang DESC";
        $result = $conn->query($sql); 

        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                
                $raw_path = $row['gambar'];
                $gambar_tag = 'Tidak Ada Gambar';

                if ($raw_path) {
                    if (strpos($raw_path, $base_image_path) === 0) {
                        $final_path = $raw_path; 
                    } else {
                        $final_path = $base_image_path . basename($raw_path);
                    }
                    $gambar_tag = '<img src="' . htmlspecialchars($final_path) . '" alt="' . htmlspecialchars($row['nama']) . '">';
                }
                
                $harga_jual_rp = 'Rp. ' . number_format($row['harga_jual'], 0, ',', '.');
                $harga_beli_rp = 'Rp. ' . number_format($row['harga_beli'], 0, ',', '.');
                
                echo "<tr>";
                
                echo "<td>" . $gambar_tag . "</td>";
                
                echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                
                echo "<td>" . htmlspecialchars($row['kategori']) . "</td>";
                
                echo "<td>" . $harga_jual_rp . "</td>";
                
                echo "<td>" . $harga_beli_rp . "</td>";
                
                echo "<td>" . htmlspecialchars($row['stok']) . "</td>";
                
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