@if(!empty($review))
    <div class="edu-rating rating-default">
        <div class="rating">
            <i class="icon-Star"></i>
            @if(!empty($review['total']))
                <i class="icon-Star"></i>
                <i class="icon-Star"></i>
                <i class="icon-Star"></i>
                <i class="icon-Star"></i>
            @endif
        </div>
        <span class="rating-count">
            @if(empty($review['total'])) 
                (Be first to Review) 
            @else 
                ({{ $review['total'] }} Reviews) 
            @endif
        </span>
    </div>
@endif