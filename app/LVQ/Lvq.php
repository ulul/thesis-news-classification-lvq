<?php 
namespace App\LVQ;
class Lvq {
    private $alpha;
    private $decAlpha;
    private $minAlpha;
    private $maxEpoch;
    private $dataTrain;
    private $dataFinal = [];
    private $kelas = [];
    private $term = [];
    private $termFrequent = [];
    private $termFrequentFinal = [];
    private $blackListTerm;
    private $firstData = [];
    private $termWithList = [];
    private $bobotAwal = [];
    private $minimumProbabilityTerm;
    private $bobotAkhir = [];

    function setData($dataTrain, $alpha, $decAlpha, $minAlpha, $maxEpoch)
    {
        $this->dataTrain = $dataTrain;
        $this->alpha = $alpha;
        $this->decAlpha = $decAlpha;
        $this->minAlpha = $minAlpha;
        $this->maxEpoch = $maxEpoch;
    }

    public function setblackListTerm($blackList)
    {
        $this->blackListTerm = explode("\n", $blackList);
    }

    public function setMinimumProbabilityTerm($minimumProbabilityTerm)
    {
        $this->minimumProbabilityTerm = $minimumProbabilityTerm;
    }

    public function getMinimumProbabilityTerm()
    {
        return $this->minimumProbabilityTerm;
    }

    public function setAllTerm($data)
    {
        foreach ($data as  $item) {
            $body = explode(' ', $item->body_stemmed);
            foreach ($body as $data) {
                if (!in_array($data, $this->term) && !is_numeric($data) && !in_array($data, $this->blackListTerm)) {
                    array_push($this->term, $data);
                }
            }
        }
    }

    public function getAllTerm()
    {
        return $this->term;
    }

    public function setTermFrequent()
    {
        foreach ($this->dataTrain as $key => $item) {
            $body = explode(' ', $item->body_stemmed);
            foreach ($this->term as $term) {
                foreach ($body as $data) {
                    if (isset($this->termFrequent[$key][$term]) && $term == $data) {
                        $this->termFrequent[$key][$term] += 1;
                    } else if (!isset($this->termFrequent[$key][$term]) && $term == $data) {
                        $this->termFrequent[$key][$term] = 1;
                    } else if (!isset($this->termFrequent[$key][$term]) && $term != $data) {
                        $this->termFrequent[$key][$term] = 0;
                    }
                }
            }

            $this->firstData[] = [
                'key' => $key,
                'bobot' => $this->termFrequent[$key],
                'category' => $item->category->category,
            ];

            if (! in_array($item->category->category, $this->kelas)) {
                array_push($this->kelas, $item->category->category);
            }
        }
    }

    public function getKelas()
    {
        return collect($this->kelas);
    }

    public function getFirstData()
    {
        return collect($this->firstData);
    }

    public function setTermWithList()
    {
        foreach ($this->getKelas() as $key => $value) {
            $data = $this->getFirstData()->where('category', $value);
            $totalData = $data->count();
            $totalTerm = [];

            foreach ($data as $key => $value) {
                foreach ($value['bobot'] as $key => $value) {
                    if ($value > 0) {
                        if (isset($totalTerm[$key])) {
                            $totalTerm[$key] += 1;
                        } else {
                            $totalTerm[$key] = 0;
                        }
                    } else {
                        if (!isset($totalTerm[$key])) {
                            $totalTerm[$key] = 0;
                        }
                    }
                }
            }

            foreach ($totalTerm as $key => $value) {
                $prosentase = $value / $totalData * 100;
                if ($prosentase > $this->getMinimumProbabilityTerm()) {
                    if (!in_array($key, $this->termWithList)) {
                        array_push($this->termWithList, $key);
                    }
                }
            }
        }
    }

    public function getTermWithList()
    {
        return $this->termWithList;
    }

    public function setTermFrequentFinal()
    {
        foreach ($this->dataTrain as $key => $item) {
            $body = explode(' ', $item->body_stemmed);
            foreach ($this->termWithList as $term) {
                foreach ($body as $data) {
                    if (isset($this->termFrequentFinal[$key][$term]) && $term == $data) {
                        $this->termFrequentFinal[$key][$term] += 1;
                    } else if (!isset($this->termFrequentFinal[$key][$term]) && $term == $data) {
                        $this->termFrequentFinal[$key][$term] = 1;
                    } else if (!isset($this->termFrequentFinal[$key][$term]) && $term != $data) {
                        $this->termFrequentFinal[$key][$term] = 0;
                    }
                }
            }

            $this->dataFinal[] = [
                'id' => $item->id,
                'key' => $key,
                'bobot' => $this->termFrequentFinal[$key],
                'category' => $item->category->category,
            ];

        }
        
    }
    public function getDataFinal()
    {
        return collect($this->dataFinal);
    }

    public function getFinalTermFrequent()
    {
        return collect($this->termFrequentFinal);
    }

    public function setBobotAwal()
    {
        foreach ($this->getKelas() as $key => $value) {
            $category = $this->getDataFinal()->where('category', $value)->first();
            array_push($this->bobotAwal, $category);
        }
    }

    public function getBobotAwal()
    {
        return collect($this->bobotAwal);
    }

    public function run()
    {
        $this->setTermFrequent();
        $this->setTermWithList();
        $this->setTermFrequentFinal();
        $this->setBobotAwal();
        $loop = true;
        $epoch = 0;
        $this->bobotAkhir = $this->getBobotAwal()->toArray();
        while ($loop) {
            foreach ($this->getDataFinal() as $indexData => $data) {
                $bobotPerhitungan = [];
                foreach ($this->bobotAkhir as $indexBobotAwal => $bobotAwalItem) {
                    $tempTotal = 0;
                    foreach ($bobotAwalItem['bobot'] as $index => $value) {
                        $tempTotal += pow(($value - $this->getDataFinal()[$indexData]['bobot'][$index]), 2);
                    }

                    array_push($bobotPerhitungan, [
                        'key' => $bobotAwalItem['key'],
                        'total' => $tempTotal,
                        'index' => $indexBobotAwal,
                        'category' => $bobotAwalItem['category']
                    ]);
                }

                $bobotPerhitungan = collect($bobotPerhitungan);
                $min = $bobotPerhitungan->min('total');
                $minimumData = $bobotPerhitungan->where('total', $min)->first();

                $newBobotWinner = [];

                if ($minimumData['category'] == $data['category']) {
                    foreach ($this->bobotAkhir[$minimumData['index']]['bobot'] as $key => $value) {
                        $newBobotWinner[$key] = $value + ($this->alpha
                            * ($data['bobot'][$key] - $value));
                    }
                } else {
                    foreach ($this->bobotAkhir[$minimumData['index']]['bobot'] as $key => $value) {
                        $newBobotWinner[$key] = $value - ($this->alpha
                            * ($data['bobot'][$key] - $value));
                    }
                }
                $this->bobotAkhir[$minimumData['index']]['bobot'] = $newBobotWinner;
            }

            $epoch++;

            $this->alpha = $this->alpha * $this->decAlpha;
            if ($epoch >= $this->maxEpoch || $this->alpha <= $this->minAlpha) {
                $loop = false;
            }
        }
    }

    public function getBobotAkhir()
    {
        return $this->bobotAkhir;
    }
}