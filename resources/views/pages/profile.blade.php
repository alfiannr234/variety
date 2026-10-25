<x-layout>
    <x-slot:title>Profil Saya - Variety</x-slot:title>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="flex flex-col md:flex-row gap-8">


            <x-user-sidebar />

            <div class="flex-1">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100">
                        <h2 class="text-xl font-extrabold text-slate-900">Profil Saya</h2>
                    </div>

                    <form action="{{ route('user.profile.update') }}" method="POST" class="p-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label
                                    class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Alamat
                                    Email</label>
                                <input type="email" value="{{ $user->email }}" disabled
                                    class="w-full px-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-sm text-slate-500 cursor-not-allowed">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama
                                    Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nomor
                                    Telepon</label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">

                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit"
                                class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-emerald-600/25 transition">Simpan
                                Profil</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-layout>