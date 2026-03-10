<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-cover bg-center bg-no-repeat" 
        style="background-image: linear-gradient(to bottom, #02034775, #0b0e1a), 
    linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ asset('asset/bg-login.png') }}');">
        
        <div class="mb-4 mt-5 text-center">
            <div class="flex items-center justify-center gap-3">
                <div class="bg-blue-600 p-2 rounded-lg shadow-lg shadow-blue-900/20">
                    <i class="fas fa-anchor text-white text-xl"></i>
                </div>
                <div class="text-left">
                    <h1 class="text-xl font-bold text-white tracking-tight uppercase">Marine PMS</h1>
                    <p class="text-xs text-gray-400 font-semibold">PT OCEANINDO PRIMA SARANA</p>
                </div>
            </div>
        </div>

        <div class="w-full sm:max-w-md px-8 py-10 bg-[#1a1c23]/90 backdrop-blur-sm shadow-2xl rounded-xl border border-gray-800">
            
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-white mb-2">Welcome Aboard</h2>
                <p class="text-sm text-gray-400">Enter your credentials to access the vessel maintenance logs.</p>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </div>
                        <input id="email" type="email" name="email" :value="old('email')" required autofocus 
                               class="block w-full pl-10 pr-3 py-3 bg-[#111318] border border-gray-700 rounded-lg text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" 
                               placeholder="Enter your email">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mb-6">
                    <div class="flex justify-between mb-2">
                        <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                               class="block w-full pl-10 pr-3 py-3 bg-[#111318] border border-gray-700 rounded-lg text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="••••••••">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center mb-6">
                    <input id="remember_me" type="checkbox" class="rounded bg-gray-900 border-gray-700 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                    <span class="ms-2 text-sm text-gray-400">{{ __('Remember me') }}</span>
                </div>

                <div class="mt-8">
                    <button type="submit" class="w-full flex justify-center items-center py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition duration-200 ease-in-out transform active:scale-[0.98]">
                        {{ __('Sign In') }}
                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-8 flex justify-between w-full max-w-4xl px-4 text-[10px] text-gray-500 uppercase tracking-widest">
            <div>© 2026 PT OCEANINDO PRIMA SARANA</div>
            <div class="space-x-4">
                <a href="#" class="hover:text-gray-300">Privacy Policy</a>
                <a href="#" class="hover:text-gray-300">Support</a>
            </div>
        </div>
    </div>
</x-guest-layout>