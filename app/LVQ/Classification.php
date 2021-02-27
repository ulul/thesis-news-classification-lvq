<?php
namespace App\LVQ;

use App\Models\News;
use App\Repositories\NewsRepository;

class Classification {
    private $term = [];
    private $termFrequent = [];
    private $dataTest = [];
    private $bobotAkhirLvq = [];
    private $termFrequentWithKelas = [];
    private $result = [];
    
    public function __construct(
        $term,
        $dataTest,
        $bobotAkhirLvq
    )
    {
        $this->term = $term;
        $this->dataTest = $dataTest;
        $this->bobotAkhirLvq = $bobotAkhirLvq;
    }


    public function setTermFrequent()
    {
        foreach ($this->dataTest as $key => $item) {
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
            $this->termFrequentWithKelas[] = [
                'id' => $item->id,
                'key' => $key,
                'bobot' => $this->termFrequent[$key],
                'category' => $item->category->category,
            ];
        }
    }
    

    public function run()
    {
        $this->setTermFrequent();
        foreach ($this->termFrequentWithKelas as $key => $testing) {
            $distanceWithCategory = [];
            foreach ($this->bobotAkhirLvq as  $valueBobot) {
                $totalDistance = 0;
                foreach ($valueBobot['bobot'] as $keyBobot => $value) {
                    $totalDistance += pow(($testing['bobot'][$keyBobot] - $value), 2);
                }
                $dataDistance = [
                    'id' => $testing['id'],
                    'result_category' => $valueBobot['category'],
                    'distance' => $totalDistance
                ];
                $distanceWithCategory[] = $dataDistance;

            }

            $distanceWithCategory = collect($distanceWithCategory);
            $min = $distanceWithCategory->min('distance');
            $dataMin = $distanceWithCategory->where('distance', $min)->first();
            $this->result[] = [
                'id' => $testing['id'],
                'distance' => $min,
                'result' => $dataMin['result_category'],
                'original' => $testing['category']
            ];
        }

        foreach ($this->result as $key => $result) {
            $this->result[$key]['news'] = News::where('id', $result['id'])->first();
        }
    }

    public function getResult()
    {
        return $this->result;
    }

}