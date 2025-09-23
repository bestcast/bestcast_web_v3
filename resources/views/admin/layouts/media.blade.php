<!-- Modal start -->
    <div class="modal fade medialist" id="mediaModal" tabindex="-1" aria-labelledby="mediaModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-fullscreen">
        <div class="modal-content ajax_load" data-domain="{{ url('/') }}">
        </div>
      </div>
    </div>
    <div class="modal fade pgAllModel" id="pgAllModel" tabindex="-1" aria-labelledby="pgAllModelLabel" aria-hidden="true">
      <div class="modal-dialog modal-fullscreen">
        <div class="modal-content pg_ajax_load" data-domain="{{ url('/') }}">
        </div>
      </div>
    </div>
    <input type="hidden" class="searchtext" />


    <script type="text/javascript">
      jQuery('.upload-media').on('click',function(){
        loadMedia(this,0);
      });

      function loadMedia(ele,start=0){
        jQuery('.pg_ajax_load').html('');
        var buttondiv=jQuery(ele).attr('data-div');
        //jQuery('.overlay').show();
        var selectedid=jQuery('.upload-media.um-'+buttondiv).next('.upload-media-field').val();
        var searchtext=jQuery('.searchtext').val();
        var length=200;
        jQuery('.ajax_load .loading').addClass('active');
        jQuery('.ajax_load .draft').html('Please Wait...');
        jQuery.ajax({
            url:jQuery('.ajax_load').attr('data-domain')+'/admin/media/popup/list',
            data:"start="+start+"&length="+length+"&selectedid="+selectedid+"&searchtext="+searchtext,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            success: function (data) {
              var html=rows='';
              html+='<div class="modal-header"><h5 class="modal-title" id="mediaModalLabel">Media</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>';
              html+='<div class="modal-body medialist"><div class="loading"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';

              /*--Toolbar start---*/
              html+='<div class="container-fluid toolbar"><div class="row spacebtw">';  

                html+='<div class="col-md-3  m-2">';/*--Search start---*/
                  html+='<div class="row searchbox">';
                    html+='<div class="col-md-6 pr-0"><input type="text" class="form-control btn-search-text" id="seachmedia" value="'+searchtext+'" placeholder="Search"></div>';
                    html+='<div class="col-md-3 p-0"><button class="btn btn-primary btn-sm btn-search" type="submit">Search</button></div>';
                    html+='<div class="col-md-3 p-0"><button class="btn btn-primary btn-sm btn-reset" type="submit">Reload</button></div>';
                  html+='</div>';                      
                html+='</div>';/*--Search end---*/    

                html+='<div class="col-md-3  m-2">';/*--Count start---*/  
                  html+='Total:'+data.recordsTotal+' - ';
                  html+='Filtered:'+data.recordsFiltered+'';
                html+='</div>';/*--Count end---*/                

                html+='<div class="col-md-3  m-2 a-right">';/*--Button start---*/  
                  html+='<a href="'+jQuery('.addnewmedia').attr('data-addnewmedia')+'" target="_blank" class="btn btn-primary" type="submit">+ Add New Media</a>';
                html+='</div>';/*--Button end---*/
              html+='</div></div>';
              /*--Toolbar end---*/

              
              html+='<div class="hLTMediaImg clearfix" data-length="'+(start+length)+'">';
                /*--Load Data start---*/
                if(data.recordsTotal){
                    jQuery.each(data.data, function(key,value) {
                      var activeImg=' ';
                      if(selectedid==value.id){
                        activeImg=' active ';
                      }
                      var rowshtml='';
                      rowshtml+='<div class="hLTLs">';
                        rowshtml+='<div class="hLTImg '+activeImg+'" data-id="'+value.id+'" data-urlkey="'+value.urlkeyonly+'" >';//style="background-image:url('+value.urlkey+')"
                          rowshtml+='<img src="'+value.urlkey+'" title="'+value.alt+'" />';
                        rowshtml+='</div>';
                      rowshtml+='</div>';
                      rows+=rowshtml;
                      html+=rowshtml;
                    }); 
                }else{
                  html+='<div class="empty-records">No Records Found.</div>';
                }
                /*--Load Data end---*/
              html+='</div>';
              if(start==0){
                if((start+length)<data.recordsTotal){
                  html+='<center><button onclick="loadMedia(jQuery(\'.upload-media.um-'+buttondiv+'\'),'+(start+length)+')" class="btn btn-secondary btn-loadmore" type="submit">Load More</button></center>';
                }
              }

              html+='</div>';
              html+='<div class="modal-footer">';
                html+='<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>';
                if(data.recordsTotal){
                  html+='<button type="button" class="btn btn-primary btn-select" data-bs-dismiss="modal">Select</button>';
                }
              html+='</div>';

              if(start==0){ //before load more
                jQuery('.ajax_load').html(html);
                jQuery('.ajax_load .loading').removeClass('active');
                jQuery('.ajax_load .draft').html('');
              }else{ //after click load more button
                jQuery('.ajax_load').find('.hLTMediaImg').append(rows);
                jQuery(".ajax_load").find(".btn-loadmore").attr("onclick",'loadMedia(jQuery(\'.upload-media.um-'+buttondiv+'\'),'+(start+length)+')');
                if((start+length)>=data.recordsTotal){
                  jQuery(".ajax_load").find(".btn-loadmore").remove();
                }
                jQuery('.ajax_load .loading').removeClass('active');
                jQuery('.ajax_load .draft').html('');
                rows='';
              }

              
              jQuery('.medialist').find('.hLTImg').on('click',function(){
                jQuery('.ajax_load').find('.hLTImg').removeClass('active');
                jQuery(this).addClass('active');
              });
              jQuery('.medialist').find('.btn-select').on('click',function(){
                jQuery('.overlay').hide();
                var selectedid=jQuery('.ajax_load').find('.hLTImg.active').attr('data-id');
                var selectedidUrlkey=jQuery('.ajax_load').find('.hLTImg.active').attr('data-urlkey');
                console.log(selectedidUrlkey);
                var selectedImg=jQuery('.ajax_load').find('.hLTImg.active').html();
                jQuery('.upload-media.um-'+buttondiv).next('.upload-media-field').val(selectedid);
                jQuery('.upload-media.um-'+buttondiv).next().next('.imgContainer').find('.hLTIn').html('<div class="hLTImg">'+selectedImg+'</div>');
                jQuery('.upload-media.um-'+buttondiv+' span').html('Change');
                jQuery('.upload-media.um-'+buttondiv).next().next('.imgContainer').find('.btnremove').addClass('active');
                jQuery('.upload-media.um-'+buttondiv).next().next('.imgContainer').find('.upload-media-field-urlkey').val(selectedidUrlkey);
                jQuery('.searchtext').val('');
              });

              jQuery('.medialist').find('.btn-cancel').on('click',function(){
                jQuery('.overlay').hide();
              });

              jQuery('.searchbox').find('.btn-search').on('click',function(){
                jQuery('.searchtext').val(jQuery('.searchbox').find('.btn-search-text').val());
                loadMedia(jQuery('.upload-media.um-'+buttondiv),0);
              });

              jQuery('.searchbox').find('.btn-reset').on('click',function(){
                jQuery('.searchtext').val('');
                loadMedia(jQuery('.upload-media.um-'+buttondiv),0);
              });
            }
        });
      }
      jQuery('.form-row .imgContainer .btnremove').click(function(){
          jQuery(this).parent().prev().val('');
          jQuery(this).parent().next().val('');
          jQuery(this).parent().find('.upload-media-field-urlkey').val('');
          jQuery(this).parent().parent().find('.btn.upload-media span').html('Upload');
          jQuery(this).parent().find('.hLTIn').html('');
          jQuery(this).removeClass('active');
      });

      //FilterCode00
      jQuery('.ajax-generate').on('keyup',function(){
        var container=jQuery(this).attr('data-div');
        var val=encodeURI(jQuery(this).val());
        var url=jQuery('.'+container).find('.ajaxdata-url').val();
        var selectedid=jQuery('.'+container).find('.ajaxdata-selected').val();
        if(!selectedid){selectedid=0;}
        //console.log(container+"="+val+"="+url+"="+selectedid);
        if(val.length>=3){
          jQuery('.'+container).find('.generate').html('<div class="ls" data-val="0">Loading...</div>');
          jQuery('.'+container).find('.generate').load(url+'/'+val+'/'+selectedid+'/'+container);
        }
      });
      //FilterCode00

    </script>


<!-- Modal end -->
<div class="foot loading"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>