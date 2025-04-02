<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CoreConfig;
use App\Models\CoreConfigField;

class CoreConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data=array();


        $data[]=array('path'=>'general_website_themecolor','label'=>'Theme Color','type'=>'colorbox',
             'group'=>'General','subgroup'=>'General','sort'=>0,
             'value'=>'#000000');
        $data[]=array('path'=>'general_website_googlefont','label'=>'Google Font','type'=>'input',
             'group'=>'General','subgroup'=>'General','sort'=>0,
             'placeholder'=>'eg: Poppins:wght@300;400;500;600;800',
             'value'=>'Roboto:wght@400;700');
        $data[]=array('path'=>'general_website_favicon','label'=>'Favicon','type'=>'image',
             'group'=>'General','subgroup'=>'General','sort'=>0,
             'value'=>('img/favicon.png'));
        /*$data[]=array('path'=>'general_topbar_promotext','label'=>'Promo Text','type'=>'input',
             'group'=>'Topbar','subgroup'=>'General','sort'=>0,
             'value'=>'You’re invited to our latte art competition, Alberto. Come see our baristas go head to head on Saturday 7/21 at 7 pm.');
        $data[]=array('path'=>'general_topbar_phone','label'=>'Phone','type'=>'input',
             'group'=>'Topbar','subgroup'=>'General','sort'=>0,
             'value'=>'+6595959595');
        $data[]=array('path'=>'general_topbar_email','label'=>'Email','type'=>'input',
             'group'=>'Topbar','subgroup'=>'General','sort'=>0,
             'value'=>'info@example.com');
        $data[]=array('path'=>'general_topbar_welcometext','label'=>'Welcome Text','type'=>'input',
             'group'=>'Topbar','subgroup'=>'General','sort'=>0,
             'value'=>'Thanks for entering to win a year of free.');*/


        $data[]=array('path'=>'general_header_logo','label'=>'Logo','type'=>'image',
             'group'=>'Header','subgroup'=>'General','sort'=>0,
             'value'=>('img/logo.png'));

        $data[]=array('path'=>'global_seo_title','label'=>'Title','type'=>'input',
             'group'=>'SEO','subgroup'=>'General','sort'=>0,
             'value'=>'Watch TV Shows Online, Watch Movies Online');
        $data[]=array('path'=>'global_seo_description','label'=>'Description','type'=>'textarea',
             'group'=>'SEO','subgroup'=>'General','sort'=>0,
             'value'=>'Watch TV Shows Online, Watch Movies Online');
        $data[]=array('path'=>'global_seo_robots','label'=>'Robot Text','type'=>'input',
             'group'=>'SEO','subgroup'=>'General','sort'=>0,
             'value'=>'index, follow');
        $data[]=array('path'=>'global_seo_image','label'=>'Default Image','type'=>'image',
             'group'=>'SEO','subgroup'=>'General','sort'=>0,
             'value'=>('img/logo.png'));
        $data[]=array('path'=>'global_seo_imagewidth','label'=>'Image Width','type'=>'input',
             'group'=>'SEO','subgroup'=>'General','sort'=>0,
             'value'=>'500');
        $data[]=array('path'=>'global_seo_imageheight','label'=>'Image Height','type'=>'input',
             'group'=>'SEO','subgroup'=>'General','sort'=>0,
             'value'=>'400');

        $data[]=array('path'=>'general_script_css','label'=>'Custom CSS','type'=>'textarea',
             'group'=>'Additional','subgroup'=>'General','sort'=>0,
             'value'=>'');
        $data[]=array('path'=>'general_script_beforeheadclose','label'=>'Before Head Close','type'=>'textarea',
             'group'=>'Additional','subgroup'=>'General','sort'=>0,
             'value'=>'');
        $data[]=array('path'=>'general_script_afterbodyopen','label'=>'After Body Open','type'=>'textarea',
             'group'=>'Additional','subgroup'=>'General','sort'=>0,
             'value'=>'');
        $data[]=array('path'=>'general_script_beforebodyclose','label'=>'Before Body Close','type'=>'textarea',
             'group'=>'Additional','subgroup'=>'General','sort'=>0,
             'value'=>'');


        $data[]=array('path'=>'general_footer_section1title','label'=>'Title','type'=>'input',
             'group'=>'Footer','subgroup'=>'Section 1','sort'=>0,
             'value'=>'');
        $data[]=array('path'=>'general_footer_section1','label'=>'Content','type'=>'editor',
             'group'=>'Footer','subgroup'=>'Section 1','sort'=>0,
             'value'=>'<p>TEL: +91 99999 88888 <br> <a href="mailto:info@google.com">info@google.com</a></p>                  
             ');//<p># Address Information</p>
        $data[]=array('path'=>'general_footer_section2title','label'=>'Title','type'=>'input',
             'group'=>'Footer','subgroup'=>'Section 2','sort'=>0,
             'value'=>'');
        $data[]=array('path'=>'general_footer_section2','label'=>'Content','type'=>'editor',
             'group'=>'Footer','subgroup'=>'Section 2','sort'=>0,
             'value'=>'<ul>
                     <li><a href="'.env('APP_URL').'/my-account">My Account</a></li>
                     <li><a href="'.env('APP_URL').'/faq">FAQ</a></li>
                  </ul>');
        $data[]=array('path'=>'general_footer_section3title','label'=>'Title','type'=>'input',
             'group'=>'Footer','subgroup'=>'Section 3','sort'=>0,
             'value'=>'');
        $data[]=array('path'=>'general_footer_section3','label'=>'Content','type'=>'editor',
             'group'=>'Footer','subgroup'=>'Section 3','sort'=>0,
             'value'=>'<ul>
                     <li><a href="'.env('APP_URL').'/">Home</a></li>
                     <li><a href="'.env('APP_URL').'/pricing">Pricing</a></li>
                     <li><a href="'.env('APP_URL').'/careers">Careers</a></li>
                  </ul>');
        $data[]=array('path'=>'general_footer_section4title','label'=>'Title','type'=>'input',
             'group'=>'Footer','subgroup'=>'Section 4','sort'=>0,
             'value'=>'');
        $data[]=array('path'=>'general_footer_section4','label'=>'Content','type'=>'editor',
             'group'=>'Footer','subgroup'=>'Section 4','sort'=>0,
             'value'=>'<p># Address Line <br> City, State - 600000</p>');

        $data[]=array('path'=>'general_footer_logo','label'=>'Logo','type'=>'image',
             'group'=>'Footer','subgroup'=>'Footer Bar','sort'=>0,
             'value'=>('img/footer-logo.png'));
        $data[]=array('path'=>'general_footer_copywrite','label'=>'Copywrite Text','type'=>'input',
             'group'=>'Footer','subgroup'=>'Footer Bar','sort'=>0,
             'value'=>'&copy; '.date('y').' BESTCAST. All Rights Reserved.');
        $data[]=array('path'=>'general_footer_links','label'=>'Links','type'=>'editor',
             'group'=>'Footer','subgroup'=>'Footer Bar','sort'=>0,
             'value'=>'<ul><li><a href="'.env('APP_URL').'/privacy-policy">Privacy Policy</a></li><li><a href="'.env('APP_URL').'/terms-conditions">Terms &amp; Conditions</a></li></ul>');


        $data[]=array('path'=>'socialmedia_header_enable','label'=>'Header Enable?','type'=>'enable',
             'group'=>'Social Media','subgroup'=>'General','sort'=>0,
             'value'=>'1');
        $data[]=array('path'=>'socialmedia_footer_enable','label'=>'Footer Enable?','type'=>'enable',
             'group'=>'Social Media','subgroup'=>'General','sort'=>0,
             'value'=>'1');

        $data[]=array('path'=>'socialmedia_facebook_text','label'=>'Text','type'=>'input',
             'group'=>'Social Media','subgroup'=>'Facebook','sort'=>0,
             'value'=>'Facebook');
        $data[]=array('path'=>'socialmedia_facebook_link','label'=>'Link','type'=>'input',
             'group'=>'Social Media','subgroup'=>'Facebook','sort'=>0,
             'value'=>'#');
        // $data[]=array('path'=>'socialmedia_facebook_icon','label'=>'Icon','type'=>'image',
        //      'group'=>'Social Media','subgroup'=>'Facebook','sort'=>0,
        //      'value'=>('img/icon/socialmedia/default/facebook-white.png'));
        // $data[]=array('path'=>'socialmedia_facebook_iconhover','label'=>'Icon Hover','type'=>'image',
        //      'group'=>'Social Media','subgroup'=>'Facebook','sort'=>0,
        //      'value'=>('img/icon/socialmedia/default/facebook.png'));


        $data[]=array('path'=>'socialmedia_twitter_text','label'=>'Text','type'=>'input',
             'group'=>'Social Media','subgroup'=>'Twitter','sort'=>0,
             'value'=>'Twitter');
        $data[]=array('path'=>'socialmedia_twitter_link','label'=>'Link','type'=>'input',
             'group'=>'Social Media','subgroup'=>'Twitter','sort'=>0,
             'value'=>'#');
        // $data[]=array('path'=>'socialmedia_twitter_icon','label'=>'Icon','type'=>'image',
        //      'group'=>'Social Media','subgroup'=>'Twitter','sort'=>0,
        //      'value'=>('img/icon/socialmedia/default/twitter-white.png'));
        // $data[]=array('path'=>'socialmedia_twitter_iconhover','label'=>'Icon Hover','type'=>'image',
        //      'group'=>'Social Media','subgroup'=>'Twitter','sort'=>0,
        //      'value'=>('img/icon/socialmedia/default/twitter.png'));

        $data[]=array('path'=>'socialmedia_instagram_text','label'=>'Text','type'=>'input',
             'group'=>'Social Media','subgroup'=>'Instagram','sort'=>0,
             'value'=>'Instagram');
        $data[]=array('path'=>'socialmedia_instagram_link','label'=>'Link','type'=>'input',
             'group'=>'Social Media','subgroup'=>'Instagram','sort'=>0,
             'value'=>'#');
        // $data[]=array('path'=>'socialmedia_instagram_icon','label'=>'Icon','type'=>'image',
        //      'group'=>'Social Media','subgroup'=>'Instagram','sort'=>0,
        //      'value'=>('img/icon/socialmedia/default/instagram-white.png'));
        // $data[]=array('path'=>'socialmedia_instagram_iconhover','label'=>'Icon Hover','type'=>'image',
        //      'group'=>'Social Media','subgroup'=>'Instagram','sort'=>0,
        //      'value'=>('img/icon/socialmedia/default/instagram.png'));


        // $data[]=array('path'=>'socialmedia_linked_text','label'=>'Text','type'=>'input',
        //      'group'=>'Social Media','subgroup'=>'Linked','sort'=>0,
        //      'value'=>'Linked');
        // $data[]=array('path'=>'socialmedia_linked_link','label'=>'Link','type'=>'input',
        //      'group'=>'Social Media','subgroup'=>'Linked','sort'=>0,
        //      'value'=>'#');
        // $data[]=array('path'=>'socialmedia_linked_icon','label'=>'Icon','type'=>'image',
        //      'group'=>'Social Media','subgroup'=>'Linked','sort'=>0,
        //      'value'=>('img/icon/socialmedia/default/linkedin-white.png'));
        // $data[]=array('path'=>'socialmedia_linked_iconhover','label'=>'Icon Hover','type'=>'image',
        //      'group'=>'Social Media','subgroup'=>'Linked','sort'=>0,
        //      'value'=>('img/icon/socialmedia/default/linkedin.png'));


        // $data[]=array('path'=>'socialmedia_pininterest_text','label'=>'Text','type'=>'input',
        //      'group'=>'Social Media','subgroup'=>'Pininterest','sort'=>0,
        //      'value'=>'Pininterest');
        // $data[]=array('path'=>'socialmedia_pininterest_link','label'=>'Link','type'=>'input',
        //      'group'=>'Social Media','subgroup'=>'Pininterest','sort'=>0,
        //      'value'=>'#');
        // $data[]=array('path'=>'socialmedia_pininterest_icon','label'=>'Icon','type'=>'image',
        //      'group'=>'Social Media','subgroup'=>'Pininterest','sort'=>0,
        //      'value'=>('img/icon/socialmedia/default/pininterest-white.png'));
        // $data[]=array('path'=>'socialmedia_pininterest_iconhover','label'=>'Icon Hover','type'=>'image',
        //      'group'=>'Social Media','subgroup'=>'Pininterest','sort'=>0,
        //      'value'=>('img/icon/socialmedia/default/pininterest.png'));


        // $data[]=array('path'=>'socialmedia_youtube_text','label'=>'Text','type'=>'input',
        //      'group'=>'Social Media','subgroup'=>'Youtube','sort'=>0,
        //      'value'=>'Youtube');
        // $data[]=array('path'=>'socialmedia_youtube_link','label'=>'Link','type'=>'input',
        //      'group'=>'Social Media','subgroup'=>'Youtube','sort'=>0,
        //      'value'=>'#');
        // $data[]=array('path'=>'socialmedia_youtube_icon','label'=>'Icon','type'=>'image',
        //      'group'=>'Social Media','subgroup'=>'Youtube','sort'=>0,
        //      'value'=>('img/icon/socialmedia/default/youtube-white.png'));
        // $data[]=array('path'=>'socialmedia_youtube_iconhover','label'=>'Icon Hover','type'=>'image',
        //      'group'=>'Social Media','subgroup'=>'Youtube','sort'=>0,
        //      'value'=>('img/icon/socialmedia/default/youtube.png'));


        $data[]=array('path'=>'socialmedia_email_text','label'=>'Text','type'=>'input',
             'group'=>'Social Media','subgroup'=>'Email','sort'=>0,
             'value'=>'Email');
        $data[]=array('path'=>'socialmedia_email_link','label'=>'Link','type'=>'input',
             'group'=>'Social Media','subgroup'=>'Email','sort'=>0,
             'value'=>'#');
        // $data[]=array('path'=>'socialmedia_email_icon','label'=>'Icon','type'=>'image',
        //      'group'=>'Social Media','subgroup'=>'Email','sort'=>0,
        //      'value'=>('img/icon/socialmedia/default/email.png'));
        // $data[]=array('path'=>'socialmedia_email_iconhover','label'=>'Icon Hover','type'=>'image',
        //      'group'=>'Social Media','subgroup'=>'Email','sort'=>0,
        //      'value'=>('img/icon/socialmedia/default/email-back.png'));

        // $data[]=array('path'=>'page_seo_title',               'value'=>'');
        // $data[]=array('path'=>'page_seo_description',         'value'=>'');
        // $data[]=array('path'=>'page_seo_robots',              'value'=>'');
        // $data[]=array('path'=>'page_seo_image',               'value'=>'');
        // $data[]=array('path'=>'page_script_css',              'value'=>'');
        // $data[]=array('path'=>'page_script_beforeheadclose',  'value'=>'');
        // $data[]=array('path'=>'page_script_afterbodyopen',    'value'=>'');
        // $data[]=array('path'=>'page_script_beforebodyclose',  'value'=>'');


        $data[]=array('path'=>'pages_login_bg','label'=>'Background','type'=>'image',
             'group'=>'Pages','subgroup'=>'Login','sort'=>0,
             'value'=>('img/sample/auth_bg.jpg'));

        $data[]=array('path'=>'pages_register_bg','label'=>'Background','type'=>'image',
             'group'=>'Pages','subgroup'=>'Register','sort'=>0,
             'value'=>('img/sample/auth_bg.jpg'));

        
        $data[]=array('path'=>'email_content_banner','label'=>'Banner Image','type'=>'image',
             'group'=>'Email','subgroup'=>'Template','sort'=>0,
             'value'=>env('APP_URL').env('APP_PATH').('img/sample/email_banner.jpg'));
        $data[]=array('path'=>'email_notify_admin','label'=>'Email copy send to?','type'=>'input',
             'group'=>'Email','subgroup'=>'Sender','sort'=>0,
             'value'=>'noreply@'.env('APP_HOST'));
        $data[]=array('path'=>'email_support_admin','label'=>'Support Email','type'=>'input',
             'group'=>'Email','subgroup'=>'Content','sort'=>0,
             'value'=>'support@'.env('APP_HOST'));

        $data[]=array('path'=>'android_logo','label'=>'Logo','type'=>'image',
             'group'=>'Footer','subgroup'=>'Footer Bar','sort'=>0,
             'value'=>('img/android_icon.png'));
        $data[]=array('path'=>'ios_logo','label'=>'Logo','type'=>'image',
             'group'=>'Footer','subgroup'=>'Footer Bar','sort'=>0,
             'value'=>('img/ios_icon.png'));
        $data[]=array('path'=>'general_app_experience','label'=>'Copywrite Text','type'=>'input',
             'group'=>'Footer','subgroup'=>'Footer Bar','sort'=>0,
             'value'=>'For better experience,download the BESTCAST app now');

        foreach($data as $val){
            $newItem = CoreConfig::create([
                'path'          => $val['path'],
                'value'         => $val['value']       
            ]);
        }

        foreach($data as $val){
            $newItem = CoreConfigField::create([
                'path'          => $val['path'],
                'label'         => $val['label'],
                'type'          => $val['type'],
                'placeholder'   => empty($val['placeholder'])?'':$val['placeholder'],
                'comment'       => empty($val['comment'])?'':$val['comment'],
                'classname'     => empty($val['classname'])?'':$val['classname'],
                'errormessage'  => empty($val['errormessage'])?'':$val['errormessage'],
                'option'        => empty($val['option'])?'':$val['option'],
                'group'         => empty($val['group'])?'':$val['group'],
                'subgroup'      => empty($val['subgroup'])?'':$val['subgroup'],
                'sort'          => empty($val['sort'])?0:$val['sort']
            ]);
        }
    }
}