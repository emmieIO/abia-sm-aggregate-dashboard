<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abia State Smart School | Login</title>
    <meta name="description" content="Login to the Abia State Smart School Aggregate Dashboard.">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="flex justify-center items-center h-screen flex-col login-bg">
        <div class="flex items-center justify-center bg-white  p-3 rounded-lg ">
            <img src="{{ asset('dist/images/abia-logo.png') }}" class="w-[200px] h-auto" alt="">
        </div>
        <div
            aria-colindex=""class="mt-7 w-[90%] lg:w-[400px] bg-white border border-gray-200 rounded-lg shadow-2xs dark:bg-neutral-900 dark:border-neutral-700">
            <div class="p-4 sm:p-7">
                <div class="text-center">
                    <h1 class="block text-xl font-bold text-gray-800 dark:text-white">Sign In</h1>
                    <small>Please enter your credentials to access the dashboard.</small>
                </div>

                <div class="mt-5">
                    <form x-data="{ passwordHidden: true, loading: false }" @submit.prevent="loading = true; $el.submit()" method="post" action="{{ route('login') }}">
                    <form x-data="{ passwordHidden: true, loading: false }" onsubmit="loading=true" method="post" action="{{ route('login') }}">
                        @csrf
                        <div class="grid gap-y-4">
                            <!-- Form Group -->
                            <div>
                                <label for="email" class="block text-sm mb-2 dark:text-white">Username</label>
                                <div class="relative">
                                    <input type="text" id="username" name="username"
                                        class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                                        aria-describedby="email-error">
                                </div>
                                @error('username')
                                    <p class="text-xs text-red-600 mt-2" id="email-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- End Form Group -->

                            <!-- Form Group -->
                            <div>
                                <div class="flex flex-wrap justify-between items-center gap-2">
                                    <label for="password" class="block text-sm mb-2 dark:text-white">Password</label>
                                </div>
                                <div class="relative">
                                    <input :type="passwordHidden ? 'password' : 'text'" id="password" name="password"
                                        class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm focus:border-red-500 focus:ring-red-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                                        aria-describedby="password-error">
                                </div>
                                 @error('password')
                                    <p class="text-xs text-red-600 mt-2" id="email-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- End Form Group -->

                            <!-- Checkbox -->
                            <div class="flex items-center">
                                <div class="flex">
                                    <input id="remember-me" name="remember-me" type="checkbox"
                                        @click="passwordHidden = !passwordHidden"
                                        class="shrink-0 mt-0.5 border-gray-200 rounded-sm text-red-600 focus:ring-red-500 dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-red-500 dark:checked:border-red-500 dark:focus:ring-offset-gray-800">
                                </div>
                                <div class="ms-3">
                                    <label for="remember-me" class="text-xs dark:text-white">Show password</label>
                                </div>
                            </div>
                            <!-- End Checkbox -->

                            <button type="submit" :disabled="loading"
                                class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-red-600 text-white hover:bg-red-700 focus:outline-hidden focus:bg-red-700 disabled:opacity-50 disabled:pointer-events-none">
                                <i x-show="loading" data-lucide="loader-circle" class="animate-spin"></i>
                                Access Dashboard <i data-lucide="arrow-right-circle"></i>
                            </button>
                        </div>
                    </form>
                    <!-- End Form -->
                </div>
            </div>
        </div>
    </div>
</body>

</html>
