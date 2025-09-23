@if(Session::has('profileToken'))
  <?php
    $profileid=Session::get('profileToken');
    $prf='';
    if(!empty($profileid)){
        $profile=App\Models\UsersProfile::getData($profileid);
        if($profile){
            $profileList=App\Models\UsersProfile::getList();

         //dd($profile);
            $pmenu=array();
            $pItem=array();
            $pItem['id']=$profile->id;$pItem['active']=1; $pItem['name']=$profile->name;$pItem['havepin']=0;
            $profileImg=$pItem['thumbnail']=App\Models\UsersProfile::getImage($profile);    
            $pmenu[]=$pItem;
            foreach($profileList as $item){
                if($profile->id==$item->id){
                    continue;
                }
                $pItem=array();
                $pItem['id']=$item->id;$pItem['active']=0;    $pItem['name']=$item->name;$pItem['havepin']=empty($item->pin)?0:1;
                $pItem['thumbnail']=App\Models\UsersProfile::getImage($item);        
                $pmenu[]=$pItem;
            }

            $prf.='<div class="quote-icon pfMenu" data-ischild="'.$profile->is_child.'" data-autoplay="'.$profile->autoplay.'">';
                $iconimg=empty($profileImg)?'':' style="background-image:url('.Lib::img($profileImg).')" ';
                $prf.='<div class="icon" '.$iconimg.'>'.$profile->name.'</div>';
                $prf.='<div class="list"><div class="in">';

                    if(!empty($menu)):
                        foreach($pmenu as $item){
                            $iconimg=empty($item['thumbnail'])?'':' style="background-image:url('.Lib::img($item['thumbnail']).')" ';
                            $havepin=empty($item['havepin'])?'':' locked ';
                            $prf.='<div class="item profile '.$havepin.' active-'.$item['active'].'" data-id='.$item['id'].'>
                                        <div class="icon" '.$iconimg.'></div><div class="text">'.$item['name'].'</div>
                                    </div>';
                        }
                        $prf.='<div class="item manage"><div class="icon"></div><div class="text">Manage profile</div></div>';
                    else:
                        $prf.='<div class="item backarrow"><a href="'.url('/browse').'"><div class="icon"></div><div class="text">'.env('APP_NAME').'</div></a></div>';
                        $prf.='<div class="item manage"><a href="'.url('/browse?manageprofile=1').'"><div class="icon"></div><div class="text">Manage profile</div></a></div>';
                    endif;


                    $prf.='<div class="item account"><a href="'.url('/my-account').'"><div class="icon"></div><div class="text">Account</div></a></div>';
                    if (auth()->user()->isAdmin()):
                        $prf.='<div class="item account"><a href="'.url('/backend').'"><div class="icon"></div><div class="text">Backend</div></a></div>';
                    endif;
                    $prf.='<div class="item help"><a href="'.url('/help').'"><div class="icon"></div><div class="text">Help Centre</div></a></div>';
                    $prf.='<div class="item signout"><form id="logoutForm"><button type="submit">Logout</button></form></div>';

                $prf.='</div></div>';
            $prf.='</div>';
        }else{
            Session::forget('profileToken');?>
            <script type="text/javascript">
                window.location.reload();
            </script>
            <?php
        }
    //dd($pmenu);
    }

  ?>
@endif
<?php
if(empty($prf)){
    echo '<form id="logoutForm"><button class="edu-btn btn-small left-icon header-button" type="submit">Logout</button></form>';
}else{
    echo $prf;
}
?>


<?php /*
@if (auth()->user()->isAdmin())
  <!-- <a class="edu-btn btn-medium left-icon header-button" href="{{ route('admin.myaccount.index') }}">Account</a> -->
  <!-- <a class="edu-btn btn-medium left-icon header-button" href="{{ route('logout') }}"><i class="ri-user-line" onclick="event.preventDefault();document.getElementById('logout-form').submit();" ></i>Logout</a><form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form> -->
@else


  <!-- <a class="edu-btn btn-medium left-icon header-button" href="{{ url('/my-account') }}"></i>Account</a> -->
  <!-- <a class="edu-btn btn-medium left-icon header-button" href="{{ route('logout') }}"><i class="ri-user-line" onclick="event.preventDefault();document.getElementById('logout-form').submit();" ></i>Logout</a><form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form> -->
@endif
*/?>