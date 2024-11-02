<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/header.php';
if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.php");
    exit();
}
?>

<div class="container mt-5">
    <h1 class="text-center">Bienvenido a HamburGeeks</h1>
    <p class="text-center">Descubre las mejores hamburguesas, combos y más.</p>
</div>

<div class="container mt-4 mb-5">
    <div class="row">
        <div class="col-6">
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
