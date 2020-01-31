<?php
/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-10-11
 * Time: 10:51
 */

namespace Algorithm;
include_once "Algorithm/Hungarian.php";

//header('Content-Type: application/json');

$data = json_decode($_POST['r']);

if (array_sum($data[0]) <= 0 || array_sum($data[1]) <= 0) {
    die("Błędne dane w macierzy!");
}

$hungarian = new Hungarian($data);

try {
    $hungarian->solve();
} catch (\Exception $e) {
    die("Błąd");
}
