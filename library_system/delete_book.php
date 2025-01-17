<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $book_id = $_GET['id'];

    // Query untuk menghapus buku
    $query = "DELETE FROM books WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $book_id);  // Mengikat parameter untuk id buku

    if ($stmt->execute()) {
        // Jika berhasil, redirect ke halaman lihat buku
        header("Location: view_book.php?success=1");
        exit;
    } else {
        // Jika gagal
        header("Location: view_book.php?error=1");
        exit;
    }
} else {
    // Jika ID tidak ada
    header("Location: view_book.php");
    exit;
}
