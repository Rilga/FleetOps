<x-app-layout>
    <div class="py-8 bg-[#0f111a] min-h-screen text-slate-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8 flex flex-col md:flex-row justify-between items-start gap-4">
                <div>
                    <a href="{{ route('chief.inspect', $machinery->ship_id) }}" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest hover:text-blue-500 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Vessel Inspection
                    </a>
                    <h2 class="text-3xl font-black text-white uppercase tracking-tighter mt-4 leading-none">
                        Maintenance Log: <span class="text-blue-500">{{ $machinery->name }}</span>
                    </h2>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-[0.3em] mt-2">
                        Vessel: {{ $machinery->ship->name }} | Model: {{ $machinery->model }}
                    </p>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-[9px] font-black text-slate-600 uppercase">Current Running Hours</p>
                        <p class="text-2xl font-mono font-black text-white">{{ number_format($machinery->current_rh, 1) }}</p>
                    </div>
                    <button onclick="window.print()" class="p-3 bg-slate-900 border border-slate-800 rounded-xl hover:text-white transition-all">
                        <i class="fas fa-print"></i>
                    </button>
                </div>
            </div>

            <div class="bg-[#1a1c23]/80 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-900/50 border-b border-slate-800">
                            <th class="p-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">Date Done</th>
                            <th class="p-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">Job Description</th>
                            <th class="p-5 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Done At (RH)</th>
                            <th class="p-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">Remarks / Findings</th>
                            <th class="p-5 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50">
                        @forelse($allHistories as $history)
                        <tr class="hover:bg-slate-800/20 transition-colors">
                            <td class="p-5">
                                <p class="text-xs font-bold text-white">{{ \Carbon\Carbon::parse($history->completion_date)->format('d M Y') }}</p>
                                <p class="text-[9px] text-slate-500 font-mono mt-0.5">{{ \Carbon\Carbon::parse($history->created_at)->format('H:i') }} LT</p>
                            </td>
                            <td class="p-5">
                                <p class="text-xs font-bold text-blue-400 uppercase tracking-tight">{{ $history->task->job_details }}</p>
                                <p class="text-[9px] text-slate-600 font-bold uppercase mt-1">Interval: {{ $history->task->interval_rh }} Hrs</p>
                            </td>
                            <td class="p-5 text-center font-mono text-xs text-white">
                                {{ number_format($history->done_at_rh, 0) }}
                            </td>
                            <td class="p-5">
                                <div class="max-w-xs text-[11px] text-slate-400 italic leading-relaxed">
                                    "{{ $history->remarks ?? 'No specific remarks.' }}"
                                </div>
                            </td>
                            <td class="p-5 text-center">
                                @if($history->is_verified)
                                    <div class="flex flex-col items-center">
                                        <span class="text-[8px] font-black bg-emerald-600/20 text-emerald-500 px-2 py-0.5 rounded uppercase border border-emerald-500/30">Verified</span>
                                        <p class="text-[7px] text-slate-600 font-bold mt-1 uppercase">By: {{ $history->verifier->name ?? 'Chief' }}</p>
                                    </div>
                                @else
                                    <span class="text-[8px] font-black bg-yellow-600/20 text-yellow-500 px-2 py-0.5 rounded uppercase border border-yellow-500/30">Pending</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-20 text-center">
                                <p class="text-slate-600 text-xs font-bold uppercase tracking-[0.2em]">No maintenance history recorded for this unit.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-8">
                {{ $allHistories->links() }}
            </div>

        </div>
    </div>
</x-app-layout>