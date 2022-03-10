@if ($paginator->hasPages()) 
        @if ($paginator->onFirstPage())
            <span class="btn btn-secondary disabled">← Previous</span>
        @else
            <a class="btn btn-primary" href="{{ $paginator->previousPageUrl() }}">← Previous</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="btn btn-secondary disabled">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="btn disabled">{{ $page }}</span>
                    @else
                        <a class="btn btn-primary" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a class="btn btn-primary" href="{{ $paginator->nextPageUrl() }}" rel="next">Next →</a>
        @else
            <span class="btn btn-secondary disabled">Next →</span>
        @endif
@endif 