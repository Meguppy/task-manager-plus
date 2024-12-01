<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div>
    <div class="container col-6">
        <x-input-error :messages="$errors->get('email')" class="text-danger" />
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="row bg-light p-5">
                <!-- Email Address -->
                <div class="mb-3 col-12">
                    <x-input-label for="email" :value="__('Email')" class="form-label" />
                    <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                </div>

                <!-- Password -->
                <div class="mb-3 col-12">
                    <x-input-label for="password" :value="__('Password')" class="form-label" />

                    <x-text-input id="password" class="form-control"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block my-2">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                {{-- <div class="flex items-center justify-end mt-4">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div> --}}


                <div class="mb-3 col-12">
                    <x-primary-button class="btn btn-primary px-5">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>

            </div>
        </form>
    </div>
</x-guest-layout>
