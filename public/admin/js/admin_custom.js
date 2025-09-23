/*--------------------------------------Slider start------------------------------------*/

function markRead(element,id,url){
    element.innerHTML='Loading...';
    // Perform the AJAX request
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var jsonResponse = JSON.parse(xhr.responseText);
                element.classList.remove('mark-read');
                element.classList.remove('mark-unread');
                if(jsonResponse.is_read){
                    element.innerHTML='Mark as read';
                    element.classList.add('mark-read');
                }else{
                    element.innerHTML='Mark as unread';
                    element.classList.add('mark-unread');
                }
                document.getElementById('notifyCount').innerHTML=jsonResponse.totalcount;
            } else {
                element.innerHTML='Failed!';
            }
        }
    };
    xhr.send();
}

jQuery(document).ready(function($){
    $('.nav-pills a > span.arrow').click(function(e){
        e.preventDefault();
        if($(this).parent().next().hasClass('open')){
          $(this).parent().next().slideUp(200);
          $(this).parent().next().removeClass('open');
        }else{
          $(this).parent().next().slideDown(200);
          $(this).parent().next().addClass('open');
        }
    });

	$('.datepicker').datepicker({
		format: 'dd/mm/yyyy'
    });
	$('.datepicker_system').datepicker({
		format: 'yyyy-mm-dd'
    });
});
/*--------------------------------------Slider end------------------------------------*/

