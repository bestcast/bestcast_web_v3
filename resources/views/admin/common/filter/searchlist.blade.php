<div class="col-md">
    <div class="form-floating">
          <div class="form-row">
                @php($filterUrl=Lib::urlParams(\URL::current(),Request::getQueryString(),'search,page'))
                <div class="inline-search">
                    <input type="text" class="form-control" id="searchlist" name="searchlist" value="{{ old('searchlist',app('request')->input('search')) }}" placeholder="Search ..." >
                    <div class="search-btn icon-search"></div>
                </div>
                <script>
                    jQuery(document).ready(function($){
                      $('#searchlist').keypress(function(event){
                        if(event.keyCode == 13){
                          var inputValue = $(this).val();
                          window.location.href='{!! $filterUrl !!}&search='+inputValue;
                        }
                      });
                      $('.search-btn').click(function(event){
                          var inputValue = $(this).prev().val();
                          window.location.href='{!! $filterUrl !!}&search='+inputValue;
                      });
                    });
                </script>
          </div>
    </div>
</div>