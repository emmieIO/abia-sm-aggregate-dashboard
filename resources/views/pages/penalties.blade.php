<x-app-layout>
    <x-slot name="title">Student Penalties</x-slot>
    <x-slot name="search">
        <div class="hidden md:flex items-center gap-3">
            <form action="{{ route('penalties.index') }}" method="get" class="flex items-center gap-2">
                <select name="school_id" onchange="this.form.submit()" class="py-2 px-3 pe-9 block bg-white border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400">
                    <option value="">All Schools</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </select>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-3.5">
                        <i data-lucide="search" class="shrink-0 size-4 text-gray-400 dark:text-white/60"></i>
                    </div>
                    <input value="{{ request('search') }}" onblur="this.form.submit()" type="text" name="search"
                        class="py-2 ps-10 pe-16 block w-full bg-white border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400"
                        placeholder="Search Offence or Student">
                </div>
            </form>
            <a href="{{ route('penalties.index') }}" class="p-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i data-lucide="refresh-ccw" class="size-4"></i>
            </a>
        </div>
    </x-slot>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-900 dark:border-neutral-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200 text-rose-600">Student Discipline Records</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-800">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-bold uppercase text-gray-500">S/N</th>
                        <th class="px-6 py-3 text-start text-xs font-bold uppercase text-gray-500">Student ID</th>
                        <th class="px-6 py-3 text-start text-xs font-bold uppercase text-gray-500">Student Name</th>
                        <th class="px-6 py-3 text-start text-xs font-bold uppercase text-gray-500">Offence</th>
                        <th class="px-6 py-3 text-start text-xs font-bold uppercase text-gray-500">Punishment</th>
                        <th class="px-6 py-3 text-start text-xs font-bold uppercase text-gray-500">School</th>
                        <th class="px-6 py-3 text-start text-xs font-bold uppercase text-gray-500">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @forelse($penalties as $index => $penalty)
                    <tr class="hover:bg-rose-50/30 dark:hover:bg-rose-900/10 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-neutral-200">{{ $penalties->firstItem() + $index }}</td>
                        <td class="px-6 py-4 text-sm font-mono text-rose-600 font-bold">{{ $penalty->student_id }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $penalty->fname }} {{ $penalty->sname }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-neutral-200">
                            <span class="font-medium">{{ $penalty->offence }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm italic text-gray-500">{{ $penalty->punishment }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $penalty->school }}</td>
                        <td class="px-6 py-4 text-sm text-gray-400 font-bold uppercase">{{ $penalty->offence_date }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-sm text-gray-500 dark:text-neutral-400">
                            No discipline records match the current filters.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-700">
            {{ $penalties->links('vendor.pagination.custom-pg') }}
        </div>
    </div>
</x-app-layout>
