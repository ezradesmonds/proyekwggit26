<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}"/>
  <title>WGG Inventory — Strengthened Through Every Season</title>

  {{-- Google Fonts --}}
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Bebas+Neue&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&display=swap" rel="stylesheet"/>

  {{-- GSAP --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" defer></script>

  {{-- Tailwind CDN (for prototype — in production use Vite + npm) --}}
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            display: ['"Dancing Script"', 'cursive'],
            heading: ['"Bebas Neue"', 'sans-serif'],
            body:    ['"DM Sans"', 'sans-serif'],
          },
          colors: {
            sky:      '#7ec8e3',
            ice:      '#b6dff5',
            deep:     '#3a5f8a',
            dusk:     '#6b5fa5',
            mist:     '#9b8fc4',
            lavender: '#c8bfea',
            snow:     '#eaf4fb',
          },
        }
      }
    }
  </script>

  <style>
    :root {
      --glass:        rgba(255,255,255,0.13);
      --glass-border: rgba(255,255,255,0.30);
    }

    html { scroll-behavior: smooth; }

    body {
      font-family: 'DM Sans', sans-serif;
      background: linear-gradient(160deg, #4a7fb5 0%, #6b5fa5 40%, #8e7fc9 70%, #b6a8e0 100%);
      background-attachment: fixed;
      min-height: 100vh;
    }

    /* ── Ripple bg ── */
    .ripple {
      position: fixed; border-radius: 50%; pointer-events: none;
      background: radial-gradient(circle, rgba(120,190,230,0.18) 0%, transparent 70%);
      animation: drift linear infinite;
    }
    .r1{width:700px;height:300px;top:15%;left:-10%;animation-duration:22s;}
    .r2{width:500px;height:200px;top:50%;right:-8%;animation-duration:28s;animation-delay:-10s;}
    .r3{width:600px;height:250px;bottom:10%;left:20%;animation-duration:35s;animation-delay:-18s;}
    @keyframes drift{0%{transform:translateX(0)scaleY(1);}50%{transform:translateX(40px)scaleY(1.1);}100%{transform:translateX(0)scaleY(1);}}

    /* ── Ice blobs ── */
    .ice-blob { position: fixed; pointer-events: none; z-index: 0; opacity:.55; animation: float ease-in-out infinite; }
    @keyframes float{0%,100%{transform:translateY(0);}50%{transform:translateY(-18px);}}

    /* ── Glass ── */
    .glass {
      background: var(--glass);
      backdrop-filter: blur(18px);
      -webkit-backdrop-filter: blur(18px);
      border: 1px solid var(--glass-border);
    }

    /* ── Form / input styles ── */
    .wgg-input {
      width: 100%;
      background: rgba(255,255,255,0.10);
      border: 1px solid rgba(255,255,255,0.28);
      border-radius: 10px;
      padding: .7rem 1rem;
      color: #fff;
      font-family: 'DM Sans', sans-serif;
      font-size: .92rem;
      outline: none;
      transition: border-color .2s, background .2s;
    }
    .wgg-input::placeholder { color: rgba(255,255,255,0.38); }
    .wgg-input:focus { border-color: #7ec8e3; background: rgba(255,255,255,0.16); }
    .wgg-input option { background: #3a5f8a; color: #fff; }

    /* ── Stock bar ── */
    .stock-fill { height: 100%; border-radius: 3px; transition: width .5s ease; }

    /* ── Modal ── */
    .modal-overlay {
      position: fixed; inset: 0; z-index: 200;
      background: rgba(20,30,60,.72);
      backdrop-filter: blur(8px);
      display: flex; align-items: center; justify-content: center;
      opacity: 0; pointer-events: none;
      transition: opacity .25s;
    }
    .modal-overlay.active { opacity: 1; pointer-events: all; }
    .modal-box {
      background: linear-gradient(135deg, rgba(55,85,145,.88), rgba(85,65,155,.88));
      backdrop-filter: blur(24px);
      border: 1px solid var(--glass-border);
      border-radius: 24px;
      padding: 2.2rem;
      width: 90%; max-width: 520px;
      transform: translateY(30px) scale(.96);
      transition: transform .28s cubic-bezier(.34,1.56,.64,1);
    }
    .modal-overlay.active .modal-box { transform: translateY(0) scale(1); }

    /* ── Toast ── */
    .toast {
      background: rgba(40,70,130,.9);
      backdrop-filter: blur(16px);
      border: 1px solid var(--glass-border);
      border-radius: 12px;
      padding: .8rem 1.4rem;
      font-size: .88rem; color: #fff;
      animation: toastIn .3s ease;
      min-width: 240px;
    }
    @keyframes toastIn{from{transform:translateX(60px);opacity:0;}to{transform:translateX(0);opacity:1;}}

    /* ── Scrollbar ── */
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.25); border-radius: 3px; }
  </style>
</head>
<body class="text-white overflow-x-hidden">

  {{-- BG decorations --}}
  <div class="ripple r1" style="z-index:0"></div>
  <div class="ripple r2" style="z-index:0"></div>
  <div class="ripple r3" style="z-index:0"></div>

  {{-- Ice blobs --}}
  <div class="ice-blob" style="bottom:-30px;left:-40px;width:320px;animation-duration:8s;z-index:0">
    <svg viewBox="0 0 320 180" fill="none" xmlns="http://www.w3.org/2000/svg">
      <ellipse cx="160" cy="130" rx="160" ry="70" fill="url(#ig1)"/>
      <ellipse cx="90" cy="105" rx="80" ry="50" fill="url(#ig2)"/>
      <ellipse cx="220" cy="110" rx="60" ry="40" fill="url(#ig2)"/>
      <defs>
        <radialGradient id="ig1" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="#cdeeff"/><stop offset="100%" stop-color="#88c4e8"/></radialGradient>
        <radialGradient id="ig2" cx="50%" cy="30%" r="60%"><stop offset="0%" stop-color="#eaf7ff"/><stop offset="100%" stop-color="#9ad4ef"/></radialGradient>
      </defs>
    </svg>
  </div>
  <div class="ice-blob" style="bottom:-20px;right:-60px;width:380px;animation-duration:11s;animation-delay:-4s;z-index:0">
    <svg viewBox="0 0 380 200" fill="none" xmlns="http://www.w3.org/2000/svg">
      <ellipse cx="190" cy="150" rx="190" ry="80" fill="url(#ig3)"/>
      <ellipse cx="110" cy="120" rx="90" ry="55" fill="url(#ig4)"/>
      <ellipse cx="270" cy="125" rx="70" ry="45" fill="url(#ig4)"/>
      <defs>
        <radialGradient id="ig3" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="#bde8f8"/><stop offset="100%" stop-color="#6db8d8"/></radialGradient>
        <radialGradient id="ig4" cx="50%" cy="30%" r="60%"><stop offset="0%" stop-color="#e0f4ff"/><stop offset="100%" stop-color="#8ac8e4"/></radialGradient>
      </defs>
    </svg>
  </div>

  {{-- Nav --}}
  <nav class="fixed top-0 left-0 right-0 z-50 glass flex items-center justify-between px-8 h-[68px]">
    <span class="font-display text-3xl tracking-wide" style="text-shadow:0 2px 12px rgba(120,180,240,.6)">WGG</span>
    <div class="flex gap-8">
      <a href="#top" class="font-body font-semibold text-sm tracking-widest uppercase text-white/80 hover:text-sky transition-colors">About</a>
      <a href="#inventory" class="font-body font-semibold text-sm tracking-widest uppercase text-white/80 hover:text-sky transition-colors">Inventory</a>
      <a href="#add-form" class="font-body font-semibold text-sm tracking-widest uppercase text-white/80 hover:text-sky transition-colors">Tambah</a>
    </div>
  </nav>

  {{-- Main content --}}
  <main class="relative z-10">
    @yield('content')
  </main>

  {{-- Footer --}}
  <footer class="relative z-10 text-center py-8 text-white/30 text-xs tracking-widest border-t border-white/10">
    WGG · <span class="text-sky">Strengthened Through Every Season</span> · Petra Christian University · 2026
  </footer>

  {{-- Toast Container --}}
  <div id="toastContainer" class="fixed bottom-8 right-8 z-[300] flex flex-col gap-3"></div>

  {{-- Modals --}}
  @stack('modals')

  {{-- Scripts --}}
  @stack('scripts')

  <script>
    // Global toast helper
    function showToast(msg, type = 'ok') {
      const c = document.getElementById('toastContainer');
      const t = document.createElement('div');
      t.className = 'toast';
      t.textContent = msg;
      if (type === 'warn') t.style.borderColor = 'rgba(240,192,80,.5)';
      if (type === 'err')  t.style.borderColor = 'rgba(240,100,100,.5)';
      c.appendChild(t);
      setTimeout(() => { t.style.transition = 'opacity .3s'; t.style.opacity = '0'; setTimeout(() => t.remove(), 300); }, 3200);
    }

    // CSRF for fetch
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    async function apiFetch(url, method = 'GET', body = null) {
      const opts = {
        method,
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
      };
      if (body) opts.body = JSON.stringify(body);
      const res = await fetch(url, opts);
      return res.json();
    }
  </script>
</body>
</html>
