<?php
/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-11-24
 * Time: 16:33
 */

namespace Algorithm;
include_once "Algorithm/Johnson.php";

$data = json_decode($_POST['r']);

if (array_sum($data[0]) <= 0 || array_sum($data[1]) <= 0) {
    die("Błędne dane w macierzy!");
}

$hungarian = new Johnson($data);

try {
    echo $hungarian->solve();
} catch (\Exception $e) {
    die("Błąd");
}
