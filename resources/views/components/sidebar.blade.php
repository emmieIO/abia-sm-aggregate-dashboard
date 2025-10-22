        <!-- Content -->
        <div
            class="h-full overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500">
            <nav class="hs-accordion-group p-3 w-full flex flex-col flex-wrap" data-hs-accordion-always-open>
                <ul class="flex flex-col space-y-1">
                    <x-sidebar-link name="Dashboard" route="dashboard.index" icon="app-window" />
                    <x-sidebar-link name="Schools" route="schools.index" icon="School" />
                    <x-sidebar-link name="Students" route="students.index" icon="graduation-cap" />
                    <x-sidebar-link name="Staffs" route="staffs.index" icon="book-type" />
                    <x-sidebar-link name="Parents" route="parents.index" icon="users" />
                    {{-- <x-sidebar-link name="Reports" route="reports.index" icon="users" /> --}}
                </ul>
            </nav>
        </div>
        <!-- End Content -->
