@extends('admin.layouts.master')

@section('content')
    @include('admin.common.message')

    @php
        $folder = request('folder_id') ? \App\Models\MediaFolder::find(request('folder_id')) : null;
        $backUrl = $folder ? $folder->edit_url : route('admin.media.index');
    @endphp

    <h2 class="pb-3 border-bottom">
        Add New Media
        @if(request('folder_id'))
            <a href="{{ route('admin.media.folders') }}" class="btn btn-outline-secondary btn-sm">← All Folders</a>
        @endif
        <a href="{{ $backUrl }}" class="btn btn-secondary float-right backbtn">Back</a>
    </h2>

    {{ Form::model('', ['route' => ['admin.media.createsave'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
      <div class="form-row">
          <label for="icon">Upload Image</label>
          {!! Field::file('urlkey','') !!}
      </div>
      <div class="form-row">
          <label for="folder_id">Folder</label>
          <select name="folder_id" class="form-control">
              <option value="">-- No Folder --</option>
              @foreach(\App\Models\MediaFolder::orderBy('name')->get() as $f)
                  <option value="{{ $f->id }}" {{ old('folder_id', request('folder_id')) == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
              @endforeach
          </select>
      </div>
      <div class="form-row">
          <label for="title">Title</label>
          <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" >
      </div>
      <div class="form-row">
          <label for="alt">Alt</label>
          <input type="text" class="form-control" id="alt" name="alt" value="{{ old('alt') }}" >
      </div>
      <div class="form-row">
        <label for="excerpt">Short Description</label>
        <textarea class="form-control editor" name="excerpt" rows="5">{{ old('excerpt') }}</textarea>
      </div>

      <div class="form-row  mt-3">
          <button type="submit" class="btn btn-primary">Save</button>
          <a href="{{ $backUrl }}" class="btn btn-primary backbtn">Back</a>
      </div>
    {{ Form::close() }}
@endsection