<div class="col-md">
  <div class="form-floating">
    <div class="form-row form-select2" id="sortorder_dropdown">
      @php ($selectedValue=[0=>'Recent Movies',1=>'Title (A to Z)',2=>'Title (Z to A)',3=>'Release Year (A to Z)',4=>'Release Year (Z to A)'])
      {{ Form::select('sortorder', $selectedValue,old('sortorder',app('request')->input('sortorder')), array('id'=>'sortorder','class' => 'form-select')); }}
      @php($filterUrl=Lib::urlParams(\URL::current(),Request::getQueryString(),'sortorder,page'))
      <script>
      jQuery(document).ready(function($) {
        $('#sortorder').select2();
        $('#sortorder').on('select2:selecting', function(e) {
          //console.log('Selecting: ' , e.params.args.data);
          window.location.href='{!! $filterUrl !!}&sortorder='+e.params.args.data.id;
        });
      });
      </script>
    </div>
  </div>
</div>