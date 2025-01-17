<?php
session_start();
require 'db.php';

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $id = $_POST['id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $description = $_POST['description'];
    $rack_number = $_POST['rack_number'];  // Ambil nomor letak rak

    // Validasi input
    if (empty($id) || empty($title) || empty($author) || empty($year) || empty($description) || empty($rack_number)) {
        $error = "Semua kolom harus diisi!";
    } else {
        // Prepare query untuk insert buku
        $query = "INSERT INTO books (id, title, author, year, description, rack_number) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssss", $id, $title, $author, $year, $description, $rack_number); // Bind parameter untuk rack_number

        // Eksekusi query
        if ($stmt->execute()) {
            $success = "Buku berhasil ditambahkan!";
        } else {
            $error = "Gagal menambahkan buku. Coba lagi!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Buku - Perpustakaan</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="input-book-container">
        <h1>Input Buku Baru</h1>
        
        <!-- Success/Error Message -->
        <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        
        <form action="add_book.php" method="POST">
            <div class="form-group">
                <label for="id">ID Buku</label>
                <input type="number" id="id" name="id" required>
            </div>    

            <div class="form-group">
                <label for="title">Judul Buku</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="author">Pengarang</label>
                <input type="text" id="author" name="author" required>
            </div>

            <div class="form-group">
                <label for="year">Tahun Terbit</label>
                <input type="number" id="year" name="year" required>
            </div>

            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" required></textarea>
            </div>

            <!-- Kolom Nomor Letak Rak -->
            <div class="form-group">
                <label for="rack_number">Nomor Letak Rak</label>
                <input type="text" id="rack_number" name="rack_number" required>
            </div>

            <button type="submit">Tambah Buku</button>
        </form>

        <!-- Kembali ke Dashboard Button -->
        <?php if ($success): ?>
            <a href="dashboard.php" class="back-btn">Kembali ke Dashboard</a>
        <?php endif; ?>
    </div>
</body>
</html>
