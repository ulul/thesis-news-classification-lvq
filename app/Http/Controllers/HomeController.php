<?php

namespace App\Http\Controllers;

use App\ConfusionMatrix\ConfusionMatrix;
use App\LVQ\Classification;
use App\LVQ\Lvq;
use App\Repositories\NewsCategoryRepository;
use App\Repositories\NewsRepository;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * News repository
     *
     * @var NewsRepository
     */
    private $news;

    /**
     * Learning rate
     * 
     * @var [decimal]
     */
    private $alpha;

    /**
     * Penurunan Learning rate
     * 
     * @var [decimal]
     */
    private $decAlpha;

    /**
     * Minimum Learning rate
     * 
     * @var [decimal]
     */
    private $minAlpha;

    /**
     * Maksimum Epoch
     *
     * @var [decimal]
     */
    private $maxEpoch;

    /**
     * Minimum probability term in all document
     *
     * @var [decimal]
     */
    private $minimumProbability;

    /**
     * Confusion Matrix
     *
     * @var ConfusionMatrix
     */
    private $confusionMatrix;

    /**
     * News Category repository
     *
     * @var NewsCategoryRepository
     */
    private $category;

    /**
     * Lvq
     *
     * @var Lvq
     */
    private $lvq;


    /**
     * Make instance class
     *
     * @param NewsRepository $news
     */
    function __construct(
        Lvq $lvq,
        NewsRepository $news,
        ConfusionMatrix $confusionMatrix,
        NewsCategoryRepository $category
    )
    {
        $this->lvq = $lvq;
        $this->news = $news;
        $this->confusionMatrix = $confusionMatrix;
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        if ($request->run){

            $news = $this->news->getAllNews();

            $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();

            $dictionary = $stemmerFactory->createDefaultDictionary();
            $dictionary->addWordsFromTextFile(public_path('storage/wordlist.txt'));
            $dictionary->add('pemilu');
            $dictionary->add('peraga');
            $dictionary->add('terkait');
            $dictionary->add('pengawas');
            $dictionary->add('dimensi');
            $dictionary->add('umpama');
            $dictionary->add('pemangku');

            $stemmer = new \Sastrawi\Stemmer\Stemmer($dictionary);

            $trainData = collect();
            $testData = collect();

            $totalData = [];
            $categories = $this->category->getAllCategory();
            foreach ($categories as $key => $value) {
                $totalData[$value->id] = (int) (($request->data_latih/100)*($news->where('category_id', $value->id)->count()));
            }

            $news->map(function ($item) use ($stemmer, $totalData, &$trainData, &$testData) {
                $item->body_stemmed = $stemmer->stem($item->body);
                $maxInput = $totalData[$item->category_id];
                $totalMasuk = $trainData->where('category_id', $item->category_id)->count();
                if ($totalMasuk < $maxInput){
                    $trainData->push($item);
                }else {
                    $testData->push($item);
                }
            });

            $this->lvq->setData($trainData, 0.2, 0.01, 0.0001, 100);
            $fileKataHubung = file_get_contents(public_path('storage/kata-penghubung.txt'));
            $this->lvq->setblackListTerm($fileKataHubung);
            $this->lvq->setMinimumProbabilityTerm(40);
            $this->lvq->setAllTerm($news);
            $this->lvq->run();

            $bobotAwal = $this->lvq->getBobotAwal();
            $allTerm = $this->lvq->getTermWithList();
            $tf = $this->lvq->getTermWithList();
            $bobotAkhir = $this->lvq->getBobotAkhir();

            $classification = new Classification($tf, $testData, $bobotAkhir);
            $classification->run();
            $results = $classification->getResult();

            $this->confusionMatrix->setIndex($this->lvq->getKelas());
            $this->confusionMatrix->setInitialMatrix();
            $matrix = $this->confusionMatrix->getMatrix($results);
            $acuration = $this->confusionMatrix->getAccuration();
            $precission = $this->confusionMatrix->getPrecission();
            $recall = $this->confusionMatrix->getRecall();
            $fMeasure = $this->confusionMatrix->getFMeasure();

            $confusionMatrix = [
                'matrix' => $matrix,
                'accuration' => $acuration,
                'precission' => $precission,
                'recall' => $recall,
                'fmeasure' => $fMeasure
            ];

            return view('index', compact(
                'results', 'confusionMatrix', 'request', 'bobotAwal', 'bobotAkhir'
            ));
        }else {
            return view('index', compact('request'));
        }

    }
}
