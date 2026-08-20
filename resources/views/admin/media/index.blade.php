@extends('admin.layouts.master')
@section('content')
    @include('admin.common.message')
    <h2 class="pb-3 border-bottom">
        Media
        @if(request('folder_id'))
            <a href="{{ route('admin.media.folders') }}" class="btn btn-outline-secondary btn-sm">← All Folders</a>
        @endif
        @if(!request('picker'))
            <a href="{{ route('admin.media.create') }}?folder_id={{ request('folder_id') }}" class="btn btn-secondary float-right addnewbtn">+ Add New</a> &nbsp;
            <a href="{{ route('admin.media.folders') }}" class="btn btn-secondary float-right backbtn">Back</a>
        @endif
    </h2>

    <div class="row g-2 applyfilter">
      @include('admin.common.filter.searchlist')
    </div>

    @if(!empty($model->total()))
        <div class="txtcard image small media">
            <div class="row">
            @foreach($model->items() as $item)
            <div class="col-4 col-xxl-3 ">
                <div class="card @if(request('picker')) pickercard @endif" data-id="{{ $item->id }}" data-urlkey="{{ $item->urlkey }}" data-fullurl="{{ !empty($item->urlkey)?Lib::publicImgSrc($item->urlkey):'' }}">
                  <div class="card-body">
                    <div class="card-image" style="background-image: url({{ !empty($item->urlkey)?Lib::publicImgSrc($item->urlkey):0 }});">
                        @if(request('picker'))
                            <div class="pickerCheck"><span>&#10003;</span></div>
                        @else
                        <div class="d-flex overlay justify-content-center "><div class="d-flex align-self-center">
                            <div class="card-act">
                                <a href="{{ route('admin.media.view',$item->id) }}" class="btn btn-success btn-sm">View</a>
                                <a href="{{ route('admin.media.delete',$item->id) }}" class="btn btn-danger btn-sm btn-delete-copy-{{ $item->id }}" data-bs-toggle="modal" data-bs-target="#delete{{ $item->id }}">Delete</a>
                                <a href="{{ route('admin.media.edit',$item->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            </div>
                        </div></div>
                        @endif
                    </div>
                  </div>
                </div>
            </div>
            @endforeach
            </div>
        </div>
        <div class="d-flex justify-content-center mt-5 paginationCt">
            <div class="d-flex">
                {{ $model->appends(request()->query())->onEachSide(1)->links() }}
            </div>
        </div>
    @else
        @include('admin.common.noresult')
    @endif

    @if(!request('picker') && !empty($model->total()))
        @foreach($model->items() as $item)
            @php ($delid=$item->id)
            @php ($delurl=route('admin.media.delete',$item->id))
            @include('admin.common.modaldelete')
        @endforeach
    @endif

    @if(request('picker'))
    <div class="pickerFooter">
        <button type="button" class="btn btn-secondary" id="pickerCancel">Close</button>
        <button type="button" class="btn btn-primary" id="pickerSelect" disabled>Select</button>
    </div>

    <style>
        .pickercard { cursor:pointer; border:2px solid transparent; }
        .pickercard.selected { border:2px solid #28a745; }
        .pickerCheck { display:none; position:absolute; top:6px; right:6px; background:#28a745; color:#fff; border-radius:50%; width:24px; height:24px; text-align:center; line-height:24px; }
        .pickercard.selected .pickerCheck { display:block; }
        .card-image { position:relative; }
        .pickerFooter { position:fixed; bottom:0; left:0; right:0; background:#fff; padding:12px 20px; text-align:right; border-top:1px solid #ddd; z-index:999; }
    </style>

    <script>
        var selectedCard = null;
        var pickerField = "{{ request('field') }}";

        document.querySelectorAll('.pickercard').forEach(function(card){
            card.addEventListener('click', function(){
                document.querySelectorAll('.pickercard').forEach(function(c){ c.classList.remove('selected'); });
                card.classList.add('selected');
                selectedCard = card;
                document.getElementById('pickerSelect').disabled = false;
            });
        });

        document.getElementById('pickerCancel').addEventListener('click', function(){
            window.close();
        });

        document.getElementById('pickerSelect').addEventListener('click', function(){
            if(!selectedCard) return;
            var payload = {
                type: 'mediaSelected',
                field: pickerField,
                id: selectedCard.getAttribute('data-id'),
                urlkey: selectedCard.getAttribute('data-urlkey'),
                fullurl: selectedCard.getAttribute('data-fullurl')
            };
            var channel = new BroadcastChannel('media_picker_channel');
            channel.postMessage(payload);
            channel.close();
            window.close();
        });
    </script>
    @endif
@endsection