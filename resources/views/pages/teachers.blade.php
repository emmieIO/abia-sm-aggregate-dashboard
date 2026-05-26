<x-app-layout>
    <x-slot name="title">Staff Records</x-slot>
    <x-slot name="search">
        <div class="hidden md:flex items-center gap-3">
            <form action="{{ route('staffs.index') }}" method="get" class="flex items-center gap-2 flex-wrap">
                <!-- School Filter -->
                <select name="school_id" onchange="this.form.submit()" class="py-2 px-3 pe-9 block bg-white border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400">
                    <option value="">All Schools</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                    @endforeach
                </select>

                <!-- Department Filter -->
                <select name="depart_id" onchange="this.form.submit()" class="py-2 px-3 pe-9 block bg-white border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('depart_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>

                <!-- Designation Filter -->
                <select name="designation" onchange="this.form.submit()" class="py-2 px-3 pe-9 block bg-white border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400">
                    <option value="">All Roles</option>
                    @foreach($designations as $des)
                        <option value="{{ $des->id }}" {{ request('designation') == $des->id ? 'selected' : '' }}>{{ $des->name }}</option>
                    @endforeach
                </select>

                <!-- Gender Filter -->
                <select name="sex" onchange="this.form.submit()" class="py-2 px-3 pe-9 block bg-white border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400">
                    <option value="">All Gender</option>
                    <option value="Male" {{ request('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ request('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                </select>

                <!-- Search Input -->
                <div class="relative min-w-64">
                    <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-3.5">
                        <i data-lucide="search" class="shrink-0 size-4 text-gray-400 dark:text-white/60"></i>
                    </div>
                    <input value="{{ request('search') }}" onblur="this.form.submit()" type="text" name="search"
                        class="py-2 ps-10 pe-16 block w-full bg-white border-gray-200 rounded-lg text-sm focus:outline-hidden focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 placeholder:text-neutral-400"
                        placeholder="Lookup Staff">
                </div>
            </form>
            <a href="{{ route('staffs.index') }}" class="p-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i data-lucide="refresh-ccw" class="size-4"></i>
            </a>
        </div>
    </x-slot>

    <!-- Table Section -->
    <div class="">
        <div class="flex flex-col">
            <div class="-m-1.5 overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-900 dark:border-neutral-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">Staff Records</h2>
                        </div>

                        <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                            <thead class="bg-gray-50 dark:bg-neutral-800">
                                <tr class="whitespace-nowrap">
                                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-500">S/N</th>
                                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-500">Staff ID</th>
                                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-500">Name</th>
                                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-500">Role</th>
                                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-500">Department</th>
                                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-500">School</th>
                                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-500">Gender</th>
                                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-500">Email</th>
                                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-500">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                @foreach ($staffs as $index => $staff)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800/50 transition-colors">
                                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-neutral-200">{{ $staffs->firstItem() + $index }}</td>
                                        <td class="px-6 py-4 text-sm font-mono font-bold text-blue-600 dark:text-blue-400">{{ $staff->staff_id }}</td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-neutral-200">{{ $staff->fname }} {{ $staff->sname }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $staff->designation_name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $staff->department }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $staff->school }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $staff->sex }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $staff->email }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            @if(str_contains(strtolower($staff->status), 'active') || str_contains(strtolower($staff->status), 'enabled'))
                                                <span class="px-2 py-1 rounded-md bg-green-50 text-green-700 text-[10px] font-bold uppercase">Active</span>
                                            @else
                                                <span class="px-2 py-1 rounded-md bg-rose-50 text-rose-700 text-[10px] font-bold uppercase">Disabled</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-700">
                            {{ $staffs->links('vendor.pagination.custom-pg') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
