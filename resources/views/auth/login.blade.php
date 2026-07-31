<x-guest-layout>

    <x-auth-session-status
        class="mb-4 text-green-500 text-center"
        :status="session('status')"
    />

    <form method="POST" action="{{ route('login') }}" style="max-width: 320px; margin: 0 auto;">
        @csrf

        <!-- Título -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-slate-800">
                Bienvenido
            </h1>

            <p class="text-slate-500 mt-2">
                Accede a tu cuenta
            </p>
        </div>

        <!-- Email -->
        <div class="mb-5">
            <x-input-label
                for="email"
                :value="__('Email')"
                class="text-slate-700 font-medium"
            />

            <x-text-input
                id="email"
                class="block mt-2 w-full rounded-xl border-purple-200 bg-white/50 text-slate-800 transition-shadow shadow-sm custom-input"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
            />

            <x-input-error
                :messages="$errors->get('email')"
                class="mt-2"
            />
        </div>

        <!-- Password -->
        <div class="mb-5">
            <x-input-label
                for="password"
                :value="__('Password')"
                class="text-slate-700 font-medium"
            />

            <x-text-input
                id="password"
                class="block mt-2 w-full rounded-xl border-purple-200 bg-white/50 text-slate-800 transition-shadow shadow-sm custom-input"
                type="password"
                name="password"
                required
                autocomplete="current-password"
            />

            <x-input-error
                :messages="$errors->get('password')"
                class="mt-2"
            />
        </div>

        <!-- Remember -->
        <div class="block mb-6 text-center">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input
                    id="remember_me"
                    type="checkbox"
                    class="rounded border-teal-300 text-teal-600 focus:ring-teal-500 shadow-sm transition-colors"
                    name="remember"
                >

                <span class="ms-2 text-sm text-slate-600 font-medium">
                    {{ __('Remember me') }}
                </span>
            </label>
        </div>

        <!-- Botones -->
        <div class="flex flex-col items-center justify-center mt-8 gap-3">

            <button
                type="submit"
                class="w-full btn-primary" style="max-width: 220px;">
                {{ __('Iniciar Sesión') }}
            </button>

            @if (Route::has('password.request'))
                <a
                    class="text-sm text-teal-600 hover:text-teal-800 font-medium transition-colors mt-2"
                    href="{{ route('password.request') }}"
                >
                    ¿Olvidaste tu contraseña?
                </a>
            @endif

        </div>

    </form>

</x-guest-layout>