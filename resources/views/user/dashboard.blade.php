<x-app-layout>
    <div class="py-6 bg-[#0f172a] min-h-screen text-slate-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-slate-900 border border-slate-800 p-6 rounded mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h3 class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Active Work Area</h3>
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-600 p-2 rounded-lg">
                            <i class="fas fa-anchor text-white text-lg"></i>
                        </div>
                        <p class="text-2xl font-black text-white uppercase tracking-tight">{{ $responsibility }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                <div class="lg:col-span-4 space-y-4">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-1 h-4 bg-blue-600"></div>
                        <h3 class="font-bold text-white uppercase text-xs tracking-wider">Asset Running Hours</h3>
                    </div>
                    
                    <div class="space-y-2 overflow-y-auto max-h-[700px] pr-2 custom-scrollbar">
                        @foreach($machineries as $m)
                        <div class="bg-slate-900 border border-slate-800 p-4 rounded hover:border-blue-600/30 transition-all group">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex flex-col gap-1.5 mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] bg-blue-600/10 text-blue-400 px-2.5 py-1 rounded border border-blue-600/20 uppercase font-black tracking-widest shadow-sm">
                                                {{ $m->ship->name }}
                                            </span>
                                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-tighter">{{ $m->maker }}</p>
                                        </div>
                                    </div>
                                    
                                    <h4 class="font-bold text-base text-white group-hover:text-blue-400 transition-colors tracking-tight leading-tight">
                                        {{ $m->name }}
                                    </h4>
                                    
                                    <p class="text-[9px] text-slate-600 font-mono mt-1 uppercase">
                                        Mod: {{ $m->model ?? 'N/A' }} | S/N: {{ $m->serial_number ?? '-' }}
                                    </p>
                                </div>

                                <div class="text-right ml-4">
                                    <p class="text-xl font-mono font-bold text-white leading-none">
                                        {{ number_format($m->current_rh, 1) }}
                                    </p>
                                    <p class="text-[9px] text-slate-500 font-bold uppercase mt-1.5 tracking-tighter">Hours</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="lg:col-span-8 space-y-4">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-1 h-4 bg-red-600"></div>
                        <h3 class="font-bold text-white uppercase text-xs tracking-wider">Maintenance Alerts</h3>
                    </div>

                    <div class="space-y-3">
                        @forelse($urgentTasks as $task)
                        <div class="bg-slate-900 border border-slate-800 flex flex-col md:flex-row items-stretch overflow-hidden rounded group transition-all hover:bg-slate-800/50">
                            <div class="w-1.5 {{ $task->status == 'critical' ? 'bg-red-600 shadow-[2px_0_10px_rgba(220,38,38,0.4)]' : 'bg-orange-500 shadow-[2px_0_10px_rgba(249,115,22,0.4)]' }}"></div>
                            
                            <div class="flex-1 p-5 flex flex-col md:flex-row justify-between items-center gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-1.5">
                                        <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded border {{ $task->status == 'critical' ? 'border-red-900 text-red-500 bg-red-950/30' : 'border-orange-900 text-orange-500 bg-orange-950/30' }}">
                                            {{ $task->status }}
                                        </span>
                                        <span class="text-[9px] text-slate-600 font-bold uppercase tracking-widest">Vessel: {{ $task->machinery->ship->name }}</span>
                                    </div>
                                    <h4 class="font-bold text-white text-lg tracking-tight">{{ $task->machinery->name }}</h4>
                                    <p class="text-sm text-slate-400 mt-1 leading-relaxed">{{ $task->job_details }}</p>
                                    
                                    <div class="flex items-center gap-4 mt-4">
                                        <div class="flex items-center gap-2 bg-slate-950 px-3 py-1.5 rounded border border-slate-800">
                                            <span class="text-[10px] text-slate-500 font-bold uppercase">Schedule Due:</span>
                                            <span class="text-[12px] font-mono font-bold {{ $task->status == 'critical' ? 'text-red-500' : 'text-orange-500' }}">
                                                {{ number_format($task->next_due_rh, 0) }} HRS
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="w-full md:w-auto">
                                    <div class="w-full md:w-auto">
                                        <a href="{{ route('user.job_list', $task->machinery_id) }}" 
                                        class="inline-block text-center w-full bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded text-[11px] font-black uppercase tracking-[0.15em] transition-all shadow-lg active:scale-95 shadow-[0_5px_15px_rgba(37,99,235,0.3)]">
                                            Report Job
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="bg-slate-900 border border-dashed border-slate-800 py-20 text-center rounded">
                            <i class="fas fa-check-circle text-slate-800 text-4xl mb-4"></i>
                            <p class="text-slate-500 font-bold uppercase text-[10px] tracking-[0.3em]">All Systems Clear - No Alerts</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="fixed bottom-8 right-8">
                <a href="{{ route('user.fleet') }}" class="bg-blue-600 hover:bg-blue-500 text-white flex items-center px-8 py-4 rounded-full shadow-[0_10px_30px_rgba(37,99,235,0.4)] font-black text-xs uppercase tracking-[0.2em] transition-all hover:scale-105 active:scale-95">
                    <i class="fas fa-ship mr-3"></i>
                    Input Data
                </a>
            </div>

        </div>
    </div>

    <style>
        /* Custom Scrollbar Styling */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #0f172a; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 2px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #334155; }
    </style>
</x-app-layout>