@extends('admin.layouts.master')

@section('content')

@if(isset($questionDetail))
    {{ Form::open(['route' => 'admin.questions.updateQuestion', 'method' => 'post', 'class'=>'needs-validation']) }}
    <input type="hidden" name="question_id" value="{{$questionDetail->id}}">
@else
    {{ Form::open(['route' => 'admin.questions.saveQuestion', 'method' => 'post', 'class'=>'needs-validation']) }}
@endif
    @csrf
@include('admin.common.message')
    <div class="small-grid">
        <h2 class="pb-2 border-bottom a-center">Create New Question</h2>
        <input type="hidden" name="movie_id" value="{{$movieId}}"/>
        <input type="hidden" name="page" value="{{ $page }}">
        <div class="form-row">
            <label for="language">Question Language</label>

            <select name="language" id="language" class="form-control" required>
                <option value="">Select Language</option>

                <option value="english"
                    {{ old('language', $questionDetail->language ?? '') == 'english' ? 'selected' : '' }}>
                    English
                </option>

                <option value="tamil"
                    {{ old('language', $questionDetail->language ?? '') == 'tamil' ? 'selected' : '' }}>
                    Tamil
                </option>
            </select>

            @error('language')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-row">
            <label for="question">Question</label>
            <input type="text" class="form-control" id="question_name" name="question_name" 
            value="{{ (isset($questionDetail))?$questionDetail->question_name:old('question_name') }}" required>
            @error('question_name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-row">
            <label>Question Options</label>
            <div id="options-container">
                @php
                    if(isset($questionDetail)){
                        $oldOptions = $questionDetail->options ?? [null];
                    }
                    else{
                        $oldOptions = old('options') ?? [null];
                    }
                @endphp

                @foreach($oldOptions as $index => $option)
                    <div class="option-row">
                        @if(isset($option['id']))
                            <input type="hidden" name="option_ids[{{ $index }}]" value="{{ $option['id'] }}">
                        @endif
                        <input type="text" class="option-input" placeholder="Enter option" name="options[{{ $index }}]" value="{{ $option['name']??$option }}" required>
                        
                        <label class="radio-label">

                            @if(isset($option['id']))
                                <input type="radio" name="correct_option" value="{{ $index }}"
                {{ old('correct_option', $option['is_correct']) == 1 ? 'checked' : '' }}> Correct
                            @else
                                <input type="radio" name="correct_option" value="{{ $index }}" {{ old('correct_option') == $index ? 'checked' : '' }}> Correct
                            @endif
                        </label>

                        @error("options.$index")
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach
            </div>

            @error('options')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            @error('correct_option')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <button type="button" class="add-button" id="add-option">ADD</button>
        </div>
        <div class="form-row">
            <label for="show_time">Quiz Showing Time</label>
            <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap; margin-top: 5px;">
                <div style="display: flex; flex-direction: column; align-items: center;">
                    <label for="hours">Hours</label>
                    <input type="number" name="show_time_hour" min="0" class="form-control" placeholder="Hours" value="{{ old('show_time_hour', $questionDetail->show_time_hour ?? 0) }}">
                </div>

                <div style="display: flex; flex-direction: column; align-items: center;">
                    <label for="hours">Minutes</label>
                    <input type="number" name="show_time_min" min="0" class="form-control" placeholder="Minutes" value="{{ old('show_time_min', $questionDetail->show_time_min ?? 0) }}">
                </div>

                <div style="display: flex; flex-direction: column; align-items: center;">
                    <label for="hours">Seconds</label>
                    <input type="number" name="show_time_sec" min="0" max="59" class="form-control" placeholder="Seconds" value="{{ old('show_time_sec', $questionDetail->show_time_sec ?? 0) }}">
                </div>
            </div>
            <div style="display: flex; gap: 10px;">
                <!-- <input type="number" name="show_time_hour" min="0" class="form-control" placeholder="Hours" 
               value="{{ old('show_time_hour', isset($questionDetail) ? floor($questionDetail->show_time / 3600) : '') }}" required>

                <input type="number" name="show_time_min" min="0" class="form-control" placeholder="Minutes" 
                       value="{{ old('show_time_min', isset($questionDetail) ? floor($questionDetail->show_time / 60) : '') }}" required>

                <input type="number" name="show_time_sec" min="0" max="59" class="form-control" placeholder="Seconds" 
                       value="{{ old('show_time_sec', isset($questionDetail) ? $questionDetail->show_time % 60 : '') }}" required> -->
                

            </div>

            @error('show_time_hour')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('show_time_min')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('show_time_sec')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- <div class="form-row">
            <label for="description">Question Description</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        </div> -->

        <div class="form-row a-center">
            <input type="submit" class="btn btn-primary btn-lg" value="Continue"/> 
            &nbsp; 
            <a href="{{ route('admin.questions.list', ['movieId' => $movieId, 'page' => $page]) }}" class="btn btn-secondary btn-lg backbtn">Back</a>
        </div>
    </div>
{{ Form::close() }}

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    // Add new option
    $('#add-option').click(function (e) {
        e.preventDefault();
        let count = $('.option-row').length;

        if (count >= 4) {
            alert("You can add only 4 options.");
            return;

            /*showGlobalError("You can add only 4 options.");
            return;*/
        }

        const index = count;
        const newRow = `
            <div class="option-row">
                <input type="text" class="option-input" required placeholder="Enter option" name="options[${index}]">
                <label class="radio-label">
                    <input type="radio" name="correct_option" value="${index}"> Correct
                </label>
            </div>`;
        $('#options-container').append(newRow);
    });

    // Delete row
    /*$('#options-container').on('click', '.delete-button', function () {
        $(this).closest('.option-row').remove();
    });*/

    // Replace alert with inline error messages
    $('form').submit(function (e) {
        // e.preventDefault();
        // Clear previous errors
        $('.validation-error').remove();

        let hasError = false;
        const optionInputs = $('input[name^="options"]');
        const correctSelected = $('input[name="correct_option"]:checked').length > 0;
        const checkBoolean = [];
        // Check empty textboxes
        optionInputs.each(function () {
            if ($(this).val().trim() === '') {
                const errorDiv = $('<div class="validation-error alert alert-danger">This field is required.</div>');
                $(this).after(errorDiv);
                hasError = true;
            }
        });

        /*correctSelected.each(function(index,value){
            if($(this).is(':checked')){
                $('.checkBoolean').val(index);
            }
        });*/
        

        // Check minimum option count
        if (optionInputs.length < 4) {
            showGlobalError("You must add 4 options.");
            hasError = true;
        }

        // Check correct option selected
        if (!correctSelected) {
            const radioGroup = $('input[name="correct_option"]').last();
            const errorDiv = $('<div class="validation-error alert alert-danger">Please select the correct option.</div>');
            radioGroup.closest('.option-row').append(errorDiv);
            hasError = true;
        }

        if (hasError) {
            e.preventDefault(); // Prevent submit
        }
    });

    // Show top-level form error (optional)
    function showGlobalError(message) {
        const globalError = $('<div class="validation-error alert alert-danger">' + message + '</div>');
        $('#options-container').before(globalError);
    }
});
</script>

@endsection
