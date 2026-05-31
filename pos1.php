
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaundryPOS - Multi User | DOKU QRIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 50:'#eff6ff',100:'#dbeafe',200:'#bfdbfe',300:'#93c5fd',400:'#60a5fa',500:'#3b82f6',600:'#2563eb',700:'#1d4ed8',800:'#1e40af',900:'#1e3a8a' },
                        accent: { 50:'#fdf4ff',100:'#fae8ff',200:'#f5d0fe',300:'#f0abfc',400:'#e879f9',500:'#d946ef',600:'#c026d3',700:'#a21caf',800:'#86198f',900:'#701a75' },
                        surface: { 50:'#f8fafc',100:'#f1f5f9',200:'#e2e8f0',300:'#cbd5e1',400:'#94a3b8',500:'#64748b',600:'#475569',700:'#334155',800:'#1e293b',900:'#0f172a' },
                        doku: { 50:'#f0f7ff',100:'#e0effe',200:'#b9dffd',300:'#7cc5fc',400:'#36a5fa',500:'#0b88f5',600:'#0069d9',700:'#0054b0',800:'#004790',900:'#003b74' }
                    },
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.bunny.net/css?family=inter:400,500,600,700,800');
        * { scrollbar-width: thin; scrollbar-color: #94a3b8 transparent; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 3px; }
        .sidebar-link { transition: all 0.2s ease; }
        .sidebar-link:hover, .sidebar-link.active { background: linear-gradient(135deg, rgba(59,130,246,0.15), rgba(217,70,239,0.1)); border-right: 3px solid #3b82f6; }
        .sidebar-link.active { color: #2563eb; font-weight: 600; }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); }
        .fade-in { animation: fadeIn 0.3s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .slide-up { animation: slideUp 0.4s ease-out; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .modal-overlay { animation: overlayIn 0.2s ease-out; }
        @keyframes overlayIn { from { opacity: 0; } to { opacity: 1; } }
        .modal-content { animation: modalIn 0.3s ease-out; }
        @keyframes modalIn { from { opacity: 0; transform: scale(0.95) translateY(10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
        .receipt-line { border-top: 2px dashed #cbd5e1; }
        .login-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #701a75 50%, #1e40af 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }
        @keyframes gradientShift { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        .login-card { animation: loginIn 0.5s ease-out; }
        @keyframes loginIn { from { opacity: 0; transform: translateY(30px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }
        @keyframes pulseRing { 0% { box-shadow: 0 0 0 0 rgba(11,136,245,0.4); } 70% { box-shadow: 0 0 0 15px rgba(11,136,245,0); } 100% { box-shadow: 0 0 0 0 rgba(11,136,245,0); } }
        .qris-pulse { animation: pulseRing 2s infinite; }
        @keyframes spinLoader { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .spinner { animation: spinLoader 1s linear infinite; }
        @keyframes successPop { 0% { transform: scale(0); opacity: 0; } 50% { transform: scale(1.2); } 100% { transform: scale(1); opacity: 1; } }
        .success-pop { animation: successPop 0.5s ease-out; }
        @keyframes countdownPulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.6; } }
        .countdown-pulse { animation: countdownPulse 1s ease-in-out infinite; }
        @media print {
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; color-adjust: exact !important; }
            body { background: white !important; }
            #loginPage, #appContainer, #sidebar, header, .no-print, #toastContainer, #paymentModal, #profileModal, #changePasswordModal, #serviceModal, #customerModal, #userModal { display: none !important; }
            #receiptModal { display: block !important; position: static !important; background: none !important; }
            #receiptModal .modal-content { position: static !important; transform: none !important; animation: none !important; margin: 0 auto !important; padding: 20px !important; box-shadow: none !important; width: 320px !important; background: white !important; }
        }
    </style>
</head>
<body class="bg-surface-50 font-sans text-surface-800">

    <!-- LOGIN PAGE -->
    <div id="loginPage" class="login-bg min-h-screen flex items-center justify-center p-4">
        <div class="login-card w-full max-w-md">
            <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 shadow-2xl">
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-gradient-to-br from-doku-500 to-accent-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl"><i class="ri-shirt-line text-white text-3xl"></i></div>
                    <h1 class="text-3xl font-bold text-white">LaundryPOS</h1>
                    <p class="text-white/60 mt-1">Sistem Kasir Laundry Multi User</p>
                </div>
                <form onsubmit="handleLogin(event)" class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-white/80 mb-2">Username</label>
                        <div class="relative"><i class="ri-user-line absolute left-4 top-1/2 -translate-y-1/2 text-white/40"></i><input type="text" id="loginUsername" required class="w-full pl-12 pr-4 py-3.5 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-white/50" placeholder="Masukkan username"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-white/80 mb-2">Password</label>
                        <div class="relative"><i class="ri-lock-line absolute left-4 top-1/2 -translate-y-1/2 text-white/40"></i><input type="password" id="loginPassword" required class="w-full pl-12 pr-12 py-3.5 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-white/50" placeholder="Masukkan password"><button type="button" onclick="togglePwd()" class="absolute right-4 top-1/2 -translate-y-1/2 text-white/40 hover:text-white/70"><i id="eyeIcon" class="ri-eye-line text-xl"></i></button></div>
                    </div>
                    <button type="submit" class="w-full py-3.5 bg-white text-surface-800 rounded-xl font-bold text-lg hover:bg-white/90 transition-all shadow-lg flex items-center justify-center gap-2"><i class="ri-login-circle-line"></i> Masuk</button>
                </form>
                <div class="mt-6 p-4 bg-white/5 rounded-xl border border-white/10">
                    <p class="text-xs text-white/60 font-semibold mb-2">📋 Akun Demo:</p>
                    <div class="space-y-1 text-xs text-white/70"><p><span class="text-white font-semibold">admin</span> / admin123 — <span class="text-red-300">Admin</span></p><p><span class="text-white font-semibold">kasir1</span> / kasir123 — <span class="text-blue-300">Kasir</span></p><p><span class="text-white font-semibold">staff1</span> / staff123 — <span class="text-purple-300">Staff</span></p></div>
                </div>
            </div>
        </div>
        <div id="loginError" class="fixed top-4 left-1/2 -translate-x-1/2 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg hidden slide-up"><i class="ri-error-warning-line mr-2"></i> <span id="loginErrorText"></span></div>
    </div>

    <!-- MAIN APP -->
    <div id="appContainer" class="hidden">
        <div class="flex h-screen overflow-hidden">
            <aside id="sidebar" class="w-64 bg-white border-r border-surface-200 flex flex-col no-print">
                <div class="p-5 border-b border-surface-100"><div class="flex items-center gap-3"><div class="w-10 h-10 bg-gradient-to-br from-doku-500 to-accent-500 rounded-xl flex items-center justify-center shadow-lg"><i class="ri-shirt-line text-white text-xl"></i></div><div><h1 class="text-lg font-bold bg-gradient-to-r from-doku-600 to-accent-600 bg-clip-text text-transparent">LaundryPOS</h1><p class="text-xs text-surface-400">Powered by DOKU</p></div></div></div>
                <div class="p-4 border-b border-surface-100 bg-surface-50"><div class="flex items-center gap-3"><div id="userAvatar" class="w-10 h-10 bg-gradient-to-br from-primary-400 to-accent-400 rounded-full flex items-center justify-center text-white font-bold text-sm cursor-pointer">A</div><div class="flex-1 min-w-0"><p id="userName" class="text-sm font-semibold text-surface-800 truncate cursor-pointer">Admin</p><p id="userRole" class="text-xs text-doku-600 font-medium cursor-pointer">Admin</p></div></div></div>
                <nav class="flex-1 py-4 px-2 space-y-1 overflow-y-auto" id="sidebarNav">
                    <a href="#" onclick="navigate('dashboard')" class="sidebar-link active flex items-center gap-3 px-4 py-3 rounded-lg text-sm" data-page="dashboard"><i class="ri-dashboard-line text-lg"></i> Dashboard</a>
                    <a href="#" onclick="navigate('orders')" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-surface-600" data-page="orders"><i class="ri-file-list-3-line text-lg"></i> Pesanan <span id="orderBadge" class="ml-auto bg-primary-100 text-primary-700 text-xs px-2 py-0.5 rounded-full font-semibold hidden">0</span></a>
                    <a href="#" onclick="navigate('neworder')" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-surface-600" data-page="neworder"><i class="ri-add-circle-line text-lg"></i> Buat Pesanan</a>
                    <a href="#" onclick="navigate('services')" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-surface-600" data-page="services"><i class="ri-settings-3-line text-lg"></i> Layanan & Harga</a>
                    <a href="#" onclick="navigate('customers')" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-surface-600" data-page="customers"><i class="ri-group-line text-lg"></i> Pelanggan</a>
                    <a href="#" onclick="navigate('reports')" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-surface-600" data-page="reports"><i class="ri-bar-chart-box-line text-lg"></i> Laporan</a>
                    <a href="#" onclick="navigate('users')" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-surface-600" data-page="users"><i class="ri-user-settings-line text-lg"></i> Kelola User</a>
                    <a href="#" onclick="navigate('activity')" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-surface-600" data-page="activity"><i class="ri-history-line text-lg"></i> Log Aktivitas</a>
                </nav>
                <div class="p-4 border-t border-surface-100"><button onclick="handleLogout()" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-red-500 hover:bg-red-50 transition-all"><i class="ri-logout-box-r-line text-lg"></i> Keluar</button></div>
            </aside>

            <main class="flex-1 flex flex-col overflow-hidden">
                <header class="bg-white border-b border-surface-200 px-6 py-4 flex items-center justify-between no-print">
                    <div class="flex items-center gap-4"><button onclick="toggleSidebar()" class="p-2 rounded-lg hover:bg-surface-100 lg:hidden"><i class="ri-menu-line text-xl"></i></button><div><h2 id="pageTitle" class="text-xl font-bold text-surface-800">Dashboard</h2><p id="pageSubtitle" class="text-sm text-surface-400">Ringkasan aktivitas</p></div></div>
                    <div class="flex items-center gap-3"><div class="text-right hidden sm:block"><p class="text-sm font-semibold text-surface-700" id="currentDate"></p><p class="text-xs text-surface-400" id="currentTime"></p></div><button onclick="navigate('neworder')" class="bg-gradient-to-r from-doku-500 to-accent-500 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:shadow-lg transition-all flex items-center gap-2"><i class="ri-add-line"></i> Pesanan Baru</button></div>
                </header>

                <div id="contentArea" class="flex-1 overflow-y-auto p-6">
                    <div id="page-dashboard" class="page-content fade-in"></div>
                    <div id="page-orders" class="page-content hidden fade-in"></div>
                    <div id="page-neworder" class="page-content hidden fade-in"></div>
                    <div id="page-services" class="page-content hidden fade-in"></div>
                    <div id="page-customers" class="page-content hidden fade-in"></div>
                    <div id="page-reports" class="page-content hidden fade-in"></div>
                    <div id="page-users" class="page-content hidden fade-in"></div>
                    <div id="page-activity" class="page-content hidden fade-in"></div>
                </div>
            </main>
        </div>
    </div>

    <!-- PAYMENT MODAL -->
    <div id="paymentModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4 no-print">
        <div class="bg-white rounded-2xl w-full max-w-lg modal-content max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-5"><h3 class="text-lg font-bold">💳 Pembayaran</h3><button onclick="closePayment()" class="p-2 hover:bg-surface-100 rounded-lg"><i class="ri-close-line text-xl"></i></button></div>
                <div class="text-center mb-6 p-4 bg-surface-50 rounded-xl"><p class="text-sm text-surface-500">Total Tagihan</p><p class="text-3xl font-bold text-surface-800" id="payTotal">Rp 0</p><p class="text-xs text-surface-400 mt-1" id="payInvoice"></p></div>
                <div class="mb-5"><label class="block text-sm font-medium text-surface-600 mb-2">Metode Pembayaran</label><div class="grid grid-cols-3 gap-3">
                    <button onclick="pickPay('qris')" class="pay-btn px-4 py-3 border-2 border-doku-200 rounded-xl text-center bg-doku-50" data-m="qris"><svg class="w-8 h-8 mx-auto mb-1" viewBox="0 0 32 32" fill="none"><rect x="2" y="2" width="12" height="12" rx="2" fill="#0069D9"/><rect x="4" y="4" width="8" height="8" rx="1" fill="white"/><rect x="5.5" y="5.5" width="5" height="5" rx="0.5" fill="#0069D9"/><rect x="18" y="2" width="12" height="12" rx="2" fill="#0069D9"/><rect x="20" y="4" width="8" height="8" rx="1" fill="white"/><rect x="21.5" y="5.5" width="5" height="5" rx="0.5" fill="#0069D9"/><rect x="2" y="18" width="12" height="12" rx="2" fill="#0069D9"/><rect x="4" y="20" width="8" height="8" rx="1" fill="white"/><rect x="5.5" y="21.5" width="5" height="5" rx="0.5" fill="#0069D9"/></svg><span class="text-xs font-semibold text-doku-700">DOKU QRIS</span></button>
                    <button onclick="pickPay('tunai')" class="pay-btn px-4 py-3 border-2 border-surface-200 rounded-xl text-center" data-m="tunai"><i class="ri-money-dollar-circle-line text-2xl mb-1 block text-surface-500"></i><span class="text-xs font-semibold">Tunai</span></button>
                    <button onclick="pickPay('transfer')" class="pay-btn px-4 py-3 border-2 border-surface-200 rounded-xl text-center" data-m="transfer"><i class="ri-bank-card-line text-2xl mb-1 block text-surface-500"></i><span class="text-xs font-semibold">Transfer</span></button>
                </div></div>
                <div id="qrisBox" class="mb-5 hidden"><div class="border border-doku-200 rounded-xl overflow-hidden bg-white"><div class="bg-doku-600 px-4 py-3 flex items-center justify-between"><div class="flex items-center gap-2"><div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center"><span class="text-doku-600 font-bold text-xs">DOKU</span></div><span class="text-white text-sm font-semibold">QRIS Payment</span></div><span class="text-white/70 text-xs">Powered by DOKU</span></div><div class="p-5 text-center">
                    <div id="qInit"><button onclick="genQRIS()" class="w-full py-3 bg-doku-600 text-white rounded-xl font-semibold hover:bg-doku-700 flex items-center justify-center gap-2"><i class="ri-qr-code-line text-lg"></i> Generate QRIS Code</button></div>
                    <div id="qLoad" class="hidden"><div class="py-8"><div class="w-12 h-12 border-4 border-doku-200 border-t-doku-600 rounded-full spinner mx-auto mb-4"></div><p class="text-sm text-surface-600 font-medium">Menghubungkan ke DOKU...</p><p class="text-xs text-surface-400 mt-1">Requesting QR code</p></div></div>
                    <div id="qShow" class="hidden"><div class="mb-3"><div class="inline-block p-3 bg-white rounded-xl border-2 border-doku-200 qris-pulse"><canvas id="dokuQR" class="rounded-lg"></canvas></div></div><div class="flex items-center justify-center gap-2 mb-3"><div class="px-2 py-1 bg-surface-100 rounded"><span class="text-xs font-bold text-surface-700">QRIS</span></div><span class="text-xs text-surface-400">Semua e-wallet & mobile banking</span></div><div class="flex items-center justify-center gap-2 mb-4 p-2 bg-amber-50 rounded-lg"><i class="ri-time-line text-amber-500"></i><span class="text-sm text-amber-700 font-semibold countdown-pulse" id="qrTimer">05:00</span></div><div id="qrStatus" class="p-3 bg-surface-50 rounded-xl mb-4"><div class="flex items-center justify-center gap-2"><div class="w-2 h-2 bg-doku-500 rounded-full pulse-dot"></div><p class="text-sm text-surface-600 font-medium">Menunggu pembayaran...</p></div><p class="text-xs text-surface-400 mt-1">Scan dengan e-wallet atau mobile banking</p></div><div class="grid grid-cols-4 gap-2 mb-3"><div class="p-2 bg-surface-50 rounded-lg text-center"><div class="w-8 h-4 mx-auto bg-blue-500 rounded flex items-center justify-center"><span class="text-white text-[6px] font-bold">GOPAY</span></div><p class="text-[9px] text-surface-500 mt-1">GoPay</p></div><div class="p-2 bg-surface-50 rounded-lg text-center"><div class="w-8 h-4 mx-auto bg-purple-600 rounded flex items-center justify-center"><span class="text-white text-[6px] font-bold">DANA</span></div><p class="text-[9px] text-surface-500 mt-1">DANA</p></div><div class="p-2 bg-surface-50 rounded-lg text-center"><div class="w-8 h-4 mx-auto bg-red-500 rounded flex items-center justify-center"><span class="text-white text-[6px] font-bold">OVO</span></div><p class="text-[9px] text-surface-500 mt-1">OVO</p></div><div class="p-2 bg-surface-50 rounded-lg text-center"><div class="w-8 h-4 mx-auto bg-blue-700 rounded flex items-center justify-center"><span class="text-white text-[6px] font-bold">BCA</span></div><p class="text-[9px] text-surface-500 mt-1">m-BCA</p></div></div><div class="text-left p-3 bg-surface-50 rounded-xl space-y-1 text-xs"><div class="flex justify-between"><span class="text-surface-500">Merchant ID</span><span class="font-mono text-surface-700">DOKU-12345678</span></div><div class="flex justify-between"><span class="text-surface-500">Order ID</span><span class="font-mono text-surface-700" id="dOrd">-</span></div><div class="flex justify-between"><span class="text-surface-500">Session ID</span><span class="font-mono text-surface-700" id="dSes">-</span></div></div><div class="mt-4 pt-4 border-t border-surface-200"><p class="text-xs text-surface-400 mb-2">⚠️ Mode Demo</p><button onclick="simPayOK()" class="w-full py-3 bg-green-500 text-white rounded-xl font-semibold hover:bg-green-600 flex items-center justify-center gap-2"><i class="ri-checkbox-circle-line text-lg"></i> Simulasi Pembayaran Berhasil</button></div></div>
                    <div id="qDone" class="hidden"><div class="py-6"><div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 success-pop"><i class="ri-checkbox-circle-line text-green-500 text-4xl"></i></div><h4 class="text-lg font-bold text-green-700 mb-1">Pembayaran Berhasil!</h4><p class="text-sm text-surface-500 mb-4">Pembayaran via DOKU QRIS dikonfirmasi</p><div class="p-3 bg-green-50 rounded-xl space-y-1 text-xs text-left mb-4"><div class="flex justify-between"><span class="text-green-700">Status</span><span class="font-semibold text-green-700">PAID</span></div><div class="flex justify-between"><span class="text-green-700">DOKU Response</span><span class="font-mono text-green-700">00000000</span></div><div class="flex justify-between"><span class="text-green-700">Transaction ID</span><span class="font-mono text-green-700" id="dTX">-</span></div></div><button onclick="closePayment()" class="w-full py-3 bg-green-500 text-white rounded-xl font-semibold hover:bg-green-600"><i class="ri-check-line mr-1"></i> Selesai</button></div></div>
                    <div id="qExp" class="hidden"><div class="py-6"><div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4"><i class="ri-timer-2-line text-red-500 text-4xl"></i></div><h4 class="text-lg font-bold text-red-700 mb-1">QR Code Expired</h4><p class="text-sm text-surface-500 mb-4">QRIS code telah kadaluarsa</p><button onclick="genQRIS()" class="w-full py-3 bg-doku-600 text-white rounded-xl font-semibold hover:bg-doku-700"><i class="ri-refresh-line mr-1"></i> Generate Ulang</button></div></div>
                </div></div><div class="mt-3 p-3 bg-doku-50 rounded-xl border border-doku-100"><div class="flex items-start gap-2"><i class="ri-information-line text-doku-600 mt-0.5"></i><div class="text-xs text-doku-700"><p class="font-semibold mb-1">Integrasi DOKU QRIS</p><p>Production: API <code class="bg-doku-100 px-1 rounded">/api/v2/qr/qr-mpm-generate</code>. Webhook callback real-time.</p></div></div></div></div>
                <div id="cashBox" class="mb-5 hidden"><label class="block text-sm font-medium text-surface-600 mb-2">Jumlah Bayar</label><input type="number" id="cashIn" oninput="calcChg()" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-lg font-bold focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="Masukkan jumlah..."><div class="flex gap-2 mt-3 flex-wrap"><button onclick="setCash(0)" class="px-3 py-1.5 bg-surface-100 rounded-lg text-xs font-medium hover:bg-surface-200">Uang Pas</button><button onclick="setCash(50000)" class="px-3 py-1.5 bg-surface-100 rounded-lg text-xs font-medium hover:bg-surface-200">Rp 50.000</button><button onclick="setCash(100000)" class="px-3 py-1.5 bg-surface-100 rounded-lg text-xs font-medium hover:bg-surface-200">Rp 100.000</button></div><div class="mt-3 p-3 bg-green-50 rounded-xl flex justify-between items-center"><span class="text-sm text-green-700">Kembalian</span><span class="text-lg font-bold text-green-700" id="chgAmt">Rp 0</span></div></div>
                <div id="trfBox" class="mb-5 hidden"><div class="p-4 bg-surface-50 rounded-xl space-y-3"><div class="flex justify-between text-sm"><span class="text-surface-500">Bank</span><span class="font-semibold">BCA</span></div><div class="flex justify-between text-sm"><span class="text-surface-500">No. Rekening</span><span class="font-mono font-bold text-lg text-doku-600">123-456-7890</span></div><div class="flex justify-between text-sm"><span class="text-surface-500">Atas Nama</span><span class="font-semibold">PT LaundryPOS</span></div><div class="receipt-line my-2"></div><div class="flex justify-between text-sm"><span class="text-surface-500">Jumlah</span><span class="font-bold text-lg text-doku-600" id="trfAmt">Rp 0</span></div></div><button onclick="simTrfOK()" class="w-full mt-3 py-3 bg-doku-600 text-white rounded-xl font-semibold hover:bg-doku-700 flex items-center justify-center gap-2"><i class="ri-checkbox-circle-line"></i> Simulasi Transfer Berhasil</button></div>
                <button id="confPayBtn" onclick="confPay()" class="w-full py-3.5 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl font-semibold hover:shadow-lg transition-all flex items-center justify-center gap-2 hidden"><i class="ri-check-line"></i> Konfirmasi Pembayaran</button>
            </div>
        </div>
    </div>

    <!-- RECEIPT MODAL -->
    <div id="receiptModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-sm modal-content">
            <div class="p-6" id="rcptContent"><div class="text-center mb-4"><div class="w-12 h-12 bg-gradient-to-br from-doku-500 to-accent-500 rounded-xl flex items-center justify-center mx-auto mb-3"><i class="ri-shirt-line text-white text-xl"></i></div><h3 class="font-bold text-lg">LaundryPOS</h3><p class="text-xs text-surface-400">Jl. Contoh No. 123, Kota</p></div><div class="receipt-line my-3"></div><div class="space-y-2 text-sm mb-3"><div class="flex justify-between"><span class="text-surface-500">No. Invoice</span><span class="font-semibold" id="rInv"></span></div><div class="flex justify-between"><span class="text-surface-500">Tanggal</span><span id="rDate"></span></div><div class="flex justify-between"><span class="text-surface-500">Pelanggan</span><span id="rCust"></span></div><div class="flex justify-between"><span class="text-surface-500">Kasir</span><span id="rCashier"></span></div></div><div class="receipt-line my-3"></div><div id="rItems" class="space-y-2 text-sm mb-3"></div><div class="receipt-line my-3"></div><div class="space-y-1 text-sm"><div class="flex justify-between"><span class="text-surface-500">Total</span><span class="font-bold" id="rTotal"></span></div><div class="flex justify-between"><span class="text-surface-500">Pembayaran</span><span id="rPay"></span></div><div class="flex justify-between"><span class="text-surface-500">Kembalian</span><span id="rChg"></span></div></div><div class="receipt-line my-3"></div><p class="text-center text-xs text-surface-400 mt-4">Terima kasih! 🧺</p></div>
            <div class="p-4 border-t border-surface-100 flex gap-3 no-print"><button onclick="closeRcpt()" class="flex-1 py-3 border border-surface-200 rounded-xl text-sm font-semibold text-surface-600 hover:bg-surface-50">Tutup</button><button onclick="doPrint()" class="flex-1 py-3 bg-doku-600 text-white rounded-xl text-sm font-semibold hover:bg-doku-700 flex items-center justify-center gap-2"><i class="ri-printer-line"></i> Cetak</button></div>
        </div>
    </div>

    <!-- SERVICE MODAL -->
    <div id="svcModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4 no-print">
        <div class="bg-white rounded-2xl w-full max-w-md modal-content">
            <div class="p-6"><h3 class="text-lg font-bold mb-5" id="svcTitle">Tambah Layanan</h3><input type="hidden" id="svcEditId"><div class="space-y-4"><div><label class="block text-sm font-medium text-surface-600 mb-1">Nama</label><input type="text" id="svcName" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"></div><div><label class="block text-sm font-medium text-surface-600 mb-1">Harga</label><input type="number" id="svcPrice" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"></div><div><label class="block text-sm font-medium text-surface-600 mb-1">Satuan</label><select id="svcUnit" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"><option value="kg">Per Kg</option><option value="pcs">Per Pcs</option><option value="set">Per Set</option></select></div><div><label class="block text-sm font-medium text-surface-600 mb-1">Estimasi (jam)</label><input type="number" id="svcDur" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" value="24"></div><div><label class="block text-sm font-medium text-surface-600 mb-1">Ikon</label><input type="text" id="svcIcon" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" value="👕"></div></div><div class="flex gap-3 mt-6"><button onclick="closeSvc()" class="flex-1 py-3 border border-surface-200 rounded-xl text-sm font-semibold text-surface-600 hover:bg-surface-50">Batal</button><button onclick="saveSvc()" class="flex-1 py-3 bg-primary-500 text-white rounded-xl text-sm font-semibold hover:bg-primary-600">Simpan</button></div></div>
        </div>
    </div>

    <!-- CUSTOMER MODAL -->
    <div id="custModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4 no-print">
        <div class="bg-white rounded-2xl w-full max-w-md modal-content">
            <div class="p-6"><h3 class="text-lg font-bold mb-5" id="custTitle">Tambah Pelanggan</h3><input type="hidden" id="custEditId"><div class="space-y-4"><div><label class="block text-sm font-medium text-surface-600 mb-1">Nama</label><input type="text" id="cName" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"></div><div><label class="block text-sm font-medium text-surface-600 mb-1">Telepon</label><input type="tel" id="cPhone" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"></div><div><label class="block text-sm font-medium text-surface-600 mb-1">Alamat</label><textarea id="cAddr" rows="2" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"></textarea></div></div><div class="flex gap-3 mt-6"><button onclick="closeCust()" class="flex-1 py-3 border border-surface-200 rounded-xl text-sm font-semibold text-surface-600 hover:bg-surface-50">Batal</button><button onclick="saveCust()" class="flex-1 py-3 bg-primary-500 text-white rounded-xl text-sm font-semibold hover:bg-primary-600">Simpan</button></div></div>
        </div>
    </div>

    <!-- USER MODAL -->
    <div id="userModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4 no-print">
        <div class="bg-white rounded-2xl w-full max-w-md modal-content">
            <div class="p-6"><h3 class="text-lg font-bold mb-5" id="usrTitle">Tambah User</h3><input type="hidden" id="usrEditId"><div class="space-y-4"><div><label class="block text-sm font-medium text-surface-600 mb-1">Nama</label><input type="text" id="uName" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"></div><div><label class="block text-sm font-medium text-surface-600 mb-1">Username</label><input type="text" id="uUser" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"></div><div><label class="block text-sm font-medium text-surface-600 mb-1">Password</label><input type="password" id="uPass" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"></div><div><label class="block text-sm font-medium text-surface-600 mb-1">Role</label><select id="uRole" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"><option value="admin">Admin</option><option value="kasir">Kasir</option><option value="staff">Staff</option></select></div><div><label class="block text-sm font-medium text-surface-600 mb-1">Telepon</label><input type="tel" id="uPhone" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"></div><div><label class="block text-sm font-medium text-surface-600 mb-1">Status</label><select id="uStatus" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"><option value="active">Aktif</option><option value="inactive">Nonaktif</option></select></div></div><div class="flex gap-3 mt-6"><button onclick="closeUsr()" class="flex-1 py-3 border border-surface-200 rounded-xl text-sm font-semibold text-surface-600 hover:bg-surface-50">Batal</button><button onclick="saveUsr()" class="flex-1 py-3 bg-primary-500 text-white rounded-xl text-sm font-semibold hover:bg-primary-600">Simpan</button></div></div>
        </div>
    </div>

    <!-- PROFILE MODAL -->
    <div id="profModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4 no-print">
        <div class="bg-white rounded-2xl w-full max-w-md modal-content">
            <div class="p-6"><div class="text-center mb-5"><div id="profAv" class="w-20 h-20 bg-gradient-to-br from-primary-400 to-accent-400 rounded-full flex items-center justify-center text-white font-bold text-3xl mx-auto mb-3">A</div><h3 class="text-lg font-bold" id="profName">Admin</h3><p class="text-sm text-surface-400" id="profUser">@admin</p><span class="inline-block mt-2 text-xs text-white font-semibold px-3 py-1 rounded-full" id="profRole">Admin</span></div><div class="space-y-3 mb-5 text-sm"><div class="flex justify-between"><span class="text-surface-500">Login Terakhir</span><span class="font-medium" id="profLogin">-</span></div><div class="flex justify-between"><span class="text-surface-500">Total Transaksi</span><span class="font-medium" id="profOrders">0</span></div><div class="flex justify-between"><span class="text-surface-500">Total Pendapatan</span><span class="font-medium" id="profRev">Rp 0</span></div></div><div class="flex gap-3"><button onclick="closeProf()" class="flex-1 py-3 border border-surface-200 rounded-xl text-sm font-semibold text-surface-600 hover:bg-surface-50">Tutup</button><button onclick="showChgPwd()" class="flex-1 py-3 bg-primary-500 text-white rounded-xl text-sm font-semibold hover:bg-primary-600 flex items-center justify-center gap-2"><i class="ri-lock-password-line"></i> Ganti Password</button></div></div>
        </div>
    </div>

    <!-- CHANGE PASSWORD MODAL -->
    <div id="chgPwdModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4 no-print">
        <div class="bg-white rounded-2xl w-full max-w-md modal-content">
            <div class="p-6"><h3 class="text-lg font-bold mb-5">🔒 Ganti Password</h3><div class="space-y-4"><div><label class="block text-sm font-medium text-surface-600 mb-1">Password Lama</label><input type="password" id="oldPwd" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"></div><div><label class="block text-sm font-medium text-surface-600 mb-1">Password Baru</label><input type="password" id="newPwd" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"></div><div><label class="block text-sm font-medium text-surface-600 mb-1">Konfirmasi</label><input type="password" id="cfmPwd" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"></div></div><div class="flex gap-3 mt-6"><button onclick="closeChgPwd()" class="flex-1 py-3 border border-surface-200 rounded-xl text-sm font-semibold text-surface-600 hover:bg-surface-50">Batal</button><button onclick="doChgPwd()" class="flex-1 py-3 bg-primary-500 text-white rounded-xl text-sm font-semibold hover:bg-primary-600">Simpan</button></div></div>
        </div>
    </div>

    <div id="toastBox" class="fixed bottom-4 right-4 z-[100] space-y-2"></div>

    <script>
        // ===== DATA =====
        let users = [
            { id: 1, name: 'Administrator', username: 'admin', password: 'admin123', role: 'admin', phone: '081200000001', status: 'active', lastLogin: '2024-01-15 08:00' },
            { id: 2, name: 'Siti Kasir', username: 'kasir1', password: 'kasir123', role: 'kasir', phone: '081200000002', status: 'active', lastLogin: '' },
            { id: 3, name: 'Budi Staff', username: 'staff1', password: 'staff123', role: 'staff', phone: '081200000003', status: 'active', lastLogin: '' },
            { id: 4, name: 'Rina Kasir', username: 'kasir2', password: 'kasir123', role: 'kasir', phone: '081200000004', status: 'inactive', lastLogin: '' }
        ];
        let currentUser = null;
        let activityLog = [];
        let nextUserId = 5;
        let services = [
            { id: 1, name: 'Cuci Reguler', price: 7000, unit: 'kg', duration: 24, icon: '👕' },
            { id: 2, name: 'Cuci Express', price: 12000, unit: 'kg', duration: 6, icon: '⚡' },
            { id: 3, name: 'Cuci + Setrika', price: 10000, unit: 'kg', duration: 24, icon: '👔' },
            { id: 4, name: 'Setrika Saja', price: 5000, unit: 'kg', duration: 12, icon: '♨️' },
            { id: 5, name: 'Cuci Bedcover', price: 25000, unit: 'set', duration: 48, icon: '🛏️' },
            { id: 6, name: 'Cuci Sepatu', price: 35000, unit: 'pcs', duration: 48, icon: '👟' },
            { id: 7, name: 'Dry Clean', price: 20000, unit: 'pcs', duration: 72, icon: '✨' },
            { id: 8, name: 'Cuci Karpet', price: 15000, unit: 'kg', duration: 48, icon: '🧶' }
        ];
        let customers = [
            { id: 1, name: 'Budi Santoso', phone: '081234567890', address: 'Jl. Merdeka No. 10' },
            { id: 2, name: 'Siti Rahayu', phone: '082345678901', address: 'Jl. Sudirman No. 5' },
            { id: 3, name: 'Ahmad Wijaya', phone: '083456789012', address: 'Jl. Ahmad Yani No. 22' }
        ];
        let orders = [];
        let nextInv = 1;
        let nextSvcId = 9;
        let nextCustId = 4;
        let curPayId = null;
        let selPay = null;
        let qrTimer = null;
        let qrTime = 300;

        const perms = { admin: ['dashboard','orders','neworder','services','customers','reports','users','activity'], kasir: ['dashboard','orders','neworder','customers'], staff: ['dashboard','orders'] };

        // ===== AUTH =====
        function handleLogin(e) {
            e.preventDefault();
            const u = document.getElementById('loginUsername').value.trim();
            const p = document.getElementById('loginPassword').value;
            const user = users.find(x => x.username === u && x.password === p);
            if (!user) { showLoginErr('Username atau password salah'); return; }
            if (user.status === 'inactive') { showLoginErr('Akun dinonaktifkan. Hubungi admin.'); return; }
            user.lastLogin = new Date().toISOString().replace('T', ' ').substring(0, 16);
            currentUser = { ...user };
            addLog('login', `${user.name} (${user.role}) login`);
            document.getElementById('loginPage').classList.add('hidden');
            document.getElementById('appContainer').classList.remove('hidden');
            boot();
        }
        function handleLogout() {
            if (currentUser) addLog('logout', `${currentUser.name} logout`);
            currentUser = null;
            document.getElementById('appContainer').classList.add('hidden');
            document.getElementById('loginPage').classList.remove('hidden');
            document.getElementById('loginUsername').value = '';
            document.getElementById('loginPassword').value = '';
        }
        function showLoginErr(msg) {
            const el = document.getElementById('loginError');
            document.getElementById('loginErrorText').textContent = msg;
            el.classList.remove('hidden');
            setTimeout(() => el.classList.add('hidden'), 3000);
        }
        function togglePwd() {
            const i = document.getElementById('loginPassword');
            const ic = document.getElementById('eyeIcon');
            if (i.type === 'password') { i.type = 'text'; ic.className = 'ri-eye-off-line text-xl'; }
            else { i.type = 'password'; ic.className = 'ri-eye-line text-xl'; }
        }

        // ===== INIT =====
        function boot() {
            tick(); setInterval(tick, 1000);
            loadSample();
            applyPerms();
            updUser();
            fillSvcSel();
            fillCustList();
            setDates();
            go('dashboard');
        }
        function tick() {
            const n = new Date();
            document.getElementById('currentDate').textContent = n.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            document.getElementById('currentTime').textContent = n.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        }
        function applyPerms() {
            const ok = perms[currentUser.role] || [];
            document.querySelectorAll('.sidebar-link').forEach(l => l.style.display = ok.includes(l.dataset.page) ? '' : 'none');
        }
        function updUser() {
            if (!currentUser) return;
            document.getElementById('userName').textContent = currentUser.name;
            document.getElementById('userRole').textContent = currentUser.role.charAt(0).toUpperCase() + currentUser.role.slice(1);
            document.getElementById('userAvatar').textContent = currentUser.name.charAt(0).toUpperCase();
            ['userName','userRole','userAvatar'].forEach(id => {
                const el = document.getElementById(id); el.style.cursor = 'pointer'; el.onclick = showProf;
            });
        }
        function addLog(type, desc) {
            const now = new Date();
            activityLog.unshift({ id: activityLog.length + 1, userId: currentUser ? currentUser.id : 0, userName: currentUser ? currentUser.name : 'System', userRole: currentUser ? currentUser.role : '', type, desc, timestamp: now.toISOString(), time: now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }), date: now.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) });
        }

        // ===== SAMPLE =====
        function loadSample() {
            const s = [{ u: 1, st: 'selesai', py: 'qris', pd: true }, { u: 2, st: 'diproses', py: 'tunai', pd: true }, { u: 2, st: 'menunggu', py: 'qris', pd: false }, { u: 1, st: 'diambil', py: 'qris', pd: true }, { u: 1, st: 'selesai', py: 'transfer', pd: true }, { u: 2, st: 'selesai', py: 'qris', pd: true }];
            s.forEach(a => {
                const d = new Date(); d.setDate(d.getDate() - Math.floor(Math.random() * 5));
                const ds = d.toISOString().split('T')[0];
                const tm = `${String(8 + Math.floor(Math.random() * 10)).padStart(2, '0')}:${String(Math.floor(Math.random() * 60)).padStart(2, '0')}`;
                const inv = `INV-${String(nextInv).padStart(4, '0')}`;
                const svc = services[Math.floor(Math.random() * services.length)];
                const w = svc.unit === 'kg' ? (Math.random() * 4 + 1).toFixed(1) : 0;
                const q = svc.unit !== 'kg' ? Math.floor(Math.random() * 3 + 1) : 1;
                const tot = svc.price * (parseFloat(w) || q);
                orders.push({ id: nextInv, invoice: inv, customerId: customers[Math.floor(Math.random() * customers.length)].id, items: [{ serviceId: svc.id, weight: parseFloat(w) || 0, qty: q, subtotal: tot }], total: tot, status: a.st, payment: a.py, paid: a.pd, cashPaid: a.py === 'tunai' ? Math.ceil(tot / 1000) * 1000 : 0, change: 0, cashierId: a.u, date: ds, time: tm, notes: '', dokuTX: a.pd && a.py === 'qris' ? `DOKU-${Date.now()}-${nextInv}` : null });
                nextInv++;
            });
        }

        // ===== NAV =====
        function go(page) {
            document.querySelectorAll('.page-content').forEach(el => el.classList.add('hidden'));
            document.getElementById('page-' + page).classList.remove('hidden');
            document.querySelectorAll('.sidebar-link').forEach(el => el.classList.remove('active'));
            const link = document.querySelector(`.sidebar-link[data-page="${page}"]`);
            if (link) link.classList.add('active');
            const t = { dashboard: ['Dashboard', 'Ringkasan aktivitas'], orders: ['Pesanan', 'Kelola pesanan'], neworder: ['Pesanan Baru', 'Buat pesanan baru'], services: ['Layanan & Harga', 'Kelola layanan'], customers: ['Pelanggan', 'Data pelanggan'], reports: ['Laporan', 'Analisis pendapatan'], users: ['Kelola User', 'Manajemen pengguna'], activity: ['Log Aktivitas', 'Riwayat aktivitas'] };
            if (t[page]) { document.getElementById('pageTitle').textContent = t[page][0]; document.getElementById('pageSubtitle').textContent = t[page][1]; }
            const renders = { dashboard: renderDash, orders: renderOrd, services: renderSvc, customers: renderCust, reports: renderRpt, users: renderUsr, activity: renderAct };
            if (renders[page]) renders[page]();
            if (page === 'neworder') { fillSvcSel(); fillCustList(); calcTot(); document.getElementById('ordCashier').textContent = `${currentUser.name} (${currentUser.role})`; }
            if (page === 'orders') { renderOrd(); fillFilterUsr(); }
            if (page === 'activity') { renderAct(); fillActUsr(); }
        }
        function toggleSidebar() { document.getElementById('sidebar').classList.toggle('-translate-x-full'); }

        // ===== HELPERS =====
        const rp = n => 'Rp ' + n.toLocaleString('id-ID');
        const rps = n => n >= 1e6 ? (n / 1e6).toFixed(1) + 'jt' : n >= 1e3 ? (n / 1e3).toFixed(0) + 'rb' : n.toString();
        const rc = r => ({ admin: 'bg-red-100 text-red-700', kasir: 'bg-blue-100 text-blue-700', staff: 'bg-purple-100 text-purple-700' }[r] || 'bg-surface-100 text-surface-600');
        const rb = r => ({ admin: 'bg-gradient-to-br from-red-400 to-red-600', kasir: 'bg-gradient-to-br from-blue-400 to-blue-600', staff: 'bg-gradient-to-br from-purple-400 to-purple-600' }[r] || 'bg-surface-400');
        const sb = s => { const c = { menunggu: 'bg-amber-100 text-amber-700', diproses: 'bg-blue-100 text-blue-700', selesai: 'bg-green-100 text-green-700', diambil: 'bg-purple-100 text-purple-700' }; const l = { menunggu: 'Menunggu', diproses: 'Diproses', selesai: 'Selesai', diambil: 'Diambil' }; return `<span class="text-xs px-2.5 py-1 rounded-full font-medium ${c[s] || ''}">${l[s] || s}</span>`; };
        function toast(msg, type = 'info') {
            const c = document.getElementById('toastBox');
            const cl = { success: 'bg-green-500', error: 'bg-red-500', warning: 'bg-amber-500', info: 'bg-primary-500' };
            const ic = { success: 'ri-check-line', error: 'ri-close-line', warning: 'ri-alert-line', info: 'ri-information-line' };
            const t = document.createElement('div');
            t.className = `${cl[type]} text-white px-4 py-3 rounded-xl shadow-lg flex items-center gap-2 text-sm font-medium slide-up min-w-[280px]`;
            t.innerHTML = `<i class="${ic[type]} text-lg"></i> ${msg}`;
            c.appendChild(t);
            setTimeout(() => { t.style.opacity = '0'; t.style.transform = 'translateX(20px)'; t.style.transition = 'all 0.3s'; setTimeout(() => t.remove(), 300); }, 3000);
        }

        // ===== DASHBOARD =====
        function renderDash() {
            const td = new Date().toISOString().split('T')[0];
            const to = orders.filter(o => o.date === td);
            const tr = to.reduce((s, o) => s + o.total, 0);
            const pr = orders.filter(o => o.status === 'diproses').length;
            const au = users.filter(u => u.status === 'active').length;
            const qc = orders.filter(o => o.date === td && o.payment === 'qris').length;
            document.getElementById('statTodayOrders').textContent = to.length;
            document.getElementById('statTodayRevenue').textContent = rp(tr);
            document.getElementById('statProcessing').textContent = pr;
            document.getElementById('statQrisCount').textContent = qc;

            const uc = orders.filter(o => !o.paid).length;
            const badge = document.getElementById('orderBadge');
            badge.textContent = uc; badge.classList.toggle('hidden', uc === 0);

            const recent = [...orders].reverse().slice(0, 5);
            document.getElementById('pgDashRecent').innerHTML = recent.length === 0 ? '<tr><td colspan="5" class="py-8 text-center text-surface-400">Belum ada pesanan</td></tr>' : recent.map(o => {
                const c = customers.find(x => x.id === o.customerId);
                const ca = users.find(x => x.id === o.cashierId);
                return `<tr class="border-b border-surface-50 hover:bg-surface-50 cursor-pointer" onclick="viewOrd(${o.id})"><td class="py-3 px-3 font-medium text-primary-600">${o.invoice}</td><td class="py-3 px-3">${c ? c.name : '-'}</td><td class="py-3 px-3 font-semibold">${rp(o.total)}</td><td class="py-3 px-3">${sb(o.status)}</td><td class="py-3 px-3"><span class="text-xs px-2 py-1 rounded-full ${rc(ca?.role || '')}">${ca ? ca.name : '-'}</span></td></tr>`;
            }).join('');

            const sc = { menunggu: orders.filter(o => o.status === 'menunggu').length, diproses: orders.filter(o => o.status === 'diproses').length, selesai: orders.filter(o => o.status === 'selesai').length, diambil: orders.filter(o => o.status === 'diambil').length };
            const tot = orders.length || 1;
            const cc = { menunggu: 'bg-amber-500', diproses: 'bg-blue-500', selesai: 'bg-green-500', diambil: 'bg-purple-500' };
            const ll = { menunggu: 'Menunggu', diproses: 'Diproses', selesai: 'Selesai', diambil: 'Diambil' };
            document.getElementById('pgDashStatus').innerHTML = Object.entries(sc).map(([k, v]) => `<div class="flex items-center gap-3"><div class="w-3 h-3 rounded-full ${cc[k]}"></div><span class="text-sm text-surface-600 flex-1">${ll[k]}</span><div class="w-32 bg-surface-100 rounded-full h-2.5 overflow-hidden"><div class="${cc[k]} h-full rounded-full" style="width: ${(v / tot * 100)}%"></div></div><span class="text-sm font-semibold w-8 text-right">${v}</span></div>`).join('');

            const rl = [...users].filter(u => u.lastLogin).sort((a, b) => b.lastLogin.localeCompare(a.lastLogin)).slice(0, 4);
            document.getElementById('pgDashLogin').innerHTML = rl.length === 0 ? '<p class="text-xs text-surface-400">Belum ada login</p>' : rl.map(u => `<div class="flex items-center gap-2"><div class="w-7 h-7 ${rb(u.role)} rounded-full flex items-center justify-center text-white text-xs font-bold">${u.name.charAt(0)}</div><div class="flex-1 min-w-0"><p class="text-xs font-medium truncate">${u.name}</p><p class="text-[10px] text-surface-400">${u.lastLogin}</p></div><span class="text-[10px] px-1.5 py-0.5 rounded-full ${rc(u.role)}">${u.role}</span></div>`).join('');
        }

        // ===== ORDERS =====
        function fillFilterUsr() { document.getElementById('filterUser').innerHTML = '<option value="all">Semua User</option>' + users.map(u => `<option value="${u.id}">${u.name}</option>`).join(''); }
        function renderOrd() {
            const s = (document.getElementById('searchOrder')?.value || '').toLowerCase();
            const fs = document.getElementById('filterStatus')?.value || 'all';
            const fu = document.getElementById('filterUser')?.value || 'all';
            let f = orders.filter(o => {
                const c = customers.find(x => x.id === o.customerId);
                return (o.invoice.toLowerCase().includes(s) || (c && c.name.toLowerCase().includes(s))) && (fs === 'all' || o.status === fs) && (fu === 'all' || o.cashierId === parseInt(fu));
            });
            f.sort((a, b) => b.id - a.id);
            document.getElementById('pgOrdCount').textContent = f.length + ' pesanan';
            const tbody = document.getElementById('pgOrdTbody');
            const emp = document.getElementById('pgOrdEmpty');
            if (f.length === 0) { tbody.innerHTML = ''; emp.classList.remove('hidden'); return; }
            emp.classList.add('hidden');
            const cp = currentUser.role === 'admin' || currentUser.role === 'kasir';
            const ce = currentUser.role === 'admin' || currentUser.role === 'kasir';
            tbody.innerHTML = f.map(o => {
                const c = customers.find(x => x.id === o.customerId);
                const ca = users.find(x => x.id === o.cashierId);
                const sn = o.items.map(i => { const sv = services.find(s => s.id === i.serviceId); return sv ? sv.name : ''; }).filter(Boolean).join(', ');
                const pi = o.paid ? '<i class="ri-check-line text-green-500"></i>' : '<i class="ri-close-line text-red-400"></i>';
                const pl = o.paid ? (o.payment === 'qris' ? '📱 QRIS' : o.payment === 'tunai' ? '💵 Tunai' : '🏦 Transfer') : '-';
                return `<tr class="border-b border-surface-50 hover:bg-surface-50"><td class="py-3 px-3 font-medium text-primary-600 cursor-pointer" onclick="viewOrd(${o.id})">${o.invoice}</td><td class="py-3 px-3 text-surface-500">${o.date}</td><td class="py-3 px-3">${c ? c.name : '-'}</td><td class="py-3 px-3 text-xs">${sn}</td><td class="py-3 px-3 text-right font-semibold">${rp(o.total)}</td><td class="py-3 px-3 text-center">${sb(o.status)}</td><td class="py-3 px-3 text-center text-xs">${pi} ${pl}</td><td class="py-3 px-3"><span class="text-xs px-2 py-1 rounded-full ${rc(ca?.role || '')}">${ca ? ca.name : '-'}</span></td><td class="py-3 px-3 text-center"><div class="flex items-center justify-center gap-1">${!o.paid && cp ? `<button onclick="openPay(${o.id})" class="p-1.5 text-green-600 hover:bg-green-50 rounded-lg" title="Bayar"><i class="ri-money-dollar-circle-line"></i></button>` : ''}<button onclick="viewOrd(${o.id})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg" title="Detail"><i class="ri-eye-line"></i></button>${ce ? `<button onclick="chgStatus(${o.id})" class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg" title="Update"><i class="ri-refresh-line"></i></button>` : ''}${currentUser.role === 'admin' ? `<button onclick="delOrd(${o.id})" class="p-1.5 text-red-400 hover:bg-red-50 rounded-lg" title="Hapus"><i class="ri-delete-bin-line"></i></button>` : ''}</div></td></tr>`;
            }).join('');
        }
        function viewOrd(id) { const o = orders.find(x => x.id === id); if (o) showRcpt(o); }
        function chgStatus(id) { const o = orders.find(x => x.id === id); if (!o) return; const fl = ['menunggu', 'diproses', 'selesai', 'diambil']; const idx = fl.indexOf(o.status); if (idx < fl.length - 1) { o.status = fl[idx + 1]; addLog('order', `Status ${o.invoice} → "${o.status}" oleh ${currentUser.name}`); toast(`Status ${o.invoice} → "${o.status}"`, 'success'); renderOrd(); } }
        function delOrd(id) { if (confirm('Hapus pesanan?')) { orders = orders.filter(o => o.id !== id); addLog('order', `${orders.length > 0 ? '' : 'INV-'} dihapus oleh ${currentUser.name}`); toast('Pesanan dihapus', 'info'); renderOrd(); } }

        // ===== NEW ORDER =====
        function addOrdItem() {
            const c = document.getElementById('ordItems');
            const d = document.createElement('div');
            d.className = 'oi bg-white rounded-xl p-4 border border-surface-200 fade-in';
            d.innerHTML = `<div class="grid grid-cols-1 md:grid-cols-4 gap-3"><div><label class="block text-xs text-surface-500 mb-1">Layanan</label><select class="ss w-full px-3 py-2.5 border border-surface-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" onchange="calcTot()"><option value="">Pilih</option></select></div><div><label class="block text-xs text-surface-500 mb-1">Berat (kg)</label><input type="number" class="wi w-full px-3 py-2.5 border border-surface-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" step="0.1" min="0" placeholder="0.0" oninput="calcTot()"></div><div><label class="block text-xs text-surface-500 mb-1">Jumlah</label><input type="number" class="qi w-full px-3 py-2.5 border border-surface-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" min="1" value="1" oninput="calcTot()"></div><div class="flex items-end gap-2"><div class="flex-1"><label class="block text-xs text-surface-500 mb-1">Subtotal</label><p class="px-3 py-2.5 text-sm font-semibold sd">Rp 0</p></div><button onclick="rmOrdItem(this)" class="p-2.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg"><i class="ri-delete-bin-line"></i></button></div></div>`;
            c.appendChild(d); fillSvcSel();
        }
        function rmOrdItem(btn) { const c = document.getElementById('ordItems'); if (c.children.length > 1) { btn.closest('.oi').remove(); calcTot(); } else { toast('Minimal 1 item', 'warning'); } }
        function fillSvcSel() { document.querySelectorAll('.ss').forEach(sel => { const v = sel.value; sel.innerHTML = '<option value="">Pilih Layanan</option>' + services.map(s => `<option value="${s.id}">${s.icon} ${s.name} - ${rp(s.price)}/${s.unit}</option>`).join(''); if (v) sel.value = v; }); }
        function fillCustList() { document.getElementById('custList').innerHTML = customers.map(c => `<option value="${c.name}">${c.name} - ${c.phone}</option>`).join(''); }
        function calcTot() {
            let tot = 0;
            document.querySelectorAll('.oi').forEach(item => {
                const sid = parseInt(item.querySelector('.ss').value);
                const w = parseFloat(item.querySelector('.wi').value) || 0;
                const q = parseInt(item.querySelector('.qi').value) || 1;
                const sv = services.find(s => s.id === sid);
                if (sv) {
                    const st = sv.unit === 'kg' && w > 0 ? sv.price * w * q : sv.price * q;
                    item.querySelector('.sd').textContent = rp(st); tot += st;
                } else { item.querySelector('.sd').textContent = 'Rp 0'; }
            });
            document.getElementById('ordTotDisp').textContent = rp(tot);
            const md = Math.max(...Array.from(document.querySelectorAll('.oi')).map(i => { const s = services.find(x => x.id === parseInt(i.querySelector('.ss').value)); return s ? s.duration : 0; }));
            if (md > 0) { const e = new Date(); e.setHours(e.getHours() + md); document.getElementById('ordEst').textContent = e.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }) + ' ' + e.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }); }
            else { document.getElementById('ordEst').textContent = '-'; }
        }
        function saveOrd() {
            const nm = document.getElementById('custNameIn').value.trim();
            const ph = document.getElementById('custPhoneIn').value.trim();
            const nt = document.getElementById('ordNotes').value.trim();
            if (!nm) { toast('Masukkan nama pelanggan', 'warning'); return; }
            let cu = customers.find(c => c.name.toLowerCase() === nm.toLowerCase());
            if (!cu) { cu = { id: nextCustId++, name: nm, phone: ph, address: '' }; customers.push(cu); fillCustList(); }
            else if (ph) { cu.phone = ph; }
            const items = []; let tot = 0;
            document.querySelectorAll('.oi').forEach(item => {
                const sid = parseInt(item.querySelector('.ss').value);
                const w = parseFloat(item.querySelector('.wi').value) || 0;
                const q = parseInt(item.querySelector('.qi').value) || 1;
                const sv = services.find(s => s.id === sid);
                if (sv) { const st = sv.unit === 'kg' && w > 0 ? sv.price * w * q : sv.price * q; items.push({ serviceId: sid, weight: w, qty: q, subtotal: st }); tot += st; }
            });
            if (items.length === 0) { toast('Pilih minimal 1 layanan', 'warning'); return; }
            const now = new Date();
            const ord = { id: nextInv, invoice: `INV-${String(nextInv).padStart(4, '0')}`, customerId: cu.id, items, total: tot, status: 'menunggu', payment: '', paid: false, cashPaid: 0, change: 0, cashierId: currentUser.id, date: now.toISOString().split('T')[0], time: now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }), notes: nt, dokuTX: null };
            orders.push(ord); nextInv++;
            addLog('order', `Pesanan ${ord.invoice} dibuat oleh ${currentUser.name} - ${rp(tot)}`);
            toast(`Pesanan ${ord.invoice} berhasil!`, 'success');
            resetOrd();
            openPay(ord.id);
        }
        function resetOrd() {
            document.getElementById('custNameIn').value = ''; document.getElementById('custPhoneIn').value = ''; document.getElementById('ordNotes').value = '';
            document.getElementById('ordItems').innerHTML = `<div class="oi bg-white rounded-xl p-4 border border-surface-200"><div class="grid grid-cols-1 md:grid-cols-4 gap-3"><div><label class="block text-xs text-surface-500 mb-1">Layanan</label><select class="ss w-full px-3 py-2.5 border border-surface-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" onchange="calcTot()"><option value="">Pilih</option></select></div><div><label class="block text-xs text-surface-500 mb-1">Berat (kg)</label><input type="number" class="wi w-full px-3 py-2.5 border border-surface-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" step="0.1" min="0.1" placeholder="0.0" oninput="calcTot()"></div><div><label class="block text-xs text-surface-500 mb-1">Jumlah</label><input type="number" class="qi w-full px-3 py-2.5 border border-surface-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" min="1" value="1" oninput="calcTot()"></div><div class="flex items-end gap-2"><div class="flex-1"><label class="block text-xs text-surface-500 mb-1">Subtotal</label><p class="px-3 py-2.5 text-sm font-semibold sd">Rp 0</p></div><button onclick="rmOrdItem(this)" class="p-2.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg"><i class="ri-delete-bin-line"></i></button></div></div></div>`;
            fillSvcSel(); calcTot();
        }

        // ===== DOKU QRIS PAYMENT =====
        function openPay(id) {
            curPayId = id;
            const ord = orders.find(o => o.id === id);
            if (!ord) return;
            document.getElementById('payTotal').textContent = rp(ord.total);
            document.getElementById('payInvoice').textContent = `${ord.invoice} — ${customers.find(c => c.id === ord.customerId)?.name || ''}`;
            selPay = null;
            document.querySelectorAll('.pay-btn').forEach(b => { b.classList.remove('border-doku-500', 'bg-doku-50', 'border-primary-500'); b.classList.add('border-surface-200'); });
            ['qrisBox','cashBox','trfBox','confPayBtn'].forEach(id => document.getElementById(id).classList.add('hidden'));
            ['qInit','qDone','qExp'].forEach(id => document.getElementById(id).classList.remove('hidden'));
            ['qLoad','qShow'].forEach(id => document.getElementById(id).classList.add('hidden'));
            document.getElementById('cashIn').value = ''; document.getElementById('chgAmt').textContent = 'Rp 0';
            if (qrTimer) { clearInterval(qrTimer); qrTimer = null; }
            showModal('paymentModal');
        }
        function closePayment() { closeModal('paymentModal'); curPayId = null; if (qrTimer) { clearInterval(qrTimer); qrTimer = null; } }
        function pickPay(m) {
            selPay = m;
            document.querySelectorAll('.pay-btn').forEach(b => {
                b.classList.remove('border-doku-500', 'bg-doku-50', 'border-primary-500');
                b.classList.add('border-surface-200');
                if (b.dataset.m === m) {
                    if (m === 'qris') { b.classList.add('border-doku-500', 'bg-doku-50'); }
                    else { b.classList.add('border-primary-500', 'bg-primary-50'); }
                    b.classList.remove('border-surface-200');
                }
            });
            document.getElementById('qrisBox').classList.toggle('hidden', m !== 'qris');
            document.getElementById('cashBox').classList.toggle('hidden', m !== 'tunai');
            document.getElementById('trfBox').classList.toggle('hidden', m !== 'transfer');
            document.getElementById('confPayBtn').classList.toggle('hidden', m !== 'tunai' && m !== 'transfer');
            if (m === 'transfer') {
                const ord = orders.find(o => o.id === curPayId);
                if (ord) document.getElementById('trfAmt').textContent = rp(ord.total);
            }
        }
        function genQRIS() {
            const ord = orders.find(o => o.id === curPayId);
            if (!ord) return;
            document.getElementById('qInit').classList.add('hidden');
            document.getElementById('qLoad').classList.remove('hidden');
            document.getElementById('qShow').classList.add('hidden');
            document.getElementById('qDone').classList.add('hidden');
            document.getElementById('qExp').classList.add('hidden');
            setTimeout(() => {
                const oid = `ORD-${Date.now().toString().slice(-10)}-${ord.id}`;
                const sid = `SES-${Math.random().toString(36).substring(2, 10).toUpperCase()}`;
                document.getElementById('dOrd').textContent = oid;
                document.getElementById('dSes').textContent = sid;
                const qrStr = `00020101021126610016COM.NOBUBANK.WWW01189360091400000068270210G5328791352045945530336054${ord.total.toFixed(0).padStart(3, '0')}5802ID5925LAUNDRYPOS STORE6006JAKARTA62370533${new Date().toISOString().replace(/[-:T]/g, '').substring(0, 14)}${ord.invoice}1234567890123456304`;
                QRCode.toCanvas(document.getElementById('dokuQR'), qrStr, { width: 200, margin: 1, color: { dark: '#0b1929', light: '#ffffff' }, errorCorrectionLevel: 'M' });
                document.getElementById('qLoad').classList.add('hidden');
                document.getElementById('qShow').classList.remove('hidden');
                startTimer();
                addLog('payment', `QRIS DOKU digenerate untuk ${ord.invoice}`);
            }, 2000);
        }
        function startTimer() {
            qrTime = 300;
            const te = document.getElementById('qrTimer');
            if (qrTimer) clearInterval(qrTimer);
            qrTimer = setInterval(() => {
                qrTime--;
                const m = Math.floor(qrTime / 60);
                const s = qrTime % 60;
                te.textContent = `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
                if (qrTime <= 60) { te.classList.add('text-red-600'); te.classList.remove('text-amber-700'); }
                if (qrTime <= 0) { clearInterval(qrTimer); qrTimer = null; document.getElementById('qShow').classList.add('hidden'); document.getElementById('qExp').classList.remove('hidden'); }
            }, 1000);
        }
        function simPayOK() {
            const ord = orders.find(o => o.id === curPayId);
            if (!ord) return;
            if (qrTimer) { clearInterval(qrTimer); qrTimer = null; }
            const tx = `DOKU-${Date.now()}-${Math.random().toString(36).substring(2, 8).toUpperCase()}`;
            document.getElementById('dTX').textContent = tx;
            document.getElementById('qShow').classList.add('hidden');
            document.getElementById('qDone').classList.remove('hidden');
            ord.payment = 'qris'; ord.paid = true; ord.cashierId = currentUser.id; ord.dokuTX = tx;
            addLog('payment', `QRIS DOKU berhasil ${ord.invoice} - ${rp(ord.total)} (TX: ${tx})`);
            setTimeout(() => { closePayment(); toast(`QRIS DOKU berhasil! (${ord.invoice})`, 'success'); showRcpt(ord); renderOrd(); }, 2000);
        }
        function simTrfOK() {
            const ord = orders.find(o => o.id === curPayId);
            if (!ord) return;
            ord.payment = 'transfer'; ord.paid = true; ord.cashierId = currentUser.id;
            addLog('payment', `Transfer berhasil ${ord.invoice}`);
            toast(`Transfer berhasil! (${ord.invoice})`, 'success');
            closePayment(); showRcpt(ord); renderOrd();
        }
        function setCash(a) { const ord = orders.find(o => o.id === curPayId); if (!ord) return; document.getElementById('cashIn').value = a === 0 ? ord.total : a; calcChg(); }
        function calcChg() { const ord = orders.find(o => o.id === curPayId); if (!ord) return; const p = parseFloat(document.getElementById('cashIn').value) || 0; document.getElementById('chgAmt').textContent = rp(Math.max(0, p - ord.total)); }
        function confPay() {
            if (selPay === 'tunai') {
                const ord = orders.find(o => o.id === curPayId);
                if (!ord) return;
                const p = parseFloat(document.getElementById('cashIn').value) || 0;
                if (p < ord.total) { toast('Pembayaran kurang', 'error'); return; }
                ord.payment = 'tunai'; ord.paid = true; ord.cashierId = currentUser.id; ord.cashPaid = p; ord.change = p - ord.total;
                addLog('payment', `Tunai ${ord.invoice} - ${rp(ord.total)} (Bayar: ${rp(p)})`);
                closePayment(); toast(`Tunai berhasil! (${ord.invoice})`, 'success'); showRcpt(ord); renderOrd();
            }
        }

        // ===== RECEIPT =====
        function showRcpt(ord) {
            const c = customers.find(x => x.id === ord.customerId);
            const ca = users.find(x => x.id === ord.cashierId);
            document.getElementById('rInv').textContent = ord.invoice;
            document.getElementById('rDate').textContent = ord.date + ' ' + ord.time;
            document.getElementById('rCust').textContent = c ? c.name : '-';
            document.getElementById('rCashier').textContent = ca ? ca.name : '-';
            document.getElementById('rTotal').textContent = rp(ord.total);
            document.getElementById('rPay').textContent = { qris: 'QRIS (DOKU)', tunai: 'Tunai', transfer: 'Transfer Bank', '': 'Belum Dibayar' }[ord.payment] || '-';
            document.getElementById('rChg').textContent = ord.change > 0 ? rp(ord.change) : '-';
            document.getElementById('rItems').innerHTML = ord.items.map(it => { const sv = services.find(s => s.id === it.serviceId); const qt = sv.unit === 'kg' ? `${it.weight} kg` : `${it.qty} item`; return `<div class="flex justify-between"><div><span class="font-medium">${sv ? sv.name : ''}</span><br><span class="text-xs text-surface-400">${qt} × ${rp(sv ? sv.price : 0)}</span></div><span class="font-semibold">${rp(it.subtotal)}</span></div>`; }).join('');
            if (ord.notes) document.getElementById('rItems').innerHTML += `<div class="text-xs text-surface-400 mt-2"><i class="ri-sticky-note-line"></i> ${ord.notes}</div>`;
            if (ord.dokuTX) document.getElementById('rItems').innerHTML += `<div class="text-xs text-surface-400 mt-1">DOKU TX: ${ord.dokuTX}</div>`;
            showModal('receiptModal');
        }
        function closeRcpt() { closeModal('receiptModal'); }
        function doPrint() { setTimeout(() => window.print(), 200); }

        // ===== SERVICES =====
        function renderSvc() {
            const ce = currentUser.role === 'admin';
            document.getElementById('btnAddSvc').style.display = ce ? '' : 'none';
            document.getElementById('pgSvcGrid').innerHTML = services.map(s => `<div class="bg-white rounded-2xl border border-surface-100 p-5 card-hover"><div class="flex items-start justify-between mb-3"><div class="text-3xl">${s.icon}</div>${ce ? `<div class="flex gap-1"><button onclick="editSvc(${s.id})" class="p-1.5 text-surface-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg"><i class="ri-edit-line"></i></button><button onclick="delSvc(${s.id})" class="p-1.5 text-surface-400 hover:text-red-600 hover:bg-red-50 rounded-lg"><i class="ri-delete-bin-line"></i></button></div>` : ''}</div><h4 class="font-semibold text-surface-800 mb-1">${s.name}</h4><p class="text-lg font-bold text-primary-600">${rp(s.price)}<span class="text-sm text-surface-400 font-normal">/${s.unit}</span></p><p class="text-xs text-surface-400 mt-2"><i class="ri-time-line"></i> Estimasi ${s.duration} jam</p></div>`).join('');
        }
        function showAddSvc() { document.getElementById('svcTitle').textContent = 'Tambah Layanan'; document.getElementById('svcEditId').value = ''; document.getElementById('svcName').value = ''; document.getElementById('svcPrice').value = ''; document.getElementById('svcUnit').value = 'kg'; document.getElementById('svcDur').value = '24'; document.getElementById('svcIcon').value = '👕'; showModal('svcModal'); }
        function editSvc(id) { const s = services.find(x => x.id === id); if (!s) return; document.getElementById('svcTitle').textContent = 'Edit Layanan'; document.getElementById('svcEditId').value = s.id; document.getElementById('svcName').value = s.name; document.getElementById('svcPrice').value = s.price; document.getElementById('svcUnit').value = s.unit; document.getElementById('svcDur').value = s.duration; document.getElementById('svcIcon').value = s.icon; showModal('svcModal'); }
        function closeSvc() { closeModal('svcModal'); }
        function saveSvc() {
            const nm = document.getElementById('svcName').value.trim(); const pr = parseInt(document.getElementById('svcPrice').value) || 0; const un = document.getElementById('svcUnit').value; const dr = parseInt(document.getElementById('svcDur').value) || 24; const ic = document.getElementById('svcIcon').value || '👕'; const eid = document.getElementById('svcEditId').value;
            if (!nm || pr <= 0) { toast('Lengkapi data layanan', 'warning'); return; }
            if (eid) { const s = services.find(x => x.id === parseInt(eid)); if (s) { Object.assign(s, { name: nm, price: pr, unit: un, duration: dr, icon: ic }); toast('Layanan diperbarui', 'success'); } }
            else { services.push({ id: nextSvcId++, name: nm, price: pr, unit: un, duration: dr, icon: ic }); toast('Layanan ditambahkan', 'success'); }
            addLog('user', `Layanan "${nm}" ${eid ? 'diperbarui' : 'ditambahkan'}`);
            closeSvc(); renderSvc(); fillSvcSel();
        }
        function delSvc(id) { if (confirm('Hapus layanan?')) { const s = services.find(x => x.id === id); services = services.filter(x => x.id !== id); addLog('user', `Layanan "${s?.name}" dihapus`); toast('Layanan dihapus', 'info'); renderSvc(); } }

        // ===== CUSTOMERS =====
        function renderCust() {
            const s = (document.getElementById('searchCust')?.value || '').toLowerCase();
            const f = customers.filter(c => c.name.toLowerCase().includes(s) || c.phone.includes(s));
            document.getElementById('pgCustTbody').innerHTML = f.map(c => { const co = orders.filter(o => o.customerId === c.id); return `<tr class="border-b border-surface-50 hover:bg-surface-50"><td class="py-3 px-4"><div class="flex items-center gap-3"><div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-accent-400 rounded-full flex items-center justify-center text-white font-bold text-sm">${c.name.charAt(0)}</div><div><p class="font-semibold text-surface-800">${c.name}</p><p class="text-xs text-surface-400">${c.address || '-'}</p></div></div></td><td class="py-3 px-4 text-surface-500">${c.phone}</td><td class="py-3 px-4 text-right font-semibold">${co.length}</td><td class="py-3 px-4 text-right font-semibold">${rp(co.reduce((s, o) => s + o.total, 0))}</td><td class="py-3 px-4 text-center"><div class="flex items-center justify-center gap-1"><button onclick="editCust(${c.id})" class="p-1.5 text-surface-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg"><i class="ri-edit-line"></i></button><button onclick="delCust(${c.id})" class="p-1.5 text-surface-400 hover:text-red-600 hover:bg-red-50 rounded-lg"><i class="ri-delete-bin-line"></i></button></div></td></tr>`; }).join('');
        }
        function showAddCust() { document.getElementById('custTitle').textContent = 'Tambah Pelanggan'; document.getElementById('custEditId').value = ''; document.getElementById('cName').value = ''; document.getElementById('cPhone').value = ''; document.getElementById('cAddr').value = ''; showModal('custModal'); }
        function editCust(id) { const c = customers.find(x => x.id === id); if (!c) return; document.getElementById('custTitle').textContent = 'Edit Pelanggan'; document.getElementById('custEditId').value = c.id; document.getElementById('cName').value = c.name; document.getElementById('cPhone').value = c.phone; document.getElementById('cAddr').value = c.address || ''; showModal('custModal'); }
        function closeCust() { closeModal('custModal'); }
        function saveCust() {
            const nm = document.getElementById('cName').value.trim(); const ph = document.getElementById('cPhone').value.trim(); const ad = document.getElementById('cAddr').value.trim(); const eid = document.getElementById('custEditId').value;
            if (!nm) { toast('Masukkan nama', 'warning'); return; }
            if (eid) { const c = customers.find(x => x.id === parseInt(eid)); if (c) { Object.assign(c, { name: nm, phone: ph, address: ad }); toast('Pelanggan diperbarui', 'success'); } }
            else { customers.push({ id: nextCustId++, name: nm, phone: ph, address: ad }); toast('Pelanggan ditambahkan', 'success'); }
            addLog('user', `Pelanggan "${nm}" ${eid ? 'diperbarui' : 'ditambahkan'}`);
            closeCust(); renderCust(); fillCustList();
        }
        function delCust(id) { if (orders.filter(o => o.customerId === id).length > 0) { toast('Ada pesanan aktif', 'error'); return; } if (confirm('Hapus pelanggan?')) { const c = customers.find(x => x.id === id); customers = customers.filter(x => x.id !== id); addLog('user', `Pelanggan "${c?.name}" dihapus`); toast('Pelanggan dihapus', 'info'); renderCust(); } }

        // ===== REPORTS =====
        function setDates() { const t = new Date(); const w = new Date(t); w.setDate(w.getDate() - 7); document.getElementById('rptFrom').value = w.toISOString().split('T')[0]; document.getElementById('rptTo').value = t.toISOString().split('T')[0]; }
        function renderRpt() {
            const fr = document.getElementById('rptFrom')?.value; const to = document.getElementById('rptTo')?.value;
            let f = orders.filter(o => o.paid); if (fr) f = f.filter(o => o.date >= fr); if (to) f = f.filter(o => o.date <= to);
            const ld = []; for (let i = 6; i >= 0; i--) { const d = new Date(); d.setDate(d.getDate() - i); const ds = d.toISOString().split('T')[0]; const do_ = orders.filter(o => o.date === ds && o.paid); ld.push({ day: d.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric' }), rev: do_.reduce((s, o) => s + o.total, 0), cnt: do_.length }); }
            const mx = Math.max(...ld.map(d => d.rev), 1);
            document.getElementById('pgRptRev').innerHTML = ld.map(d => `<div class="flex items-center gap-3"><span class="text-xs text-surface-500 w-16">${d.day}</span><div class="flex-1 bg-surface-100 rounded-full h-6 overflow-hidden"><div class="bg-gradient-to-r from-primary-500 to-accent-500 h-full rounded-full flex items-center px-2" style="width: ${Math.max((d.rev / mx) * 100, d.rev > 0 ? 8 : 0)}%"><span class="text-xs text-white font-semibold">${d.rev > 0 ? rps(d.rev) : ''}</span></div></div><span class="text-xs text-surface-400 w-12 text-right">${d.cnt} trx</span></div>`).join('');
            const cs = {}; orders.filter(o => o.paid).forEach(o => { if (!cs[o.cashierId]) cs[o.cashierId] = { cnt: 0, rev: 0 }; cs[o.cashierId].cnt++; cs[o.cashierId].rev += o.total; });
            const mcr = Math.max(...Object.values(cs).map(s => s.rev), 1);
            document.getElementById('pgRptCash').innerHTML = Object.entries(cs).length === 0 ? '<p class="text-sm text-surface-400">Belum ada data</p>' : Object.entries(cs).map(([uid, st]) => { const u = users.find(x => x.id === parseInt(uid)); return `<div class="flex items-center gap-3"><div class="w-8 h-8 ${rb(u?.role || '')} rounded-full flex items-center justify-center text-white text-xs font-bold">${u ? u.name.charAt(0) : '?'}</div><div class="flex-1"><div class="flex justify-between mb-1"><span class="text-sm font-medium">${u ? u.name : 'Unknown'}</span><span class="text-xs text-surface-500">${st.cnt} trx</span></div><div class="bg-surface-100 rounded-full h-2.5 overflow-hidden"><div class="bg-gradient-to-r from-primary-500 to-green-500 h-full rounded-full" style="width: ${(st.rev / mcr) * 100}%"></div></div><p class="text-xs text-surface-400 mt-1">${rp(st.rev)}</p></div></div>`; }).join('');
            f.sort((a, b) => b.id - a.id);
            const tr = f.reduce((s, o) => s + o.total, 0);
            document.getElementById('rptTotal').textContent = rp(tr);
            document.getElementById('pgRptTbody').innerHTML = f.map(o => { const c = customers.find(x => x.id === o.customerId); const ca = users.find(x => x.id === o.cashierId); const pl = { qris: '📱 QRIS (DOKU)', tunai: '💵 Tunai', transfer: '🏦 Transfer' }; return `<tr class="border-b border-surface-50 hover:bg-surface-50 cursor-pointer" onclick="viewOrd(${o.id})"><td class="py-3 px-3 text-surface-500">${o.date}</td><td class="py-3 px-3 font-medium text-primary-600">${o.invoice}</td><td class="py-3 px-3">${c ? c.name : '-'}</td><td class="py-3 px-3 text-right font-semibold">${rp(o.total)}</td><td class="py-3 px-3 text-center text-xs">${pl[o.payment] || '-'}</td><td class="py-3 px-3"><span class="text-xs px-2 py-1 rounded-full ${rc(ca?.role || '')}">${ca ? ca.name : '-'}</span></td></tr>`; }).join('');
        }

        // ===== USERS =====
        function renderUsr() {
            document.getElementById('cntAdmin').textContent = users.filter(u => u.role === 'admin').length;
            document.getElementById('cntKasir').textContent = users.filter(u => u.role === 'kasir').length;
            document.getElementById('cntStaff').textContent = users.filter(u => u.role === 'staff').length;
            document.getElementById('pgUsrTbody').innerHTML = users.map(u => {
                const uo = orders.filter(o => o.cashierId === u.id);
                const me = currentUser.id === u.id;
                return `<tr class="border-b border-surface-50 hover:bg-surface-50"><td class="py-3 px-4"><div class="flex items-center gap-3"><div class="w-10 h-10 ${rb(u.role)} rounded-full flex items-center justify-center text-white font-bold">${u.name.charAt(0)}</div><div><p class="font-semibold text-surface-800">${u.name} ${me ? '<span class="text-xs text-primary-500">(Anda)</span>' : ''}</p><p class="text-xs text-surface-400">${u.phone || '-'}</p></div></div></td><td class="py-3 px-4 font-mono text-sm">@${u.username}</td><td class="py-3 px-4 text-center"><span class="text-xs px-3 py-1 rounded-full font-semibold text-white ${u.role === 'admin' ? 'bg-red-500' : u.role === 'kasir' ? 'bg-blue-500' : 'bg-purple-500'}">${u.role.charAt(0).toUpperCase() + u.role.slice(1)}</span></td><td class="py-3 px-4 text-center"><span class="text-xs px-2 py-1 rounded-full ${u.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}">${u.status === 'active' ? 'Aktif' : 'Nonaktif'}</span></td><td class="py-3 px-4 text-sm text-surface-500">${u.lastLogin || '-'}</td><td class="py-3 px-4 text-right font-semibold">${uo.length}</td><td class="py-3 px-4 text-center"><div class="flex items-center justify-center gap-1"><button onclick="editUsr(${u.id})" class="p-1.5 text-surface-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg"><i class="ri-edit-line"></i></button>${!me ? `<button onclick="togUsr(${u.id})" class="p-1.5 text-surface-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg" title="${u.status === 'active' ? 'Nonaktifkan' : 'Aktifkan'}"><i class="ri-${u.status === 'active' ? 'user-unfollow-line' : 'user-follow-line'}"></i></button><button onclick="delUsr(${u.id})" class="p-1.5 text-surface-400 hover:text-red-600 hover:bg-red-50 rounded-lg"><i class="ri-delete-bin-line"></i></button>` : ''}</div></td></tr>`;
            }).join('');
        }
        function showAddUsr() { document.getElementById('usrTitle').textContent = 'Tambah User'; document.getElementById('usrEditId').value = ''; document.getElementById('uName').value = ''; document.getElementById('uUser').value = ''; document.getElementById('uPass').value = ''; document.getElementById('uPass').placeholder = 'Minimal 6 karakter'; document.getElementById('uRole').value = 'kasir'; document.getElementById('uPhone').value = ''; document.getElementById('uStatus').value = 'active'; showModal('userModal'); }
        function editUsr(id) { const u = users.find(x => x.id === id); if (!u) return; document.getElementById('usrTitle').textContent = 'Edit User'; document.getElementById('usrEditId').value = u.id; document.getElementById('uName').value = u.name; document.getElementById('uUser').value = u.username; document.getElementById('uPass').value = ''; document.getElementById('uPass').placeholder = 'Kosongkan jika tidak diubah'; document.getElementById('uRole').value = u.role; document.getElementById('uPhone').value = u.phone || ''; document.getElementById('uStatus').value = u.status; showModal('userModal'); }
        function closeUsr() { closeModal('userModal'); }
        function saveUsr() {
            const nm = document.getElementById('uName').value.trim(); const us = document.getElementById('uUser').value.trim().toLowerCase(); const pw = document.getElementById('uPass').value; const ro = document.getElementById('uRole').value; const ph = document.getElementById('uPhone').value.trim(); const st = document.getElementById('uStatus').value; const eid = document.getElementById('usrEditId').value;
            if (!nm || !us) { toast('Lengkapi nama dan username', 'warning'); return; }
            if (!eid && pw.length < 6) { toast('Password minimal 6 karakter', 'warning'); return; }
            if (users.find(u => u.username === us && u.id !== parseInt(eid))) { toast('Username sudah digunakan', 'error'); return; }
            if (eid) { const u = users.find(x => x.id === parseInt(eid)); if (u) { u.name = nm; u.username = us; if (pw.length >= 6) u.password = pw; u.role = ro; u.phone = ph; u.status = st; toast('User diperbarui', 'success'); } }
            else { users.push({ id: nextUserId++, name: nm, username: us, password: pw, role: ro, phone: ph, status: st, lastLogin: '' }); toast('User ditambahkan', 'success'); }
            addLog('user', `User "${nm}" (@${us}, ${ro}) ${eid ? 'diperbarui' : 'ditambahkan'}`);
            closeUsr(); renderUsr();
        }
        function togUsr(id) { const u = users.find(x => x.id === id); if (!u) return; u.status = u.status === 'active' ? 'inactive' : 'active'; addLog('user', `Status "${u.name}" → ${u.status}`); toast(`User ${u.name} ${u.status === 'active' ? 'diaktifkan' : 'dinonaktifkan'}`, 'info'); renderUsr(); }
        function delUsr(id) { if (currentUser.id === id) { toast('Tidak bisa hapus akun sendiri', 'error'); return; } if (confirm('Hapus user?')) { const u = users.find(x => x.id === id); users = users.filter(x => x.id !== id); addLog('user', `User "${u?.name}" dihapus`); toast('User dihapus', 'info'); renderUsr(); } }

        // ===== ACTIVITY =====
        function fillActUsr() { document.getElementById('filterActUsr').innerHTML = '<option value="all">Semua User</option>' + users.map(u => `<option value="${u.id}">${u.name}</option>`).join(''); }
        function renderAct() {
            const fu = document.getElementById('filterActUsr')?.value || 'all';
            const ft = document.getElementById('filterActType')?.value || 'all';
            const f = activityLog.filter(a => (fu === 'all' || a.userId === parseInt(fu)) && (ft === 'all' || a.type === ft));
            const el = document.getElementById('pgActList');
            const emp = document.getElementById('pgActEmpty');
            if (f.length === 0) { el.innerHTML = ''; emp.classList.remove('hidden'); return; }
            emp.classList.add('hidden');
            const ti = { login: { i: 'ri-login-circle-line', c: 'bg-green-100 text-green-600' }, logout: { i: 'ri-logout-box-line', c: 'bg-red-100 text-red-600' }, order: { i: 'ri-file-list-3-line', c: 'bg-blue-100 text-blue-600' }, payment: { i: 'ri-money-dollar-circle-line', c: 'bg-green-100 text-green-600' }, user: { i: 'ri-user-settings-line', c: 'bg-purple-100 text-purple-600' } };
            el.innerHTML = f.map(a => { const t = ti[a.type] || { i: 'ri-information-line', c: 'bg-surface-100 text-surface-600' }; const u = users.find(x => x.id === a.userId); return `<div class="p-4 hover:bg-surface-50 flex items-start gap-4"><div class="w-10 h-10 ${t.c} rounded-xl flex items-center justify-center flex-shrink-0"><i class="${t.i} text-xl"></i></div><div class="flex-1 min-w-0"><p class="text-sm text-surface-700">${a.desc}</p><div class="flex items-center gap-2 mt-1"><span class="text-xs px-2 py-0.5 rounded-full ${rc(a.userRole || '')}">${a.userRole || 'system'}</span><span class="text-xs text-surface-400">${a.date} ${a.time}</span></div></div><span class="text-xs text-surface-400 flex-shrink-0">${u ? u.name : 'System'}</span></div>`; }).join('');
        }

        // ===== PROFILE =====
        function showProf() {
            if (!currentUser) return;
            const u = users.find(x => x.id === currentUser.id);
            if (u) Object.assign(currentUser, u);
            document.getElementById('profAv').textContent = currentUser.name.charAt(0).toUpperCase();
            document.getElementById('profName').textContent = currentUser.name;
            document.getElementById('profUser').textContent = `@${currentUser.username}`;
            document.getElementById('profRole').textContent = currentUser.role.charAt(0).toUpperCase() + currentUser.role.slice(1);
            document.getElementById('profRole').className = `inline-block mt-2 text-xs text-white font-semibold px-3 py-1 rounded-full ${currentUser.role === 'admin' ? 'bg-red-500' : currentUser.role === 'kasir' ? 'bg-blue-500' : 'bg-purple-500'}`;
            document.getElementById('profLogin').textContent = currentUser.lastLogin || '-';
            const uo = orders.filter(o => o.cashierId === currentUser.id);
            document.getElementById('profOrders').textContent = uo.length;
            document.getElementById('profRev').textContent = rp(uo.reduce((s, o) => s + o.total, 0));
            showModal('profModal');
        }
        function closeProf() { closeModal('profModal'); }
        function showChgPwd() { closeProf(); document.getElementById('oldPwd').value = ''; document.getElementById('newPwd').value = ''; document.getElementById('cfmPwd').value = ''; showModal('chgPwdModal'); }
        function closeChgPwd() { closeModal('chgPwdModal'); }
        function doChgPwd() {
            const op = document.getElementById('oldPwd').value;
            const np = document.getElementById('newPwd').value;
            const cp = document.getElementById('cfmPwd').value;
            if (op !== currentUser.password) { toast('Password lama salah', 'error'); return; }
            if (np.length < 6) { toast('Minimal 6 karakter', 'warning'); return; }
            if (np !== cp) { toast('Konfirmasi tidak cocok', 'error'); return; }
            const u = users.find(x => x.id === currentUser.id);
            if (u) { u.password = np; currentUser.password = np; addLog('user', `${currentUser.name} ganti password`); toast('Password diubah', 'success'); closeChgPwd(); }
        }

        // ===== MODAL HELPERS =====
        function showModal(id) { const el = document.getElementById(id); el.classList.remove('hidden'); el.classList.add('flex'); }
        function closeModal(id) { const el = document.getElementById(id); el.classList.add('hidden'); el.classList.remove('flex'); }

        // ===== PAGE TEMPLATES =====
        function renderPageContent() {
            // Dashboard
            document.getElementById('page-dashboard').innerHTML = `<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6"><div class="bg-white rounded-2xl p-5 border border-surface-100 card-hover"><div class="flex items-center justify-between mb-3"><span class="text-sm text-surface-500">Pesanan Hari Ini</span><div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center"><i class="ri-shopping-bag-3-line text-blue-600 text-xl"></i></div></div><p class="text-3xl font-bold text-surface-800" id="statTodayOrders">0</p><p class="text-xs text-surface-400 mt-1">Total semua user</p></div><div class="bg-white rounded-2xl p-5 border border-surface-100 card-hover"><div class="flex items-center justify-between mb-3"><span class="text-sm text-surface-500">Pendapatan Hari Ini</span><div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center"><i class="ri-money-dollar-circle-line text-green-600 text-xl"></i></div></div><p class="text-3xl font-bold text-surface-800" id="statTodayRevenue">Rp 0</p><p class="text-xs text-surface-400 mt-1">Semua pembayaran</p></div><div class="bg-white rounded-2xl p-5 border border-surface-100 card-hover"><div class="flex items-center justify-between mb-3"><span class="text-sm text-surface-500">Dalam Proses</span><div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center"><i class="ri-loader-4-line text-amber-600 text-xl"></i></div></div><p class="text-3xl font-bold text-surface-800" id="statProcessing">0</p><p class="text-xs text-surface-400 mt-1">Sedang dikerjakan</p></div><div class="bg-white rounded-2xl p-5 border border-surface-100 card-hover"><div class="flex items-center justify-between mb-3"><span class="text-sm text-surface-500">QRIS Hari Ini</span><div class="w-10 h-10 bg-doku-100 rounded-xl flex items-center justify-center"><i class="ri-qr-scan-2-line text-doku-600 text-xl"></i></div></div><p class="text-3xl font-bold text-surface-800" id="statQrisCount">0</p><p class="text-xs text-doku-600 mt-1">via DOKU QRIS</p></div></div><div class="grid grid-cols-1 lg:grid-cols-3 gap-6"><div class="lg:col-span-2 bg-white rounded-2xl border border-surface-100 p-5"><h3 class="font-bold text-surface-800 mb-4">Pesanan Terbaru</h3><div class="overflow-x-auto"><table class="w-full text-sm"><thead><tr class="border-b border-surface-100"><th class="text-left py-3 px-3 text-surface-500 font-medium">Invoice</th><th class="text-left py-3 px-3 text-surface-500 font-medium">Pelanggan</th><th class="text-left py-3 px-3 text-surface-500 font-medium">Total</th><th class="text-left py-3 px-3 text-surface-500 font-medium">Status</th><th class="text-left py-3 px-3 text-surface-500 font-medium">Kasir</th></tr></thead><tbody id="pgDashRecent"></tbody></table></div></div><div class="bg-white rounded-2xl border border-surface-100 p-5"><h3 class="font-bold text-surface-800 mb-4">Status Pesanan</h3><div id="pgDashStatus" class="space-y-3"></div><div class="mt-4 p-4 bg-surface-50 rounded-xl"><p class="text-xs text-surface-500 mb-1">User Terakhir Login</p><div id="pgDashLogin" class="space-y-2 mt-2"></div></div></div></div>`;
            // Orders
            document.getElementById('page-orders').innerHTML = `<div class="bg-white rounded-2xl border border-surface-100 p-5"><div class="flex flex-wrap items-center justify-between gap-4 mb-5"><div class="flex items-center gap-2"><div class="relative"><i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-surface-400"></i><input type="text" id="searchOrder" oninput="renderOrd()" placeholder="Cari invoice/nama..." class="pl-10 pr-4 py-2.5 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 w-64"></div><select id="filterStatus" onchange="renderOrd()" class="px-4 py-2.5 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"><option value="all">Semua Status</option><option value="menunggu">Menunggu</option><option value="diproses">Diproses</option><option value="selesai">Selesai</option><option value="diambil">Diambil</option></select><select id="filterUser" onchange="renderOrd()" class="px-4 py-2.5 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"><option value="all">Semua User</option></select></div><span class="text-sm text-surface-500" id="pgOrdCount">0 pesanan</span></div><div class="overflow-x-auto"><table class="w-full text-sm"><thead><tr class="border-b border-surface-100 bg-surface-50"><th class="text-left py-3 px-3 text-surface-500 font-medium">Invoice</th><th class="text-left py-3 px-3 text-surface-500 font-medium">Tanggal</th><th class="text-left py-3 px-3 text-surface-500 font-medium">Pelanggan</th><th class="text-left py-3 px-3 text-surface-500 font-medium">Layanan</th><th class="text-right py-3 px-3 text-surface-500 font-medium">Total</th><th class="text-center py-3 px-3 text-surface-500 font-medium">Status</th><th class="text-center py-3 px-3 text-surface-500 font-medium">Bayar</th><th class="text-left py-3 px-3 text-surface-500 font-medium">Kasir</th><th class="text-center py-3 px-3 text-surface-500 font-medium">Aksi</th></tr></thead><tbody id="pgOrdTbody"></tbody></table></div><div id="pgOrdEmpty" class="hidden py-12 text-center"><i class="ri-inbox-line text-5xl text-surface-300"></i><p class="text-surface-400 mt-3">Belum ada pesanan</p></div></div>`;
            // New Order
            document.getElementById('page-neworder').innerHTML = `<div class="max-w-4xl mx-auto"><div class="bg-white rounded-2xl border border-surface-100 p-6"><h3 class="text-lg font-bold mb-5">🧺 Buat Pesanan Baru</h3><div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6"><div><label class="block text-sm font-medium text-surface-600 mb-2">Nama Pelanggan</label><div class="relative"><input type="text" id="custNameIn" list="custList" placeholder="Ketik nama..." class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"><datalist id="custList"></datalist></div></div><div><label class="block text-sm font-medium text-surface-600 mb-2">No. Telepon</label><input type="tel" id="custPhoneIn" placeholder="08xxxxxxxxxx" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"></div></div><div class="bg-surface-50 rounded-xl p-5 mb-6"><h4 class="font-semibold text-sm text-surface-700 mb-4">Item Laundry</h4><div id="ordItems" class="space-y-3"><div class="oi bg-white rounded-xl p-4 border border-surface-200"><div class="grid grid-cols-1 md:grid-cols-4 gap-3"><div><label class="block text-xs text-surface-500 mb-1">Layanan</label><select class="ss w-full px-3 py-2.5 border border-surface-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" onchange="calcTot()"><option value="">Pilih</option></select></div><div><label class="block text-xs text-surface-500 mb-1">Berat (kg)</label><input type="number" class="wi w-full px-3 py-2.5 border border-surface-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" step="0.1" min="0.1" placeholder="0.0" oninput="calcTot()"></div><div><label class="block text-xs text-surface-500 mb-1">Jumlah</label><input type="number" class="qi w-full px-3 py-2.5 border border-surface-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" min="1" value="1" oninput="calcTot()"></div><div class="flex items-end gap-2"><div class="flex-1"><label class="block text-xs text-surface-500 mb-1">Subtotal</label><p class="px-3 py-2.5 text-sm font-semibold sd">Rp 0</p></div><button onclick="rmOrdItem(this)" class="p-2.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg"><i class="ri-delete-bin-line"></i></button></div></div></div></div><button onclick="addOrdItem()" class="mt-3 text-sm text-primary-600 hover:text-primary-700 font-medium flex items-center gap-1"><i class="ri-add-line"></i> Tambah Item</button></div><div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6"><div><label class="block text-sm font-medium text-surface-600 mb-2">Catatan</label><textarea id="ordNotes" rows="2" placeholder="Contoh: Pisahkan warna..." class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"></textarea></div><div class="bg-gradient-to-r from-doku-50 to-accent-50 rounded-xl p-5"><div class="flex justify-between mb-2"><span class="text-sm text-surface-500">Total</span><span class="text-lg font-bold" id="ordTotDisp">Rp 0</span></div><div class="flex justify-between mb-2"><span class="text-xs text-surface-400">Estimasi Selesai</span><span class="text-sm font-semibold" id="ordEst">-</span></div><div class="flex justify-between"><span class="text-xs text-surface-400">Kasir</span><span class="text-sm font-semibold" id="ordCashier">-</span></div></div></div><div class="flex flex-wrap gap-3 justify-end"><button onclick="resetOrd()" class="px-6 py-3 border border-surface-200 rounded-xl text-sm font-semibold text-surface-600 hover:bg-surface-50">Reset</button><button onclick="saveOrd()" class="px-6 py-3 bg-gradient-to-r from-doku-500 to-accent-500 text-white rounded-xl text-sm font-semibold hover:shadow-lg transition-all flex items-center gap-2"><i class="ri-save-line"></i> Simpan Pesanan</button></div></div></div>`;
            // Services
            document.getElementById('page-services').innerHTML = `<div class="flex justify-between items-center mb-5"><p class="text-sm text-surface-500">Kelola layanan dan harga</p><button id="btnAddSvc" onclick="showAddSvc()" class="px-4 py-2.5 bg-primary-500 text-white rounded-xl text-sm font-semibold hover:bg-primary-600 transition-all flex items-center gap-2"><i class="ri-add-line"></i> Tambah Layanan</button></div><div id="pgSvcGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"></div>`;
            // Customers
            document.getElementById('page-customers').innerHTML = `<div class="flex justify-between items-center mb-5"><div class="relative"><i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-surface-400"></i><input type="text" id="searchCust" oninput="renderCust()" placeholder="Cari pelanggan..." class="pl-10 pr-4 py-2.5 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 w-64"></div><button onclick="showAddCust()" class="px-4 py-2.5 bg-primary-500 text-white rounded-xl text-sm font-semibold hover:bg-primary-600 transition-all flex items-center gap-2"><i class="ri-add-line"></i> Tambah Pelanggan</button></div><div class="bg-white rounded-2xl border border-surface-100 overflow-hidden"><div class="overflow-x-auto"><table class="w-full text-sm"><thead><tr class="border-b border-surface-100 bg-surface-50"><th class="text-left py-3 px-4 text-surface-500 font-medium">Nama</th><th class="text-left py-3 px-4 text-surface-500 font-medium">Telepon</th><th class="text-right py-3 px-4 text-surface-500 font-medium">Total Pesanan</th><th class="text-right py-3 px-4 text-surface-500 font-medium">Total Belanja</th><th class="text-center py-3 px-4 text-surface-500 font-medium">Aksi</th></tr></thead><tbody id="pgCustTbody"></tbody></table></div></div>`;
            // Reports
            document.getElementById('page-reports').innerHTML = `<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6"><div class="bg-white rounded-2xl border border-surface-100 p-5"><h3 class="font-bold mb-4">Pendapatan 7 Hari</h3><div id="pgRptRev" class="space-y-3"></div></div><div class="bg-white rounded-2xl border border-surface-100 p-5"><h3 class="font-bold mb-4">Performa Kasir</h3><div id="pgRptCash" class="space-y-3"></div></div></div><div class="bg-white rounded-2xl border border-surface-100 p-5"><div class="flex flex-wrap items-center justify-between gap-4 mb-5"><h3 class="font-bold">Riwayat Transaksi</h3><div class="flex items-center gap-2"><input type="date" id="rptFrom" onchange="renderRpt()" class="px-3 py-2 border border-surface-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"><span class="text-surface-400">s/d</span><input type="date" id="rptTo" onchange="renderRpt()" class="px-3 py-2 border border-surface-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"></div></div><div class="overflow-x-auto"><table class="w-full text-sm"><thead><tr class="border-b border-surface-100 bg-surface-50"><th class="text-left py-3 px-3 text-surface-500 font-medium">Tanggal</th><th class="text-left py-3 px-3 text-surface-500 font-medium">Invoice</th><th class="text-left py-3 px-3 text-surface-500 font-medium">Pelanggan</th><th class="text-right py-3 px-3 text-surface-500 font-medium">Total</th><th class="text-center py-3 px-3 text-surface-500 font-medium">Pembayaran</th><th class="text-left py-3 px-3 text-surface-500 font-medium">Kasir</th></tr></thead><tbody id="pgRptTbody"></tbody></table></div><div class="mt-4 flex justify-between items-center p-4 bg-surface-50 rounded-xl"><span class="text-sm text-surface-500">Total Pendapatan</span><span class="text-xl font-bold text-primary-600" id="rptTotal">Rp 0</span></div></div>`;
            // Users
            document.getElementById('page-users').innerHTML = `<div class="flex justify-between items-center mb-5"><p class="text-sm text-surface-500">Kelola pengguna dan hak akses</p><button onclick="showAddUsr()" class="px-4 py-2.5 bg-primary-500 text-white rounded-xl text-sm font-semibold hover:bg-primary-600 transition-all flex items-center gap-2"><i class="ri-add-line"></i> Tambah User</button></div><div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6"><div class="bg-gradient-to-r from-red-50 to-red-50 rounded-2xl p-4 border border-red-100"><p class="text-sm text-red-600 font-semibold mb-1">Admin</p><p class="text-xs text-red-500">Akses penuh</p><p class="text-2xl font-bold text-red-700 mt-2" id="cntAdmin">0</p></div><div class="bg-gradient-to-r from-blue-50 to-blue-50 rounded-2xl p-4 border border-blue-100"><p class="text-sm text-blue-600 font-semibold mb-1">Kasir</p><p class="text-xs text-blue-500">Pesanan & pembayaran</p><p class="text-2xl font-bold text-blue-700 mt-2" id="cntKasir">0</p></div><div class="bg-gradient-to-r from-purple-50 to-purple-50 rounded-2xl p-4 border border-purple-100"><p class="text-sm text-purple-600 font-semibold mb-1">Staff</p><p class="text-xs text-purple-500">Update status pesanan</p><p class="text-2xl font-bold text-purple-700 mt-2" id="cntStaff">0</p></div></div><div class="bg-white rounded-2xl border border-surface-100 overflow-hidden"><div class="overflow-x-auto"><table class="w-full text-sm"><thead><tr class="border-b border-surface-100 bg-surface-50"><th class="text-left py-3 px-4 text-surface-500 font-medium">User</th><th class="text-left py-3 px-4 text-surface-500 font-medium">Username</th><th class="text-center py-3 px-4 text-surface-500 font-medium">Role</th><th class="text-center py-3 px-4 text-surface-500 font-medium">Status</th><th class="text-left py-3 px-4 text-surface-500 font-medium">Login Terakhir</th><th class="text-center py-3 px-4 text-surface-500 font-medium">Total Trx</th><th class="text-center py-3 px-4 text-surface-500 font-medium">Aksi</th></tr></thead><tbody id="pgUsrTbody"></tbody></table></div></div>`;
            // Activity
            document.getElementById('page-activity').innerHTML = `<div class="flex justify-between items-center mb-5"><p class="text-sm text-surface-500">Riwayat aktivitas semua pengguna</p><div class="flex items-center gap-2"><select id="filterActUsr" onchange="renderAct()" class="px-4 py-2.5 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"><option value="all">Semua User</option></select><select id="filterActType" onchange="renderAct()" class="px-4 py-2.5 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"><option value="all">Semua Aksi</option><option value="login">Login</option><option value="logout">Logout</option><option value="order">Pesanan</option><option value="payment">Pembayaran</option><option value="user">Manajemen User</option></select></div></div><div class="bg-white rounded-2xl border border-surface-100 overflow-hidden"><div id="pgActList" class="divide-y divide-surface-100"></div><div id="pgActEmpty" class="hidden py-12 text-center"><i class="ri-history-line text-5xl text-surface-300"></i><p class="text-surface-400 mt-3">Belum ada aktivitas</p></div></div>`;
        }

        // ===== INIT =====
        renderPageContent();

        // Auto-fill customer
        document.addEventListener('input', (e) => {
            if (e.target.id === 'custNameIn') {
                const c = customers.find(x => x.name.toLowerCase() === e.target.value.toLowerCase());
                if (c) document.getElementById('custPhoneIn').value = c.phone;
            }
        });
    </script>
</body>
</html>
