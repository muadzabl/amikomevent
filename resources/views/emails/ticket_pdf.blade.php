<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>E-Ticket {{ $transaction->order_id }}</title>
    <style>
        body { font-family: sans-serif; color: #333; padding: 20px; }
        .ticket-box { border: 2px dashed #4f46e5; padding: 20px; border-radius: 15px; }
        .header { text-align: center; border-bottom: 2px solid #eee; padding-bottom: 15px; }
        .title { font-size: 20px; font-weight: bold; color: #4f46e5; }
        .info { margin-top: 20px; font-size: 14px; line-height: 1.6; }
        .qr-code { text-align: center; margin-top: 20px; }
        .footer { margin-top: 20px; font-size: 11px; text-align: center; color: #888; }
    </style>
</head>
<body>
    <div class="ticket-box">
        <div class="header">
            <h2 class="title">AmikomEventHub E-TICKET</h2>
            <p>Order ID: <strong>{{ $transaction->order_id }}</strong></p>
        </div>

        <div class="info">
            <!-- 🔥 Ubah bagian ini menjadi customer_name 🔥 -->
            <p><strong>Nama Pemegang Tiket:</strong> {{ $transaction->customer_name }}</p>
            <p><strong>Acara:</strong> {{ $transaction->event->title }}</p>
            <p><strong>Tanggal & Waktu:</strong> {{ \Carbon\Carbon::parse($transaction->event->date)->format('d M Y, H:i') }} WIB</p>
            <p><strong>Lokasi:</strong> {{ $transaction->event->location }}</p>
            <p><strong>Status Pembayaran:</strong> <span style="color: green; font-weight: bold;">PAID / LUNAS</span></p>
        </div>
        
        <div class="qr-code">
            <!-- Menampilkan QR Code Kode Unik Transaksi -->
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $transaction->order_id }}" alt="QR Code Tiket">
            <p style="font-size: 12px; margin-top: 5px;">Tunjukkan QR Code ini di pintu masuk acara.</p>
        </div>

        <div class="footer">
            <p>Terima kasih telah memesan tiket melalui AmikomEventHub.</p>
        </div>
    </div>
</body>
</html>