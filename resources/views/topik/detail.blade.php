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
                <div class="card-header">
                    <h3 class="card-title">
                        KRITERIA
                    </h3>
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#kriteriaModal">
                            <i class="fas fa-plus"></i> Tambah Kriteria
                        </button>
                    </div>
                    <div class="form-group">
                        <table id="table-kriteria" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kriteria</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="table-kriteria-tbody">
                                @foreach ($kriterias as $kriteria)
                                <tr>
                                    <td> {{ $loop->iteration }} </td>
                                    <td> {{$kriteria['nama']}} </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-id=" {{$kriteria['id']}} " data-toggle="modal" data-target="#editKriteriaModal"><i class="fas fa-edit"></i></button>
                                        <a href="" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
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

            <!-- Default box -->
        <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <h3 class="card-title">
                            ALTERNATIF
                        </h3>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#alternatifModal">
                                <i class="fas fa-plus"></i> Tambah alternatif
                            </button>
                        </div>
                        <div class="form-group">
                            <table id="table-alternatif" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Alternatif</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="table-alterntif-tbody">
                                    @foreach ($alterntifs as $alterntif)
                                    <tr>
                                        <td> {{ $loop->iteration }} </td>
                                        <td> {{$alterntif['nama']}} </td>
                                        <td>
                                            <a href="" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                            <a href="" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
        </div>
    </div>
    <!-- /.card -->
    <div class="modal fade" id="kriteriaModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Kriteria</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form role="form" enctype="multipart/form-data" role="form" method="POST" action="{{route('topik.tambah.kriteria',$topik->id)}}" id="tambahkriteria">
                @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="kriteria">Kriteria</label>
                            <input type="text" class="form-control" id="kriteria" placeholder="Nama Kriteria" name="kriteria">
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary"
                        >Simpan</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- /.card -->
    <div class="modal fade" id="editKriteriaModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Kriteria</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form" enctype="multipart/form-data" role="form" method="POST" action="{{route('topik.tambah.kriteria',$topik->id)}}" id="editkriteria">
                        @csrf
                            <div class="form-group">
                                <label for="editkriteria">Kriteria</label>
                                <input type="text" class="form-control" id="editkriteria" placeholder="Nama Kriteria" name="kriteria">
                            </div>
                        <!-- /.card-body -->
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary"
                        onclick="event.preventDefault(); document.getElementById('editkriteria').submit();"
                    >Simpan</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- /.card -->
    <div class="modal fade" id="alternatifModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Alternatif</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form role="form" enctype="multipart/form-data" role="form" method="POST" action="{{route('topik.tambah.alternatif',$topik->id)}}" id="tambahalternatif">
                @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="alternatif">Alternatif</label>
                            <input type="text" class="form-control" id="alternatif" placeholder="Nama Alternatif" name="alternatif">
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary"
                        >Simpan</button>
                    </div>
                </form>
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
      $('#table-kriteria').DataTable();
      $('#table-alternatif').DataTable();
    });

    $("#tambahkriteria").submit(function(e) {
         e.preventDefault();

        var form = $(this);
        var url = form.attr('action');

        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            success: function(response)
            {
                console.log(response);
                var html = "";
                response.forEach(function(element, index){
                    html += '<tr>'+
                        '<td>'+ (parseInt(index) + 1) +'</td>'+
                        '<td>'+ element.nama +'</td>'+
                        '<td></td>'+
                    '</tr>';
                })

                $('#kriteriaModal').modal('toggle');
                $('#kriteria').val("");
                $("#table-kriteria-tbody").empty().append(html);
                // $("#table-kriteria").DataTable();
            }
        });
    });

    $("#tambahalternatif").submit(function(e) {
        e.preventDefault();

        var form = $(this);
        var url = form.attr('action');

        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            success: function(response)
            {
                console.log(response);
                var html = "";
                response.forEach(function(element, index){
                    html += '<tr>'+
                        '<td>'+ (parseInt(index) + 1) +'</td>'+
                        '<td>'+ element.nama +'</td>'+
                        '<td></td>'+
                    '</tr>';
                })

                $('#alternatifModal').modal('toggle');
                $('#alternatif').val("");
                $("#table-alterntif-tbody").empty().append(html);
                // $("#table-kriteria").DataTable();
            }
        });
    });

    // $("#editKriteriaModal").on('show.bs.modal', function(event){
    //     var button = $(event.relatedTarget);
    //     var kriteriaId = button.data('id');
    // });

</script>
@endpush
