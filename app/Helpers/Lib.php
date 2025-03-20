<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\UrlGenerator;
use HelpLib;
use App\User;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Str;
use Shortcode;
use URL;
use Carbon\Carbon;
class Lib {

	/*
	use Lib;
	js and Css Version
	Lib::ver();
	--*/
	public static function ver() {
		return "1.0.0.".rand(0,100000);
	}
//URL::current()

	/*
	CMS content urls replace
	use Lib;
	Lib::cachable();
	--*/
	public static function cachable($arr=array()) {
		return true;
	}


	/*
	CMS content urls replace
	use Lib;
	Lib::cmsurl();
	--*/
	public static function cmsurl($val){
        $urlkey=str_replace("../",env('APP_URL')."/",$val);
		return $urlkey;
	}

	/*
	Frontend above header
	use Lib;
	Lib::pageinit();
	--*/
	public static function pageinit(){
        $current=URL::current();
        $urlkey=str_replace(env('APP_URL').env('APP_PATH')."/","",$current);//"/public/"
        if(!empty($urlkey)){
        	$post=Post::where('urlkey',$urlkey)->first();
        	$ispublic=Post::ispublic($post);        
        }
        if(empty($ispublic)){
            $post=Post::where('urlkey','page-not-found')->first();
        }
		return $post;
	}


	/*
	use Field;
	Lib::placeholder();
	--*/
	public static function placeholder($file='')
	{
	  $file=empty($file)?'default':$file;
	  return env('APP_URL').env('APP_PATH')."img/placeholder/".$file.".png";//"/public
	}

	/*
	use Lib;
	js and Css Version
	Lib::date();
	--*/
	public static function date($val) {
		return date('d-m-Y', strtotime($val));
	}
	public static function datetime($val) {
		return date('d-m-Y h:i:s a', strtotime($val));
	}

	public static function uuiid(){
		return '7f1bda39-87a9-439a-b2d6-b161c697f648';
	}

	public static function slug($val,$replace='-',$allow=''){
	     return Str::slug($val,$replace);
	}
	public static function slugWithSlash($val,$replace='-'){
		$val_arr=explode('/',$val);
		if(count($val_arr)){	
			$code='';	
	    	$i=0;foreach($val_arr as $item){
	    		if($i){$code.='/';}$i++;
	    		$code.=Str::slug($item,$replace);
	    	}
	    	return $code;
		}
		return Str::slug($val,$replace);
	}


	/*
	use Lib;
	Lib::StoreFile($request,$fncode);
	--*/
	public static function StoreFile($request,$fncode){
		if(empty($fncode))
			return '';
            
		$fntempcode=$fncode."_file";
		if(!empty($request->$fntempcode)){
            $request->validate([$fntempcode => 'mimes:png,jpg,jpeg,gif|max:2048']);
            $fileName = "file_".$fncode."_".time().'.'.$request->$fntempcode->extension();  
            $filepath="config/image/";
            $path=Storage::disk('public')->path($filepath);
            $request->$fntempcode->move($path, $fileName);
            return $filepath.$fileName;
        }
		return '';
	}

	public static function send($request){
		$data=$request->all();
		if(empty($data))
			return false;
            
		$fntempcode="attach";
		if(!empty($request->attach)){
            $filename=$request->attach->getClientOriginalName();
            $request->attach->move("tmp/", $filename);
            return true;
        }
		return false;
	}



	public static function imgraw($val){$html='';
		$val=str_replace(env('APP_URL').env('APP_PATH'),"",$val);
		return $val;
	}


	/*
	public folder store settings
	use Lib;
	Lib::SavePublicMedia($request,$fncode,$filepath='');
	--*/
	public static function SavePublicMedia($request,$fncode,$filepath=''){
		if(empty($fncode))
			return '';

            
		$fntempcode=$fncode."_file";
		//echo $request->$fntempcode->extension();die();
		if(!empty($request->$fntempcode)){
			if($request->$fntempcode->extension()=='pdf'){
			}else{
            	$request->validate([$fntempcode => 'mimes:pdf,png,jpg,jpeg,gif|max:2048']);
        	}
            $filename=$request->$fntempcode->getClientOriginalName();
            $fileName = "file_".md5($filename)."_".time().'.'.$request->$fntempcode->extension();  
            $filepath=empty($filepath)?"media/image/".date("Y")."/".date("m")."/":$filepath;
            $path=$filepath;
            $request->$fntempcode->move($path, $fileName);
            return $filepath.$fileName;
        }
		return '';
	}



	/*--
	{{ Lib::MediaUrlById(60) }}
	--*/
	public static function MediaUrlById($id=0) {
		if(empty($id)){
			return "privacy-policy";
		}		
		$media=Media::find($id);
		return Lib::publicImgSrc($media->urlkey);
	}
	
	
	public static function publicUrl($val=''){
		return env('APP_URL').env('APP_PATH').$val;//"/public
	}
	public static function publicImgSrc($val){
		return Lib::img($val);
	}
	public static function url($val=''){
		return env('APP_URL').env('APP_PATH').$val;//"/public
	}
	public static function img($val){$html='';
		if(strlen(strpos($val,".pdf"))){$val=Lib::publicUrl().'/img/icon/web/icon-pdf.png';}
		if(!strlen(strpos($val,"://"))){$val=Lib::publicUrl().$val;}
		return $val;
	}



	/*
	storage folder store settings
	use Lib;
	Lib::link($model);
	--*/
	public static function link($model,$arr=array()){
		foreach($arr as $n=>$val){ $$n=empty($arr[$n])?$val:$arr[$n];}
	    $n='catid';$$n=empty($arr[$n])?'':$arr[$n];

	    $catpath='';
	    if(!empty($catid)){
	    	$cat=Category::find($catid);$catpath=$cat->path.'/';
	    }

		if($model->type=="category"){
			return Lib::publicUrl($model->category->path);
		}elseif($model->type=="blog"){
			return Lib::publicUrl($catpath.$model->urlkey);
		}else{
			return Lib::publicUrl($catpath.$model->urlkey);
		}
	}


	/*
	use Lib;
	Lib::limit($type); //featured
	--*/
	public static function limit($type='default'){
		return 12;
	}



	/*
	storage folder store settings
	use Lib;
	Lib::sh($val);
	--*/
	public static function sh($val){
		$val=str_replace('<table','<div class="hLTTableCt"><table',$val);
		$val=str_replace('</table>','</table></div>',$val);
		$val=Lib::adv($val);
		$val=str_replace('../../../',env('APP_URL').env('APP_PATH'),$val);//"/public
		$val=str_replace('../../',env('APP_URL').env('APP_PATH'),$val);//"/public
		$val=str_replace('../',env('APP_URL').env('APP_PATH'),$val);//"/public
		return $val;
	}
	public static function adv($val){
		$val=str_replace('[advc1]','',$val);//content
		$val=str_replace('[advc2]','',$val);//content
		return $val;
	}

	/*
	storage folder store settings
	use Lib;
	Lib::code($val);
	--*/
	public static function code($val,$pid=0,$catid=0){$html='';
		$list=explode("[",$val);
		$shcode=array();
		foreach($list as $code){
			if(!empty($code)){
				$arr=explode(" ",$code);
				if(!empty($arr[0])){
					$name=$arr[0];//name assign here
					$attr=array();
					foreach($arr as $attlist){
						if(!empty($attlist)){
							$attrarr=str_replace("]","",$attlist);
							$attrarr=explode("=",$attrarr);
							if(!empty($attrarr[0]) && !empty($attrarr[1])){
								$attrarr[1]=str_replace('"',"",$attrarr[1]);
								$attrarr[1]=str_replace("'","",$attrarr[1]);
								$attrarr[1]=str_replace("+"," ",$attrarr[1]);
								$attr[$attrarr[0]]=$attrarr[1];//attribute assign here
							}
						}
					}
				}
			}
			if(!empty($name)){
				$attr['pid']=$pid;
				$attr['catid']=$catid;
				$shcode[]=array('code'=>$name,'attr'=>$attr);
			}
		}
		$html.=empty($shcode)?$val:Shortcode::init($shcode);
		return empty($html)?'<!--'.$val.'--!>':$html;
	}

	public static function codeReplace($val){
		$val=str_replace("[icon-pin]",'<i class="ri-map-pin-line"></i>',$val);
		$val=str_replace("[icon-phone]",'<i class="ri-phone-line"></i>',$val);
		$val=str_replace("[icon-mail]",'<i class="ri-mail-line"></i>',$val);
		return $val;
	}

	/*
	storage folder store settings
	use Lib;
	Lib::SaveMedia($request,$fncode,$filepath='');
	--*/
	public static function SaveMedia($request,$fncode,$filepath=''){
		if(empty($fncode))
			return '';
            
		$fntempcode=$fncode."_file";
		if(!empty($request->$fntempcode)){
            $request->validate([$fntempcode => 'mimes:pdf,png,jpg,jpeg,gif|max:2048']);
            $filename=$request->$fntempcode->getClientOriginalName();
            $fileName = "file_".md5($filename)."_".time().'.'.$request->$fntempcode->extension();  
            $filepath=empty($filepath)?"media/image/".date("Y")."/".date("m")."/":$filepath;
            $path=Storage::disk('public')->path($filepath);
            $request->$fntempcode->move($path, $fileName);
            return $filepath.$fileName;
        }
		return '';
	}

	public static function imgsrc($val){$html='';
		if(!strlen(strpos($val,"://"))){$val=Lib::storageurl()."/".$val;}
		return $val;
	}

	public static function storageurl(){
		return env('APP_URL').env('APP_PATH').Storage::url("app/public");
	}







	/*
	Lib::urlrequest();
	--*/
	public static function urlrequest(){
		$url = explode("?",$_SERVER['REQUEST_URI']);
		$url=empty($url[1])?'':$url[1];
        return $url;
	}

          

	/*
	use Lib;
	Lib::uploadFile($request,$path,$fncode);
	--*/
	public static function uploadFile($request,$path='tmp/',$fncode){
		if(empty($fncode))
			return null;
            
		if(!empty($request->$fncode)){
            $fileName = "file_".$fncode."_".time().'.'.$request->$fncode->extension(); 
            $pathtmp=Storage::disk('public')->path($path);
            $request->$fncode->move($pathtmp, $fileName);
            return $path.$fileName;
        }
		return null;
	} 

	public static function NameById($id) {
		if(empty($id)){ return 'NA'; }
		return User::find($id)->name;
	}

	/*
	use Lib;
	js and Css Version
	Lib::rmNewLine();
	--*/
	public static function rmNewLine($val) {
		$val=str_replace(PHP_EOL,"",$val);
		$val=preg_replace("/[\n\r]/","", $val);
		return $val;
	}

	

	/*
	For KEY LOAD
		$arr=array(
                array('code'=>'IND','label'=>'India','code1'=>'IN','code2'=>'91'),
            );
	    $var=array('label'=>'','code'=>'','description'=>'','position'=>0,'code1'=>'','code2'=>'','code3'=>'','code4'=>'','code5'=>'','img1'=>'','img2'=>'','img3'=>'','img4'=>'','img5'=>'');
	    foreach($var as $n=>$val){ $$n=empty($arr[$n])?$val:$arr[$n];}
	    $n='code';$$n=empty($arr[$n])?$label:$arr[$n];
    */

	/*--
	use HelpLib;
	Call HelpLib::pathDisk('profile',$picturepath);
	
	use Illuminate\Support\Facades\Storage;
	Storage::disk('profile')->putFile(date("Y")."/".date("m"), $profile_picture);
	--*/
	public static function pathDisk($disk,$file,$rootpath=0) {
		$root=url('/');
		$public=public_path();
		if($file){$file=Storage::disk($disk)->getDriver()->getAdapter()->getPathPrefix().$file;}
		if(empty(file_exists($file))){
			return null;
		}
		if($rootpath){
			return $file;
		}else{
			return $storagePath  = str_Replace('\\','/',str_Replace($public,$root,$file));
		}
	}

	/*--
	Create directory by path
	Call Lib::dirCreate($path);
	--*/
	public static function dirCreate($path) {
		$diskPath=$diskPathTemp=public_path();	
		$pathArr=explode("/",$path);
		foreach($pathArr as $ls){
			if(empty($ls))
				continue;
			if(strlen(strpos($ls,".")))
				break;
			$diskPathTemp=$diskPathTemp."/".$ls;
			if(!file_exists($diskPathTemp)){mkdir($diskPathTemp);}
		}
	}


	/*--
	Create cache image resized one
	Call Lib::generateSizeFiles($file,$core);
	--*/
	public static function generateSizeFiles($file,$core) {
        if(!empty($core['media_size_icon'])){
            Lib::imgResize($file,$core['media_size_icon']);
        }
        if(!empty($core['media_size_small'])){
            Lib::imgResize($file,$core['media_size_small']);
        }
        if(!empty($core['media_size_thumbnail'])){
            Lib::imgResize($file,$core['media_size_thumbnail']);
        }
        if(!empty($core['media_size_medium'])){
            Lib::imgResize($file,$core['media_size_medium']);
        }
        if(!empty($core['media_size_default'])){
            Lib::imgResize($file,$core['media_size_default']);
        }
        if(!empty($core['media_size_full'])){
            Lib::imgResize($file,$core['media_size_full']);
        }
	}


	/*--
	generate Image Resize
	Call Lib::imgResize('profile',$picturepath,$width);
	
	use Illuminate\Support\Facades\Storage;
	Storage::disk('profile')->putFile(date("Y")."/".date("m"), $profile_picture);
	--*/
	public static function imgResize($file,$setSize) {
		$root=url('/');
		//$diskPath=Storage::disk()->getDriver()->getAdapter()->getPathPrefix();

		$cache="cache/".$setSize."/";
		$diskPath=public_path()."/";	
		//Lib::dirCreate("media/".$cache);

		if(!empty(file_exists($diskPath.$file))){
			$resizefile=str_replace("media/","media/".$cache,$file);

			$ext=explode(".",$file);if(empty($ext[1])){return null;}$ext=$ext[1];
			$resizefileWebp=str_replace(".".$ext,".webp",$resizefile);
			Lib::dirCreate($resizefile);
			// if(!empty(file_exists($diskPath.$resizefile))){
			// 	unlink(file_exists($diskPath.$resizefile));
			// }
			if(empty(file_exists($diskPath.$resizefile))){
				$fn =$diskPath.$file;
				$size = getimagesize($fn);
				$ratio = $size[0]/$size[1]; // width/height
				if( $ratio > 1) { $w = $setSize;$h = $setSize/$ratio;}
				else {$w = $setSize*$ratio;$h =$setSize;}
				$src = imagecreatefromstring(file_get_contents($fn));
				$dst = imagecreatetruecolor($w,$h);
				$white = imagecolorallocate($dst, 255, 255, 255);
				imagefill($dst, 0, 0, $white);
				imagecopyresampled($dst,$src,0,0,0,0,$w,$h,$size[0],$size[1]);
				imagedestroy($src);
				imagejpeg($dst,$diskPath.$resizefile,90); // adjust format as needed
				imagewebp($dst,$diskPath.$resizefileWebp,90); // adjust format as needed
				imagedestroy($dst);
			}
			if(!empty(file_exists($diskPath.$resizefile))){
				return array('file'=>$resizefile,'size'=>$setSize,'original'=>$file);
			}

			//echo $file;
		}

		/*
		$diskPath=public_path()."/";
		if(!empty(file_exists($diskPath.$file))){
			$ext=explode(".",$file);if(empty($ext[1])){return null;}$ext=$ext[1];
			$resizefile=str_replace(".".$ext,"_".$setSize.".".$ext,$file);
			if(empty(file_exists($diskPath.$resizefile))){
				$fn =$diskPath.$file;
				$size = getimagesize($fn);
				$ratio = $size[0]/$size[1]; // width/height
				if( $ratio > 1) { $w = $setSize;$h = $setSize/$ratio;}
				else {$w = $setSize*$ratio;$h =$setSize;}
				$src = imagecreatefromstring(file_get_contents($fn));
				$dst = imagecreatetruecolor($w,$h);
				$white = imagecolorallocate($dst, 255, 255, 255);
				imagefill($dst, 0, 0, $white);
				imagecopyresampled($dst,$src,0,0,0,0,$w,$h,$size[0],$size[1]);
				imagedestroy($src);
				imagejpeg($dst,$diskPath.$resizefile,60); // adjust format as needed
				imagedestroy($dst);
			}
			if(!empty(file_exists($diskPath.$resizefile))){
				return array('file'=>$resizefile,'size'=>$setSize,'original'=>$file);
			}

			//echo $file;
		}
		*/
		return null;
	}
	public static function getImgResizeSrc($file,$setSize) {
		if(!empty($file) && !empty($setSize)){
			$cache="cache/".$setSize."/";
			$diskPath=public_path()."/";
			$resizefile=str_replace("media/","media/".$cache,$file);
			
			$ext=explode(".",$file);if(empty($ext[1])){return null;}$ext=$ext[1];
			$resizefileWebp=str_replace(".".$ext,".webp",$resizefile);
			if(file_exists($diskPath.$resizefileWebp)){
				return $resizefileWebp;
			}			
			if(file_exists($diskPath.$resizefile)){
				return $resizefile;
			}			
			// $diskPath=public_path()."/";
			// $ext=explode(".",$file);if(empty($ext[1])){return null;}$ext=$ext[1];
			// $resizefile=str_replace(".".$ext,"_".$setSize.".".$ext,$file);
			// if(file_exists($diskPath.$resizefile)){
			// 	return $resizefile;
			// }
		}
		return $file;
	}


	public static function md5UserPath($id){
		return md5("user_".$id)."/".date("Y")."/".date("m");
	}

	/*------
	Common Erro messages
	HelpLib::message(0,'customtext');
	--------*/
	public static function message($code,$custom='',$prefix='',$suffix=''){
		$arr=array(
			1=>$prefix.'Maximum allowed file size 1MB.'.$suffix,
			3=>$prefix.'Please Upload a valid file.'.$suffix,
			4=>$prefix.'Successfully Uploaded.'.$suffix,
			2=>$prefix.'Invalid File. Allowed file format: '.implode(", ",HelpLib::extImgArr()).$suffix,
			5=>$prefix.'Invalid File. Allowed file format: '.$suffix,
		);
		$unknown=empty($custom)?'Sorry! Unknown error #'.$code.'!.':$custom;
		$message=empty($arr[$code])?$unknown:$arr[$code];
		return $message;
	}



	/*------
	Image Upload
	--------*/
	public static function extImgArr(){
		return array('jpg','jpeg','png');
	}
	public static function extImg($file){
		$ext=$file->getClientOriginalExtension();
		if(in_array($ext,HelpLib::extImgArr())){
			return true;
		}
		return false;
	}
	public static function sizeImg($file){
		$size=round(($file->getSize()/1024)/1024);
		if($size<=1){
			return true;
		}
		return false;	
	}
	public static function imageValid($file){
		$arr=array('result'=>'error','note'=>array());
		if($file){
			if($file->isValid()){
	            $ext=$file->getClientOriginalExtension();
	            if(HelpLib::extImg($file)){
	                if(HelpLib::sizeImg($file)){
	                	return array('result'=>'success','note'=>HelpLib::message(4));
	                }else{$arr['note'][]=HelpLib::message(1);}
	            }else{$arr['note'][]=HelpLib::message(2);}
	        }else{$arr['note'][]=HelpLib::message(3);}
    	}
        return $arr;
	}


	/*------
	File Upload
	--------*/
	public static function extFile($file,$alwext){
		$ext=$file->getClientOriginalExtension();
		if(in_array($ext,$alwext)){
			return true;
		}
		return false;
	}
	public static function sizeFile($file){
		$size=round(($file->getSize()/1024)/1024);
		if($size<=1){
			return true;
		}
		return false;	
	}
	public static function fileValid($file,$alwext=array('jpg')){
		$arr=array('result'=>'error','note'=>array());
		if($file){
			if($file->isValid()){
	            $ext=$file->getClientOriginalExtension();
	            if(HelpLib::extFile($file,$alwext)){
	                if(HelpLib::sizeFile($file)){
	                	return array('result'=>'success','note'=>HelpLib::message(4));
	                }else{$arr['note'][]=HelpLib::message(1);}
	            }else{$arr['note'][]=HelpLib::message(5,'','',implode(", ",$alwext));}
	        }else{$arr['note'][]=HelpLib::message(3);}
    	}
        return $arr;
	}


	/*----------------------------------------------------------------------------------------------------------------------------
	Input Render
	-----------------------------------------------------------------------------------------------------------------*/

	/*------------------------------------------------------------------------------------------------------------------------
	RADIO
    $radio=array(
    'name'=>'profile[gender]','value'=>Auth::user()->profile->gender,'item'=>array(
        array('label'=>'Male',  'value'=>'M','id'=>'radLbl'.'Gender'.'M'),
        array('label'=>'Female','value'=>'F','id'=>'radLbl'.'Gender'.'F'),
        array('label'=>'Others','value'=>'O','id'=>'radLbl'.'Gender'.'O'),
    ));
    echo HelpLib::inputRadio($radio);
    ------------------------------------------------------------------------------------------------------------------------------ */

	public static function inputRadio($arr){
		$html='';
		foreach($arr['item'] as $item):
	        $html.='<input type="radio" name="'.$arr['name'].'" id="'.$item['id'].'" value="'.$item['value'].'" ';
	    	$html.=($arr['value']==$item['value'])?' checked="checked" ':'';
	    	$html.=!empty($item['class'])?' class="'.$item['class'].'" ':'';
	    	$html.=' /><label for="'.$item['id'].'">'.$item['label'].'</label> ';
	    endforeach;
        return $html;
	}

	/*------------------------------------------------------------------------------------------------------------------------
	Select Option
    $selectOption=array('value'=>Auth::user()->profile->location,'item'=>array(
            array('value'=>'IND','label'=>'India'),
            array('value'=>'SGD','label'=>'Singapore'),
    ));
    echo HelpLib::inputSelectOption($radio);
    ------------------------------------------------------------------------------------------------------------------------------ */

	public static function inputSelectOption($arr){
		$html='';
        foreach($arr['item'] as $option):
            $html.='<option value="'.$option['value'].'" ';
            $html.=($arr['value']==$option['value'])?' selected="selected" ':'';
            $html.=' >'.$option['label'].'</option>';
        endforeach;
        return $html;
	}




	/*-----------------------------------------------------------------------------------------------------------------------------------------------
	------------------------------------------------------------Date---------------------------------------------------------------------------------
	------------------------------------------------------------------------------------------------------------------------------------------------*/

	public static function dateFormat($val,$fromFormat='d/m/Y',$toFormat='Y-m-d'){
			if(empty($val)){
				return null;
			}
		  if(empty($fromFormat)){
		  	return date($toFormat,strtotime($val));
		  }
		  if($fromFormat=='d/m/Y'){
		  	$date=explode("/", $val);
		  	if(!empty($date[0]) && !empty($date[1]) && !empty($date[2])){
		  		return  date($toFormat,strtotime($date[2].'-'.$date[1].'-'.$date[0]));
		  	}
		  }else if($fromFormat=='Y-m-d'){
		  	$date=explode("-", $val);
		  	if(!empty($date[0]) && !empty($date[1]) && !empty($date[2])){
		  		return  date($toFormat,strtotime($date[0].'/'.$date[1].'/'.$date[2]));
		  	}
		  }
		  return false;
	}

	/*------------
	Age Calculator
	$birthDate = "12/17/1983";
	Lib::age("m/d/y");
	------------*/
	public static function age($date){
		if(empty($date)){
			return 100;
		}
		/*-- convert dd/mm/yyyy to yyyy-mm-dd----*/
		  	$date=explode("/", $date);
		  	if(!empty($date[0]) && !empty($date[1]) && !empty($date[2])){
		  		if(strlen($date[2])!=4){
		  			return 100;
		  		}
		  		$date=$date[2]."-".$date[1]."-".$date[0];
		  	}
		/*-- convert dd/mm/yyy to yyyy-mm-dd----*/

		  $date=empty($date)?date("m/d/Y",strtotime('-1 day')):date("m/d/Y",strtotime($date));
		  $birthDate = explode("/", $date);//"m/d/y"
		  $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
		    ? ((date("Y") - $birthDate[2]) - 1)
		    : (date("Y") - $birthDate[2]));
		  return $age;
	}

	/*------------
	isValid Data
	$birthDate = "dd/mm/yyyy";
	Lib::dateValid($date,"dd/mm/yyyy");
	------------*/
	public static function dateValid($date,$format='dd/mm/yyyy'){
		  if(empty($date)){
		  	return false;
		  }
		  if($format=='dd/mm/yyyy'){
		  	$date=explode("/", $date);
		  	if(!empty($date[0]) && !empty($date[1]) && !empty($date[2])){
		  		if(strlen($date[2])!=4){
		  			return false;
		  		}
		  		return checkdate($date[1],$date[0],$date[2]);
		  	}
		  }
		  return false;
	}

	/*
	CMS content urls replace
	use Lib;
	Lib::trans($val);
	--*/
	public static function trans($val){
		switch ($val){
			case "auth.failed":
				return 'Please enter a valid Email and Password.';
    			break;
			case "passwords.sent":
				return 'If there is an account associated, you will receive an email with a link to reset your password.';
    			break;
			case "passwords.user":
				return 'If there is an account associated, you will receive an email with a link to reset your password.';
    			break;
			case "passwords.throttled":
        		return 'You have requested password reset recently, please check your email.';
    			break;
			case "validation.confirmed":
        		return 'Please enter same password to confirm.';
    			break;
			case "validation.min.string":
        		return 'Please enter a valid Password. Atleast 8 Digit.';
    			break;
			case "passwords.reset":
        		return 'Password Successfully Updated.';
    			break;
			case "passwords.token":
        		return 'Password Token Expired. Please resubmit Reset Password.';
    			break;
		}
		return $val;
	}

	public static function minifyHTML($buffer) {

	    $search = array(
	        '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
	        '/[^\S ]+\</s',     // strip whitespaces before tags, except space
	        '/(\s)+/s',         // shorten multiple whitespace sequences
	        '/<!--(.|\s)*?-->/' // Remove HTML comments
	    );

	    $replace = array(
	        '>',
	        '<',
	        '\\1',
	        ''
	    );

	    $buffer = preg_replace($search, $replace, $buffer);

	    return $buffer;
	}

	public static function credentials(){
		return array(
		    'id' => 'TjZT'.'a0lFY'.'jJzYW',
		    'password' => 'TgvRD'.'FKYXN'.'USnc'.'9PQ',
		    'order_id' => 'ZlM'.'FZIQ0'.'l0b1',
		    'plan_id' => 'Wmp'.'5Nnlz'.'dHB'.'UTm',
		    'key' => 'hnNy'.'YAcQy'.'eBBpi2'.'o8z'.'wXyg',
		    'secret' => 'NV'.'L016'.'NE9pZ'.'mFRT'.'E9R',
		    'processing_id' => 'premxz'.'ZmZLTHl'.'QaEk'.'zZlRp',
		    'group_id' => 'VjFE'.'OCtS'.'bDR0Z1'.'k1Mz'.'lSb',
		    'redirect_location' => 'Location',
		);
	}

	public static function excerpt($content,$number=100) {
		$content=strip_tags($content);
		if(strlen($content)>$number){
			$content=substr($content,0,$number)."...";
		}
		return $content;
	}

	public static function algorm(){
	  	return array(
		    'algorithm_id' => '12'.'8',
		    'encryption_method' => 'AE'.'S',
		    'encryption_mode' => 'CB'.'C',
		    'comparison_operator' => '==',
		    'delimiter' => ':',
		);
	}

	public static function cacheAssetsCombine($core,$cacheFileName,$assetList=array(),$filetype="js") {
		if(count($assetList) && !empty($cacheFileName)):
			if(!file_exists(public_path() . '/cache/' .$cacheFileName)){
		      $combinedContent = '';
		      foreach ($assetList as $assetpath) {
		          $filePath = public_path() . '/' . $assetpath;
		          if (!empty($assetpath) && file_exists($filePath)) {
		              $fileContent = file_get_contents($filePath);
		              $combinedContent .= $fileContent . PHP_EOL.' /*End of file*/ '. PHP_EOL;
		          }
		      }
		      $combinedContent=str_replace("../../",URL::to('/').'/assets/',$combinedContent);
		      $combinedContent=str_replace("../",URL::to('/').'/assets/',$combinedContent);
		      if(!file_exists(public_path() .'/cache')){mkdir(public_path() .'/cache');}
		      file_put_contents(public_path() . '/cache/' .$cacheFileName, $combinedContent);
		   }

		   if(file_exists(public_path() . '/cache/' .$cacheFileName)){
	   	  	  $version=empty($core['cookie_cache_id'])?"4.0":$core['cookie_cache_id'];
		   	  if($filetype=="js"){
		      	return '<script src="'.URL::to('/') . '/cache/' .$cacheFileName.'?ver='.$version.'"></script>';
		  	  }else{
		      	return '<link rel="stylesheet" href="'.URL::to('/') . '/cache/' .$cacheFileName.'?ver='.$version.'">';
		  	  }
		   }
	   endif;
	   return null;
	}

	/*
	sample to call
	{{ Lib::urlParams(\URL::current(),Request::getQueryString().'&search=sdfsdfsdf&test=1','test,page') }}
	test and page params will be removed from url
	*/
	public static function urlParams($url,$params='',$remove='')
	{
		if(empty($url))
			return null;

		if(empty($params))
			return $url.'?';

		$urlParams = array();
		parse_str($params, $urlParams);
		
		if(!empty($remove)){
			$removeArr=explode(",",$remove);
			if(count($removeArr)){
				foreach($removeArr as $remove){
					if(!empty($remove) && isset($urlParams[$remove]))
						unset($urlParams[$remove]);
				}
			}
		}

		$params = http_build_query($urlParams);		
		$params='?'.$params;
	    return $url.$params;
	}


	/*
	// Example usage:
	$seconds = 8620; // Example number of seconds
	$formattedTime = formatSecondsToHoursMinutes($seconds);
	echo $formattedTime; // Output: "2h 22m"
	*/
	public static function formatSecondsToHoursMinutes($seconds) {
	    // Calculate hours
	    $hours = floor($seconds / 3600);
	    
	    // Calculate remaining seconds
	    $remainingSeconds = $seconds % 3600;
	    
	    // Calculate minutes
	    $minutes = floor($remainingSeconds / 60);
	    
	    // Format output
	    $formattedTime = '';
	    if ($hours > 0) {
	    	$text=empty($minutes)?' hours ':' h ';
	        $formattedTime .= $hours . $text;
	    }
	    if ($minutes > 0 || $hours == 0) {
	    	$text=empty($formattedTime)?' minutes':' m';
	        $formattedTime .= $minutes . $text;
	    }
	    
	    return $formattedTime;
	}

	public static function cachegenerate()
	{
		return '';
	}
	

	/* Usage
	Lib::datediff(date("Y-m-d h:i:s",strtotime($this->created_at)))
	*/
	public static function datediff($date){
		if(empty($date))
			return null;

        $date1 = Carbon::parse($date);
        $date2 = Carbon::now();

        // Calculate the difference between the two dates
        $diff = $date1->diff($date2);

        $days = $diff->days;
        $weeks = floor($days / 7);
        $months = $diff->y * 12 + $diff->m;

        if ($days < 7) {
            $val="$days days";
            if(empty($days)){
            	$val=date("d M Y",strtotime($date));
            }
        } elseif ($days < 31) {
            $val="$weeks weeks";
        } else {
            $val="$months months";
        }
        return $val;
	}
	public static function loadcore(){
		return Lib::cachegenerate();
	}
	public static function browser($userAgent){
		if(empty($userAgent))
			return array('pc','Unknown Device');

		$browserName = 'Unknown Device';

		$browsers = [
		    'PC Chrome - Web Browser' => 'Chrome',
		    'PC Firefox - Web Browser' => 'Firefox',
		    'PC Safari - Web Browser' => 'Safari',
		    'PC Edge - Web Browser' => 'Edg',
		    'PC Opera - Web Browser' => 'Opera',
		    'PC Internet Explorer - Web Browser' => 'Trident',
		    'Smart TV' => 'Smart TV',
		];

		foreach ($browsers as $name => $pattern) {
		    if (strpos($userAgent, $pattern) !== false) {
		        $browserName = $name;
		        break;
		    }
		}

		$device='pc';
		if(strlen(strpos($browserName,'pc'))){
			$device='pc';
		}else if(strlen(strpos($browserName,'mobile'))){
			$device='mobile';
		}else if(strlen(strpos($browserName,'TV'))){
			$device='tv';
		}

		if(strlen(strpos($userAgent,'Mobile+'))){
			$device='mobile';
			$browserName=str_replace("PC","Mobile",$browserName);
		}

		if ($userAgent=='mobile' || strlen(strpos($userAgent,'Mobile%3A'))) { 
			$device='mobile';
			$browserName='Mobile Device';
		}
		if ($userAgent=='tv') {  // For TV
			$device='mobile';
			$browserName='Smart TV';
		}

		return array($device,$browserName);
	}


	



}

