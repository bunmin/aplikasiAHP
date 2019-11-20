@extends('layouts.app')

@section('title', $title)

@push('last-header')
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
                    <li class="breadcrumb-item"><a href="{{ route('matrix.index',$topik->id) }}">Matrix Perbandingan</a></li>
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
                        Matrix Kriteria
                    </h3>
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <form role="form" enctype="multipart/form-data" role="form" method="POST" action="{{route('matrix.kriteria.updatebobot',$topik->id)}}" id="simpanbobot">
                            @csrf
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <select class="custom-select" id="opsi-kriteria-kiri" name="bobotkiri">
                                            <option>--Pilih Kriteria--</option>
                                            @foreach ($kriterias as $kriteria)
                                            <option value="{{ $kriteria->id }}"> {{ $kriteria->nama }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <select class="custom-select" name="nilaibobot">
                                            <option>--Pilih Bobot--</option>
                                            <option value="-9">9 kali kurang penting</option>
                                            <option value="-8">8 kali kurang penting</option>
                                            <option value="-7">7 kali kurang penting</option>
                                            <option value="-6">6 kali kurang penting</option>
                                            <option value="-5">5 kali kurang penting</option>
                                            <option value="-4">4 kali kurang penting</option>
                                            <option value="-3">3 kali kurang penting</option>
                                            <option value="-2">2 kali kurang penting</option>
                                            <option value="1">sama penting</option>
                                            <option value="2">2 kali lebih penting</option>
                                            <option value="3">3 kali lebih penting</option>
                                            <option value="4">4 kali lebih penting</option>
                                            <option value="5">5 kali lebih penting</option>
                                            <option value="6">6 kali lebih penting</option>
                                            <option value="7">7 kali lebih penting</option>
                                            <option value="8">8 kali lebih penting</option>
                                            <option value="9">9 kali lebih penting</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <select class="custom-select" id="opsi-kriteria-kanan" name="bobotkanan">
                                            <option>--Pilih Kriteria--</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group middle">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i>
                                    </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Inkonsistensi</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="cr" disabled >
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="card-body table-responsive p-0" style="height: 300px;">
                            <table class="table table-bordered">
                                <tbody id="table-matrix-tbody">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-group row" id="hasil-perbandingan">
                        <div class="form-group middle">
                            <button type="submit" class="btn btn-success" data-toggle="modal" data-target="#chartHasil">
                                {{-- <i class="fas fa-save"></i> --}}
                                Hasil
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
    <div class="modal fade" id="chartHasil">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Hasil Kriteria</h4>
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
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    Swal.fire({
        onBeforeOpen: () => {
            Swal.showLoading()
        },
    })

    function inserttablematrix(){
        $.ajax({
            url : "{{ route('matrix.kriteria.getall',$topik->id) }}",
            type : "GET",
            dataType : "json",
            data : {},
            success : function(response){
                $("#table-matrix-tbody").empty().append(response);
            }
        })
    }

    function getCR(){
        $.ajax({
            url : "{{ route('matrix.kriteria.getcr',$topik->id) }}",
            type : "GET",
            dataType : "json",
            data : {},
            success : function(response){
                $("#cr").val(response.toFixed(3));
            }
        })
    }

    function cekHasil(){
        $.ajax({
            url : "{{ route('matrix.kriteria.cekhasil',$topik->id) }}",
            type : "GET",
            dataType : "json",
            data : {},
            success : function(response){
                if (response == 0){
                    $('#hasil-perbandingan').show();
                }
            }
        })
    }

    $(function () {
        $('#hasil-perbandingan').hide();
        inserttablematrix();
        getCR();
        cekHasil();

        Swal.close();
    });

    $("#opsi-kriteria-kiri").change(function(e) {
        e.preventDefault();

        Swal.fire({
            onBeforeOpen: () => {
                Swal.showLoading()
            },
        })

        var kriteriaId = $(this).val();

        $.ajax({
            type: "GET",
            url: "{{ route('matrix.kriteria.getanother',$topik->id) }}",
            data: {'kriteriaid':kriteriaId},
            success: function(response)
            {
                var html = "<option>--Pilih Kriteria--</option>";
                response.forEach(function(element, index){
                    html += '<option value="'+element.id+'">'+element.nama+'</option>';
                })

                $("#opsi-kriteria-kanan").empty().append(html);

                Swal.close();
            }
        });
    });

    $("#simpanbobot").submit(function(e) {
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
                inserttablematrix();
                getCR();
                cekHasil();

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

    function drawChart(response) {

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

        $.ajax({
            type: "GET",
            url: "{{ route('matrix.kriteria.getnilai',$topik->id) }}",
            data: {},
            success: function(response)
            {
                google.charts.load('current', {
                    callback: function () {
                        drawChart(response);
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

        // Swal.close();
    });

</script>
@endpush
