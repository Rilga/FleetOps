<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Marine PMS</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,600,800" rel="stylesheet" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Instrument Sans', sans-serif; }
            /* Grid halus tanpa glow */
            .bg-grid {
                background-image: radial-gradient(circle at 2px 2px, rgba(255, 255, 255, 0.05) 1px, transparent 0);
                background-size: 40px 40px;
            }
            .bg-main {
                background-color: #0f111a;
            }
        </style>
    </head>
    <body class="bg-main text-slate-400 min-h-screen flex flex-col bg-grid">
        
        <header class="w-full max-w-7xl mx-auto px-6 py-10 flex justify-between items-center relative z-10">
            <div class="flex items-center gap-3">
                <div class="bg-blue-600 p-2 rounded-lg shadow-lg shadow-blue-900/20">
                    <i class="fas fa-anchor text-white text-xl"></i>
                </div>
                <div class="text-left leading-tight">
                    <h1 class="text-lg font-bold text-white tracking-tight uppercase">Marine <span class="text-blue-500">PMS</span></h1>
                    <p class="text-[9px] text-slate-500 font-bold tracking-widest uppercase">PT Oceanindo Prima Sarana</p>
                </div>
            </div>

            @if (Route::has('login'))
                <nav class="flex items-center">
                    @auth
                        <a href="{{ url('/dashboard') }}" 
                        class="px-6 py-2.5 bg-slate-800 border border-slate-700 text-white text-[11px] font-extrabold uppercase tracking-widest rounded-md hover:bg-slate-700 transition-all shadow-lg">
                        Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                        class="px-8 py-3 bg-blue-600 text-white text-[12px] font-extrabold uppercase tracking-[0.2em] rounded shadow-[0_0_15px_rgba(37,99,235,0.4)] hover:bg-blue-500 hover:scale-105 transition-all active:scale-95">
                        Login
                        </a>
                    @endauth
                </nav>
            @endif
        </header>

        <main class="flex-1 flex flex-col items-center justify-center px-6 relative">
            <div class="max-w-4xl w-full text-center relative z-10 bg-[#1a1c23]/40 border border-slate-800 p-12 lg:p-16 rounded-lg">
                <h1 class="text-4xl lg:text-6xl font-extrabold text-white uppercase tracking-tighter leading-none mb-6">
                    Fleet Asset <br>
                    <span class="text-blue-500">Maintenance System</span>
                </h1>

                <p class="text-slate-500 text-base max-w-xl mx-auto mb-10 leading-relaxed">
                    Automated running hours tracking, forecasting service intervals, 
                    and integrated maintenance management for maritime operations.
                </p>

                <div class="mt-10 pt-4 border-t border-slate-800 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-4">
                        <h4 class="text-white font-bold text-xl uppercase">08</h4>
                        <p class="text-[9px] text-slate-600 font-bold uppercase tracking-widest mt-1">Active Vessels</p>
                    </div>
                    <div class="p-4 border-slate-800 md:border-x">
                        <h4 class="text-white font-bold text-xl uppercase">98.2%</h4>
                        <p class="text-[9px] text-slate-600 font-bold uppercase tracking-widest mt-1">System Uptime</p>
                    </div>
                    <div class="p-4">
                        <h4 class="text-white font-bold text-xl uppercase">Digital</h4>
                        <p class="text-[9px] text-slate-600 font-bold uppercase tracking-widest mt-1">RH Forecast</p>
                    </div>
                </div>
            </div>
        </main>

        <footer class="w-full max-w-7xl mx-auto px-6 py-10 flex flex-col md:flex-row justify-between items-center gap-4 border-t border-slate-900">
            <p class="text-[9px] font-bold text-slate-700 uppercase tracking-[0.3em]">
                © 2026 PT OCEANINDO PRIMA SARANA
            </p>
            <div class="flex gap-6 text-[9px] font-bold text-slate-700 uppercase tracking-widest">
                <a href="#" class="hover:text-slate-400 transition-colors">Support</a>
            </div>
        </footer>
    </body>
</html>