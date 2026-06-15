 {{-- bg-gray-100 and dark:bg-neutral-700. --}}
 {{-- {{ route('dashboard.index') }} --}}
 @props([
    "route" => "",
    'name' => "",
    'icon' => ""
 ])

<a href="{{ route($route) }}"
    @class([
        'flex items-center gap-x-3.5 py-2 px-3 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-white',
        'bg-gray-100 dark:bg-neutral-700' => request()->routeIs($route),
    ])>
    <i data-lucide="{{ $icon }}" class="size-4 shrink-0"></i>
    {{ $name }}
</a>
 </li>
