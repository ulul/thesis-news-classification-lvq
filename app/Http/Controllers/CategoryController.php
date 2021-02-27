<?php

namespace App\Http\Controllers;

use App\Models\NewsCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        NewsCategory::create([
            'category' => $request->category
        ]);
        return redirect()->route('training.index')->with('category_success', 'Kategori baru berhasil di tambahkan');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request)
    {
        $category = NewsCategory::find($request->id);
        $category->category = $request->category;
        $category->save();
        return redirect()->route('training.index')->with('category_success', 'Kategori baru berhasil di ubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $news = NewsCategory::find($request->id);
        $news->delete();
        return redirect()->route('training.index')->with('category_success', 'Data kategori berhasil di hapus');
    }
}
