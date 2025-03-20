@extends('admin.layouts.master')


@section('content')


    @include('admin.common.message')

    <div class="section-row">
      <h2 class="pb-2 border-bottom">Cache Management</h2>
      {{ Form::model(null, ['route' => ['admin.config.indexer.cache'], 'method' => 'post', 'id' => 'cacheActionForm']) }}
        <button type="button" name="generate" id="cacheGenerateButton" onclick="submitFormGenerateCache()" value="generate" class="btn btn-primary">Generate Cache</button>&nbsp;&nbsp;&nbsp;&nbsp;
        <button type="submit" name="clearcssjs" value="clear" class="btn btn-warning">Clear CSS/JS</button>
        <button type="submit" name="clear" value="clear" class="btn btn-danger" style="float:right;">Clear All Pages Cache</button>
      {{ Form::close() }}<br>
      <?php $cacheData=App\Models\CoreConfig::getCachedPagesData();
      $percent=floor(($cacheData['cached']/$cacheData['total'])*100); ?>
      <div class="loading-bar-container">
        <div class="loading-bar" id="cacheLoadingBar" style="width:{{ $percent }}%"><span id="cacheLoadingBarText">{{ $percent }}%</span></div>
      </div>
      Total Pages: <span id="cacheCachedTotal">{{ $cacheData['total'] }}</span> &nbsp; Cached: <span id="cacheCachedCount">{{ $cacheData['cached'] }}</span> &nbsp; <span class="loadingmsg" id="cacheStatus"></span>
      <script type="text/javascript">
        function submitFormGenerateCache() {
          document.getElementById('cacheGenerateButton').disabled = true;
          document.getElementById('cacheStatus').innerHTML='<em>Loading... Please Wait...</em>';
          const form = document.getElementById('cacheActionForm');
          const formData = new FormData(form);
          formData.append('generate', '1');
          fetch("{{ route('admin.config.indexer.cache') }}", {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if(data.cached){
              document.getElementById('cacheCachedTotal').innerHTML=data.total;
              document.getElementById('cacheCachedCount').innerHTML=data.cached;
              document.getElementById('cacheGenerateButton').disabled = false;
              document.getElementById('cacheStatus').innerHTML='<i>Processing More... '+data.url+' (Created Successfully '+data.path+')</i>';
              document.getElementById('cacheLoadingBar').style.width = Math.floor((data.cached/data.total)*100)+'%';
              document.getElementById('cacheLoadingBarText').innerHTML = Math.floor((data.cached/data.total)*100)+'%';
              if(data.cached==data.total){
                document.getElementById('cacheStatus').innerHTML='<i>All Generated Successfully.</i>';
              }else{
                setTimeout(function(){
                  submitFormGenerateCache();
                },3000);
              }
            }
          })
          .catch(error => {
            // Handle errors
            console.error('Error:', error);
            document.getElementById('cacheStatus').innerHTML='<em>'+error+'</em>';
          });
        }
      </script>
    </div>

    <div class="section-row">
      <h2 class="pb-2 border-bottom">Image Cache Management</h2>
      {{ Form::model(null, ['route' => ['admin.config.regenerate.image'], 'method' => 'post', 'id' => 'cacheImageActionForm']) }}
        <button type="button" id="cacheImageGenerateButton" onclick="submitFormGenerateCacheImage()" name="generate" value="generate" class="btn btn-primary" >ReGenerate</button>
        For to clean image cache need to manually delete from ftp (/public_html/public/media/cache/) All directory.
      {{ Form::close() }}<br>
      <?php $cacheData=App\Models\CoreConfig::getCachedImagesData();
      $percent=empty($cacheData['total'])?0:floor(($cacheData['cached']/$cacheData['total'])*100);
      ?>

      <div class="loading-bar-container">
        <div class="loading-bar" id="cacheImageLoadingBar" style="width:{{ $percent }}%"><span id="cacheImageLoadingBarText">{{ $percent }}%</span></div>
      </div>
      Total Pages: <span id="cacheImageCachedTotal">{{ $cacheData['total'] }}</span> &nbsp; Cached: <span id="cacheImageCachedCount">{{ $cacheData['cached'] }}</span> &nbsp; <span class="loadingmsg" id="cacheImageStatus"></span>
      <script type="text/javascript">
        function submitFormGenerateCacheImage() {
          document.getElementById('cacheImageGenerateButton').disabled = true;
          document.getElementById('cacheImageStatus').innerHTML='<em>Loading... Please Wait...</em>';
          const form = document.getElementById('cacheImageActionForm');
          const formData = new FormData(form);
          formData.append('generate', '1');
          fetch("{{ route('admin.config.regenerate.image') }}", {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if(data.cached){
              document.getElementById('cacheImageCachedTotal').innerHTML=data.total;
              document.getElementById('cacheImageCachedCount').innerHTML=data.cached;
              document.getElementById('cacheImageGenerateButton').disabled = false;
              document.getElementById('cacheImageStatus').innerHTML='<i>Processing More...</i>';
              document.getElementById('cacheImageLoadingBar').style.width = Math.floor((data.cached/data.total)*100)+'%';
              document.getElementById('cacheImageLoadingBarText').innerHTML = Math.floor((data.cached/data.total)*100)+'%';
              if(data.cached==data.total){
                document.getElementById('cacheImageStatus').innerHTML='<i>All Generated Successfully.</i>';
              }else{
                setTimeout(function(){
                  submitFormGenerateCacheImage();
                },3000);
              }
            }
          })
          .catch(error => {
            // Handle errors
            console.error('Error:', error);
            document.getElementById('cacheImageStatus').innerHTML='<em>'+error+'</em>';
          });
        }
      </script>
    </div>



@endsection



