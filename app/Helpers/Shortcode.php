<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\UrlGenerator;
use HelpLib;
use App\User;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Form;
use App\Models\Newsletter;
use App\Models\Subscription;
use URL;
class Shortcode {

	/*
	use Shortcode;
	Shortcode::init();
	--*/
	public static function init($arr) {
		foreach($arr as $code){
			switch($code['code']){
				case "categorylist":
					return Shortcode::categorylist($code['attr']);
				case "tags":
					return Shortcode::tags($code['attr']);
				case "latestpost":
					return Shortcode::latestpost($code['attr']);
				case "cards":
					return Shortcode::cards($code['attr']);
				case "form":
					return Shortcode::form($code['attr']);
				case "pricing":
					return Shortcode::pricing($code['attr']);
				default:
					return false;
			}
		}
		return false;
	}

	//[tags tagid="3" title="Popular Tags" class="block"]
	public static function tags($attr) {
		foreach($attr as $k=>$v){$$k=empty($v)?'':$v;}$html='';
		if(empty($tagid))
			return null;
		$id=empty($id)?0:$id;
		$title=empty($title)?'':$title;
		$class=empty($class)?'':$class;

		if(!empty($title)):
			$html.='<div class="edu-blog-widget widget-tags '.$class.'">
					    <div class="inner">
					        <h5 class="widget-title">'.$title.'</h5>
					        <div class="content"><div class="tag-list">';
		endif;
			//$pid
			$html.=Category::categoryList($pid,$tagid);   
		if(!empty($title)):
			        $html.='</div></div>
			    </div>
			</div>';
		endif;
		return $html;
	}


	//[latestpost postids="3" title="Latest Post" class="block"]
	public static function latestpost($attr) {
		foreach($attr as $k=>$v){$$k=empty($v)?'':$v;}$html='';
		$title=empty($title)?'':$title;
		$class=empty($class)?'':$class;
		
		if(!empty($title)):
			$html.='<div class="edu-blog-widget widget-latest-post '.$class.'">
					    <div class="inner">
					        <h5 class="widget-title">'.$title.'</h5>
					        <div class="content latest-post-list">';
		endif; 
	        $html.=Post::latestpost($pid,$attr);       
		if(!empty($title)):
			        $html.='</div>
			    </div>
			</div>';
		endif;
		return $html;
	}

	//[categorylist id="1" title="Categories" class="block"]
	public static function categorylist($attr) {
		foreach($attr as $k=>$v){$$k=empty($v)?'':$v;}$html='';
		$id=empty($id)?0:$id;
		$title=empty($title)?'':$title;
		$class=empty($class)?'':$class;

		if(!empty($title)):
			$html.='<div class="edu-blog-widget widget-categories '.$class.'">
					    <div class="inner">
					        <h5 class="widget-title">'.$title.'</h5>
					        <div class="content">';
		endif;
			$html.=Category::categoryList($pid,$id);        
		if(!empty($title)):
			        $html.='</div>
			    </div>
			</div>';
		endif;
		return $html;
	}



	public static function cards($attr) {
		foreach($attr as $k=>$v){$$k=empty($v)?'':$v;}$html='';
		$cat=empty($cat)?'<current_id>':$cat;
		$limit=empty($limit)?0:$limit;
		if($cat=='<current_id>'){$cat=$catid;}
        $items=PostCategory::listByCat($cat,$limit,$attr);
		$template=empty($template)?'default':$template;
		return view('template.shortcode.cards.'.$template,['items'=>$items]);
	}


	public static function pricing($attr) {
		foreach($attr as $k=>$v){$$k=empty($v)?'':$v;}$html='';
		$type=empty($type)?1:$type;
    	$items=Subscription::where('type',$type)->where('status',1)->get();
		return view('template.shortcode.pricing', ['items'=>$items,'type'=>$type]);
	}


	public static function spamcheck(){
		$spamcheck=Form::all()->count();$spamcheck=md5(md5($spamcheck));
		return $spamcheck;
	}
	public static function newsletterSpamcheck(){
		$spamcheck=Newsletter::all()->count();$spamcheck=md5(md5($spamcheck));
		return $spamcheck;
	}







	public static function form($attr) {
		foreach($attr as $k=>$v){$$k=empty($v)?'':$v;}$html='';
		if(empty($type))
			return null;

		switch($type){
			case "contact":
				return Shortcode::formContact($attr);
			case "review":
				return Shortcode::formReview($attr);
			case "newsletter":
				return Shortcode::formNewsletter($attr);
			default:
				return false;
		}
		return $html;
	}
	public static function formContact($attr) {
		foreach($attr as $k=>$v){$$k=empty($v)?'':$v;}$html='';
		$spamcheck=Shortcode::spamcheck();
		return view('template.form.contact',['spamcheck'=>$spamcheck,'pid'=>$pid]);
	}
	public static function formReview($attr) {
		foreach($attr as $k=>$v){$$k=empty($v)?'':$v;}$html='';
		$spamcheck=Shortcode::spamcheck();
		return view('template.form.review',['spamcheck'=>$spamcheck,'pid'=>$pid]);
	}
	public static function formNewsletter($attr) {
		foreach($attr as $k=>$v){$$k=empty($v)?'':$v;}$html='';
		$spamcheck=Shortcode::newsletterSpamcheck();
		return view('template.form.newsletter',['spamcheck'=>$spamcheck,'pid'=>$pid]);
	}



}

