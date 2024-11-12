<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/header.php'; // Ensuring the header with the navbar is included
if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lobby</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        .category-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin: 50px 0;
        }
        .category-card {
            margin: 10px;
            width: 150px;
            cursor: pointer;
            text-align: center;
            transition: transform 0.3s ease-in-out; /* Smooth transition for scale */
        }
        .category-card img {
            width: 100%;
            height: 100px; /* Fixed height for all images */
            object-fit: cover; /* Ensure images cover the area well */
        }
        .category-card h3 {
            margin-top: 5px;
            font-size: 14px;
        }
        .category-card:hover {
            transform: scale(1.1); /* Scale up card on hover */
        }
        #menu {
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="text-center mt-5">
        <img src="../uploads/lobby/menu.jpg" alt="Imagen de Carga" id="menu" class="img-fluid">
    </div>

    <div class="category-container">
        <div class="category-card" onclick="location.href='index-menu.php#combos';">
            <img src="../uploads/lobby/combo.jpg" alt="Combos">
            <h3>Combos</h3>
        </div>
        <div class="category-card" onclick="location.href='index-menu.php#hamburguesas';">
            <img src="../uploads/lobby/classic.png"  alt="Hamburguesas">
            <h3>Hamburguesas</h3>
        </div>
        <div class="category-card" onclick="location.href='index-menu.php#acompaniamientos';">
            <img src="../uploads/lobby/PAPAS.png" alt="Acompañamientos">
            <h3>Para Acompañar</h3>
        </div>
        <div class="category-card" onclick="location.href='index-menu.php#bebidas';">
            <img src="../uploads/lobby/fanta.png"  alt="Bebidas">
            <h3>Bebidas</h3>
        </div>
        <div class="category-card" onclick="location.href='index-menu.php#postres';">
            <img src="../uploads/lobby/cheescake.jpg"  alt="Postres">
            <h3>Postres</h3>
        </div>
        <!-- Additional categories can be included as necessary -->
    </div>

    <div class="about-us text-center mt-5" style="padding-bottom: 50px;">
        <h2>Sobre Nosotros</h2>
        <p>Somos una empresa dedicada a ofrecer las mejores hamburguesas de la ciudad, utilizando ingredientes frescos y de alta calidad. Nuestro objetivo es brindar una experiencia culinaria única a nuestros clientes, donde cada bocado sea memorable.</p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
