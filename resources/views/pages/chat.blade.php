<x-layout>
    <x-slot:title>Pesan - Variety</x-slot:title>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 h-[calc(100vh-100px)]">
        <div class="flex h-full bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

            <div class="w-full md:w-1/3 border-r border-slate-200 flex flex-col bg-slate-50">
                <div class="p-4 border-b border-slate-200 bg-white">
                    <h2 class="text-lg font-extrabold text-slate-900">Pesan Saya</h2>
                </div>

                <div class="flex-1 overflow-y-auto">
                    @if($chats->isEmpty())
                        <p class="text-center text-sm text-slate-500 mt-10">Belum ada obrolan.</p>
                    @else
                        @foreach($chats as $c)
                            @php
                                $lawanBicara = ($c->user_id === $user->id) ? $c->seller : $c->user;
                            @endphp

                            <a href="{{ route('chat.index', ['room' => $c->id]) }}"
                                class="flex items-center gap-3 p-4 border-b border-slate-100 hover:bg-emerald-50 transition {{ (isset($activeChat) && $activeChat->id == $c->id) ? 'bg-emerald-50 border-l-4 border-l-emerald-500' : 'bg-white' }}">

                                <div
                                    class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold shrink-0">
                                    {{ substr($lawanBicara->name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-bold text-slate-900 truncate">{{ $lawanBicara->name }}</h4>
                                    <p class="text-xs text-slate-500 truncate">
                                        {{ $c->latestMessage ? $c->latestMessage->message : 'Belum ada pesan' }}
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="hidden md:flex flex-1 flex-col bg-white relative">
                @if($activeChat)
                    @php
                        $lawan = ($activeChat->user_id === $user->id) ? $activeChat->seller : $activeChat->user;
                    @endphp

                    <div class="p-4 border-b border-slate-200 flex items-center gap-3 bg-white shadow-sm z-10">
                        <div
                            class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold">
                            {{ substr($lawan->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900">{{ $lawan->name }}</h3>
                            <p class="text-[11px] text-emerald-600 font-bold uppercase tracking-wider">
                                {{ $activeChat->seller_id === $lawan->id ? 'Penjual' : 'Pembeli' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-slate-50/50">
                        @foreach($messages as $msg)
                            <div class="flex {{ $msg->sender_id === $user->id ? 'justify-end' : 'justify-start' }}">
                                <div
                                    class="max-w-[75%] rounded-2xl px-4 py-2 text-sm {{ $msg->sender_id === $user->id ? 'bg-emerald-600 text-white rounded-br-none' : 'bg-white border border-slate-200 text-slate-800 rounded-bl-none shadow-sm' }}">
                                    {{ $msg->message }}
                                    <span
                                        class="block text-[10px] mt-1 text-right {{ $msg->sender_id === $user->id ? 'text-emerald-200' : 'text-slate-400' }}">
                                        {{ $msg->created_at->format('H:i') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="p-4 bg-white border-t border-slate-200">
                        <form action="{{ route('chat.store', $activeChat->id) }}" method="POST" class="flex gap-2">
                            @csrf
                            <input type="text" name="message" required placeholder="Tulis pesan..." autocomplete="off"
                                class="flex-1 px-4 py-2.5 bg-slate-100 border border-transparent rounded-xl text-sm focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                            <button type="submit"
                                class="px-5 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition flex items-center justify-center shadow-sm">
                                <svg class="w-5 h-5 rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex-1 flex flex-col items-center justify-center text-slate-400">
                        <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z">
                            </path>
                        </svg>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layout>