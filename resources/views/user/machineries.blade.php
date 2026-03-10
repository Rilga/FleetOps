<x-app-layout>
    <div x-data="{ 
            search: '', 
            machineries: {{ $machineries->toJson() }},
            get filteredMachineries() {
                return this.machineries.filter(m => {
                    return m.name.toLowerCase().includes(this.search.toLowerCase()) || 
                           m.maker.toLowerCase().includes(this.search.toLowerCase()) ||
                           m.model.toLowerCase().includes(this.search.toLowerCase());
                });
            }
        }" 
        class="py-8 bg-[#0f172a] min-h-screen text-slate-300 font-sans">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-slate-900 border border-slate-800 p-6 rounded mb-8 shadow-xl">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-[0.2em] text-blue-500 mb-2">
                            <a href="{{ route('user.fleet') }}" class="hover:underline">Fleet Selection</a>
                            <span>/</span>
                            <span class="text-slate-500">{{ $ship->name }}</span>
                        </div>
                        <h2 class="text-3xl font-black text-white uppercase tracking-tighter">{{ $ship->name }}</h2>
                    </div>
                    
                    <div class="relative w-full md:w-64">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-600">
                            <i class="fas fa-search text-xs"></i>
                        </span>
                        <input x-model="search" 
                               type="text" 
                               placeholder="Cari mesin..." 
                               class="w-full bg-[#0f172a] border-slate-800 rounded text-xs py-2.5 pl-10 text-white focus:border-blue-600 focus:ring-0 transition-all placeholder:text-slate-700">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="m in filteredMachineries" :key="m.id">
                    <div class="bg-slate-900 border border-slate-800 rounded overflow-hidden transition-all duration-300 hover:border-blue-600/50 group">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <p class="text-[10px] text-blue-500 font-bold uppercase tracking-widest mb-1" x-text="m.maker"></p>
                                    <h4 class="text-lg font-bold text-white uppercase group-hover:text-blue-500 transition-colors" x-text="m.name"></h4>
                                    <p class="text-[9px] text-slate-600 font-mono mt-1 italic" x-text="m.model"></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-mono font-bold text-white leading-none" x-text="parseFloat(m.current_rh).toFixed(1)"></p>
                                    <p class="text-[9px] text-slate-600 font-bold uppercase mt-1">Total Hours</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 mb-6">
                                <div class="flex-1 h-[1px] bg-slate-800"></div>
                                <div class="w-1.5 h-1.5 bg-blue-600 rotate-45"></div>
                                <div class="flex-1 h-[1px] bg-slate-800"></div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <a :href="'{{ route('user.input_rh', ['machinery_id' => ':id']) }}'.replace(':id', m.id)" class="flex flex-col items-center justify-center p-3 bg-[#0f172a] border border-slate-800 rounded hover:bg-blue-600 hover:border-blue-500 group/btn transition-all">
                                    <i class="fas fa-clock text-slate-600 group-hover/btn:text-white mb-2"></i>
                                    <span class="text-[9px] font-bold text-slate-400 group-hover/btn:text-white uppercase tracking-widest">Input RH</span>
                                </a>
                                
                                <a :href="'{{ route('user.job_list', ['machinery_id' => ':id']) }}'.replace(':id', m.id)" class="flex flex-col items-center justify-center p-3 bg-[#0f172a] border border-slate-800 rounded hover:bg-slate-800 group/btn transition-all">
                                    <i class="fas fa-tools text-slate-600 group-hover/btn:text-blue-500 mb-2"></i>
                                    <span class="text-[9px] font-bold text-slate-400 group-hover/btn:text-slate-200 uppercase tracking-widest">Job List</span>
                                </a>
                            </div>
                        </div>
                        <div class="h-1 w-0 bg-blue-600 group-hover:w-full transition-all duration-500"></div>
                    </div>
                </template>
            </div>

            <div x-show="filteredMachineries.length === 0" class="text-center py-20 bg-slate-900 border border-dashed border-slate-800 rounded mt-6">
                <p class="text-slate-500 text-sm italic">Mesin tidak ditemukan.</p>
            </div>

            <div class="mt-12 text-center">
                <p class="text-[10px] text-slate-600 font-bold uppercase tracking-[0.3em]">MARINE PMS &bull; PT OCEANINDO PRIMA SARANA &bull; 2026</p>
            </div>
        </div>
    </div>
</x-app-layout>