<?php
/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-10-27
 * Time: 13:38
 */

namespace Algorithm;


/**
 * Class Algorithm
 * @package Algorithm
 */
class Algorithm
{
    /**
     * @var array
     */
    public $matrix = [];

    /**
     * @var array
     */
    public $matrix_final = [];

    /**
     * @var array
     */
    public $matrix_tmp = [];

    /**
     * @var int
     */
    public $rows;

    /**
     * @var int
     */
    public $columns;

    /**
     * @var int
     */
    public $result = 0;

    /**
     * Algorithm constructor.
     * @param $matrix
     */
    public function __construct($matrix)
    {
        $this->matrix = $matrix;
        $this->matrix_final = $matrix;
        $this->rows = count($matrix);
        $this->columns = count($matrix[0]);
        $this->matrix_tmp = array_fill(0, $this->rows, array_fill(0, $this->columns, -1));
    }

    /**
     * @param $matrix
     * @return string
     */
    function writeMatrix($matrix): string
    {
        $text = '';

        for ($i = 0; $i < count($matrix); $i++) {
            $text .= '[';
            for ($j = 0; $j < count($matrix[0]); $j++) {
                $text .= $matrix[$i][$j] . ' ';
            }
            $text .= ']' . "\n";
        }
        $text .= "\n";

        return print($text);
    }
}
