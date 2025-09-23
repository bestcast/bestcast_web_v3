<!-- Modal -->
<div class="modal fade" id="delete{{ $delid }}" tabindex="-1" aria-labelledby="delete{{ $delid }}Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Are you sure?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure? You will not be able to recover this!
      </div>
      <div class="modal-footer">
        <button type="button" class="delete btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button> 
        <a href="{{ $delurl }}" class="delete btn btn-danger btn-sm btn-delete-{{ $delid }}" >Yes Delete it!</a>
      </div>
    </div>
  </div>
</div>