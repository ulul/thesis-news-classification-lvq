<?php

namespace App\Http\Controllers;

use App\Repositories\NewsCategoryRepository;
use Illuminate\Http\Request;
use App\Repositories\NewsRepository;

class TestingController extends Controller
{

    /**
     * News Repository
     *
     * @var NewsRepository
     */
    private $news;

    /**
     * News Category Repository
     *
     * @var NewsCategoryRepository
     */
    private $newsCategory;

    /**
     * Constructor
     *
     * @param NewsRepository $news
     * @param NewsCategoryRepository $newsCategory
     */
    public function __construct(
        NewsRepository $news,
        NewsCategoryRepository $newsCategory
    )
    {
        $this->news = $news;
        $this->newsCategory = $newsCategory;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = $this->newsCategory->get();
        return view('testing.index', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }
}
