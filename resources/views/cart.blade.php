@extends('layouts.frontend')

@section('title', 'Keranjang Belanja | Tangga Mas Scaffolding')

@section('content')
<div class="bg-gray-50 min-h-screen">
  <div class="container mx-auto px-4 py-6 md:py-10 max-w-6xl">

    <!-- Breadcrumb -->
    <nav class="text-xs text-gray-400 mb-4 flex items-center gap-1.5">
      <a href="{{ url('/') }}" class="hover:text-brand-green transition-colors">Beranda</a>
      <i class="fa-solid fa-chevron-right text-[8px]"></i>
      <span class="text-gray-600 font-medium">Keranjang Belanja</span>
    </nav>

    @if(count($cart) > 0)
    <!-- Grid 2 kolom: daftar item + ringkasan -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

      <!-- KOLOM KIRI: DAFTAR ITEM -->
      <div class="lg:col-span-2 space-y-3 w-full">
        @foreach($cart as $id => $item)
        <div id="cart-row-{{ $id }}" class="bg-white border border-gray-100 rounded-xl p-4 md:p-5 shadow-sm hover:shadow-md transition-shadow relative group">
          <div class="flex gap-4">

            <!-- Gambar Produk -->
            <a href="{{ url('/products/detail/' . ($item['slug'] ?? '#')) }}" class="w-20 h-20 md:w-24 md:h-24 bg-gray-50 border border-gray-100 rounded-lg flex items-center justify-center shrink-0 overflow-hidden">
              <img src="{{ $item['gambar'] ? asset('images/products/' . $item['gambar']) : asset('images/logotm.png') }}" 
                class="w-full h-full object-contain p-1.5 transition-transform duration-300 hover:scale-110" 
                onerror="this.src='{{ asset('images/logotm.png') }}'" 
                alt="{{ $item['nama_produk'] }}">
            </a>

            <!-- Detail Produk -->
            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between gap-2">
                <div class="min-w-0">
                  <p class="text-[10px] md:text-xs text-gray-400 mb-0.5">{{ $item['spesifikasi'] ?? 'Material Scaffolding' }}</p>
                  <h3 class="font-bold text-gray-800 text-sm md:text-base leading-snug line-clamp-2">{{ $item['nama_produk'] }}</h3>
                </div>
                <!-- Tombol Hapus -->
                <button onclick="removeFromCart('{{ $id }}')" class="text-gray-300 hover:text-red-500 transition-colors p-1 shrink-0 cursor-pointer" title="Hapus">
                  <i class="fa-solid fa-trash-can text-xs md:text-sm"></i>
                </button>
              </div>

              <!-- Harga & Quantity -->
              <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 mt-3">
                <div>
                  <span class="text-[10px] text-gray-400 block mb-0.5">Harga Satuan</span>
                  <span class="text-brand-price font-bold text-sm md:text-base">Rp {{ number_format($item['harga'], 0, ',', '.') }}</span>
                </div>

                <div class="flex items-center gap-3">
                  <!-- Quantity Control -->
                  <div class="flex items-center border border-gray-200 rounded-lg bg-white overflow-hidden shadow-sm">
                    <button onclick="changeQty('{{ $id }}', -1)" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100 hover:text-brand-green transition-colors font-bold text-sm cursor-pointer">−</button>
                    <input type="number" id="qty-input-{{ $id }}" value="{{ $item['quantity'] }}" min="1" onchange="updateCartQty('{{ $id }}', this.value)" class="w-10 h-8 text-center bg-gray-50 border-x border-gray-200 text-xs font-bold focus:outline-none focus:ring-0 p-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                    <button onclick="changeQty('{{ $id }}', 1)" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100 hover:text-brand-green transition-colors font-bold text-sm cursor-pointer">+</button>
                  </div>

                  <!-- Subtotal Desktop -->
                  <div class="text-right hidden sm:block">
                    <span class="text-[10px] text-gray-400 block mb-0.5">Subtotal</span>
                    <span class="text-gray-900 font-bold text-sm">Rp {{ number_format($item['harga'] * $item['quantity'], 0, ',', '.') }}</span>
                  </div>
                </div>
              </div>

              <!-- Subtotal Mobile -->
              <div class="sm:hidden mt-2 pt-2 border-t border-gray-50 flex justify-between items-center">
                <span class="text-[10px] text-gray-400">Subtotal</span>
                <span class="text-gray-900 font-bold text-sm">Rp {{ number_format($item['harga'] * $item['quantity'], 0, ',', '.') }}</span>
              </div>
            </div>

          </div>
        </div>
        @endforeach
      </div>

      <!-- KOLOM KANAN: RINGKASAN PESANAN -->
      <div class="w-full">
        <div class="bg-white border border-gray-100 rounded-xl shadow-sm sticky top-24 overflow-hidden">
          <!-- Header Ringkasan -->
          <div class="bg-white-50 px-5 py-3.5 border-b border-white-100">
            <h2 class="text-sm md:text-base font-bold text-gray-800 flex items-center gap-2">
              <i class="fa-solid fa-receipt text-brand-green text-xs"></i> Ringkasan Pesanan
            </h2>
          </div>

          <div class="p-5">
            <!-- Detail Item -->
            <div class="space-y-2.5 text-xs md:text-sm text-gray-500 mb-4">
              <div class="flex justify-between">
                <span>Total Item</span>
                <span class="font-semibold text-gray-700" id="summary-total-item">{{ count($cart) }} Material</span>
              </div>
              <div class="flex justify-between">
                <span>Total Kuantitas</span>
                @php $totalQty = 0; foreach($cart as $item) { $totalQty += $item['quantity']; } @endphp
                <span class="font-semibold text-gray-700">{{ $totalQty }} Unit</span>
              </div>
            </div>

            <hr class="border-gray-100 mb-4">

            <!-- Estimasi Total -->
            @php
              $total = 0;
              foreach($cart as $item) { $total += $item['harga'] * $item['quantity']; }
            @endphp
            <div class="flex justify-between items-center mb-5">
              <span class="text-gray-800 font-bold text-sm">Estimasi Total</span>
              <span class="text-brand-price font-extrabold text-base md:text-lg" id="summary-total-price">
                Rp {{ number_format($total, 0, ',', '.') }}
              </span>
            </div>

            <!-- Tombol WhatsApp -->
            <button onclick="sendToWhatsApp()" class="w-full bg-[#1BBC9A] text-white font-bold text-sm py-3.5 rounded-xl hover:bg-[#0C5646] transition-all shadow-sm hover:shadow-md flex items-center justify-center gap-2 cursor-pointer active:scale-[0.98]">
              <i class="fa-brands fa-whatsapp text-lg"></i> Ajukan Penawaran
            </button>

            <p class="text-[10px] text-center text-gray-400 mt-3 leading-relaxed">
              Daftar material di atas akan otomatis dikirim sebagai pesan WhatsApp ke tim sales kami.
            </p>

            <!-- Trust Badges -->
            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-center gap-4 text-[10px] text-gray-400">
              <span class="flex items-center gap-1"><i class="fa-solid fa-shield-halved text-brand-green"></i> Produk Asli</span>
              <span class="flex items-center gap-1"><i class="fa-solid fa-truck-fast text-brand-green"></i> Kirim Seluruh Indonesia</span>
            </div>
          </div>
        </div>
      </div>

    </div>

    @else
    <!-- KERANJANG KOSONG - Full width, centered -->
    <div class="flex items-center justify-center py-20 md:py-28">
      <div class="text-center max-w-md mx-auto">
        <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-5">
          <i class="fa-solid fa-basket-shopping text-4xl text-gray-300"></i>
        </div>
        <h2 class="text-gray-800 text-lg font-bold mb-2">Keranjang Anda Kosong</h2>
        <p class="text-gray-400 text-sm max-w-xs mx-auto mb-6">Belum ada material scaffolding yang ditambahkan ke keranjang belanja Anda.</p>
        <a href="{{ url('/products') }}" class="inline-flex items-center gap-2 bg-[#1BBC9A] text-white text-sm font-bold px-6 py-3 rounded-xl hover:bg-[#0C5646] transition-colors shadow-sm">
          <i class="fa-solid fa-box-open text-xs"></i> Jelajahi Produk
        </a>
      </div>
    </div>
    @endif

  </div>
</div>

<!-- ENGINE AJAX & FORMATTER WA -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function changeQty(id, change) {
  const input = document.getElementById(`qty-input-${id}`);
  if (!input) return;
  let currentVal = parseInt(input.value) || 1;
  let newVal = currentVal + change;
  if (newVal < 1) newVal = 1;
  input.value = newVal;
  updateCartQty(id, newVal);
}

function updateCartQty(id, newQty) {
  if(parseInt(newQty) < 1) return;
  fetch("{{ route('cart.update') }}", {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({ id: id, quantity: newQty })
  })
  .then(response => response.json())
  .then(data => {
    if(data.success) {
      location.reload();
    }
  });
}

function removeFromCart(id) {
  Swal.fire({
    title: 'Hapus item ini?',
    text: 'Item akan dihapus dari keranjang belanja.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#EF4444',
    cancelButtonColor: '#9CA3AF',
    confirmButtonText: 'Ya, Hapus',
    cancelButtonText: 'Batal',
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      fetch("{{ route('cart.remove') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ id: id })
      })
      .then(response => response.json())
      .then(data => {
        if(data.success) {
          document.getElementById(`cart-row-${id}`).remove();
          const badge = document.getElementById('cartBadgeCount');
          if(badge) badge.innerText = data.cart_count;
          Swal.fire({ title: 'Terhapus!', text: data.message, icon: 'success', timer: 1500, showConfirmButton: false }).then(() => {
            location.reload();
          });
        }
      });
    }
  });
}

function sendToWhatsApp() {
  const cartData = @json($cart);
  let message = "*Halo Tangga Mas Scaffolding,*\n";
  message += "Saya ingin mengajukan permohonan penawaran harga untuk daftar kebutuhan material proyek berikut:\n\n";
  let index = 1;
  let grandTotal = 0;
  for (const id in cartData) {
    const item = cartData[id];
    const subtotal = item.harga * item.quantity;
    grandTotal += subtotal;
    message += `${index}. *${item.nama_produk}*\n`;
    message += `   Kuantitas: ${item.quantity} unit\n`;
    message += `   Harga: Rp ${new Intl.NumberFormat('id-ID').format(item.harga)} /unit\n`;
    message += `   Subtotal: Rp ${new Intl.NumberFormat('id-ID').format(subtotal)}\n\n`;
    index++;
  }
  message += `-------------------------------------------\n`;
  message += `*Estimasi Total Kebutuhan:* Rp ${new Intl.NumberFormat('id-ID').format(grandTotal)}\n\n`;
  message += "Mohon bantuannya untuk informasi ketersediaan stok produk dan perhitungan ongkos kirim armada pabrik. Terima kasih.";

  const phone = "628123651717";
  const url = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
  window.open(url, '_blank');
}
</script>
@endsection