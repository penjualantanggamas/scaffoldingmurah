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

    @php
      $items = $cartItems ?? $cart ?? [];
    @endphp

    @if(!empty($items) && (is_array($items) || is_object($items)) && count($items) > 0)
    
    <!-- Form Checkout yang Mengirim Item Terpilih -->
    <form id="checkoutForm" action="{{ route('checkout.index') }}" method="GET">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        <!-- KOLOM KIRI: DAFTAR ITEM -->
        <div class="lg:col-span-2 space-y-3 w-full">
          
          <!-- Baris Header 'Pilih Semua' -->
          <div class="bg-white border border-gray-100 rounded-xl p-3.5 px-4 md:px-5 shadow-sm flex items-center justify-between">
            <label class="flex items-center gap-3 cursor-pointer text-xs md:text-sm font-bold text-gray-700 select-none">
              <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)" checked class="w-4 h-4 text-[#1BBC9A] accent-[#1BBC9A] rounded border-gray-300 focus:ring-[#1BBC9A] cursor-pointer">
              <span>Pilih Semua (<span id="totalAvailableItems">{{ count($items) }}</span> Produk)</span>
            </label>
            <button type="button" onclick="deleteSelectedItems()" class="text-xs text-red-500 hover:text-red-700 font-semibold transition-colors flex items-center gap-1">
              <i class="fa-solid fa-trash-can text-xs"></i> Hapus Terpilih
            </button>
          </div>

          <!-- List Item Produk -->
          @foreach($items as $id => $item)
          @php
              $itemArr = is_array($item) ? $item : (array) $item;
              $namaProduk = $itemArr['nama_produk'] ?? 'Produk Scaffolding';
              $harga = $itemArr['harga'] ?? 0;
              $qty = $itemArr['quantity'] ?? $itemArr['jumlah'] ?? 1;
              $gambar = $itemArr['gambar'] ?? 'logotm.png';
              $slug = $itemArr['slug'] ?? '#';
              $subtotalItem = $harga * $qty;
          @endphp
          <div id="cart-row-{{ $id }}" class="bg-white border border-gray-100 rounded-xl p-4 md:p-5 shadow-sm hover:shadow-md transition-shadow relative group flex items-start gap-3 md:gap-4">
            
            <!-- Checkbox Per Produk -->
            <div class="pt-2 shrink-0">
              <input type="checkbox" 
                     name="selected_items[]" 
                     value="{{ $id }}" 
                     data-price="{{ $harga }}" 
                     data-qty-id="qty-input-{{ $id }}" 
                     onchange="calculateSummary()" 
                     checked 
                     class="item-checkbox w-4 h-4 text-[#1BBC9A] accent-[#1BBC9A] rounded border-gray-300 focus:ring-[#1BBC9A] cursor-pointer">
            </div>

            <!-- Konten Produk -->
            <div class="flex-1 flex gap-3 md:gap-4 min-w-0">
              <!-- Gambar Produk -->
              <a href="{{ url('/products/detail/' . $slug) }}" class="w-16 h-16 md:w-20 md:h-20 bg-gray-50 border border-gray-100 rounded-lg flex items-center justify-center shrink-0 overflow-hidden">
                <img src="{{ !empty($gambar) ? asset('images/products/' . $gambar) : asset('images/logotm.png') }}" 
                  class="w-full h-full object-contain p-1.5 transition-transform duration-300 hover:scale-110" 
                  onerror="this.src='{{ asset('images/logotm.png') }}'" 
                  alt="{{ $namaProduk }}">
              </a>

              <!-- Detail Produk -->
              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                  <div class="min-w-0">
                    <p class="text-[10px] md:text-xs text-gray-400 mb-0.5">{{ $itemArr['ukuran_varian'] ?? $itemArr['spesifikasi'] ?? 'Material Scaffolding' }}</p>
                    <h3 class="font-bold text-gray-800 text-xs md:text-sm leading-snug line-clamp-2">{{ $namaProduk }}</h3>
                  </div>
                  <!-- Tombol Hapus -->
                  <button type="button" onclick="removeFromCart('{{ $id }}')" class="text-gray-300 hover:text-red-500 transition-colors p-1 shrink-0 cursor-pointer" title="Hapus">
                    <i class="fa-solid fa-trash-can text-xs md:text-sm"></i>
                  </button>
                </div>

                <!-- Harga & Quantity -->
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-2.5 mt-3">
                  <div>
                    <span class="text-[10px] text-gray-400 block mb-0.5">Harga Satuan</span>
                    <span class="text-brand-price font-bold text-xs md:text-sm">Rp {{ number_format($harga, 0, ',', '.') }}</span>
                  </div>

                  <div class="flex items-center gap-3">
                    <!-- Quantity Control -->
                    <div class="flex items-center border border-gray-200 rounded-lg bg-white overflow-hidden shadow-sm">
                      <button type="button" onclick="changeQty('{{ $id }}', -1)" class="w-7 h-7 flex items-center justify-center text-gray-500 hover:bg-gray-100 hover:text-brand-green transition-colors font-bold text-xs cursor-pointer">−</button>
                      <input type="number" id="qty-input-{{ $id }}" value="{{ $qty }}" min="1" onchange="updateCartQty('{{ $id }}', this.value)" class="w-10 h-7 text-center bg-gray-50 border-x border-gray-200 text-xs font-bold focus:outline-none focus:ring-0 p-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                      <button type="button" onclick="changeQty('{{ $id }}', 1)" class="w-7 h-7 flex items-center justify-center text-gray-500 hover:bg-gray-100 hover:text-brand-green transition-colors font-bold text-xs cursor-pointer">+</button>
                    </div>

                    <!-- Subtotal Desktop -->
                    <div class="text-right hidden sm:block">
                      <span class="text-[10px] text-gray-400 block mb-0.5">Subtotal</span>
                      <span id="subtotal-{{ $id }}" class="text-gray-900 font-bold text-xs md:text-sm">Rp {{ number_format($subtotalItem, 0, ',', '.') }}</span>
                    </div>
                  </div>
                </div>

                <!-- Subtotal Mobile -->
                <div class="sm:hidden mt-2 pt-2 border-t border-gray-50 flex justify-between items-center">
                  <span class="text-[10px] text-gray-400">Subtotal</span>
                  <span class="text-gray-900 font-bold text-xs">Rp {{ number_format($subtotalItem, 0, ',', '.') }}</span>
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
            <div class="bg-white px-5 py-3.5 border-b border-gray-100">
              <h2 class="text-sm md:text-base font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-receipt text-brand-green text-xs"></i> Ringkasan Pesanan
              </h2>
            </div>

            <div class="p-5">
              <!-- Detail Item Terpilih -->
              <div class="space-y-2.5 text-xs md:text-sm text-gray-500 mb-4">
                <div class="flex justify-between">
                  <span>Produk Terpilih</span>
                  <span class="font-semibold text-gray-700" id="summary-selected-item">0 Material</span>
                </div>
                <div class="flex justify-between">
                  <span>Total Kuantitas</span>
                  <span class="font-semibold text-gray-700" id="summary-selected-qty">0 Unit</span>
                </div>
              </div>

              <hr class="border-gray-100 mb-4">

              <!-- Estimasi Total -->
              <div class="flex justify-between items-center mb-5">
                <span class="text-gray-800 font-bold text-sm">Estimasi Total</span>
                <span class="text-brand-price font-extrabold text-base md:text-lg" id="summary-total-price">
                  Rp 0
                </span>
              </div>

              <!-- Tombol Checkout (Submit Form) -->
              <button type="submit" id="btnCheckout" class="w-full bg-[#1BBC9A] hover:bg-[#0C5646] text-white font-bold text-sm py-3.5 rounded-xl transition-all shadow-sm hover:shadow-md flex items-center justify-center gap-2 cursor-pointer active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed">
              </i> Pesan Sekarang
              </button>

              <p class="text-[10px] text-center text-gray-400 mt-3 leading-relaxed">
                Hanya produk yang dicentang yang akan diproses.
              </p>

              <!-- Trust Badges -->
              <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-center gap-4 text-[10px] text-gray-400">
                <span class="flex items-center gap-1"><i class="fa-solid fa-shield-halved text-brand-green"></i> Berkuailitas</span>
                <span class="flex items-center gap-1"><i class="fa-solid fa-truck-fast text-brand-green"></i> Kirim Seluruh Indonesia</span>
              </div>
            </div>
          </div>
        </div>

      </div>
    </form>

    @else
    <!-- KERANJANG KOSONG -->
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

<!-- ENGINE AJAX & JAVASCRIPT KALKULASI SELEKTIF -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  calculateSummary();
});

// 1. Fungsi Pilih Semua / Uncheck Semua
function toggleSelectAll(selectAllCheckbox) {
  const checkboxes = document.querySelectorAll('.item-checkbox');
  checkboxes.forEach(cb => {
    cb.checked = selectAllCheckbox.checked;
  });
  calculateSummary();
}

// 2. Kalkulasi Ringkasan Berdasarkan Produk Yang Dicentang
function calculateSummary() {
  const checkboxes = document.querySelectorAll('.item-checkbox');
  const selectAllCheckbox = document.getElementById('selectAllCheckbox');
  const btnCheckout = document.getElementById('btnCheckout');
  
  let selectedCount = 0;
  let totalQty = 0;
  let totalPrice = 0;

  checkboxes.forEach(cb => {
    if (cb.checked) {
      selectedCount++;
      const price = parseFloat(cb.getAttribute('data-price')) || 0;
      const qtyInputId = cb.getAttribute('data-qty-id');
      const qtyInput = document.getElementById(qtyInputId);
      const qty = parseInt(qtyInput ? qtyInput.value : 1) || 1;

      totalQty += qty;
      totalPrice += (price * qty);
    }
  });

  // Update Tampilan Ringkasan
  document.getElementById('summary-selected-item').innerText = `${selectedCount} Material`;
  document.getElementById('summary-selected-qty').innerText = `${totalQty} Unit`;
  document.getElementById('summary-total-price').innerText = `Rp ${new Intl.NumberFormat('id-ID').format(totalPrice)}`;

  // Sync Checkbox 'Pilih Semua'
  if (selectAllCheckbox) {
    selectAllCheckbox.checked = (checkboxes.length > 0 && selectedCount === checkboxes.length);
  }

  // Nonaktifkan tombol checkout jika tidak ada item yang dicentang
  if (btnCheckout) {
    btnCheckout.disabled = (selectedCount === 0);
  }
}

// 3. Ubah Kuantitas
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
  if (parseInt(newQty) < 1) return;
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
    if (data.success) {
      calculateSummary();
    }
  });
}

// 4. Hapus Satu Item
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
        if (data.success) {
          const row = document.getElementById(`cart-row-${id}`);
          if (row) row.remove();

          const badge = document.getElementById('cartBadgeCount');
          if (badge) {
            badge.innerText = data.cart_count;
            if (data.cart_count > 0) badge.classList.remove('hidden');
            else badge.classList.add('hidden');
          }

          calculateSummary();

          // Reload jika keranjang habis
          const remainingRows = document.querySelectorAll('.item-checkbox');
          if (remainingRows.length === 0) {
            location.reload();
          }
        }
      });
    }
  });
}

// 5. Hapus Massal Produk Terpilih
function deleteSelectedItems() {
  const selectedCheckboxes = document.querySelectorAll('.item-checkbox:checked');
  if (selectedCheckboxes.length === 0) {
    Swal.fire({ text: 'Pilih minimal satu produk untuk dihapus.', icon: 'info' });
    return;
  }

  Swal.fire({
    title: `Hapus ${selectedCheckboxes.length} item terpilih?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#EF4444',
    cancelButtonColor: '#9CA3AF',
    confirmButtonText: 'Ya, Hapus Semua',
    cancelButtonText: 'Batal',
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      const deletePromises = Array.from(selectedCheckboxes).map(cb => {
        return fetch("{{ route('cart.remove') }}", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({ id: cb.value })
        });
      });

      Promise.all(deletePromises).then(() => {
        location.reload();
      });
    }
  });
}

// Validasi Form Checkout
document.getElementById('checkoutForm')?.addEventListener('submit', function(e) {
  const selectedCheckboxes = document.querySelectorAll('.item-checkbox:checked');
  if (selectedCheckboxes.length === 0) {
    e.preventDefault();
    Swal.fire({
      icon: 'warning',
      title: 'Pilih Produk',
      text: 'Silakan pilih minimal 1 produk yang ingin dibeli.',
      confirmButtonColor: '#1BBC9A'
    });
  }
});
</script>
@endsection