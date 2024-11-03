<?php
// Iniciamos sesión si aún no se ha hecho
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/header.php'; // Incluimos el header en la página


$usuarioLogueado = isset($_SESSION['username']);
?>

<div class="container mt-5">
    <h1 class="text-center">Bienvenido a HamburGeeks</h1>
    <p class="text-center">Descubre las mejores hamburguesas, combos y más.</p>

    <?php if ($usuarioLogueado): ?>
        <div class="alert alert-info text-center">¡Hola, <?= htmlspecialchars($_SESSION['username']); ?>! Disfruta de nuestras deliciosas ofertas.</div>
    <?php endif; ?>
</div>

<div class="container mt-4 mb-5">
    <div class="row">
        <div class="col-6">
            <!-- Contenedor para el mapa -->
            <div id="map" style="width: 100%; height: 300px;"></div>
        </div>
    </div>
</div>

<script>
function initMap() {
    var hamburgeeks = {lat: -36.916083, lng: -73.029912}; 
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 10,
        center: hamburgeeks
    });
    var marker = new google.maps.Marker({
        position: hamburgeeks,
        map: map
    });
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDdYo2KvUW0fJayyfWMazSpdXeFDZHQaM&callback=initMap" async defer></script>


<?php
include '../includes/footer.php'; 
?>

