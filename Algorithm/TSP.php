<?php
/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-11-11
 * Time: 19:08
 */

namespace Algorithm;
include_once "Algorithm.php";


class TSP extends Algorithm
{
    public $d;
    public $id;

    public function __construct($matrix, $id)
    {
        $this->id = $id;
        $this->d = $matrix[intval($id)];
        parent::__construct($matrix);
    }

    public function solve()
    {
        $row = $this->id;
        $route = [];
        $length = 0;

        for ($i = 0; $i < count($this->d) - 1; $i++) {
            $max = max($this->d); // 3
            $col = array_keys($this->d, $max)[0]; // 5
            if ($i == 0) {
                array_push($route, $row, $col, $row);
                $length = $this->matrix[$row][$col] + $this->matrix[$col][$row];
            } else {
                $tmp = INF;
                $c = 0;
                for ($j = 0; $j < count($route) - 1; $j++) {
                    $c = $this->matrix[$route[$j]][$col] + $this->matrix[$col][$route[$j + 1]] - $this->matrix[$route[$j]][$route[$j + 1]];
                    if ($tmp > $c) {
                        $tmp = $c;
                        $c = $route[$j];
                    }
                }
                array_splice($route, array_keys($route, $c)[0] + 1, 0, $col);
                $length += $tmp;
            }


            for ($a = 0; $a < count($this->d); $a++) {
                if ($this->d[$a] > $this->matrix[$col][$a]) {
                    $this->d[$a] = $this->matrix[$col][$a];
                }
            }

            $this->d[$col] = 0;
        }

        echo "d = [" . implode(" ", $this->d) . ']' . '<br>';
        echo "route = [" . implode(" ", $route) . ']' . '<br>';

        return $length;
    }
}
