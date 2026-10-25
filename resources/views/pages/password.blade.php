<x-layout>
    <x-slot:title>Ubah Password - Variety</x-slot:title>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col md:flex-row gap-8">
            <x-user-sidebar />

            <div class="flex-1">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-6">
                    <div class="pb-4 border-b border-slate-100 mb-6">
                        <h2 class="text-xl font-extrabold text-slate-900">Ubah Password</h2>
                    </div>

                    @if(session('success'))
                        <div
                            class="mb-6 p-4 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl font-bold text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('user.password.update') }}" method="POST" class="max-w-md">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Password
                                    Saat Ini</label>
                                <input type="password" name="current_password" required
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none">
                                @error('current_password')
                                <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Password
                                    Baru</label>
                                <input type="password" name="password" required minlength="6"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none">
                                @error('password')
                                <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Konfirmasi
                                    Password Baru</label>
                                <input type="password" name="password_confirmation" required minlength="6"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none">
                            </div>
                        </div>

                        <div class="mt-8">
                            <button type="submit"
                                class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-emerald-600/25 transition">Perbarui
                                Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>