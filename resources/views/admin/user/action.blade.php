<a href="{{ route('admin.user.edit',$id) }}" class="edit btn btn-success btn-sm">Edit</a>
<a class="delete btn btn-danger btn-sm btn-delete-copy-{{ $id }}" data-bs-toggle="modal" data-bs-target="#delete{{ $id }}">Delete</a>
@php ($delid=$id)
@php ($delurl=route('admin.user.delete',$id))
@include('admin.common.modaldelete')
