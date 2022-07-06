<?php

require './vendor/autoload.php';

$stats = new App\Stats($argv[1]);
echo $stats->toJson();
