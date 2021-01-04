<?php
# Traitement formulaire
if (!empty($_POST['insee'])) {
    $inseeSelected = ($_POST['insee']);
    $url = "https://api.meteo-concept.com/api/forecast/daily/0?token=a9b814eaf38dd3c21fe83f56700730b9511b1692a27759760e5806dc70caedf8&insee=" . $inseeSelected;
    $data = file_get_contents($url);
    if ($data) {
        $decoded = json_decode($data);
        $city = $decoded->city;
        $forecast = $decoded->forecast;
        $meteoDisplayed = "Aujourd'hui à <strong>{$city->name}</strong>, on prévoit <strong>{$forecast->rr10}mm</strong> (pas plus de {$forecast->rr1}mm en tous cas) de précipitations.";
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
if (!empty($meteoDisplayed)) {
    echo "<p>$meteoDisplayed</p>";
}
?>

    <!-- ////////////////////CODE FORECAST////////////////////////////// -->

