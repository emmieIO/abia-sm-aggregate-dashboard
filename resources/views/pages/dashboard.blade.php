<x-app-layout>
    <x-slot name="title">Dashboard Page</x-slot>
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-blue-600 text-white p-8 space-y-3 rounded-2xl flex flex-col justify-between">
            <h2 class="text-lg font-bold">Total Number of Schools</h2>
            <p class="text-xl font-extrabold">{{ $school_count }}</p>
            <small class="text-md">Benefiting from the initiative</small>
        </div>
        <div class="bg-green-400 text-white p-8 space-y-3 rounded-2xl flex flex-col justify-between">
            <h2 class="text-lg font-bold">Total Number of Students</h2>
            <p class="text-xl font-extrabold">{{ $student_count }}</p>
            <small class="text-md">Benefiting from the initiative</small>
        </div>
        <div class="bg-red-400 text-white p-8 space-y-3 rounded-2xl flex flex-col justify-between">
            <h2 class="text-lg font-bold">Total Number of Teachers</h2>
            <p class="text-xl font-extrabold">{{ $staff_count }}</p>
            <small class="text-md">Benefiting from the initiative</small>
        </div>
        <div class="bg-teal-400 text-white p-8 space-y-3 rounded-2xl flex flex-col justify-between">
            <h2 class="text-lg font-bold">Total Number of Parents</h2>
            <p class="text-xl font-extrabold">{{ $parent_count }}</p>
            <small class="text-md">Benefiting from the initiative</small>
        </div>
    </div>
    <div class="grid md:grid-cols-1 lg:grid-cols-2">
        <div id="genderChart" class="font-sans" data-options="{{ $gender_distribution }}"></div>
        <div id="ageChart" data-options="{{ $age_distribution }}"></div>
        <div class="">
            <div class="max-w-sm space-y-3 my-3">
                <form action="" method="get">
                    <select id="top-subjects"
                        class="py-2 px-3 pe-9 block w-full rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none border-black">
                        <option selected="">Fileter by subject</option>
                        @foreach ($subjects as $subject)
                            <option data-route="{{ route('students.top-by-subject', ['subject' => $subject->id]) }}"
                                value="{{ $subject->id }}">{{ Str::ucfirst($subject->name) }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="cols-span-2" id="topStudents" data-options="{{ $top_students }}"></div>
        </div>
        <div class="">
            <div class="max-w-sm space-y-3 my-3">
                <form action="" method="get">
                    <select id="least-subjects"
                        class="py-2 px-3 pe-9 block w-full rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none border-black">
                        <option selected="">Fileter by subject</option>
                        @foreach ($subjects as $subject)
                            <option data-route="{{ route('students.low-by-subject', ['subject' => $subject->id]) }}"
                                value="{{ $subject->id }}">{{ Str::ucfirst($subject->name) }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="cols-span-2" id="leastStudents" data-options="{{ $least_students }}"></div>
        </div>

    </div>


</x-app-layout>
