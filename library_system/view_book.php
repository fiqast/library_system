<?php
session_start();
require 'db.php';

// Query untuk mengambil semua data buku
$query = "SELECT * FROM books";
$result = $conn->query($query);

// Jika ada buku, tampilkan
if ($result->num_rows > 0) {
    $books = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $books = [];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Semua Buku</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="view-books-container">
        <h1>Lihat Semua Buku</h1>

        <!-- Pesan Sukses atau Gagal -->
        <?php if (isset($_GET['success'])): ?>
            <p class="success">Buku berhasil dihapus!</p>
        <?php elseif (isset($_GET['error'])): ?>
            <p class="error">Gagal menghapus buku. Coba lagi!</p>
        <?php endif; ?>

        <?php if (empty($books)) { ?>
            <!-- Pesan jika belum ada buku di database -->
            <div class="no-books-message">
                <p>Maaf, belum ada buku yang tersedia di perpustakaan!</p>
                <a href="add_book.php" class="btn-add-book">Tambah Buku Baru</a>
            </div>
        <?php } else { ?>
            <!-- Menampilkan daftar buku jika ada -->
            <div class="books-grid">
                <?php foreach ($books as $book) { ?>
                    <div class="book-card">
                        <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                        <p><strong>ID Buku:</strong> <?php echo htmlspecialchars($book['id']); ?></p>
                        <p><strong>Pengarang:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
                        <p><strong>Tahun Terbit:</strong> <?php echo htmlspecialchars($book['year']); ?></p>
                        <p><strong>Deskripsi:</strong> <?php echo htmlspecialchars($book['description']); ?></p>
                        <p><strong>Nomor Letak Rak:</strong> <?php echo htmlspecialchars($book['rack_number']); ?></p>
                        
                        <!-- Tombol Hapus Buku -->
                        <a href="delete_book.php?id=<?php echo $book['id']; ?>" class="delete-btn" onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">Hapus</a>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</body>
</html>
