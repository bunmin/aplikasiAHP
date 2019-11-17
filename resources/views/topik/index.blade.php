@extends('layouts.app')

@push('last-header')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('bower_components/admin-lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
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
        </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content">
    <div class="container-fluid">
        <!-- Default box -->
        <div class="card">
        <div class="card-body">
                <div class="card-header">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal">
                        <i class="fas fa-plus"></i> Buat Topik
                    </button>
                </div>

                <div class="card-body">
                    <table id="topiktable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul Topik</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topiks as $topik)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{$topik['judul']}} </td>
                                <td> {{$topik['keterangan']}} </td>
                                <td>
                                    <a href="{{ route('topik.detail', $topik['id']) }}" class="btn btn-sm btn-info"><i class="fas fa-plus"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
        </div>
        <!-- /.card-body -->
        </div>
    </div>
    <!-- /.card -->
    <div class="modal fade" id="exampleModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Topik</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form" enctype="multipart/form-data" role="form" method="POST" action="{{route('topik.tambah')}}" id="tambahtopik">
                        @csrf
                            <div class="form-group">
                                <label for="judultopik">Judul Topik</label>
                                <input type="text" class="form-control" id="judultopik" placeholder="Judul Topik" name="judultopik">
                            </div>
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea class="form-control" name="keterangan" id="keterangan" cols="30" rows="4"></textarea>
                            </div>
                        <!-- /.card-body -->
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary"
                        onclick="event.preventDefault(); document.getElementById('tambahtopik').submit();"
                    >Simpan</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</section>
@endsection

@push('after-footer')
<!-- DataTables -->
<script src="{{ asset('bower_components/admin-lte/plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('bower_components/admin-lte/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script>
    $(function () {
      $('#topiktable').DataTable();
    });


</script>
@endpush
