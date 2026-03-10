<x-app-layout>
    <div class="py-8 bg-[#0f111a] min-h-screen text-slate-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8 flex flex-col md:flex-row justify-between items-end gap-6">
                <div>
                    <a href="{{ route('chief.dashboard') }}" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest hover:text-blue-500 transition-colors flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i> Fleet Dashboard
                    </a>
                    <h2 class="text-4xl font-black text-white uppercase tracking-tighter mt-4 leading-none">{{ $ship->name }}</h2>
                    <p class="text-blue-500 text-[10px] font-bold uppercase tracking-[0.3em] mt-2">Vessel Technical Inspection Report</p>
                </div>

                <div class="flex gap-4">
                    <div class="bg-red-950/20 border border-red-900/50 px-6 py-3 rounded-2xl text-center min-w-[120px]">
                        <p class="text-[9px] font-black text-red-500 uppercase tracking-widest">Critical</p>
                        <p class="text-2xl font-mono font-black text-white leading-none">{{ $stats['critical'] }}</p>
                    </div>
                    <div class="bg-yellow-950/20 border border-yellow-900/50 px-6 py-3 rounded-2xl text-center min-w-[120px]">
                        <p class="text-[9px] font-black text-yellow-500 uppercase tracking-widest">Warning</p>
                        <p class="text-2xl font-mono font-black text-white leading-none">{{ $stats['warning'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-[#1a1c23]/80 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-900/50 border-b border-slate-800">
                                <th class="p-6 text-[10px] font-black text-slate-500 uppercase tracking-widest">Machinery Unit</th>
                                <th class="p-6 text-[10px] font-black text-slate-500 uppercase tracking-widest">Model / Serial</th>
                                <th class="p-6 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Current RH</th>
                                <th class="p-6 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Task Status</th>
                                <th class="p-6 text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50">
                            @foreach($ship->machineries as $m)
                            <tr class="hover:bg-slate-800/30 transition-colors group">
                                <td class="p-6">
                                    <p class="text-sm font-black text-white uppercase tracking-tight">{{ $m->name }}</p>
                                    <p class="text-[10px] text-blue-500 font-bold uppercase mt-1">{{ $m->maker }}</p>
                                </td>
                                <td class="p-6 text-xs text-slate-400 font-mono italic">
                                    {{ $m->model }}<br>
                                    <span class="text-[10px] text-slate-600">S/N: {{ $m->serial_number }}</span>
                                </td>
                                <td class="p-6 text-center">
                                    <p class="text-lg font-mono font-black text-white leading-none">{{ number_format($m->current_rh, 1) }}</p>
                                    <p class="text-[8px] text-slate-600 font-black uppercase mt-1">Hours</p>
                                </td>
                                <td class="p-6">
                                    <div class="flex justify-center gap-2">
                                        @php
                                            $c = $m->maintenanceTasks->where('status', 'critical')->count();
                                            $w = $m->maintenanceTasks->where('status', 'warning')->count();
                                        @endphp
                                        
                                        @if($c > 0)
                                            <span class="bg-red-600/10 border border-red-600 text-red-500 text-[9px] font-black px-2 py-1 rounded">{{ $c }} CRIT</span>
                                        @endif
                                        @if($w > 0)
                                            <span class="bg-yellow-600/10 border border-yellow-600 text-yellow-500 text-[9px] font-black px-2 py-1 rounded">{{ $w }} WARN</span>
                                        @endif
                                        @if($c == 0 && $w == 0)
                                            <span class="bg-emerald-600/10 border border-emerald-600 text-emerald-500 text-[9px] font-black px-2 py-1 rounded">ALL CLEAR</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-6 text-right">
                                    <a href="{{ route('chief.machinery_history', $m->id) }}" class="inline-flex items-center gap-2 text-[10px] font-black text-slate-500 hover:text-blue-500 uppercase tracking-widest transition-colors">
                                        View Full History <i class="fas fa-chevron-right text-[8px]"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 flex justify-between items-center bg-[#1a1c23]/40 p-6 rounded-2xl border border-slate-800 border-dashed">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-500">
                        <i class="fas fa-info-circle text-lg"></i>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed italic">
                        "This report shows current operating status for all machinery units on <span class="text-white font-bold">{{ $ship->name }}</span>. 
                        Data is synchronized with the latest entries from the Engineering Department."
                    </p>
                </div>
                <button onclick="window.print()" class="bg-slate-800 hover:bg-slate-700 text-white px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                    <i class="fas fa-print"></i> Print Report
                </button>
            </div>
        </div>
    </div>
</x-app-layout>