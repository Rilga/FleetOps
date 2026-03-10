<x-app-layout>
    <div class="py-12 bg-[#0f111a] min-h-screen text-slate-300">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-10 flex justify-between items-center">
                <div>
                    <a href="{{ route('chief.dashboard') }}" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest hover:text-blue-500 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                    </a>
                    <h2 class="text-3xl font-black text-white uppercase tracking-tighter mt-4 italic underline decoration-blue-600 underline-offset-8">Full Approval Queue</h2>
                </div>
                <div class="text-right">
                    <span class="text-4xl font-black text-slate-800 uppercase tracking-tighter block leading-none">{{ $pendingApprovals->total() }}</span>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Pending Fleet Tasks</span>
                </div>
            </div>

            <div class="space-y-4">
                @forelse($pendingApprovals as $item)
                    <div class="bg-[#1a1c23]/80 border border-blue-900/30 p-8 rounded-3xl flex flex-col md:flex-row justify-between items-center gap-8 shadow-2xl hover:border-blue-500/50 transition-all">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-[9px] font-black bg-blue-600 text-white px-3 py-1 rounded uppercase tracking-tighter">
                                    {{ $item->task->machinery->ship->name }}
                                </span>
                                <span class="text-[10px] text-slate-500 font-mono italic">{{ $item->completion_date }}</span>
                            </div>
                            <h5 class="text-xl font-black text-white uppercase tracking-tight">{{ $item->task->machinery->name }}</h5>
                            <p class="text-sm text-slate-400 font-bold uppercase tracking-wide">{{ $item->task->job_details }}</p>
                            
                            <div class="mt-4 p-4 bg-black/40 rounded-xl border border-slate-800 italic text-xs text-slate-500 leading-relaxed">
                                <span class="text-slate-700 block text-[9px] font-black uppercase tracking-widest not-italic mb-1">Engineer Remarks:</span>
                                "{{ $item->remarks }}"
                            </div>
                        </div>

                        <div class="w-full md:w-auto">
                            <form action="{{ route('chief.verify', $item->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full md:w-48 bg-blue-600 hover:bg-blue-500 text-white py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] shadow-xl transition-all active:scale-95">
                                    Approve Job
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20 bg-[#1a1c23]/40 rounded-3xl border border-dashed border-slate-800">
                        <i class="fas fa-check-circle text-slate-800 text-5xl mb-4"></i>
                        <p class="text-slate-500 font-bold uppercase text-xs tracking-[0.2em]">Queue Clean • No Pending Approvals</p>
                    </div>
                @endforelse

                <div class="mt-12">
                    {{ $pendingApprovals->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>