<x-app-layout>
    <x-slot name="title">Alumni Records</x-slot>
    <x-slot name="search">
        <div class="hidden md:flex items-center gap-3">
            <form action="{{ route('alumni.index') }}" method="get" class="flex items-center gap-2">
                <select name="school_id" onchange="this.form.submit()" class="py-2 px-3 pe-9 block w-80 bg-white border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400">
                    <option value="">All Schools</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </select>

                <!-- Session Filter -->
                <select name="session_id" onchange="this.form.submit()" class="py-2 px-3 pe-9 block w-44 bg-white border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400">
                    <option value="">All Sessions</option>
                    @foreach($sessions as $session)
                        <option value="{{ $session->id }}" {{ request('session_id') == $session->id ? 'selected' : '' }}>
                            {{ $session->name }}
                        </option>
                    @endforeach
                </select>

                <!-- Search Input -->
                <div class="relative min-w-64">
                    <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-3.5">
                        <i data-lucide="search" class="shrink-0 size-4 text-gray-400 dark:text-white/60"></i>
                    </div>
                    <input value="{{ request('search') }}" onblur="this.form.submit()" type="text" name="search"
                        class="py-2 ps-10 pe-16 block w-full bg-white border-gray-200 rounded-lg text-sm focus:outline-hidden focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 placeholder:text-neutral-400"
                        placeholder="Lookup Alumni">
                </div>
            </form>
            <a href="{{ route('alumni.index') }}" class="p-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i data-lucide="refresh-ccw" class="size-4"></i>
            </a>
        </div>
    </x-slot>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-900 dark:border-neutral-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">Alumni Directory</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-800">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">S/N</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Student ID</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Name</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">School</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Graduation Session</th>
                        <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @forelse($alumni as $index => $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800/50">
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-neutral-200">{{ $alumni->firstItem() + $index }}</td>
                        <td class="px-6 py-4 text-sm font-mono text-blue-600 dark:text-blue-400 font-bold">{{ $item->student_id }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-neutral-200">{{ $item->fname }} {{ $item->sname }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-neutral-400">{{ $item->school }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 rounded-md bg-blue-100 text-blue-700 text-xs font-bold dark:bg-blue-900/30 dark:text-blue-300">
                                {{ $item->session }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-neutral-400">{{ $item->graduation_date }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500 dark:text-neutral-400">
                            No alumni records match the current filters.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-700">
            {{ $alumni->links('vendor.pagination.custom-pg') }}
        </div>
    </div>
</x-app-layout>
