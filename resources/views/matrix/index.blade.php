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
                                        @if($cekKriteriaHasil == 0)
                                        <button type="submit" class="btn btn-sm btn-success" data-toggle="modal" data-target="#chartHasilKriteria">Hasil</button>
                                        @endif
                                    </td>
                                </tr>
                                @foreach ($kriterias as $kriteria)
                                @php
                                    $cekAlternatifHasil =  App\Http\Controllers\MatrixController::cekAlternatifHasil($topik->id,$kriteria->id);
                                @endphp
                                <tr>
                                    <td> {{ $loop->iteration }} </td>
                                    <td> {{$kriteria['nama']}}</td>
                                    <td>
                                        <a href="{{ route('matrix.alternatif', [$topik['id'] , $kriteria['id']]) }}" type="button" class="btn btn-sm btn-primary" >Input Matrix</a>
                                        @if($cekAlternatifHasil == 0)
                                        <button type="submit" class="btn btn-sm btn-success" data-id="{{ $kriteria['id'] }}" data-toggle="modal" data-target="#chartHasilAlternatif">Hasil</button>
                                        @endif
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
    <!-- /.card -->
    <div class="modal fade" id="chartHasilKriteria">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Hasil Kriteria</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="chart_div_kriteria"></div>
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
    <div class="modal fade" id="chartHasilAlternatif">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Hasil Alternatif</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="chart_div_alternatif"></div>
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
    Swal.fire({
        onBeforeOpen: () => {
            Swal.showLoading()
        },
    })

    $(function () {
      $('#table-kriteria').DataTable();

      Swal.close();
    });

    function drawChartKriteria(response) {

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Nama Kriteria');
        data.addColumn('number', 'Nilai Rata Rata');
        data.addColumn({type:'string', role:'style'});
        response.forEach(function(element, index){
            data.addRows([
                [element.nama, element.rata_rata_nilai,getRandomColor()],
            ]);
        })

        var options = {
            // title: "Density of Precious Metals, in g/cm^3",
            width: '750',
            height: '200',
            bar: {groupWidth: "95%"},
            legend: { position: "none" },
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div_kriteria'));
        chart.draw(data,options);

        Swal.close();
        }

    $("#chartHasilKriteria").on('show.bs.modal', function(event){
        Swal.fire({
            onBeforeOpen: () => {
                Swal.showLoading()
            },
        })

        $.ajax({
            type: "GET",
            url: "{{ route('matrix.kriteria.getnilai',$topik->id) }}",
            data: {},
            success: function(response)
            {
                google.charts.load('current', {
                    callback: function () {
                        drawChartKriteria(response);
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

    function drawChartAlternatif(response) {

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Nama Alternatif');
        data.addColumn('number', 'Nilai Rata Rata');
        data.addColumn({type:'string', role:'style'});
        response.forEach(function(element, index){
            data.addRows([
                [element.nama, element.rata_rata_nilai,getRandomColor()],
            ]);
        })

        var options = {
            // title: "Density of Precious Metals, in g/cm^3",
            width: '750',
            height: '200',
            bar: {groupWidth: "95%"},
            legend: { position: "none" },
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div_alternatif'));
        chart.draw(data,options);

        Swal.close();
    }

    $("#chartHasilAlternatif").on('show.bs.modal', function(event){
        Swal.fire({
            onBeforeOpen: () => {
                Swal.showLoading()
            },
        })

        var button = $(event.relatedTarget);
        var kriteriaId = button.data('id');

        $.ajax({
            type: "GET",
            url: "{{ route('matrix.alternatif.getnilai',[$topik->id]) }}",
            data: {'kriteriaId':kriteriaId},
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
