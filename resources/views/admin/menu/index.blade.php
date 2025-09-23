@extends('admin.layouts.master')


@section('content')


    @include('admin.common.message')

    <h2 class="pb-3 border-bottom">
        Menu <a href="{{ route('admin.menu.create') }}" class="btn btn-secondary float-right addnewbtn">+ Add New</a>
    </h2>
    
    <div class="catlist">                  
    {!! App\Models\Menu::divList() !!}
    </div>



@endsection



