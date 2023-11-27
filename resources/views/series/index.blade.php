@foreach ($popular as $series)
    <div>
        {{ $series->title }} (<a href="/series/{{ $series->slug }}">{{ $series->slug }}</a>) (all time: {{ $series->visit_count_total }}, current scope: {{ $series->visit_count }})
    </div>
@endforeach
