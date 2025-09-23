@if ($paginator->hasPages())
<div class='sctPaginationOut clearfix'><div class='sctPagination clearfix'>
    
    <div class="pageitems">
        <?php /* 
        <div class="arrowsonly">
            @if ($paginator->onFirstPage())
                <!-- <span class="prev page-numbers" aria-hidden="true">
                    {!! __('pagination.previous') !!}
                </span> -->
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="prev page-numbers">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="next page-numbers">
                    {!! __('pagination.next') !!}
                </a>
            @else
               <!--  <span class="next page-numbers" aria-hidden="true">
                    {!! __('pagination.next') !!}
                </span> -->
            @endif
        </div>
        */ ?>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">

        <?php /* 
            <span class='page-numbers page-num'>Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}</span>
        */ ?>

            <ul class="pagination edu-pagination">

                    @if ($paginator->onFirstPage())
                        <!-- <span aria-disabled="true" class="prev page-numbers" aria-label="{{ __('pagination.previous') }}">«</span> -->
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}"><i class="ri-arrow-drop-left-line iconG icon-arrow-double-left"></i></a></li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li class="page-item"><a href="#">{{ $element }}</a></li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="page-item active"><a class="page-link" href="#">{{ $page }}</a></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}"><i class="ri-arrow-drop-right-line iconG icon-arrow-double-right"></i></a></li>
                    @else
                        <!-- <span aria-disabled="true" class="next page-numbers" aria-label="{{ __('pagination.next') }}">»</span> -->
                    @endif
            </ul>


        </div>
    </div>
</div></div>
@endif
