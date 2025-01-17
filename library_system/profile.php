<?php
session_start();
require 'db.php';

$user_id = $_SESSION['user_id'];  // Mendapatkan ID pengguna dari session

// Ambil data pengguna dari database
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Mengatur pesan sukses atau error jika ada perubahan
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_name = $_POST['name'];
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];
    $new_phone = $_POST['phone'];
    $new_photo = $_FILES['photo']['name'];

    // Validasi input nama, username, dan email
    if (empty($new_name) || empty($new_username) || empty($new_email)) {
        $error = 'Nama, Username, dan Email tidak boleh kosong.';
    } else {
        // Update data pengguna termasuk email dan phone
        $update_query = "UPDATE users SET name = ?, username = ?, email = ?, phone = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssssi", $new_name, $new_username, $new_email, $new_phone, $user_id);
        
        if ($stmt->execute()) {
            $success = 'Profil berhasil diperbarui.';
        } else {
            $error = 'Terjadi kesalahan saat memperbarui profil.';
        }

        // Jika ada foto yang diunggah
        if (!empty($new_photo)) {
            $photo_target = 'uploads/' . basename($new_photo);
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $photo_target)) {
                // Update foto profil jika berhasil
                $update_photo_query = "UPDATE users SET photo = ? WHERE id = ?";
                $stmt = $conn->prepare($update_photo_query);
                $stmt->bind_param("si", $photo_target, $user_id);
                $stmt->execute();
            } else {
                $error = 'Gagal mengunggah foto.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="profile-container">
        <h1>Profil Saya</h1>

        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="profile.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Nama:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <div class="form-group">
                <label for="phone">Nomor Telepon:</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
            </div>

            <div class="form-group">
                <label for="photo">Foto Profil:</label>
                <input type="file" id="photo" name="photo" accept="image/*">
                <?php if (!empty($user['photo'])): ?>
                    <img src="<?php echo $user['photo']; ?>" alt="Foto Profil" class="profile-photo">
                <?php endif; ?>
            </div>

            <button type="submit">Perbarui Profil</button>
        </form>
        
        <a href="dashboard.php" class="back-btn">Kembali ke Dashboard</a>
    </div>
</body>
</html>
