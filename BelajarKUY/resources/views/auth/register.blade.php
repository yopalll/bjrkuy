<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Pilihan Role: Siswa (default) atau Instruktur --}}
        <div class="mb-4">
            <x-input-label :value="__('Daftar sebagai')" />
            <div class="grid grid-cols-2 gap-3 mt-2">
                {{-- Kartu Siswa --}}
                <label for="role_student"
                    class="flex flex-col items-center p-3 border-2 rounded-lg cursor-pointer transition
                           peer-checked:border-indigo-500 has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50
                           border-gray-200 hover:border-indigo-300 text-center">
                    <input type="radio" id="role_student" name="role" value="user"
                        class="peer sr-only" {{ old('role', 'user') === 'user' ? 'checked' : '' }} />
                    <span class="text-2xl mb-1">🎓</span>
                    <span class="font-semibold text-gray-700 text-sm">Siswa</span>
                    <span class="text-xs text-gray-400">Belajar dari kursus</span>
                </label>
                {{-- Kartu Instruktur --}}
                <label for="role_instructor"
                    class="flex flex-col items-center p-3 border-2 rounded-lg cursor-pointer transition
                           has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50
                           border-gray-200 hover:border-indigo-300 text-center">
                    <input type="radio" id="role_instructor" name="role" value="instructor"
                        class="peer sr-only" {{ old('role') === 'instructor' ? 'checked' : '' }} />
                    <span class="text-2xl mb-1">📖</span>
                    <span class="font-semibold text-gray-700 text-sm">Instruktur</span>
                    <span class="text-xs text-gray-400">Buat & jual kursus</span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Sudah punya akun?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Daftar') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

