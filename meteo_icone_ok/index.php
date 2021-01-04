<?php

$pathes = scandir(__DIR__);

echo "<ul>";
foreach ($pathes as $path) {
    echo "<a href=\"$path\">$path</a><br />";
}
echo "</ul>";

