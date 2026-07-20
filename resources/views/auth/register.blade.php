<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Akun Baru Admin | Tangga Mas</title>

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
        
        <!-- Ukuran Lebar Menggunakan max-w-2xl agar sejajar dan serasi dengan Box Login -->
        <div class="w-full max-w-2xl bg-white shadow-2xl rounded-2xl border border-gray-100 px-8 py-10 sm:px-12 transition-all duration-300">
            
            <!-- Header Brand Tangga Mas -->
            <div class="text-center mb-8">
                <a href="{{ url('/') }}" class="inline-block mb-4">
                    <img src="{{ asset('images/logotm.png') }}" class="h-12 w-auto object-contain mx-auto" alt="Logo Tangga Mas Scaffolding">
                </a>
                <h4 class="text-3xl font-extrabold text-gray-800 tracking-tight">Daftar Akun Admin Baru</h4>
                <!-- <p class="text-sm text-gray-500 mt-2">Buat akun akses baru untuk pengelolaan database operasional Tangga Mas</p> -->
            </div>

            <!-- Form Registrasi -->
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-user text-gray-400 text-base"></i> Nama Lengkap
                    </label>
                    <input 
                        id="name" 
                        class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1BBC9A] focus:ring-1 focus:ring-[#1BBC9A] transition-all" 
                        type="text" 
                        name="name" 
                        value="{{ old('name') }}" 
                        required 
                        autofocus 
                        placeholder="Masukkan nama lengkap Anda"
                        autocomplete="name" 
                    />
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alamat Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-envelope text-gray-400 text-base"></i> Alamat Email Resmi
                    </label>
                    <input 
                        id="email" 
                        class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1BBC9A] focus:ring-1 focus:ring-[#1BBC9A] transition-all" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        placeholder="nama.admin@tanggapas.com"
                        autocomplete="username" 
                    />
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kata Sandi -->
                <div>
                    <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-lock text-gray-400 text-base"></i> Kata Sandi Baru
                    </label>
                    <input 
                        id="password" 
                        class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1BBC9A] focus:ring-1 focus:ring-[#1BBC9A] transition-all"
                        type="password"
                        name="password"
                        required 
                        placeholder="Minimal 8 karakter unik"
                        autocomplete="new-password" 
                    />
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konfirmasi Kata Sandi -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-shield text-gray-400 text-base"></i> Ulangi Kata Sandi
                    </label>
                    <input 
                        id="password_confirmation" 
                        class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1BBC9A] focus:ring-1 focus:ring-[#1BBC9A] transition-all"
                        type="password"
                        name="password_confirmation"
                        required 
                        placeholder="Masukkan kembali kata sandi di atas"
                        autocomplete="new-password" 
                    />
                    @error('password_confirmation')
                        <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tombol Submit Pendaftaran -->
                <div class="pt-4">
                    <button type="submit" class="w-full bg-[#1BBC9A] hover:bg-[#0C5646] text-white font-bold text-sm py-4 px-4 rounded-xl shadow-md shadow-emerald-100 hover:shadow-none transition-all duration-200 flex items-center justify-center gap-2 tracking-wide cursor-pointer">
                        <i class="fa-solid fa-user-plus"></i>
                        <span>Daftarkan Akun Sistem</span>
                    </button>
                </div>
            </form>

            <!-- Pembatas Menu Kembali -->
            <div class="relative flex py-6 items-center">
                <div class="flex-grow border-t border-gray-100"></div>
                <span class="flex-shrink mx-4 text-xs text-gray-400 font-bold uppercase tracking-widest">Sudah Punya Akun</span>
                <div class="flex-grow border-t border-gray-100"></div>
            </div>

            <!-- Tombol Kembali Ke Halaman Login -->
            <div class="text-center">
                <a href="{{ route('login') }}" class="inline-flex w-full items-center justify-center gap-2 border border-gray-200 hover:border-[#1BBC9A] hover:bg-emerald-50/20 text-gray-700 hover:text-[#1BBC9A] font-bold text-sm py-3.5 px-4 rounded-xl transition-all duration-200 cursor-pointer">
                    <i class="fa-solid fa-right-to-bracket text-xs"></i>
                    <span>Kembali Menuju Halaman Login</span>
                </a>
            </div>

        </div>

    </div>

</body>
</html>