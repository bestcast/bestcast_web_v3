@if(!empty($review) && !empty($review['total']))
    <div class="course-tab-content course-details-content reviewbox">
        <div class="row row--30">
            <div class="col-lg-4">
                <div class="rating-box">
                    <div class="rating-number">{{ $review['overall'] }}.0</div>
                    <div class="rating">
                        <i class="icon-Star"></i>
                        <i class="icon-Star"></i>
                        <i class="icon-Star"></i>
                        <i class="icon-Star"></i>
                        <i class="icon-Star"></i>
                    </div>
                    <span>({{ $review['total'] }} Reviews)</span>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="review-wrapper">

                    <div class="single-progress-bar">
                        <div class="rating-text">
                            5 <i class="icon-Star"></i>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: {{ ($review[5]/$review['total'])*100 }}%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span class="rating-value">{{ $review[5] }}</span>
                    </div>

                    <div class="single-progress-bar">
                        <div class="rating-text">
                            4 <i class="icon-Star"></i>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: {{ ($review[4]/$review['total'])*100 }}%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span class="rating-value">{{ $review[4] }}</span>
                    </div>

                    <div class="single-progress-bar">
                        <div class="rating-text">
                            3 <i class="icon-Star"></i>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: {{ ($review[3]/$review['total'])*100 }}%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span class="rating-value">{{ $review[3] }}</span>
                    </div>

                    <div class="single-progress-bar">
                        <div class="rating-text">
                            2 <i class="icon-Star"></i>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: {{ ($review[2]/$review['total'])*100 }}%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span class="rating-value">{{ $review[2] }}</span>
                    </div>

                    <div class="single-progress-bar">
                        <div class="rating-text">
                            1 <i class="icon-Star"></i>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: {{ ($review[1]/$review['total'])*100 }}%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span class="rating-value">{{ $review[1] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif