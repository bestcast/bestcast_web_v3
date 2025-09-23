@extends('admin.layouts.master')


@section('content')


    @include('admin.common.message')

    {{ Form::model($meta, ['route' => ['admin.paymentgateway.save',1], 'method' => 'post']) }}
    <!-- 1 is mobile app -->
    <h2 class="pb-3 border-bottom">
        Payment Gateway <button type="submit" class="btn btn-primary float-right">Update</button>
    </h2>

    
    <div class="image txt-right">
        <div class="row">
            <h3>Razorpay</h3>
            <div class="col-7">
                <div class="form-row">
                      <label class="form-label">Enable?</label>
                      <div class="mb-3 form-check form-switch">
                        {{Form::hidden('meta[razorpay_enable]',0)}}
                        <input class="form-check-input" type="checkbox" name="meta[razorpay_enable]" role="switch" @if(old('razorpay_enable',(empty($meta['razorpay_enable'])?0:1))) checked="" @endif>
                      </div>
                </div>
            </div>
            <div class="col-7">
                <div class="form-row">
                  <label class="form-label"for="name">Merchant ID</label>
                  <input type="text" class="form-control" name="meta[razorpay_merchantid]" value="{{ old('meta.appid',(empty($meta['razorpay_merchantid'])?'':$meta['razorpay_merchantid']))  }}" >
                </div>
            </div>
            <div class="col-7 dnn">
                <div class="form-row">
                      <label class="form-label">Enable Auto Subscription?</label>
                      
                      <div class="mb-3 form-check form-switch">
                        {{Form::hidden('meta[razorpay_auto_subscription]',0)}}
                        <input class="form-check-input" type="checkbox" name="meta[razorpay_auto_subscription]" role="switch" @if(old('razorpay_auto_subscription',(empty($meta['razorpay_auto_subscription'])?0:1))) checked="" @endif><p class="comment">Enable for Auto Subscription to active.</p>
                      </div>
                </div>
            </div>
            <div class="col-7">
                <div class="form-row">
                      <label class="form-label">is Live Mode?</label>
                      
                      <div class="mb-3 form-check form-switch">
                        {{Form::hidden('meta[razorpay_mode]',0)}}
                        <input class="form-check-input" type="checkbox" name="meta[razorpay_mode]" role="switch" @if(old('razorpay_mode',(empty($meta['razorpay_mode'])?0:1))) checked="" @endif><p class="comment">Enable for Live mode, Disable for Test mode</p>
                      </div>
                </div>
            </div>
            <div class="col-7">
                <div class="form-row">
                  <label class="form-label"for="name">Test Key</label>
                  <input type="text" class="form-control" name="meta[razorpay_test_key]" value="{{ old('meta.razorpay_test_key',(empty($meta['razorpay_test_key'])?'':$meta['razorpay_test_key']))  }}" >
                </div>
            </div>
            <div class="col-7">
                <div class="form-row">
                  <label class="form-label"for="name">Test Secret</label>
                  <input type="password" class="form-control" name="meta[razorpay_test_secret]" value="{{ old('meta.razorpay_test_secret',(empty($meta['razorpay_test_secret'])?'':$meta['razorpay_test_secret']))  }}" >
                </div>
            </div>
            <div class="col-7">
                <div class="form-row">
                  <label class="form-label"for="name">Live Key</label>
                  <input type="text" class="form-control" name="meta[razorpay_live_key]" value="{{ old('meta.razorpay_live_key',(empty($meta['razorpay_live_key'])?'':$meta['razorpay_live_key']))  }}" >
                </div>
            </div>
            <div class="col-7">
                <div class="form-row">
                  <label class="form-label"for="name">Live Secret</label>
                  <input type="password" class="form-control" name="meta[razorpay_live_secret]" value="{{ old('meta.razorpay_live_secret',(empty($meta['razorpay_live_secret'])?'':$meta['razorpay_live_secret']))  }}" >
                </div>
            </div>
            <div class="col-7">
                <div class="form-row">
                  <label class="form-label"for="name">Company Name</label>
                  <input type="text" class="form-control" name="meta[razorpay_company_name]" value="{{ old('meta.razorpay_company_name',(empty($meta['razorpay_company_name'])?env('APP_NAME'):$meta['razorpay_company_name']))  }}" >
                </div>
            </div>
            <div class="row">
            <div class="form-row">
                <label class="form-label">Payment Logo</label>
                {!! Field::galleryUpload('razorpay_logo','Logo',$meta) !!}
                <p class="comment">Recommended size 100X100</p>
            </div>
            </div>
            <div class="col-7">
                <div class="form-row">
                  <label class="form-label"for="name">Color Code</label>
                  <input type="text" class="form-control" name="meta[razorpay_colorcode]" value="{{ old('meta.razorpay_colorcode',(empty($meta['razorpay_colorcode'])?'#cc1e24':$meta['razorpay_colorcode']))  }}" >
                  <p class="comment">eg: #cc1e24</p>
                </div>
            </div>


            <h3>BuyPlan Page Content</h3>
            <div class="col-7">
                <div class="form-row">
                  <label class="form-label"for="name">Title</label>
                  <input type="text" class="form-control" name="meta[buyplan_title]" value="{{ old('meta.buyplan_title',(empty($meta['buyplan_title'])?'':$meta['buyplan_title']))  }}" >
                </div>
            </div>
            <div class="col-7">
                <div class="form-row">
                  <label class="form-label"for="name">Content</label>
                  <textarea class="form-control editor-buyplan_content" name="meta[buyplan_content]">{{ old('meta.buyplan_content',empty($meta['buyplan_content'])?'':$meta['buyplan_content']) }}</textarea>
                  {!! Field::editor('editor-buyplan_content') !!}
                </div>
            </div>


            <h3>Payment Status Page Content</h3>
            <div class="col-7">
                <div class="form-row">
                  <label class="form-label"for="name">Title</label>
                  <input type="text" class="form-control" name="meta[paymentstatus_title]" value="{{ old('meta.paymentstatus_title',(empty($meta['paymentstatus_title'])?'':$meta['paymentstatus_title']))  }}" >
                </div>
            </div>
            <div class="col-7">
                <div class="form-row">
                  <label class="form-label"for="name">Content</label>
                  <textarea class="form-control editor-paymentstatus_content" name="meta[paymentstatus_content]">{{ old('meta.paymentstatus_content',empty($meta['paymentstatus_content'])?'':$meta['paymentstatus_content']) }}</textarea>
                  {!! Field::editor('editor-paymentstatus_content') !!}
                </div>
            </div>
            <div class="col-7">
                <div class="form-row">
                  <label class="form-label"for="name">Warning Message</label>
                  <input type="text" class="form-control" name="meta[paymentstatus_warning]" value="{{ old('meta.paymentstatus_warning',(empty($meta['paymentstatus_warning'])?'':$meta['paymentstatus_warning']))  }}" >
                </div>
            </div>
            <div class="col-7">
                <div class="form-row">
                  <label class="form-label"for="name">Success Message</label>
                  <input type="text" class="form-control" name="meta[paymentstatus_success]" value="{{ old('meta.paymentstatus_success',(empty($meta['paymentstatus_success'])?'':$meta['paymentstatus_success']))  }}" >
                </div>
            </div>
            <div class="col-7">
                <div class="form-row">
                  <label class="form-label"for="name">Error Message</label>
                  <input type="text" class="form-control" name="meta[paymentstatus_error]" value="{{ old('meta.paymentstatus_error',(empty($meta['paymentstatus_error'])?'':$meta['paymentstatus_error']))  }}" >
                </div>
            </div>

        </div>
    </div>

      <div class="form-row col-md-12">
          <button type="submit" class="btn btn-primary">Update ALL</button>
      </div>

    </form>

@endsection



