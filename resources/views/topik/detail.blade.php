@extends('layouts.app')

@section('title', $title)

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
                            <input type="text" class="form-control" id="judultopik" placeholder="Judul Topik" name="judultopik" value="{{ $topik->judul }}" disabled>
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
                                        <button type="button" class="btn btn-sm btn-primary" data-id="{{$kriteria['id']}}" data-toggle="modal" data-target="#editKriteriaModal"><i class="fas fa-edit"></i></button>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="deletekriteria({{$kriteria['id']}})"><i class="fas fa-trash"></i></button>
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
                                    @foreach ($alterntifs as $alternatif)
                                    <tr>
                                        <td> {{ $loop->iteration }} </td>
                                        <td> {{$alternatif['nama']}} </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" data-id="{{$alternatif['id']}}" data-toggle="modal" data-target="#editAlternatifModal"><i class="fas fa-edit"></i></button>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="deletealternatif({{$alternatif['id']}})"><i class="fas fa-trash"></i></button>
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
                <form role="form" enctype="multipart/form-data" role="form" method="POST" action="{{route('topik.kriteria.tambah',$topik->id)}}" id="tambahkriteria">
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
    <div class="modal fade" id="alternatifModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Alternatif</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form role="form" enctype="multipart/form-data" role="form" method="POST" action="{{route('topik.alternatif.tambah',$topik->id)}}" id="tambahalternatif">
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
                <form role="form" enctype="multipart/form-data" role="form" method="POST" action="{{route('topik.kriteria.update',$topik->id)}}" id="editkriteria">
                @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="valEditkriteria">Kriteria</label>
                            <input type="text" class="form-control" id="valEditkriteria" placeholder="Nama Kriteria" name="kriteria">
                            <input type="hidden" class="form-control" id="kriteriaId" placeholder="Nama Kriteria" name="id">
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
    <div class="modal fade" id="editAlternatifModal">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Alternatif</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form role="form" enctype="multipart/form-data" role="form" method="POST" action="{{route('topik.alternatif.update',$topik->id)}}" id="editalternatif">
                    @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="valEditAlternatif">Alternatif</label>
                                <input type="text" class="form-control" id="valEditAlternatif" placeholder="Nama Alternatif" name="alternatif">
                                <input type="hidden" class="form-control" id="alternatifId" name="id">
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

    function tbodykriteria(response) {
        var html = "";
        response.forEach(function(element, index){
            html += '<tr>'+
                '<td>'+ (parseInt(index) + 1) +'</td>'+
                '<td>'+ element.nama +'</td>'+
                '<td><button type="button" class="btn btn-sm btn-primary" data-id="'+element.id+'" data-toggle="modal" data-target="#editKriteriaModal"><i class="fas fa-edit"></i></button>'+
                ' <button type="button" class="btn btn-sm btn-danger" onclick="deletekriteria('+element.id+')"><i class="fas fa-trash"></i></button></td>'+
            '</tr>';
        })

        $("#table-kriteria-tbody").empty().append(html);
        $('#table-kriteria').DataTable();
    }

    function tbodyalternatif(response) {
        var html = "";
        response.forEach(function(element, index){
            html += '<tr>'+
                '<td>'+ (parseInt(index) + 1) +'</td>'+
                '<td>'+ element.nama +'</td>'+
                '<td><button type="button" class="btn btn-sm btn-primary" data-id="'+element.id+'" data-toggle="modal" data-target="#editAlternatifModal"><i class="fas fa-edit"></i></button>'+
                ' <button type="button" class="btn btn-sm btn-danger" onclick="deletealternatif('+element.id+')"><i class="fas fa-trash"></i></button></td>'+
            '</tr>';
        })

        $("#table-alterntif-tbody").empty().append(html);
        $('#table-alternatif').DataTable();
    }

    $("#tambahkriteria").submit(function(e) {
        e.preventDefault();

        Swal.fire({
            onBeforeOpen: () => {
                Swal.showLoading()
            },
        })

        var form = $(this);
        var url = form.attr('action');

        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            success: function(response)
            {
                tbodykriteria(response)

                $('#kriteriaModal').modal('toggle');
                $('#kriteria').val("");

                Swal.close();
                // Swal.fire({
                //     type: 'success',
                //     title: 'Berhasil',
                //     text: 'Data berhasil tersimpan!',
                // })
            },
            error : function(response){
                Swal.close();
                // console.log(response);
                Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Data gagal tersimpan!',
                })
            }

        });
    });

    $("#editKriteriaModal").on('show.bs.modal', function(event){
        Swal.fire({
            onBeforeOpen: () => {
                Swal.showLoading()
            },
        })

        var button = $(event.relatedTarget);
        var kriteriaId = button.data('id');
        $('#kriteriaId').val(kriteriaId);

        $.ajax({
            url : "{{ route('topik.kriteria.get') }}",
            type : "GET",
            dataType : "json",
            data : {"id":kriteriaId},
            success : function(response){
                $('#valEditkriteria').val(response.nama);
                Swal.close();
            }
        })
    });

    $("#editkriteria").submit(function(e) {
        e.preventDefault();

        Swal.fire({
            onBeforeOpen: () => {
                Swal.showLoading()
            },
        })

        var form = $(this);
        var url = form.attr('action');

        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            success: function(response)
            {
                tbodykriteria(response)
                $('#editKriteriaModal').modal('toggle');

                Swal.close();
                Swal.fire({
                    type: 'success',
                    title: 'Berhasil',
                    text: 'Data berhasil tersimpan!',
                })
            },
            error : function(response){
                Swal.close();
                Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Data gagal tersimpan!',
                })
            }
        });
    });

    function deletekriteria(id){
        var kriteriaId = id;

        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "untuk menghapus kriteria ini!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin!'
            }).then((result) => {
            if (result.value) {
                $.ajax({
                    url : "{{ route('topik.kriteria.delete', $topik->id ) }}",
                    type : "POST",
                    dataType : "json",
                    data : {
                        '_token' : $('meta[name="csrf-token"]').attr('content'),
                        "id":kriteriaId
                    },
                    success : function(response){
                        tbodykriteria(response)
                        Swal.fire(
                            'Terhapus!',
                            'Data berhasil dihapus.',
                            'success'
                        )
                    }
                })
            }
        })
    }


    $("#tambahalternatif").submit(function(e) {
        e.preventDefault();

        Swal.fire({
            onBeforeOpen: () => {
                Swal.showLoading()
            },
        })

        var form = $(this);
        var url = form.attr('action');

        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            success: function(response)
            {
                tbodyalternatif(response)

                $('#alternatifModal').modal('toggle');
                $('#alternatif').val("");

                Swal.close();
                // Swal.fire({
                //     type: 'success',
                //     title: 'Berhasil',
                //     text: 'Data berhasil tersimpan!',
                // })
            },
            error : function(response){
                Swal.close();
                // console.log(response);
                Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Data gagal tersimpan!',
                })
            }
        });
    });

    $("#editAlternatifModal").on('show.bs.modal', function(event){
        Swal.fire({
            onBeforeOpen: () => {
                Swal.showLoading()
            },
        })

        var button = $(event.relatedTarget);
        var alternatifId = button.data('id');
        $('#alternatifId').val(alternatifId);

        $.ajax({
            url : "{{ route('topik.alternatif.get',) }}",
            type : "GET",
            dataType : "json",
            data : {"id":alternatifId},
            success : function(response){
                $('#valEditAlternatif').val(response.nama);
                Swal.close();
            }
        })
    });

    $("#editalternatif").submit(function(e) {
        e.preventDefault();

        Swal.fire({
            onBeforeOpen: () => {
                Swal.showLoading()
            },
        })

        var form = $(this);
        var url = form.attr('action');

        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            success: function(response)
            {
                tbodyalternatif(response)
                $('#editAlternatifModal').modal('toggle');

                Swal.close();
                Swal.fire({
                    type: 'success',
                    title: 'Berhasil',
                    text: 'Data berhasil tersimpan!',
                })
            },
            error : function(response){
                Swal.close();
                Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Data gagal tersimpan!',
                })
            }
        });
    });

    function deletealternatif(id){
        var alternatifId = id;

        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "untuk menghapus alternatif ini!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin!'
            }).then((result) => {
            if (result.value) {
                $.ajax({
                    url : "{{ route('topik.alternatif.delete', $topik->id ) }}",
                    type : "POST",
                    dataType : "json",
                    data : {
                        '_token' : $('meta[name="csrf-token"]').attr('content'),
                        "id":alternatifId
                    },
                    success : function(response){
                        tbodyalternatif(response)
                        Swal.fire(
                            'Terhapus!',
                            'Data berhasil dihapus.',
                            'success'
                        )
                    }
                })
            }
        })
    }

</script>
@endpush
