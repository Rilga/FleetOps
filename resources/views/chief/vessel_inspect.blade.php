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

            <form action="{{ route('chief.export-pdf', $ship->id) }}" method="GET" class="mt-6 bg-[#1a1c23]/80 border border-slate-800 p-6 rounded-2xl shadow-xl">
                <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-5">
                    <div class="flex-1">
                        <h3 class="text-xs font-black text-white uppercase tracking-widest">Export PDF Report</h3>
                        <p class="text-[9px] text-slate-500 uppercase tracking-widest mt-2">Select one month or a completion-date range.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 flex-[2]">
                        <div>
                            <label for="pdf-month" class="block text-[9px] font-bold text-slate-500 uppercase mb-1">Month</label>
                            <input id="pdf-month" name="month" type="month" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:border-blue-500 focus:ring-0">
                        </div>
                        <div>
                            <label for="pdf-start-date" class="block text-[9px] font-bold text-slate-500 uppercase mb-1">Start date</label>
                            <input id="pdf-start-date" name="start_date" type="date" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:border-blue-500 focus:ring-0">
                        </div>
                        <div>
                            <label for="pdf-end-date" class="block text-[9px] font-bold text-slate-500 uppercase mb-1">End date</label>
                            <input id="pdf-end-date" name="end_date" type="date" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-xs text-white focus:border-blue-500 focus:ring-0">
                        </div>
                    </div>

                    <button type="submit" class="bg-red-600 hover:bg-red-500 text-white px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2 whitespace-nowrap">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </button>
                </div>
                <p class="text-[9px] text-slate-600 mt-4">A date range takes priority. Leave every field blank to export all maintenance history.</p>
            </form>
        </div>
    </div>

    <script>
        const pdfMonth = document.getElementById('pdf-month');
        const pdfStartDate = document.getElementById('pdf-start-date');
        const pdfEndDate = document.getElementById('pdf-end-date');

        pdfMonth.addEventListener('change', () => {
            if (pdfMonth.value) {
                pdfStartDate.value = '';
                pdfEndDate.value = '';
            }
        });

        [pdfStartDate, pdfEndDate].forEach((input) => {
            input.addEventListener('change', () => {
                if (pdfStartDate.value || pdfEndDate.value) {
                    pdfMonth.value = '';
                }
            });
        });
    </script>
</x-app-layout>
