<?php 
namespace App\Repositories;

use App\Models\NewsCategory;
use Prettus\Repository\Eloquent\BaseRepository;

class NewsCategoryRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return void
     */
    function model()
    {
        return NewsCategory::class;
    }
    
    public function getAllCategory()
    {
        return NewsCategory::get();
    }
}