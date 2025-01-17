<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Ambil statistik jumlah buku
$query = "SELECT COUNT(*) AS total_books FROM books";
$result = $conn->query($query);
$total_books = $result->fetch_assoc()['total_books'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Perpustakaan</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <header>
            <h1>Selamat Datang, <?php echo $_SESSION['name']; ?>!</h1>
            <nav>
                <ul>
                    <li><a href="add_book.php">Input Buku</a></li>
                    <li><a href="search_book.php">Cari Buku</a></li>
                    <li><a href="view_book.php">Lihat Buku</a></li>
                    <li><a href="profile.php">Profil Saya</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>
        <main>
            <section class="stats">
                <h2>Statistik</h2>
                <p>Total Buku dalam Perpustakaan: <strong><?php echo $total_books; ?></strong></p>
            </section>
            <section class="features">
                <h2>Fitur Utama</h2>
                <div class="feature-card">
                    <h3>Input Buku</h3>
                    <p>Tambahkan buku baru ke dalam perpustakaan dengan mudah.</p>
                    <a href="add_book.php" class="btn">Tambah Buku</a>
                </div>
                <div class="feature-card">
                    <h3>Cari Buku</h3>
                    <p>Temukan buku yang Anda butuhkan dengan cepat.</p>
                    <a href="search_book.php" class="btn">Cari Buku</a>
                </div>
                <div class="feature-card">
                    <h3>Lihat Semua Buku</h3>
                    <p>Lihat daftar semua buku yang tersedia di perpustakaan.</p>
                    <a href="view_book.php" class="btn">Lihat Buku</a>
                </div>
            </section>
        </main>
        <footer>
            <p>&copy; 2025 Perpustakaan Digital. Semua Hak Dilindungi.</p>
        </footer>
    </div>
</body>
</html>
