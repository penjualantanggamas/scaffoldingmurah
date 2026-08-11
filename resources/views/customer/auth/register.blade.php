<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Akun | Tangga Mas</title>

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
        
        <!-- UKURAN LEBAR DUA SISI NYAMAN -->
        <div class="w-full max-w-2xl bg-white shadow-2xl rounded-2xl border border-gray-100 px-8 py-10 sm:px-12 transition-all duration-300">
            
            <!-- Header Brand Tangga Mas -->
            <div class="text-center mb-8">
                <a href="{{ url('/') }}" class="inline-block mb-4">
                    <img src="{{ asset('images/logotm.png') }}" class="h-14 w-auto object-contain mx-auto" alt="Logo Tangga Mas Scaffolding">
                </a>
                <h2 class="text-2xl font-extrabold text-gray-800 tracking-tight">Registrasi</h2>
            </div>

            <!-- Status Sesi Otentikasi Error -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm font-medium space-y-1">
                    @foreach ($errors->all() as $error)
                        <p>• {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Form Autentikasi Registrasi -->
            <form method="POST" action="{{ route('customer.register') }}" class="space-y-5">
                @csrf

                <!-- Nama Lengkap -->
                <div>
                    <label for="nama_lengkap" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-user text-gray-400 text-base"></i> Nama Lengkap<span class="text-red-500">*</span>
                    </label>
                    <input 
                        id="nama_lengkap" 
                        class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1BBC9A] focus:ring-1 focus:ring-[#1BBC9A] transition-all" 
                        type="text" 
                        name="nama_lengkap" 
                        value="{{ old('nama_lengkap') }}" 
                        required 
                        placeholder="Contoh: Budi Santoso"
                    />
                </div>

                <!-- Email & No HP Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-envelope text-gray-400 text-base"></i>Email <span class="text-red-500">*</span>
                        </label>
                        <input 
                            id="email" 
                            class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1BBC9A] focus:ring-1 focus:ring-[#1BBC9A] transition-all" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            placeholder="budi@perusahaan.com"
                        />
                    </div>
                    <div>
                        <label for="no_hp" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-phone text-gray-400 text-base"></i>Nomor Telepon<span class="text-red-500">*</span>
                        </label>
                        <input 
                            id="no_hp" 
                            class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1BBC9A] focus:ring-1 focus:ring-[#1BBC9A] transition-all" 
                            type="text" 
                            name="no_hp" 
                            value="{{ old('no_hp') }}" 
                            required 
                            placeholder="Contoh: 08123456789"
                        />
                    </div>
                </div>

                <!-- Password Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-gray-100 pt-4">
                    <div>
                        <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-lock text-gray-400 text-base"></i>Password <span class="text-red-500">*</span>
                        </label>
                        <input 
                            id="password" 
                            class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1BBC9A] focus:ring-1 focus:ring-[#1BBC9A] transition-all" 
                            type="password" 
                            name="password" 
                            required 
                            placeholder="Minimal 6 karakter"
                        />
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-shield text-gray-400 text-base"></i> Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <input 
                            id="password_confirmation" 
                            class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1BBC9A] focus:ring-1 focus:ring-[#1BBC9A] transition-all" 
                            type="password" 
                            name="password_confirmation" 
                            required 
                            placeholder="Ketik ulang password"
                        />
                    </div>
                </div>

                <!-- Tombol Submit Registrasi -->
                <div class="pt-3">
                    <button type="submit" class="w-full bg-[#1BBC9A] hover:bg-[#0C5646] text-white font-bold text-sm py-4 px-4 rounded-xl shadow-md shadow-emerald-100 hover:shadow-none transition-all duration-200 flex items-center justify-center gap-2 tracking-wide cursor-pointer">
                        <i class="fa-solid fa-user-plus"></i>
                        <span>Registrasi Akun</span>
                    </button>
                </div>
            </form>

            <!-- Pembatas Menu Login -->
            <div class="relative flex py-6 items-center">
                <div class="flex-grow border-t border-gray-100"></div>
                <span class="flex-shrink mx-4 text-xs text-gray-400 font-bold uppercase tracking-widest">Sudah Punya Akun?</span>
                <div class="flex-grow border-t border-gray-100"></div>
            </div>

            <!-- Bagian Tombol Balik ke Login -->
            <div class="text-center">
                <a href="{{ route('customer.login') }}" class="inline-flex w-full items-center justify-center gap-2 border border-gray-200 hover:border-[#1BBC9A] hover:bg-emerald-50/20 text-gray-700 hover:text-[#1BBC9A] font-bold text-sm py-3.5 px-4 rounded-xl transition-all duration-200 cursor-pointer">
                    <i class="fa-solid fa-right-to-bracket text-xs"></i>
                    <span>Login</span>
                </a>
            </div>

        </div>
    </div>

</body>
</html>