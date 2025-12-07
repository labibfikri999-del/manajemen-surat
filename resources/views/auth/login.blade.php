<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Login - YARSI NTB | Sistem Manajemen Dokumen</title>
  <link rel="icon" type="image/png" href="{{ asset('images/Logo Yayasan Bersih.png') }}">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-emerald-500 via-emerald-600 to-teal-700 flex items-center justify-center p-4">

  {{-- Login Card --}}
  <div class="bg-white rounded-2xl w-full max-w-md p-6 sm:p-8 shadow-2xl">
    
    {{-- Logo & Header --}}
    <div class="text-center mb-6">
      <div class="inline-block mb-3">
        <img src="/images/Logo Yayasan Bersih.png" alt="Yayasan Bersih" class="w-16 h-16 mx-auto object-contain">
      </div>
      <h1 class="text-2xl font-bold text-gray-900 mb-1">YARSI NTB</h1>
      <p class="text-emerald-600 font-medium text-sm">Sistem Manajemen Dokumen Yayasan</p>
    </div>

    {{-- Welcome Message --}}
    <div class="bg-emerald-50 rounded-xl p-3.5 mb-6 border border-emerald-200">
      <div class="flex items-start gap-3">
        <div class="w-9 h-9 bg-emerald-500 rounded-lg flex items-center justify-center shrink-0">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
          </svg>
        </div>
        <div>
          <h3 class="font-semibold text-gray-900 text-sm">Selamat Datang!</h3>
          <p class="text-gray-600 text-xs mt-0.5">Silakan login untuk mengakses sistem</p>
        </div>
      </div>
    </div>

    {{-- Error Message --}}
    @if(session('error'))
    <div class="bg-red-50 rounded-xl p-4 mb-6 border border-red-100">
      <div class="flex items-center gap-3">
        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-red-600 text-sm">{{ session('error') }}</p>
      </div>
    </div>
    @endif

    @if ($errors->any())
    <div class="bg-red-50 rounded-xl p-4 mb-6 border border-red-100">
      <div class="flex items-start gap-3">
        <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <ul class="text-red-600 text-sm space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    </div>
    @endif

    {{-- Success Message --}}
    @if(session('success'))
    <div class="bg-emerald-50 rounded-xl p-4 mb-6 border border-emerald-100">
      <div class="flex items-center gap-3">
        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <p class="text-emerald-600 text-sm">{{ session('success') }}</p>
      </div>
    </div>
    @endif

    {{-- Login Form --}}
    <form action="{{ route('login') }}" method="POST" class="space-y-5">
      @csrf
      
      {{-- Email --}}
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
            </svg>
          </div>
          <input 
            type="email" 
            name="email" 
            value="{{ old('email') }}"
            placeholder="nama@yarsi-ntb.ac.id" 
            class="w-full pl-12 pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition-all"
            required
            autofocus
          >
        </div>
      </div>

      {{-- Password --}}
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
          </div>
          <input 
            type="password" 
            name="password" 
            id="password"
            placeholder="••••••••" 
            class="w-full pl-12 pr-12 py-3 rounded-xl border-2 border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition-all"
            required
          >
          <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center">
            <svg id="eyeIcon" class="w-5 h-5 text-gray-400 hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
          </button>
        </div>
      </div>

      {{-- Remember Me --}}
      <div class="flex items-center justify-between">
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
          <span class="text-sm text-gray-600">Ingat saya</span>
        </label>
      </div>

      {{-- Submit Button --}}
      <button type="submit" class="w-full py-3.5 rounded-xl text-white font-semibold text-base bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
        <span>Masuk ke Sistem</span>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
        </svg>
      </button>
    </form>

    {{-- Footer Info (di-nonaktifkan sesuai permintaan) --}}

    {{-- Copyright --}}
    <p class="text-center text-xs text-gray-400 mt-6">
      © {{ date('Y') }} Yayasan Rumah Sakit Islam NTB. All rights reserved.
    </p>
  </div>

  <script>
    function togglePassword() {
      const input = document.getElementById('password');
      const icon = document.getElementById('eyeIcon');
      
      if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = `
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
        `;
      } else {
        input.type = 'password';
        icon.innerHTML = `
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        `;
      }
    }
  </script>
</body>
</html>
