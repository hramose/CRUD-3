{{-- localized date using jenssegers/date --}}
<span data-order="{{ $entry->{$column['name']} }}">
    @if (!empty($entry->{$column['name']}))
	{{ Date::parse($entry->{$column['name']})->format(($column['format'] ?? config('sone.base.default_date_format'))) }}
    @endif
</span>