@extends('admin.layouts.master')


@section('content')


    @include('admin.common.message')

    <h2 class="pb-3 border-bottom">
        Notification <a href="{{ route('admin.notification.markread','markallread') }}" class="btn btn-secondary float-right addnewbtn">Mark all Read</a>
    </h2>
    
    @if(!empty($model->total()))
        <table class="table notifyTable">
          <thead>
            <tr>
              <th scope="col">Notification</th>
              <th scope="col">Date & Time</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($model->items() as $item)
                <tr>
                  <td>
                    {{ $item->title }}
                    @if($item->content)
                        <br><div class="comments">{{ $item->content }}</div>
                    @endif
                  </td>
                  <td>{{ $item->created_at }}</td>
                  <td>
                    @if($item->mark_read)
                        <a href="#!" onclick='markRead(this,{{ $item->id }},"{{ route('admin.notification.markread',$item->id) }}")'   class="linkMark mark-unread">Mark as unread</a>
                    @else
                        <a href="#!" onclick='markRead(this,{{ $item->id }},"{{ route('admin.notification.markread',$item->id) }}")' class="linkMark mark-read">Mark as read</a>
                    @endif
                    @role('admin')
                        @if(auth()->user()->id==1)
                        | <a href="{{ route('admin.notification.delete',$item->id) }}" class="linkMark mark-unread">Delete</a>
                        @endif
                    @endrole
                  </td>
                </tr>
            @endforeach
          </tbody>
        </table>
        <div class="d-flex justify-content-center mt-5 paginationCt">
            <div class="d-flex">
                {{ $model->onEachSide(1)->links() }}
            </div>
        </div>
    @else
        @include('admin.common.noresult')
    @endif



@endsection



