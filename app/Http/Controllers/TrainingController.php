<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use App\Repositories\NewsRepository;
use App\Http\Requests\TrainingRequest;
use App\Repositories\NewsCategoryRepository;

class TrainingController extends Controller
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
    function __construct(
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
    public function index(Request $request)
    {
        $news = $this->news->getLatestNews($request);
        $categories = $this->newsCategory->get();
        return view('training.index', compact('categories', 'news'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = $this->newsCategory->get();
        return view('training.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TrainingRequest $request)
    {
        $this->news->create([
            'title' => $request->title,
            'category_id' => $request->category,
            'body' => $request->body
        ]);

        return redirect()->route('training.index')->with('success', 'Data latih berhasil di tambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
        $news = $this->news->getNews($id);
        $news->body_stemmed = $stemmer->stem($news->body);
        return view('training.show', compact('news'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = $this->newsCategory->get();
        $news = $this->news->getNews($id);
        return view('training.edit', compact('news', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TrainingRequest $request, $id)
    {
        $this->news->update([
            'title' => $request->title,
            'category_id' => $request->category,
            'body' => $request->body
        ], $id);

        return redirect()->route('training.index')->with('success', 'Data latih berhasil di ubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $news = $this->news->find($request->id);
        $news->delete();
        return redirect()->route('training.index')->with('success', 'Data latih berhasil di hapus');
    }
}
