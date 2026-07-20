<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin | Tangga Mas Scaffolding</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              'brand-green': '#1BBC9A',
              'brand-green-dark': '#0C5646',
              'brand-gray-bg': '#f9fafb',
              'brand-price': '#16a34a'
            },
            fontFamily: { 'sans': ['"Inter"', 'sans-serif'] }
          }
        }
      }
    </script>
</head>
<body class="font-sans text-gray-800 bg-gray-50 antialiased flex flex-col min-h-screen">

    <header class="bg-slate-900 text-white border-b border-slate-800 sticky top-0 z-50 shadow-md">
      <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">

          <div class="flex items-center gap-3">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
              <img src="{{ asset('images/logotmputih.png') }}" class="h-7 w-auto object-contain" alt="Logo Tangga Mas">
            </a>
            <!-- <span class="bg-brand-green/20 text-brand-green text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded border border-brand-green/30">
                Control Panel
            </span> -->
          </div>

          <nav class="flex items-center gap-1 sm:gap-4 text-xs sm:text-sm font-medium text-slate-300">
            <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-lg hover:text-white hover:bg-slate-800 transition-all {{ Request::is('admin/dashboard') ? 'bg-slate-800 text-brand-green font-semibold' : '' }}">
              <i class="fa-solid fa-chart-line mr-1.5 text-xs"></i>Dashboard
            </a>
            <a href="{{ route('produk.index') }}" class="px-3 py-2 rounded-lg hover:text-white hover:bg-slate-800 transition-all {{ Request::is('admin/produk*') ? 'bg-slate-800 text-brand-green font-semibold' : '' }}">
              <i class="fa-solid fa-boxes-stacked mr-1.5 text-xs"></i>Produk CRUD
            </a>
          </nav>
          <!-- <div class="flex items-center gap-4">
            <a href="{{ url('/products') }}" target="_blank" class="text-xs text-slate-400 hover:text-white flex items-center gap-1.5 bg-slate-800 px-3 py-1.5 rounded-lg border border-slate-700/50 transition-colors">
              <i class="fa-solid fa-globe"></i> <span class="hidden md:inline">Lihat Toko</span>
            </a>
          </div> -->
          <!-- Tombol Logout Khusus di Halaman Panel Admin -->
          <div class="flex items-center gap-4">
              <!-- Penanda Akun -->
              <span class="text-sm text-gray-600 font-medium">
                  <i class="fa-solid fa-user-shield text-gray-400 mr-1"></i> {{ Auth::user()->name }}
              </span>

              <!-- Form Keluar -->
              <form method="POST" action="{{ route('logout') }}" class="inline">
                  @csrf
                  <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold px-3 py-2 rounded-lg transition-all flex items-center gap-2 border border-red-200 shadow-sm">
                      <i class="fa-solid fa-right-from-bracket"></i>
                      <span>Log Out</span>
                  </button>
              </form>
          </div>
        </div>
      </div>
    </header>

    <main class="flex-grow">
        @yield('content')
    </main>

</body>
</html>