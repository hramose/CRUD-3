{{-- converts 1/true or 0/false to yes/no/lang --}}
<span data-order="{{ $entry->{$column['name']} }}">
	@if ($entry->{$column['name']} === true || $entry->{$column['name']} === 1 || $entry->{$column['name']} === '1')
        @if ( isset( $column['options'][1] ) )
            {!! $column['options'][1] !!}
        @else
            {{ Lang::has('sone::crud.yes')?trans('sone::crud.yes'):'Yes' }}
        @endif
    @else
        @if ( isset( $column['options'][0] ) )
            {!! $column['options'][0] !!}
        @else
            {{ Lang::has('sone::crud.no')?trans('sone::crud.no'):'No' }}
        @endif
    @endif
</span>
