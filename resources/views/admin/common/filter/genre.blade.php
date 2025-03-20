<div class="col-md">
  <div class="form-floating">
    <div class="form-row form-select2" id="genres_dropdown">
      @php($selectedId=app('request')->input('genre'))
      @php($selectedValue=App\Models\Genres::where('id',$selectedId)->pluck('title','id'))
      {{ Form::select('genre_id', $selectedValue,old('genre_id',$selectedId), array('id'=>'genre_id','class' => 'form-select')); }}

      @php($filterUrl=Lib::urlParams(\URL::current(),Request::getQueryString(),'genre,page'))
      <script>
      jQuery(document).ready(function($) {
        $('#genre_id').select2({
            placeholder: "Choose genre...",
            minimumInputLength: 0,
            ajax: {
                url: function (params) {
                  return  "{{ route('admin.genres.searchbytitle') }}/"+params.term;
                },  dataType: 'json',
                processResults: function (data) { return {  results: data };  },cache: true
            }
        });
        $('#genre_id').on('select2:selecting', function(e) {
          //console.log('Selecting: ' , e.params.args.data);
          window.location.href='{!! $filterUrl !!}&genre='+e.params.args.data.id;
        });
      });
      </script>
    </div>
  </div>
</div>