<x-app-layout>
    <x-slot name="title">Security Settings</x-slot>

    <div class="max-w-2xl mx-auto space-y-6">
        <section class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Change Password</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-neutral-400">
                    Ensure your account is using a long, random password to stay secure.
                </p>
            </div>

            @if (session('status') === 'password-updated')
                <div class="mt-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-sm text-emerald-700 dark:bg-emerald-950/20 dark:border-emerald-900/60 dark:text-emerald-200">
                    <div class="flex items-center gap-2">
                        <i data-lucide="check-circle" class="size-4"></i>
                        <span>Password has been successfully updated.</span>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                @csrf

                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-neutral-200">Current Password</label>
                    <input id="current_password" name="current_password" type="password" class="mt-1 block w-full rounded-lg border-gray-200 bg-gray-50 text-sm text-gray-800 focus:border-blue-500 focus:ring-blue-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100" autocomplete="current-password" required>
                    @error('current_password')
                        <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-neutral-200">New Password</label>
                    <input id="password" name="password" type="password" class="mt-1 block w-full rounded-lg border-gray-200 bg-gray-50 text-sm text-gray-800 focus:border-blue-500 focus:ring-blue-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100" autocomplete="new-password" required>
                    @error('password')
                        <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-neutral-200">Confirm New Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full rounded-lg border-gray-200 bg-gray-50 text-sm text-gray-800 focus:border-blue-500 focus:ring-blue-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100" autocomplete="new-password" required>
                    @error('password_confirmation')
                        <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-900 transition-colors">
                        Update Password
                    </button>
                </div>
            </form>
        </section>
    </div>
</x-app-layout>
