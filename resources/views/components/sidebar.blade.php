        <!-- Content -->
        <div
            class="h-full overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500">
            <nav class="hs-accordion-group p-3 w-full flex flex-col flex-wrap" data-hs-accordion-always-open>
                <ul class="flex flex-col space-y-1">
                    <x-sidebar-link name="Dashboard" route="dashboard.index" icon="layout-dashboard" />
                    <x-sidebar-link name="Schools" route="schools.index" icon="school" />
                    <x-sidebar-link name="Alumni" route="alumni.index" icon="graduation-cap" />
                    <x-sidebar-link name="Staffs" route="staffs.index" icon="briefcase" />
                    <x-sidebar-link name="Parents" route="parents.index" icon="user-plus" />
                    <x-sidebar-link name="Penalties" route="penalties.index" icon="gavel" />
                </ul>
            </nav>
        </div>
        <!-- End Content -->
