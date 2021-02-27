<?php 
namespace App\Repositories;

use App\Models\News;
use Prettus\Repository\Eloquent\BaseRepository;

class NewsRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return void
     */
    function model()
    {
        return News::class;
    }

    /**
     * Get all news
     *
     * @return void
     */
    public function getAllNews()
    {
        return News::with(['category'])->orderBy('id', 'asc')->limit(300)->get();
    }

    public function getLatestNews($request = null)
    {
        $news = News::with(['category'])->orderBy('id', 'desc');

        if ($request->category) {
            $news->where('category_id', $request->category);
        }
        
        return $news->get();
    }

    /**
     * Get spesific news 
     *
     * @param [Integer] $id
     * @return void
     */
    public function getNews($id)
    {
        return News::find($id);
    }
}