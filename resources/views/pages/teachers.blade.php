<x-app-layout>
    <x-slot name="title">Staff Records</x-slot>

    <!-- Table Section -->
    <div class="">
        <div class="flex flex-col">
            <div class="-m-1.5 overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-900 dark:border-neutral-700">
                        <div class="space-y-4 border-b border-gray-200 px-6 py-4 dark:border-neutral-700">
                            <div class="flex items-center justify-between gap-3">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">Staff Records</h2>
                                <a href="{{ route('staffs.index') }}" class="inline-flex size-9 shrink-0 items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 focus:outline-hidden focus:ring-2 focus:ring-blue-500 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800" aria-label="Reset staff filters">
                                    <i data-lucide="refresh-ccw" class="size-4"></i>
                                </a>
                            </div>

                            <form action="{{ route('staffs.index') }}" method="get" class="grid gap-3 sm:grid-cols-2 xl:grid-cols-[minmax(16rem,1.3fr)_minmax(13rem,1fr)_9rem_minmax(16rem,1fr)]">
                                <select name="school_id" onchange="this.form.submit()" class="block w-full rounded-lg border-gray-200 bg-white px-3 py-2 pe-9 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
                                    <option value="">All Schools</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                                    @endforeach
                                </select>

                                <select name="depart_id" onchange="this.form.submit()" class="block w-full rounded-lg border-gray-200 bg-white px-3 py-2 pe-9 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
                                    <option value="">All Departments</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ request('depart_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>

                                <select name="sex" onchange="this.form.submit()" class="block w-full rounded-lg border-gray-200 bg-white px-3 py-2 pe-9 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
                                    <option value="">All Gender</option>
                                    <option value="Male" {{ request('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ request('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>

                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 start-0 z-20 flex items-center ps-3.5">
                                        <i data-lucide="search" class="size-4 shrink-0 text-gray-400 dark:text-white/60"></i>
                                    </div>
                                    <input value="{{ request('search') }}" onblur="this.form.submit()" type="text" name="search"
                                        class="block w-full rounded-lg border-gray-200 bg-white py-2 ps-10 pe-3 text-sm placeholder:text-neutral-400 focus:border-blue-500 focus:outline-hidden focus:ring-blue-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300"
                                        placeholder="Lookup Staff">
                                </div>
                            </form>
                        </div>

                        <table class="data-table">
                            <thead>
                                <tr class="whitespace-nowrap">
                                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-500">S/N</th>
                                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-500">Name</th>
                                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-500">Role</th>
                                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-500">Department</th>
                                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-500">School</th>
                                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-500">Status</th>
                                    <th class="px-6 py-3 text-end text-xs font-semibold uppercase text-gray-500">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                @foreach ($staffs as $index => $staff)
                                    @php
                                        $staffName = trim(collect([$staff->fname, $staff->oname, $staff->sname])->filter()->implode(' '));
                                        $staffModalId = 'staff-detail-'.$staff->id;
                                    @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800/50 transition-colors">
                                        <td>{{ $staffs->firstItem() + $index }}</td>
                                        <td class="record-title">{{ $staffName ?: 'Unnamed staff' }}</td>
                                        <td class="record-muted">{{ $staff->designation_name ?: 'Unassigned' }}</td>
                                        <td class="record-muted">{{ $staff->department }}</td>
                                        <td class="record-muted">{{ $staff->school }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            @if(str_contains(strtolower($staff->status), 'active') || str_contains(strtolower($staff->status), 'enabled'))
                                                <span class="px-2 py-1 rounded-md bg-green-50 text-green-700 text-[10px] font-bold uppercase">Active</span>
                                            @else
                                                <span class="px-2 py-1 rounded-md bg-rose-50 text-rose-700 text-[10px] font-bold uppercase">Disabled</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-end text-sm">
                                            <button type="button" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-hidden focus:ring-2 focus:ring-blue-500 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800" data-hs-overlay="#{{ $staffModalId }}">
                                                <i data-lucide="eye" class="size-4"></i>
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @foreach ($staffs as $staff)
                            @php
                                $staffName = trim(collect([$staff->fname, $staff->oname, $staff->sname])->filter()->implode(' '));
                                $staffModalId = 'staff-detail-'.$staff->id;
                                $staffSections = [
                                    'Personal' => [
                                        'Staff ID' => $staff->staff_id ?: 'Not provided',
                                        'Full Name' => $staffName ?: 'Unnamed staff',
                                        'Gender' => $staff->sex ?: 'Not provided',
                                        'Date of Birth' => $staff->dob ?: 'Not provided',
                                        'Religion' => $staff->religion ?: 'Not provided',
                                        'Status' => $staff->status ?: 'Unknown',
                                    ],
                                    'Contact' => [
                                        'Phone' => $staff->phone ?: 'Not provided',
                                        'Email' => $staff->email ?: 'Not provided',
                                        'Alternate Email' => $staff->email2 ?: 'Not provided',
                                    ],
                                    'Employment' => [
                                        'Role' => $staff->designation_name ?: 'Unassigned',
                                        'Department' => $staff->department ?: 'Unassigned',
                                        'Assigned Class' => $staff->designate ?: 'Unassigned',
                                        'Employment Type' => $staff->employment_type ?: 'Not provided',
                                        'Date Employed' => $staff->doe ?: 'Not provided',
                                        'ABSSIN' => $staff->abssin ?: 'Not provided',
                                    ],
                                    'School / Location' => [
                                        'School' => $staff->school ?: 'Unassigned',
                                        'State' => $staff->state ?: 'Not provided',
                                        'LGA' => $staff->lga ?: 'Not provided',
                                        'Village' => $staff->village ?: 'Not provided',
                                        'Address' => $staff->address ?: 'Not provided',
                                    ],
                                ];
                            @endphp

                            <div id="{{ $staffModalId }}" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="{{ $staffModalId }}-label">
                                <div class="m-3 mt-10 opacity-0 transition-all hs-overlay-open:mt-7 hs-overlay-open:opacity-100 sm:mx-auto sm:w-full sm:max-w-5xl xl:max-w-6xl">
                                    <div class="flex max-h-[88vh] flex-col rounded-xl border border-gray-200 bg-white shadow-sm pointer-events-auto dark:border-neutral-700 dark:bg-neutral-900">
                                        <div class="flex items-start justify-between gap-4 border-b border-gray-200 px-5 py-4 dark:border-neutral-700">
                                            <div>
                                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-neutral-400">Staff Details</p>
                                                <h3 id="{{ $staffModalId }}-label" class="mt-1 text-lg font-bold text-gray-900 dark:text-white">{{ $staffName ?: 'Unnamed staff' }}</h3>
                                                <p class="mt-1 font-mono text-sm font-semibold text-blue-600 dark:text-blue-400">{{ $staff->staff_id }}</p>
                                            </div>
                                            <button type="button" class="inline-flex size-9 shrink-0 items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 hover:text-gray-800 focus:outline-hidden focus:ring-2 focus:ring-blue-500 dark:border-neutral-700 dark:text-neutral-400 dark:hover:bg-neutral-800 dark:hover:text-neutral-200" aria-label="Close" data-hs-overlay="#{{ $staffModalId }}">
                                                <i data-lucide="x" class="size-4"></i>
                                            </button>
                                        </div>

                                        <div class="overflow-y-auto px-5 py-5">
                                            <div class="grid gap-4 lg:grid-cols-3 xl:grid-cols-4">
                                                @foreach($staffSections as $section => $items)
                                                    <section class="{{ in_array($section, ['Personal', 'Contact', 'Employment', 'School / Location'], true) ? 'lg:col-span-2' : '' }} rounded-xl border border-gray-200 bg-gray-50/70 p-4 dark:border-neutral-700 dark:bg-neutral-800/50">
                                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $section }}</h4>
                                                        <dl class="mt-3 grid gap-3 {{ $section === 'Contact' ? 'sm:grid-cols-3' : 'sm:grid-cols-2' }}">
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
                                            <button type="button" class="rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-hidden focus:ring-2 focus:ring-blue-500 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800" data-hs-overlay="#{{ $staffModalId }}">
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-700">
                            {{ $staffs->links('vendor.pagination.custom-pg') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
