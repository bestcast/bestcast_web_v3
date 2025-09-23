<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Mail;
use App\User;
use Field;
use Lib;
use Email;
use DB;

class DashboardController extends Controller
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

        //Email::test(array());


        // $to_name = 'test';
        // $to_email = 'kharykaran@gmail.com';
        // $data = array('name'=>'sender_name', 'body' => 'A test mail');
          
        // Mail::send('mail.contact',$data,function($message) use ($to_name, $to_email) {
        //     $message->to($to_email, $to_name)->subject('Laravel Test Mail');
        //     $message->from(env('MAIL_FROM_ADDRESS'),'Test Mail');
        // });

        //$user_count=User::get()->count();


        /*

        SELECT post.urlkey FROM `post` left join meta on meta.meta_id=post.id where meta.value=1 and post.type='blog' and post.status=1 and post.deleted_at is null;

        SELECT post.urlkey FROM `post` left join meta on meta.meta_id=post.id where meta.value=14 and post.type='blog' and post.status=1 and post.deleted_at is null;

        update `meta` set value=1 where path='author_id' and value=14 and meta_id >=125;

        */
        //$article_count = DB::select('SELECT post.urlkey FROM `post` left join meta on meta.meta_id=post.id where meta.value='.Auth::user()->id.' and post.type="blog" and post.status=1 and post.deleted_at is null');
        //$article_count = DB::select('SELECT post.urlkey FROM `post` where post.type="blog" and post.status=1');

        $array=array(
                //'user_count'=>$user_count,
                //'article_count'=>count($article_count),
                );
        return view('admin.dashboard.index', $array);
    }
}

