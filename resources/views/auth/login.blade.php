<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Login - YARSI NTB | Sistem Manajemen Dokumen</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    * { font-family: 'Inter', sans-serif; }
    
    .login-bg {
      background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #10b981 100%);
      position: relative;
      overflow: hidden;
    }
    
    .login-bg::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
      animation: float 30s linear infinite;
    }
    
    @keyframes float {
      0% { transform: translate(0, 0) rotate(0deg); }
      100% { transform: translate(-50px, -50px) rotate(360deg); }
    }
    
    .glass-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25),
                  0 0 0 1px rgba(255, 255, 255, 0.1);
    }
    
    .input-field {
      transition: all 0.3s ease;
      border: 2px solid #e5e7eb;
    }
    
    .input-field:focus {
      border-color: #047857;
      box-shadow: 0 0 0 4px rgba(4, 120, 87, 0.1);
    }
    
    .btn-login {
      background: linear-gradient(135deg, #047857 0%, #065f46 100%);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    
    .btn-login::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s ease;
    }
    
    .btn-login:hover::before {
      left: 100%;
    }
    
    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px -5px rgba(4, 120, 87, 0.4);
    }
    
    .btn-login:active {
      transform: translateY(0);
    }
    
    .floating-shapes div {
      position: absolute;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.1);
      animation: floating 15s infinite;
    }
    
    @keyframes floating {
      0%, 100% { transform: translateY(0) rotate(0deg); }
      50% { transform: translateY(-20px) rotate(180deg); }
    }
    
    .logo-pulse {
      animation: pulse 2s ease-in-out infinite;
    }
    
    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.02); }
    }
    
    .form-appear {
      animation: formAppear 0.6s ease-out;
    }
    
    @keyframes formAppear {
      0% { opacity: 0; transform: translateY(20px); }
      100% { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body class="min-h-screen login-bg flex items-center justify-center p-4">
  
  {{-- Floating Shapes --}}
  <div class="floating-shapes">
    <div class="w-72 h-72 -top-20 -left-20" style="animation-delay: 0s;"></div>
    <div class="w-96 h-96 -bottom-32 -right-32" style="animation-delay: 5s;"></div>
    <div class="w-48 h-48 top-1/2 left-1/4" style="animation-delay: 2.5s;"></div>
  </div>

  {{-- Login Card --}}
  <div class="glass-card rounded-3xl w-full max-w-md p-8 md:p-10 relative z-10 form-appear">
    
    {{-- Logo & Header --}}
    <div class="text-center mb-8">
      <div class="logo-pulse inline-block mb-4">
        <img src="/images/logo-yarsi.svg" alt="YARSI NTB" class="w-20 h-20 mx-auto">
      </div>
      <h1 class="text-2xl font-bold text-gray-800 mb-1">YARSI NTB</h1>
      <p class="text-emerald-600 font-medium text-sm">Sistem Manajemen Dokumen Yayasan</p>
    </div>

    {{-- Welcome Message --}}
    <div class="bg-emerald-50 rounded-xl p-4 mb-6 border border-emerald-100">
      <div class="flex items-start gap-3">
        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center shrink-0">
          <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
          </svg>
        </div>
        <div>
          <h3 class="font-semibold text-emerald-800 text-sm">Selamat Datang!</h3>
          <p class="text-emerald-600 text-xs mt-0.5">Silakan login untuk mengakses sistem</p>
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
        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
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
            class="input-field w-full pl-12 pr-4 py-3.5 rounded-xl bg-gray-50 focus:bg-white outline-none text-gray-700"
            required
            autofocus
          >
        </div>
      </div>

      {{-- Password --}}
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
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
            class="input-field w-full pl-12 pr-12 py-3.5 rounded-xl bg-gray-50 focus:bg-white outline-none text-gray-700"
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
      <button type="submit" class="btn-login w-full py-4 rounded-xl text-white font-semibold text-base flex items-center justify-center gap-2">
        <span>Masuk ke Sistem</span>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
        </svg>
      </button>
    </form>

    {{-- Footer Info --}}
    <div class="mt-8 pt-6 border-t border-gray-100">
      <div class="text-center">
        <p class="text-xs text-gray-500 mb-3">Akun Demo untuk Testing:</p>
        <div class="grid grid-cols-1 gap-2 text-xs">
          <div class="bg-gray-50 rounded-lg p-2 flex justify-between items-center">
            <span class="text-gray-600">Direktur:</span>
            <code class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded font-mono text-xs">direktur@yarsi-ntb.ac.id</code>
          </div>
          <div class="bg-gray-50 rounded-lg p-2 flex justify-between items-center">
            <span class="text-gray-600">Staff:</span>
            <code class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded font-mono text-xs">staff@yarsi-ntb.ac.id</code>
          </div>
          <div class="bg-gray-50 rounded-lg p-2 flex justify-between items-center">
            <span class="text-gray-600">Instansi:</span>
            <code class="bg-purple-100 text-purple-700 px-2 py-0.5 rounded font-mono text-xs">rs@yarsi-ntb.ac.id</code>
          </div>
        </div>
        <p class="text-xs text-gray-400 mt-3">Password: <code class="bg-gray-100 px-1.5 py-0.5 rounded">password123</code></p>
      </div>
    </div>

    {{-- Copyright --}}
    <p class="text-center text-xs text-gray-400 mt-6">
      © {{ date('Y') }} Yayasan YARSI NTB. All rights reserved.
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
