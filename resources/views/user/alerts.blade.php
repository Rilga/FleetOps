<x-app-layout>
    <div class="py-6 bg-[#0f172a] min-h-screen text-slate-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-slate-900 border border-slate-800 p-6 rounded mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 shadow-xl">
                <div>
                    <h3 class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Priority Monitoring</h3>
                    <div class="flex items-center gap-3">
                        <div class="bg-red-600 p-2 rounded-lg shadow-lg shadow-red-900/20 {{ $urgentTasks->where('status', 'critical')->count() > 0 ? 'animate-pulse' : '' }}">
                            <i class="fas fa-exclamation-triangle text-white text-lg"></i>
                        </div>
                        <h1 class="text-2xl font-black text-white uppercase tracking-tight">Maintenance <span class="text-blue-500">Alerts</span></h1>
                    </div>
                </div>
                <div class="flex flex-col items-end">
                    <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Active PIC</p>
                    <p class="text-xs font-mono text-blue-400 font-bold">{{ $picTarget }} Engineer</p>
                </div>
            </div>

            <div class="mb-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <a href="{{ route('user.alerts', ['filter' => 'all']) }}" 
                       class="bg-slate-900 border {{ $filter == 'all' ? 'border-blue-500 ring-1 ring-blue-500/50' : 'border-slate-800' }} p-5 rounded hover:bg-slate-800/50 transition-all">
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-1">Show All Alerts</p>
                        <p class="text-3xl font-black text-white leading-none">{{ $urgentTasks->count() }}</p>
                    </a>

                    <a href="{{ route('user.alerts', ['filter' => 'critical']) }}" 
                       class="bg-slate-900 border {{ $filter == 'critical' ? 'border-red-500 ring-1 ring-red-500/50' : 'border-slate-800' }} p-5 rounded hover:bg-slate-800/50 transition-all">
                        <p class="text-[10px] text-red-500 font-bold uppercase tracking-widest mb-1">Critical Priority</p>
                        <p class="text-3xl font-black text-red-600 leading-none">{{ $urgentTasks->where('status', 'critical')->count() }}</p>
                    </a>

                    <a href="{{ route('user.alerts', ['filter' => 'warning']) }}" 
                       class="bg-slate-900 border {{ $filter == 'warning' ? 'border-orange-500 ring-1 ring-orange-500/50' : 'border-slate-800' }} p-5 rounded hover:bg-slate-800/50 transition-all">
                        <p class="text-[10px] text-orange-500 font-bold uppercase tracking-widest mb-1">Warning Status</p>
                        <p class="text-3xl font-black text-orange-500 leading-none">{{ $urgentTasks->where('status', 'warning')->count() }}</p>
                    </a>
                </div>

                <div class="flex items-center gap-2 px-1">
                    <span class="text-[10px] text-slate-600 font-black uppercase tracking-widest">Active Filter:</span>
                    <span class="text-[10px] font-black uppercase text-blue-400 tracking-[0.15em]">{{ $filter }}</span>
                </div>
            </div>

            <div class="space-y-4">
                @forelse($urgentTasks as $task)
                <div class="bg-slate-900 border border-slate-800 flex flex-col md:flex-row items-stretch overflow-hidden rounded group transition-all hover:bg-slate-800/40 shadow-lg">
                    <div class="w-1.5 {{ $task->status == 'critical' ? 'bg-red-600 shadow-[2px_0_15px_rgba(220,38,38,0.4)]' : 'bg-orange-500 shadow-[2px_0_15px_rgba(249,115,22,0.4)]' }}"></div>
                    
                    <div class="flex-1 p-6 flex flex-col lg:flex-row justify-between items-center gap-6">
                        <div class="flex-1 w-full">
                            <div class="flex flex-wrap items-center gap-3 mb-2">
                                <span class="text-[10px] bg-blue-600/10 text-blue-400 px-2.5 py-1 rounded border border-blue-600/20 uppercase font-black tracking-widest shadow-sm">
                                    {{ $task->machinery->ship->name }}
                                </span>
                                <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded border {{ $task->status == 'critical' ? 'border-red-900 text-red-500 bg-red-950/30' : 'border-orange-900 text-orange-500 bg-orange-950/30' }}">
                                    {{ $task->status }}
                                </span>
                            </div>

                            <h4 class="font-bold text-xl text-white group-hover:text-blue-400 transition-colors tracking-tight leading-tight">
                                {{ $task->machinery->name }}
                            </h4>
                            
                            <p class="text-sm text-slate-400 mt-1 font-medium leading-relaxed">
                                {{ $task->job_details }}
                            </p>

                            <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mt-4">
                                <div class="flex flex-col">
                                    <span class="text-[8px] text-slate-600 font-bold uppercase tracking-widest">System</span>
                                    <span class="text-xs text-slate-300 font-bold uppercase truncate">{{ $task->system }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[8px] text-slate-600 font-bold uppercase tracking-widest">Running Hours</span>
                                    <span class="text-xs font-mono text-white">{{ number_format($task->machinery->current_rh, 1) }} HRS</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[8px] text-slate-600 font-bold uppercase tracking-widest">Schedule Due</span>
                                    <span class="text-xs font-mono font-bold {{ $task->status == 'critical' ? 'text-red-500' : 'text-orange-500' }}">
                                        {{ number_format($task->next_due_rh, 0) }} HRS
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="w-full lg:w-auto">
                            <a href="{{ route('user.job_list', $task->machinery_id) }}" 
                               class="inline-block text-center w-full lg:w-48 bg-blue-600 hover:bg-blue-500 text-white px-6 py-3.5 rounded text-[11px] font-black uppercase tracking-[0.2em] transition-all shadow-lg active:scale-95 shadow-blue-900/20">
                                <i class="fas fa-file-alt mr-2"></i> Report Job
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-slate-900 border border-dashed border-slate-800 py-24 text-center rounded">
                    <div class="bg-slate-800/50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-700 text-slate-500">
                        <i class="fas fa-clipboard-check text-2xl"></i>
                    </div>
                    <p class="text-slate-500 font-bold uppercase text-xs tracking-[0.3em]">No {{ $filter != 'all' ? $filter : '' }} Alerts Found</p>
                    <a href="{{ route('user.alerts', ['filter' => 'all']) }}" class="text-[10px] text-blue-500 font-black uppercase mt-4 inline-block hover:underline">Show All Alerts</a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>