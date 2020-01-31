<?php
/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-10-27
 * Time: 16:17
 */

namespace Algorithm;
include_once "Algorithm.php";


/**
 * Class Knapsnack
 * @package Algorithm
 */
class Knapsnack extends Algorithm
{
    /**
     * @var array
     */
    public $i_weight = [];

    /**
     * @var array
     */
    public $i_value = [];

    /**
     * Knapsnack constructor.
     * @param $matrix
     */
    public function __construct($matrix)
    {
        parent::__construct($matrix);

        foreach ($matrix as $matri => $matr) {
            ($matri == 0) ? $this->i_value = $matr : $this->i_weight = $matr;
        }

    }

    /**
     * @param $w
     * @return float|int|string
     */
    function packGreedy($w)
    {
        if ($w < max($this->i_value)) {
            die("Increase the value of the backpack!");
        }
        self::sortBubble($this->i_weight, $this->i_value, $this->columns);

        $V = 0;
        for ($i = 0; $i < $this->columns; $i++) {
            $piece = floor($w / $this->i_weight[$i]);
            $w -= $piece * $this->i_weight[$i];
            $V += $piece * $this->i_value[$i];
        }
        return $V;
    }

    /**
     * @param $i_weight
     * @param $i_value
     * @param $n
     */
    function sortBubble($i_weight, $i_value, $n): void
    {
        for ($i = 0; $i < $n - 1; $i++) {
            for ($j = 0; $j < $n - $i - 1; $j++) {
                if ($i_value[$j] / $i_weight[$j] <= $i_value[$j + 1] / $i_weight[$j + 1]) {
                    $t = $i_weight[$j];
                    $i_weight[$j] = $i_weight[$j + 1];
                    $i_weight[$j + 1] = $t;
                    $t = $i_value[$j];
                    $i_value[$j] = $i_value[$j + 1];
                    $i_value[$j + 1] = $t;

                }
            }
        }
        $this->i_value = $i_value;
        $this->i_weight = $i_weight;
    }

    /**
     * @param $w
     * @return mixed
     */
    function packDynamic($w)
    {
        if ($w < max($this->i_value)) {
            die("Increase the value of the backpack!");

        }
        $P = array_fill(0, $this->columns + 1, array_fill(0, $w + 1, 0));
        $Q = array_fill(0, $this->columns + 1, array_fill(0, $w + 1, 0));

        self::writeMatrix($P);

        for ($i = 1; $i <= $this->columns; $i++) {
            for ($j = 1; $j <= $w; $j++) {
                if ($this->i_weight[$i - 1] <= $j && $P[$i - 1][$j] < $P[$i][$j - $this->i_weight[$i - 1]] + $this->i_value[$i - 1]) {
                    $P[$i][$j] = max($P[$i - 1][$j], $P[$i][$j - $this->i_weight[$i - 1]] + $this->i_value[$i - 1]);
                    $Q[$i][$j] = $i;

                } else {
                    $P[$i][$j] = $P[$i - 1][$j];
                    $Q[$i][$j] = $Q[$i - 1][$j];
                }
            }
        }

        $i = $w;
        $items = "";
        do {
            $items .= $Q[$this->columns][$i] . " ";
            $i -= $this->i_weight[$Q[$this->columns][$i] - 1];
        } while ($i >= 1);

        $this->writeMatrix($P);
        $this->writeMatrix($Q);
        echo $items . "\n";
        return $P[$this->columns][$w];
    }

}
