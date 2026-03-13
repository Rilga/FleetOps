<x-app-layout>
    <div class="py-8 bg-[#0a0c12] min-h-screen text-slate-300 font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <a href="{{ route('admin.analyze', $ship->id) }}" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest hover:text-blue-500 transition-colors flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i> Back to Analysis
                    </a>
                    <h2 class="text-3xl font-black text-white uppercase tracking-tighter mt-4 leading-none italic decoration-blue-500 underline underline-offset-8">Technical Audit Log</h2>
                    <p class="text-blue-500 text-[10px] font-bold uppercase tracking-[0.3em] mt-4">{{ $ship->name }} • Full Verification History</p>
                </div>
                <button onclick="window.print()" class="p-4 bg-[#161922] border border-slate-800 rounded-2xl text-white hover:bg-slate-800 transition-all shadow-xl">
                    <i class="fas fa-print"></i>
                </button>
            </div>

            <div class="bg-[#161922] border border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-900/50 border-b border-slate-800 text-[9px] font-black text-slate-500 uppercase tracking-widest">
                            <th class="p-6">Timestamp</th>
                            <th class="p-6">Machinery Unit</th>
                            <th class="p-6">Maintenance Task</th>
                            <th class="p-6 text-center">Done at RH</th>
                            <th class="p-6">Engineer Remarks</th>
                            <th class="p-6">Verified By</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50">
                        @forelse($auditLogs as $log)
                        <tr class="hover:bg-blue-600/5 transition-colors border-l-2 {{ $log->is_verified ? 'border-emerald-500' : 'border-yellow-500' }}">
                            <td class="p-6 whitespace-nowrap">
                                <p class="text-[10px] font-black text-white">{{ \Carbon\Carbon::parse($log->completion_date)->format('d M Y') }}</p>
                                <p class="text-[9px] text-slate-600 font-mono mt-1 italic">{{ $log->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="p-6">
                                <span class="text-[9px] font-black text-blue-500 uppercase tracking-tighter">{{ $log->task->machinery->name }}</span>
                            </td>
                            <td class="p-6">
                                <p class="text-xs font-bold text-white uppercase leading-tight">{{ $log->task->job_details }}</p>
                                <p class="text-[9px] text-slate-600 uppercase mt-1 font-bold">Interval: {{ $log->task->interval }} Hrs</p>
                            </td>
                            <td class="p-6 text-center font-mono text-xs text-white">
                                {{ number_format($log->done_at_rh, 0) }}
                            </td>
                            <td class="p-6">
                                <div class="max-w-xs text-[10px] text-slate-500 italic leading-relaxed bg-black/20 p-3 rounded-xl border border-slate-800/50">
                                    "{{ $log->remarks }}"
                                </div>
                            </td>
                            <td class="p-6">
                                @if($log->is_verified)
                                    <div class="flex flex-col">
                                        <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest">Approved</span>
                                        <span class="text-[8px] text-slate-500 font-bold mt-1 uppercase">{{ $log->verifier->name ?? 'Chief' }}</span>
                                        <span class="text-[7px] text-slate-700 font-mono italic">{{ $log->verified_at }}</span>
                                    </div>
                                @else
                                    <span class="text-[9px] font-black text-yellow-600 uppercase tracking-widest italic animate-pulse">Awaiting Verification</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-20 text-center">
                                <i class="fas fa-folder-open text-slate-800 text-5xl mb-4"></i>
                                <p class="text-slate-600 font-bold uppercase text-xs tracking-[0.2em]">No Technical Logs Recorded in this Vessel</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-8">
                {{ $auditLogs->links() }}
            </div>

        </div>
    </div>
</x-app-layout>