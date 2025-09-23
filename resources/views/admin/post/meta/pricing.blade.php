
<div class="container-fluid">

  <h3 class="pb-2 border-bottom mb-4 mt-5">Pricing Settings</h3>
  <div class="form-row">
      <label class="form-label">Enable?</label>
      <div class="mb-3 form-check form-switch">
        {{Form::hidden('meta[enable_pricing]',0)}}
        <input class="form-check-input" type="checkbox" name="meta[enable_pricing]" role="switch" @if(old('enable_pricing',(empty($meta['enable_pricing'])?0:1))) checked="" @endif>
      </div>
  </div>
  <div class="form-row">
      <label class="form-label">Subtitle</label>
      <input type="text" class="form-control" name="meta[pricing_subtitle]" value="{{ old('meta.pricing_subtitle',(empty($meta['pricing_subtitle'])?'':$meta['pricing_subtitle'])) }}">
  </div>
  <div class="form-row">
      <label class="form-label">title</label>
      <input type="text" class="form-control" name="meta[pricing_title]" value="{{ old('meta.pricing_title',(empty($meta['pricing_title'])?'':$meta['pricing_title'])) }}">
  </div>
  <div class="form-row">
      <label class="form-label">Bottom Content</label>
      <textarea class="form-control editor-pricing_content" name="meta[pricing_content]">{{ old('meta.pricing_content',empty($meta['pricing_content'])?'':$meta['pricing_content']) }}</textarea>
      {!! Field::editor('editor-pricing_content') !!}
  </div>

</div>