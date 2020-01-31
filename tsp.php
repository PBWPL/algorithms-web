<?php
/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-11-11
 * Time: 19:08
 */

namespace Algorithm;
include_once "Algorithm/TSP.php";

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

$data = json_decode($_POST['r']);

$matrix = $data[0];
$choose = $data[1];

if ($choose == '') {
    $choose = 0;
}

$tsp = new TSP($matrix, $choose);
echo $tsp->solve();
