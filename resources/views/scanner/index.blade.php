<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Penjaga Pintu - Gatekeeper QR Scanner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- HTML5 QR Code Library -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0f172a; }
        #reader video { object-fit: cover !important; border-radius: 1rem; }
        #reader { border: none !important; }
        #reader__scan_region { background: transparent !important; }
        #reader__dashboard { padding: 10px !important; }
        #reader__dashboard_section_csr button {
            background-color: #4f46e5 !important;
            color: white !important;
            padding: 8px 16px !important;
            border-radius: 12px !important;
            font-weight: bold !important;
            border: none !important;
            margin: 4px !important;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col justify-between">

    {{-- Top Header Gatekeeper --}}
    <header class="bg-slate-900 border-b border-emerald-900/50 px-4 py-3 sticky top-0 z-30 shadow-lg">
        <div class="max-w-lg mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ auth()->user()->role === 'organizer' ? route('partner.dashboard') : (in_array(auth()->user()->role, ['admin','superadmin']) ? route('admin.dashboard') : route('home')) }}" 
                   class="flex-shrink-0">
                    <img src="{{ asset('images/amikom-logo.png') }}" alt="Logo Amikom" class="w-9 h-9 object-contain bg-white rounded-xl p-0.5">
                </a>
                <div>
                    <h1 class="text-base font-black text-white flex items-center gap-2">
                        🚪 Gatekeeper Scanner
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-ping"></span>
                    </h1>
                    <p class="text-[11px] text-slate-400">Anti-Fraud Gate Protection</p>
                </div>
            </div>
            <div class="text-right">
                <span class="text-xs text-emerald-400 font-bold block">{{ auth()->user()->name }}</span>
                <span class="text-[10px] bg-emerald-950 text-emerald-300 border border-emerald-800 px-2 py-0.5 rounded-full uppercase font-extrabold">Panitia</span>
            </div>
        </div>
    </header>

    {{-- Container Utama --}}
    <main class="max-w-lg mx-auto w-full px-4 py-4 flex-1 flex flex-col gap-4">

        {{-- Widget Counter Check-in --}}
        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-4 flex items-center justify-between shadow-xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-600/20 text-emerald-400 rounded-xl flex items-center justify-center font-black text-lg">
                    🎟️
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Check-In Realtime</p>
                    <div class="flex items-baseline gap-2">
                        <span id="counter-checked" class="text-2xl font-black text-emerald-400">{{ $totalCheckedIn }}</span>
                        <span class="text-xs text-slate-500 font-semibold">/ <span id="counter-total">{{ $totalPaidTickets }}</span> Tiket Lunas</span>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <span class="inline-block px-2.5 py-1 bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 rounded-full text-[10px] font-bold">
                    System Live
                </span>
            </div>
        </div>

        {{-- Filter Event --}}
        @if($events->count() > 0)
        <div>
            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Pilih Acara Registrasi:</label>
            <select id="event-select" class="w-full bg-slate-900 border border-slate-800 text-white rounded-xl px-4 py-2.5 text-xs font-bold focus:outline-none focus:border-indigo-500">
                <option value="ALL">-- Semua Event (Universal Check-In) --</option>
                @foreach($events as $event)
                    <option value="{{ $event->id }}">{{ $event->title }}</option>
                @endforeach
            </select>
        </div>
        @endif

        {{-- Frame Kamera HTML5 QR Scanner --}}
        <div class="relative bg-slate-900 border-2 border-indigo-500/40 rounded-3xl p-2 shadow-2xl overflow-hidden">
            <div class="absolute top-4 left-4 z-20 bg-slate-950/80 backdrop-blur border border-slate-800 text-emerald-400 text-[11px] font-bold px-3 py-1 rounded-full flex items-center gap-2">
                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                Kamera Aktif
            </div>

            <div id="reader" class="w-full min-h-[280px] bg-slate-950 rounded-2xl overflow-hidden"></div>
            
            <p class="text-[11px] text-slate-400 text-center py-2 font-medium">Arahkan kamera smartphone panitia tepat ke kode QR peserta</p>
        </div>

        {{-- Modal / Banner Result Pop-Up --}}
        <div id="result-modal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-md z-50 flex items-center justify-center p-4">
            <div id="modal-card" class="bg-slate-900 border-2 rounded-3xl p-6 max-w-sm w-full shadow-2xl text-center space-y-4 transition-all scale-95 transform">
                <div id="modal-icon" class="text-5xl my-2 animate-bounce"></div>
                <div>
                    <h2 id="modal-title" class="text-xl font-black"></h2>
                    <p id="modal-message" class="text-xs text-slate-300 mt-2 font-medium leading-relaxed"></p>
                </div>

                {{-- Detail Peserta --}}
                <div id="modal-detail" class="bg-slate-950/80 border border-slate-800 rounded-2xl p-4 text-left text-xs space-y-2 hidden">
                    <div class="flex justify-between border-b border-slate-800/80 pb-2">
                        <span class="text-slate-400">Nama Peserta:</span>
                        <strong id="det-name" class="text-white text-right font-extrabold"></strong>
                    </div>
                    <div class="flex justify-between border-b border-slate-800/80 pb-2">
                        <span class="text-slate-400">Acara:</span>
                        <strong id="det-event" class="text-indigo-300 text-right font-bold truncate max-w-[180px]"></strong>
                    </div>
                    <div class="flex justify-between border-b border-slate-800/80 pb-2">
                        <span class="text-slate-400">Order ID:</span>
                        <strong id="det-trx" class="text-slate-300 font-mono text-[11px]"></strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Waktu Check-In:</span>
                        <strong id="det-time" class="text-emerald-400 font-bold"></strong>
                    </div>
                </div>

                <button onclick="closeModal()" class="w-full py-3 bg-slate-800 hover:bg-slate-700 text-white rounded-2xl font-bold text-sm transition">
                    OK / Scan Berikutnya
                </button>
            </div>
        </div>

        {{-- Form Input Manual Order ID --}}
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4">
            <h3 class="text-xs font-bold text-slate-300 mb-2 flex items-center justify-between">
                <span>⌨️ Masukkan Kode TRX Manual</span>
                <span class="text-[10px] text-slate-500 font-normal">Antisipasi layar HP peserta rusak</span>
            </h3>
            <form onsubmit="handleManualSubmit(event)" class="flex gap-2">
                <input type="text" id="manual-trx-input" placeholder="Contoh: TRX-1721839210" 
                       class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-xs text-white uppercase tracking-wider font-mono outline-none focus:border-indigo-500 transition">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-xs font-bold transition flex-shrink-0">
                    Proses
                </button>
            </form>
        </div>

    </main>

    <footer class="text-center py-3 text-[11px] text-slate-500 border-t border-slate-900">
        AmikomEventHub &copy; 2026. Gatekeeper System & Anti-Double Entry Scanner.
    </footer>

    {{-- Script HTML5 QR Scanner & Audio Synthesizer --}}
    <script>
        let isProcessing = false;

        // Audio Beep Generator menggunakan HTML5 Web Audio API (Tanpa file eksternal)
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();

        function playSound(type) {
            try {
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.connect(gain);
                gain.connect(audioCtx.destination);

                if (type === 'SUCCESS') {
                    // Beep tinggi menyenangkan untuk Sukses
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(880, audioCtx.currentTime); // Note A5
                    osc.frequency.exponentialRampToValueAtTime(1200, audioCtx.currentTime + 0.15);
                    gain.gain.setValueAtTime(0.3, audioCtx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.25);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.25);
                } else {
                    // Alarm buzzer rendah untuk Gagal / Double Entry
                    osc.type = 'sawtooth';
                    osc.frequency.setValueAtTime(220, audioCtx.currentTime); // Note A3
                    osc.frequency.setValueAtTime(150, audioCtx.currentTime + 0.15);
                    gain.gain.setValueAtTime(0.4, audioCtx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.4);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.4);
                }
            } catch(e) { console.log('Audio disabled:', e); }
        }

        function onScanSuccess(decodedText, decodedResult) {
            if (isProcessing) return;
            isProcessing = true;
            processScan(decodedText);
        }

        function handleManualSubmit(e) {
            e.preventDefault();
            const input = document.getElementById('manual-trx-input');
            const val = input.value.trim();
            if (val) {
                processScan(val);
                input.value = '';
            }
        }

        function processScan(orderId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch("{{ route('scan.process') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    "Accept": "application/json"
                },
                body: JSON.stringify({ order_id: orderId })
            })
            .then(res => res.json())
            .then(data => {
                showModalResult(data);
            })
            .catch(err => {
                console.error("Scan Error:", err);
                showModalResult({
                    success: false,
                    status_code: 'INVALID',
                    title: '⛔ KESALAHAN JARINGAN',
                    message: 'Tidak dapat terhubung ke server. Periksa koneksi internet panitia!'
                });
            });
        }

        function showModalResult(res) {
            const modal = document.getElementById('result-modal');
            const card = document.getElementById('modal-card');
            const icon = document.getElementById('modal-icon');
            const title = document.getElementById('modal-title');
            const msg = document.getElementById('modal-message');
            const detail = document.getElementById('modal-detail');

            // Reset style border & warna card
            card.className = "bg-slate-900 border-2 rounded-3xl p-6 max-w-sm w-full shadow-2xl text-center space-y-4 transition-all scale-100";

            if (res.success) {
                playSound('SUCCESS');
                card.classList.add('border-emerald-500', 'shadow-emerald-500/20');
                icon.innerText = "✅";
                title.className = "text-xl font-black text-emerald-400";
                title.innerText = res.title || "CHECK-IN BERHASIL";
                msg.innerText = res.message;

                if (res.data) {
                    document.getElementById('det-name').innerText = res.data.customer_name;
                    document.getElementById('det-event').innerText = res.data.event_title;
                    document.getElementById('det-trx').innerText = res.data.order_id;
                    document.getElementById('det-time').innerText = res.data.time;
                    detail.classList.remove('hidden');

                    if (res.data.total_checked_in) {
                        document.getElementById('counter-checked').innerText = res.data.total_checked_in;
                    }
                }
            } else {
                playSound('ERROR');
                if (res.status_code === 'ALREADY_USED') {
                    card.classList.add('border-rose-500', 'shadow-rose-500/30');
                    icon.innerText = "🚨";
                    title.className = "text-xl font-black text-rose-500";
                } else if (res.status_code === 'UNPAID') {
                    card.classList.add('border-amber-500', 'shadow-amber-500/20');
                    icon.innerText = "⚠️";
                    title.className = "text-xl font-black text-amber-400";
                } else {
                    card.classList.add('border-red-600', 'shadow-red-600/30');
                    icon.innerText = "⛔";
                    title.className = "text-xl font-black text-red-500";
                }

                title.innerText = res.title || "AKSES DITOLAK";
                msg.innerText = res.message;

                if (res.data && res.data.customer_name) {
                    document.getElementById('det-name').innerText = res.data.customer_name;
                    document.getElementById('det-event').innerText = res.data.event_title;
                    document.getElementById('det-trx').innerText = res.data.order_id;
                    document.getElementById('det-time').innerText = res.data.check_in_at || '-';
                    detail.classList.remove('hidden');
                } else {
                    detail.classList.add('hidden');
                }
            }

            modal.classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('result-modal').classList.add('hidden');
            setTimeout(() => { isProcessing = false; }, 800);
        }

        // Inisialisasi Scanner Kamera Belakang HP (Environment Facing Camera)
        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", 
            { 
                fps: 15, 
                qrbox: { width: 220, height: 220 },
                aspectRatio: 1.0,
                experimentalFeatures: {
                    useBarCodeDetectorIfSupported: true
                }
            }, 
            /* verbose= */ false
        );

        html5QrcodeScanner.render(onScanSuccess);
    </script>
</body>
</html>