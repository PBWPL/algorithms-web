<?php
/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-11-24
 * Time: 16:33
 */

namespace Algorithm;

include_once "Algorithm.php";

/**
 * Class Johnson
 * @package Algorithm
 */
class Johnson extends Algorithm
{
    public function __construct($matrix)
    {
        parent::__construct($matrix);
        for ($i = $this->rows; $i > 0; $i--) {
            $this->matrix[$i] = $this->matrix[$i - 1];
            if ($i == 1) {
                for ($j = 0; $j < $this->columns; $j++) {
                    $this->matrix[$i - 1][$j] = $j + 1;
                }
            }
        }
    }

    function solve()
    {
        if ($this->rows == 2) {
            // STEP 1
            $data = $this->compare($this->matrix, $this->columns);
            $N1 = $data[0];
            $N2 = $data[1];
//            $this->writeMatrix($N1);
//            $this->writeMatrix($N2);

            // STEP 2
            if (count($N1[0]) > 1) {
                $N1 = $this->sortBubble($N1, 1, 'desc'); // rosnąco
            }
            if (count($N2[0]) > 1) {
                $N2 = $this->sortBubble($N2, 2, 'asc'); // malejąco
            }
//            $this->writeMatrix($N1);
//            $this->writeMatrix($N2);

            // STEP 3
            $N = $this->merge($N1, $N2);
//            $this->writeMatrix($N);

            // STEP 4
            for ($i = 0; $i < count($N[0]); $i++) {
                $length = (isset($M1)) ? count($M1) : 0;
                for ($j = $length; $j < $length + $N[1][$i]; $j++) {
                    $M1[$j] = $N[0][$i];
                }
                $length = (isset($M2)) ? count($M2) : 0;
                for ($j = $length; $j < $length + $N[2][$i]; $j++) {
                    $M2[$j] = $N[0][$i];
                }
            }
//            echo implode(' ', $M1) . "\n";
//            echo implode(' ', $M2) . "\n";

            // STEP 5
            for ($i = 0, $j = 0; $i < count($M1);) {
                if ($j < count($M1) && $M1[$j] == $M2[$i]) {
                    array_splice($M2, $i, 0, 'x');
                    $i--;
                    $j--;
                }
                $j++;
                $i++;
            }
            $x1 = implode(' ', $M1) . "\n";
            $x2 = implode(' ', $M2) . "\n";

            // STEP 6
            $pack = [];
            $T = count($M2);
            $L = implode(' &rarr; ', $N[0]);


            $colors = [
                '#000099', '0033cc', '#0000cc', '#336699', '#0066cc',
                '#99ccff', '#6699ff', '#003366', '#6699cc', '#006699',
                '#3399cc', '#0099cc', '#66ccff', '#3399ff', '#003399',
                '#0099ff', '#33ccff', '#00ccff', '#9933cc', '#660066',
                '#9900ff', '#9966cc', '#330033', '#663399', '#6633cc',
                '#6600cc', '#9966ff', '#330066', '#6600ff', '#6633ff',
                '#93DB7O', '#215E21', '#4E2F2F', '#9F9F5F', '#COD9D9',
                '#A8A8A8', '#8F8FBD', '#E9C2A6', '#32CD32', '#E47833',
                '#8E236B', '#32CD99', '#3232CD', '#6B8E23', '#EAEAAE',
                '#937ODB', '#DB7O93', '#A68064', '#2F2F4F', '#23238E',
                '#4D4DFF', '#FF6EC7', '#8C7853', '#A67D3D', '#5F9F9F'
            ];

            $m = [];
            // -------
            $x = $M1[0];
            $start = 0;
            $end = 0;
            for ($i = 0; $i <= count($M1); $i++) {
                if ($M1[$i] == $x) {
                    $end++;
                } else {
                    $x = $M1[$i + 1];
                    array_push($m, ['name' => 'M1', 'startTime' => $start, 'endTime' => $end, 'color' => $colors[$i]]);
                    $start = $end;
                    $end++;
                }
            }
            // -------
            $start = 0;
            $end = 0;
            for ($i = 0; $i <= count($M2); $i++) {
                if ($M2[$i] == 'x') {
                    $start++;
                    $end++;
                } else {
                    $x = $M2[$i + 1];
                    if ($M2[$i] == $x) {
                        $end++;
                    } else {
                        array_push($m, ['name' => 'M2', 'startTime' => $start, 'endTime' => $end, 'color' => $colors[count($M2) - $i]]);
                        $start = $end;
                        $end++;
                    }
                }

                //echo 's = ' . $start . ' e = ' . $end . ' x = ' . $x ."\n";
            }
            // -------

            $pack = [/*'test1' => $x1, 'test2' => $x2, */'result' => $T, 'list' => $L, 'chart_data' => $m];
            return json_encode($pack);
        } else {
            return "under construction";
        }
    }

    /**
     * @param $matrix
     * @param $n
     * @return array
     */
    function compare($matrix, $n): array
    {
        $N1 = $N2 = $data = [];
        $n1 = $n2 = 0;
        for ($i = 0; $i < $n; $i++) {
            if ($matrix[1][$i] < $matrix[2][$i]) {
                $N1[0][$n1] = $matrix[0][$i];
                $N1[1][$n1] = $matrix[1][$i];
                $N1[2][$n1] = $matrix[2][$i];
                $n1++;
            } else {
                $N2[0][$n2] = $matrix[0][$i];
                $N2[1][$n2] = $matrix[1][$i];
                $N2[2][$n2] = $matrix[2][$i];
                $n2++;
            }
        }

        array_push($data, $N1);
        array_push($data, $N2);
        return $data;
    }

    /**
     * @param $matrix
     * @param $sort_row
     * @param string $type
     * @return array
     */
    function sortBubble($matrix, $sort_row, $type = 'asc'): array
    {
        $n = count($matrix[0]);
        $data = [];
        for ($i = 0; $i < $n - 1; $i++) {
            for ($j = 0; $j < $n - $i - 1; $j++) {
                if ($type == 'asc') {
                    if ($matrix[$sort_row][$j] < $matrix[$sort_row][$j + 1]) {
                        for ($k = 0; $k < count($matrix); $k++) {
                            $t = $matrix[$k][$j];
                            $matrix[$k][$j] = $matrix[$k][$j + 1];
                            $matrix[$k][$j + 1] = $t;
                        }
                    }
                } elseif ($type == 'desc') {
                    if ($matrix[$sort_row][$j] > $matrix[$sort_row][$j + 1]) {
                        for ($k = 0; $k < count($matrix); $k++) {
                            $t = $matrix[$k][$j];
                            $matrix[$k][$j] = $matrix[$k][$j + 1];
                            $matrix[$k][$j + 1] = $t;
                        }
                    }
                }
            }
        }
        return $matrix;
    }

    function merge($data, $matrix)
    {
        for ($i = 0, $index = count($data[0]); $i < count($matrix[0]); $i++, $index++) {
            for ($j = 0; $j < count($data); $j++) {
                $data[$j][$index] = $matrix[$j][$i];
            }
        }
        return $data;
    }

}
