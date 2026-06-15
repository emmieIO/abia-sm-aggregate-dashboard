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
    <div class="space-y-4">
        <div class="rounded-xl border border-gray-200 bg-white px-5 py-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Schools</h2>
                    <p class="text-sm text-gray-500 dark:text-neutral-400">Open a school to choose a class and view students.</p>
                </div>
                <p class="text-sm font-medium text-gray-500 dark:text-neutral-400">
                    {{ number_format($schools->total()) }} records
                </p>
            </div>
        </div>

        @if($schools->count())
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($schools as $school)
                    <article class="group flex min-h-64 flex-col rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:border-blue-300 hover:shadow-md dark:border-neutral-700 dark:bg-neutral-900 dark:hover:border-blue-500/70">
                        <div class="flex items-start justify-between gap-4">
                            <span class="inline-flex size-11 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-300">
                                <i data-lucide="school" class="size-5"></i>
                            </span>

                            @if($school->status)
                                <span class="rounded-md bg-green-50 px-2 py-1 text-[10px] font-bold uppercase text-green-700 dark:bg-green-950/40 dark:text-green-300">Active</span>
                            @else
                                <span class="rounded-md bg-gray-100 px-2 py-1 text-[10px] font-bold uppercase text-gray-600 dark:bg-neutral-800 dark:text-neutral-300">Disabled</span>
                            @endif
                        </div>

                        <div class="mt-4 min-w-0">
                            <a href="{{ route('schools.students', $school->school_id) }}" class="block text-lg font-semibold leading-6 text-gray-900 hover:text-blue-600 dark:text-white dark:hover:text-blue-300">
                                {{ $school->name }}
                            </a>
                            <p class="mt-1 font-mono text-sm font-semibold text-blue-600 dark:text-blue-400">{{ $school->school_id }}</p>
                        </div>

                        <dl class="mt-4 grid gap-3 text-sm">
                            <div class="flex items-start gap-3">
                                <i data-lucide="map-pin" class="mt-0.5 size-4 shrink-0 text-gray-400"></i>
                                <dd class="min-w-0 text-gray-600 dark:text-neutral-300">
                                    {{ $school->lga_name ?: 'LGA unavailable' }}
                                </dd>
                            </div>
                            <div class="flex items-start gap-3">
                                <i data-lucide="phone" class="mt-0.5 size-4 shrink-0 text-gray-400"></i>
                                <dd class="min-w-0 text-gray-600 dark:text-neutral-300">
                                    {{ $school->phone ?: 'No phone number' }}
                                </dd>
                            </div>
                            <div class="flex items-start gap-3">
                                <i data-lucide="mail" class="mt-0.5 size-4 shrink-0 text-gray-400"></i>
                                <dd class="min-w-0 truncate text-gray-600 dark:text-neutral-300">
                                    {{ $school->email ?: 'No email address' }}
                                </dd>
                            </div>
                        </dl>

                        <div class="mt-auto pt-5">
                            <a href="{{ route('schools.students', $school->school_id) }}" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-900">
                                View classes
                                <i data-lucide="arrow-right" class="size-4"></i>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="rounded-xl border border-gray-200 bg-white px-5 py-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                {{ $schools->links('vendor.pagination.custom-pg') }}
            </div>
        @else
            <div class="rounded-xl border border-dashed border-gray-200 bg-white p-8 text-center shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <i data-lucide="school" class="mx-auto size-9 text-gray-400"></i>
                <h2 class="mt-3 text-base font-semibold text-gray-900 dark:text-white">No schools found</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-neutral-400">Try adjusting the current filters.</p>
            </div>
        @endif
        </div>
</x-app-layout>
