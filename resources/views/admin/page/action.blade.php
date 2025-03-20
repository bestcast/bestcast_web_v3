<a href="{{ Lib::publicUrl($model->urlkey) }}" target="_blank" class="view btn btn-info btn-sm">View</a>
<a href="{{ route('admin.post.edit',$id) }}" class="edit btn btn-success btn-sm">Edit</a>
<a class="delete btn btn-danger btn-sm btn-delete-copy-{{ $id }}" data-bs-toggle="modal" data-bs-target="#delete{{ $id }}">Delete</a>
@php ($delid=$id)
@php ($delurl=route('admin.post.delete',$id))
@include('admin.common.modaldelete')