<div class="form-row  col-md-12 m-0">
    @if($model->created_at)
        <div class="text-muted">Created at: {{ Lib::datetime($model->created_at)  }}</div>
    @endif
    @if($model->updated_at)
        <div class="text-muted">Updated at: {{ Lib::datetime($model->updated_at)  }}</div>
    @endif
    @if($model->created_by)
        <div class="text-muted">Created By: {{ Lib::NameById($model->created_by)  }}</div>
    @endif
    @if($model->updated_by)
        <div class="text-muted">Updated By: {{ Lib::NameById($model->updated_by)  }}</div>
    @endif
</div>