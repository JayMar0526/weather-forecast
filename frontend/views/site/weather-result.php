<div class="weather-result">
    <h1><?= $currentWeather->city->country.', '.$currentWeather->city->name ?></h1>
    <h4>3-day Forecast</h4>
    <div class="row">
        <?php foreach ($dailyForecasts as $forecast): ?>
            <div class="col-md-4">
                <div class="card text-center">
                    <img src="<?= $forecast->weather->getIconUrl() ?>" alt="Avatar" style="width:50%;">
                    <div class="card-container">
                        <h4><?= $forecast->time->day->format('Y-m-d D') ?></h4>
                        <h2><?=  strtoupper($forecast->weather->description) ?></h2>
                        <p><?= 'Max: '.$forecast->temperature->max.'  Min: '.$currentWeather->temperature->min  ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="row">
        <div class="col-md-6">
            <h2>Map</h4>
            <div id="map"></div>
        </div>
        <div class="col-md-6">
            <h2>Today Weather</h2>
            <table class="table table-bordered">
                <tr>
                    <td>Wind</td>
                    <td><?= $currentWeather->wind->speed .' | '. $currentWeather->wind->direction ?></td>
                </tr>
                <tr>
                    <td>Cloudiness</td>
                    <td><?= $currentWeather->clouds ?></td>
                </tr>
                <tr>
                    <td>Pressure</td>
                    <td><?= $currentWeather->pressure ?></td>
                </tr>
                <tr>
                    <td>Humidity</td>
                    <td><?= $currentWeather->humidity ?></td>
                </tr>
                <tr>
                    <td>Precipitation</td>
                    <td><?= $currentWeather->precipitation ?></td>
                </tr>
                <tr>
                    <td>Geo coords</td>
                    <td><?= '['.$currentWeather->city->lat.','.$currentWeather->city->lon.']' ?></td>
                </tr>
            </table>
            <h2>Ultra Violet Index</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>UV Index</th>
                    </tr>
                </thead>
                <?php foreach ($forecastuvIndex as $fcuvindex) : ?>
                <tr>
                    <td><?= $fcuvindex->time->format('r')?></td>
                    <td><?= $fcuvindex->uvIndex ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
</div>
<?php
    $lat = json_encode($currentWeather->city->lat);
    $lon = json_encode($currentWeather->city->lon);
    $this->registerJs("
        var curLat = {$lat}
        var curLon =  {$lon}

        // Create the script tag, set the appropriate attributes
        var script = document.createElement('script');
        script.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyBhxgtNWqapfsVfy26cvuXifczK1KQRjn0&callback=initMap';
        script.defer = true;
        script.async = true;

        // Attach your callback function to the `window` object
        window.initMap = function() {
            // The location of inputted location
            var uluru = {lat: curLat, lng: curLon};
            // The map, centered at desired location
            var map = new google.maps.Map(
                document.getElementById('map'), {zoom: 12, center: uluru});
            // The marker, positioned at Uluru
            var marker = new google.maps.Marker({position: uluru, map: map});
        };

        // Append the 'script' element to 'head'
        document.head.appendChild(script);
    ");

    
?>
