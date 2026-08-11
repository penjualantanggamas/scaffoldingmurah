<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Akun | Tangga Mas</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS Direct CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              'sans': ['"Inter"', 'sans-serif'],
            }
          }
        }
      }
    </script>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50">

    <div class="min-h-screen flex flex-col sm:justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
        
        <!-- UKURAN DIPASTIKAN LEBAR -->
        <div class="w-full max-w-2xl bg-white shadow-2xl rounded-2xl border border-gray-100 px-8 py-10 sm:px-12 transition-all duration-300">
            
            <!-- Header Brand Tangga Mas -->
            <div class="text-center mb-10">
                <a href="{{ url('/') }}" class="inline-block mb-4">
                    <img src="{{ asset('images/logotm.png') }}" class="h-14 w-auto object-contain mx-auto" alt="Logo Tangga Mas Scaffolding">
                </a>
            </div>

            <!-- Status Sesi Otentikasi -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Form Autentikasi -->
            <form method="POST" action="{{ route('customer.login') }}" class="space-y-6">
                @csrf

                <!-- Input Alamat Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-envelope text-gray-400 text-base"></i>Email
                    </label>
                    <input 
                        id="email" 
                        class="block w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1BBC9A] focus:ring-1 focus:ring-[#1BBC9A] transition-all" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus 
                    />
                </div>

                <!-- Input Kata Sandi -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-lock text-gray-400 text-base"></i> Kata Sandi
                        </label>
                    </div>
                    <input 
                        id="password" 
                        class="block w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1BBC9A] focus:ring-1 focus:ring-[#1BBC9A] transition-all"
                        type="password"
                        name="password"
                        required 
                    />
                </div>

                <!-- Opsi Ingat Akun -->
                <div class="flex items-center justify-between pt-1">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[#1BBC9A] focus:ring-[#1BBC9A] shadow-sm transition-all" name="remember">
                        <span class="ms-2.5 text-sm text-gray-600 font-medium">Ingat akun saya di perangkat ini</span>
                    </label>
                </div>

                <!-- Tombol Submit Masuk -->
                <div class="pt-2">
                    <button type="submit" class="w-full bg-[#1BBC9A] hover:bg-[#0C5646] text-white font-bold text-sm py-4 px-4 rounded-xl shadow-md shadow-emerald-100 hover:shadow-none transition-all duration-200 flex items-center justify-center gap-2 tracking-wide cursor-pointer">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span>Masuk ke Sistem Transaksi</span>
                    </button>
                </div>
            </form>

            <!-- Pembatas Menu Pendaftaran -->
            <div class="relative flex py-8 items-center">
                <div class="flex-grow border-t border-gray-100"></div>
                <span class="flex-shrink mx-4 text-xs text-gray-400 font-bold uppercase tracking-widest">Belum Punya Akun?</span>
                <div class="flex-grow border-t border-gray-100"></div>
            </div>

            <!-- Bagian Tombol Registrasi Akun Baru -->
            <div class="text-center">
                <a href="{{ route('customer.register') }}" class="inline-flex w-full items-center justify-center gap-2 border border-gray-200 hover:border-[#1BBC9A] hover:bg-emerald-50/20 text-gray-700 hover:text-[#1BBC9A] font-bold text-sm py-3.5 px-4 rounded-xl transition-all duration-200 cursor-pointer">
                    <i class="fa-solid fa-user-plus text-xs"></i>
                    <span>Daftar Akun Baru</span>
                </a>
            </div>

        </div>
    </div>

</body>
</html>