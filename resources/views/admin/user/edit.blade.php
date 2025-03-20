@extends('admin.layouts.master')


@section('content')


{{ Form::model($model, ['route' => ['admin.user.editsave', $model->id], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
  <div class="row">
    <div class="col-md-8">
        <div class="container">
            @include('admin.common.message')
            <h2 class="pb-2 border-bottom">Edit: {!! $model->email  !!} </h2>

            <div class="form-group row mb-5">
                <div class="col-sm-8">
                    <label class="form-label">Profile Photo</label>
                    <div class="fileblock">
                        <div class="comment">Allowed file format: png, jpg, jpeg, gif. Maximum file size 2MB<br>Better Resolution: 500X500</div>
                        <input type="file" class="form-control" id="photo_file" name="photo_file"  accept="image/*">
                     </div>
                    <div class="mt-3">
                        @if(!empty($model->roles))
                            @foreach($model->roles as $role)
                                <span class="badge bg-info text-dark">Role: {{ $role->name }}</span>
                            @endforeach
                        @else
                            <span class="badge bg-info text-dark">No Role Applied</span>
                        @endif
                        @if($model->email_verified_at)
                            <span class="badge bg-success text-light">Email Verified</span>
                        @else
                            <span class="badge bg-danger text-light">Email Not Verified</span>
                        @endif
                        @if($model->phone_verified_at)
                            <span class="badge bg-success text-light">Mobile Verified</span>
                        @else
                            <span class="badge bg-danger text-light">Mobile Not Verified</span>
                        @endif
                    </div>
                </div>
                <div class="col-sm-4">
                  <div class="profile_photo" @if(!empty($model->photo)) style="background-image:url('{{ Lib::publicImgSrc($model->photo) }}')" @endif>
                  </div>
                </div>
            </div>


            <div class="form-group row">
                <div class="col-sm-3">
                    <div class="form-row">
                        <label class="form-label">Title</label>
                        @php ($val=old('title',$model->title))
                        <select class="form-select selectbox-default" name="title">
                          @php ($ls=Field::prefixtitle())
                          @foreach($ls as $item)
                            <option value="{{ $item }}" @if($val==$item) selected="selected" @endif>{{ $item }}</option>   
                          @endforeach 
                        </select>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="form-row">
                        <label class="form-label" for="firstname">First Name<em>*</em></label>
                        <input type="text" class="form-control" id="firstname" name="firstname" value="{{ old('firstname',$model->firstname) }}" >
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-row">
                        <label class="form-label" for="lastname">Last Name</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" value="{{ old('lastname',$model->lastname) }}" >
                    </div>
                </div>
            </div>


            <div class="form-group row">
                <div class="col-sm-3">
                    <div class="form-row">
                        <label class="form-label">Gender</label>
                        @php ($val=old('gender',$model->gender))
                        <select class="form-select selectbox-default" name="gender">
                          @php ($ls=array(""=>"Select","Female"=>"Female","Male"=>"Male","Other"=>"Others"))
                          @foreach($ls as $key=>$item)
                            <option value="{{ $key }}" @if($val==$key) selected="selected" @endif>{{ $item }}</option>   
                          @endforeach 
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-row">
                        <label class="form-label">Date of birth<em>*</em></label>
                        <input type="text" class="form-control datepicker" name="dob" value="{{ old('dob',(empty($model->dob)?'':Lib::dateFormat($model->dob,'Y-m-d','d/m/Y'))) }}" placeholder="dd/mm/yyyy">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-row">
                        @php ($designation=(empty($meta['designation'])?'':$meta['designation']))
                        <label class="form-label"for="name">Designation</label>
                        <input type="text" min="0" max="10" class="form-control" name="meta[designation]" value="{{ old('meta.designation',$designation) }}" >
                    </div>
                </div>
            </div>

            <div class="form-row">
                <label class="form-label">Content</label>
                @php ($content=(empty($meta['content'])?'':$meta['content']))
                <textarea class="form-control editor-full" name="meta[content]" rows="5">{{ old('meta.content',$content) }}</textarea>
                {!! Field::editor('editor-full') !!}
            </div>
            
            
            <div class="card mt-5">
                <div class="card-header">Social Media</div>
                <div class="card-body">

                    @php ($xurl=(empty($meta['xurl'])?'':$meta['xurl']))
                    <div class="form-row">
                        <label class="form-label" for="name">X Url</label>
                        <input type="text" min="0" max="10" class="form-control" name="meta[xurl]" value="{{ old('meta.xurl',$xurl) }}" >
                    </div>

                    @php ($facebook=(empty($meta['facebook'])?'':$meta['facebook']))
                    <div class="form-row">
                        <label class="form-label" for="name">Facebook Url</label>
                        <input type="text" min="0" max="10" class="form-control" name="meta[facebook]" value="{{ old('meta.facebook',$facebook) }}" >
                    </div>

                    @php ($instagram=(empty($meta['instagram'])?'':$meta['instagram']))
                    <div class="form-row">
                        <label class="form-label" for="name">Instagram Url</label>
                        <input type="text" min="0" max="10" class="form-control" name="meta[instagram]" value="{{ old('meta.instagram',$instagram) }}" >
                    </div>

                    @php ($linkedin=(empty($meta['linkedin'])?'':$meta['linkedin']))
                    <div class="form-row">
                        <label class="form-label" for="name">LinkedIn Url</label>
                        <input type="text" min="0" max="10" class="form-control" name="meta[linkedin]" value="{{ old('meta.linkedin',$linkedin) }}" >
                    </div>

                    @php ($youtubeurl=(empty($meta['youtubeurl'])?'':$meta['youtubeurl']))
                    <div class="form-row">
                        <label class="form-label" for="name">Youtube Url</label>
                        <input type="text" min="0" max="10" class="form-control" name="meta[youtubeurl]" value="{{ old('meta.youtubeurl',$youtubeurl) }}" >
                    </div>

                </div>
            </div>

            <div class="box mt-3">
                  @php ($gallery_count=(empty($meta['gallery_count'])?0:$meta['gallery_count']))
                  <div class="form-row">
                      <label class="form-label" for="name">Number of Gallery Images</label>
                      <input type="number" min="0" max="10" class="form-control form-number" name="meta[gallery_count]" value="{{ old('meta.gallery_count',$gallery_count) }}" >
                  </div>
                  <ul class="gallerylist">
                    @for($no=1;$no<=$gallery_count;$no++)
                      <li>
                          {!! Field::galleryUpload('gallery_id_'.$no,'Image',$meta) !!}
                      </li>
                    @endfor
                  </ul>
            </div>

            @php($refer=App\Models\Meta::refer(1,true))
            @if(!empty($refer['refer_credits']))
                @php($list=App\User::getReferralData($model))
                @if(!empty($list) && count($list))
                <div class="box mt-5">
                    <div class="credits"><div class="in fix">
                            <div class="points fix">
                                <div class="available">Available Credits: <span>{{ App\User::getReferralCreditsTotal($model) }}</span></div>
                                <div class="used">Credits Used: <span>{{ App\User::getReferralCreditsUsed($model) }}</span></div>
                            </div>

                             
                                <table  class="table">
                                  <tr class="header">
                                    <td>Email Address</td>
                                    <td>Registered Date</td>
                                    <td>Subscription</td>
                                    <td>Credits</td>
                                  </tr>
                                  @foreach($list as $item)
                                  <tr>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ empty($item->created_at)?'':Lib::dateFormat($item->created_at,'','d M Y') }}</td>
                                    <td>{{ empty($item->plan)?'Not Yet':"Yes" }}</td>
                                    <td>{{ empty($item->plan)?'':$refer['refer_credits'] }}</td>
                                  </tr>
                                  @endforeach
                                </table>
                    </div></div>
                </div>
                @endif
            @endif

        </div>
    </div>

    <div class="col-md-4">
        <div class="container">
          <div class="card">
              <div class="card-header">Option</div>
              <div class="card-body">

                    <div class="form-row">
                        <label class="form-label" for="email">Email<em>*</em></label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email',$model->email) }}" >
                    </div>
                    <div class="form-row">
                        <label class="form-label" for="phone">Phone<em>*</em></label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone',$model->phone) }}" >
                    </div>
                    <div class="form-row">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" value="" >
                    </div>

                    <div class="form-row">
                        <label class="form-label" for="password_confirmation">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" value="" >
                    </div>


                    <div class="form-group row">
                        <div class="col-sm-6">
                          <div class="form-row">
                            <label for="excerpt" class="form-label">Email Verify?</label>
                            <div class="mb-3 form-check form-switch">
                              {{Form::hidden('email_verify',0)}}
                              <input class="form-check-input" type="checkbox" name="email_verify" role="switch" @if(old('email_verify' ,(empty($model->email_verified_at)?0:1))) checked="" @endif />
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-row">
                            <label for="excerpt" class="form-label">Mobile Verify?</label>
                            <div class="mb-3 form-check form-switch">
                              {{Form::hidden('phone_verify',0)}}
                              <input class="form-check-input" type="checkbox" name="phone_verify" role="switch" @if(old('phone_verify' ,(empty($model->phone_verified_at)?0:1))) checked="" @endif />
                            </div>
                          </div>
                        </div>
                    </div>


                    @if (auth()->user()->isAdmin())
                        <div class="form-row">
                          <label for="excerpt" class="form-label">Type</label>
                          {{ Form::select('type', App\User::type(), old('type',$model->type), array('class' => 'form-select')); }}
                        </div>
                    @endif

                  <div class="form-row col-md-12">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('admin.user.index') }}" class="btn btn-secondary backbtn">Back</a>
                  </div>
              </div>
              <div class="card-footer text-muted">
                @include('admin.common.userinfo') 
                <div class="deleteaction"><a data-bs-toggle="modal" data-bs-target="#delete{{ $model->id }}" class="btn btn-danger btn-delete-copy-{{ $model->id }}">Delete</a></div>
                @php ($delid=$model->id)
                @php ($delurl=route('admin.user.delete',$model->id))
                @include('admin.common.modaldelete')
              </div>

        </div>

        <div class="card mt-5">
            <div class="card-header">Subscription</div>
            <div class="card-body">

                <?php
                $trans=App\Models\Transaction::getActive($model);
                if(!empty($trans->razorpay_subscription_id)){
                    $razorResponse=App\Models\Transaction::updatePlanToUser($model,$trans->razorpay_subscription_id);
                }
                // if(!empty($trans->razorpay_order_id)){
                //     $razorResponse=App\Models\Transaction::updateOrderToUser($model,$trans->razorpay_order_id);
                // }
                ?>

                
                <div class="form-row">
                  <label for="excerpt" class="form-label">Choose Plan</label>
                  {{ Form::select('plan', App\Models\Subscription::planList(), old('plan',$model->plan), array('class' => 'form-select')); }}
                </div>
                <div class="form-row">
                    <label for="excerpt" class="form-label">Set Expiry Date</label>
                    <input type="text" class="form-control datepicker" name="plan_expiry" value="{{ old('plan_expiry',(empty($model->plan_expiry)?'':Lib::dateFormat($model->plan_expiry,'Y-m-d','d/m/Y'))) }}" placeholder="dd/mm/yyyy">
                </div>

                @if(isset($razorResponse) && !empty($razorResponse->charge_at) && !empty($trans->razorpay_subscription_id))
                    <div class="medium"><b>Razor Subscription ID:</b> {{ $trans->razorpay_subscription_id }}</div>
                    <div class="medium"><b>Next Payment:</b> {{ date("d M Y ",$razorResponse->charge_at) }}</div>
                    <div class="medium"><b>Payment Type:</b> {{ ucwords($razorResponse->payment_method) }}</div>
                    <div class="large"><b>Status:</b> <span class="badge @if($razorResponse->status=='active') bg-success @else bg-danger @endif text-light">{{ ucwords($razorResponse->status) }}</span>
                    </div>
                @endif
                @if(!empty($trans->razorpay_order_id))
                    <div class="medium"><b>Razor Order ID:</b> {{ $trans->razorpay_order_id }}</div>
                    <div class="medium"><b>Expiry:</b> {{ $model->plan_expiry }}</div>
                    <a href="{{ route('admin.transactions.index') }}?user_id={{ $model->id }}" class="btn btn-primary">Transaction Details</a>
                @endif
            </div>
        </div>


        <div class="card mt-5">
              <div class="card-header">Profiles</div>
              <div class="card-body">
              @php($usersProfile=App\Models\UsersProfile::getByUserId($model->id))
              @foreach($usersProfile as $list)
                <div class="profileBx">
                    <div class="profileicon_photo" 
                    @if($list->profileicon && $list->profileicon->thumbnail)
                        style="background-image:url('{{ Lib::publicImgSrc($list->profileicon->thumbnail->urlkey) }}')
                    @endif
                    "></div>
                    <div class="title">{{ $list->name }}</div>
                </div>
              @endforeach
            </div>
        </div>



        <div class="card mt-5">
            <div class="card-header">Refer Program</div>
            <div class="card-body">
                <div class="form-row">
                    <label class="form-label" for="phone">Referal Code</label>
                    <input type="text" readonly class="form-control" id="referal_code" name="referal_code" value="{{ old('referal_code',$model->referal_code) }}" style="background: #CCC;">
                    <p class="comment">{{ App\User::getReferralUrl($model) }}</p>
                </div>

                <div class="form-row">
                    <label class="form-label" for="phone">Referrer Code</label>
                    <input type="text"  class="form-control" id="refferer" name="refferer" value="{{ old('refferer',$model->refferer) }}">
                </div>
                <div class="form-row">
                    <label class="form-label" for="phone">Credits Used</label>
                    <input type="number" class="form-control" id="credits_used" name="credits_used" value="{{ old('credits_used',empty($model->credits_used)?0:$model->credits_used) }}" >
                </div>
            </div>
        </div>





        
    </div>
  </div>


{{ Form::close() }}
@endsection



