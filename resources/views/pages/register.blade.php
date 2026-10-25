<x-layout :hideNavbar="true">
    <x-slot:title>Daftar Akun - Variety</x-slot:title>

    <div class="min-h-screen flex items-center justify-center bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">

        <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-sm border border-slate-200"
            x-data="{ step: 1, email: '', showPass: false, showConfirm: false }">

            <div class="text-center mb-8">
                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Daftar</h2>
            </div>

            <form action="/register" method="POST">
                @csrf

                <!-- LANGKAH 1: Masukkan Email -->
                <div x-show="step === 1" x-transition>
                    <div class="mb-4">
                        <input type="email" name="email" x-model="email" required placeholder="Alamat Email"
                            class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                        @error('email')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="button" @click="if(email) step = 2"
                        class="w-full py-3 bg-emerald-600 text-white text-sm font-bold rounded-lg shadow-sm hover:bg-emerald-700 transition"
                        :class="!email ? 'opacity-50 cursor-not-allowed' : ''" :disabled="!email">
                        Selanjutnya
                    </button>
                </div>

                <!-- LANGKAH 2: Buat Password & Konfirmasi -->
                <div x-show="step === 2" style="display: none;" x-transition>

                    <!-- Tombol Kembali -->
                    <button type="button" @click="step = 1"
                        class="text-sm font-bold text-slate-500 hover:text-emerald-600 mb-4 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                        Kembali
                    </button>

                    <!-- Info Email yang didaftarkan -->
                    <p class="text-xs text-slate-500 mb-4">Mendaftar dengan: <span class="font-bold text-slate-800"
                            x-text="email"></span></p>

                    <!-- Input Password -->
                    <div class="relative mb-4">
                        <input :type="showPass ? 'text' : 'password'" name="password" required minlength="6"
                            placeholder="Buat Password"
                            class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                        <button type="button" @click="showPass = !showPass"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-emerald-600">
                            <svg x-show="!showPass" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            <svg x-show="showPass" class="w-5 h-5" style="display: none;" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                </path>
                            </svg>
                        </button>
                    </div>

                    <!-- Input Konfirmasi Password -->
                    <div class="relative mb-6">
                        <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required
                            minlength="6" placeholder="Konfirmasi Password"
                            class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                        <button type="button" @click="showConfirm = !showConfirm"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-emerald-600">
                            <svg x-show="!showConfirm" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            <svg x-show="showConfirm" class="w-5 h-5" style="display: none;" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                </path>
                            </svg>
                        </button>
                    </div>

                    <!-- Tombol Submit -->
                    <button type="submit"
                        class="w-full py-3 bg-emerald-600 text-white text-sm font-bold rounded-lg shadow-sm hover:bg-emerald-700 transition">
                        Daftar & Masuk
                    </button>
                </div>
            </form>
            <div class="relative flex items-center py-6">
                <div class="flex-grow border-t border-slate-200"></div>
                <span class="shrink-0 px-4 text-xs text-slate-400 uppercase tracking-widest">ATAU</span>
                <div class="flex-grow border-t border-slate-200"></div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Facebook -->
                <button type="button"
                    class="flex items-center justify-center gap-2 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition">
                    <svg class="w-5 h-5 text-[#1877F2]" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                    </svg>
                    <span class="text-sm font-medium text-slate-700">Facebook</span>
                </button>

                <!-- Google -->
                <button type="button"
                    class="flex items-center justify-center gap-2 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path
                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.58c2.08-1.92 3.27-4.74 3.27-8.09z"
                            fill="#4285F4" />
                        <path
                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.58-2.77c-.98.66-2.23 1.06-3.7 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                            fill="#34A853" />
                        <path
                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                            fill="#FBBC05" />
                        <path
                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                            fill="#EA4335" />
                    </svg>
                    <span class="text-sm font-medium text-slate-700">Google</span>
                </button>
            </div>

            <div class="mt-8 text-center text-sm text-slate-600">
                Sudah punya akun?
                <a href="/login" class="text-emerald-600 font-bold hover:text-emerald-700 transition">Log In</a>
            </div>
        </div>
    </div>
</x-layout>