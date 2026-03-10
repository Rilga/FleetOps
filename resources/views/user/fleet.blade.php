<x-app-layout>
    {{-- Inisialisasi Data ke Alpine.js --}}
    <div x-data="{ 
            search: '', 
            filter: 'all',
            ships: {{ $ships->toJson() }},
            get filteredShips() {
                return this.ships.filter(ship => {
                    const matchesSearch = ship.name.toLowerCase().includes(this.search.toLowerCase());
                    const matchesFilter = this.filter === 'all' || ship.type.toLowerCase() === this.filter.toLowerCase();
                    return matchesSearch && matchesFilter;
                });
            }
        }" 
        class="py-8 bg-[#0f172a] min-h-screen text-slate-300 font-sans">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-slate-900 border border-slate-800 p-6 rounded mb-8 shadow-xl">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <div>
                        <h3 class="text-slate-500 text-[10px] font-bold uppercase tracking-[0.2em] mb-1">Fleet Directory</h3>
                        <p class="text-2xl font-bold text-white uppercase tracking-tight">Select Operational Ship</p>
                    </div>

                    <div class="flex flex-col md:flex-row gap-4 items-center">
                        <div class="relative w-full md:w-64">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500">
                                <i class="fas fa-search text-xs"></i>
                            </span>
                            <input x-model="search" 
                                   type="text" 
                                   placeholder="Search for the ship's name..." 
                                   class="w-full bg-[#0f172a] border-slate-800 rounded text-xs py-2.5 pl-10 text-white focus:border-blue-600 focus:ring-0 transition-all placeholder:text-slate-600">
                        </div>

                        <div class="flex bg-[#0f172a] p-1 rounded border border-slate-800 w-full md:w-auto">
                            <button @click="filter = 'all'" 
                                    :class="filter === 'all' ? 'bg-blue-600 text-white' : 'text-slate-500 hover:text-white'"
                                    class="px-4 py-1.5 text-[10px] font-bold uppercase tracking-widest rounded transition-all">
                                All Vessels
                            </button>
                            <button @click="filter = 'aht'" 
                                    :class="filter === 'aht' ? 'bg-blue-600 text-white' : 'text-slate-500 hover:text-white'"
                                    class="px-4 py-1.5 text-[10px] font-bold uppercase tracking-widest rounded transition-all">
                                AHT
                            </button>
                            <button @click="filter = 'ahts'" 
                                    :class="filter === 'ahts' ? 'bg-blue-600 text-white' : 'text-slate-500 hover:text-white'"
                                    class="px-4 py-1.5 text-[10px] font-bold uppercase tracking-widest rounded transition-all">
                                AHTS
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <template x-for="ship in filteredShips" :key="ship.id">
                    <a :href="'/engineer/machineries/' + ship.id" class="group block">
                        <div class="bg-slate-900 border border-slate-800 rounded overflow-hidden transition-all duration-300 hover:border-blue-600/50 hover:shadow-[0_0_20px_rgba(37,99,235,0.1)] relative">
                            
                            <div class="h-48 overflow-hidden relative">
                                <div class="absolute inset-0 bg-slate-950/40 group-hover:bg-transparent transition-colors duration-500 z-10"></div>
                                
                                <img :src="'/images/ships/' + ship.image" 
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                     :alt="ship.name">
                                
                                <div class="absolute top-4 left-4 z-20">
                                    <span class="px-2 py-1 bg-slate-900/80 backdrop-blur border border-slate-700 text-blue-400 text-[9px] font-bold uppercase tracking-widest rounded" x-text="ship.type"></span>
                                </div>
                            </div>

                            <div class="p-5 relative">
                                <div class="absolute top-0 left-5 w-8 h-0.5 bg-blue-600 transition-all duration-500 group-hover:w-20"></div>

                                <div class="flex justify-between items-start mt-2">
                                    <h3 class="font-bold text-white uppercase tracking-tight group-hover:text-blue-500 transition-colors" x-text="ship.name"></h3>
                                </div>
                                
                                <div class="flex items-center gap-2 mt-2 mb-6">
                                    <div class="w-1 h-3 bg-slate-800 group-hover:bg-blue-600 transition-colors"></div>
                                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest italic">Department: Engine</span>
                                </div>

                                <div class="flex items-center justify-between py-2.5 px-3 bg-[#0f172a] border border-slate-800 rounded group-hover:border-slate-700 transition-colors">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">select</span>
                                    <i class="fas fa-arrow-right text-[10px] text-slate-600 group-hover:text-blue-500 group-hover:translate-x-1 transition-all"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </template>
            </div>

            <div x-show="filteredShips.length === 0" class="bg-slate-900 border border-dashed border-slate-800 py-20 text-center rounded mt-6">
                <i class="fas fa-search text-slate-700 text-3xl mb-4"></i>
                <p class="text-slate-500 font-bold uppercase text-[10px] tracking-[0.2em]">Ship not found</p>
            </div>

            <div class="mt-12 text-center">
                <p class="text-[10px] text-slate-600 font-bold uppercase tracking-[0.3em]">MARINE PMS &bull; PT OCEANINDO PRIMA SARANA &bull; 2026</p>
            </div>
        </div>
    </div>
</x-app-layout>