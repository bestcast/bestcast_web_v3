<div class="col-md">
  <div class="form-floating">
    <div class="form-row form-select2" id="languages_dropdown">
      @php($selectedId=app('request')->input('language'))
      @php($selectedValue=App\Models\Languages::where('id',$selectedId)->pluck('title','id'))
      {{ Form::select('language_id', $selectedValue,old('language_id',$selectedId), array('id'=>'language_id','class' => 'form-select')); }}
      @php($filterUrl=Lib::urlParams(\URL::current(),Request::getQueryString(),'language,page'))
      <script>
      jQuery(document).ready(function($) {
        $('#language_id').select2({
            placeholder: "Choose language...",
            minimumInputLength: 0,
            ajax: {
                url: function (params) {
                  return  "{{ route('admin.languages.searchbytitle') }}/"+params.term;
                },  dataType: 'json',
                processResults: function (data) { return {  results: data };  },cache: true
            }
        });
        $('#language_id').on('select2:selecting', function(e) {
          //console.log('Selecting: ' , e.params.args.data);
          window.location.href='{!! $filterUrl !!}&language='+e.params.args.data.id;
        });
      });
      </script>
    </div>
  </div>
</div>