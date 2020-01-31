<?php
/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-10-27
 * Time: 13:01
 */

namespace Algorithm;
include_once "Algorithm/Knapsnack.php";


$data = file_get_contents("php://input");

$w = explode('*', $data);
$data = json_decode($w[1]);
$w = intval($w[0]);

if (array_sum($data[0]) <= 0 || array_sum($data[1]) <= 0) {
    die("Incorrect data");
}

$x = new Knapsnack($data);
echo "Dynamic: " . $x->packDynamic($w);
