<x-app-layout>
    <div class="py-6 bg-[#0f172a] min-h-screen text-slate-300">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-4">
                <a href="{{ route('user.maintenance_history', $log->task->machinery_id) }}" class="text-slate-500 text-[10px] font-bold uppercase tracking-widest hover:text-blue-500 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Back to History
                </a>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-2xl">
                <div class="bg-gradient-to-r from-slate-800 to-slate-900 p-8 border-b border-slate-700">
                    <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-500 mb-1">Modify Maintenance Log</p>
                            <h2 class="text-3xl font-black text-white uppercase tracking-tighter">{{ $log->task->job_details }}</h2>
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-[0.1em] mt-1 italic">Knowledge Correction Module</p>
                        </div>
                        <div class="bg-[#0f172a] p-3 rounded-xl border border-slate-800 text-center min-w-[140px]">
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Verification Status</p>
                            <p class="text-xs font-mono text-yellow-500 uppercase font-bold italic mt-1">
                                <i class="fas fa-hourglass-half mr-1"></i> Awaiting Approval
                            </p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('user.maintenance.update', $log->id) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Completion Date</label>
                                <div class="relative">
                                    <input type="date" name="completion_date" 
                                           value="{{ old('completion_date', \Carbon\Carbon::parse($log->completion_date)->format('Y-m-d')) }}" 
                                           class="w-full bg-[#0f172a] border-slate-800 rounded-lg text-white focus:border-blue-600 focus:ring-blue-600/20 transition-all">
                                </div>
                                @error('completion_date') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Maintenance Remarks</label>
                                <textarea name="remarks" rows="6" 
                                          class="w-full bg-[#0f172a] border-slate-800 rounded-lg text-sm text-white placeholder:text-slate-700 focus:border-blue-600 transition-all" 
                                          placeholder="Describe the work done...">{{ old('remarks', $log->remarks) }}</textarea>
                                @error('remarks') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex flex-col justify-between">
                            <div class="bg-slate-800/20 border border-slate-800 p-6 rounded-2xl flex-grow">
                                <label class="block text-[10px] font-bold text-blue-500 uppercase tracking-widest mb-4 text-center">Done at Running Hours</label>
                                <div class="relative py-4">
                                    <input type="number" step="0.1" name="done_at_rh" 
                                           value="{{ old('done_at_rh', $log->done_at_rh) }}" 
                                           required
                                           class="w-full bg-[#0f172a] border-blue-900/30 rounded-lg py-8 px-4 text-4xl font-mono font-black text-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all text-center" 
                                           placeholder="0.0">
                                    <p class="text-center text-[9px] text-slate-600 uppercase font-black tracking-widest mt-4 italic">Confirm actual engine reading upon completion</p>
                                </div>
                                @error('done_at_rh') <p class="text-red-500 text-[10px] mt-1 text-center font-bold">{{ $message }}</p> @enderror
                            </div>

                            <div class="mt-6 flex items-start gap-3 p-4 bg-yellow-900/5 border border-yellow-900/10 rounded-xl">
                                <i class="fas fa-info-circle text-yellow-600 text-xs mt-0.5"></i>
                                <p class="text-[9px] text-slate-500 leading-relaxed uppercase font-bold tracking-tighter">
                                    This log is editable until a Chief Engineer performs verification.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-slate-800">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-black py-4 rounded-xl uppercase tracking-[0.2em] shadow-lg shadow-blue-900/20 transition-all active:scale-95 flex items-center justify-center gap-3">
                            <i class="fas fa-save text-sm"></i> Commit Changes & Update Log
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>