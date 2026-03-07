@extends('layouts.app')

@section('content')

{{-- ═══════════════════════════════════
     HERO
════════════════════════════════════ --}}
<section id="top" class="relative min-h-screen flex flex-col justify-center px-[5vw] pt-24 pb-32 overflow-hidden">
  <p class="hero-eyebrow font-heading text-sky tracking-[.35em] text-sm mb-4 opacity-0">
    Strengthened Through Every Season &middot; 2026
  </p>
  <h1 class="hero-title font-heading text-white leading-[.92] mb-6 opacity-0"
      style="font-size:clamp(3.5rem,10vw,8.5rem);text-shadow:0 4px 30px rgba(70,120,200,.35)">
    INVENTORY<br>
    <span class="text-ice">WGG</span>
  </h1>
  <p class="hero-sub font-body text-white/70 max-w-lg leading-relaxed mb-10 opacity-0"
     style="font-size:clamp(.9rem,1.5vw,1.1rem)">
    Kelola stok barang secara realtime. Tambah, edit, dan hapus data inventory.
  </p>
  <a href="#inventory"
     class="hero-cta inline-flex items-center gap-2 opacity-0 w-fit px-8 py-3 rounded-full font-semibold text-sm tracking-widest uppercase text-white transition-all duration-200 hover:-translate-y-1"
     style="background:linear-gradient(135deg,#7ec8e3,#4a7fb5);box-shadow:0 6px 28px rgba(80,160,230,.4)">
    Lihat Inventory
  </a>
  <div class="absolute right-[6vw] bottom-[10vh] font-heading text-white/10 tracking-[.15em]"
       style="font-size:7rem;writing-mode:vertical-rl">2026</div>
</section>

{{-- ═══════════════════════════════════
     INVENTORY SECTION
════════════════════════════════════ --}}
<section id="inventory" class="relative px-[5vw] pb-24">

  <h2 class="font-display text-white mb-1 reveal" style="font-size:clamp(2rem,5vw,3.5rem);text-shadow:0 2px 16px rgba(120,180,240,.5)">
    Inventory
  </h2>
  <p class="font-body text-white/50 tracking-widest uppercase text-sm mb-10 reveal">Kelola semua data barang</p>

  {{-- Stats Row --}}
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-10" id="statsRow">
    @php
      $statDefs = [
        ['label'=>'Total Barang',   'value'=>$stats['total'],     'badge'=>'Produk',   'cls'=>'text-sky/80   bg-sky/10   border-sky/30'],
        ['label'=>'Total Stok',     'value'=>$stats['total_qty'], 'badge'=>'Unit',     'cls'=>'text-emerald-300 bg-emerald-400/10 border-emerald-400/30'],
        ['label'=>'Stok Aman',      'value'=>$stats['ok'],        'badge'=>'Aman',     'cls'=>'text-emerald-300 bg-emerald-400/10 border-emerald-400/30'],
        ['label'=>'Stok Rendah',    'value'=>$stats['warn'],      'badge'=>'Rendah',   'cls'=>'text-yellow-300  bg-yellow-400/10  border-yellow-400/30'],
        ['label'=>'Stok Habis',     'value'=>$stats['low'],       'badge'=>'Kritis',   'cls'=>'text-red-300     bg-red-400/10     border-red-400/30'],
      ];
    @endphp

    @foreach($statDefs as $i => $s)
    <div class="glass rounded-2xl p-5 flex flex-col gap-1 transition-all duration-200 hover:-translate-y-1 hover:shadow-xl reveal"
         style="animation-delay:{{ $i * .08 }}s">
      <span class="text-white/50 text-xs tracking-widest uppercase">{{ $s['label'] }}</span>
      <span class="font-heading text-4xl text-white tracking-wide" id="stat-{{ $i }}">{{ $s['value'] }}</span>
      <span class="text-xs font-semibold tracking-wider px-2 py-0.5 rounded-full border inline-block w-fit {{ $s['cls'] }}">{{ $s['badge'] }}</span>
    </div>
    @endforeach
  </div>

  {{-- Add Form --}}
  <div id="add-form" class="glass rounded-2xl p-6 mb-6 reveal">
    <h3 class="font-display text-2xl text-white mb-5">Tambah Barang Baru</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
      <div class="flex flex-col gap-2">
        <label class="text-white/60 text-xs tracking-widest uppercase font-semibold">Nama Barang</label>
        <input id="f-name" type="text" class="wgg-input" placeholder="cth. Laptop Gaming"/>
      </div>
      <div class="flex flex-col gap-2">
        <label class="text-white/60 text-xs tracking-widest uppercase font-semibold">Jumlah Stok</label>
        <input id="f-qty" type="number" class="wgg-input" placeholder="0" min="0"/>
      </div>
      <div class="flex flex-col gap-2">
        <label class="text-white/60 text-xs tracking-widest uppercase font-semibold">Kategori</label>
        <select id="f-cat" class="wgg-input">
          <option value="">— Pilih Kategori —</option>
          @foreach(['Elektronik','Pakaian','Makanan','Peralatan','Kesehatan','Lainnya'] as $cat)
          <option value="{{ $cat }}">{{ $cat }}</option>
          @endforeach
        </select>
      </div>
      <div class="flex flex-col gap-2">
        <label class="text-white/60 text-xs tracking-widest uppercase font-semibold">Stok Minimum</label>
        <input id="f-min" type="number" class="wgg-input" placeholder="5" min="0"/>
      </div>
    </div>
    <button onclick="addItem()"
            class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-sm tracking-widest uppercase text-white transition-all hover:-translate-y-0.5"
            style="background:linear-gradient(135deg,#5ab4e0,#4a7fb5);box-shadow:0 4px 18px rgba(90,180,224,.35)">
      ＋ Tambahkan
    </button>
  </div>

  {{-- Table Card --}}
  <div class="glass rounded-2xl p-6 reveal">

    {{-- Search & Filter --}}
    <div class="flex flex-wrap gap-3 mb-5 items-center">
      <div class="relative flex-1 min-w-[200px]">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-white/40 pointer-events-none">🔍</span>
        <input id="searchInput" type="text" class="wgg-input pl-9" placeholder="Cari nama barang…" oninput="filterTable()"/>
      </div>
      <select id="filterCat" class="wgg-input w-auto" onchange="filterTable()">
        <option value="">Semua Kategori</option>
        @foreach(['Elektronik','Pakaian','Makanan','Peralatan','Kesehatan','Lainnya'] as $cat)
        <option value="{{ $cat }}">{{ $cat }}</option>
        @endforeach
      </select>
      <select id="filterStock" class="wgg-input w-auto" onchange="filterTable()">
        <option value="">Semua Stok</option>
        <option value="ok">Stok Aman</option>
        <option value="warn">Stok Rendah</option>
        <option value="low">Stok Habis</option>
      </select>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto rounded-xl border border-white/20">
      <table class="w-full border-collapse text-sm" id="mainTable">
        <thead>
          <tr style="background:rgba(60,100,160,.35)">
            <th class="px-4 py-3 text-left text-white/55 text-xs tracking-widest uppercase font-semibold border-b border-white/15">#</th>
            <th class="px-4 py-3 text-left text-white/55 text-xs tracking-widest uppercase font-semibold border-b border-white/15">Nama Barang</th>
            <th class="px-4 py-3 text-left text-white/55 text-xs tracking-widest uppercase font-semibold border-b border-white/15">Kategori</th>
            <th class="px-4 py-3 text-left text-white/55 text-xs tracking-widest uppercase font-semibold border-b border-white/15">Stok</th>
            <th class="px-4 py-3 text-left text-white/55 text-xs tracking-widest uppercase font-semibold border-b border-white/15">Kondisi</th>
            <th class="px-4 py-3 text-left text-white/55 text-xs tracking-widest uppercase font-semibold border-b border-white/15">Aksi</th>
          </tr>
        </thead>
        <tbody id="inventoryBody">
          @forelse($items as $i => $item)
          <tr class="item-row border-b border-white/[.07] hover:bg-white/[.06] transition-colors duration-150"
              data-id="{{ $item->id }}"
              data-name="{{ strtolower($item->name) }}"
              data-cat="{{ $item->category }}"
              data-status="{{ $item->stock_status }}">
            <td class="px-4 py-3 text-white/35 text-xs">{{ $i + 1 }}</td>
            <td class="px-4 py-3 font-semibold text-white/90">{{ $item->name }}</td>
            <td class="px-4 py-3">
              <span class="cat-pill cat-{{ strtolower($item->category) }} text-xs font-semibold tracking-wider px-3 py-0.5 rounded-full border">
                {{ $item->category }}
              </span>
            </td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-2">
                <strong class="min-w-[2rem] text-white/90">{{ $item->qty }}</strong>
                <div class="h-1.5 rounded-full bg-white/10 overflow-hidden w-16">
                  @php $pct = $item->min_stock > 0 ? min(100, round($item->qty / ($item->min_stock * 3) * 100)) : ($item->qty > 0 ? 60 : 0); @endphp
                  <div class="stock-fill {{ $item->stock_status === 'ok' ? 'bg-gradient-to-r from-emerald-400 to-teal-300' : ($item->stock_status === 'warn' ? 'bg-gradient-to-r from-yellow-400 to-amber-300' : 'bg-gradient-to-r from-red-400 to-rose-300') }}"
                       style="width:{{ $pct }}%"></div>
                </div>
              </div>
            </td>
            <td class="px-4 py-3">
              @php
                $cls = $item->stock_status === 'ok'
                  ? 'text-emerald-300 bg-emerald-400/10 border-emerald-400/30'
                  : ($item->stock_status === 'warn'
                    ? 'text-yellow-300 bg-yellow-400/10 border-yellow-400/30'
                    : 'text-red-300 bg-red-400/10 border-red-400/30');
              @endphp
              <span class="text-xs font-semibold tracking-wider px-2 py-0.5 rounded-full border {{ $cls }}">
                {{ $item->stock_label }}
              </span>
            </td>
            <td class="px-4 py-3">
              <div class="flex gap-2">
                <button onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->qty }}, '{{ $item->category }}', {{ $item->min_stock }})"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold tracking-wider uppercase text-white transition-all hover:-translate-y-0.5"
                        style="background:linear-gradient(135deg,#f0c050,#d4922a);box-shadow:0 3px 10px rgba(240,192,80,.3)">
                  Edit
                </button>
                <button onclick="openDeleteModal({{ $item->id }}, '{{ addslashes($item->name) }}')"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold tracking-wider uppercase text-white transition-all hover:-translate-y-0.5"
                        style="background:linear-gradient(135deg,#e07070,#c04040);box-shadow:0 3px 10px rgba(224,112,112,.3)">
                  Hapus
                </button>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="px-4 py-16 text-center text-white/35">
              <div class="text-5xl mb-3">📦</div>
              <div>Belum ada barang. Tambahkan yang pertama!</div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Empty filtered state --}}
    <div id="emptyFilter" class="hidden text-center py-16 text-white/35">
      <div class="text-5xl mb-3">🔍</div>
      <div>Tidak ada barang yang sesuai filter.</div>
    </div>

    {{-- TOMBOL EXPORT EXCEL --}}
    <div class="mt-6 flex justify-end border-t border-white/10 pt-5">
      <button onclick="exportToExcel()"
              class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl font-semibold text-sm tracking-widest uppercase text-white transition-all hover:-translate-y-0.5"
              style="background:linear-gradient(135deg,#10b981,#059669);box-shadow:0 4px 15px rgba(16,185,129,.3)">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        Export ke Excel
      </button>
    </div>

  </div>

  {{-- ═══════════════════════════════════
       HISTORY / LOG SECTION
  ════════════════════════════════════ --}}
  <div class="mt-12 reveal">
    <h3 class="font-display text-2xl text-white mb-6">Riwayat Aktivitas</h3>
    <div class="glass rounded-2xl p-6 max-h-[400px] overflow-y-auto" id="logContainer">
      @if(isset($logs) && $logs->count() > 0)
        <div class="relative border-l border-white/20 ml-3 pl-6 space-y-6" id="logList">
          @foreach($logs as $log)
          <div class="relative log-item">
            <div class="absolute -left-[31px] top-1.5 w-3 h-3 rounded-full {{ str_contains($log->action, 'Hapus') ? 'bg-red-400' : (str_contains($log->action, 'Baru') ? 'bg-sky' : 'bg-emerald-400') }} shadow-[0_0_8px_currentColor]"></div>
            
            <div class="flex flex-col sm:flex-row sm:items-baseline gap-1 sm:gap-3 mb-1">
              <span class="font-semibold text-white/90 text-sm">{{ $log->item_name }}</span>
              <span class="text-white/40 text-[10px] tracking-wider uppercase">{{ $log->created_at->diffForHumans() }} &middot; {{ $log->created_at->format('d M Y, H:i') }}</span>
            </div>
            
            <div class="text-xs font-semibold px-2 py-0.5 rounded border inline-block mb-2 
              {{ str_contains($log->action, 'Hapus') ? 'text-red-300 bg-red-400/10 border-red-400/30' : (str_contains($log->action, 'Baru') ? 'text-sky/90 bg-sky/10 border-sky/30' : 'text-emerald-300 bg-emerald-400/10 border-emerald-400/30') }}">
              {{ $log->action }}
            </div>
            
            <p class="text-white/60 text-sm leading-relaxed">"{{ $log->notes }}"</p>
          </div>
          @endforeach
        </div>
      @else
        <div id="emptyLog" class="text-center py-8 text-white/35">
          <div class="text-4xl mb-3">🕒</div>
          <div>Belum ada riwayat aktivitas.</div>
        </div>
      @endif
    </div>
  </div>

</section>

@endsection

{{-- ═══════════════════════════════════
     MODALS
════════════════════════════════════ --}}
@push('modals')

{{-- Edit Modal --}}
<div id="editModal" class="modal-overlay">
  <div class="modal-box">
    <h3 class="font-display text-3xl text-white mb-6">Edit Barang</h3>
    <input type="hidden" id="e-id"/>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
      <div class="flex flex-col gap-2">
        <label class="text-white/60 text-xs tracking-widest uppercase font-semibold">Nama Barang</label>
        <input id="e-name" type="text" class="wgg-input"/>
      </div>
      <div class="flex flex-col gap-2">
        <label class="text-white/60 text-xs tracking-widest uppercase font-semibold">Jumlah Stok</label>
        <input id="e-qty" type="number" class="wgg-input" min="0"/>
      </div>
      <div class="flex flex-col gap-2">
        <label class="text-white/60 text-xs tracking-widest uppercase font-semibold">Kategori</label>
        <select id="e-cat" class="wgg-input">
          @foreach(['Elektronik','Pakaian','Makanan','Peralatan','Kesehatan','Lainnya'] as $cat)
          <option value="{{ $cat }}">{{ $cat }}</option>
          @endforeach
        </select>
      </div>
      <div class="flex flex-col gap-2">
        <label class="text-white/60 text-xs tracking-widest uppercase font-semibold">Stok Minimum</label>
        <input id="e-min" type="number" class="wgg-input" min="0"/>
      </div>
      
      {{-- INPUT NOTES BARU --}}
      <div class="flex flex-col gap-2 sm:col-span-2 mt-2">
        <label class="text-white/60 text-xs tracking-widest uppercase font-semibold">Catatan / Notes (Opsional)</label>
        <textarea id="e-notes" class="wgg-input resize-none h-20" placeholder="Kenapa diubah? (cth. Barang rusak, restock bulanan...)"></textarea>
      </div>

    </div>
    <div class="flex justify-end gap-3">
      <button onclick="closeEditModal()"
              class="px-5 py-2.5 rounded-xl text-sm font-semibold tracking-wider uppercase text-white"
              style="background:linear-gradient(135deg,#e07070,#c04040)">Batal</button>
      <button onclick="saveEdit()"
              class="px-5 py-2.5 rounded-xl text-sm font-semibold tracking-wider uppercase text-white"
              style="background:linear-gradient(135deg,#4ecfa0,#2faa80)">Simpan</button>
    </div>
  </div>
</div>

{{-- Delete Modal --}}
<div id="deleteModal" class="modal-overlay">
  <div class="modal-box" style="max-width:400px">
    <h3 class="font-display text-3xl text-white mb-4">Hapus Barang?</h3>
    <p class="text-white/70 leading-relaxed text-sm mb-1">Yakin ingin menghapus barang</p>
    <p class="font-semibold text-white mb-5" id="deleteItemName"></p>
    <p class="text-white/50 text-xs mb-6">Tindakan ini tidak dapat dibatalkan.</p>
    <div class="flex justify-end gap-3">
      <button onclick="closeDeleteModal()"
              class="px-5 py-2.5 rounded-xl text-sm font-semibold tracking-wider uppercase text-white"
              style="background:linear-gradient(135deg,#f0c050,#d4922a)">Batal</button>
      <button onclick="confirmDelete()"
              class="px-5 py-2.5 rounded-xl text-sm font-semibold tracking-wider uppercase text-white"
              style="background:linear-gradient(135deg,#e07070,#c04040)">Hapus</button>
    </div>
  </div>
</div>

@endpush

{{-- ═══════════════════════════════════
     SCRIPTS
════════════════════════════════════ --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>

<style>
  .wgg-input[type="number"] {
    /* Memaksa browser merender UI bawaan (seperti panah) ke mode gelap */
    color-scheme: dark;
  }

  /* Modifikasi panah untuk browser berbasis WebKit (Chrome, Edge, Safari) */
  .wgg-input[type="number"]::-webkit-inner-spin-button,
  .wgg-input[type="number"]::-webkit-outer-spin-button {
    cursor: pointer;
    opacity: 0.5; /* Bikin agak transparan biar nge-blend */
    transition: opacity 0.2s ease;
  }

  /* Saat di-hover, panahnya jadi lebih terang */
  .wgg-input[type="number"]::-webkit-inner-spin-button:hover,
  .wgg-input[type="number"]::-webkit-outer-spin-button:hover {
    opacity: 1;
  }

  .cat-pill.cat-elektronik { color:#90c8ff; background:rgba(90,160,240,.2); border-color:rgba(90,160,240,.4); }
  .cat-pill.cat-pakaian    { color:#d8b0ff; background:rgba(200,150,240,.2); border-color:rgba(200,150,240,.4); }
  .cat-pill.cat-makanan    { color:#70eeb0; background:rgba(100,220,160,.18); border-color:rgba(100,220,160,.4); }
  .cat-pill.cat-peralatan  { color:#fcd877; background:rgba(240,180,90,.18); border-color:rgba(240,180,90,.4); }
  .cat-pill.cat-kesehatan  { color:#ff9a9a; background:rgba(240,110,110,.18); border-color:rgba(240,110,110,.4); }
  .cat-pill.cat-lainnya    { color:#ccc; background:rgba(180,180,180,.15); border-color:rgba(180,180,180,.3); }
</style>

<script>
  // ── GSAP ──
  window.addEventListener('load', () => {
    if (typeof gsap === 'undefined') return;
    gsap.registerPlugin(ScrollTrigger);

    gsap.to('.hero-eyebrow', { opacity:1, y:0, duration:.8, delay:.2 });
    gsap.to('.hero-title',   { opacity:1, y:0, duration:.9, delay:.45 });
    gsap.to('.hero-sub',     { opacity:1, y:0, duration:.8, delay:.7 });
    gsap.to('.hero-cta',     { opacity:1, y:0, duration:.8, delay:.9 });

    gsap.utils.toArray('.reveal').forEach((el, i) => {
      gsap.from(el, {
        opacity:0, y:28, duration:.75, delay: i * .1,
        scrollTrigger:{ trigger:el, start:'top 86%' }
      });
    });
  });

  // ── EXPORT TO EXCEL (VIA SHEETJS) ──
  function exportToExcel() {
    const rows = document.querySelectorAll('.item-row');
    const data = [];

    // Ambil data dari baris tabel HTML satu per satu
    rows.forEach((row, index) => {
      const name = row.querySelector('td:nth-child(2)').innerText.trim();
      const cat = row.querySelector('td:nth-child(3)').innerText.trim();
      const qty = row.querySelector('td:nth-child(4) strong').innerText.trim();
      const status = row.querySelector('td:nth-child(5)').innerText.trim();

      data.push({
        'No': index + 1,
        'Nama Barang': name,
        'Kategori': cat,
        'Jumlah Stok': parseInt(qty),
        'Kondisi': status
      });
    });

    // Validasi kalau tabelnya kosong
    if(data.length === 0) {
      Swal.fire({ icon: 'info', title: 'Data Kosong', text: 'Tidak ada data barang untuk diexport.', background: '#1e293b', color: '#fff' });
      return;
    }

    // Buat worksheet dan workbook
    const ws = XLSX.utils.json_to_sheet(data);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Inventory");

    // Simpan file dengan format YYYY-MM-DD
    const dateStr = new Date().toISOString().slice(0, 10);
    XLSX.writeFile(wb, `WGG_Inventory_${dateStr}.xlsx`);
  }

  // ── PREPEND LOG OTOMATIS (REAL-TIME UI) ──
  function prependLog(name, action, notes) {
    const container = document.getElementById('logContainer');
    const emptyState = document.getElementById('emptyLog');
    if(emptyState) emptyState.remove();

    const date = new Date();
    const timeString = date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    const dateString = date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });

    let colorCls = 'text-emerald-300 bg-emerald-400/10 border-emerald-400/30';
    let dotCls = 'bg-emerald-400';
    if(action.includes('Hapus')) { colorCls = 'text-red-300 bg-red-400/10 border-red-400/30'; dotCls = 'bg-red-400'; }
    else if(action.includes('Baru')) { colorCls = 'text-sky/90 bg-sky/10 border-sky/30'; dotCls = 'bg-sky'; }

    const div = document.createElement('div');
    div.className = 'relative log-item';
    div.innerHTML = `
      <div class="absolute -left-[31px] top-1.5 w-3 h-3 rounded-full ${dotCls} shadow-[0_0_8px_currentColor]"></div>
      <div class="flex flex-col sm:flex-row sm:items-baseline gap-1 sm:gap-3 mb-1">
        <span class="font-semibold text-white/90 text-sm">${name}</span>
        <span class="text-white/40 text-[10px] tracking-wider uppercase">Baru saja &middot; ${dateString}, ${timeString}</span>
      </div>
      <div class="text-xs font-semibold px-2 py-0.5 rounded border inline-block mb-2 ${colorCls}">
        ${action}
      </div>
      <p class="text-white/60 text-sm leading-relaxed">"${notes || 'Tanpa catatan'}"</p>
    `;

    if(!container.querySelector('.space-y-6')) {
       container.innerHTML = `<div class="relative border-l border-white/20 ml-3 pl-6 space-y-6" id="logList"></div>`;
    }
    
    document.getElementById('logList').prepend(div);
    if(typeof gsap !== 'undefined') gsap.from(div, { opacity: 0, x: -20, duration: 0.5 });
  }

  // ── FILTER TABLE ──
  function filterTable() {
    const q  = document.getElementById('searchInput').value.toLowerCase();
    const fc = document.getElementById('filterCat').value;
    const fs = document.getElementById('filterStock').value;
    const rows = document.querySelectorAll('.item-row');
    let visible = 0;
    rows.forEach(row => {
      const match = (!q  || row.dataset.name.includes(q)) &&
                    (!fc || row.dataset.cat === fc) &&
                    (!fs || row.dataset.status === fs);
      row.style.display = match ? '' : 'none';
      if (match) visible++;
    });
    document.getElementById('emptyFilter').classList.toggle('hidden', visible > 0);
  }

  // ── ADD ITEM ──
  async function addItem() {
    const name = document.getElementById('f-name').value.trim();
    const qty  = parseInt(document.getElementById('f-qty').value) || 0;
    const cat  = document.getElementById('f-cat').value;
    const min  = parseInt(document.getElementById('f-min').value) || 5;

    if (!name) { Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Nama barang wajib diisi!', background: '#1e293b', color: '#fff' }); return; }
    if (!cat)  { Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Pilih kategori terlebih dahulu!', background: '#1e293b', color: '#fff' }); return; }

    try {
      const response = await fetch('/items', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ name, qty, category: cat, min_stock: min })
      });
      
      const data = await response.json();

      if (!response.ok) {
        if (response.status === 422) {
          const errorMessages = Object.values(data.errors).flat().join('\n');
          Swal.fire({ icon: 'error', title: 'Validasi Gagal', text: errorMessages, background: '#1e293b', color: '#fff' });
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menambahkan barang.', background: '#1e293b', color: '#fff' });
        }
        return;
      }

      if (data.success) {
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: data.message, timer: 1500, showConfirmButton: false, background: '#1e293b', color: '#fff' });
        document.getElementById('f-name').value = '';
        document.getElementById('f-qty').value  = '';
        document.getElementById('f-cat').value  = '';
        document.getElementById('f-min').value  = '';
        
        appendRow(data.item);
        refreshStats();
        
        // Panggil Log Otomatis di UI
        prependLog(name, 'Barang Baru Ditambahkan', `Stok awal: ${qty} unit`);
      }
    } catch(e) { Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan sistem.', background: '#1e293b', color: '#fff' }); }
  }

  // ── APPEND ROW ──
  function appendRow(item) {
    const body = document.getElementById('inventoryBody');
    const placeholder = body.querySelector('td[colspan]');
    if (placeholder) placeholder.closest('tr').remove();

    const statusClsMap = {
      ok:   'text-emerald-300 bg-emerald-400/10 border-emerald-400/30',
      warn: 'text-yellow-300  bg-yellow-400/10  border-yellow-400/30',
      low:  'text-red-300     bg-red-400/10     border-red-400/30',
    };
    const fillClsMap = {
      ok:   'bg-gradient-to-r from-emerald-400 to-teal-300',
      warn: 'bg-gradient-to-r from-yellow-400 to-amber-300',
      low:  'bg-gradient-to-r from-red-400 to-rose-300',
    };
    const catCls = 'cat-' + item.category.toLowerCase();
    const s = item.stock_status;
    const pct = item.min_stock > 0 ? Math.min(100, Math.round(item.qty / (item.min_stock * 3) * 100)) : (item.qty > 0 ? 60 : 0);
    const idx = body.querySelectorAll('.item-row').length + 1;

    const tr = document.createElement('tr');
    tr.className = 'item-row border-b border-white/[.07] hover:bg-white/[.06] transition-colors duration-150';
    tr.dataset.id     = item.id;
    tr.dataset.name   = item.name.toLowerCase();
    tr.dataset.cat    = item.category;
    tr.dataset.status = s;
    tr.innerHTML = `
      <td class="px-4 py-3 text-white/35 text-xs">${idx}</td>
      <td class="px-4 py-3 font-semibold text-white/90">${item.name}</td>
      <td class="px-4 py-3"><span class="cat-pill ${catCls} text-xs font-semibold tracking-wider px-3 py-0.5 rounded-full border">${item.category}</span></td>
      <td class="px-4 py-3">
        <div class="flex items-center gap-2">
          <strong class="min-w-[2rem] text-white/90">${item.qty}</strong>
          <div class="h-1.5 rounded-full bg-white/10 overflow-hidden w-16">
            <div class="stock-fill ${fillClsMap[s]}" style="width:${pct}%"></div>
          </div>
        </div>
      </td>
      <td class="px-4 py-3"><span class="text-xs font-semibold tracking-wider px-2 py-0.5 rounded-full border ${statusClsMap[s]}">${item.stock_label}</span></td>
      <td class="px-4 py-3">
        <div class="flex gap-2">
          <button onclick="openEditModal(${item.id},'${item.name.replace(/'/g,"\\'")}',${item.qty},'${item.category}',${item.min_stock})"
                  class="px-3 py-1.5 rounded-lg text-xs font-semibold tracking-wider uppercase text-white hover:-translate-y-0.5 transition-all"
                  style="background:linear-gradient(135deg,#f0c050,#d4922a)">✏️ Edit</button>
          <button onclick="openDeleteModal(${item.id},'${item.name.replace(/'/g,"\\'")}')"
                  class="px-3 py-1.5 rounded-lg text-xs font-semibold tracking-wider uppercase text-white hover:-translate-y-0.5 transition-all"
                  style="background:linear-gradient(135deg,#e07070,#c04040)">🗑️</button>
        </div>
      </td>`;
    body.appendChild(tr);
    if (typeof gsap !== 'undefined') {
      gsap.from(tr, { opacity:0, y:16, duration:.4 });
    }
  }

  // ── EDIT MODAL & VALIDASI PERUBAHAN ──
  let editingId = null;
  let initialEditState = {}; // Variabel penyimpan data awal

  function openEditModal(id, name, qty, cat, min) {
    editingId = id;
    document.getElementById('e-id').value   = id;
    document.getElementById('e-name').value = name;
    document.getElementById('e-qty').value  = qty;
    document.getElementById('e-cat').value  = cat;
    document.getElementById('e-min').value  = min;
    document.getElementById('e-notes').value = ''; // Kosongkan notes saat dibuka
    
    // Simpan snapshot data sebelum diubah user
    initialEditState = {
      name: name,
      qty: parseInt(qty),
      cat: cat,
      min: parseInt(min)
    };

    document.getElementById('editModal').classList.add('active');
  }

  function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
    editingId = null;
  }

  async function saveEdit() {
    const id   = document.getElementById('e-id').value;
    const name = document.getElementById('e-name').value.trim();
    const qty  = parseInt(document.getElementById('e-qty').value);
    const cat  = document.getElementById('e-cat').value;
    const min  = parseInt(document.getElementById('e-min').value) || 0;
    const notes = document.getElementById('e-notes').value.trim();
    
    if (!name) { 
      Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Nama tidak boleh kosong!', background: '#1e293b', color: '#fff' }); 
      return; 
    }

    // CEK APAKAH ADA PERUBAHAN DATA
    if (
      name === initialEditState.name &&
      qty === initialEditState.qty &&
      cat === initialEditState.cat &&
      min === initialEditState.min
    ) {
      // Jika data sama persis, hentikan proses (jangan fetch ke backend)
      Swal.fire({ 
        icon: 'info', 
        title: 'Tidak Ada Perubahan', 
        text: 'Data barang tidak ada yang diubah.', 
        background: '#1e293b', 
        color: '#fff',
        timer: 2000,
        showConfirmButton: false
      });
      return; 
    }
    
    try {
      const response = await fetch(`/items/${id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ name, qty, category: cat, min_stock: min, notes: notes })
      });

      const data = await response.json();

      if (!response.ok) {
        if (response.status === 422) {
          const errorMessages = Object.values(data.errors).flat().join('\n');
          Swal.fire({ icon: 'error', title: 'Validasi Gagal', text: errorMessages, background: '#1e293b', color: '#fff' });
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Gagal menyimpan.', background: '#1e293b', color: '#fff' });
        }
        return;
      }

      if (data.success) {
        closeEditModal();
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: data.message, timer: 1500, showConfirmButton: false, background: '#1e293b', color: '#fff' });
        
        updateRowInDOM(data.item);
        refreshStats();

        // Panggil Log Otomatis di UI
        let actionText = 'Data Diperbarui';
        if (initialEditState.qty !== qty) {
          actionText = `Update Stok (${initialEditState.qty} ➔ ${qty})`;
        }
        prependLog(name, actionText, notes || 'Tanpa catatan');
      }
    } catch(e) { 
      Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan sistem.', background: '#1e293b', color: '#fff' }); 
    }
  }

  function updateRowInDOM(item) {
    const tr = document.querySelector(`.item-row[data-id="${item.id}"]`);
    if (!tr) return;

    tr.dataset.name = item.name.toLowerCase();
    tr.dataset.cat = item.category;
    tr.dataset.status = item.stock_status;

    const statusClsMap = {
      ok:   'text-emerald-300 bg-emerald-400/10 border-emerald-400/30',
      warn: 'text-yellow-300  bg-yellow-400/10  border-yellow-400/30',
      low:  'text-red-300     bg-red-400/10     border-red-400/30',
    };
    const fillClsMap = {
      ok:   'bg-gradient-to-r from-emerald-400 to-teal-300',
      warn: 'bg-gradient-to-r from-yellow-400 to-amber-300',
      low:  'bg-gradient-to-r from-red-400 to-rose-300',
    };
    
    const catCls = 'cat-' + item.category.toLowerCase();
    const s = item.stock_status;
    const pct = item.min_stock > 0 ? Math.min(100, Math.round(item.qty / (item.min_stock * 3) * 100)) : (item.qty > 0 ? 60 : 0);
    const idx = tr.querySelector('td:first-child').innerText;

    tr.innerHTML = `
      <td class="px-4 py-3 text-white/35 text-xs">${idx}</td>
      <td class="px-4 py-3 font-semibold text-white/90">${item.name}</td>
      <td class="px-4 py-3">
        <span class="cat-pill ${catCls} text-xs font-semibold tracking-wider px-3 py-0.5 rounded-full border">
          ${item.category}
        </span>
      </td>
      <td class="px-4 py-3">
        <div class="flex items-center gap-2">
          <strong class="min-w-[2rem] text-white/90">${item.qty}</strong>
          <div class="h-1.5 rounded-full bg-white/10 overflow-hidden w-16">
            <div class="stock-fill ${fillClsMap[s]}" style="width:${pct}%"></div>
          </div>
        </div>
      </td>
      <td class="px-4 py-3">
        <span class="text-xs font-semibold tracking-wider px-2 py-0.5 rounded-full border ${statusClsMap[s]}">
          ${item.stock_label}
        </span>
      </td>
      <td class="px-4 py-3">
        <div class="flex gap-2">
          <button onclick="openEditModal(${item.id}, '${item.name.replace(/'/g,"\\'")}', ${item.qty}, '${item.category}', ${item.min_stock})"
                  class="px-3 py-1.5 rounded-lg text-xs font-semibold tracking-wider uppercase text-white transition-all hover:-translate-y-0.5"
                  style="background:linear-gradient(135deg,#f0c050,#d4922a);box-shadow:0 3px 10px rgba(240,192,80,.3)">
            Edit
          </button>
          <button onclick="openDeleteModal(${item.id}, '${item.name.replace(/'/g,"\\'")}')"
                  class="px-3 py-1.5 rounded-lg text-xs font-semibold tracking-wider uppercase text-white transition-all hover:-translate-y-0.5"
                  style="background:linear-gradient(135deg,#e07070,#c04040);box-shadow:0 3px 10px rgba(224,112,112,.3)">
            Hapus
          </button>
        </div>
      </td>
    `;

    if (typeof gsap !== 'undefined') {
      gsap.from(tr, { backgroundColor: "rgba(255,255,255,0.15)", duration: 1 });
    }
  }

  // ── DELETE MODAL & ACTION (PAKAI SWEETALERT2) ──
  function openDeleteModal(id, name) {
    Swal.fire({
      title: 'Hapus Barang?',
      text: `Yakin ingin menghapus "${name}"? Tindakan ini tidak dapat dibatalkan.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#c04040',
      cancelButtonColor: '#d4922a',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal',
      background: '#1e293b',
      color: '#fff'
    }).then(async (result) => {
      if (result.isConfirmed) {
        try {
          const response = await fetch(`/items/${id}`, {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
          });

          const data = await response.json();

          if (data.success) {
            Swal.fire({
              icon: 'success',
              title: 'Terhapus!',
              text: data.message,
              timer: 1500,
              showConfirmButton: false,
              background: '#1e293b',
              color: '#fff'
            });

            const row = document.querySelector(`.item-row[data-id="${id}"]`);
            if (row) {
              if (typeof gsap !== 'undefined') {
                gsap.to(row, { 
                  opacity: 0, 
                  x: -30, 
                  duration: 0.3, 
                  onComplete: () => { 
                    row.remove(); 
                    refreshStats(); 
                    
                    // Panggil Log Otomatis di UI
                    prependLog(name, 'Barang Dihapus', 'Data barang telah dihapus permanen dari sistem');

                    const tbody = document.getElementById('inventoryBody');
                    if (tbody.querySelectorAll('.item-row').length === 0) {
                      tbody.innerHTML = `
                        <tr>
                          <td colspan="6" class="px-4 py-16 text-center text-white/35">
                            <div class="text-5xl mb-3">📦</div>
                            <div>Belum ada barang. Tambahkan yang pertama!</div>
                          </td>
                        </tr>
                      `;
                    }
                  } 
                });
              }
            }
          } else {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menghapus barang.', background: '#1e293b', color: '#fff' });
          }
        } catch(e) {
          Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan sistem.', background: '#1e293b', color: '#fff' });
        }
      }
    });
  }

  // ── CLOSE MODALS ON OUTSIDE CLICK ──
  document.getElementById('editModal').addEventListener('click',   e => { if(e.target===e.currentTarget) closeEditModal(); });
  document.getElementById('deleteModal').addEventListener('click', e => { if(e.target===e.currentTarget) closeDeleteModal(); });

  // ── REFRESH STATS VIA API ──
  async function refreshStats() {
    try {
      const response = await fetch('/items/stats', { headers: { 'Accept': 'application/json' } });
      const s = await response.json();
      const vals = [s.total, s.total_qty, s.ok, s.warn, s.low];
      vals.forEach((v, i) => {
        const el = document.getElementById(`stat-${i}`);
        if (el) { 
          if (typeof gsap !== 'undefined') {
            gsap.to(el, { innerText: v, duration:.6, snap:{innerText:1}, ease:'power1.out' }); 
          } else {
            el.innerText = v;
          }
        }
      });
    } catch(e) {}
  }
</script>
@endpush