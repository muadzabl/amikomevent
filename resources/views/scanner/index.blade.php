<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Penjaga Pintu - QR Code Scanner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Library HTML5 QR Scanner -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</head>
<body class="bg-slate-900 text-white font-sans min-h-screen flex flex-col items-center justify-center p-4">

    <div class="max-w-md w-full bg-slate-800 rounded-2xl p-6 shadow-2xl border border-slate-700">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-black text-indigo-400">🚪 QR Check-in Scanner</h1>
            <p class="text-xs text-slate-400">Aplikasi Penjaga Pintu Masuk Event</p>
        </div>

        <!-- Area Kamera Scanner -->
        <div class="overflow-hidden rounded-xl bg-black border-2 border-indigo-500/50 mb-6">
            <div id="reader" class="w-full"></div>
        </div>

        <!-- Box Notifikasi / Hasil Scan -->
        <div id="result-box" class="hidden p-4 rounded-xl text-center mb-4 transition-all">
            <h2 id="result-title" class="text-lg font-bold"></h2>
            <p id="result-message" class="text-xs mt-1"></p>
            <div id="participant-info" class="mt-3 text-left bg-black/30 p-3 rounded-lg text-xs space-y-1 hidden">
                <p><strong>Nama:</strong> <span id="info-name"></span></p>
                <p><strong>Event:</strong> <span id="info-event"></span></p>
                <p><strong>No TRX:</strong> <span id="info-trx"></span></p>
            </div>
        </div>

        <!-- Form Manual Input Order ID (Antisipasi jika kamera error) -->
        <div class="mt-4 pt-4 border-t border-slate-700">
            <p class="text-xs text-slate-400 mb-2 text-center">Atau Masukkan Kode TRX Manual:</p>
            <div class="flex gap-2">
                <input type="text" id="manual-trx" placeholder="TRX-123456" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-indigo-500">
                <button onclick="processScan(document.getElementById('manual-trx').value)" class="bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-lg text-xs font-bold">Check</button>
            </div>
        </div>
    </div>

    <script>
        let isScanning = true;

        function onScanSuccess(decodedText, decodedResult) {
            if (!isScanning) return;
            isScanning = false; // Tahan scan sejenak agar tidak spamming

            processScan(decodedText);

            // Buka akses scan kembali setelah 3 detik
            setTimeout(() => { isScanning = true; }, 3000);
        }

        function processScan(orderId) {
            if (!orderId) return;

            const resultBox = document.getElementById('result-box');
            const resultTitle = document.getElementById('result-title');
            const resultMsg = document.getElementById('result-message');
            const participantInfo = document.getElementById('participant-info');

            fetch("{{ route('scan.process') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ order_id: orderId })
            })
            .then(response => response.json())
            .then(data => {
                resultBox.classList.remove('hidden', 'bg-emerald-500/20', 'border-emerald-500', 'bg-red-500/20', 'border-red-500');
                
                if (data.success) {
                    resultBox.classList.add('bg-emerald-500/20', 'border', 'border-emerald-500', 'text-emerald-300');
                    resultTitle.innerText = "SUCCESS!";
                    resultMsg.innerText = data.message;
                    
                    document.getElementById('info-name').innerText = data.data.customer_name;
                    document.getElementById('info-event').innerText = data.data.event_title;
                    document.getElementById('info-trx').innerText = data.data.order_id;
                    participantInfo.classList.remove('hidden');
                } else {
                    resultBox.classList.add('bg-red-500/20', 'border', 'border-red-500', 'text-red-300');
                    resultTitle.innerText = "DENIED!";
                    resultMsg.innerText = data.message;
                    participantInfo.classList.add('hidden');
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
        }

        // Inisialisasi Kamera QR Scanner
        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", { fps: 10, qrbox: { width: 250, height: 250 } }
        );
        html5QrcodeScanner.render(onScanSuccess);
    </script>
</body>
</html>