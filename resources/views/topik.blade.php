@extends('layouts.app')
@push('last-header')
@endpush
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('bower_components/admin-lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
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
            <table id="topiktable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No</th>
                  <th>Judul Topik</th>
                  <th>Keterengan</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td>1</td>
                  <td>Internet
                    Explorer 4.0
                  </td>
                  <td>Win 95+</td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>Internet
                    Explorer 5.0
                  </td>
                  <td>Win 95+</td>
                </tr>
                </tbody>
              </table>

      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->

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
