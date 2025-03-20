<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\UrlGenerator;
use App\User;
use App\Models\Post;
use Lib;
class Field {


  /*
  use Field;
  Field::getDate($val);
  --*/
  public static function getDate($val)
  {
      return date("d M Y H:i a",strtotime($val));
  }

  /*
  use Field;
  Field::status();
  Field::getstatus($val);
  --*/
  public static function status()
  {
      return array('0' => 'Draft', '1' => 'Published');//, '2' => 'Private', '3' => 'Protected'
  }
  public static function getStatus($val)
  {
      $ar=Field::status();
      return empty($ar[$val])?$ar[0]:$ar[$val];;
  }


  /*
  use Field;
  Field::status();
  Field::getstatus($val);
  --*/
  public static function paymentMethod()
  {
      return array('0' => 'no_payment','1' => 'bank_transfer', '2' => 'upi_transfer');//, '2' => 'Private', '3' => 'Protected'
  }
  public static function paymentMethodLabel()
  {
      return array('no_payment' => 'No Payment','bank_transfer' => 'Bank Transfer', 'upi_transfer' => 'UPI Transfer');//, '2' => 'Private', '3' => 'Protected'
  }
  public static function getPaymentMethod($val)
  {
      $ar=Field::paymentMethod();
      return empty($ar[$val])?0:$ar[$val];;
  }
  public static function getPaymentMethodLabel($val)
  {
      $ar=Field::paymentMethodLabel();
      return empty($ar[$val])?'No Payment':$ar[$val];;
  }
  public static function getPaymentMethodID($val)
  {
      $ar=Field::paymentMethod();
      foreach($ar as $key=>$ls){
        if($ls==$val){
          return $key;
        }
      }
      return 0;
  }


  public static function prefixtitle()
  {
      return array("Mr.","Mrs.","Miss.","Dr.");
  }

  
  public static function getStates($val)
  {
      $ar=Field::states();
      return empty($ar[$val])?'NA':$ar[$val];;
  }
  public static function states()
  {
      return array(
        '1' => 'Tamil Nadu',
        '2' => 'Puducherry',
        '3' => 'Andhra Pradesh',
        '4' => 'Arunachal Pradesh',
        '5' => 'Assam',
        '6' => 'Bihar',
        '7' => 'Chhattisgarh',
        '8' => 'Delhi',
        '9' => 'Goa',
        '10' => 'Gujarat',
        '11' => 'Haryana',
        '12' => 'Himachal Pradesh',
        '13' => 'Jammu and Kashmir',
        '14' => 'Jharkhand',
        '15' => 'Karnataka',
        '16' => 'Kerala',
        '17' => 'Madhya Pradesh',
        '18' => 'Maharashtra',
        '19' => 'Manipur',
        '20' => 'Meghalaya',
        '21' => 'Mizoram',
        '22' => 'Nagaland',
        '23' => 'Odisha',
        '24' => 'Punjab',
        '25' => 'Rajasthan',
        '26' => 'Sikkim',
        '27' => 'Telangana',
        '28' => 'Tripura',
        '29' => 'Uttar Pradesh',
        '30' => 'Uttarakhand',
        '31' => 'West Bengal',
        '32' => 'Andaman and Nicobar Islands',
        '33' => 'Chandigarh',
        '34' => 'Dadra and Nagar Haveli',
        '35' => 'Daman and Diu',
        '36' => 'Lakshadweep'
    );
  }

  public static function approval()
  {
      return array('0' => 'Pending', '1' => 'Approved', '2' => 'Rejected');
  }
  public static function getApproval($val)
  {
      $ar=Field::approval();
      return empty($ar[$val])?$ar[0]:$ar[$val];;
  }




  /*
  use Field;
  Field::textAlign();
  --*/
  public static function textAlign()
  {
      return array(0 => 'Left', 1 => 'Right', 2 => 'Center', 3 => 'Justify', 4 => 'Center & Justify');
  }
  public static function getTextAlign($val)
  {
    if($val==1)
      return 'a-right';
    else if($val==2)
      return 'a-center';
    else if($val==3)
      return 'a-justify';
    else if($val==4)
      return 'a-center-justify';
    else
      return null;
  }


  /*
  use Field;
  Field::textAlign();
  --*/
  public static function divSize()
  {
      return array(0 => 'Default', 1 => 'Extra Small', 2 => 'Small', 3 => 'Medium', 4 => 'Large', 5 => 'Full');
  }


  /*
  use Field;
  Field::template();
  Field::getTemplate($val);
  --*/
  public static function template()
  {
      return array(0 => 'Movies', 1 => 'Content');
  }
  public static function getTemplateSlug($val)
  {
      $ar=Field::template();
      $val=empty($ar[$val])?$ar[1]:$ar[$val];
      return Lib::slug($val);
  }
  public static function getTemplate($val)
  {
      $ar=Field::template();
      return empty($ar[$val])?$ar[1]:$ar[$val];
  }


  /*
  use Field;
  Field::priceType();
  Field::getPriceType($val);
  --*/
  public static function priceType()
  {
      return array(1 => 'Plan A', 2 => 'Plan B', 3 => 'Plan C');
  }
  public static function getPriceType($val)
  {
      $ar=Field::priceType();
      return empty($ar[$val])?$ar[1]:$ar[$val];
  }


  /*
  use Field;
  Field::paymentStatus();
  Field::getPaymentStatus($val);
  --*/
  public static function paymentStatus()
  {
      return array(0 => 'Pending', 1 => 'Pending Payment', 2 => 'Success', 3 => 'Cancelled', 4 => 'Closed');
  }
  public static function getPaymentStatus($val)
  {
      $ar=Field::paymentStatus();
      return empty($ar[$val])?$ar[1]:$ar[$val];
  }

  /*
  use Field;
  Field::sectionTemplate();
  Field::getSectionTemplateSlug($val);
  --*/
  public static function sectionTemplate()
  {
      $list=array(
          //1 => 'Fullwidth',
          3 => 'List', 
          2 => 'Two Column 1',
          15 => 'Fullwidth 1', 
          4 => 'Banner 1', 
          7 => 'Banner 2', 
          5 => 'Icon List 1', 
          6 => 'Icon List 2',
          10 => 'Icon List 3',
          14 => 'Icon List 4',
          16 => 'Icon List 5',
          8 => 'Newsletter',
          9 => 'Testimonial 1',
          11 => 'Team 1',
          12 => 'Contact 1',
          13 => 'Video 1',
          17 => 'FAQ 1');//18 next
      return $list;
  }
  public static function getSectionTemplateSlug($val)
  {
      $ar=Field::sectionTemplate();
      $val=empty($ar[$val])?$ar[1]:$ar[$val];
      return Lib::slug($val);
  }
  public static function getSectionTemplate($val)
  {
      $ar=Field::sectionTemplate();
      return empty($ar[$val])?$ar[1]:$ar[$val];
  }


  /*
  use Field;
  Field::listTemplate();
  --*/
  public static function listTemplate()
  {
      return array(0 => 'Default',1 => 'FAQ', 3 => 'Banner Slider');
  }
  public static function getListTemplateSlug($val)
  {
      $ar=Field::listTemplate();
      $val=empty($ar[$val])?$ar[0]:$ar[$val];
      return Lib::slug($val);
  }


  public static function getPostById($id){
    if(empty($id)){ return '';}
    $get=Post::find($id);
    return empty($get->id)?'':$get;
  }

  public static function getPostTitleById($id){
    if(empty($id)){ return '';}
    $get=Post::find($id);
    return empty($get->title)?'':$get->title;
  }


  /*
  use Field;
  Field::column();
  --*/
  public static function column($template=1)
  {
    return array(0=>'Default',2=>'2 Column',3=>'3 Column',4=>'4 Column',5=>'5 Column',6=>'6 Column');
  }
  public static function getColumn($val)
  {
      $ar=Field::column();
      return empty($ar[$val])?$ar[0]:$ar[$val];;
  }

  /*
  use Field;
  Field::position();
  Field::getPosition($val);
  --*/
  public static function position($template=1)
  {
    if($template==2){
      return array(1=>'Left',2=>'Right',3=>'Left (Full)',4=>'Right (Full)');
    }
    return array(1=>'Top',2=>'Bottom',3=>'Below Title',4=>'Background');
  }
  public static function getPosition($val)
  {
      $ar=Field::position();
      return empty($ar[$val])?$ar[1]:$ar[$val];;
  }

  /*
  use Field;
  Field::padding();
  Field::getPadding($val);
  --*/
  public static function padding($template=1)
  {
    return array(0=>'No',1=>'Yes',2=>'Top Only',3=>'Bottom Only');
  }
  public static function getPadding($val)
  {
      $ar=Field::padding();
      return empty($ar[$val])?$ar[1]:$ar[$val];;
  }
  public static function getPaddingValue($val)
  {
    if(empty($val)){ return '';}
    elseif($val==1){ return 'edu-section-gap';}
    elseif($val==2){ return 'edu-section-gapTop';}
    elseif($val==3){ return 'edu-section-gapBottom';}
    return '';
  }


  /*
  use Field;
  Field::buttonStyle();
  --*/
  public static function buttonStyle()
  {
      return array('0' => 'Default', '1' => 'Primary');
  }


  /*
  use Field;
  Field::data($item);
  --*/
  public static function data($item){$html='';
    if(empty($item) && empty($item->field->type)) return null;
    $html=null;
    switch ($item->field->type) {
      case 'image':
          $html='';
          $html.='<div class="fileblock">
                <div class="comment">Allowed file format: png, jpg, jpeg, gif. Maximum file size 2MB'.$item->field->comment.'</div>';
                $html.='<input type="file" class="form-control" id="'.$item->path.'_file" name="'.$item->path.'_file"  accept="image/*">
                    <input type="hidden" class="form-control" id="'.$item->path.'" name="'.$item->path.'"  value="'.$item->value.'">';
                if(!empty($item->value)){
                  $html.='<div class="thmprviewCt"><div class="thmprview">
                    <div class="close" data-id="'.$item->path.'"></div>
                    <img src="'.Lib::publicImgSrc($item->value).'" />';
                    if(strlen(strpos($item->value,".pdf"))){
                      $html.='<div>'.Lib::publicUrl($item->value).'</div>';
                    }
                  $html.='</div></div>';
                }
          $html.='</div>';
        break;
      case 'enable':
          $html='<div class="selectblock">
                            <select class="form-control" id="'.$item->path.'" name="'.$item->path.'">';
                              $sel=!empty($item->value)?'':' selected="selected" ';
                              $html.='<option value="0" '.$sel.'>Disable</option>';
                              $sel=empty($item->value)?'':' selected="selected" ';
                              $html.='<option value="1" '.$sel.'>Enable</option>';
                            $html.='</select>
                          </div>';
        break;
      case 'colorbox':
          $color=empty($item->value)?'#000':$item->value;
          $html='<input type="text" class="form-control colorbox" id="'.$item->path.'" name="'.$item->path.'" value="'.$color.'" placeholder="'.$item->field->placeholder.'">';
        break;
      case 'textarea':
          $html='<textarea class="form-control" id="'.$item->path.'" name="'.$item->path.'">'.$item->value.'</textarea>';
        break;
      case 'editor':
          $html='<textarea row="5" class="form-control editorblk editor_'.$item->path.'" id="'.$item->path.'" name="'.$item->path.'">'.$item->value.'</textarea>';
          $html.=Field::editor('editor_'.$item->path);
        break;
      default:
        $html='<input type="text" class="form-control" id="'.$item->path.'" name="'.$item->path.'" value="'.$item->value.'" placeholder="'.$item->field->placeholder.'">';
        break;
    }
    return $html;
  }

  /*
  use Lib;
  Field::editor($class);
  --*/
  public static function editor($class){

    return "<script>
      tinymce.init({
        selector: '.".$class."',
        menubar: false,
        max_height:600,
        plugins: [
          'autoresize','advlist','autolink',
          'lists','link','image','charmap','preview','anchor','searchreplace','visualblocks',
          'fullscreen','insertdatetime','media','table','help','wordcount','code'
        ],
        toolbar: 'undo redo | formatselect | casechange blocks | bold italic underline strikethrough  | alignleft aligncenter alignright alignjustify | bullist numlist | link unlink hr blockquote | table | image forecolor | code | searchreplace | removeformat formatpainter backcolor outdent indent help '
      });
    </script>";


    // return "<script>
    //   tinymce.init({
    //     selector: '.".$class."',
    //     menubar: false,
    //     max_height:600,
    //     plugins: [
    //       'autoresize','a11ychecker','advlist','advcode','advtable','autolink','checklist','export',
    //       'lists','link','image','charmap','preview','anchor','searchreplace','visualblocks',
    //       'powerpaste','fullscreen','formatpainter','insertdatetime','media','table','help','wordcount','code'
    //     ],
    //     toolbar: 'undo redo | formatselect | casechange blocks | bold italic underline strikethrough  | alignleft aligncenter alignright alignjustify | bullist numlist | link unlink hr blockquote | table | image forecolor | code | searchreplace | checklist removeformat formatpainter backcolor outdent indent help '
    //   });
    // </script>";


        // return "<script>
        //     ClassicEditor.create( document.querySelector( '.".$class."' ),{
        //          toolbar: ['heading', 'alignment','bold', 'italic', 'strikethrough', 'underline', 'subscript', 'superscript', 'link',  'numberedList', 'bulletedList', 'todoList','fontsize','fontColor','code','codeBlock','insertTable','outdent', 'indent','blockQuote','undo', 'redo','sourceEditing' ]
        //         }).catch( error => {
        //             console.error( error );
        //     });
        // </script>";
  }

  /*
  use Lib;
  Field::file('icon',$val);
  --*/
  public static function file($name,$value=''){$html='';
    $html='';
    $html.='<div class="fileblock">
          <div class="comment">Allowed file format: png, jpg, jpeg, gif. Maximum file size 2MB</div>';
          $html.='<input type="file" class="form-control" id="'.$name.'_file" name="'.$name.'_file"  accept="image/*,application/pdf">
              <input type="hidden" class="form-control" id="'.$name.'" name="'.$name.'"  value="'.$value.'">';
          if(!empty($value)){
            if(strlen(strpos($value,".pdf"))){
              $html.='<div class="pdfUrl">'.Lib::publicImgSrc($value).'</div>';
            }else{
              $html.='<div class="thmprviewCt"><div class="thmprview">
                <div class="close" data-id="'.$name.'"></div>
                <img src="'.Lib::publicImgSrc($value).'" />
              </div></div>';
            }
          }
    $html.='</div>';

    return $html;
  }


  /*
  use Field;
  Field::mediaUpload('image_id','Image',$model);
  --*/
  public static function mediaUpload($key,$label,$model){$html='';
    $mediakey=str_replace('_id','',$key); //which is image_id replace to image
    $html.='<div class="form-row uploadimg"><div class="addnewmedia" data-addnewmedia="'.route('admin.media.create').'"></div>';
        $html.='<button type="button" class="btn btn-dark upload-media um-'.$key.'" data-div="'.$key.'" data-id="'.$model->$key.'" data-bs-toggle="modal" data-bs-target="#mediaModal"><span>';
          $html.=empty($model->$key)?'Upload':'Change';
        $html.='</span> '.$label.'</button>';
        $html.='<input type="hidden" class="form-control upload-media-field" id="'.$key.'" name="'.$key.'" value="'.$model->$key.'">';
        $html.='<div class="imgContainer">
          <div class="hLTIn">';
            if(!empty($model->$mediakey->urlkey)):
              $html.='<div class="hLTImg"><img src="'.Lib::publicImgSrc($model->$mediakey->urlkey).'" /></div>';
            endif;
          $html.='</div>';
          $html.='<button type="button" class="btn btn-secondary btn-sm btnremove '.(empty($model->$mediakey->urlkey)?'':'active').'">Remove</button>';
        $html.='</div>';
    $html.='</div>';
    return $html;
  }


  /*
  use Field;
  Field::galleryUpload('image_id','Image',$meta);
  --*/
  public static function galleryUpload($key,$label,$meta){$html='';
    $html.='<div class="form-row uploadimg"><div class="addnewmedia" data-addnewmedia="'.route('admin.media.create').'"></div>';
        $html.='<button type="button" class="btn btn-dark upload-media um-'.$key.'" data-div="'.$key.'" data-id="'.(empty($meta[$key])?'':$meta[$key]).'" data-bs-toggle="modal" data-bs-target="#mediaModal"><span>';
          $html.=empty($meta[$key])?'Upload':'Change';
        $html.='</span> '.$label.'</button>';
        $html.='<input type="hidden" class="form-control upload-media-field" id="'.$key.'" name="meta['.$key.']" value="'.(empty($meta[$key])?'':$meta[$key]).'">';
        $html.='<div class="imgContainer">
          <div class="hLTIn">';
            if(!empty($meta[$key.'_urlkey'])):
              $html.='<div class="hLTImg"><img src="'.Lib::publicImgSrc($meta[$key.'_urlkey']).'" /></div>';
            endif;
          $html.='</div>';
          $html.='<button type="button" class="btn btn-secondary btn-sm btnremove '.(empty($meta[$key.'_urlkey'])?'':'active').'">Remove</button>';
          $html.='<input type="hidden" class="form-control upload-media-field-urlkey" id="'.$key.'_urlkey'.'" name="meta['.$key.'_urlkey'.']" value="'.(empty($meta[$key.'_urlkey'])?'':$meta[$key.'_urlkey']).'">';
        $html.='</div>';
    $html.='</div>';
    return $html;
  }


  /*
  use Field;
  Field::getRegionID($val);
  --*/
  public static function getRegion()
  {
      return array('content' => 1,'top-content' =>2,'bottom-content' =>3,'left-sidebar' =>4,'right-sidebar' =>5,'section-list' =>6);
  }
  public static function getRegionID($val)
  {
      $ar=Field::getRegion();
      return empty($ar[$val])?$ar['content']:$ar[$val];
  }

  /*
  use Field;
  Field::addSection($pageid,$region);
  --*/
  public static function addSection($pageid,$region='content'){$html='';
    $trig="trigger-pgCSList-".$region;
    $html.='<div class="sectionList region-'.$region.'">
        <div class="pgCSList loadajax-'.$trig.'">
        </div>
        <button type="button" class="btn btn-primary btn-lg pgCSListBtn pgCSListBtn-'.$trig.'" data-div="'.$trig.'" data-popuplist="'.route('admin.section.popuplist').'" data-sectionedit="'.route('admin.section.edit','').'"  data-sectionnew="'.route('admin.section.create').'" data-sectionpageadd="'.route('admin.section.sectionpageadd').'" data-regionlist="'.route('admin.section.regionlist').'?region='.$region.'&pageid='.$pageid.'" data-bs-toggle="modal" data-bs-target="#pgAllModel">+ Add Section</button>
        <input type="hidden" class="pgCSListVal-'.$trig.'region" value="'.$region.'"/>
        <input type="hidden" class="pgCSListVal-'.$trig.'pageid" value="'.$pageid.'"/>
        <script type="text/javascript">
          jQuery(".loadajax-'.$trig.'").load(jQuery(".pgCSListBtn-'.$trig.'").attr("data-regionlist"));
        </script>
    </div>';
    return $html;
  }



  /*
  use Field;
  Field::getReviewCount($meta);
  --*/
  public static function reviewStatus(){
    return true;
  }
  public static function getReviewCount($meta)
  {
      $config=Field::reviewStatus();
      if(empty($config))
        return false;

      $default=array('total'=>1,'overall'=>5,'1'=>0,'2'=>0,'3'=>0,'4'=>0,'5'=>1);

      if(empty($meta['review_count']))
        return $default;

      if(strtolower($meta['review_count'])=='disable')
        return false;

      $arr=$meta['review_count'];
      $arr=explode(",",$arr);
      if(count($arr)!=5)
        return $default;

      $maxs = array_search(max($arr),$arr);
      return array('total'=>array_sum($arr),'overall'=>($maxs+1),'1'=>$arr[0],'2'=>$arr[1],'3'=>$arr[2],'4'=>$arr[3],'5'=>$arr[4]);
  }

}
