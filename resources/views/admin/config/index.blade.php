@extends('admin.layouts.master')


@section('content')

    @include('admin.common.message')
    <h2 class="pb-2 border-bottom">Settings</h2>


      <?php
        // $field=$configfield->filter(function ($item) {
        //   if($item->path=='general_website_googlefont'){return $item;}
        // })->first();

      //dd($config[23]->field);
      ?>


      {{ Form::model($config, ['route' => ['admin.config.save', $_GET], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}

        <?php 
        $htmlarr=array();
        foreach($config as $item){ 
          $html=empty($htmlarr[$item->field->group][$item->field->subgroup])?'':$htmlarr[$item->field->group][$item->field->subgroup];
          $html.='<div class="form-row '.$item->field->classname.'">
                    <label for="'.$item->path.'">'.$item->field->label.'</label>';
                    $html.=Field::data($item);
          $html.='</div>';
          $htmlarr[$item->field->group][$item->field->subgroup]=$html;
        }

        $html='';
          $i=0;foreach($htmlarr as $ik=>$iv){
            if($i){$html.='<hr>';}
            $html.='<h2>'.$ik.'</h2>';
            foreach($iv as $jk=>$jv){
              $html.='<div class="sub-title">'.$jk.'</div>';
              $html.=$jv;
            }
            $i++;
          }
        echo $html;

        ?>
        <div class="form-row">
          <div class="form-group">
              <button type="submit" class="btn btn-primary">Update</button>
          </div>
        </div>

      {{ Form::close() }}

      
@endsection



