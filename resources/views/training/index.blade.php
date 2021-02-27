@extends('layout.app')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
@endsection
@section('content')
<div class="my-3 p-3 bg-white rounded shadow-sm">
    <div class="my-3 p-3 bg-white rounded shadow-sm">
        <h6 class="border-bottom border-gray pb-2 mb-0">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalCreateCategory">Tambah</button>
            <div class="text-center" >Kategori </div>
        </h6>
        @if (session('category_success'))
            <div class="col-sm-12">
                <div class="alert  alert-success alert-dismissible fade show" role="alert">
                    {{ session('category_success') }}
                        
                </div>
            </div>
        @endif

        <div class="container">
        <table class="table">
            <thead>
            <th>No</th>
            <th>Kategori</th>
            <th>Opsi</th>
            </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $category->category }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm btn-edit-category"
                        data-id="{{ $category->id }}"
                        data-name="{{ $category->category }}"
                        >Edit</button>
                        <button class="btn btn-sm btn-danger delete-category" 
                        data-id="{{ $category->id }}"
                        >Hapus</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
        </table>
        </div>

    </div>

    <div class="my-3 p-3 bg-white rounded shadow-sm">
        <form class="form-inline">
                         
            <div class="form-group mb-3 mx-2">
                <select name="category" id="" class="form-control">
                    <option value="">Pilih Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ ($category->id == request()->query('category') ? 'selected' : '') }}>
                            {{ $category->category }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <button type="submit" class="btn btn-outline-primary mb-2 mx-2"><i class="fa fa-filter"></i> Filter</button>
            <a href="{{ route('training.index') }}" class="btn btn-outline-danger mb-2"><i class="fa fa-reset"></i> Reset</a>
        </form>
        <h6 class="border-bottom border-gray pb-2 mb-0">
            <a class="btn btn-primary btn-sm" href="{{ route('training.create') }}">Tambah</a>
            <div class="text-center">Data Latih</div>
        </h6>


        <div class="container">
        @if (session('success'))
            <div class="col-sm-12">
                <div class="alert  alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                        
                </div>
            </div>
        @endif
        <table class="table" id="tableData">
        <thead>
            <th>No</th>
            <th>Judul</th>
            <th>Kategori</th>
            <th>Opsi</th>
        </thead>
        <tbody>
            @foreach ($news as $key => $item)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->category->category }}</td>
                    <td>
                        <a class="btn btn-success btn-sm" href="{{ route('training.show', $item->id) }}">Detail</a>
                        <a class="btn btn-warning btn-sm" href="{{ route('training.edit', $item->id) }}">Edit</a>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $item->id }}">Hapus</button>

                    </td>
                </tr>
            @endforeach
        </tbody>
        </table>
        </div>

    </div>
</div>
<form id="form-delete-category" action="{{ route('category.destroy') }}" class="d-none" method="POST">
    @csrf
    @method('DELETE')
    <input type="hidden" name="id" id="delete-id">
</form>
<form id="form-delete" action="{{ route('training.destroy') }}" class="d-none" method="POST">
    @csrf
    @method('DELETE')
    <input type="hidden" name="id" id="delete-news-id">
</form>

<div class="modal fade" id="modalCreateCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Kategori</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST" action="{{ route('category.store') }}">
        @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>Kategori</label>
                    <input type="text" class="form-control" name="category" required>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
              </div>
        </form>
        
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalUpdateCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ubah Kategori</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST" action="{{ route('category.update') }}">
        @method('PUT')
        @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>Kategori</label>
                    <input type="text" class="form-control" name="category" required>
                    <input type="hidden" name="id">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
              </div>
        </form>
        
      </div>
    </div>
  </div>
  
@endsection
@section('js')
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tableData').DataTable({
                "pageLength": 50
            });

            $('.btn-delete').click(function () {
                if (confirm('Anda yakin akan menghapus data ini?')) {
                    $('#delete-news-id').val($(this).attr('data-id'));
                    $('#form-delete').submit();
                }
            });

            $('.delete-category').click(function () {
                if (confirm('Anda yakin akan menghapus data ini?')) {
                    $('#delete-id').val($(this).attr('data-id'));
                    $('#form-delete-category').submit();
                }
            });

            $('.btn-edit-category').click(function () {
                const id = $(this).attr('data-id');
                const name = $(this).attr('data-name');
                $("input[name=id]").val(id);
                $("input[name=category]").val(name);
                $('#modalUpdateCategory').modal('show');
            });
        } );
        

    </script>
@endsection