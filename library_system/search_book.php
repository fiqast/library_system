<?php
session_start();
require 'db.php';

$search_results = [];
$search_by = '';
$search_value = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search_by = $_POST['search_by'];
    $search_value = $_POST['search_value'];

    // Tidak menambahkan wildcard % jika pencarian ID
    if ($search_by == 'id') {
        // Format pencarian ID agar sesuai
        $search_value = str_pad($search_value, 3, '0', STR_PAD_LEFT);  // Misalnya, ID harus 3 digit
    }

    // Tidak menambahkan wildcard % di sini
    // Cukup menggunakan nilai langsung tanpa wildcard
    $query = "SELECT * FROM books WHERE $search_by LIKE ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $search_value);

    // Eksekusi query
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $search_results = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        echo "Error executing query.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Buku</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="search-book-container">
        <h1>Cari Buku</h1>

        <form action="search_book.php" method="POST">
            <div class="form-group">
                <label for="search_by">Cari Berdasarkan:</label>
                <select id="search_by" name="search_by">
                    <option value="title" <?php echo $search_by == 'title' ? 'selected' : ''; ?>>Judul</option>
                    <option value="author" <?php echo $search_by == 'author' ? 'selected' : ''; ?>>Pengarang</option>
                    <option value="year" <?php echo $search_by == 'year' ? 'selected' : ''; ?>>Tahun Terbit</option>
                    <option value="id" <?php echo $search_by == 'id' ? 'selected' : ''; ?>>ID Buku</option>
                </select>
            </div>

            <div class="form-group">
                <label for="search_value">Masukkan Kata Kunci:</label>
                <input type="text" id="search_value" name="search_value" value="<?php echo htmlspecialchars($search_value); ?>" required>
            </div>

            <button type="submit">Cari Buku</button>
        </form>

        <?php if (!empty($search_results)): ?>
            <h2>Hasil Pencarian:</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID Buku</th>
                        <th>Judul Buku</th>
                        <th>Pengarang</th>
                        <th>Tahun Terbit</th>
                        <th>Deskripsi</th>
                        <th>Nomor Letak Rak</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($search_results as $book): ?>
                        <tr>
                            <td><?php echo $book['id']; ?></td>
                            <td><?php echo htmlspecialchars($book['title']); ?></td>
                            <td><?php echo htmlspecialchars($book['author']); ?></td>
                            <td><?php echo htmlspecialchars($book['year']); ?></td>
                            <td><?php echo htmlspecialchars($book['description']); ?></td>
                            <td><?php echo htmlspecialchars($book['rack_number']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif (isset($search_results)): ?>
            <p>Data tidak ditemukan.</p>
        <?php endif; ?>
    </div>
</body>
</html>
