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
                                            <option value="-9">9 kali lebih buruk</option>
                                            <option value="-8">8 kali lebih buruk</option>
                                            <option value="-7">7 kali lebih buruk</option>
                                            <option value="-6">6 kali lebih buruk</option>
                                            <option value="-5">5 kali lebih buruk</option>
                                            <option value="-4">4 kali lebih buruk</option>
                                            <option value="-3">3 kali lebih buruk</option>
                                            <option value="-2">2 kali lebih buruk</option>
                                            <option value="1">sama baik</option>
                                            <option value="2">2 kali lebih baik</option>
                                            <option value="3">3 kali lebih baik</option>
                                            <option value="4">4 kali lebih baik</option>
                                            <option value="5">5 kali lebih baik</option>
                                            <option value="6">6 kali lebih baik</option>
                                            <option value="7">7 kali lebih baik</option>
                                            <option value="8">8 kali lebih baik</option>
                                            <option value="9">9 kali lebih baik</option>
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
                            <table class="table table-bordered">
                                <tbody id="table-matrix-tbody">

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

    $(function () {
    //   $('#table-kriteria').DataTable();
        inserttablematrix();
        getCR();

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

</script>
@endpush
