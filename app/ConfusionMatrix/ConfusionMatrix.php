<?php
namespace App\ConfusionMatrix;

class ConfusionMatrix {

    /**
     * Index of matrix
     *
     * @var String
     */
    private $index;

    /**
     * Total all data
     *
     * @var Integer
     */
    private $totalData;

    /**
     * Total all class
     *
     * @var Integer
     */
    private $totalClass;

    /**
     * Confusion Matrix 
     *
     * @var array
     */
    private $matrix = [];

    /**
     * Value true positive every class
     *
     * @var array
     */
    private $tp = [];

    /**
     * Value false positive every class
     *
     * @var array
     */
    private $fp = [];

    /**
     * Value precission every class
     *
     * @var array
     */
    private $precission = [];

    /**
     * Value false negative every class
     *
     * @var array
     */
    private $fn = [];

    /**
     * Value recall every class
     *
     * @var array
     */
    private $recall = [];

    /**
     * Set index of matrix
     *
     * @param array $index
     * @return void
     */
    public function setIndex($index = [])
    {
        $this->index =  $index;
    }

    /**
     * Set initial matrix
     *
     * @return void
     */
    public function setInitialMatrix()
    {
        foreach ($this->index as $i){
            foreach ($this->index as $j) {
                $this->matrix[$i][$j] = 0;
            }
            $this->totalClass++;
        }

        return $this->matrix;
    }

    /**
     * Get matrix
     *
     * @param array $data
     * @return array
     */
    public function getMatrix($data = [])
    {
        foreach ($data as $value) {
            $this->matrix[$value['original']][$value['result']] += 1;
            $this->totalData++;
        }

        return $this->matrix;
    }

    /**
     * Get value accuration
     *
     * @return array
     */
    public function getAccuration()
    {
        foreach ($this->matrix as $key => $value) {
            $this->tp[$key] = $this->matrix[$key][$key];
        }

        $accuration = array_sum($this->tp) / $this->totalData;

        return number_format(($accuration * 100), 2, '.', '') . ' %';
    }

    /**
     * Get value precission
     *
     * @return array
     */
    public function getPrecission()
    {
        foreach ($this->index as $valueI) {
            foreach ($this->matrix[$valueI] as $j => $valueJ) {
                if ($j != $valueI){
                    if (isset($this->fp[$valueI])){
                        $this->fp[$valueI] += $this->matrix[$j][$valueI];
                    }else {
                        $this->fp[$valueI] = $this->matrix[$j][$valueI];
                    }
                }
            }
        }

        foreach ($this->index as $value) {
            if ($this->tp[$value] > 0){
                $this->precission[$value] = ($this->tp[$value]/($this->tp[$value] + $this->fp[$value]));
            }else {
                $this->precission[$value] = 0;
            }
            
        }
        $precission = array_sum($this->precission) / $this->totalClass;
        
        return number_format(($precission * 100), 2, '.', '') . ' %';
    }

    public function getRecall()
    {
        foreach ($this->index as $key => $valueI) {
            foreach ($this->matrix as $j => $valueJ) {
                if ($j != $valueI){
                    if (isset($this->fn[$valueI])) {
                        $this->fn[$valueI] += $this->matrix[$valueI][$j];
                    }else{
                        $this->fn[$valueI] = $this->matrix[$valueI][$j];
                    }
                }
            }
        }

        foreach ($this->index as $value) {
            $this->recall[$value] = ($this->tp[$value] / ($this->tp[$value] + $this->fn[$value]));
        }

        $recall = array_sum($this->recall) / $this->totalClass;

        return number_format(($recall * 100), 2, '.', '') . ' %';

    }

    public function getFMeasure()
    {
        $precission = array_sum($this->precission) / $this->totalClass;
        $recall = array_sum($this->recall) / $this->totalClass;

        $fMeasure = 2*(($precission*$recall)/($precission+$recall));

        return number_format(($fMeasure * 100), 2, '.', '') . ' %';
    }
}