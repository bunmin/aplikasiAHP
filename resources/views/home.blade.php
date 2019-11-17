@extends('layouts.app')

@section('title', $title)

@push('last-header')
@endpush

@section('content')
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

    <!-- Default box -->
    <div class="card">
      <div class="card-body">
        <div class="row center">
            Aplikasi AHP
            <br>
            Ini adalah aplikasi Sistem Pendukung Keputusan menggunakan metode AHP.
        </div>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->

</section>
@endsection

@push('after-footer')
@endpush
