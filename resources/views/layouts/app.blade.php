<!-- <!DOCTYPE html>
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
      transition: background 0.5s ease; /* Transisi smooth saat ganti tema */
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
    .ice-blob { position: fixed; pointer-events: none; z-index: 0; opacity:.55; animation: float ease-in-out infinite; transition: opacity 0.5s ease; }
    @keyframes float{0%,100%{transform:translateY(0);}50%{transform:translateY(-18px);}}

    /* ── Glass ── */
    .glass {
      background: var(--glass);
      backdrop-filter: blur(18px);
      -webkit-backdrop-filter: blur(18px);
      border: 1px solid var(--glass-border);
      transition: background 0.5s ease, border-color 0.5s ease;
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
      transition: transform .28s cubic-bezier(.34,1.56,.64,1), background 0.5s ease;
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

    /* ═══════════════════════════════════════
       DARK MODE OVERRIDES
    ═══════════════════════════════════════ */
    body.dark-theme {
      /* Background dominan hitam kebiruan yang elegan */
      background: linear-gradient(160deg, #0f172a 0%, #1e293b 40%, #020617 100%);
    }

    body.dark-theme .glass {
      background: rgba(15, 23, 42, 0.4);
      border-color: rgba(255, 255, 255, 0.08);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }

    body.dark-theme .wgg-input {
      background: rgba(15, 23, 42, 0.6);
      border-color: rgba(255, 255, 255, 0.12);
    }

    body.dark-theme .wgg-input:focus {
      border-color: #38bdf8;
      background: rgba(15, 23, 42, 0.8);
    }

    body.dark-theme .wgg-input option {
      background: #0f172a; 
    }

    body.dark-theme .modal-box {
      background: linear-gradient(135deg, rgba(15,23,42,.95), rgba(2,6,23,.95));
      border-color: rgba(255, 255, 255, 0.1);
    }

    body.dark-theme .ice-blob {
      opacity: 0.15; /* Redupkan ice blob biar gak terlalu terang di dark mode */
    }

    body.dark-theme .ripple {
      background: radial-gradient(circle, rgba(56, 189, 248, 0.05) 0%, transparent 70%);
    }
    
    body.dark-theme footer {
      border-color: rgba(255, 255, 255, 0.05);
    }
  </style>
</head>
<body class="text-white overflow-x-hidden">

  {{-- ═══ WGG LOADER — CLEAN ═══ --}}
  <div id="wgg-loader">
    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="160" height="160" viewBox="0 0 4501 4501" xml:space="preserve">
      <path transform="translate(92,59)" fill="rgba(182,223,245,0.9)" d="M1788.414551,972.013245 C1827.716187,971.681763 1867.017334,971.294983 1906.319458,971.044373 C1923.616211,970.934082 1940.915527,971.023987 1958.212524,971.159058 C1986.866455,971.382812 2015.519165,971.924744 2044.172852,971.956238 C2089.572266,972.006104 2134.611084,976.250916 2179.523682,982.520569 C2208.544189,986.571716 2237.671387,989.858215 2266.690674,993.918518 C2310.914062,1000.106201 2354.375977,1010.210815 2397.815186,1020.325867 C2450.322754,1032.552490 2502.873291,1044.691528 2554.194580,1061.400635 C2627.707764,1085.334839 2699.463135,1113.765625 2769.662842,1146.167847 C2820.446533,1169.608398 2871.728516,1192.082153 2920.244385,1220.057983 C3035.449463,1286.488892 3144.361328,1361.721069 3241.958496,1452.542114 C3318.753662,1524.005737 3390.230957,1600.261719 3449.213623,1687.369629 C3467.862305,1714.910889 3484.722656,1743.721436 3501.264648,1772.602173 C3507.794189,1784.001587 3512.858643,1796.803101 3515.787354,1809.607056 C3530.637695,1874.528320 3546.702881,1939.228516 3556.982422,2005.096069 C3561.395752,2033.375366 3564.820557,2061.845215 3567.605469,2090.334961 C3573.048096,2146.014648 3578.277832,2201.726807 3582.568848,2257.504639 C3587.064209,2315.943115 3592.072998,2374.352295 3593.362061,2433.019043 C3594.458984,2482.941650 3597.047119,2532.830322 3598.680908,2582.743408 C3599.552979,2609.379395 3599.953125,2636.033691 3600.294434,2662.683350 C3600.738525,2697.330811 3600.934082,2731.981201 3601.198242,2766.630859 C3601.756592,2839.919434 3602.493164,2913.207520 3602.773926,2986.497070 C3603.039551,3055.798828 3603.335205,3125.107666 3602.659424,3194.403809 C3602.003662,3261.670166 3600.278076,3328.927979 3598.740967,3396.183105 C3597.675781,3442.791260 3596.152832,3489.388672 3594.800293,3535.989990 C3593.467285,3581.926025 3592.681396,3627.887695 3590.584229,3673.789307 C3587.970947,3730.993408 3584.140869,3788.140625 3581.096436,3845.326416 C3580.176025,3862.618408 3579.584229,3879.964111 3579.829834,3897.271240 C3580.042969,3912.296875 3574.812500,3924.317383 3564.277100,3934.772461 C3548.196289,3950.730225 3532.493408,3967.070312 3516.702148,3983.318359 C3448.897949,4053.083496 3381.093506,4122.848633 3313.340820,4192.663574 C3261.833740,4245.738770 3210.302734,4298.791992 3159.023926,4352.086914 C3148.425781,4363.102051 3138.950928,4375.192871 3128.837891,4386.681641 C3125.869141,4390.054199 3122.485352,4393.061523 3118.571533,4396.952148 C3114.500977,4393.097656 3110.705322,4390.399902 3108.041748,4386.852051 C3030.743652,4283.880371 2944.299805,4188.508789 2861.464355,4090.145020 C2808.670166,4027.454346 2755.326904,3965.224609 2701.965576,3903.015137 C2692.708008,3892.222656 2688.666992,3881.054688 2690.240723,3866.522949 C2692.890625,3842.047363 2693.899902,3817.381592 2695.187256,3792.773926 C2698.633057,3726.928467 2701.949219,3661.075928 2705.135498,3595.217041 C2706.647949,3563.955566 2707.991455,3532.681396 2708.913086,3501.398193 C2709.285400,3488.767578 2708.326172,3476.097900 2707.935303,3459.737793 C2708.278076,3440.068359 2708.447266,3424.098877 2709.091064,3408.148438 C2709.921387,3387.563721 2711.773926,3367.002930 2712.036377,3346.416992 C2712.511475,3309.159424 2712.147949,3271.890869 2712.111816,3234.626953 C2712.031982,3152.114746 2712.257324,3069.600098 2711.665771,2987.091309 C2711.475098,2960.523682 2708.773926,2933.983887 2707.828857,2907.409424 C2706.364014,2866.205566 2705.485352,2824.981201 2704.189453,2783.770752 C2703.981201,2777.142334 2702.896484,2770.541260 2703.061035,2762.614258 C2709.795410,2754.989014 2711.135010,2747.375000 2710.565674,2739.069336 C2706.833740,2684.645020 2703.461426,2630.193604 2699.377441,2575.796143 C2697.085938,2545.273438 2694.494141,2514.727539 2690.552979,2484.384766 C2683.611572,2430.945312 2675.755371,2377.620361 2667.859375,2324.310059 C2661.518799,2281.500977 2652.953613,2239.098389 2642.569092,2197.082764 C2630.569580,2148.533691 2613.552490,2101.608643 2595.577881,2055.044678 C2590.326172,2041.440552 2584.345947,2027.822144 2576.600098,2015.533447 C2555.668213,1982.325439 2534.007324,1949.555786 2511.854980,1917.145752 C2496.802246,1895.123169 2478.710693,1875.515747 2459.509521,1856.968506 C2426.709229,1825.285645 2389.523193,1799.820801 2349.621338,1777.938721 C2273.739990,1736.325806 2193.997803,1705.792969 2108.074219,1692.992676 C2035.534058,1682.186401 1962.559448,1676.594360 1889.195923,1678.207764 C1857.955200,1678.894897 1826.668945,1680.648804 1794.728271,1687.660400 C1781.700562,1682.835449 1780.135132,1673.453003 1780.988647,1662.731812 C1781.991821,1650.131348 1783.600464,1637.518188 1783.616455,1624.908936 C1783.833252,1454.362427 1783.822998,1283.815674 1783.857910,1113.268921 C1783.865601,1075.961304 1783.846313,1038.653687 1783.803589,1001.346130 C1783.791992,991.314941 1783.888916,981.321594 1788.414551,972.013245z"/>
      <path transform="translate(92,59)" fill="rgba(182,223,245,0.9)" d="M2702.216797,2763.927734 C2702.896484,2770.541260 2703.981201,2777.142334 2704.189453,2783.770752 C2705.485352,2824.981201 2706.364014,2866.205566 2707.828857,2907.409424 C2708.773926,2933.983887 2711.475098,2960.523682 2711.665771,2987.091309 C2712.257324,3069.600098 2712.031982,3152.114746 2712.111816,3234.626953 C2712.147949,3271.890869 2712.511475,3309.159424 2712.036377,3346.416992 C2711.773926,3367.002930 2709.921387,3387.563721 2709.091064,3408.148438 C2708.447266,3424.098877 2708.278076,3440.068359 2707.759766,3457.847168 C2695.666016,3460.036377 2683.596191,3459.490479 2671.774902,3460.952393 C2636.556641,3465.308838 2601.236328,3463.845215 2565.936523,3463.943604 C2541.952393,3464.010498 2517.954346,3463.446289 2493.986572,3464.078857 C2455.916748,3465.083496 2418.024902,3462.454590 2380.223633,3458.886963 C2337.121582,3454.818604 2294.025391,3450.312744 2251.140625,3444.436523 C2218.187988,3439.921387 2185.388672,3433.833252 2152.830322,3426.976807 C2043.143311,3403.876953 1936.044189,3371.546143 1831.319824,3331.885254 C1767.191528,3307.598877 1704.295288,3279.824707 1643.588135,3247.844482 C1512.151245,3178.604492 1389.969116,3095.792236 1280.163330,2995.251709 C1245.257080,2963.291016 1209.976685,2931.671875 1178.501465,2896.295410 C1156.824585,2871.931641 1136.062866,2846.627930 1116.511230,2820.531250 C1092.948975,2789.081787 1071.080200,2756.362793 1048.518311,2724.165039 C1031.904419,2700.455566 1020.398315,2674.329346 1012.336243,2646.638672 C994.430847,2585.138672 981.095215,2522.627197 972.095398,2459.233887 C965.448914,2412.416748 958.986084,2365.553711 953.700806,2318.569580 C949.606628,2282.174561 947.093567,2245.585693 944.533875,2209.037354 C940.485596,2151.232178 936.835938,2093.397949 933.297607,2035.558838 C929.758911,1977.714844 926.561035,1919.849854 923.226807,1861.993408 C923.035095,1858.668091 922.783508,1855.331787 922.858459,1852.007446 C924.106750,1796.670532 920.516541,1741.455444 918.827271,1686.191162 C917.952393,1657.567993 917.456421,1628.927734 917.228577,1600.291748 C916.581604,1518.990356 915.796631,1437.687134 915.762878,1356.384521 C915.734375,1287.747803 916.382751,1219.107056 917.275269,1150.475098 C918.262817,1074.537598 919.737915,998.605469 921.248596,922.675903 C922.348389,867.398987 923.503479,812.118713 925.297119,756.861511 C926.463562,720.925354 928.939453,685.033386 930.589172,649.110596 C931.291687,633.812744 932.191833,618.451721 931.531372,603.183044 C930.818420,586.700134 936.124756,573.384399 947.440796,561.701660 C966.908875,541.602844 985.393616,520.515808 1005.413635,500.994873 C1049.825806,457.689880 1091.431885,411.803406 1133.081543,365.902100 C1156.353027,340.255127 1181.290771,316.128204 1205.125977,290.982941 C1252.794067,240.695099 1300.222656,190.180267 1347.742798,139.752258 C1353.682373,133.449112 1359.771973,127.272690 1365.442139,120.734077 C1371.911499,113.273949 1383.625000,112.420189 1391.297363,121.906136 C1405.122803,138.999374 1419.488403,155.662949 1433.857666,172.308090 C1500.921143,249.992813 1568.118652,327.561829 1635.158203,405.267120 C1684.781006,462.784821 1734.078613,520.583862 1783.861694,577.961853 C1798.310669,594.614929 1803.631226,612.722839 1801.686157,634.932434 C1798.375854,672.727539 1797.115356,710.724670 1795.773315,748.664429 C1793.748047,805.910950 1792.465332,863.183289 1790.707153,920.439819 C1790.216675,936.408752 1789.163330,952.360474 1788.391357,970.166626 C1783.888916,981.321594 1783.791992,991.314941 1783.803589,1001.346130 C1783.846313,1038.653687 1783.865601,1075.961304 1783.857910,1113.268921 C1783.822998,1283.815674 1783.833252,1454.362427 1783.616455,1624.908936 C1783.600464,1637.518188 1781.991821,1650.131348 1780.988647,1662.731812 C1780.135132,1673.453003 1781.700562,1682.835449 1794.534180,1689.177002 C1793.857666,1699.047974 1793.699097,1706.797974 1794.736694,1714.916748 C1796.844727,1731.413086 1798.133057,1748.032227 1799.233521,1764.635620 C1802.315430,1811.129395 1804.650146,1857.677612 1808.222168,1904.131958 C1810.518066,1933.989136 1813.637207,1963.838623 1817.874023,1993.479004 C1825.974976,2050.151123 1835.239380,2106.655762 1843.834595,2163.258301 C1856.599731,2247.321289 1879.148682,2328.503174 1913.798706,2406.301025 C1926.027954,2433.758545 1939.517578,2460.467529 1955.978027,2485.567383 C1982.338745,2525.763672 2011.092163,2564.095703 2047.464355,2595.999268 C2073.533203,2618.865479 2101.139160,2639.763428 2130.061279,2658.916748 C2163.492188,2681.056152 2198.700928,2699.700684 2236.304688,2713.784912 C2316.561768,2743.844971 2398.829834,2764.444824 2484.812744,2769.100586 C2523.424072,2771.191162 2561.976318,2772.511963 2600.580566,2770.365479 C2634.473877,2768.480957 2668.339111,2766.093262 2702.216797,2763.927734z"/>
    </svg>
  </div>

  <style>
    #wgg-loader {
      position: fixed; inset: 0; z-index: 10000;
      background: #37435b;
      display: flex; align-items: center; justify-content: center;
      transition: opacity .5s ease;
    }
    #wgg-loader svg {
      animation: wggPulse 2s ease-in-out infinite;
      filter: drop-shadow(0 0 16px rgba(0,234,255,.7));
    }
    @keyframes wggPulse {
      0%,100% { opacity: .85; filter: drop-shadow(0 0 12px rgba(0,234,255,.6)); }
      50%      { opacity: 1;   filter: drop-shadow(0 0 28px rgba(0,234,255,1)); }
    }
  </style>

  <script>
    document.body.style.overflow = 'hidden';
    let _done = false;
    function finishLoader() {
      if (_done) return; _done = true;
      const el = document.getElementById('wgg-loader');
      if (!el) return;
      el.style.opacity = '0';
      setTimeout(() => { el.remove(); document.body.style.overflow = ''; }, 520);
    }
    window.addEventListener('load', () => setTimeout(finishLoader, 900));
    setTimeout(finishLoader, 8000);
  </script>
  {{-- ═══ END LOADER ═══ --}}

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
    <img src="https://wgg.petra.ac.id/assets/wgg26.png" alt="WGG" class="h-10 w-auto" style="filter:drop-shadow(0 0 8px rgba(182,223,245,0.6))">
    <div class="flex gap-8 items-center">
      <a href="#top" class="font-body font-semibold text-sm tracking-widest uppercase text-white/80 hover:text-sky transition-colors">About</a>
      <a href="#inventory" class="font-body font-semibold text-sm tracking-widest uppercase text-white/80 hover:text-sky transition-colors">Inventory</a>
      <a href="#add-form" class="font-body font-semibold text-sm tracking-widest uppercase text-white/80 hover:text-sky transition-colors">Tambah</a>
      
      {{-- ════ TOMBOL TEMA MATAHARI/BULAN ════ --}}
      <button id="themeToggleBtn" onclick="toggleTheme()" class="ml-4 text-xl hover:scale-110 transition-transform cursor-pointer focus:outline-none" aria-label="Toggle Theme">
        ☀️
      </button>
    </div>
  </nav>

  {{-- Main content --}}
  <main class="relative z-10">
    @yield('content')
  </main>

  {{-- Footer --}}
  <footer class="relative z-10 text-center py-8 text-white/30 text-xs tracking-widest border-t border-white/10 transition-colors duration-500">
    WGG · <span class="text-sky">Strengthened Through Every Season</span> · Petra Christian University · 2026
  </footer>

  {{-- Toast Container --}}
  <div id="toastContainer" class="fixed bottom-8 right-8 z-[300] flex flex-col gap-3"></div>

  {{-- Modals --}}
  @stack('modals')

  {{-- Scripts --}}
  @stack('scripts')

  <script>
    // ════ FUNGSI GANTI TEMA ════
    function toggleTheme() {
      const body = document.body;
      const btn = document.getElementById('themeToggleBtn');
      
      // Toggle class dark-theme ke tag body
      const isDark = body.classList.toggle('dark-theme');
      
      // Ubah icon tombol
      btn.innerText = isDark ? '🌙' : '☀️';
      
      // Simpan preferensi tema ke localStorage supaya tidak hilang saat di-refresh
      localStorage.setItem('theme', isDark ? 'dark' : 'light');
    }

    // ════ CEK TEMA SAAT HALAMAN DIMUAT ════
    document.addEventListener('DOMContentLoaded', () => {
      const savedTheme = localStorage.getItem('theme');
      const btn = document.getElementById('themeToggleBtn');
      
      // Jika sebelumnya user memilih dark mode, langsung aktifkan
      if (savedTheme === 'dark') {
        document.body.classList.add('dark-theme');
        if (btn) btn.innerText = '🌙';
      } else {
        // Light mode (default)
        if (btn) btn.innerText = '☀️';
      }
    });


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
</html> -->

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
      transition: background 0.5s ease; /* Transisi smooth saat ganti tema */
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
    .ice-blob { position: fixed; pointer-events: none; z-index: 0; opacity:.55; animation: float ease-in-out infinite; transition: opacity 0.5s ease; }
    @keyframes float{0%,100%{transform:translateY(0);}50%{transform:translateY(-18px);}}

    /* ── Glass ── */
    .glass {
      background: var(--glass);
      backdrop-filter: blur(18px);
      -webkit-backdrop-filter: blur(18px);
      border: 1px solid var(--glass-border);
      transition: background 0.5s ease, border-color 0.5s ease;
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
      transition: transform .28s cubic-bezier(.34,1.56,.64,1), background 0.5s ease;
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

    /* ═══════════════════════════════════════
       DARK MODE OVERRIDES
    ═══════════════════════════════════════ */
    body.dark-theme {
      background: linear-gradient(160deg, #0f172a 0%, #1e293b 40%, #020617 100%);
    }

    body.dark-theme .glass {
      background: rgba(15, 23, 42, 0.4);
      border-color: rgba(255, 255, 255, 0.08);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }

    body.dark-theme .wgg-input {
      background: rgba(15, 23, 42, 0.6);
      border-color: rgba(255, 255, 255, 0.12);
    }

    body.dark-theme .wgg-input:focus {
      border-color: #38bdf8;
      background: rgba(15, 23, 42, 0.8);
    }

    body.dark-theme .wgg-input option {
      background: #0f172a; 
    }

    body.dark-theme .modal-box {
      background: linear-gradient(135deg, rgba(15,23,42,.95), rgba(2,6,23,.95));
      border-color: rgba(255, 255, 255, 0.1);
    }

    body.dark-theme .ice-blob {
      opacity: 0.15;
    }

    body.dark-theme .ripple {
      background: radial-gradient(circle, rgba(56, 189, 248, 0.05) 0%, transparent 70%);
    }
    
    body.dark-theme footer {
      border-color: rgba(255, 255, 255, 0.05);
    }
  </style>
</head>
<body class="text-white overflow-x-hidden">

  {{-- ═══ WGG LOADER — CLEAN ═══ --}}
  <div id="wgg-loader">
    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="160" height="160" viewBox="0 0 4501 4501" xml:space="preserve">
      <path transform="translate(92,59)" fill="rgba(182,223,245,0.9)" d="M1788.414551,972.013245 C1827.716187,971.681763 1867.017334,971.294983 1906.319458,971.044373 C1923.616211,970.934082 1940.915527,971.023987 1958.212524,971.159058 C1986.866455,971.382812 2015.519165,971.924744 2044.172852,971.956238 C2089.572266,972.006104 2134.611084,976.250916 2179.523682,982.520569 C2208.544189,986.571716 2237.671387,989.858215 2266.690674,993.918518 C2310.914062,1000.106201 2354.375977,1010.210815 2397.815186,1020.325867 C2450.322754,1032.552490 2502.873291,1044.691528 2554.194580,1061.400635 C2627.707764,1085.334839 2699.463135,1113.765625 2769.662842,1146.167847 C2820.446533,1169.608398 2871.728516,1192.082153 2920.244385,1220.057983 C3035.449463,1286.488892 3144.361328,1361.721069 3241.958496,1452.542114 C3318.753662,1524.005737 3390.230957,1600.261719 3449.213623,1687.369629 C3467.862305,1714.910889 3484.722656,1743.721436 3501.264648,1772.602173 C3507.794189,1784.001587 3512.858643,1796.803101 3515.787354,1809.607056 C3530.637695,1874.528320 3546.702881,1939.228516 3556.982422,2005.096069 C3561.395752,2033.375366 3564.820557,2061.845215 3567.605469,2090.334961 C3573.048096,2146.014648 3578.277832,2201.726807 3582.568848,2257.504639 C3587.064209,2315.943115 3592.072998,2374.352295 3593.362061,2433.019043 C3594.458984,2482.941650 3597.047119,2532.830322 3598.680908,2582.743408 C3599.552979,2609.379395 3599.953125,2636.033691 3600.294434,2662.683350 C3600.738525,2697.330811 3600.934082,2731.981201 3601.198242,2766.630859 C3601.756592,2839.919434 3602.493164,2913.207520 3602.773926,2986.497070 C3603.039551,3055.798828 3603.335205,3125.107666 3602.659424,3194.403809 C3602.003662,3261.670166 3600.278076,3328.927979 3598.740967,3396.183105 C3597.675781,3442.791260 3596.152832,3489.388672 3594.800293,3535.989990 C3593.467285,3581.926025 3592.681396,3627.887695 3590.584229,3673.789307 C3587.970947,3730.993408 3584.140869,3788.140625 3581.096436,3845.326416 C3580.176025,3862.618408 3579.584229,3879.964111 3579.829834,3897.271240 C3580.042969,3912.296875 3574.812500,3924.317383 3564.277100,3934.772461 C3548.196289,3950.730225 3532.493408,3967.070312 3516.702148,3983.318359 C3448.897949,4053.083496 3381.093506,4122.848633 3313.340820,4192.663574 C3261.833740,4245.738770 3210.302734,4298.791992 3159.023926,4352.086914 C3148.425781,4363.102051 3138.950928,4375.192871 3128.837891,4386.681641 C3125.869141,4390.054199 3122.485352,4393.061523 3118.571533,4396.952148 C3114.500977,4393.097656 3110.705322,4390.399902 3108.041748,4386.852051 C3030.743652,4283.880371 2944.299805,4188.508789 2861.464355,4090.145020 C2808.670166,4027.454346 2755.326904,3965.224609 2701.965576,3903.015137 C2692.708008,3892.222656 2688.666992,3881.054688 2690.240723,3866.522949 C2692.890625,3842.047363 2693.899902,3817.381592 2695.187256,3792.773926 C2698.633057,3726.928467 2701.949219,3661.075928 2705.135498,3595.217041 C2706.647949,3563.955566 2707.991455,3532.681396 2708.913086,3501.398193 C2709.285400,3488.767578 2708.326172,3476.097900 2707.935303,3459.737793 C2708.278076,3440.068359 2708.447266,3424.098877 2709.091064,3408.148438 C2709.921387,3387.563721 2711.773926,3367.002930 2712.036377,3346.416992 C2712.511475,3309.159424 2712.147949,3271.890869 2712.111816,3234.626953 C2712.031982,3152.114746 2712.257324,3069.600098 2711.665771,2987.091309 C2711.475098,2960.523682 2708.773926,2933.983887 2707.828857,2907.409424 C2706.364014,2866.205566 2705.485352,2824.981201 2704.189453,2783.770752 C2703.981201,2777.142334 2702.896484,2770.541260 2703.061035,2762.614258 C2709.795410,2754.989014 2711.135010,2747.375000 2710.565674,2739.069336 C2706.833740,2684.645020 2703.461426,2630.193604 2699.377441,2575.796143 C2697.085938,2545.273438 2694.494141,2514.727539 2690.552979,2484.384766 C2683.611572,2430.945312 2675.755371,2377.620361 2667.859375,2324.310059 C2661.518799,2281.500977 2652.953613,2239.098389 2642.569092,2197.082764 C2630.569580,2148.533691 2613.552490,2101.608643 2595.577881,2055.044678 C2590.326172,2041.440552 2584.345947,2027.822144 2576.600098,2015.533447 C2555.668213,1982.325439 2534.007324,1949.555786 2511.854980,1917.145752 C2496.802246,1895.123169 2478.710693,1875.515747 2459.509521,1856.968506 C2426.709229,1825.285645 2389.523193,1799.820801 2349.621338,1777.938721 C2273.739990,1736.325806 2193.997803,1705.792969 2108.074219,1692.992676 C2035.534058,1682.186401 1962.559448,1676.594360 1889.195923,1678.207764 C1857.955200,1678.894897 1826.668945,1680.648804 1794.728271,1687.660400 C1781.700562,1682.835449 1780.135132,1673.453003 1780.988647,1662.731812 C1781.991821,1650.131348 1783.600464,1637.518188 1783.616455,1624.908936 C1783.833252,1454.362427 1783.822998,1283.815674 1783.857910,1113.268921 C1783.865601,1075.961304 1783.846313,1038.653687 1783.803589,1001.346130 C1783.791992,991.314941 1783.888916,981.321594 1788.414551,972.013245z"/>
      <path transform="translate(92,59)" fill="rgba(182,223,245,0.9)" d="M2702.216797,2763.927734 C2702.896484,2770.541260 2703.981201,2777.142334 2704.189453,2783.770752 C2705.485352,2824.981201 2706.364014,2866.205566 2707.828857,2907.409424 C2708.773926,2933.983887 2711.475098,2960.523682 2711.665771,2987.091309 C2712.257324,3069.600098 2712.031982,3152.114746 2712.111816,3234.626953 C2712.147949,3271.890869 2712.511475,3309.159424 2712.036377,3346.416992 C2711.773926,3367.002930 2709.921387,3387.563721 2709.091064,3408.148438 C2708.447266,3424.098877 2708.278076,3440.068359 2707.759766,3457.847168 C2695.666016,3460.036377 2683.596191,3459.490479 2671.774902,3460.952393 C2636.556641,3465.308838 2601.236328,3463.845215 2565.936523,3463.943604 C2541.952393,3464.010498 2517.954346,3463.446289 2493.986572,3464.078857 C2455.916748,3465.083496 2418.024902,3462.454590 2380.223633,3458.886963 C2337.121582,3454.818604 2294.025391,3450.312744 2251.140625,3444.436523 C2218.187988,3439.921387 2185.388672,3433.833252 2152.830322,3426.976807 C2043.143311,3403.876953 1936.044189,3371.546143 1831.319824,3331.885254 C1767.191528,3307.598877 1704.295288,3279.824707 1643.588135,3247.844482 C1512.151245,3178.604492 1389.969116,3095.792236 1280.163330,2995.251709 C1245.257080,2963.291016 1209.976685,2931.671875 1178.501465,2896.295410 C1156.824585,2871.931641 1136.062866,2846.627930 1116.511230,2820.531250 C1092.948975,2789.081787 1071.080200,2756.362793 1048.518311,2724.165039 C1031.904419,2700.455566 1020.398315,2674.329346 1012.336243,2646.638672 C994.430847,2585.138672 981.095215,2522.627197 972.095398,2459.233887 C965.448914,2412.416748 958.986084,2365.553711 953.700806,2318.569580 C949.606628,2282.174561 947.093567,2245.585693 944.533875,2209.037354 C940.485596,2151.232178 936.835938,2093.397949 933.297607,2035.558838 C929.758911,1977.714844 926.561035,1919.849854 923.226807,1861.993408 C923.035095,1858.668091 922.783508,1855.331787 922.858459,1852.007446 C924.106750,1796.670532 920.516541,1741.455444 918.827271,1686.191162 C917.952393,1657.567993 917.456421,1628.927734 917.228577,1600.291748 C916.581604,1518.990356 915.796631,1437.687134 915.762878,1356.384521 C915.734375,1287.747803 916.382751,1219.107056 917.275269,1150.475098 C918.262817,1074.537598 919.737915,998.605469 921.248596,922.675903 C922.348389,867.398987 923.503479,812.118713 925.297119,756.861511 C926.463562,720.925354 928.939453,685.033386 930.589172,649.110596 C931.291687,633.812744 932.191833,618.451721 931.531372,603.183044 C930.818420,586.700134 936.124756,573.384399 947.440796,561.701660 C966.908875,541.602844 985.393616,520.515808 1005.413635,500.994873 C1049.825806,457.689880 1091.431885,411.803406 1133.081543,365.902100 C1156.353027,340.255127 1181.290771,316.128204 1205.125977,290.982941 C1252.794067,240.695099 1300.222656,190.180267 1347.742798,139.752258 C1353.682373,133.449112 1359.771973,127.272690 1365.442139,120.734077 C1371.911499,113.273949 1383.625000,112.420189 1391.297363,121.906136 C1405.122803,138.999374 1419.488403,155.662949 1433.857666,172.308090 C1500.921143,249.992813 1568.118652,327.561829 1635.158203,405.267120 C1684.781006,462.784821 1734.078613,520.583862 1783.861694,577.961853 C1798.310669,594.614929 1803.631226,612.722839 1801.686157,634.932434 C1798.375854,672.727539 1797.115356,710.724670 1795.773315,748.664429 C1793.748047,805.910950 1792.465332,863.183289 1790.707153,920.439819 C1790.216675,936.408752 1789.163330,952.360474 1788.391357,970.166626 C1783.888916,981.321594 1783.791992,991.314941 1783.803589,1001.346130 C1783.846313,1038.653687 1783.865601,1075.961304 1783.857910,1113.268921 C1783.822998,1283.815674 1783.833252,1454.362427 1783.616455,1624.908936 C1783.600464,1637.518188 1781.991821,1650.131348 1780.988647,1662.731812 C1780.135132,1673.453003 1781.700562,1682.835449 1794.534180,1689.177002 C1793.857666,1699.047974 1793.699097,1706.797974 1794.736694,1714.916748 C1796.844727,1731.413086 1798.133057,1748.032227 1799.233521,1764.635620 C1802.315430,1811.129395 1804.650146,1857.677612 1808.222168,1904.131958 C1810.518066,1933.989136 1813.637207,1963.838623 1817.874023,1993.479004 C1825.974976,2050.151123 1835.239380,2106.655762 1843.834595,2163.258301 C1856.599731,2247.321289 1879.148682,2328.503174 1913.798706,2406.301025 C1926.027954,2433.758545 1939.517578,2460.467529 1955.978027,2485.567383 C1982.338745,2525.763672 2011.092163,2564.095703 2047.464355,2595.999268 C2073.533203,2618.865479 2101.139160,2639.763428 2130.061279,2658.916748 C2163.492188,2681.056152 2198.700928,2699.700684 2236.304688,2713.784912 C2316.561768,2743.844971 2398.829834,2764.444824 2484.812744,2769.100586 C2523.424072,2771.191162 2561.976318,2772.511963 2600.580566,2770.365479 C2634.473877,2768.480957 2668.339111,2766.093262 2702.216797,2763.927734z"/>
    </svg>
  </div>

  <style>
    #wgg-loader {
      position: fixed; inset: 0; z-index: 10000;
      background: #37435b;
      display: flex; align-items: center; justify-content: center;
      transition: opacity .5s ease;
    }
    #wgg-loader svg {
      animation: wggPulse 2s ease-in-out infinite;
      filter: drop-shadow(0 0 16px rgba(0,234,255,.7));
    }
    @keyframes wggPulse {
      0%,100% { opacity: .85; filter: drop-shadow(0 0 12px rgba(0,234,255,.6)); }
      50%      { opacity: 1;   filter: drop-shadow(0 0 28px rgba(0,234,255,1)); }
    }
  </style>

  <script>
    document.body.style.overflow = 'hidden';
    let _done = false;
    function finishLoader() {
      if (_done) return; _done = true;
      const el = document.getElementById('wgg-loader');
      if (!el) return;
      el.style.opacity = '0';
      setTimeout(() => { el.remove(); document.body.style.overflow = ''; }, 520);
    }
    window.addEventListener('load', () => setTimeout(finishLoader, 900));
    setTimeout(finishLoader, 8000);
  </script>
  {{-- ═══ END LOADER ═══ --}}

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

  {{-- Nav (MOBILE FRIENDLY & SVG ICONS) --}}
  <nav class="fixed top-0 left-0 right-0 z-50 glass flex items-center justify-between px-3 md:px-8 h-[60px] md:h-[68px]">
    <img src="https://wgg.petra.ac.id/assets/wgg26.png" alt="WGG" class="h-7 sm:h-8 md:h-10 w-auto" style="filter:drop-shadow(0 0 8px rgba(182,223,245,0.6))">
    
    {{-- Ubah gap-4 jadi gap-2.5 di HP biar muat semua --}}
    <div class="flex gap-2.5 sm:gap-4 md:gap-8 items-center">
      
      {{-- Class 'hidden sm:block' dihapus biar About tetap muncul di HP --}}
      <a href="#top" class="font-body font-semibold text-[10px] sm:text-xs md:text-sm tracking-widest uppercase text-white/80 hover:text-sky transition-colors">About</a>
      <a href="#inventory" class="font-body font-semibold text-[10px] sm:text-xs md:text-sm tracking-widest uppercase text-white/80 hover:text-sky transition-colors">Inventory</a>
      <a href="#add-form" class="font-body font-semibold text-[10px] sm:text-xs md:text-sm tracking-widest uppercase text-white/80 hover:text-sky transition-colors">Tambah</a>
      
      {{-- ════ TOMBOL TEMA SVG OUTLINE ════ --}}
      <button id="themeToggleBtn" onclick="toggleTheme()" class="ml-1 md:ml-4 text-white/80 hover:text-white transition-all hover:scale-110 cursor-pointer focus:outline-none" aria-label="Toggle Theme">
        
        {{-- Ikon Matahari (Tampil saat Light Mode) --}}
        <svg id="sunIcon" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="5"></circle>
          <line x1="12" y1="1" x2="12" y2="3"></line>
          <line x1="12" y1="21" x2="12" y2="23"></line>
          <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
          <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
          <line x1="1" y1="12" x2="3" y2="12"></line>
          <line x1="21" y1="12" x2="23" y2="12"></line>
          <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
          <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
        </svg>

        {{-- Ikon Bulan (Tampil saat Dark Mode) --}}
        <svg id="moonIcon" xmlns="http://www.w3.org/2000/svg" class="hidden w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
        </svg>

      </button>
    </div>
  </nav>

  {{-- Main content --}}
  <main class="relative z-10">
    @yield('content')
  </main>

  {{-- Footer --}}
  <footer class="relative z-10 text-center py-8 text-white/30 text-xs tracking-widest border-t border-white/10 transition-colors duration-500">
    WGG · <span class="text-sky">Strengthened Through Every Season</span> · Petra Christian University · 2026
  </footer>

  {{-- Toast Container --}}
  <div id="toastContainer" class="fixed bottom-8 right-8 z-[300] flex flex-col gap-3"></div>

  {{-- Modals --}}
  @stack('modals')

  {{-- Scripts --}}
  @stack('scripts')

  <script>
    // ════ FUNGSI GANTI TEMA DENGAN SVG ════
    function toggleTheme() {
      const body = document.body;
      const sunIcon = document.getElementById('sunIcon');
      const moonIcon = document.getElementById('moonIcon');
      
      // Toggle class dark-theme ke tag body
      const isDark = body.classList.toggle('dark-theme');
      
      // Sembunyikan/Tampilkan Icon SVG
      if (isDark) {
        sunIcon.classList.add('hidden');
        moonIcon.classList.remove('hidden');
      } else {
        sunIcon.classList.remove('hidden');
        moonIcon.classList.add('hidden');
      }
      
      // Simpan preferensi tema ke localStorage
      localStorage.setItem('theme', isDark ? 'dark' : 'light');
    }

    // ════ CEK TEMA SAAT HALAMAN DIMUAT ════
    document.addEventListener('DOMContentLoaded', () => {
      const savedTheme = localStorage.getItem('theme');
      const sunIcon = document.getElementById('sunIcon');
      const moonIcon = document.getElementById('moonIcon');
      
      // Jika sebelumnya user memilih dark mode
      if (savedTheme === 'dark') {
        document.body.classList.add('dark-theme');
        if (sunIcon) sunIcon.classList.add('hidden');
        if (moonIcon) moonIcon.classList.remove('hidden');
      } else {
        // Light mode (default)
        if (sunIcon) sunIcon.classList.remove('hidden');
        if (moonIcon) moonIcon.classList.add('hidden');
      }
    });


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