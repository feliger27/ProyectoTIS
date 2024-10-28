<?php
include '../includes/header.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.php");
    exit();
}
?>

<div class="container mt-5">
    <h1 class="text-center">Bienvenido a HamburGeeks</h1>
    <p class="text-center">Descubre las mejores hamburguesas, combos y más.</p>
</div>

<?php
include '../includes/footer.php';
?>
