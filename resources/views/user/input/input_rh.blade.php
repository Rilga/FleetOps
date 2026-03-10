<x-app-layout>
    <div class="py-12 bg-[#0f172a] min-h-screen text-slate-300">
        <div class="max-w-xl mx-auto px-4">
            <a href="{{ url()->previous() }}" class="text-slate-500 text-[10px] font-bold uppercase tracking-widest hover:text-blue-500 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back to the Machine List
            </a>

            <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden mt-6 shadow-2xl">
                <div class="bg-gradient-to-r from-slate-800 to-slate-900 p-8 border-b border-slate-700">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-500 mb-1">Daily Operational Log</p>
                            <h2 class="text-3xl font-black text-white uppercase tracking-tighter">{{ $machinery->name }}</h2>
                        </div>
                        <div class="bg-[#0f172a] p-3 rounded-xl border border-slate-800 text-center min-w-[100px]">
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Serial No</p>
                            <p class="text-xs font-mono text-white">{{ $machinery->serial_number ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('user.store_rh', $machinery->id) }}" method="POST" class="p-8 space-y-8">
                    @csrf
                    
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Report Date</label>
                        <input type="date" name="log_date" value="{{ date('Y-m-d') }}" 
                               class="w-full bg-[#0f172a] border-slate-800 rounded-lg text-white focus:border-blue-600 transition-all">
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest">Previous Reading</label>
                            <div class="bg-[#1e293b] p-4 rounded-lg border border-slate-700">
                                <span class="text-2xl font-mono font-bold text-slate-500">{{ number_format($machinery->current_rh, 1) }}</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold text-blue-500 uppercase tracking-widest">Current Reading</label>
                            <input type="number" step="0.1" name="current_reading" required
                                   class="w-full bg-[#0f172a] border-blue-900/50 rounded-lg py-4 px-4 text-2xl font-mono font-bold text-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all text-center" 
                                   placeholder="0.0">
                        </div>
                    </div>

                    <div class="bg-slate-800/30 border border-slate-700 p-5 rounded-2xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-bold text-white uppercase tracking-tight">Engine Condition</h4>
                                <p class="text-[10px] text-slate-500 mt-1 uppercase tracking-tighter">Toggle to the right if the machine is broken (Breakdown)</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_breakdown" value="1" class="sr-only peer">
                                <div class="w-14 h-7 bg-emerald-500/20 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-red-600"></div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Engineer Remarks (Engine Condition)</label>
                        <textarea name="remarks" rows="3" 
                                  class="w-full bg-[#0f172a] border-slate-800 rounded-lg text-sm text-white placeholder:text-slate-700 focus:border-blue-600 transition-all" 
                                  placeholder="Enter today's engine condition notes..."></textarea>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-black py-5 rounded-xl uppercase tracking-[0.2em] shadow-lg shadow-blue-900/20 transition-all active:scale-95">
                        Submit Daily Reading
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>