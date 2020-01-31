<?php
/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-10-11
 * Time: 09:54
 */

namespace Algorithm;
include_once "Algorithm.php";


/**
 * Class Hungarian
 * @package Algorithm
 */
class Hungarian extends Algorithm
{
    /**
     * @var int
     */
    public $lines = 0;

    /**
     * @return int
     */
    function solve(): int
    {
        // Step 1:
        $this->matrix_final = self::stepOne($this->matrix_final);

        // Step 2:
        $this->matrix_final = self::stepTwo($this->matrix_final);
        $this->writeMatrix($this->matrix_final);

        // Step 3:
        $this->matrix_tmp = $this->matrix_final;
        $this->matrix_tmp = self::stepThree($this->matrix_tmp);

        while ($this->lines != $this->rows) {
            $min = self::searchMin($this->matrix_tmp);
            $this->matrix_final = self::doAddSubtract($this->matrix_tmp, $this->matrix_final, $min);
            $this->matrix_tmp = $this->matrix_final;
            $this->matrix_tmp = self::stepThree($this->matrix_tmp);
        }

        $this->writeMatrix($this->matrix_tmp);
        $this->writeMatrix($this->matrix_final);

        // Step 4:
        $this->matrix_final = self::searchIndependentZeros($this->matrix_final);
        self::writeMatrix($this->matrix_final);

        // Step 4a:
        $this->matrix_final = self::replaceValues($this->matrix_final);
        self::writeMatrix($this->matrix_final);

        // Step 4b:
        $this->matrix_final = self::multiplicationMatrix($this->matrix_final, $this->matrix);
        self::writeMatrix($this->matrix_final);

        // Step 4c:
        $this->result = self::addValues($this->matrix_final);
        echo "Suma: " . $this->result;
        return $this->result;

    }

    /**
     * @param $matrix
     * @return array
     */
    function stepOne($matrix): array
    {
        for ($i = 0; $i < count($matrix); $i++) {
            $min = min($matrix[$i]);
            for ($j = 0; $j < count($matrix[$i]); $j++) {
                $matrix[$i][$j] -= $min;
            }
        }

        return $matrix;
    }

    /**
     * @param $matrix
     * @return array
     */
    function stepTwo($matrix): array
    {
        for ($j = 0; $j < count($matrix[0]); $j++) {
            $min = $matrix[0][$j];
            for ($i = 0; $i < count($matrix); $i++) {
                if ($matrix[$i][$j] < $min) {
                    $min = $matrix[$i][$j];
                }
            }
            for ($k = 0; $k < count($matrix[0]); $k++) {
                $matrix[$k][$j] -= $min;
            }
        }

        return $matrix;
    }

    /**
     * @param $matrix
     * @return array
     */
    function stepThree($matrix): array
    {
        $this->lines = 0;
        $test = 0;

        do {
            if ($this->lines > $this->rows) {
                $t = false;
                $zeros = self::searchZeros($matrix, $t);
            } else {
                $t = true;
                $zeros = self::searchZeros($matrix, $t);
            }
            $this->lines += $zeros[2];
            $matrix = self::crossOut($matrix, $zeros[0], $zeros[1]);
            $var = 0;
            foreach ($matrix as $dat) {
                if (min($dat) == 0) {
                    $var = 1;
                    break;
                }
            }
            $test++;
        } while ($var == 1 && $test < $this->rows);

        return $matrix;
    }

    /**
     * @param $matrix
     * @param $t
     * @return array
     */
    function searchZeros($matrix, $t): array
    {
        $col = array_fill(0, count($matrix), 0);
        $row = array_fill(0, count($matrix), 0);

        for ($i = 0; $i < count($matrix); $i++) {
            for ($j = 0; $j < count($matrix[0]); $j++) {
                if ($matrix[$i][$j] == 0) {
                    $row[$i] += 1;
                }
            }
        }

        for ($j = 0; $j < count($matrix); $j++) {
            for ($i = 0; $i < count($matrix[0]); $i++) {
                if ($matrix[$j][$i] == 0) {
                    $col[$i] += 1;
                }
            }
        }

        $c = array_keys($col, max($col))[0];
        $r = array_keys($row, max($row))[0];
        $i = 0;

        if ($t == false) {
            $i += 1;
            if (isset(array_keys($row, max($row))[$i])) {
                $r = array_keys($row, max($row))[$i];
            } elseif (isset(array_keys($col, max($col))[$i])) {
                $c = array_keys($col, max($col))[$i];
            }
        }

        if (max($col) == max($row) && max($col) > 1) {
            return [$r, $c, 2];
        } elseif (max($col) > max($row)) {
            return [count($matrix) + 1, $c, 1];
        } elseif (max($col) < max($row)) {
            return [$r, count($matrix[0]) + 1, 1];
        }

        if (max($col) == 1) {
            return [count($matrix) + 1, $c, 1];
        } else {
            return [$r, count($matrix[0]) + 1, 1];
        }
    }

    /**
     * @param $matrix
     * @param int $row
     * @param int $col
     * @return array
     */
    function crossOut($matrix, int $row, int $col): array
    {
        $tmp = $matrix;

        for ($i = 0; $i < count($matrix); $i++) {
            if ($row != $i) {
                for ($j = 0; $j < count($matrix[0]); $j++) {
                    $tmp[$i][$j] = INF;
                    if ($col != $j) {
                        $tmp[$i][$j] = $matrix[$i][$j];
                    }
                }
            } else {
                for ($j = 0; $j < count($matrix[0]); $j++) {
                    $tmp[$i][$j] = INF;
                }
            }
        }

        return ($tmp);
    }

    /**
     * @param $matrix
     * @return int
     */
    function searchMin($matrix): int
    {
        $min = $matrix[0][0];

        foreach ($matrix as $dat) {
            if (min($dat) < $min) {
                $min = min($dat);
            }
        }

        return $min;
    }

    /**
     * @param $matrix_tmp
     * @param $matrix
     * @param $min
     * @return array
     */
    function doAddSubtract($matrix_tmp, $matrix, $min): array
    {
        $col = array_fill(0, count($matrix_tmp), 0);
        $row = array_fill(0, count($matrix_tmp), 0);

        for ($i = 0; $i < count($matrix_tmp); $i++) {
            for ($j = 0; $j < count($matrix_tmp[0]); $j++) {
                if ($matrix_tmp[$i][$j] == INF) {
                    $row[$i] += 1;
                }
            }
        }

        for ($j = 0; $j < count($matrix_tmp); $j++) {
            for ($i = 0; $i < count($matrix_tmp[0]); $i++) {
                if ($matrix_tmp[$j][$i] == INF) {
                    $col[$i] += 1;
                }
            }
        }

        for ($i = 0; $i < count($matrix); $i++) {
            for ($j = 0; $j < count($matrix[0]); $j++) {
                if ($row[$i] == count($matrix) && $col[$j] == count($matrix[0])) {
                    $matrix[$i][$j] += $min;
                } elseif ($matrix_tmp[$i][$j] != INF) {
                    $matrix[$i][$j] -= $min;
                }
            }
        }

        return $matrix;
    }

    /**
     * @param $matrix
     * @return mixed
     */
    function searchIndependentZeros($matrix)
    {
        $tmp = [];

        for ($i = 0; $i < count($matrix); $i++) {
            for ($j = 0; $j < count($matrix[0]); $j++) {
                if ($matrix[$i][$j] == 0) {
                    array_push($tmp, [$i, $j]);
                    $id = self::checkTest($tmp, $i, $j);
                    if ($id[0] !== INF && $id[1] == 0) {
                        array_pop($tmp);

                    } elseif ($id[0] !== INF && $id[1] == 1) {
                        array_pop($tmp);
                    }
                }

            }
        }

        for ($i = 0; $i < count($tmp); $i++) {
            $matrix[$tmp[$i][0]][$tmp[$i][1]] = 'X';
        }

        for ($i = 0; $i < count($matrix); $i++) {
            for ($j = 0; $j < count($matrix[0]); $j++) {
                if ($matrix[$i][$j] === 0) {
                    $matrix[$i][$j] = INF;
                }
            }
        }

        return $matrix;
    }

    /**
     * @param $tmp
     * @param $i
     * @param $j
     * @return array
     */
    function checkTest($tmp, $i, $j)
    {
        if (count($tmp) >= 2) {
            for ($k = 0; $k < count($tmp) - 1; $k++) {
                if ($tmp[$k][0] == $i) {
                    return [$k, 0];
                } elseif ($tmp[$k][1] == $j) {
                    return [$k, 1];
                }
            }
        }

        return [INF, INF];
    }

    /**
     * @param $matrix
     * @return array
     */
    function replaceValues($matrix): array
    {
        foreach ($matrix as $matri => $matr) {
            foreach ($matr as $mat => $ma) {
                if ($ma == 'X') {
                    $matrix[$matri][$mat] = 1;
                } else {
                    $matrix[$matri][$mat] = 0;
                }
            }
        }

        return $matrix;
    }

    /**
     * @param $matrix_final
     * @param $matrix
     * @return array
     */
    function multiplicationMatrix($matrix_final, $matrix): array
    {
        foreach ($matrix as $matri => $matr) {
            foreach ($matr as $mat => $ma) {
                $matrix[$matri][$mat] *= $matrix_final[$matri][$mat];
            }
        }

        return $matrix;
    }

    //------------------------------------------

    /**
     * @param $matrix
     * @return int
     */
    function addValues($matrix): int
    {
        $val = 0;

        foreach ($matrix as $matri => $matr) {
            foreach ($matr as $mat => $ma) {
                $val += $matrix[$matri][$mat];
            }
        }

        return $val;
    }

    //------------------------------------------
}
