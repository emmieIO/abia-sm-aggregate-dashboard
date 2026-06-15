<x-app-layout>
    <x-slot name="title">Parents Records</x-slot>

    <!-- Table Section -->
    <div class="">
        <div class="flex flex-col">
            <div class="-m-1.5 overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-900 dark:border-neutral-700">
                        <div class="space-y-4 border-b border-gray-200 px-6 py-4 dark:border-neutral-700">
                            <div class="flex items-center justify-between gap-3">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">Parents Records</h2>
                                <a href="{{ route('parents.index') }}" class="inline-flex size-9 shrink-0 items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 focus:outline-hidden focus:ring-2 focus:ring-blue-500 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800" aria-label="Reset parent filters">
                                    <i data-lucide="refresh-ccw" class="size-4"></i>
                                </a>
                            </div>

                            <form action="{{ route('parents.index') }}" method="get" class="grid gap-3 sm:grid-cols-2 xl:grid-cols-[minmax(16rem,1fr)_minmax(18rem,1.4fr)]">
                                <select name="school_id" onchange="this.form.submit()" class="block w-full rounded-lg border-gray-200 bg-white px-3 py-2 pe-9 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
                                    <option value="">All Schools</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                                    @endforeach
                                </select>

                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 start-0 z-20 flex items-center ps-3.5">
                                        <i data-lucide="search" class="size-4 shrink-0 text-gray-400 dark:text-white/60"></i>
                                    </div>
                                    <input value="{{ request('search') }}" onblur="this.form.submit()" type="text" name="search"
                                        class="block w-full rounded-lg border-gray-200 bg-white py-2 ps-10 pe-3 text-sm placeholder:text-neutral-400 focus:border-blue-500 focus:outline-hidden focus:ring-blue-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300"
                                        placeholder="Lookup Parents">
                                </div>
                            </form>
                        </div>

                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-500">S/N</th>
                                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-500">Parent Name</th>
                                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-500">School</th>
                                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-500">Status</th>
                                    <th class="px-6 py-3 text-end text-xs font-semibold uppercase text-gray-500">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                @forelse ($parents as $index => $parent)
                                    @php
                                        $status = strtolower((string) $parent->status);
                                        $fatherName = trim(collect([$parent->fname, $parent->oname, $parent->sname])->filter()->implode(' '));
                                        $motherName = trim(collect([$parent->mfname, $parent->moname, $parent->msname])->filter()->implode(' '));
                                        $parentModalId = 'parent-detail-'.$parent->id;
                                    @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800/50 transition-colors">
                                        <td>{{ $parents->firstItem() + $index }}</td>
                                        <td class="record-title">{{ $fatherName ?: $motherName ?: 'Unnamed parent' }}</td>
                                        <td class="record-muted">{{ $parent->school }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            @if(str_contains($status, 'active') || str_contains($status, 'enabled'))
                                                <span class="px-2 py-1 rounded-md bg-green-50 text-green-700 text-[10px] font-bold uppercase">Active</span>
                                            @else
                                                <span class="px-2 py-1 rounded-md bg-gray-100 text-gray-600 text-[10px] font-bold uppercase">{{ $parent->status ?: 'Unknown' }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-end text-sm">
                                            <button type="button" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-hidden focus:ring-2 focus:ring-blue-500 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800" data-hs-overlay="#{{ $parentModalId }}">
                                                <i data-lucide="eye" class="size-4"></i>
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="empty-row px-6 py-10">
                                            No parent records match the current filters.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        @foreach ($parents as $parent)
                            @php
                                $fatherName = trim(collect([$parent->fname, $parent->oname, $parent->sname])->filter()->implode(' '));
                                $motherName = trim(collect([$parent->mfname, $parent->moname, $parent->msname])->filter()->implode(' '));
                                $parentModalId = 'parent-detail-'.$parent->id;
                                $parentSections = [
                                    'Parent Record' => [
                                        'Parent ID' => $parent->parent_id ?: 'Not provided',
                                        'School' => $parent->school ?: 'Unassigned',
                                        'Status' => $parent->status ?: 'Unknown',
                                        'Religion' => $parent->religion ?: 'Not provided',
                                        'Industry' => $parent->industry ?: 'Not provided',
                                    ],
                                    'Primary Contact' => [
                                        'Phone' => $parent->phone ?: 'Not provided',
                                        'Email' => $parent->email ?: 'Not provided',
                                        'Alternate Email' => $parent->email2 ?: 'Not provided',
                                    ],
                                    'Father Details' => [
                                        'Full Name' => $fatherName ?: 'Not provided',
                                        'Occupation' => $parent->occupation ?: 'Not provided',
                                        'Phone' => $parent->fphone ?: 'Not provided',
                                        'Email' => $parent->femail ?: 'Not provided',
                                        'Date of Birth' => $parent->fdob ?: 'Not provided',
                                        'State' => $parent->father_state ?: 'Not provided',
                                        'LGA' => $parent->flga ?: 'Not provided',
                                        'Village' => $parent->fvillage ?: 'Not provided',
                                        'Address' => $parent->address ?: 'Not provided',
                                    ],
                                    'Mother Details' => [
                                        'Full Name' => $motherName ?: 'Not provided',
                                        'Occupation' => $parent->moccupation ?: 'Not provided',
                                        'Phone' => $parent->mphone ?: 'Not provided',
                                        'Email' => $parent->memail ?: 'Not provided',
                                        'Date of Birth' => $parent->mdob ?: 'Not provided',
                                        'State' => $parent->mother_state ?: 'Not provided',
                                        'LGA' => $parent->mlga ?: 'Not provided',
                                        'Village' => $parent->mvillage ?: 'Not provided',
                                        'Address' => $parent->maddress ?: 'Not provided',
                                    ],
                                ];
                            @endphp

                            <div id="{{ $parentModalId }}" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="{{ $parentModalId }}-label">
                                <div class="m-3 mt-10 opacity-0 transition-all hs-overlay-open:mt-7 hs-overlay-open:opacity-100 sm:mx-auto sm:w-full sm:max-w-5xl xl:max-w-6xl">
                                    <div class="flex max-h-[88vh] flex-col rounded-xl border border-gray-200 bg-white shadow-sm pointer-events-auto dark:border-neutral-700 dark:bg-neutral-900">
                                        <div class="flex items-start justify-between gap-4 border-b border-gray-200 px-5 py-4 dark:border-neutral-700">
                                            <div>
                                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-neutral-400">Parent Details</p>
                                                <h3 id="{{ $parentModalId }}-label" class="mt-1 text-lg font-bold text-gray-900 dark:text-white">{{ $fatherName ?: $motherName ?: 'Unnamed parent' }}</h3>
                                                <p class="mt-1 text-sm font-semibold text-blue-600 dark:text-blue-400">{{ $parent->school ?: 'Unassigned school' }}</p>
                                            </div>
                                            <button type="button" class="inline-flex size-9 shrink-0 items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 hover:text-gray-800 focus:outline-hidden focus:ring-2 focus:ring-blue-500 dark:border-neutral-700 dark:text-neutral-400 dark:hover:bg-neutral-800 dark:hover:text-neutral-200" aria-label="Close" data-hs-overlay="#{{ $parentModalId }}">
                                                <i data-lucide="x" class="size-4"></i>
                                            </button>
                                        </div>

                                        <div class="overflow-y-auto px-5 py-5">
                                            <div class="grid gap-4 lg:grid-cols-3 xl:grid-cols-4">
                                                @foreach($parentSections as $section => $items)
                                                    <section class="{{ in_array($section, ['Parent Record', 'Primary Contact', 'Father Details', 'Mother Details'], true) ? 'lg:col-span-2' : '' }} rounded-xl border border-gray-200 bg-gray-50/70 p-4 dark:border-neutral-700 dark:bg-neutral-800/50">
                                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $section }}</h4>
                                                        <dl class="mt-3 grid gap-3 {{ in_array($section, ['Parent Record', 'Primary Contact'], true) ? 'sm:grid-cols-3' : 'sm:grid-cols-2' }}">
                                                            @foreach($items as $label => $value)
                                                                <div class="{{ $label === 'Address' ? 'sm:col-span-2' : '' }} rounded-lg border border-gray-100 bg-white p-3 dark:border-neutral-700 dark:bg-neutral-900">
                                                                    <dt class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-neutral-400">{{ $label }}</dt>
                                                                    <dd class="mt-1 break-words text-sm font-medium text-gray-900 dark:text-white">{{ $value }}</dd>
                                                                </div>
                                                            @endforeach
                                                        </dl>
                                                    </section>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-end gap-2 border-t border-gray-200 px-5 py-4 dark:border-neutral-700">
                                            <button type="button" class="rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-hidden focus:ring-2 focus:ring-blue-500 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800" data-hs-overlay="#{{ $parentModalId }}">
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-700">
                            {{ $parents->links('vendor.pagination.custom-pg') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
