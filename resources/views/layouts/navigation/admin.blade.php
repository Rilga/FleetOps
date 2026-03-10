<nav x-data="{ open: false }" class="bg-[#111827] text-gray-400 w-full lg:w-64 lg:fixed lg:inset-y-0 lg:left-0 flex flex-col border-r border-gray-800 z-50">
    <div class="p-6">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
            <div class="bg-blue-600 p-2 rounded-lg">
                <i class="fas fa-anchor text-white text-xl"></i>
            </div>
            <div class="flex flex-col">
                <span class="text-white font-bold text-lg tracking-tight">Marine PMS</span>
                <span class="text-[10px] text-gray-500 uppercase font-semibold">PT OCEANINDO PRIMA</span>
            </div>
        </a>
    </div>

    <div class="flex-grow px-4 space-y-2 overflow-y-auto">
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'hover:bg-gray-800 hover:text-white' }}">
            <i class="fas fa-th-large text-lg"></i>
            <span class="font-medium">Dashboard</span>
        </a>
    </div>

    <div class="p-4 border-t border-gray-800">
        <div class="flex items-center gap-3 px-2 py-3 mb-2">
            <img class="h-10 w-10 rounded-full object-cover border border-gray-700" 
                 src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0284c7&color=fff" 
                 alt="{{ Auth::user()->name }}" />
            <div class="flex flex-col overflow-hidden">
                <span class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</span>
                <span class="text-[10px] text-gray-500 font-bold uppercase">Manager</span>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 w-full px-4 py-2 text-sm font-medium text-gray-400 hover:text-red-400 hover:bg-red-400/10 rounded-lg transition-all">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</nav>