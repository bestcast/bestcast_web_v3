@extends('admin.layouts.master')
@section('content')
    @include('admin.common.message')
    <h2 class="pb-3 border-bottom">
        Media Folders
        <button class="btn btn-secondary float-right" data-bs-toggle="modal" data-bs-target="#newFolder">+ New Folder</button>
    </h2>

    <div class="row g-2">
        @foreach($folders as $folder)
        <div class="col-4 col-xxl-3">
            <div class="card">
                <a href="{{ route('admin.media.index') }}?folder_id={{ $folder->id }}">
                    <div class="card-image" style="background-image: url({{ !empty($folder->coverImage)?Lib::publicImgSrc($folder->coverImage->urlkey):'' }}); height:150px;"></div>
                    <div class="card-body text-center">
                        <strong>{{ $folder->name }}</strong>
                        <div class="text-muted">{{ $folder->media_count }} files</div>
                    </div>
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <div class="modal fade" id="newFolder">
      <div class="modal-dialog">
        <div class="modal-content">
          {{ Form::open(['route' => 'admin.media.folders.save', 'method' => 'post']) }}
          <div class="modal-body">
              <input type="text" name="name" class="form-control" placeholder="Folder name (e.g. Locker, Keeno)" required>
          </div>
          <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Create</button>
          </div>
          {{ Form::close() }}
        </div>
      </div>
    </div>
@endsection