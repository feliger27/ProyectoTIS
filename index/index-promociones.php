<?php
include '../includes/header.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.php");
    exit();
}
?>



<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <div class="logo-container me-3">
            <!-- Asegúrate que la ruta al lobby del menú es correcta -->
            <a class="navbar-brand" href="../index/index-lobby.php">
                <img src="../index/logo-hamburgeeks.png" alt="Logo" width="30" height="30"> HamburGeeks
            </a>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="../index/index-menu.php">Menú</a></li>
                <li class="nav-item"><a class="nav-link" href="../index/index-promociones.php">Promociones</a></li>
                <li class="nav-item"><a class="nav-link" href="../index/index-perfil.php">Mi Cuenta</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5" style="padding-top: 80px;"> <!-- Ajusta el padding para no solapar el contenido con la navbar -->
    <h1 class="text-center">Promociones</h1>
    <div class="row">
        <!-- Tarjeta de promoción de ejemplo -->
        <div class="col-md-4">
            <div class="card mb-4">
                <img src="promo1.jpg" class="card-img-top" alt="Promoción 1">
                <div class="card-body">
                    <h5 class="card-title">Combo Especial</h5>
                    <p class="card-text">Incluye hamburguesa doble, papas fritas y bebida.</p>
                    <p class="card-text"><strong>$99.90</strong></p>
                </div>
            </div>
        </div>
        <!-- Puedes repetir las tarjetas para más promociones dinámicamente aquí -->
    </div>
</div>


<?php
include '../includes/footer.php';
?>
