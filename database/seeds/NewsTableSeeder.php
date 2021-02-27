<?php

use App\Models\News;
use Illuminate\Database\Seeder;

class NewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        News::create([
            'title' => 'Menjelang Pemilu',
            'body' => 'Tokoh politik dari berbagai partai mengadakan rapat untuk membahas koalisi baru menjelang pemilu 2014 dan beberapa pilkada 2012 dan 2013.',
            'category_id' => 2
        ]);
        
        News::create([
            'title' => 'Kepentingan Partai Politik',
            'body' => 'Partai politik sudah tidak dapat dipercaya. Sebagian besar partai mengutamakan kepentingan partai daripada kebutuhan rakyat.',
            'category_id' => 2
        ]);

        News::create([
            'title' => 'Partai Demokrat',
            'body' => 'Partai demokrat memenangkan pemilu 2009 karena figur SBY. Partai Golkar berusaha menang pada 2012. Pertandingan 2 partai ini akan seru.',
            'category_id' => 2
        ]);

        News::create([
            'title' => 'Persema vs Persebaya',
            'body' => 'Pertandingan pertama antara Persema dan Persebaya diadakan di Malang. Ini akan menguntungkan tuan rumah.',
            'category_id' => 1
        ]);

        News::create([
            'title' => 'Persebaya',
            'body' => 'Beberapa pertandingan sepakbola yang dilakoni persebaya pada masa kampanye Pilkada 2010 Kota surabaya akan ditunda.',
            'category_id' => 1
        ]);

        News::create([
            'title' => 'Sebakbola indonesia',
            'body' => 'Sepakbola Indonesia memang belum bangkit. Manajemen tim, pertandingan dan tiket perlu ditingkatkan, bukan hanya fokus pada kemenangan tim.',
            'category_id' => 1
        ]);
    }
}
