<?php
   // get the long and lat from Url
   if(isset($_GET['long']) && isset($_GET['lat']) && !empty($_GET['long']) && !empty($_GET['lat']) && isset($_GET['address']) && !empty(['address']) && is_numeric($_GET['long']) && is_numeric($_GET['lat'])) {
        $long = $_GET['long'];
        $lat = $_GET['lat'];
        $address = $_GET['address'];
    }
    else{
        header("Location: filming_in_paris.php");
    }
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8" />
        <title>Leaflet map</title>
        <style>#map{ height: 500px }</style>
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/leaflet@1.3.3/dist/leaflet.css">
        <script src='https://unpkg.com/leaflet@1.3.3/dist/leaflet.js'></script>
    </head>
    <body>
        <div id="map"></div>
        <script>
            var map = L.map('map').setView([<?php echo $lat;?>, <?php echo $long;?>], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            L.marker([<?php echo $lat;?>, <?php echo $long;?>]).addTo(map)
            .bindPopup('<?php echo $address; ?>')
            .openPopup();
        </script>                
        <div id="map"></div>
                    