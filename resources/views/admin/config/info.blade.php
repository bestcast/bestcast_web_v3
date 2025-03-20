@extends('admin.layouts.master')


@section('content')
    @include('admin.common.message')
    <h2 class="pb-2 border-bottom">API Connect</h2>
    <br>
    <div class="form-row">
      <label class="form-label red"for="post_id">[cards cat="<pid>"]</label>
      <p class="comment">ID from Content->Category-> [ID:xx]</p>      
    </div>
    <hr>
@endsection



