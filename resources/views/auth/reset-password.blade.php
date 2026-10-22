<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password Baru | BPBD Kabupaten Probolinggo</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome & Tailwind CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-white min-h-screen font-sans text-slate-800 antialiased flex flex-col lg:flex-row"
      x-data="{ showPassword: false, showConfirmPassword: false }">

    <!-- LEFT COLUMN: Watercolor Banner & Official Agency Logo -->
    <div class="lg:w-1/2 min-h-[380px] lg:min-h-screen relative flex flex-col items-center justify-between p-8 sm:p-12 overflow-hidden shrink-0"
         style="background: linear-gradient(135deg, #fff0f3 0%, #fff8f6 40%, #e0f2fe 100%);">
        
        <!-- Background Watercolor Splash Accents -->
        <div class="absolute -top-20 -left-20 w-80 h-80 rounded-full bg-rose-300/30 blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/3 -right-20 w-96 h-96 rounded-full bg-blue-300/30 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-10 left-10 w-72 h-72 rounded-full bg-orange-300/20 blur-2xl pointer-events-none"></div>

        <!-- Top Content: Logos & Agency Title -->
        <div class="relative z-10 text-center my-auto space-y-6 max-w-md">
            <div class="flex items-center justify-center gap-4 sm:gap-6">
                <img src="{{ asset('images/logo-bpbd.svg') }}" 
                     alt="Logo BPBD Kabupaten Probolinggo" 
                     class="h-16 sm:h-20 w-auto object-contain drop-shadow-md">
                
                <div class="flex flex-col items-start justify-center">
                    <span class="text-xs font-semibold tracking-widest text-slate-500 italic uppercase">endless</span>
                    <span class="text-xl sm:text-2xl font-extrabold tracking-tight bg-gradient-to-r from-orange-600 via-amber-500 to-blue-600 bg-clip-text text-transparent">
                        probolinggo
                    </span>
                </div>
            </div>

            <div class="space-y-1.5 pt-2">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#1e293b] leading-snug tracking-tight">
                    BPBD Kabupaten Probolinggo
                </h2>
                <h3 class="text-sm sm:text-base font-bold text-slate-600">
                    Badan Penanggulangan Bencana Daerah
                </h3>
            </div>
        </div>

        <!-- Bottom Vector City Skyline Illustration -->
        <div class="relative z-10 w-full pt-8">
            <svg class="w-full h-24 sm:h-36 text-[#00a2ff]/30 fill-current" viewBox="0 0 1200 200" preserveAspectRatio="none">
                <path d="M0,200 L0,140 L30,140 L30,160 L60,160 L60,110 L100,110 L100,200 L140,200 L140,80 L190,80 L190,200 L230,200 L230,130 L280,130 L280,200 L340,200 L340,100 L390,100 L390,200 L450,200 L450,70 L510,70 L510,200 L580,200 L580,120 L640,120 L640,200 L710,200 L710,90 L770,90 L770,200 L840,200 L840,140 L900,140 L900,200 L970,200 L970,60 L1040,60 L1040,200 L1110,200 L1110,130 L1170,130 L1170,200 Z" />
            </svg>
        </div>
    </div>

    <!-- RIGHT COLUMN: Reset Password Form Container -->
    <div class="lg:w-1/2 flex items-center justify-center p-6 sm:p-12 lg:p-16 bg-white shrink-0 min-h-screen">
        <div class="max-w-md w-full space-y-6">
            
            <!-- Heading Title -->
            <div class="text-center pt-2 pb-2">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1e293b] tracking-tight">Reset Password Baru</h1>
                <p class="text-xs text-slate-500 font-medium mt-1.5">
                    Buat password baru yang aman untuk akun administrator Anda.
                </p>
            </div>

            <!-- Global Status Notification -->
            @if(session('status'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2.5">
                    <i class="fas fa-check-circle text-emerald-600 text-base shrink-0"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- Global Error Notification -->
            @if($errors->any())
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold flex items-center gap-2.5">
                    <i class="fas fa-exclamation-circle text-rose-500 text-base shrink-0"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Form Reset Password -->
            <form action="{{ route('password.update') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Email Field -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1 text-xs">
                        Email Administrator <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email', $email) }}" 
                           required 
                           placeholder="admin@bpbd.probolinggokab.go.id" 
                           class="w-full px-4 py-3 bg-[#f8fafc] border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#00a2ff] focus:bg-white focus:outline-none font-medium text-slate-800 text-sm placeholder:text-slate-400 transition">
                </div>

                <!-- Password Baru Field -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1 text-xs">
                        Password Baru <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" 
                               name="password" 
                               required 
                               placeholder="Minimal 6 karakter" 
                               class="w-full px-4 py-3 pr-11 bg-[#f8fafc] border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#00a2ff] focus:bg-white focus:outline-none font-medium text-slate-800 text-sm placeholder:text-slate-400 transition">
                        
                        <button type="button" 
                                @click="showPassword = !showPassword" 
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
                            <i class="fas" :class="showPassword ? 'fa-eye' : 'fa-eye-slash'"></i>
                        </button>
                    </div>
                </div>

                <!-- Konfirmasi Password Baru Field -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1 text-xs">
                        Konfirmasi Password Baru <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input :type="showConfirmPassword ? 'text' : 'password'" 
                               name="password_confirmation" 
                               required 
                               placeholder="Ulangi password baru" 
                               class="w-full px-4 py-3 pr-11 bg-[#f8fafc] border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#00a2ff] focus:bg-white focus:outline-none font-medium text-slate-800 text-sm placeholder:text-slate-400 transition">
                        
                        <button type="button" 
                                @click="showConfirmPassword = !showConfirmPassword" 
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
                            <i class="fas" :class="showConfirmPassword ? 'fa-eye' : 'fa-eye-slash'"></i>
                        </button>
                    </div>
                </div>

                <!-- Action Buttons (Kembali & Simpan Password) -->
                <div class="grid grid-cols-2 gap-3 pt-3">
                    <a href="{{ route('login') }}" 
                       class="py-3 px-4 bg-[#e2e8f0] hover:bg-[#cbd5e1] text-slate-700 font-bold text-center text-sm rounded-lg transition-colors flex items-center justify-center">
                        Batal
                    </a>

                    <button type="submit" 
                            class="py-3 px-4 bg-[#00a2ff] hover:bg-[#008de6] text-white font-bold text-center text-sm rounded-lg shadow-md transition-colors flex items-center justify-center gap-1.5">
                        <i class="fas fa-save text-xs"></i> Simpan Password Baru
                    </button>
                </div>
            </form>

        </div>
    </div>

</body>
</html>
