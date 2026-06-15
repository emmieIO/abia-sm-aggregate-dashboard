<x-app-layout>
    <x-slot name="title">Analytics Dashboard</x-slot>

    @php
        $contextName = $selected_school?->name ?? 'Abia State';
        $contextLabel = $selected_school ? 'School analytics' : 'Statewide analytics';
        $activeSchoolId = request('school_id');
        $topSchool = $school_analytics->sortByDesc('active_students')->first();
    @endphp

    <div class="space-y-6">
        <section class="flex flex-col gap-4 rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-blue-600 dark:text-blue-400">{{ $contextLabel }}</p>
                <h1 class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ $contextName }}</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-neutral-400">
                    {{ number_format($student_count) }} active students, {{ number_format($staff_count) }} staff, {{ number_format($parent_count) }} parent records
                </p>
            </div>

            <form method="GET" action="{{ route('dashboard.index') }}" class="flex w-full flex-col gap-2 sm:flex-row lg:w-auto">
                <select name="school_id" class="min-w-64 rounded-lg border-gray-200 bg-gray-50 text-sm text-gray-800 focus:border-blue-500 focus:ring-blue-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100" onchange="this.form.submit()">
                    <option value="">All schools</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->school_id }}" @selected($activeSchoolId === $school->school_id)>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </select>

                @if($activeSchoolId)
                    <a href="{{ route('dashboard.index') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-800">
                        <i data-lucide="x" class="size-4"></i>
                        Clear
                    </a>
                @endif
            </form>
        </section>

        <section class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">
            <a href="{{ route('schools.index') }}" class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm transition hover:border-blue-200 hover:shadow-md dark:border-neutral-800 dark:bg-neutral-900">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-neutral-400">{{ $selected_school ? 'Current School' : 'Schools' }}</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $selected_school ? 1 : number_format($school_count) }}</p>
                    </div>
                    <span class="rounded-lg bg-blue-50 p-3 text-blue-600 dark:bg-blue-950/40 dark:text-blue-300">
                        <i data-lucide="school" class="size-5"></i>
                    </span>
                </div>
            </a>

            <a href="{{ $activeSchoolId ? route('schools.students', $activeSchoolId) : route('schools.index') }}" class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm transition hover:border-emerald-200 hover:shadow-md dark:border-neutral-800 dark:bg-neutral-900">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-neutral-400">Active Students</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($student_count) }}</p>
                    </div>
                    <span class="rounded-lg bg-emerald-50 p-3 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-300">
                        <i data-lucide="users" class="size-5"></i>
                    </span>
                </div>
            </a>

            <a href="{{ route('staffs.index', $activeSchoolId ? ['school_id' => $activeSchoolId] : []) }}" class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm transition hover:border-violet-200 hover:shadow-md dark:border-neutral-800 dark:bg-neutral-900">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-neutral-400">Student to Staff Ratio</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            {{ $staff_count > 0 ? number_format($student_staff_ratio, 1).' : 1' : 'N/A' }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-neutral-400">
                            @if($staff_count > 0)
                                {{ number_format($student_count) }} students / {{ number_format($staff_count) }} staff
                            @else
                                No staff recorded for this view
                            @endif
                        </p>
                    </div>
                    <span class="rounded-lg bg-violet-50 p-3 text-violet-600 dark:bg-violet-950/40 dark:text-violet-300">
                        <i data-lucide="briefcase-business" class="size-5"></i>
                    </span>
                </div>
            </a>

            <a href="{{ route('parents.index', $activeSchoolId ? ['school_id' => $activeSchoolId] : []) }}" class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm transition hover:border-amber-200 hover:shadow-md dark:border-neutral-800 dark:bg-neutral-900">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-neutral-400">Parent Coverage</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($parent_coverage_rate, 1) }}%</p>
                    </div>
                    <span class="rounded-lg bg-amber-50 p-3 text-amber-600 dark:bg-amber-950/40 dark:text-amber-300">
                        <i data-lucide="user-round-check" class="size-5"></i>
                    </span>
                </div>
            </a>

            <div class="rounded-lg border border-rose-200 bg-white p-5 shadow-sm dark:border-rose-950/60 dark:bg-neutral-900">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-neutral-400">Intervention Needed</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($intervention_count) }}</p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-neutral-500">Below {{ number_format($intervention_threshold, 0) }}%</p>
                    </div>
                    <span class="rounded-lg bg-rose-50 p-3 text-rose-600 dark:bg-rose-950/40 dark:text-rose-300">
                        <i data-lucide="life-buoy" class="size-5"></i>
                    </span>
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 gap-4 xl:grid-cols-3">
            <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900 xl:col-span-2">
                <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-bold text-gray-900 dark:text-white">{{ $selected_school ? 'Class Enrollment' : 'Enrollment by School' }}</h2>
                        <p class="text-sm text-gray-500 dark:text-neutral-400">{{ $selected_school ? 'Active students by assigned class' : 'Active students distributed across schools' }}</p>
                    </div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">{{ number_format($class_count) }} classes</span>
                </div>
                <div class="mt-5 h-72">
                    <canvas id="enrollmentChart" data-options='@json($selected_school ? $class_distribution : $school_distribution)'></canvas>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                <div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Profile Completeness</h2>
                    <p class="text-sm text-gray-500 dark:text-neutral-400">{{ number_format($data_completeness_rate, 1) }}% average completion</p>
                </div>

                <div class="mt-5 space-y-4">
                    @forelse($data_quality as $quality)
                        <div>
                            <div class="flex items-center justify-between gap-3 text-sm">
                                <span class="font-medium text-gray-700 dark:text-neutral-200">{{ $quality->label }}</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($quality->complete_rate, 1) }}%</span>
                            </div>
                            <div class="mt-2 h-2 overflow-hidden rounded-full bg-gray-100 dark:bg-neutral-800">
                                <div class="h-full rounded-full bg-emerald-500" style="width: {{ min($quality->complete_rate, 100) }}%"></div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-neutral-500">{{ number_format($quality->missing) }} missing</p>
                        </div>
                    @empty
                        <p class="rounded-lg border border-dashed border-gray-200 p-4 text-sm text-gray-500 dark:border-neutral-700 dark:text-neutral-400">No active student profile data is available.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                <h2 class="text-base font-bold text-gray-900 dark:text-white">Gender Mix</h2>
                <p class="text-sm text-gray-500 dark:text-neutral-400">Active students</p>
                <div class="mt-5 h-64">
                    <canvas id="genderChart" data-options='@json($gender_distribution)'></canvas>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                <h2 class="text-base font-bold text-gray-900 dark:text-white">Stage Distribution</h2>
                <p class="text-sm text-gray-500 dark:text-neutral-400">Current academic stage</p>
                <div class="mt-5 h-64">
                    <canvas id="stageChart" data-options='@json($stage_distribution)'></canvas>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                <h2 class="text-base font-bold text-gray-900 dark:text-white">Birth Year Trend</h2>
                <p class="text-sm text-gray-500 dark:text-neutral-400">Captured date of birth records</p>
                <div class="mt-5 h-64">
                    <canvas id="ageChart" data-options='@json($age_distribution)'></canvas>
                </div>
            </div>
        </section>

        <section class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Intervention Watchlist</h2>
                    <p class="text-sm text-gray-500 dark:text-neutral-400">
                        Students scoring below {{ number_format($intervention_threshold, 0) }}% {{ $selected_school ? 'in this school' : 'statewide' }}
                    </p>
                </div>
                <span class="inline-flex w-fit items-center gap-2 rounded-lg bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700 dark:bg-rose-950/30 dark:text-rose-200">
                    <i data-lucide="activity" class="size-4"></i>
                    {{ number_format($intervention_count) }} flagged
                </span>
            </div>

            @if($intervention_students->isNotEmpty())
                <div class="mt-5 table-scroll">
                    <table class="data-table compact">
                        <thead>
                            <tr class="text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-neutral-500">
                                <th class="py-3 pr-4">Student</th>
                                <th class="px-4 py-3">School</th>
                                <th class="px-4 py-3">Class</th>
                                <th class="px-4 py-3">Basis</th>
                                <th class="px-4 py-3 text-right">Score</th>
                                <th class="px-4 py-3 text-right">Attendance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-neutral-800">
                            @foreach($intervention_students as $student)
                                <tr>
                                    <td class="py-3 pr-4">
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $student->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-neutral-500">{{ $student->student_id }}</p>
                                    </td>
                                    <td class="px-4 py-3">{{ $student->school }}</td>
                                    <td class="px-4 py-3">{{ $student->class_name }}</td>
                                    <td class="px-4 py-3">{{ $student->basis }}</td>
                                    <td class="px-4 py-3 text-right font-bold text-rose-600">{{ number_format($student->score, 1) }}%</td>
                                    <td class="px-4 py-3 text-right">{{ $student->attendance !== null ? number_format($student->attendance) : 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="mt-5 grid gap-4 rounded-lg border border-dashed border-rose-200 bg-rose-50/60 p-5 text-sm text-rose-900 dark:border-rose-950/60 dark:bg-rose-950/20 dark:text-rose-100 md:grid-cols-[auto_1fr] md:items-start">
                    <span class="inline-flex size-10 items-center justify-center rounded-lg bg-white text-rose-600 shadow-sm dark:bg-neutral-900 dark:text-rose-300">
                        <i data-lucide="file-search" class="size-5"></i>
                    </span>
                    <div>
                        <p class="font-semibold">No underperforming students can be flagged yet.</p>
                        <p class="mt-1 text-rose-800/80 dark:text-rose-100/80">
                            The watchlist is ready, but this context has no exam summary or scored subject records. Once scores are captured, students below {{ number_format($intervention_threshold, 0) }}% will appear here automatically.
                        </p>
                    </div>
                </div>
            @endif
        </section>

        <section class="grid grid-cols-1 gap-4 xl:grid-cols-3">
            <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900 xl:col-span-2">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-base font-bold text-gray-900 dark:text-white">School Comparison</h2>
                        <p class="text-sm text-gray-500 dark:text-neutral-400">Enrollment, staffing, parents, and class capacity signals</p>
                    </div>
                    @if($topSchool)
                        <span class="hidden rounded-lg bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600 dark:bg-neutral-800 dark:text-neutral-300 sm:inline-flex">
                            Largest: {{ $topSchool->school }}
                        </span>
                    @endif
                </div>

                <div class="mt-5 table-scroll">
                    <table class="data-table compact">
                        <thead>
                            <tr class="text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-neutral-500">
                                <th class="py-3 pr-4">School</th>
                                <th class="px-4 py-3 text-right">Students</th>
                                <th class="px-4 py-3 text-right">Staff</th>
                                <th class="px-4 py-3 text-right">Students per 1 Staff</th>
                                <th class="px-4 py-3 text-right">Parents</th>
                                <th class="px-4 py-3 text-right">Coverage</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-neutral-800">
                            @foreach($school_analytics as $school)
                                <tr>
                                    <td class="py-3 pr-4">
                                        <a href="{{ route('dashboard.index', ['school_id' => $school->school_id]) }}" class="font-semibold text-gray-900 hover:text-blue-600 dark:text-white dark:hover:text-blue-400">{{ $school->school }}</a>
                                        <p class="text-xs text-gray-500 dark:text-neutral-500">{{ $school->lga }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-right">{{ number_format($school->active_students) }}</td>
                                    <td class="px-4 py-3 text-right">{{ number_format($school->staff_count) }}</td>
                                    <td class="px-4 py-3 text-right">
                                        @if($school->staff_count > 0)
                                            <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($school->student_staff_ratio, 1) }} : 1</span>
                                            <p class="text-xs text-gray-500 dark:text-neutral-500">{{ number_format($school->active_students) }} / {{ number_format($school->staff_count) }}</p>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">{{ number_format($school->parent_count) }}</td>
                                    <td class="px-4 py-3 text-right">{{ number_format($school->parent_coverage_rate, 1) }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                <h2 class="text-base font-bold text-gray-900 dark:text-white">Academic Records</h2>
                <p class="text-sm text-gray-500 dark:text-neutral-400">{{ $academic_availability['message'] }}</p>

                <div class="mt-5 grid grid-cols-2 gap-3">
                    <div class="rounded-lg bg-gray-50 p-4 dark:bg-neutral-800">
                        <p class="text-xs font-medium text-gray-500 dark:text-neutral-400">Subject Catalog</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($subject_count) }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-4 dark:bg-neutral-800">
                        <p class="text-xs font-medium text-gray-500 dark:text-neutral-400">Scored Subjects</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($academic_availability['scored_subjects']) }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-4 dark:bg-neutral-800">
                        <p class="text-xs font-medium text-gray-500 dark:text-neutral-400">Exam Rows</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($exam_record_count) }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-4 dark:bg-neutral-800">
                        <p class="text-xs font-medium text-gray-500 dark:text-neutral-400">Summary Rows</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($exam_summary_count) }}</p>
                    </div>
                </div>

                @if($academic_availability['has_records'])
                    <div class="mt-5 space-y-3">
                        @foreach($top_students as $student)
                            <div class="flex items-center justify-between rounded-lg border border-gray-100 p-3 dark:border-neutral-800">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $student->firstname }} {{ $student->surname }}</p>
                                    <p class="text-xs text-gray-500 dark:text-neutral-500">{{ $student->subject }} · {{ $student->school }}</p>
                                </div>
                                <span class="text-sm font-bold text-blue-600">{{ number_format($student->score, 1) }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="mt-5 rounded-lg border border-dashed border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 dark:border-amber-900/60 dark:bg-amber-950/20 dark:text-amber-200">
                        Academic ranking, attendance, top performers, low performers, and subject trends will become meaningful after exam records are captured.
                    </div>
                @endif
            </div>
        </section>
    </div>
</x-app-layout>
