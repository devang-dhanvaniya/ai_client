@props(['column', 'label', 'sortByColumn', 'sortDirection'])

<th wire:click="setSortFunctionality('{{ $column }}')">
    <div class="d-flex flex-nowrap align-items-center justify-content-between ml-1 gap-2">
        {{ $label }}
        <div>
            @if ($sortByColumn === $column)
                <i class="cursor-pointer d-block fa-solid fa-angle-up {{ $sortDirection === 'ASC' ? 'opacity-100' : 'opacity-50' }}"></i>
                <i class="cursor-pointer d-block fa-solid fa-angle-down {{ $sortDirection === 'DESC' ? 'opacity-100' : 'opacity-50' }}"></i>
            @else
                <i class="cursor-pointer d-block fa-solid fa-angle-up opacity-50"></i>
                <i class="cursor-pointer d-block fa-solid fa-angle-down opacity-50"></i>
            @endif
        </div>
    </div>
</th>
