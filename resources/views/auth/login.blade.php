<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin | Diskominfo & BPBD Kabupaten Probolinggo</title>
    
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
      x-data="{ 
          email: 'admin@bpbd.probolinggokab.go.id', 
          password: 'BpbdProbolinggo2026!',
          showPassword: false,
          captchaCode: '',
          captchaInput: '',
          
          generateCaptcha() {
              const chars = 'rhdyf7k9ax3m258bvwzq';
              let code = '';
              for (let i = 0; i < 6; i++) {
                  code += chars.charAt(Math.floor(Math.random() * chars.length));
              }
              this.captchaCode = code;
          }
      }"
      x-init="generateCaptcha()">

    <!-- LEFT COLUMN: Watercolor Banner & Official Agency Logo (Matching Official Diskominfo Screenshot 100%) -->
    <div class="lg:w-1/2 min-h-[380px] lg:min-h-screen relative flex flex-col items-center justify-between p-8 sm:p-12 overflow-hidden shrink-0"
         style="background: linear-gradient(135deg, #fff0f3 0%, #fff8f6 40%, #e0f2fe 100%);">
        
        <!-- Background Watercolor Splash Accents -->
        <div class="absolute -top-20 -left-20 w-80 h-80 rounded-full bg-rose-300/30 blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/3 -right-20 w-96 h-96 rounded-full bg-blue-300/30 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-10 left-10 w-72 h-72 rounded-full bg-orange-300/20 blur-2xl pointer-events-none"></div>

        <!-- Top Content: Logos & Agency Title -->
        <div class="relative z-10 text-center my-auto space-y-6 max-w-md">
            <!-- Logos Row (Logo BPBD & Endless Probolinggo) -->
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

            <!-- Agency Name Text -->
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

    <!-- RIGHT COLUMN: Login Admin Form Container (Matching Official Diskominfo Screenshot 100%) -->
    <div class="lg:w-1/2 flex items-center justify-center p-6 sm:p-12 lg:p-16 bg-white shrink-0 min-h-screen">
        <div class="max-w-md w-full space-y-6">
            
            <!-- Heading Title -->
            <div class="text-center pt-2 pb-2">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1e293b] tracking-tight">Login Admin</h1>
            </div>

            <!-- Global Error Notification -->
            @if($errors->any())
                <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold flex items-center gap-2">
                    <i class="fas fa-exclamation-circle text-rose-500 text-sm"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Form Login Admin -->
            <form action="{{ route('login') }}" method="POST" class="space-y-5 text-xs">
                @csrf

                <!-- Hidden Input for Captcha Code Verification -->
                <input type="hidden" name="captcha_code" :value="captchaCode">

                <!-- 1. Username Field -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5 text-xs">
                        Username <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="email" 
                           required 
                           x-model="email" 
                           placeholder="Username" 
                           class="w-full px-4 py-3 bg-[#f8fafc] border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#00a2ff] focus:bg-white focus:outline-none font-medium text-slate-800 text-sm placeholder:text-slate-400 transition">
                </div>

                <!-- 2. Password Field with Eye Toggle -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5 text-xs">
                        Password
                    </label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" 
                               name="password" 
                               required 
                               x-model="password" 
                               placeholder="••••••••" 
                               class="w-full px-4 py-3 pr-11 bg-[#f8fafc] border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#00a2ff] focus:bg-white focus:outline-none font-medium text-slate-800 text-sm placeholder:text-slate-400 transition">
                        
                        <button type="button" 
                                @click="showPassword = !showPassword" 
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
                            <i class="fas" :class="showPassword ? 'fa-eye' : 'fa-eye-slash'"></i>
                        </button>
                    </div>
                </div>

                <!-- 3. CAPTCHA Field (Matching exact sample screenshot design) -->
                <div class="space-y-2">
                    <label class="block font-bold text-slate-700 text-xs">
                        Masukkan kode berikut
                    </label>

                    <!-- CAPTCHA Display & Refresh Button Container -->
                    <div class="flex items-center gap-2">
                        
                        <!-- 100% Fail-Proof Distorted CAPTCHA Box Container -->
                        <div class="relative flex-1 h-[52px] bg-[#f1f5f9] border border-slate-200 rounded-lg overflow-hidden flex items-center justify-between px-6 select-none shadow-inner"
                             style="background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 8px 8px;">
                            
                            <!-- SVG Wavy Background Noise Lines (Exact Sample Match) -->
                            <svg class="absolute inset-0 w-full h-full pointer-events-none opacity-50" xmlns="http://www.w3.org/2000/svg">
                                <path d="M 0 10 Q 50 40 100 10 T 200 20 T 300 10" stroke="#3b82f6" stroke-width="2" fill="none" />
                                <path d="M 0 35 Q 70 5 140 40 T 280 20" stroke="#ec4899" stroke-width="1.5" fill="none" />
                                <path d="M 0 20 Q 90 45 180 15 T 320 30" stroke="#10b981" stroke-width="2" fill="none" />
                                <line x1="10" y1="42" x2="280" y2="8" stroke="#db2777" stroke-width="1.5" />
                                <line x1="30" y1="5" x2="250" y2="45" stroke="#8b5cf6" stroke-width="1.5" />
                            </svg>

                            <!-- Stylized Characters (r h d y f 7 style with rotation & colors) -->
                            <div class="relative z-10 flex items-center justify-around w-full font-mono font-extrabold tracking-widest text-2xl sm:text-3xl">
                                <template x-for="(char, idx) in captchaCode.split('')" :key="idx">
                                    <span :style="`
                                            color: ${['#10b981', '#7c3aed', '#ec4899', '#2563eb', '#ca8a04', '#059669'][idx % 6]};
                                            transform: rotate(${((idx % 2 === 0 ? 1 : -1) * (8 + (idx * 3)))}deg) translateY(${idx % 2 === 0 ? -3 : 2}px);
                                            display: inline-block;
                                            font-family: 'Plus Jakarta Sans', monospace, sans-serif;
                                          `" 
                                          x-text="char">
                                    </span>
                                </template>
                            </div>
                        </div>

                        <!-- Green Refresh Button -->
                        <button type="button" 
                                @click="generateCaptcha()" 
                                title="Acak / Refresh Kode Captcha"
                                class="h-[52px] w-[54px] bg-[#2ecc71] hover:bg-[#27ae60] active:scale-95 text-white rounded-lg flex items-center justify-center text-lg shadow-sm transition-all shrink-0">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>

                    <!-- CAPTCHA Input Field -->
                    <input type="text" 
                           name="captcha_answer" 
                           required 
                           x-model="captchaInput" 
                           placeholder="Masukkan kode captcha..." 
                           class="w-full px-4 py-3 bg-[#f8fafc] border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#00a2ff] focus:bg-white focus:outline-none font-medium text-slate-800 text-sm placeholder:text-slate-400 transition">
                </div>

                <!-- Quick Demo Credentials Info -->
                <div class="bg-blue-50/70 p-3 rounded-lg border border-blue-100 text-[11px] text-blue-900 space-y-1">
                    <p class="font-bold flex items-center gap-1"><i class="fas fa-info-circle text-blue-600"></i> Akun Demo Uji Coba Admin:</p>
                    <div class="flex flex-wrap gap-2 pt-1">
                        <button type="button" @click="email = 'admin@bpbd.probolinggokab.go.id'; password='BpbdProbolinggo2026!';" class="px-2 py-1 bg-white border border-blue-200 rounded text-[10px] font-bold text-blue-700 hover:bg-blue-100">Super Admin (Full)</button>
                        <button type="button" @click="email = 'anggota@bpbd.probolinggokab.go.id'; password='BpbdProbolinggo2026!';" class="px-2 py-1 bg-white border border-blue-200 rounded text-[10px] font-bold text-slate-700 hover:bg-blue-100">Staf Pusdalops</button>
                    </div>
                </div>

                <!-- 4. Action Buttons (Kembali & Masuk) -->
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <!-- Kembali Button (Grey) -->
                    <a href="{{ route('home') }}" 
                       class="py-3 px-4 bg-[#e2e8f0] hover:bg-[#cbd5e1] text-slate-700 font-bold text-center text-sm rounded-lg transition-colors flex items-center justify-center">
                        Kembali
                    </a>

                    <!-- Masuk Button (Vibrant Blue) -->
                    <button type="submit" 
                            class="py-3 px-4 bg-[#00a2ff] hover:bg-[#008de6] text-white font-bold text-center text-sm rounded-lg shadow-md transition-colors flex items-center justify-center">
                        Masuk
                    </button>
                </div>
            </form>

        </div>
    </div>

</body>
</html>
