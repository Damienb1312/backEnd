<?php

# Traitement formulaire
if (!empty($_POST['insee'])) {

    # Get meteo informations
    $inseeSelected = ($_POST['insee']);
    $url = "https://api.meteo-concept.com/api/forecast/daily/0?token=a9b814eaf38dd3c21fe83f56700730b9511b1692a27759760e5806dc70caedf8&insee=" . $inseeSelected;
    $data = file_get_contents($url);
    if ($data) {
        $meteoInfos = json_decode($data);
        $city = $meteoInfos->city;

        # Meteo displayed
        $meteoDisplayed = "Aujourd'hui à <strong>{$city->name}</strong>, on prévoit <strong>{$meteoInfos->forecast->rr10}mm</strong>
        (pas plus de {$meteoInfos->forecast->rr1}mm en tous cas) de précipitations.";

        # Day weather icon
        $weatherCode = $meteoInfos->forecast->weather;
        $weatherImage = file_get_contents("animated/weather-$weatherCode.svg");
        $meteoDay = "météo du jour : " . $weatherImage;
    }
}
?>

<form action="" method="POST">
<label for="city">choisir une ville</label>
<select name="insee" id="city">
    <option value="">--choisir une ville--</option>
<?php
$cities = [
    '35238' => 'Rennes',
    '83050' => 'Draguignan',
    '83137' => 'Toulon',
    '13214' => 'Marseille 14e',
    '06088' => 'Nice',
    '06069' => 'Grasse',
    '83023' => 'Brignoles',
    '33063' => 'Bordeaux',
    '90010' => 'Belfort',
    '15014' => 'Aurillac',
    '75109' => 'Paris',
    '17300' => 'La Rochelle',
    '59350' => 'Lille'
];
foreach ($cities as $insee => $city) {
    if ($insee == $inseeSelected) {
        echo "<option value =\"$insee\" selected>$city</option>";
    } else {
        echo "<option value =\"$insee\">$city</option>";
    }
}
?>
</select>
<input type="submit" name="submit" value="Générer"></input>
</form>

<?php
if (!empty($meteoDisplayed) && !empty($meteoDay)) {
    echo "<p>$meteoDisplayed</p>";
    echo "<p>$meteoDay</p>";
}
?>