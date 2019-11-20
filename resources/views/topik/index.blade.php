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
                            @php
                            // print_r($topiks);
                            @endphp
                            @foreach ($topiks as $topik)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{$topik['judul']}} </td>
                                <td> {{$topik['keterangan']}} </td>
                                <td>
                                    <a href="{{ route('topik.detail', $topik['id']) }}" class="btn btn-sm btn-info"><i class="fas fa-plus"></i></a>
                                    <a href="{{ route('matrix.index', $topik['id']) }}" class="btn btn-sm btn-primary">Matrix</a>
                                    @if ($topik['count_total'] > 0)
                                    <a href="#" class="btn btn-sm btn-primary" data-id="{{ $topik['id'] }}" data-toggle="modal" data-target="#chartHasil">Hasil</a>
                                    @endif
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
    <div class="modal fade" id="chartHasil">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Hasil Topik</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="chart_div"></div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    $(function () {
      $('#topiktable').DataTable();
    });

    function drawChartAlternatif(response) {

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Nama Alternatif');
        data.addColumn('number', 'Nilai Rata Rata');
        data.addColumn({type:'string', role:'style'});
        response.forEach(function(element, index){
            data.addRows([
                [element.nama, element.nilai_bobot,getRandomColor()],
            ]);
        })

        var options = {
            // title: "Density of Precious Metals, in g/cm^3",
            width: '750',
            height: '200',
            bar: {groupWidth: "95%"},
            legend: { position: "none" },
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data,options);

        Swal.close();
    }

    $("#chartHasil").on('show.bs.modal', function(event){
        Swal.fire({
            onBeforeOpen: () => {
                Swal.showLoading()
            },
        })

        var button = $(event.relatedTarget);
        var topikId = button.data('id');

        $.ajax({
            type: "GET",
            url: "{{ route('matrix.gettotal') }}",
            data: {'topikId':topikId},
            success: function(response)
            {
                google.charts.load('current', {
                    callback: function () {
                        drawChartAlternatif(response);
                    },
                    packages: ['corechart', 'bar']
                });
            },
            error : function(response){
                Swal.close();
                Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Data gagal diambil!',
                })
            }
        });
    });
</script>
@endpush
