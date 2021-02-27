@extends('layout.app')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
@endsection
@section('content')
<div class="my-3 p-3 bg-white rounded shadow-sm">
    <h6 class="border-bottom border-gray pb-2 mb-3">
        <p>Judul : {{ $news->title }}</p>
        <p class="mt-2">Kategori : {{ $news->category->category }}</p>
    </h6>
    
    <div class="container">
        <p>
            {{ $news->body }}
        </p>
        <p class="mt-4">
            <strong>Hasil stemming :</strong> <br>
            {{ $news->body_stemmed }}
        </p>
    </div>
</div>
@endsection