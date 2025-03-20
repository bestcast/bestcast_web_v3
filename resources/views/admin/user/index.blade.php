@extends('admin.layouts.master')


@section('content')


    @include('admin.common.message')

    <h2 class="pb-3 border-bottom">
        {{ $title }}
        <a href="{{ route('admin.user.create') }}" class="btn btn-secondary float-right addnewbtn">+ Add New</a>
    </h2>

    <table class="table table-bordered yajra-datatable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Roles</th>
                <th>Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <script type="text/javascript">
      jQuery(document).ready(function ($) {
        
        $.noConflict();
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            "order": [[ 0, "desc" ]],
            ajax: "{{ route('admin.user.list',$type) }}",
            columns: [
                //{data: 'DT_RowIndex', name: 'DT_RowIndex',className: "id"},
                {data: 'id', name: 'id',className: "id"},
                {data: 'name', name: 'name', className: "name"},
                {data: 'phone', name: 'phone', className: "phone"},
                {data: 'roles', name: 'roles', className: "roles"},
                {data: 'created_at', name: 'created_at', className: "created_at"},
                {
                    data: 'action', 
                    name: 'action', 
                    orderable: true, 
                    searchable: true,
                    className: "action"
                },
            ]
        });


        $('#search-form').on('submit', function(e) {
            table.draw();
            e.preventDefault();
        });
        
      });
    </script>


@endsection



