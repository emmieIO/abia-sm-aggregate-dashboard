<x-app-layout>
    <x-slot name="title">Alumni Records</x-slot>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-900 dark:border-neutral-700">
        <div class="space-y-4 border-b border-gray-200 px-6 py-4 dark:border-neutral-700">
            <div class="flex items-center justify-between gap-3">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">Alumni Directory</h2>
                <a href="{{ route('alumni.index') }}" class="inline-flex size-9 shrink-0 items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 focus:outline-hidden focus:ring-2 focus:ring-blue-500 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800" aria-label="Reset alumni filters">
                    <i data-lucide="refresh-ccw" class="size-4"></i>
                </a>
            </div>

            <form action="{{ route('alumni.index') }}" method="get" class="grid gap-3 sm:grid-cols-2 xl:grid-cols-[minmax(16rem,1.2fr)_minmax(11rem,0.8fr)_minmax(18rem,1.3fr)]">
                <select name="school_id" onchange="this.form.submit()" class="block w-full rounded-lg border-gray-200 bg-white px-3 py-2 pe-9 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
                    <option value="">All Schools</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </select>

                <select name="session_id" onchange="this.form.submit()" class="block w-full rounded-lg border-gray-200 bg-white px-3 py-2 pe-9 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
                    <option value="">All Sessions</option>
                    @foreach($sessions as $session)
                        <option value="{{ $session->id }}" {{ request('session_id') == $session->id ? 'selected' : '' }}>
                            {{ $session->name }}
                        </option>
                    @endforeach
                </select>

                <div class="relative sm:col-span-2 xl:col-span-1">
                    <div class="pointer-events-none absolute inset-y-0 start-0 z-20 flex items-center ps-3.5">
                        <i data-lucide="search" class="size-4 shrink-0 text-gray-400 dark:text-white/60"></i>
                    </div>
                    <input value="{{ request('search') }}" onblur="this.form.submit()" type="text" name="search"
                        class="block w-full rounded-lg border-gray-200 bg-white py-2 ps-10 pe-3 text-sm placeholder:text-neutral-400 focus:border-blue-500 focus:outline-hidden focus:ring-blue-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300"
                        placeholder="Lookup Alumni">
                </div>
            </form>
        </div>

        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">S/N</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Name</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">School</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Graduation Session</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Date</th>
                        <th class="px-6 py-3 text-end text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @forelse($alumni as $index => $item)
                    @php
                        $alumniName = trim(collect([$item->fname, $item->oname, $item->sname])->filter()->implode(' '));
                        $alumniModalId = 'alumni-detail-'.$item->id;
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800/50">
                        <td>{{ $alumni->firstItem() + $index }}</td>
                        <td class="record-title">{{ $alumniName ?: 'Unnamed alumnus' }}</td>
                        <td class="record-muted">{{ $item->school }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 rounded-md bg-blue-100 text-blue-700 text-xs font-bold dark:bg-blue-900/30 dark:text-blue-300">
                                {{ $item->session }}
                            </span>
                        </td>
                        <td class="record-muted">{{ $item->graduation_date }}</td>
                        <td class="px-6 py-4 text-end text-sm">
                            <button type="button" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-hidden focus:ring-2 focus:ring-blue-500 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800" data-hs-overlay="#{{ $alumniModalId }}">
                                <i data-lucide="eye" class="size-4"></i>
                                View
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="empty-row px-6 py-10">
                            No alumni records match the current filters.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @foreach($alumni as $item)
            @php
                $alumniName = trim(collect([$item->fname, $item->oname, $item->sname])->filter()->implode(' '));
                $alumniModalId = 'alumni-detail-'.$item->id;
                $alumniSections = [
                    'Personal' => [
                        'Student ID' => $item->student_id ?: 'Not provided',
                        'Full Name' => $alumniName ?: 'Unnamed alumnus',
                        'Gender' => $item->sex ?: 'Not provided',
                        'Date of Birth' => $item->dob ?: 'Not provided',
                        'Religion' => $item->religion ?: 'Not provided',
                        'Status' => $item->status ?: 'Not provided',
                    ],
                    'Contact' => [
                        'Phone' => $item->phone ?: 'Not provided',
                        'Email' => $item->email ?: 'Not provided',
                    ],
                    'Alumni Record' => [
                        'School' => $item->school ?: 'Unassigned',
                        'Class' => $item->class ?: 'Not provided',
                        'Graduation Session' => $item->session ?: 'Not provided',
                        'Graduation Date' => $item->graduation_date ?: 'Not provided',
                    ],
                    'Location' => [
                        'State' => $item->state ?: 'Not provided',
                        'Village' => $item->village ?: 'Not provided',
                        'Address' => $item->address ?: 'Not provided',
                    ],
                ];
            @endphp

            <div id="{{ $alumniModalId }}" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="{{ $alumniModalId }}-label">
                <div class="m-3 mt-10 opacity-0 transition-all hs-overlay-open:mt-7 hs-overlay-open:opacity-100 sm:mx-auto sm:w-full sm:max-w-5xl xl:max-w-6xl">
                    <div class="flex max-h-[88vh] flex-col rounded-xl border border-gray-200 bg-white shadow-sm pointer-events-auto dark:border-neutral-700 dark:bg-neutral-900">
                        <div class="flex items-start justify-between gap-4 border-b border-gray-200 px-5 py-4 dark:border-neutral-700">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-neutral-400">Alumni Details</p>
                                <h3 id="{{ $alumniModalId }}-label" class="mt-1 text-lg font-bold text-gray-900 dark:text-white">{{ $alumniName ?: 'Unnamed alumnus' }}</h3>
                                <p class="mt-1 font-mono text-sm font-semibold text-blue-600 dark:text-blue-400">{{ $item->student_id }}</p>
                            </div>
                            <button type="button" class="inline-flex size-9 shrink-0 items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 hover:text-gray-800 focus:outline-hidden focus:ring-2 focus:ring-blue-500 dark:border-neutral-700 dark:text-neutral-400 dark:hover:bg-neutral-800 dark:hover:text-neutral-200" aria-label="Close" data-hs-overlay="#{{ $alumniModalId }}">
                                <i data-lucide="x" class="size-4"></i>
                            </button>
                        </div>

                        <div class="overflow-y-auto px-5 py-5">
                            <div class="grid gap-4 lg:grid-cols-3 xl:grid-cols-4">
                                @foreach($alumniSections as $section => $items)
                                    <section class="{{ in_array($section, ['Personal', 'Contact', 'Alumni Record', 'Location'], true) ? 'lg:col-span-2' : '' }} rounded-xl border border-gray-200 bg-gray-50/70 p-4 dark:border-neutral-700 dark:bg-neutral-800/50">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $section }}</h4>
                                        <dl class="mt-3 grid gap-3 {{ $section === 'Contact' ? 'sm:grid-cols-2' : 'sm:grid-cols-2' }}">
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
                            <button type="button" class="rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-hidden focus:ring-2 focus:ring-blue-500 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800" data-hs-overlay="#{{ $alumniModalId }}">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-700">
            {{ $alumni->links('vendor.pagination.custom-pg') }}
        </div>
    </div>
</x-app-layout>
