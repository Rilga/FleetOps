<x-app-layout>
    <div class="py-8 bg-[#0f111a] min-h-screen text-slate-300">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <a href="{{ route('user.job_list', $machinery->id) }}" class="text-[10px] font-bold text-blue-500 uppercase tracking-widest hover:text-white transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Job List
                </a>
                <h2 class="text-3xl font-black text-white uppercase tracking-tighter mt-4">{{ $machinery->name }}</h2>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-[0.2em]">Activity Log & Service History</p>
            </div>

            <div class="space-y-4">
                @forelse($histories as $log)
                <div class="bg-[#1a1c23] border {{ $log->is_verified ? 'border-slate-800' : 'border-yellow-900/30' }} p-5 rounded-2xl shadow-xl relative overflow-hidden">
                    @if(!$log->is_verified)
                        <div class="absolute top-0 right-0 bg-yellow-600 text-black text-[8px] font-black px-3 py-1 uppercase tracking-tighter">
                            Awaiting Chief Verification
                        </div>
                    @endif

                    <div class="flex flex-col md:flex-row justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-[10px] font-mono text-blue-500">{{ \Carbon\Carbon::parse($log->completion_date)->format('d M Y') }}</span>
                                <span class="text-slate-700">•</span>
                                <span class="text-[10px] font-mono text-slate-500">{{ number_format($log->done_at_rh, 0) }} HRS</span>
                            </div>
                            
                            <h4 class="text-sm font-bold text-white uppercase tracking-tight">{{ $log->task->job_details }}</h4>
                            
                            <div class="mt-3 p-3 bg-black/20 rounded-xl border border-slate-800/50">
                                <p class="text-[11px] text-slate-400 italic">"{{ $log->remarks ?? 'No remarks provided.' }}"</p>
                            </div>
                        </div>

                        <div class="flex flex-col justify-end items-end gap-2">
                            @if($log->is_verified)
                                <div class="flex items-center gap-2 text-emerald-500 bg-emerald-500/10 px-3 py-1 rounded-full border border-emerald-500/20">
                                    <i class="fas fa-check-double text-[10px]"></i>
                                    <span class="text-[9px] font-black uppercase">Verified</span>
                                </div>
                                <p class="text-[8px] text-slate-600 font-bold uppercase italic">By Chief: {{ $log->verifier->name ?? 'System' }}</p>
                            @else
                                <div class="flex items-center gap-2 text-yellow-500 bg-yellow-500/10 px-3 py-1 rounded-full border border-yellow-500/20">
                                    <i class="fas fa-hourglass-half text-[10px]"></i>
                                    <span class="text-[9px] font-black uppercase">Pending</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-20 bg-[#1a1c23]/50 rounded-3xl border border-dashed border-slate-800">
                    <p class="text-slate-600 text-xs font-bold uppercase tracking-widest">No maintenance logs found for this machinery.</p>
                </div>
                @endforelse

                <div class="mt-8">
                    {{ $histories->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>