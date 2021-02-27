@extends('layout.app')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
@endsection
@section('content')
<div class="my-3 p-3 bg-white rounded shadow-sm">
    <h6 class="border-bottom border-gray pb-2 mb-3">
        Tambah Data Latih
    </h6>
    <form action="{{ route('training.update', $news->id) }}" method="POST">
      @method('PUT')
      @csrf
        @if ($errors->any())
            <div class="form-group">
                <div class="alert alert-danger alert-has-icon w-100 mx-3">
                    <div class="alert-icon"><i class="fas fa-exclamation-circle"></i></div>
                    <div class="alert-body">
                    <div class="alert-title">Kesalahan Validasi</div>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="form-group">
          <label for="exampleFormControlInput1">Judul Berita</label>
          <input name="title" type="text" class="form-control" placeholder="Judul berita" value="{{ $news->title }}">
        </div>
        <div class="form-group">
          <label for="exampleFormControlSelect1">Kategori Berita</label>
          <select name="category" class="form-control" id="exampleFormControlSelect1">
            <option value="">Pilih Kategori</option>
            @foreach ($categories as $item)
                <option value="{{ $item->id }}"
                  {{ $item->id == $news->category_id ? "selected" : "" }}
                  >{{ $item->category }}</option>
            @endforeach
          </select>
        </div>
        
        <div class="form-group">
          <label for="exampleFormControlTextarea1">Teks Berita</label>
          <textarea name="body" class="form-control" id="exampleFormControlTextarea1" rows="3">{{ $news->body }}</textarea>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
</div>
@endsection