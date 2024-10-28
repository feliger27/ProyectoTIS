<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HamburGeeks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .logo-container {
            background-color: #ffa500;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <div class="logo-container me-3">
            <a class="navbar-brand" href="#"><img src="../index/logo-hamburgeeks.png" alt="Logo" width="30" height="30"> HamburGeeks</a>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#">Menú</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Promociones</a></li>
                <li class="nav-item"><a class="nav-link" href="../index/index-perfil.php">Mi Cuenta</a></li>
                <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-cart"></i></a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container" style="padding-top: 60px;">
<!-- El contenido de cada página comenzará aquí -->
