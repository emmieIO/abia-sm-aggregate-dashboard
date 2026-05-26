<x-app-layout>
    <x-slot name="title">Schools Records</x-slot>
    <x-slot name="search">
        <div class="hidden md:flex items-center gap-3">
            <form action="{{ route('schools.index') }}" method="get" class="flex items-center gap-2">
                <!-- LGA Filter -->
                <select name="lga" onchange="this.form.submit()" class="py-2 px-3 pe-9 block bg-white border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400">
                    <option value="">All LGAs</option>
                    @foreach($lgas as $lga)
                        <option value="{{ $lga->id }}" {{ request('lga') == $lga->id ? 'selected' : '' }}>{{ $lga->name }}</option>
                    @endforeach
                </select>

                <!-- Status Filter -->
                <select name="status" onchange="this.form.submit()" class="py-2 px-3 pe-9 block bg-white border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400">
                    <option value="">All Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Disabled</option>
                </select>

                <!-- Search Input -->
                <div class="relative min-w-64">
                    <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-3.5">
                        <i data-lucide="search" class="shrink-0 size-4 text-gray-400 dark:text-white/60"></i>
                    </div>
                    <input value="{{ request('search') }}" onblur="this.form.submit()" type="text" name="search"
                        class="py-2 ps-10 pe-16 block w-full bg-white border-gray-200 rounded-lg text-sm focus:outline-hidden focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 placeholder:text-neutral-400"
                        placeholder="Lookup Schools">
                </div>
            </form>
            <a href="{{ route('schools.index') }}" class="p-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i data-lucide="refresh-ccw" class="size-4"></i>
            </a>
        </div>
    </x-slot>
    <!-- Table Section -->
    <div class="">
        <!-- Card -->
        <div class="flex flex-col">
            <div class="-m-1.5 overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">
                    <div
                        class="bg-white border border-gray-200 rounded-xl shadow-2xs overflow-hidden dark:bg-neutral-900 dark:border-neutral-700">
                        <!-- Header -->
                        <div
                            class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                                    Schools Records
                                </h2>
                            </div>
                        </div>
                        <!-- End Header -->
                        <!-- Table -->
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                            <thead
                                class="bg-gray-50 divide-y divide-gray-200 dark:bg-neutral-800 dark:divide-neutral-700 whitespace-nowrap">
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
                                            School Name
                                        </span>
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span
                                            class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                            School ID
                                        </span>
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span
                                            class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                            Phone
                                        </span>
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span
                                            class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                            Email
                                        </span>
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span
                                            class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                            Address
                                        </span>
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span
                                            class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                            Local Government Area
                                        </span>
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span
                                            class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                            Status
                                        </span>
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700  text-color">
                                @forelse ($schools as $index => $school)
                                        <tr>
                                            <td class="h-px w-auto whitespace-nowrap">
                                                <div class="px-6 py-2 flex items-center gap-x-3">
                                                    <span
                                                        class="text-sm text-black dark:text-white decoration-2">{{ $schools->firstItem() + $index }}</span>
                                                </div>
                                            </td>
                                            <td class="h-px w-auto whitespace-nowrap">
                                                <div class="px-6 py-2 flex items-center gap-x-3">
                                                    <span
                                                        class="text-sm text-black dark:text-white decoration-2">{{ $school->name }}</span>
                                                </div>
                                            </td>
                                            <td class="h-px w-auto whitespace-nowrap">
                                                <div class="px-6 py-2">
                                                    <span
                                                        class="font-semibold text-sm text-gray-800 dark:text-neutral-200">{{ $school->school_id }}</span>

                                                </div>
                                            </td>
                                            <td class="h-px w-auto whitespace-nowrap">
                                                <div class="px-6 py-2">
                                                    <span
                                                        class="text-sm text-gray-800 dark:text-neutral-200">{{ $school->phone }}</span>
                                                </div>
                                            </td>
                                            <td class="h-px w-auto whitespace-nowrap">
                                                <div class="px-6 py-2">
                                                    <span
                                                        class="text-sm text-gray-800 dark:text-neutral-200">{{ $school->email }}</span>
                                                </div>
                                            </td>
                                            <td class="h-px w-auto whitespace-nowrap">
                                                <div class="px-6 py-2">
                                                    <span
                                                        class="text-sm text-gray-800 dark:text-neutral-200">{{ $school->address }}</span>
                                                </div>
                                            </td>
                                            <td class="h-px w-auto whitespace-nowrap">
                                                <div class="px-6 py-2">
                                                    <span
                                                        class="text-sm text-gray-800 dark:text-neutral-200">{{ $school->lga_name }}</span>
                                                </div>
                                            </td>
                                            <td class="h-px w-auto whitespace-nowrap">
                                                <div class="px-6 py-2">
                                                    <span
                                                        class="text-sm text-gray-800 dark:text-neutral-200">{{ $school->status ? 'Active' : 'Disabled' }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-10 text-center text-sm text-gray-500 dark:text-neutral-400">
                                            No school records match the current filters.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!-- End Table -->

                        <!-- Footer -->
                        <div
                            class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-t border-gray-200 dark:border-neutral-700">
                            <div>
                                <div class="flex gap-x-2 space-between w-full">
                                    {{ $schools->links('vendor.pagination.custom-pg') }}
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
    <!-- End Table Section -->
</x-app-layout>
