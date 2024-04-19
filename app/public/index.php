<?php
declare(strict_types=1);

require dirname(__DIR__).'/autoload.php';

use src\RunMe;

$app = new RunMe();
$app->execute();