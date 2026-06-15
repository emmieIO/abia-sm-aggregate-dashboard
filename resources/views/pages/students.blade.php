<x-app-layout>
    <x-slot name="title">{{ $selectedSchool ? $selectedSchool->name.' Students' : 'Students Records' }}</x-slot>

    @if($selectedSchool)
        <section class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <div class="flex flex-col gap-4 border-b border-gray-200 px-5 py-4 dark:border-neutral-700 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <a href="{{ route('schools.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400">
                        <i data-lucide="arrow-left" class="size-4"></i>
                        Schools
                    </a>
                    <h1 class="mt-2 text-xl font-bold text-gray-900 dark:text-white">{{ $selectedSchool->name }}</h1>
                    <div class="mt-1 flex flex-wrap gap-2 text-sm text-gray-500 dark:text-neutral-400">
                        <span>{{ $selectedSchool->school_id }}</span>
                        @if($selectedSchool->lga_name)
                            <span class="text-gray-300 dark:text-neutral-700">/</span>
                            <span>{{ $selectedSchool->lga_name }}</span>
                        @endif
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 sm:flex sm:items-center">
                    <div class="rounded-lg bg-gray-50 px-4 py-2 dark:bg-neutral-800">
                        <p class="text-xs font-medium text-gray-500 dark:text-neutral-400">Classes</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($classSummaries->count()) }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 px-4 py-2 dark:bg-neutral-800">
                        <p class="text-xs font-medium text-gray-500 dark:text-neutral-400">Students</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($classSummaries->sum('student_count')) }}</p>
                    </div>
                </div>
            </div>

            <div class="px-5 py-4">
                <div class="mb-3 flex items-center justify-between gap-3">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-neutral-400">Classes</h2>
                    @if($selectedClass)
                        <a href="{{ route('schools.students', $selectedSchool->school_id) }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400">Clear selection</a>
                    @endif
                </div>
                <div class="flex gap-2 overflow-x-auto pb-1">
                    @forelse($classSummaries as $class)
                        <a href="{{ route('schools.students', ['school' => $selectedSchool->school_id, 'class_id' => $class->id]) }}"
                            class="flex min-w-44 shrink-0 items-center justify-between gap-3 rounded-lg border px-3 py-2 transition hover:border-blue-300 hover:bg-blue-50/60 dark:hover:bg-blue-950/20 {{ $selectedClass && $selectedClass->id === $class->id ? 'border-blue-500 bg-blue-50 text-blue-900 dark:border-blue-400 dark:bg-blue-950/30 dark:text-blue-100' : 'border-gray-200 bg-white text-gray-800 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-200' }}">
                            <span>
                                <span class="block text-sm font-semibold">{{ $class->name }}</span>
                                <span class="text-xs text-gray-500 dark:text-neutral-400">{{ number_format($class->student_count) }} students</span>
                            </span>
                            <i data-lucide="chevron-right" class="size-4 shrink-0"></i>
                        </a>
                    @empty
                        <div class="w-full rounded-lg border border-dashed border-gray-200 p-5 text-sm text-gray-500 dark:border-neutral-700 dark:text-neutral-400">
                            No classes are available for this school.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    @endif

    <!-- Table Section -->
    @if(! $requiresClassSelection || $selectedClass)
    <div class="">
        <!-- Card -->
        <div class="flex flex-col">
            <div class="-m-1.5 overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">
                    <div
                        class="bg-white border border-gray-200 rounded-xl shadow-2xs overflow-hidden dark:bg-neutral-900 dark:border-neutral-700">
                        <!-- Header -->
                        <div class="px-6 py-4 grid gap-4 border-b border-gray-200 dark:border-neutral-700 lg:grid-cols-[1fr_auto] lg:items-center">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                                    {{ $selectedClass ? $selectedClass->name.' Students' : 'Students Records' }}
                                </h2>
                                @if($selectedSchool && $selectedClass)
                                    <p class="mt-1 text-sm text-gray-500 dark:text-neutral-400">{{ $selectedSchool->name }}</p>
                                @endif
                            </div>
                            @if($selectedSchool && $selectedClass)
                                <form action="{{ route('schools.students', $selectedSchool->school_id) }}" method="get" class="grid gap-3 sm:grid-cols-[minmax(14rem,1fr)_auto]">
                                    <input type="hidden" name="class_id" value="{{ $selectedClass->id }}">
                                    <div class="relative">
                                        <div class="pointer-events-none absolute inset-y-0 start-0 z-20 flex items-center ps-3.5">
                                            <i data-lucide="search" class="size-4 shrink-0 text-gray-400 dark:text-white/60"></i>
                                        </div>
                                        <input value="{{ request('search') }}" onblur="this.form.submit()" type="text" name="search"
                                            class="block w-full rounded-lg border-gray-200 bg-white py-2 ps-10 pe-3 text-sm placeholder:text-neutral-400 focus:border-blue-500 focus:outline-hidden focus:ring-blue-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300"
                                            placeholder="Search students">
                                    </div>
                                    <select name="sex" onchange="this.form.submit()" class="rounded-lg border-gray-200 bg-white py-2 pe-9 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
                                        <option value="">All Gender</option>
                                        <option value="Male" {{ request('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ request('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="missing" {{ request('sex') == 'missing' ? 'selected' : '' }}>Missing gender</option>
                                    </select>
                                </form>
                            @endif
                        </div>
                        <!-- End Header -->
                        <!-- Table -->
                        <table class="data-table compact">
                            <thead>
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-start border-s border-gray-200 dark:border-neutral-700">
                                        <span
                                            class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                            S/N
                                        </span>
                                    </th>

                                    <th scope="col"
                                        class="px-6 py-3 text-start border-s border-gray-200 dark:border-neutral-700">
                                        <span
                                            class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                            Student_id
                                        </span>
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                            Student
                                        </span>
                                    </th>

                                    @unless($selectedSchool)
                                        <th scope="col" class="px-6 py-3 text-start">
                                            <span class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                                School
                                            </span>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start">
                                            <span class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                                Class
                                            </span>
                                        </th>
                                    @endunless

                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span
                                            class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                            Parent
                                        </span>
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span
                                            class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                            Sex
                                        </span>
                                    </th>

                                    @unless($selectedSchool)
                                        <th scope="col" class="px-6 py-3 text-start">
                                            <span class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                                Date of Birth
                                            </span>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start">
                                            <span class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                                State
                                            </span>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start">
                                            <span class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                                Village
                                            </span>
                                        </th>
                                    @endunless

                                    <th scope="col" class="px-6 py-3 text-end">
                                        <span class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                            Action
                                        </span>
                                    </th>

                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700  text-color">
                                @forelse ($students as $index => $student)
                                    @php
                                        $studentModalId = 'student-detail-'.$student->id;
                                    @endphp
                                        <tr>
                                            <td>{{ $students->firstItem() + $index }}</td>
                                            <td class="record-id">{{ $student->student_id }}</td>
                                            <td>
                                                <p class="record-title">{{ $student->fname }} {{ $student->sname }}</p>
                                                @if($student->oname)
                                                    <p class="text-xs text-gray-500 dark:text-neutral-400">{{ $student->oname }}</p>
                                                @endif
                                            </td>
                                            @unless($selectedSchool)
                                                <td class="record-muted">{{ $student->school }}</td>
                                                <td class="record-muted">{{ $student->class }}</td>
                                            @endunless
                                            <td>
                                                <p class="record-muted">{{ trim($student->parent_fname.' '.$student->parent_sname) ?: 'Unassigned' }}</p>
                                                @if($student->parent_phone)
                                                    <p class="text-xs text-gray-500 dark:text-neutral-400">{{ $student->parent_phone }}</p>
                                                @endif
                                            </td>
                                            <td class="record-muted">{{ $student->sex }}</td>
                                            @unless($selectedSchool)
                                                <td class="record-muted">{{ $student->dob }}</td>
                                                <td class="record-muted">{{ $student->state }}</td>
                                                <td class="record-muted">{{ $student->village }}</td>
                                            @endunless
                                            <td class="text-end">
                                                <button type="button" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-1.5 text-sm font-medium text-gray-700 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700 focus:outline-hidden focus:ring-2 focus:ring-blue-500 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-blue-950/20 dark:hover:text-blue-300" aria-haspopup="dialog" aria-expanded="false" aria-controls="{{ $studentModalId }}" data-hs-overlay="#{{ $studentModalId }}">
                                                    <i data-lucide="eye" class="size-4"></i>
                                                    View
                                                </button>
                                            </td>
                                        </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $selectedSchool ? 6 : 12 }}" class="empty-row px-6 py-10">
                                            No student records match the current filters.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!-- End Table -->

                        @foreach ($students as $student)
                            @php
                                $studentModalId = 'student-detail-'.$student->id;
                                $studentName = trim($student->fname.' '.$student->sname.' '.$student->oname);
                                $parentName = trim($student->parent_fname.' '.$student->parent_sname.' '.$student->parent_oname);
                                $personalDetails = [
                                    'Student ID' => $student->student_id,
                                    'Full Name' => $studentName ?: 'Unavailable',
                                    'Gender' => $student->sex ?: 'Missing gender',
                                    'Date of Birth' => $student->dob ?: 'Not provided',
                                    'Phone' => $student->phone ?: 'Not provided',
                                    'Email' => $student->email ?: 'Not provided',
                                    'Religion' => $student->religion ?: 'Not provided',
                                    'Status' => $student->status ?: 'Not provided',
                                ];
                                $academicDetails = [
                                    'School' => $student->school ?: 'Unassigned',
                                    'Current Class' => $student->class ?: 'Unassigned',
                                    'Admitted Class' => $student->admitted_class ?: 'Not provided',
                                    'Current Stage' => $student->current_stage ?: 'Not provided',
                                    'Admitted Stage' => $student->admitted_stage ?: 'Not provided',
                                    'Current Session' => $student->current_session ?: 'Not provided',
                                    'Admitted Session' => $student->admitted_session ?: 'Not provided',
                                    'Current Term' => $student->current_term ?: 'Not provided',
                                    'Admitted Term' => $student->admitted_term ?: 'Not provided',
                                    'Date of Admission' => $student->doa ?: 'Not provided',
                                ];
                                $parentDetails = [
                                    'Parent' => $parentName ?: 'Unassigned',
                                    'Parent Phone' => $student->parent_phone ?: 'Not provided',
                                    'Parent Email' => $student->parent_email ?: 'Not provided',
                                    'Father Phone' => $student->father_phone ?: 'Not provided',
                                    'Father Email' => $student->father_email ?: 'Not provided',
                                    'Mother Phone' => $student->mother_phone ?: 'Not provided',
                                    'Mother Email' => $student->mother_email ?: 'Not provided',
                                    'Occupation' => $student->parent_occupation ?: 'Not provided',
                                ];
                                $healthDetails = [
                                    'Blood Group' => $student->blood_group ?: 'Not provided',
                                    'Genotype' => $student->genotype ?: 'Not provided',
                                    'Disability' => $student->disability ?: 'None recorded',
                                    'Asthmatic' => $student->asthmatic ?: 'Not provided',
                                    'Ailments' => $student->ailments ?: 'None recorded',
                                ];
                                $locationDetails = [
                                    'State' => $student->state ?: 'Not provided',
                                    'Village' => $student->village ?: 'Not provided',
                                    'Address' => $student->address ?: 'Not provided',
                                    'Parent Address' => $student->parent_address ?: 'Not provided',
                                ];
                                $detailSections = [
                                    'Personal' => $personalDetails,
                                    'Academic' => $academicDetails,
                                    'Parent / Guardian' => $parentDetails,
                                    'Health' => $healthDetails,
                                    'Location' => $locationDetails,
                                ];
                            @endphp

                            <div id="{{ $studentModalId }}" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="{{ $studentModalId }}-label">
                                <div class="m-3 mt-10 opacity-0 transition-all hs-overlay-open:mt-7 hs-overlay-open:opacity-100 sm:mx-auto sm:w-full sm:max-w-5xl xl:max-w-6xl">
                                    <div class="flex max-h-[88vh] flex-col rounded-xl border border-gray-200 bg-white shadow-sm pointer-events-auto dark:border-neutral-700 dark:bg-neutral-900">
                                        <div class="flex items-start justify-between gap-4 border-b border-gray-200 px-5 py-4 dark:border-neutral-700">
                                            <div>
                                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-neutral-400">Student Details</p>
                                                <h3 id="{{ $studentModalId }}-label" class="mt-1 text-lg font-bold text-gray-900 dark:text-white">{{ $studentName ?: 'Unnamed student' }}</h3>
                                                <p class="mt-1 font-mono text-sm font-semibold text-blue-600 dark:text-blue-400">{{ $student->student_id }}</p>
                                            </div>
                                            <button type="button" class="inline-flex size-9 shrink-0 items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 hover:text-gray-800 focus:outline-hidden focus:ring-2 focus:ring-blue-500 dark:border-neutral-700 dark:text-neutral-400 dark:hover:bg-neutral-800 dark:hover:text-neutral-200" aria-label="Close" data-hs-overlay="#{{ $studentModalId }}">
                                                <i data-lucide="x" class="size-4"></i>
                                            </button>
                                        </div>

                                        <div class="overflow-y-auto px-5 py-5">
                                            <div class="grid gap-4 lg:grid-cols-3 xl:grid-cols-4">
                                                @foreach($detailSections as $section => $items)
                                                    <section class="{{ in_array($section, ['Personal', 'Academic', 'Parent / Guardian'], true) ? 'lg:col-span-2' : '' }} rounded-xl border border-gray-200 bg-gray-50/70 p-4 dark:border-neutral-700 dark:bg-neutral-800/50">
                                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $section }}</h4>
                                                        <dl class="mt-3 grid gap-3 sm:grid-cols-2">
                                                            @foreach($items as $label => $value)
                                                                <div class="{{ in_array($label, ['Address', 'Parent Address', 'Ailments'], true) ? 'sm:col-span-2' : '' }} rounded-lg border border-gray-100 bg-white p-3 dark:border-neutral-700 dark:bg-neutral-900">
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
                                            <button type="button" class="rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-hidden focus:ring-2 focus:ring-blue-500 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800" data-hs-overlay="#{{ $studentModalId }}">
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Footer -->
                        <div
                            class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-t border-gray-200 dark:border-neutral-700">
                            <div>
                                <div class="flex gap-x-2 space-between w-full">
                                    {{ $students->links('vendor.pagination.custom-pg') }}
                                </div>
                            </div>
                        </div>
                        <!-- End Footer -->
                    </div>
                </div>
            </div>
        </div>
        <!-- End Card -->
    </div>
    @elseif($selectedSchool)
        <div class="rounded-xl border border-dashed border-gray-200 bg-white p-8 text-center shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <i data-lucide="users-round" class="mx-auto size-8 text-gray-400"></i>
            <h2 class="mt-3 text-base font-semibold text-gray-900 dark:text-white">Select a class to view students</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-neutral-400">Choose one of the available classes above.</p>
        </div>
    @endif
    <!-- End Table Section -->
</x-app-layout>
