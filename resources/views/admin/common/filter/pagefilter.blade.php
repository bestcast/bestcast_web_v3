<div class="col-md">
    <div class="form-floating">
          <div class="form-row">
                <label for="pagefilter" class="form-label">Page to display</label>
                @php($filterUrl=Lib::urlParams(\URL::current(),Request::getQueryString(),'page_id,page'))
                <select class="form-select" id="pagefilter" name="page_id">
                    <option value="">All Pages</option>
                    @foreach($pageList as $page)
                        <option value="{{ $page->id }}" @if(app('request')->input('page_id') == $page->id) selected @endif>{{ $page->title }}</option>
                    @endforeach
                </select>
                <script>
                    jQuery(document).ready(function($){
                      $('#pagefilter').change(function(){
                          var selected = $(this).val();
                          window.location.href='{!! $filterUrl !!}&page_id='+selected;
                      });
                    });
                </script>
          </div>
    </div>
</div>