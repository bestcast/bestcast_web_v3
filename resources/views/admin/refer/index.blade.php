@extends('admin.layouts.master')


@section('content')


    @include('admin.common.message')

    {{ Form::model($meta, ['route' => ['admin.refer.save',1], 'method' => 'post']) }}
    <!-- 1 is mobile app -->
    <h2 class="pb-3 border-bottom">
        Referral Program <button type="submit" class="btn btn-primary float-right">Update</button>
    </h2>

    @if(!empty($referlist->total()))
            <div class="txtcard image">
                <div class="row"><div class="col-12">
                <table  class="table">
                  <tr class="header">
                    <td>Email Address</td>
                    <td>Code</td>
                    <td>Credits Used</td>
                    <td>Action</td>
                  </tr>
                    @foreach($referlist->items() as $item)
                      <tr>
                        <td>{{ $item->email }}</td>
                        <td>{{ empty($item->referal_code)?'':$item->referal_code }}</td>
                        <td>{{ empty($item->credits_used)?0:$item->credits_used }}</td>
                        <td><a href="{{ route('admin.user.edit',$item->id) }}" class="btn btn-primary btn-sm">View Details</a></td>
                      </tr>
                    @endforeach
                </table>
                </div>
            </div></div>
            <div class="d-flex justify-content-center mt-5 paginationCt">
                <div class="d-flex">
                    {{ $referlist->onEachSide(1)->links() }}
                </div>
            </div>
    @endif

                            

    <div class="image txt-right">
        <div class="row">
            <h3>Settings & Content</h3>
            <div class="col-7">
                <div class="form-row">
                  <label class="form-label"for="name">Title</label>
                  <input type="text" class="form-control" name="meta[refer_title]" value="{{ old('meta.refer_title',(empty($meta['refer_title'])?'':$meta['refer_title']))  }}" >
                </div>
            </div>
            <div class="col-7">
                <div class="form-row">
                  <label class="form-label"for="name">Credits for each referal</label>
                  <input type="text" class="form-control" name="meta[refer_credits]" value="{{ old('meta.refer_credits',(empty($meta['refer_credits'])?'':$meta['refer_credits']))  }}" >
                </div>
            </div>
            <div class="row">
            <div class="form-row">
                <label class="form-label">Banner</label>
                {!! Field::galleryUpload('refer_banner','Banner',$meta) !!}
                <p class="comment">Recommended size 1200X400</p>
            </div>
            </div>
            <div class="col-7">
                <div class="form-row">
                  <label class="form-label"for="name">Content</label>
                  <textarea class="form-control editor-refer_content" name="meta[refer_content]">{{ old('meta.refer_content',empty($meta['refer_content'])?'':$meta['refer_content']) }}</textarea>
                  {!! Field::editor('editor-refer_content') !!}
                </div>
            </div>



            <h3>Instruction</h3>
            <div class="col-7">
                <div class="form-row">
                  <label class="form-label"for="name">Content</label>
                  <textarea class="form-control editor-refer_instruction" name="meta[refer_instruction]">{{ old('meta.refer_instruction',empty($meta['refer_instruction'])?'':$meta['refer_instruction']) }}</textarea>
                  {!! Field::editor('editor-refer_instruction') !!}
                </div>
            </div>



        </div>
    </div>

      <div class="form-row col-md-12">
          <button type="submit" class="btn btn-primary">Update ALL</button>
      </div>

    </form>

@endsection



