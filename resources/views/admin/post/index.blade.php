@extends('admin.layouts.master')


@section('content')


    @include('admin.common.message')

    <h2 class="pb-3 border-bottom">
        Post <a href="{{ route('admin.post.create') }}" class="btn btn-secondary float-right addnewbtn">+ Add New</a>
    </h2>
    
    <table class="table table-bordered yajra-datatable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Status</th>
                <th>Created</th>
                <th>Action</th>
                <th>URL Key</th>
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
            ajax: "{{ route('admin.post.list') }}",
            columns: [
                //{data: 'DT_RowIndex', name: 'DT_RowIndex',className: "id"},
                {data: 'id',     name: 'id',className: "id"},
                {data: 'title',     name: 'title'},
                {data: 'status',     name: 'status'},
                {data: 'created_at',     name: 'created_at',className: "createdat"},
                {
                    data: 'action', 
                    name: 'action', 
                    orderable: true, 
                    searchable: true,
                    className: "action"
                },
                {data: 'urlkey',    name: 'urlkey', searchable : true, visible: false},
            ]
        });
        
      });
    </script>

@endsection



