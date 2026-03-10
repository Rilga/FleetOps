<x-app-layout>
    <div x-data="{ 
        showModal: false, 
        taskId: null, 
        jobName: '', 
        remarks: '' 
    }" 
    @open-complete-modal.window="showModal = true; taskId = $event.detail.id; jobName = $event.detail.name"
    class="py-8 bg-[#0f172a] min-h-screen text-slate-300 font-sans">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-6 bg-slate-900 border border-slate-800 p-8 rounded-2xl shadow-xl">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="bg-blue-600 text-white text-[9px] font-black px-2 py-1 rounded uppercase tracking-widest">Planned Maintenance</span>
                        <span class="text-slate-500 text-xs font-bold uppercase tracking-widest">{{ $machinery->maker }}</span>
                    </div>
                    <h2 class="text-4xl font-black text-white uppercase tracking-tighter">{{ $machinery->name }}</h2>
                    <p class="text-slate-500 text-sm mt-1 uppercase tracking-widest italic">{{ $machinery->model }} / {{ $machinery->serial_number }}</p>
                    <div class="mt-6">
                        <a href="{{ route('user.maintenance_history', $machinery->id) }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600/10 border border-blue-600/30 rounded-xl text-[10px] font-black text-blue-500 uppercase hover:bg-blue-600 hover:text-white transition-all shadow-lg">
                            <i class="fas fa-history"></i> Full Maintenance Log
                        </a>
                    </div>
                </div>
                <div class="text-right border-l border-slate-800 pl-8">
                    <p class="text-4xl font-mono font-black text-white leading-none tracking-tighter">{{ number_format($machinery->current_rh, 1) }}</p>
                    <p class="text-[10px] text-blue-500 font-bold uppercase mt-2 tracking-[0.2em]">Total Running Hours</p>
                </div>
            </div>

            <div class="hidden lg:grid grid-cols-12 gap-4 px-6 mb-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                <div class="col-span-1">Interval</div>
                <div class="col-span-2">System / Subsystem</div>
                <div class="col-span-4">Job Details</div>
                <div class="col-span-2 text-center">Next Due RH</div>
                <div class="col-span-2 text-center">Status</div>
                <div class="col-span-1"></div>
            </div>

            <div class="space-y-4">
                @foreach($machinery->maintenanceTasks->sortBy('interval') as $task)
                <div class="bg-slate-900 border border-slate-800 rounded-xl p-6 transition-all hover:border-blue-600/30 group">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-center">
                        <div class="col-span-1">
                            <div class="bg-[#0f172a] inline-block px-3 py-1 rounded border border-slate-800 lg:border-none lg:p-0 lg:bg-transparent">
                                <span class="text-lg font-mono font-black text-white">{{ $task->interval }}</span>
                                <span class="text-[9px] font-bold text-slate-600 uppercase">Hrs</span>
                            </div>
                        </div>

                        <div class="col-span-2">
                            <p class="text-[10px] font-bold text-blue-500 uppercase truncate">{{ $task->system }}</p>
                            <p class="text-xs font-bold text-slate-400 truncate">{{ $task->subsystem }}</p>
                        </div>

                        <div class="col-span-4">
                            <h4 class="text-sm font-bold text-white leading-relaxed">{{ $task->job_details }}</h4>
                            
                            <div class="flex gap-2 mt-3 overflow-x-auto pb-1 custom-scrollbar">
                                @php
                                    // Ambil 5 riwayat terbaru, urutkan dari yang lama ke baru 
                                    // agar tampilan di layar tetap CH-1 (paling lama) ke CH-5 (paling baru)
                                    $histories = $task->histories->take(5)->reverse();
                                    $slotCount = 1;
                                @endphp

                                @foreach($histories as $history)
                                    <div class="flex-shrink-0 px-2 py-1 rounded bg-slate-800/50 border border-emerald-900/50 text-[8px] font-mono text-emerald-500 group/history relative">
                                        CH-{{ $slotCount++ }}: {{ number_format($history->done_at_rh, 0) }}
                                        
                                        <div class="hidden group-hover/history:block absolute bottom-full mb-2 left-0 w-32 bg-black text-white p-2 rounded text-[7px] z-50 shadow-xl border border-slate-700">
                                            {{ $history->completion_date }}<br>
                                            "{{ $history->remarks }}"
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Jika riwayat belum sampai 5, tampilkan slot kosong (---) --}}
                                @for($i = $histories->count(); $i < 5; $i++)
                                    <div class="flex-shrink-0 px-2 py-1 rounded bg-slate-800/50 border border-slate-700 text-[8px] font-mono text-slate-600">
                                        CH-{{ $slotCount++ }}: ---
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <div class="col-span-2 text-center">
                            <p class="text-lg font-mono font-bold {{ $task->status == 'critical' ? 'text-red-500' : 'text-slate-300' }}">
                                {{ number_format($task->next_due_rh, 1) }}
                            </p>
                            <p class="text-[9px] font-bold text-slate-600 uppercase">Target Reading</p>
                        </div>

                        <div class="col-span-2 flex justify-center">
                            <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border
                                {{ $task->status == 'critical' ? 'bg-red-500/10 border-red-500 text-red-500' : 
                                   ($task->status == 'warning' ? 'bg-yellow-500/10 border-yellow-500 text-yellow-500' : 
                                   'bg-emerald-500/10 border-emerald-500 text-emerald-500') }}">
                                {{ $task->status }}
                            </span>
                        </div>

                        <div class="col-span-1 flex justify-end">
                            <button @click="$dispatch('open-complete-modal', { id: {{ $task->id }}, name: '{{ addslashes($task->job_details) }}' })" 
                                    class="bg-blue-600 hover:bg-emerald-600 text-white p-3 rounded-lg transition-all shadow-lg shadow-blue-900/10">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
                <div class="flex items-center justify-center min-h-screen px-4">
                    <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" @click="showModal = false"></div>

                    <div class="relative bg-slate-900 border border-slate-800 rounded-2xl max-w-lg w-full p-8 shadow-2xl">
                        <h3 class="text-xl font-bold text-white uppercase tracking-tight mb-2">Complete Task</h3>
                        <p class="text-xs text-slate-500 mb-6" x-text="jobName"></p>

                        <form :action="'{{ route('user.complete_task', ['task_id' => ':id']) }}'.replace(':id', taskId)" method="POST">
                            @csrf
                            <div class="mb-6">
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Pekerjaan / Remarks</label>
                                <textarea name="remarks" required class="w-full bg-[#0f172a] border-slate-800 rounded-lg text-white text-sm focus:border-blue-600 focus:ring-0" rows="4" placeholder="Tuliskan detail pekerjaan yang telah dilakukan..."></textarea>
                            </div>

                            <div class="flex gap-3">
                                <button type="button" @click="showModal = false" class="flex-1 px-4 py-3 bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold rounded-lg uppercase tracking-widest transition-all">
                                    Batal
                                </button>
                                <button type="submit" class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-lg uppercase tracking-widest transition-all">
                                    Simpan Servis
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="mt-12 border-t border-slate-800 pt-8 flex justify-between items-center text-[10px] font-bold text-slate-600 uppercase tracking-[0.2em]">
                <p>MARINE PMS &bull; PT OCEANINDO PRIMA SARANA &bull; 2026</p>
                <div class="flex gap-4">
                    <span class="flex items-center gap-1"><div class="w-2 h-2 rounded-full bg-emerald-500"></div> Normal</span>
                    <span class="flex items-center gap-1"><div class="w-2 h-2 rounded-full bg-yellow-500"></div> Warning</span>
                    <span class="flex items-center gap-1"><div class="w-2 h-2 rounded-full bg-red-500"></div> Critical</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>