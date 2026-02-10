<?php
session_start();
include '../database/conn.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Note: In production, consider using prepared statements for security
    $query = mysqli_query($conn, "SELECT * FROM admin WHERE nama = '$username' AND password = '$password'");
    $cek = mysqli_num_rows($query);

    if ($cek > 0) {
        $data = mysqli_fetch_assoc($query);
        $_SESSION['id'] = $data['id'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['status'] = "login";
        header("Location: index.php");
        exit;
    } else {
        $error_msg = "Username atau password salah!";
    }
}

if (isset($_SESSION['status']) && $_SESSION['status'] == "login") {
    header("Location: index.php");
    exit;
} else if (isset($_SESSION["status"]) && $_SESSION["status"] == "logout") {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal - SMP PGRI 3 BOGOR</title>
    <!-- Cache busting to ensure new styles load immediately -->
    <link rel="stylesheet" href="../css/style.css?v=<?= time(); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="../img/pgri3-photoroom.png">
</head>

<body>

    <div class="login-universe">
        <!-- Blue Ocean Background Elements -->
        <div class="login-bg-shapes">
            <div class="login-bubble lb-1"></div>
            <div class="login-bubble lb-2"></div>
            <div class="login-bubble lb-3"></div>
            <div class="login-bubble lb-4"></div>
        </div>

        <div class="login-container-pro">
            <div class="login-card-glass" id="glassCard">
                <div class="card-glow"></div>

                <div class="login-header-pro">
                    <div class="logo-circle">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h2>Admin Portal</h2>
                    <p>login akun anda untuk masuk ke admin dashboard</p>
                </div>

                <form method="POST" action="login.php">
                    <div class="pro-input-group">
                        <input type="text" name="username" id="username" class="pro-input" placeholder=" " required autocomplete="off">
                        <i class="fas fa-user pro-input-icon"></i>
                        <label for="username" class="pro-label">Username</label>
                    </div>

                    <div class="pro-input-group">
                        <input type="password" name="password" id="password" class="pro-input" placeholder=" " required>
                        <i class="fas fa-lock pro-input-icon"></i>
                        <label for="password" class="pro-label">Password</label>
                        <i class="fas fa-eye toggle-password-pro" id="togglePassword"></i>
                    </div>

                    <?php if (isset($error_msg)): ?>
                        <div class="login-error-msg">
                            <i class="fas fa-exclamation-circle"></i>
                            <?= $error_msg; ?>
                        </div>
                    <?php endif; ?>

                    <button type="submit" name="login" class="btn-pro">
                        <i class="fas fa-sign-in-alt" style="margin-right: 8px;"></i> Access Dashboard
                    </button>
                </form>

                <a href="../index.php" class="home-link-pro">
                    <i class="fas fa-chevron-left" style="margin-right: 5px;"></i> Back to Main Website
                </a>
            </div>
        </div>
    </div>

    <script>
        // Password Toggle
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        if (togglePassword) {
            togglePassword.addEventListener('click', function(e) {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.classList.toggle('fa-eye-slash');
            });
        }
    </script>
</body>

</html>