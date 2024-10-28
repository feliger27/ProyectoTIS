<?php
include '../includes/header.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.php");
    exit();
}
?>

<div class="container mt-5">
    <h1 class="text-center">Nuestro Menú</h1>
    <div class="row">
        <!-- Tarjeta de producto de ejemplo -->
        <div class="col-md-4">
            <div class="card mb-4">
                <img src="burger.jpg" class="card-img-top" alt="Hamburguesa Clásica">
                <div class="card-body">
                    <h5 class="card-title">Hamburguesa Clásica</h5>
                    <p class="card-text">Carne 100% de res, lechuga, tomate y queso.</p>
                    <p class="card-text"><strong>$49.90</strong></p>
                </div>
            </div>
        </div>
        <!-- Repite las tarjetas para más productos -->
    </div>
</div>

<?php
include '../includes/footer.php';
?>
