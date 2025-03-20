<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Meta;
use App\Models\PostContent;
use App\Models\Media;

class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $page=Post::create([     'status'=>1,'type'=>'page','urlkey'=>'home','title'=>'Home',
                'excerpt' => ''
        ]);

            $postContent = PostContent::create(['post_id' => $page->id,
                'content'=>'<p>Watch anywhere. Cancel anytime.</p>'
            ]);

            $meta = Meta::create(['meta_id' => $page->id,'type'=>1,'path'=>'subtitle',
                'value'=>'Unlimited movies, TV shows and more'
            ]);

            $meta = Meta::create(['meta_id' => $page->id,'type'=>1,'path'=>'banner_background_urlkey',
                'value'=>'img/sample/auth_bg.jpg'
            ]);

            $meta = Meta::create(['meta_id' => $page->id,'type'=>1,'path'=>'register_form_content',
                'value'=>'Ready to watch? Enter your email to create or restart your membership.'
            ]);

            $meta = Meta::create(['meta_id' => $page->id,'type'=>1,'path'=>'sec_count',
                'value'=>'3'
            ]);

            for($i=1;$i<=3;$i++){
                $meta = Meta::create(['meta_id' => $page->id,'type'=>1,'path'=>'sec_enable_'.$i,
                    'value'=>'on'
                ]);
                $meta = Meta::create(['meta_id' => $page->id,'type'=>1,'path'=>'sec_title_'.$i,
                    'value'=>'Join Unlimited movies'
                ]);
                $meta = Meta::create(['meta_id' => $page->id,'type'=>1,'path'=>'sec_subtitle_'.$i,
                    'value'=>'WATCH ANYWHERE'
                ]);
                $meta = Meta::create(['meta_id' => $page->id,'type'=>1,'path'=>'sec_content_'.$i,
                    'value'=>'<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sit amet, consect adipi scing elit, sed do eiusmod tempor incididunt ut sed do eiusmod tempor incididunt ut labore et dolore aliqua.</p>'
                ]);
                $meta = Meta::create(['meta_id' => $page->id,'type'=>1,'path'=>'sec_image_'.$i.'_urlkey',
                    'value'=>'img/sample/auth_bg.jpg'
                ]);
            }


            $meta = Meta::create(['meta_id' => $page->id,'type'=>1,'path'=>'faq_count',
                'value'=>'6'
            ]);

            $meta = Meta::create(['meta_id' => $page->id,'type'=>1,'path'=>'faq_enable',
                'value'=>'on'
            ]);
            $meta = Meta::create(['meta_id' => $page->id,'type'=>1,'path'=>'faq_title',
                'value'=>'Have any Questions?'
            ]);            
            $meta = Meta::create(['meta_id' => $page->id,'type'=>1,'path'=>'faq_subtitle',
                'value'=>'FAQ QUESTION'
            ]);

            for($i=1;$i<=6;$i++){
                $meta = Meta::create(['meta_id' => $page->id,'type'=>1,'path'=>'faq_enable_'.$i,
                    'value'=>'on'
                ]);
                $meta = Meta::create(['meta_id' => $page->id,'type'=>1,'path'=>'faq_title_'.$i,
                    'value'=>'Lorem ipsum dolor sit amet, consectetur adipiscing?'
                ]);
                $meta = Meta::create(['meta_id' => $page->id,'type'=>1,'path'=>'faq_content_'.$i,
                    'value'=>'<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sit amet, consect adipi scing elit, sed do eiusmod tempor incididunt ut sed do eiusmod tempor incididunt ut labore et dolore aliqua.</p><p>You can&nbsp;<a href="#">watch as much as you want</a>, whenever you want, without a single ad Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sit amet, consect adipi scing elit, sed do eiusmod tempor incididunt ut sed do eiusmod tempor incididunt ut labore et dolore aliqua.</p>'
                ]);
            }



        $page=Post::create([     'status'=>1,'type'=>'page','urlkey'=>'movies','title'=>'Movies',
                'excerpt' => ''
        ]);

        $page=Post::create([     'status'=>1,'type'=>'page','urlkey'=>'new','title'=>'New',
                'excerpt' => ''
        ]);

        $page=Post::create([     'status'=>1,'type'=>'page','urlkey'=>'popular','title'=>'Popular',
                'excerpt' => ''
        ]);
            
        $page=Post::create([     'status'=>1,'type'=>'page','urlkey'=>'faq','title'=>'FAQ','template'=>1,
                'excerpt' => ''
        ]);

            $meta = Meta::create(['meta_id' => $page->id,'type'=>1,'path'=>'faq_count',
                'value'=>'10'
            ]);
            $meta = Meta::create(['meta_id' => $page->id,'type'=>1,'path'=>'faq_enable',
                'value'=>'on'
            ]);

            for($i=1;$i<=10;$i++){
                $meta = Meta::create(['meta_id' => $page->id,'type'=>1,'path'=>'faq_enable_'.$i,
                    'value'=>'on'
                ]);
                $meta = Meta::create(['meta_id' => $page->id,'type'=>1,'path'=>'faq_title_'.$i,
                    'value'=>'Lorem ipsum dolor sit amet, consectetur adipiscing?'
                ]);
                $meta = Meta::create(['meta_id' => $page->id,'type'=>1,'path'=>'faq_content_'.$i,
                    'value'=>'<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sit amet, consect adipi scing elit, sed do eiusmod tempor incididunt ut sed do eiusmod tempor incididunt ut labore et dolore aliqua.</p><p>You can&nbsp;<a href="#">watch as much as you want</a>, whenever you want, without a single ad Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sit amet, consect adipi scing elit, sed do eiusmod tempor incididunt ut sed do eiusmod tempor incididunt ut labore et dolore aliqua.</p>'
                ]);
            }

        $page=Post::create([     'status'=>1,'type'=>'page','urlkey'=>'careers','title'=>'Careers','template'=>1,
                'excerpt' => ''
        ]);

        $postContent = PostContent::create(['post_id' => $page->id,
            'content'=>'<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sit amet, consect adipi scing elit, sed do eiusmod tempor incididunt ut sed do eiusmod tempor incididunt ut labore et dolore aliqua.</p><p>You can&nbsp;<a href="#">watch as much as you want</a>, whenever you want, without a single ad Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sit amet, consect adipi scing elit, sed do eiusmod tempor incididunt ut sed do eiusmod tempor incididunt ut labore et dolore aliqua.</p>'
        ]);


        $page=Post::create([     'status'=>1,'type'=>'page','urlkey'=>'terms-conditions','title'=>'Terms & Conditions','template'=>1,
                'excerpt' => ''
        ]);

        $postContent = PostContent::create(['post_id' => $page->id,
            'content'=>'<h3>Consectetur adipiscing</h3> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sit amet, consect adipi scing elit, sed do eiusmod tempor incididunt ut sed do eiusmod tempor incididunt ut labore et dolore aliqua.</p><p>You can&nbsp;<a href="#">watch as much as you want</a>, whenever you want, without a single ad Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sit amet, consect adipi scing elit, sed do eiusmod tempor incididunt ut sed do eiusmod tempor incididunt ut labore et dolore aliqua.</p> <h3>Consectetur adipiscing</h3> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sit amet, consect adipi scing elit, sed do eiusmod tempor incididunt ut sed do eiusmod tempor incididunt ut labore et dolore aliqua.</p> <h3>Consectetur adipiscing</h3> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sit amet, consect adipi scing elit, sed do eiusmod tempor incididunt ut sed do eiusmod tempor incididunt ut labore et dolore aliqua. You can&nbsp;<a href="#">watch as much as you want</a>, whenever you want, without a single ad Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sit amet, consect adipi scing elit, sed do eiusmod tempor incididunt ut sed do eiusmod tempor incididunt ut labore et dolore aliqua.</p> <h3>Consectetur adipiscing</h3> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sit amet, consect adipi scing elit, sed do eiusmod tempor incididunt ut sed do eiusmod tempor incididunt ut labore et dolore aliqua.</p><p>You can&nbsp;<a href="#">watch as much as you want</a>, whenever you want, without a single ad Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sit amet, consect adipi scing elit, sed do eiusmod tempor incididunt ut sed do eiusmod tempor incididunt ut labore et dolore aliqua.</p>'
        ]);

        $page=Post::create([     'status'=>1,'type'=>'page','urlkey'=>'privacy-policy','title'=>'Privacy Policy','template'=>1,
                'excerpt' => ''
        ]);

        $postContent = PostContent::create(['post_id' => $page->id,
            'content'=>'<h3>Consectetur adipiscing</h3> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sit amet, consect adipi scing elit, sed do eiusmod tempor incididunt ut sed do eiusmod tempor incididunt ut labore et dolore aliqua.</p><p>You can&nbsp;<a href="#">watch as much as you want</a>, whenever you want, without a single ad Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sit amet, consect adipi scing elit, sed do eiusmod tempor incididunt ut sed do eiusmod tempor incididunt ut labore et dolore aliqua.</p> <h3>Consectetur adipiscing</h3> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sit amet, consect adipi scing elit, sed do eiusmod tempor incididunt ut sed do eiusmod tempor incididunt ut labore et dolore aliqua.</p> <h3>Consectetur adipiscing</h3> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sit amet, consect adipi scing elit, sed do eiusmod tempor incididunt ut sed do eiusmod tempor incididunt ut labore et dolore aliqua. You can&nbsp;<a href="#">watch as much as you want</a>, whenever you want, without a single ad Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sit amet, consect adipi scing elit, sed do eiusmod tempor incididunt ut sed do eiusmod tempor incididunt ut labore et dolore aliqua.</p> <h3>Consectetur adipiscing</h3> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sit amet, consect adipi scing elit, sed do eiusmod tempor incididunt ut sed do eiusmod tempor incididunt ut labore et dolore aliqua.</p><p>You can&nbsp;<a href="#">watch as much as you want</a>, whenever you want, without a single ad Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sit amet, consect adipi scing elit, sed do eiusmod tempor incididunt ut sed do eiusmod tempor incididunt ut labore et dolore aliqua.</p>'
        ]);


        $page=Post::create([     'status'=>1,'type'=>'page','urlkey'=>'help','title'=>'Help','template'=>1,
                'excerpt' => ''
        ]);

        $postContent = PostContent::create(['post_id' => $page->id,
            'content'=>'<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sit amet, consect adipi scing elit, sed do eiusmod tempor incididunt ut sed do eiusmod tempor incididunt ut labore et dolore aliqua.</p><p>You can&nbsp;<a href="#">watch as much as you want</a>, whenever you want, without a single ad Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sit amet, consect adipi scing elit, sed do eiusmod tempor incididunt ut sed do eiusmod tempor incididunt ut labore et dolore aliqua.</p>'
        ]);


        $page=Post::create([     'status'=>1,'type'=>'page','urlkey'=>'pricing','title'=>'Choose Your Pricing Plan','template'=>1,
                'excerpt' => ''
        ]);

            // $postContent = PostContent::create(['post_id' => $page->id,
            //     'content'=>'<p>HD (720p), Full HD (1080p), Ultra HD (4K) and HDR availability subject to your internet service and device capabilities. Not all content is available in all resolutions. See our <a href="#">Terms of Use</a> for more details. Only people who live with you may use your account. Watch on 4 different devices at the same time with Premium, 2 with Standard, and 1 with Basic and Mobile.</p>'
            // ]);

            $meta = Meta::create(['meta_id' => $page->id,'type'=>1,'path'=>'enable_pricing',
                'value'=>'on'
            ]);
            $meta = Meta::create(['meta_id' => $page->id,'type'=>1,'path'=>'pricing_subtitle',
                'value'=>'Prcing Plan'
            ]);
            $meta = Meta::create(['meta_id' => $page->id,'type'=>1,'path'=>'pricing_title',
                'value'=>"Explore our range of plans to find the perfect fit for you."
            ]);
            $meta = Meta::create(['meta_id' => $page->id,'type'=>1,'path'=>'pricing_content',
                'value'=>'<p>HD (720p), Full HD (1080p), Ultra HD (4K) and HDR availability subject to your internet service and device capabilities. Not all content is available in all resolutions. See our <a href="#">Terms of Use</a> for more details. Only people who live with you may use your account. Watch on 4 different devices at the same time with Premium, 2 with Standard, and 1 with Basic and Mobile.</p>'
            ]);


        $img='image';$$img = Media::create(['filename'=>$img.'.jpg','title'=>$img,
                    'urlkey'=>'img/sample/auth_bg.jpg'
        ]);

        $page=Post::create([     'status'=>1,'type'=>'page','urlkey'=>'page-not-found','title'=>'404 | Page not found',
                'excerpt' => '<p>Oops! That page can&rsquo;t be found.<br />Nothing was found at this location.</p>','image_id'              => $image->id,
        ]);



        $page=Post::create([     'status'=>1,'type'=>'page','urlkey'=>'refer','title'=>'Refer a Friend','template'=>1,
                'excerpt' => ''
        ]);


            $postContent = PostContent::create(['post_id' => $page->id,
                'content'=>'<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sit amet, consect adipi scing elit, sed do eiusmod tempor incididunt ut sed do eiusmod tempor incididunt ut labore et dolore aliqua.</p>'
            ]);

            $meta = Meta::create(['meta_id' => $page->id,'type'=>1,'path'=>'subtitle',
                'value'=>'Earn credit points'
            ]);

            $meta = Meta::create(['meta_id' => $page->id,'type'=>1,'path'=>'banner_background_urlkey',
                'value'=>'img/sample/auth_bg.jpg'
            ]);

        // $page=Post::create([     'status'=>1,'type'=>'page','urlkey'=>'login','title'=>'Login',
        //         'excerpt' => ''
        // ]);        
        // $page=Post::create([     'status'=>1,'type'=>'page','urlkey'=>'register','title'=>'Register',
        //         'excerpt' => ''
        // ]);



        // foreach ($Items as $Item) {
        //     $newItem = Post::where('urlkey', '=', $Item['urlkey'])->first();
        //     if ($newItem === null) {
        //         $newItem = Post::create([
        //             'status'       => $Item['status'],
        //             'type'         => $Item['type'],
        //             'urlkey'       => $Item['urlkey'],
        //             'title'        => $Item['title'],
        //             'excerpt'      => $Item['excerpt']                
        //         ]);
        //     }
        // }
    }
}
