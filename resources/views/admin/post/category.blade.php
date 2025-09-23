@extends('admin.layouts.master')


@section('content')


    @include('admin.common.message')

    <h2 class="pb-3 border-bottom">
        Category <a href="{{ route('admin.post.category.create') }}" class="btn btn-secondary float-right addnewbtn">+ Add New</a>
    </h2>

    <div class="catlist">
    {!! App\Models\Category::divList() !!}
    </div>
    
    <?php /*
    <table class="table table-bordered yajra-datatable">
        <thead>
            <tr>
                <th>No</th>
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
            ajax: "{{ route('admin.post.category.list') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex',className: "id"},
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
    */?>
@endsection



