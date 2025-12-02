<?php
/**
 * project/index.php - Router Utama
 * Memastikan alur Login -> Dashboard
 */

// 1. Mulai Sesi
session_start();

// 2. Definisikan Base Path
$base_path = 'modules/';

// 3. Dapatkan parameter 'page'. Default ke 'dashboard'
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// 4. LOGIKA PENGAMANAN (GUARD CLAUSE)
$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'];
$is_auth_page = ($page == 'auth/login' || $page == 'auth/logout');

// Jika belum login DAN mengakses halaman selain Login/Logout
if (!$is_logged_in && !$is_auth_page) {
    header('Location: index.php?page=auth/login');
    exit();
} 

// Jika sudah login DAN mencoba mengakses halaman Login, arahkan ke dashboard
if ($is_logged_in && $page == 'auth/login') {
    header('Location: index.php?page=dashboard');
    exit();
}

// 5. Tentukan path file konten
$content_file = '';
if ($page === 'dashboard') {
    $content_file = 'views/dashboard.php';
} else {
    $safe_page = preg_replace('/[^a-z0-9\/\-_]/i', '', $page);
    $content_file = $base_path . $safe_page . '.php';
}

// 6. Muat Header
require_once('views/header.php');

// 7. Muat Konten
echo '<div class="content-wrapper">'; 

if (file_exists($content_file)) {
    // Muat konfigurasi database hanya jika file konten yang membutuhkan dimuat
    require_once('config/database.php');
    require_once($content_file);
} else {
    // Halaman 404
    echo '<h2>404 Not Found</h2>';
    echo '<p>Halaman <code>' . htmlspecialchars($page) . '</code> tidak ditemukan.</p>';
}

echo '</div>'; 

// 8. Muat Footer
require_once('views/footer.php');
?>