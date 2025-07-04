<ol class="breadcrumb float-sm-right">
    @foreach ($items as $i)
        <li class="breadcrumb-item @empty($i['url']) active @endempty">
            @if (empty($i['url']))
                {{ $i['title'] }}
            @else
                <a href="{{ $i['url'] }}">
                    {{ $i['title'] }}
                </a>
            @endif

        </li>
    @endforeach
</ol>
