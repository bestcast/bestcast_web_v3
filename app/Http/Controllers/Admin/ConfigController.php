<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use Auth;
use Lib;
use App\Models\CoreConfig;
use App\Models\CoreConfigField;
use App\Models\Category;
use App\Models\Media;

/*

Database Add New Core Field

INSERT INTO `core_config_field` (`id`, `path`, `label`, `type`, `placeholder`, `comment`, `classname`, `errormessage`, `option`, `group`, `subgroup`, `sort`, `created_at`, `updated_at`) VALUES
(null, 'email_content_banner', 'Banner Image', 'image', '', '', '', '', '', 'Email', '', 0, '2022-09-09 14:49:48', '2022-09-09 14:49:48'),
(null, 'email_notify_admin', 'Email copy send to?', 'input', '', '', '', '', '', 'Email', '', 0, '2022-09-09 14:49:48', '2022-09-09 14:49:48');


INSERT INTO `core_config` (`id`, `user_id`, `store_id`, `post_id`, `path`, `value`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(NULL, 0, 0, 0, 'email_content_banner', '', NULL, NULL, NULL, '2023-01-24 11:41:17'),
(NULL, 0, 0, 0, 'email_notify_admin', 'kharykaran@gmail.com', NULL, NULL, NULL, '2023-01-24 12:36:29');


INSERT INTO `core_config_field` (`id`, `path`, `label`, `type`, `placeholder`, `comment`, `classname`, `errormessage`, `option`, `group`, `subgroup`, `sort`, `created_at`, `updated_at`) VALUES
(null, 'media_size_full', 'Full Size', 'input', '', '', '', '', '', 'Media', '', 0, '2022-09-09 14:49:48', '2022-09-09 14:49:48'),
(null, 'media_size_default', 'Default Size', 'input', '', '', '', '', '', 'Media', '', 0, '2022-09-09 14:49:48', '2022-09-09 14:49:48'),
(null, 'media_size_medium', 'Medium Size', 'input', '', '', '', '', '', 'Media', '', 0, '2022-09-09 14:49:48', '2022-09-09 14:49:48'),
(null, 'media_size_thumbnail', 'Thumbnail Size', 'input', '', '', '', '', '', 'Media', '', 0, '2022-09-09 14:49:48', '2022-09-09 14:49:48'),
(null, 'media_size_small', 'Small Size', 'input', '', '', '', '', '', 'Media', '', 0, '2022-09-09 14:49:48', '2022-09-09 14:49:48'),
(null, 'media_size_icon', 'Icon Size', 'input', '', '', '', '', '', 'Media', '', 0, '2022-09-09 14:49:48', '2022-09-09 14:49:48');



INSERT INTO `core_config` (`id`, `user_id`, `store_id`, `post_id`, `path`, `value`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(NULL, 0, 0, 0, 'media_size_full', '1920', NULL, NULL, NULL, '2023-01-24 11:41:17'),
(NULL, 0, 0, 0, 'media_size_default', '1350', NULL, NULL, NULL, '2023-01-24 11:41:17'),
(NULL, 0, 0, 0, 'media_size_medium', '800', NULL, NULL, NULL, '2023-01-24 11:41:17'),
(NULL, 0, 0, 0, 'media_size_thumbnail', '450', NULL, NULL, NULL, '2023-01-24 11:41:17'),
(NULL, 0, 0, 0, 'media_size_small', '200', NULL, NULL, NULL, '2023-01-24 12:36:29'),
(NULL, 0, 0, 0, 'media_size_icon', '100', NULL, NULL, NULL, '2023-01-24 12:36:29');


INSERT INTO `core_config_field` (`id`, `path`, `label`, `type`, `placeholder`, `comment`, `classname`, `errormessage`, `option`, `group`, `subgroup`, `sort`, `created_at`, `updated_at`) VALUES
(null, 'cookie_consent_enable', 'Enable?', 'enable', '', '', '', '', '', 'Cookies Consent', '', 0, '2022-09-09 14:49:48', '2022-09-09 14:49:48'),
(null, 'cookie_consent_content', 'Small Size', 'editor', '', '', '', '', '', 'Cookies Consent', '', 0, '2022-09-09 14:49:48', '2022-09-09 14:49:48'),
(null, 'cookie_consent_button', 'Button Name', 'input', '', '', '', '', '', 'Cookies Consent', '', 0, '2022-09-09 14:49:48', '2022-09-09 14:49:48');


INSERT INTO `core_config_field` (`id`, `path`, `label`, `type`, `placeholder`, `comment`, `classname`, `errormessage`, `option`, `group`, `subgroup`, `sort`, `created_at`, `updated_at`) VALUES
(null, 'cookie_cache_id', 'Cache ID', 'input', '', 'Enter Random Id without space, for to refresh cache.', '', '', '', 'Cookies Consent', '', 0, '2022-09-09 14:49:48', '2022-09-09 14:49:48');


INSERT INTO `core_config` (`id`, `user_id`, `store_id`, `post_id`, `path`, `value`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(NULL, 0, 0, 0, 'cookie_consent_enable', '1', NULL, NULL, NULL, '2023-01-24 12:36:29'),
(NULL, 0, 0, 0, 'cookie_consent_content', '<p>We use cookies on our site to provide you with the best user experience. <a href="https://moviesapp.com/privacy-policy">Read Cookie Policy</a></p>', NULL, NULL, NULL, '2023-01-24 12:36:29'),
(NULL, 0, 0, 0, 'cookie_consent_button', 'Accept', NULL, NULL, NULL, '2023-01-24 12:36:29');

INSERT INTO `core_config` (`id`, `user_id`, `store_id`, `post_id`, `path`, `value`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES 
(NULL, 0, 0, 0, 'cookie_cache_id', '1.1.1', NULL, NULL, NULL, '2023-01-24 12:36:29');

*/


class ConfigController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //$config = CoreConfig::all();
        $config = CoreConfig::item();
        return view('admin.config.index')->with(['config'=>$config]);
    }
    public function save(Request $request)
    {
        $input=$request->all();
        //dd($input);
        foreach($input as $path=>$value){
            if(!empty($path)){
                $core = CoreConfig::where('path', '=', $path)->first();
                if(!empty($core)){
                    $core->value = $value;
                    $tmppath=$path."_file";
                    if(!empty($request->$tmppath)){
                        $uploadfn=Lib::SavePublicMedia($request,$path);
                        //dd($uploadfn);
                        if(!empty($uploadfn)){$core->value=$uploadfn;}
                    }
                    $core->save();
                }
            }
        }
        $config = CoreConfig::item();
        return view('admin.config.index')->with('config', $config)->with('success', 'Updated Successfully.');
    }
    public function info()
    {
        return view('admin.config.info');
    }
    public function indexer()
    {        
        return view('admin.config.indexer');
    }
    public function indexerSitemap()
    {
        $urls=CoreConfig::getAllURL();
        if(count($urls)){
            $xml='';
            $xml.='<?xml version="1.0" encoding="UTF-8"?>';
            $xml.='<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
              foreach($urls as $path=>$item){
                $xml.='<url>';
                  $xml.='<loc>'.Lib::publicUrl($path).'</loc>';
                  $update_date=date("Y-m-d",strtotime($item['updated_at']));
                  $minimum_date=date("Y-m-d",strtotime("2023-11-20"));
                  if ($update_date < $minimum_date) {
                    $update_date="2023-11-20";
                  }
                  $xml.='<lastmod>'.$update_date.'</lastmod>';
                  //$xml.='<priority>'.$item['priority'].'</priority>';
                $xml.='</url>';
              }
            $xml.='</urlset>';
            $file=fopen('sitemap.xml',"w");fwrite($file,$xml);fclose($file);
        }
        return view('admin.config.indexer')->with('success', 'Generated Successfully.');
    }

    public function indexerCache(Request $request)
    {


        $input=$request->all();
        if(!empty($input['generate'])){
            $countarr=Category::getpostcount();
            $urls=CoreConfig::getAllURL();
            //dd($urls);
            if(count($urls)){
                foreach($urls as $path=>$item){
                    $url=Lib::publicUrl($path);
                    //echo $url.'<br>';

                    $cache_pagrurl=$url;
                    $cfolder='cache/'.strlen($cache_pagrurl).'/'.md5($cache_pagrurl).'/';if(!file_exists('cache')){mkdir('cache');}if(!file_exists('cache/'.strlen($cache_pagrurl))){mkdir('cache/'.strlen($cache_pagrurl));}if(!file_exists($cfolder)){

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        // SSL important
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        $output = curl_exec($ch);
                        curl_close($ch);
                        break;
                    }

                }
            }
            //die();
            $cacheData=CoreConfig::getCachedPagesData();
            return json_encode($cacheData+array('url'=>$url,'path'=>$cfolder));
            //return view('admin.config.indexer')->with('success', 'Generated Successfully. '.$cfolder.' '.$url);

        }
        if(!empty($input['clear'])){
            $countarr=Category::getpostcount();
            if(file_exists('cache')){
                    rename("cache","cache_".date("Y-m-d-h-i-s"));
            }
            return view('admin.config.indexer')->with('success', 'Cache Cleared Successfully.');
        }
        if(!empty($input['clearcssjs'])){
            $assestlist=array('header_css.css','footer_css.css','header_js.js','footer_js.js');
            foreach($assestlist as $item){
                if(file_exists('cache') && file_exists('cache/'.$item)){
                    unlink('cache/'.$item);
                }
                $core = CoreConfig::where('path', '=', 'cookie_cache_id')->first();
                if(!empty($core)){
                    $core->value = md5(time());
                    $core->save();
                }
            }
            return view('admin.config.indexer')->with('success', 'CSS/JS Cache Cleared Successfully.');
        }
        return view('admin.config.indexer')->with('error', 'Failed.');
    }


    public function regenerateImage(Request $request)
    {
        $core=CoreConfig::list();$iconsize=empty($core['media_size_icon'])?100:$core['media_size_icon'];
        $media = Media::all();
        $i=0;foreach($media as $list){
            if(!empty($list->urlkey) && ($list->type!='application/pdf')){
                $cache="cache/".$iconsize."/";
                $diskPath=public_path()."/";
                $resizefile=str_replace("media/","media/".$cache,$list->urlkey);
                if(empty(file_exists($diskPath.$resizefile))){
                    Lib::generateSizeFiles($list->urlkey,$core);
                    //echo $list->id."(".$diskPath.$resizefile.")<br>";
                    $i++;
                }
                if($i>=10){
                    break;
                }
            }
        }
        $cacheData=CoreConfig::getCachedImagesData();
        return json_encode($cacheData);
        return view('admin.config.indexer')->with('success', 'Image Cache Generated Successfully.');
    }

}
