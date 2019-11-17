@extends('layouts.app')

@section('title', $title)

@push('last-header')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('bower_components/admin-lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('bower_components/sweetalert2/dist/sweetalert2.min.css') }}">
@endpush

@section('content')

@if ($message = Session::get('message'))
<div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $message }}</strong>
</div>
@endif
@if ($message = Session::get('error'))
<div class="alert alert-danger alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $error }}</strong>
</div>
@endif

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{$title}}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('topik.index') }}">Topik</a></li>
                    <li class="breadcrumb-item active">{{$title}}</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content">
    <div class="container-fluid">
        <!-- Default box -->
        <div class="card">
                <div class="card-body">
                    <dl>
                        <div class="form-group">
                            <label for="judultopik">Judul Topik</label>
                            <input type="text" class="form-control" id="judultopik" placeholder="Judul Topik" name="judultopik" value={{ $topik->judul }} disabled>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control" name="keterangan" id="keterangan" cols="30" rows="4" disabled> {{ $topik->keterangan }} </textarea>
                        </div>
                    </dl>
                </div>
                <!-- /.card-body -->
        </div>

        <!-- Default box -->
        <div class="card">
            <div class="card-body">
                {{-- <div class="card-header">
                    <h3 class="card-title">
                        BOBOT
                    </h3>
                </div> --}}

                <div class="card-body">
                    {{-- <div class="form-group">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#kriteriaModal">
                            <i class="fas fa-plus"></i> Tambah Kriteria
                        </button>
                    </div> --}}
                    <div class="form-group">
                        <table id="table-kriteria" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Matrix Perbandingan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="table-kriteria-tbody">
                                <tr>
                                    <td> 0 </td>
                                    <td> Kriteria </td>
                                    <td>
                                        <a href="{{ route('matrix.kriteria', $topik['id']) }}" type="button" class="btn btn-sm btn-primary" >Input Matrix</a>
                                    </td>
                                </tr>
                                @foreach ($kriterias as $kriteria)
                                <tr>
                                    <td> {{ $loop->iteration }} </td>
                                    <td> {{$kriteria['nama']}}</td>
                                    <td>
                                        <a href="" type="button" class="btn btn-sm btn-primary" >Input Matrix</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
</section>
@endsection

@push('after-footer')
<!-- DataTables -->
<script src="{{ asset('bower_components/admin-lte/plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('bower_components/admin-lte/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script>
    $(function () {
      $('#table-kriteria').DataTable();
    });
</script>
@endpush
