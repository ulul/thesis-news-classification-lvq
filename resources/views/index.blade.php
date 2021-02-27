@extends('layout.app')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
@endsection
@section('content')
<div class="my-3 p-3 bg-white rounded shadow-sm">
@if ($request->run)

<div class="my-3 p-3 bg-white rounded shadow-sm">
    <h6 class="border-bottom border-gray pb-2 mb-0"><strong>Hasil dari skenario pengujian {{ $request->data_latih }}% data latih dan {{ $request->data_uji }}% data uji</strong></h6>
    <div class="container">
        <table class="table tableData">
            <thead>
                <th>No</th>
                <th>Judul</th>
                <th>Kategori original</th>
                <th>Prediksi Kategori</th>
                <th>Hasil</th>
                <th>Detail</th>
            </thead>
            <tbody>
                @foreach ($results as $key => $result)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $result['news']->title }}</td>
                    <td>{{ $result['original'] }}</td>
                    <td>{{ $result['result'] }}</td>
                    <td>
                        {!! $result['result'] == $result['original'] ? "<span class='badge badge-success'>Benar</span>"
                        : "<span class='badge badge-danger'>Salah</span>" !!}</td>
                    <td>
                        <a target="__blank" href="{{ route('training.show', $result['news']->id) }}"
                            class="btn btn-primary">
                            Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="my-3 p-3 bg-white rounded shadow-sm">
    <h6 class="border-bottom border-gray pb-2 mb-0">Confusion Matrix</h6>
    <div class="container">
        <table class="table table-bordered">
            <thead>
                <th>Kelas / Prediksi</th>
                @foreach ($confusionMatrix['matrix'] as $key => $item)
                <th>{{ $key }}</th>
                @endforeach
            </thead>
            <tbody>
                @foreach ($confusionMatrix['matrix'] as $key => $item)
                <tr>
                    <th>{{ $key }}</th>
                    @foreach ($confusionMatrix['matrix'] as $keyValue => $itemValue)
                    <td>{{ $confusionMatrix['matrix'][$key][$keyValue] }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
        <p>Akurasi : {{ $confusionMatrix['accuration'] }}</p>
        <p>Presisi : {{ $confusionMatrix['precission'] }}</p>
        <p>Recall : {{ $confusionMatrix['recall'] }}</p>
        <p>F Measure : {{ $confusionMatrix['fmeasure'] }}</p>
    </div>
</div>
@else 
<form>
  <div class="form-group">
    <label for="exampleInputEmail1">Data Latih</label>
    <select name="data_latih" id="" class="form-control">
      <option value="70">70%</option>
      <option value="80">80%</option>
      <option value="90">90%</option>
    </select>
  </div>
  <div class="form-group">
    <label>Data Uji</label>
    <input type="text" name="data_uji" readonly class="form-control">
    <input type="hidden" name="run" value="ok">
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
@endif

</div>

@endsection
@section('js')
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('.tableData').DataTable({
            "scrollX": true,
        });
        getDataUji();
        $('select[name="data_latih"]').change(function () {
          getDataUji();
        })
    });

    function getDataUji() {
      let dataLatih = $('select[name="data_latih"]').val();
      $('input[name="data_uji"]').val(100-parseInt(dataLatih)+'%');
    }

</script>
@endsection
