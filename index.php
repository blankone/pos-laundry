<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaundryPOS - Multi User</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 50:'#eff6ff',100:'#dbeafe',200:'#bfdbfe',300:'#93c5fd',400:'#60a5fa',500:'#3b82f6',600:'#2563eb',700:'#1d4ed8',800:'#1e40af',900:'#1e3a8a' },
                        accent: { 50:'#fdf4ff',100:'#fae8ff',200:'#f5d0fe',300:'#f0abfc',400:'#e879f9',500:'#d946ef',600:'#c026d3',700:'#a21caf',800:'#86198f',900:'#701a75' },
                        surface: { 50:'#f8fafc',100:'#f1f5f9',200:'#e2e8f0',300:'#cbd5e1',400:'#94a3b8',500:'#64748b',600:'#475569',700:'#334155',800:'#1e293b',900:'#0f172a' }
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
        .qr-scanner { animation: qrPulse 2s ease-in-out infinite; }
        @keyframes qrPulse { 0%, 100% { box-shadow: 0 0 0 0 rgba(59,130,246,0.4); } 50% { box-shadow: 0 0 0 10px rgba(59,130,246,0); } }
        .receipt-line { border-top: 2px dashed #cbd5e1; }
        .login-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #701a75 50%, #1e40af 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .login-card { animation: loginIn 0.5s ease-out; }
        @keyframes loginIn { from { opacity: 0; transform: translateY(30px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }
        .role-badge-admin { background: linear-gradient(135deg, #dc2626, #ef4444); }
        .role-badge-kasir { background: linear-gradient(135deg, #2563eb, #3b82f6); }
        .role-badge-staff { background: linear-gradient(135deg, #7c3aed, #8b5cf6); }

        /* ==================== PRINT STYLES ==================== */
        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            body { background: white !important; }

            /* Hide everything by default */
            #loginPage,
            #appContainer,
            #sidebar,
            header,
            .no-print,
            #toastContainer,
            #paymentModal,
            #profileModal,
            #changePasswordModal,
            #serviceModal,
            #customerModal,
            #userModal,
            #qrisDetailModal {
                display: none !important;
            }

            /* Show ONLY the receipt modal content */
            #receiptModal {
                display: block !important;
                position: static !important;
                background: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            #receiptModal > .modal-content {
                display: block !important;
                position: static !important;
                transform: none !important;
                animation: none !important;
                max-width: 100% !important;
                margin: 0 auto !important;
                padding: 20px !important;
                box-shadow: none !important;
                border: 1px solid #e2e8f0 !important;
                border-radius: 12px !important;
                width: 320px !important;
                max-height: none !important;
                overflow: visible !important;
                background: white !important;
            }
            #receiptModal > .modal-content * {
                display: block !important;
                opacity: 1 !important;
                visibility: visible !important;
            }
            #receiptModal > .modal-content .flex {
                display: flex !important;
            }
            #receiptModal > .modal-content .text-center {
                text-align: center !important;
            }
            #receiptModal > .modal-content .space-y-2,
            #receiptModal > .modal-content .space-y-1 {
                display: block !important;
            }
            #receiptContent {
                padding: 0 !important;
                display: block !important;
            }
        }
    </style>
</head>
<body class="bg-surface-50 font-sans text-surface-800">
    <!-- LOGIN PAGE -->
    <div id="loginPage" class="login-bg min-h-screen flex items-center justify-center p-4">
        <div class="login-card w-full max-w-md">
            <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 shadow-2xl">
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-accent-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl">
                        <i class="ri-shirt-line text-white text-3xl"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-white">LaundryPOS</h1>
                    <p class="text-white/60 mt-1">Sistem Kasir Laundry Multi User</p>
                </div>
                <form onsubmit="handleLogin(event)" class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-white/80 mb-2">Username</label>
                        <div class="relative">
                            <i class="ri-user-line absolute left-4 top-1/2 -translate-y-1/2 text-white/40"></i>
                            <input type="text" id="loginUsername" required class="w-full pl-12 pr-4 py-3.5 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent" placeholder="Masukkan username">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-white/80 mb-2">Password</label>
                        <div class="relative">
                            <i class="ri-lock-line absolute left-4 top-1/2 -translate-y-1/2 text-white/40"></i>
                            <input type="password" id="loginPassword" required class="w-full pl-12 pr-12 py-3.5 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent" placeholder="Masukkan password">
                            <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-white/40 hover:text-white/70 transition-all">
                                <i id="eyeIcon" class="ri-eye-line text-xl"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="w-full py-3.5 bg-white text-surface-800 rounded-xl font-bold text-lg hover:bg-white/90 transition-all shadow-lg flex items-center justify-center gap-2">
                        <i class="ri-login-circle-line"></i> Masuk
                    </button>
                </form>
                <div class="mt-6 p-4 bg-white/5 rounded-xl border border-white/10">
                    <p class="text-xs text-white/60 font-semibold mb-2">📋 Akun Demo:</p>
                    <div class="space-y-1 text-xs text-white/70">
                        <p><span class="text-white font-semibold">admin</span> / admin123 — <span class="text-red-300">Admin</span></p>
                        <p><span class="text-white font-semibold">kasir1</span> / kasir123 — <span class="text-blue-300">Kasir</span></p>
                        <p><span class="text-white font-semibold">staff1</span> / staff123 — <span class="text-purple-300">Staff</span></p>
                    </div>
                </div>
            </div>
        </div>
        <div id="loginError" class="fixed top-4 left-1/2 -translate-x-1/2 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg hidden slide-up">
            <i class="ri-error-warning-line mr-2"></i> <span id="loginErrorText">Username atau password salah</span>
        </div>
    </div>

    <!-- MAIN APP -->
    <div id="appContainer" class="hidden">
        <div class="flex h-screen overflow-hidden">
            <!-- Sidebar -->
            <aside id="sidebar" class="w-64 bg-white border-r border-surface-200 flex flex-col no-print transition-all duration-300">
                <div class="p-5 border-b border-surface-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-accent-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="ri-shirt-line text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold bg-gradient-to-r from-primary-600 to-accent-600 bg-clip-text text-transparent">LaundryPOS</h1>
                            <p class="text-xs text-surface-400">Multi User System</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 border-b border-surface-100 bg-surface-50">
                    <div class="flex items-center gap-3">
                        <div id="userAvatar" class="w-10 h-10 bg-gradient-to-br from-primary-400 to-accent-400 rounded-full flex items-center justify-center text-white font-bold text-sm">A</div>
                        <div class="flex-1 min-w-0">
                            <p id="userName" class="text-sm font-semibold text-surface-800 truncate">Admin</p>
                            <p id="userRole" class="text-xs text-primary-600 font-medium">Admin</p>
                        </div>
                    </div>
                </div>
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
                <div class="p-4 border-t border-surface-100">
                    <button onclick="handleLogout()" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-red-500 hover:bg-red-50 transition-all"><i class="ri-logout-box-r-line text-lg"></i> Keluar</button>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 flex flex-col overflow-hidden">
                <header class="bg-white border-b border-surface-200 px-6 py-4 flex items-center justify-between no-print">
                    <div class="flex items-center gap-4">
                        <button onclick="toggleSidebar()" class="p-2 rounded-lg hover:bg-surface-100 lg:hidden"><i class="ri-menu-line text-xl"></i></button>
                        <div>
                            <h2 id="pageTitle" class="text-xl font-bold text-surface-800">Dashboard</h2>
                            <p id="pageSubtitle" class="text-sm text-surface-400">Ringkasan aktivitas laundry Anda</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-semibold text-surface-700" id="currentDate"></p>
                            <p class="text-xs text-surface-400" id="currentTime"></p>
                        </div>
                        <button onclick="navigate('neworder')" class="bg-gradient-to-r from-primary-500 to-accent-500 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:shadow-lg hover:shadow-primary-500/25 transition-all flex items-center gap-2"><i class="ri-add-line"></i> Pesanan Baru</button>
                    </div>
                </header>

                <div id="contentArea" class="flex-1 overflow-y-auto p-6">
                    <!-- Dashboard -->
                    <div id="page-dashboard" class="page-content fade-in">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                            <div class="bg-white rounded-2xl p-5 border border-surface-100 card-hover"><div class="flex items-center justify-between mb-3"><span class="text-sm text-surface-500">Pesanan Hari Ini</span><div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center"><i class="ri-shopping-bag-3-line text-blue-600 text-xl"></i></div></div><p class="text-3xl font-bold text-surface-800" id="statTodayOrders">0</p><p class="text-xs text-surface-400 mt-1">Total semua user</p></div>
                            <div class="bg-white rounded-2xl p-5 border border-surface-100 card-hover"><div class="flex items-center justify-between mb-3"><span class="text-sm text-surface-500">Pendapatan Hari Ini</span><div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center"><i class="ri-money-dollar-circle-line text-green-600 text-xl"></i></div></div><p class="text-3xl font-bold text-surface-800" id="statTodayRevenue">Rp 0</p><p class="text-xs text-surface-400 mt-1">Semua pembayaran</p></div>
                            <div class="bg-white rounded-2xl p-5 border border-surface-100 card-hover"><div class="flex items-center justify-between mb-3"><span class="text-sm text-surface-500">Dalam Proses</span><div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center"><i class="ri-loader-4-line text-amber-600 text-xl"></i></div></div><p class="text-3xl font-bold text-surface-800" id="statProcessing">0</p><p class="text-xs text-surface-400 mt-1">Sedang dikerjakan</p></div>
                            <div class="bg-white rounded-2xl p-5 border border-surface-100 card-hover"><div class="flex items-center justify-between mb-3"><span class="text-sm text-surface-500">User Aktif</span><div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center"><i class="ri-user-smile-line text-purple-600 text-xl"></i></div></div><p class="text-3xl font-bold text-surface-800" id="statActiveUsers">0</p><p class="text-xs text-surface-400 mt-1">Total pengguna</p></div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <div class="lg:col-span-2 bg-white rounded-2xl border border-surface-100 p-5"><h3 class="font-bold text-surface-800 mb-4">Pesanan Terbaru</h3><div class="overflow-x-auto"><table class="w-full text-sm"><thead><tr class="border-b border-surface-100"><th class="text-left py-3 px-3 text-surface-500 font-medium">Invoice</th><th class="text-left py-3 px-3 text-surface-500 font-medium">Pelanggan</th><th class="text-left py-3 px-3 text-surface-500 font-medium">Total</th><th class="text-left py-3 px-3 text-surface-500 font-medium">Status</th><th class="text-left py-3 px-3 text-surface-500 font-medium">Kasir</th></tr></thead><tbody id="dashboardRecentOrders"></tbody></table></div></div>
                            <div class="bg-white rounded-2xl border border-surface-100 p-5"><h3 class="font-bold text-surface-800 mb-4">Status Pesanan</h3><div id="statusChart" class="space-y-3"></div><div class="mt-4 p-4 bg-surface-50 rounded-xl"><p class="text-xs text-surface-500 mb-1">User Terakhir Login</p><div id="recentLogins" class="space-y-2 mt-2"></div></div></div>
                        </div>
                    </div>

                    <!-- Orders Page -->
                    <div id="page-orders" class="page-content hidden fade-in">
                        <div class="bg-white rounded-2xl border border-surface-100 p-5">
                            <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
                                <div class="flex items-center gap-2">
                                    <div class="relative"><i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-surface-400"></i><input type="text" id="searchOrder" oninput="renderOrders()" placeholder="Cari invoice/nama..." class="pl-10 pr-4 py-2.5 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent w-64"></div>
                                    <select id="filterStatus" onchange="renderOrders()" class="px-4 py-2.5 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"><option value="all">Semua Status</option><option value="menunggu">Menunggu</option><option value="diproses">Diproses</option><option value="selesai">Selesai</option><option value="diambil">Diambil</option></select>
                                    <select id="filterUser" onchange="renderOrders()" class="px-4 py-2.5 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"><option value="all">Semua User</option></select>
                                </div>
                                <span class="text-sm text-surface-500" id="orderCount">0 pesanan</span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm"><thead><tr class="border-b border-surface-100 bg-surface-50"><th class="text-left py-3 px-3 text-surface-500 font-medium">Invoice</th><th class="text-left py-3 px-3 text-surface-500 font-medium">Tanggal</th><th class="text-left py-3 px-3 text-surface-500 font-medium">Pelanggan</th><th class="text-left py-3 px-3 text-surface-500 font-medium">Layanan</th><th class="text-right py-3 px-3 text-surface-500 font-medium">Total</th><th class="text-center py-3 px-3 text-surface-500 font-medium">Status</th><th class="text-center py-3 px-3 text-surface-500 font-medium">Bayar</th><th class="text-left py-3 px-3 text-surface-500 font-medium">Kasir</th><th class="text-center py-3 px-3 text-surface-500 font-medium">Aksi</th></tr></thead><tbody id="ordersTableBody"></tbody></table>
                            </div>
                            <div id="emptyOrders" class="hidden py-12 text-center"><i class="ri-inbox-line text-5xl text-surface-300"></i><p class="text-surface-400 mt-3">Belum ada pesanan</p></div>
                        </div>
                    </div>

                    <!-- New Order Page -->
                    <div id="page-neworder" class="page-content hidden fade-in">
                        <div class="max-w-4xl mx-auto">
                            <div class="bg-white rounded-2xl border border-surface-100 p-6">
                                <h3 class="text-lg font-bold mb-5">🧺 Buat Pesanan Baru</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                                    <div><label class="block text-sm font-medium text-surface-600 mb-2">Nama Pelanggan</label><div class="relative"><input type="text" id="customerName" list="customerList" placeholder="Ketik nama pelanggan..." class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"><datalist id="customerList"></datalist></div></div>
                                    <div><label class="block text-sm font-medium text-surface-600 mb-2">No. Telepon</label><input type="tel" id="customerPhone" placeholder="08xxxxxxxxxx" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"></div>
                                </div>
                                <div class="bg-surface-50 rounded-xl p-5 mb-6">
                                    <h4 class="font-semibold text-sm text-surface-700 mb-4">Item Laundry</h4>
                                    <div id="orderItems" class="space-y-3">
                                        <div class="order-item bg-white rounded-xl p-4 border border-surface-200">
                                            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                                <div><label class="block text-xs text-surface-500 mb-1">Layanan</label><select class="service-select w-full px-3 py-2.5 border border-surface-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" onchange="calcOrderTotal()"><option value="">Pilih Layanan</option></select></div>
                                                <div><label class="block text-xs text-surface-500 mb-1">Berat (kg)</label><input type="number" class="weight-input w-full px-3 py-2.5 border border-surface-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" step="0.1" min="0.1" placeholder="0.0" oninput="calcOrderTotal()"></div>
                                                <div><label class="block text-xs text-surface-500 mb-1">Jumlah</label><input type="number" class="qty-input w-full px-3 py-2.5 border border-surface-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" min="1" value="1" oninput="calcOrderTotal()"></div>
                                                <div class="flex items-end gap-2"><div class="flex-1"><label class="block text-xs text-surface-500 mb-1">Subtotal</label><p class="px-3 py-2.5 text-sm font-semibold subtotal-display">Rp 0</p></div><button onclick="removeOrderItem(this)" class="p-2.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"><i class="ri-delete-bin-line"></i></button></div>
                                            </div>
                                        </div>
                                    </div>
                                    <button onclick="addOrderItem()" class="mt-3 text-sm text-primary-600 hover:text-primary-700 font-medium flex items-center gap-1"><i class="ri-add-line"></i> Tambah Item</button>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                                    <div><label class="block text-sm font-medium text-surface-600 mb-2">Catatan</label><textarea id="orderNotes" rows="2" placeholder="Contoh: Pisahkan warna..." class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none"></textarea></div>
                                    <div class="bg-gradient-to-r from-primary-50 to-accent-50 rounded-xl p-5">
                                        <div class="flex justify-between mb-2"><span class="text-sm text-surface-500">Total</span><span class="text-lg font-bold" id="orderTotalDisplay">Rp 0</span></div>
                                        <div class="flex justify-between mb-2"><span class="text-xs text-surface-400">Estimasi Selesai</span><span class="text-sm font-semibold" id="orderEstimate">-</span></div>
                                        <div class="flex justify-between"><span class="text-xs text-surface-400">Kasir</span><span class="text-sm font-semibold" id="orderCashier">-</span></div>
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-3 justify-end">
                                    <button onclick="resetOrderForm()" class="px-6 py-3 border border-surface-200 rounded-xl text-sm font-semibold text-surface-600 hover:bg-surface-50 transition-all">Reset</button>
                                    <button onclick="saveOrder()" class="px-6 py-3 bg-gradient-to-r from-primary-500 to-accent-500 text-white rounded-xl text-sm font-semibold hover:shadow-lg hover:shadow-primary-500/25 transition-all flex items-center gap-2"><i class="ri-save-line"></i> Simpan Pesanan</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Services Page -->
                    <div id="page-services" class="page-content hidden fade-in">
                        <div class="flex justify-between items-center mb-5">
                            <p class="text-sm text-surface-500">Kelola layanan dan harga laundry Anda</p>
                            <button id="btnAddService" onclick="showAddServiceModal()" class="px-4 py-2.5 bg-primary-500 text-white rounded-xl text-sm font-semibold hover:bg-primary-600 transition-all flex items-center gap-2"><i class="ri-add-line"></i> Tambah Layanan</button>
                        </div>
                        <div id="servicesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"></div>
                    </div>

                    <!-- Customers Page -->
                    <div id="page-customers" class="page-content hidden fade-in">
                        <div class="flex justify-between items-center mb-5">
                            <div class="relative"><i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-surface-400"></i><input type="text" id="searchCustomer" oninput="renderCustomers()" placeholder="Cari pelanggan..." class="pl-10 pr-4 py-2.5 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 w-64"></div>
                            <button onclick="showAddCustomerModal()" class="px-4 py-2.5 bg-primary-500 text-white rounded-xl text-sm font-semibold hover:bg-primary-600 transition-all flex items-center gap-2"><i class="ri-add-line"></i> Tambah Pelanggan</button>
                        </div>
                        <div class="bg-white rounded-2xl border border-surface-100 overflow-hidden"><div class="overflow-x-auto"><table class="w-full text-sm"><thead><tr class="border-b border-surface-100 bg-surface-50"><th class="text-left py-3 px-4 text-surface-500 font-medium">Nama</th><th class="text-left py-3 px-4 text-surface-500 font-medium">Telepon</th><th class="text-right py-3 px-4 text-surface-500 font-medium">Total Pesanan</th><th class="text-right py-3 px-4 text-surface-500 font-medium">Total Belanja</th><th class="text-center py-3 px-4 text-surface-500 font-medium">Aksi</th></tr></thead><tbody id="customersTableBody"></tbody></table></div></div>
                    </div>

                    <!-- Reports Page -->
                    <div id="page-reports" class="page-content hidden fade-in">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                            <div class="bg-white rounded-2xl border border-surface-100 p-5"><h3 class="font-bold mb-4">Pendapatan 7 Hari Terakhir</h3><div id="revenueChart" class="space-y-3"></div></div>
                            <div class="bg-white rounded-2xl border border-surface-100 p-5"><h3 class="font-bold mb-4">Performa Kasir</h3><div id="cashierPerformance" class="space-y-3"></div></div>
                        </div>
                        <div class="bg-white rounded-2xl border border-surface-100 p-5">
                            <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
                                <h3 class="font-bold">Riwayat Transaksi</h3>
                                <div class="flex items-center gap-2"><input type="date" id="reportDateFrom" onchange="renderReports()" class="px-3 py-2 border border-surface-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"><span class="text-surface-400">s/d</span><input type="date" id="reportDateTo" onchange="renderReports()" class="px-3 py-2 border border-surface-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"></div>
                            </div>
                            <div class="overflow-x-auto"><table class="w-full text-sm"><thead><tr class="border-b border-surface-100 bg-surface-50"><th class="text-left py-3 px-3 text-surface-500 font-medium">Tanggal</th><th class="text-left py-3 px-3 text-surface-500 font-medium">Invoice</th><th class="text-left py-3 px-3 text-surface-500 font-medium">Pelanggan</th><th class="text-right py-3 px-3 text-surface-500 font-medium">Total</th><th class="text-center py-3 px-3 text-surface-500 font-medium">Pembayaran</th><th class="text-left py-3 px-3 text-surface-500 font-medium">Kasir</th></tr></thead><tbody id="reportsTableBody"></tbody></table></div>
                            <div class="mt-4 flex justify-between items-center p-4 bg-surface-50 rounded-xl"><span class="text-sm text-surface-500">Total Pendapatan Periode</span><span class="text-xl font-bold text-primary-600" id="reportTotalRevenue">Rp 0</span></div>
                        </div>
                    </div>

                    <!-- Users Management Page -->
                    <div id="page-users" class="page-content hidden fade-in">
                        <div class="flex justify-between items-center mb-5"><p class="text-sm text-surface-500">Kelola pengguna dan hak akses</p><button onclick="showAddUserModal()" class="px-4 py-2.5 bg-primary-500 text-white rounded-xl text-sm font-semibold hover:bg-primary-600 transition-all flex items-center gap-2"><i class="ri-add-line"></i> Tambah User</button></div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                            <div class="bg-gradient-to-r from-red-50 to-red-50 rounded-2xl p-4 border border-red-100"><p class="text-sm text-red-600 font-semibold mb-1">Admin</p><p class="text-xs text-red-500">Akses penuh: kelola user, laporan, pengaturan</p><p class="text-2xl font-bold text-red-700 mt-2" id="countAdmin">0</p></div>
                            <div class="bg-gradient-to-r from-blue-50 to-blue-50 rounded-2xl p-4 border border-blue-100"><p class="text-sm text-blue-600 font-semibold mb-1">Kasir</p><p class="text-xs text-blue-500">Buat pesanan, pembayaran, lihat pelanggan</p><p class="text-2xl font-bold text-blue-700 mt-2" id="countKasir">0</p></div>
                            <div class="bg-gradient-to-r from-purple-50 to-purple-50 rounded-2xl p-4 border border-purple-100"><p class="text-sm text-purple-600 font-semibold mb-1">Staff</p><p class="text-xs text-purple-500">Update status pesanan, lihat pesanan</p><p class="text-2xl font-bold text-purple-700 mt-2" id="countStaff">0</p></div>
                        </div>
                        <div class="bg-white rounded-2xl border border-surface-100 overflow-hidden"><div class="overflow-x-auto"><table class="w-full text-sm"><thead><tr class="border-b border-surface-100 bg-surface-50"><th class="text-left py-3 px-4 text-surface-500 font-medium">User</th><th class="text-left py-3 px-4 text-surface-500 font-medium">Username</th><th class="text-center py-3 px-4 text-surface-500 font-medium">Role</th><th class="text-center py-3 px-4 text-surface-500 font-medium">Status</th><th class="text-left py-3 px-4 text-surface-500 font-medium">Login Terakhir</th><th class="text-center py-3 px-4 text-surface-500 font-medium">Total Transaksi</th><th class="text-center py-3 px-4 text-surface-500 font-medium">Aksi</th></tr></thead><tbody id="usersTableBody"></tbody></table></div></div>
                    </div>

                    <!-- Activity Log Page -->
                    <div id="page-activity" class="page-content hidden fade-in">
                        <div class="flex justify-between items-center mb-5"><p class="text-sm text-surface-500">Riwayat aktivitas semua pengguna</p><div class="flex items-center gap-2"><select id="filterActivityUser" onchange="renderActivity()" class="px-4 py-2.5 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"><option value="all">Semua User</option></select><select id="filterActivityType" onchange="renderActivity()" class="px-4 py-2.5 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"><option value="all">Semua Aksi</option><option value="login">Login</option><option value="logout">Logout</option><option value="order">Pesanan</option><option value="payment">Pembayaran</option><option value="user">Manajemen User</option></select></div></div>
                        <div class="bg-white rounded-2xl border border-surface-100 overflow-hidden"><div id="activityList" class="divide-y divide-surface-100"></div><div id="emptyActivity" class="hidden py-12 text-center"><i class="ri-history-line text-5xl text-surface-300"></i><p class="text-surface-400 mt-3">Belum ada aktivitas</p></div></div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center modal-overlay p-4 no-print">
        <div class="bg-white rounded-2xl w-full max-w-lg modal-content max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-5"><h3 class="text-lg font-bold">💳 Pembayaran</h3><button onclick="closePaymentModal()" class="p-2 hover:bg-surface-100 rounded-lg transition-all"><i class="ri-close-line text-xl"></i></button></div>
                <div class="text-center mb-6 p-4 bg-surface-50 rounded-xl"><p class="text-sm text-surface-500">Total Tagihan</p><p class="text-3xl font-bold text-surface-800" id="paymentTotal">Rp 0</p><p class="text-xs text-surface-400 mt-1" id="paymentInvoice">INV-001</p></div>
                <div class="mb-5"><label class="block text-sm font-medium text-surface-600 mb-2">Metode Pembayaran</label><div class="grid grid-cols-3 gap-3"><button onclick="setPaymentMethod('qris')" class="payment-method-btn px-4 py-3 border-2 border-surface-200 rounded-xl text-center hover:border-primary-500 transition-all" data-method="qris"><i class="ri-qr-code-line text-2xl mb-1 block"></i><span class="text-xs font-semibold">QRIS</span></button><button onclick="setPaymentMethod('tunai')" class="payment-method-btn px-4 py-3 border-2 border-surface-200 rounded-xl text-center hover:border-primary-500 transition-all" data-method="tunai"><i class="ri-money-dollar-circle-line text-2xl mb-1 block"></i><span class="text-xs font-semibold">Tunai</span></button><button onclick="setPaymentMethod('transfer')" class="payment-method-btn px-4 py-3 border-2 border-surface-200 rounded-xl text-center hover:border-primary-500 transition-all" data-method="transfer"><i class="ri-bank-card-line text-2xl mb-1 block"></i><span class="text-xs font-semibold">Transfer</span></button></div></div>
                <div id="qrisSection" class="hidden mb-5"><div class="text-center"><p class="text-sm text-surface-500 mb-3">Scan QR Code untuk pembayaran</p><div id="qrisCode" class="inline-block bg-white p-4 rounded-xl border-2 border-primary-200 qr-scanner"><div id="qrisPlaceholder" class="w-48 h-48 bg-surface-100 rounded-lg flex items-center justify-center"><i class="ri-qr-code-line text-6xl text-surface-300"></i></div></div><p class="text-xs text-surface-400 mt-3">QRIS akan digenerate setelah pembayaran dikonfirmasi</p></div></div>
                <div id="cashSection" class="hidden mb-5"><label class="block text-sm font-medium text-surface-600 mb-2">Jumlah Bayar</label><input type="number" id="cashAmount" oninput="calcChange()" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-lg font-bold focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="Masukkan jumlah..."><div class="flex gap-2 mt-3 flex-wrap"><button onclick="setCashAmount(0)" class="px-3 py-1.5 bg-surface-100 rounded-lg text-xs font-medium hover:bg-surface-200">Uang Pas</button><button onclick="setCashAmount(50000)" class="px-3 py-1.5 bg-surface-100 rounded-lg text-xs font-medium hover:bg-surface-200">Rp 50.000</button><button onclick="setCashAmount(100000)" class="px-3 py-1.5 bg-surface-100 rounded-lg text-xs font-medium hover:bg-surface-200">Rp 100.000</button></div><div class="mt-3 p-3 bg-green-50 rounded-xl flex justify-between items-center"><span class="text-sm text-green-700">Kembalian</span><span class="text-lg font-bold text-green-700" id="changeAmount">Rp 0</span></div></div>
                <button onclick="confirmPayment()" class="w-full py-3.5 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl font-semibold hover:shadow-lg hover:shadow-green-500/25 transition-all flex items-center justify-center gap-2"><i class="ri-check-line"></i> Konfirmasi Pembayaran</button>
            </div>
        </div>
    </div>

    <!-- Receipt Modal (PRINT TARGET) -->
    <div id="receiptModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center modal-overlay p-4">
        <div class="bg-white rounded-2xl w-full max-w-sm modal-content">
            <div class="p-6" id="receiptContent">
                <div class="text-center mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-accent-500 rounded-xl flex items-center justify-center mx-auto mb-3"><i class="ri-shirt-line text-white text-xl"></i></div>
                    <h3 class="font-bold text-lg">LaundryPOS</h3>
                    <p class="text-xs text-surface-400">Jl. Contoh No. 123, Kota</p>
                    <p class="text-xs text-surface-400">Telp: 0812-3456-7890</p>
                </div>
                <div class="receipt-line my-3"></div>
                <div class="space-y-2 text-sm mb-3">
                    <div class="flex justify-between"><span class="text-surface-500">No. Invoice</span><span class="font-semibold" id="receiptInvoice"></span></div>
                    <div class="flex justify-between"><span class="text-surface-500">Tanggal</span><span id="receiptDate"></span></div>
                    <div class="flex justify-between"><span class="text-surface-500">Pelanggan</span><span id="receiptCustomer"></span></div>
                    <div class="flex justify-between"><span class="text-surface-500">Kasir</span><span id="receiptCashier"></span></div>
                </div>
                <div class="receipt-line my-3"></div>
                <div id="receiptItems" class="space-y-2 text-sm mb-3"></div>
                <div class="receipt-line my-3"></div>
                <div class="space-y-1 text-sm">
                    <div class="flex justify-between"><span class="text-surface-500">Total</span><span class="font-bold" id="receiptTotal"></span></div>
                    <div class="flex justify-between"><span class="text-surface-500">Pembayaran</span><span id="receiptPayment"></span></div>
                    <div class="flex justify-between"><span class="text-surface-500">Kembalian</span><span id="receiptChange"></span></div>
                </div>
                <div class="receipt-line my-3"></div>
                <p class="text-center text-xs text-surface-400 mt-4">Terima kasih atas kepercayaan Anda! 🧺</p>
            </div>
            <div class="p-4 border-t border-surface-100 flex gap-3 no-print">
                <button onclick="closeReceiptModal()" class="flex-1 py-3 border border-surface-200 rounded-xl text-sm font-semibold text-surface-600 hover:bg-surface-50">Tutup</button>
                <button onclick="printReceipt()" class="flex-1 py-3 bg-primary-500 text-white rounded-xl text-sm font-semibold hover:bg-primary-600 flex items-center justify-center gap-2"><i class="ri-printer-line"></i> Cetak</button>
            </div>
        </div>
    </div>

    <!-- Service Modal -->
    <div id="serviceModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center modal-overlay p-4 no-print">
        <div class="bg-white rounded-2xl w-full max-w-md modal-content">
            <div class="p-6"><h3 class="text-lg font-bold mb-5" id="serviceModalTitle">Tambah Layanan</h3><input type="hidden" id="serviceEditId"><div class="space-y-4"><div><label class="block text-sm font-medium text-surface-600 mb-1">Nama Layanan</label><input type="text" id="serviceName" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="Cuci Reguler"></div><div><label class="block text-sm font-medium text-surface-600 mb-1">Harga per Kg / Satuan</label><input type="number" id="servicePrice" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="7000"></div><div><label class="block text-sm font-medium text-surface-600 mb-1">Satuan</label><select id="serviceUnit" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"><option value="kg">Per Kg</option><option value="pcs">Per Pcs</option><option value="set">Per Set</option></select></div><div><label class="block text-sm font-medium text-surface-600 mb-1">Estimasi Selesai (jam)</label><input type="number" id="serviceDuration" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="24" value="24"></div><div><label class="block text-sm font-medium text-surface-600 mb-1">Ikon (emoji)</label><input type="text" id="serviceIcon" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="👕" value="👕"></div></div><div class="flex gap-3 mt-6"><button onclick="closeServiceModal()" class="flex-1 py-3 border border-surface-200 rounded-xl text-sm font-semibold text-surface-600 hover:bg-surface-50">Batal</button><button onclick="saveService()" class="flex-1 py-3 bg-primary-500 text-white rounded-xl text-sm font-semibold hover:bg-primary-600">Simpan</button></div></div>
        </div>
    </div>

    <!-- Customer Modal -->
    <div id="customerModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center modal-overlay p-4 no-print">
        <div class="bg-white rounded-2xl w-full max-w-md modal-content">
            <div class="p-6"><h3 class="text-lg font-bold mb-5" id="customerModalTitle">Tambah Pelanggan</h3><input type="hidden" id="customerEditId"><div class="space-y-4"><div><label class="block text-sm font-medium text-surface-600 mb-1">Nama Lengkap</label><input type="text" id="custName" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="Nama pelanggan"></div><div><label class="block text-sm font-medium text-surface-600 mb-1">No. Telepon</label><input type="tel" id="custPhone" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="08xxxxxxxxxx"></div><div><label class="block text-sm font-medium text-surface-600 mb-1">Alamat</label><textarea id="custAddress" rows="2" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none" placeholder="Alamat pelanggan"></textarea></div></div><div class="flex gap-3 mt-6"><button onclick="closeCustomerModal()" class="flex-1 py-3 border border-surface-200 rounded-xl text-sm font-semibold text-surface-600 hover:bg-surface-50">Batal</button><button onclick="saveCustomer()" class="flex-1 py-3 bg-primary-500 text-white rounded-xl text-sm font-semibold hover:bg-primary-600">Simpan</button></div></div>
        </div>
    </div>

    <!-- User Modal -->
    <div id="userModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center modal-overlay p-4 no-print">
        <div class="bg-white rounded-2xl w-full max-w-md modal-content">
            <div class="p-6"><h3 class="text-lg font-bold mb-5" id="userModalTitle">Tambah User</h3><input type="hidden" id="userEditId"><div class="space-y-4"><div><label class="block text-sm font-medium text-surface-600 mb-1">Nama Lengkap</label><input type="text" id="userFullName" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="Nama lengkap"></div><div><label class="block text-sm font-medium text-surface-600 mb-1">Username</label><input type="text" id="userUsername" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="Username untuk login"></div><div><label class="block text-sm font-medium text-surface-600 mb-1">Password</label><input type="password" id="userPassword" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="Minimal 6 karakter"></div><div><label class="block text-sm font-medium text-surface-600 mb-1">Role</label><select id="userRoleSelect" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"><option value="admin">Admin</option><option value="kasir">Kasir</option><option value="staff">Staff</option></select></div><div><label class="block text-sm font-medium text-surface-600 mb-1">No. Telepon</label><input type="tel" id="userPhone" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="08xxxxxxxxxx"></div><div><label class="block text-sm font-medium text-surface-600 mb-1">Status</label><select id="userStatusSelect" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"><option value="active">Aktif</option><option value="inactive">Nonaktif</option></select></div></div><div class="flex gap-3 mt-6"><button onclick="closeUserModal()" class="flex-1 py-3 border border-surface-200 rounded-xl text-sm font-semibold text-surface-600 hover:bg-surface-50">Batal</button><button onclick="saveUser()" class="flex-1 py-3 bg-primary-500 text-white rounded-xl text-sm font-semibold hover:bg-primary-600">Simpan</button></div></div>
        </div>
    </div>

    <!-- Profile Modal -->
    <div id="profileModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center modal-overlay p-4 no-print">
        <div class="bg-white rounded-2xl w-full max-w-md modal-content">
            <div class="p-6">
                <div class="text-center mb-5"><div id="profileAvatar" class="w-20 h-20 bg-gradient-to-br from-primary-400 to-accent-400 rounded-full flex items-center justify-center text-white font-bold text-3xl mx-auto mb-3">A</div><h3 class="text-lg font-bold" id="profileName">Admin</h3><p class="text-sm text-surface-400" id="profileUsername">@admin</p><span class="inline-block mt-2 text-xs text-white font-semibold px-3 py-1 rounded-full" id="profileRoleBadge">Admin</span></div>
                <div class="space-y-3 mb-5"><div class="flex justify-between text-sm"><span class="text-surface-500">Login Terakhir</span><span class="font-medium" id="profileLastLogin">-</span></div><div class="flex justify-between text-sm"><span class="text-surface-500">Total Transaksi</span><span class="font-medium" id="profileTotalOrders">0</span></div><div class="flex justify-between text-sm"><span class="text-surface-500">Total Pendapatan</span><span class="font-medium" id="profileTotalRevenue">Rp 0</span></div></div>
                <div class="flex gap-3"><button onclick="closeProfileModal()" class="flex-1 py-3 border border-surface-200 rounded-xl text-sm font-semibold text-surface-600 hover:bg-surface-50">Tutup</button><button onclick="showChangePasswordModal()" class="flex-1 py-3 bg-primary-500 text-white rounded-xl text-sm font-semibold hover:bg-primary-600 flex items-center justify-center gap-2"><i class="ri-lock-password-line"></i> Ganti Password</button></div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div id="changePasswordModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center modal-overlay p-4 no-print">
        <div class="bg-white rounded-2xl w-full max-w-md modal-content">
            <div class="p-6"><h3 class="text-lg font-bold mb-5">🔒 Ganti Password</h3><div class="space-y-4"><div><label class="block text-sm font-medium text-surface-600 mb-1">Password Lama</label><input type="password" id="oldPassword" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="Masukkan password lama"></div><div><label class="block text-sm font-medium text-surface-600 mb-1">Password Baru</label><input type="password" id="newPassword" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="Minimal 6 karakter"></div><div><label class="block text-sm font-medium text-surface-600 mb-1">Konfirmasi Password</label><input type="password" id="confirmPassword" class="w-full px-4 py-3 border border-surface-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="Ulangi password baru"></div></div><div class="flex gap-3 mt-6"><button onclick="closeChangePasswordModal()" class="flex-1 py-3 border border-surface-200 rounded-xl text-sm font-semibold text-surface-600 hover:bg-surface-50">Batal</button><button onclick="changePassword()" class="flex-1 py-3 bg-primary-500 text-white rounded-xl text-sm font-semibold hover:bg-primary-600">Simpan</button></div></div>
        </div>
    </div>

    <!-- Toast -->
    <div id="toastContainer" class="fixed bottom-4 right-4 z-[100] space-y-2"></div>

    <script>
        // ==================== MULTI USER SYSTEM ====================
        let users = [
            { id: 1, name: 'Administrator', username: 'admin', password: 'admin123', role: 'admin', phone: '081200000001', status: 'active', lastLogin: '2024-01-15 08:00', createdAt: '2024-01-01' },
            { id: 2, name: 'Siti Kasir', username: 'kasir1', password: 'kasir123', role: 'kasir', phone: '081200000002', status: 'active', lastLogin: '', createdAt: '2024-01-05' },
            { id: 3, name: 'Budi Staff', username: 'staff1', password: 'staff123', role: 'staff', phone: '081200000003', status: 'active', lastLogin: '', createdAt: '2024-01-10' },
            { id: 4, name: 'Rina Kasir', username: 'kasir2', password: 'kasir123', role: 'kasir', phone: '081200000004', status: 'inactive', lastLogin: '', createdAt: '2024-02-01' }
        ];

        let currentUser = null;
        let activityLog = [];
        let nextUserId = 5;

        const permissions = {
            admin: ['dashboard', 'orders', 'neworder', 'services', 'customers', 'reports', 'users', 'activity'],
            kasir: ['dashboard', 'orders', 'neworder', 'customers'],
            staff: ['dashboard', 'orders']
        };

        function handleLogin(e) {
            e.preventDefault();
            const username = document.getElementById('loginUsername').value.trim();
            const password = document.getElementById('loginPassword').value;
            const user = users.find(u => u.username === username && u.password === password);
            if (!user) {
                document.getElementById('loginError').classList.remove('hidden');
                document.getElementById('loginErrorText').textContent = 'Username atau password salah';
                setTimeout(() => document.getElementById('loginError').classList.add('hidden'), 3000);
                return;
            }
            if (user.status === 'inactive') {
                document.getElementById('loginError').classList.remove('hidden');
                document.getElementById('loginErrorText').textContent = 'Akun Anda dinonaktifkan. Hubungi admin.';
                setTimeout(() => document.getElementById('loginError').classList.add('hidden'), 3000);
                return;
            }
            const now = new Date();
            user.lastLogin = now.toISOString().replace('T', ' ').substring(0, 16);
            currentUser = { ...user };
            addActivity('login', `User ${user.name} (${user.role}) login`);
            document.getElementById('loginPage').classList.add('hidden');
            document.getElementById('appContainer').classList.remove('hidden');
            initApp();
        }

        function handleLogout() {
            if (currentUser) addActivity('logout', `User ${currentUser.name} (${currentUser.role}) logout`);
            currentUser = null;
            document.getElementById('appContainer').classList.add('hidden');
            document.getElementById('loginPage').classList.remove('hidden');
            document.getElementById('loginUsername').value = '';
            document.getElementById('loginPassword').value = '';
        }

        function togglePassword() {
            const input = document.getElementById('loginPassword');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') { input.type = 'text'; icon.className = 'ri-eye-off-line text-xl'; }
            else { input.type = 'password'; icon.className = 'ri-eye-line text-xl'; }
        }

        // ==================== APP INIT ====================
        function initApp() {
            updateDateTime();
            setInterval(updateDateTime, 1000);
            loadSampleData();
            applyRolePermissions();
            updateUserInfo();
            populateServiceSelects();
            populateCustomerList();
            setDefaultReportDates();
            navigate('dashboard');
        }

        function updateDateTime() {
            const now = new Date();
            document.getElementById('currentDate').textContent = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            document.getElementById('currentTime').textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        }

        function applyRolePermissions() {
            const allowed = permissions[currentUser.role] || [];
            document.querySelectorAll('.sidebar-link').forEach(link => {
                const page = link.dataset.page;
                link.style.display = allowed.includes(page) ? '' : 'none';
            });
        }

        function updateUserInfo() {
            if (!currentUser) return;
            document.getElementById('userName').textContent = currentUser.name;
            document.getElementById('userRole').textContent = currentUser.role.charAt(0).toUpperCase() + currentUser.role.slice(1);
            document.getElementById('userAvatar').textContent = currentUser.name.charAt(0).toUpperCase();
            document.getElementById('userName').style.cursor = 'pointer';
            document.getElementById('userRole').style.cursor = 'pointer';
            document.getElementById('userAvatar').style.cursor = 'pointer';
            document.getElementById('userName').onclick = showProfileModal;
            document.getElementById('userRole').onclick = showProfileModal;
            document.getElementById('userAvatar').onclick = showProfileModal;
        }

        function addActivity(type, description) {
            const now = new Date();
            activityLog.unshift({
                id: activityLog.length + 1,
                userId: currentUser ? currentUser.id : 0,
                userName: currentUser ? currentUser.name : 'System',
                userRole: currentUser ? currentUser.role : '',
                type, description,
                timestamp: now.toISOString(),
                displayTime: now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }),
                displayDate: now.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
            });
        }

        // ==================== SAMPLE DATA ====================
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
        let nextInvoiceNum = 1;
        let nextServiceId = 9;
        let nextCustomerId = 4;
        let currentPaymentOrderId = null;
        let selectedPaymentMethod = null;

        function loadSampleData() {
            const ua = [
                { userId: 1, status: 'selesai', payment: 'qris', paid: true },
                { userId: 2, status: 'diproses', payment: 'tunai', paid: true },
                { userId: 2, status: 'menunggu', payment: 'transfer', paid: false },
                { userId: 1, status: 'diambil', payment: 'tunai', paid: true },
                { userId: 1, status: 'selesai', payment: 'qris', paid: true },
                { userId: 2, status: 'selesai', payment: 'transfer', paid: true }
            ];
            ua.forEach((a, i) => {
                const date = new Date(); date.setDate(date.getDate() - Math.floor(Math.random() * 5));
                const orderDate = date.toISOString().split('T')[0];
                const time = `${String(8 + Math.floor(Math.random() * 10)).padStart(2, '0')}:${String(Math.floor(Math.random() * 60)).padStart(2, '0')}`;
                const invoice = `INV-${String(nextInvoiceNum).padStart(4, '0')}`;
                const custIdx = Math.floor(Math.random() * customers.length);
                const svcIdx = Math.floor(Math.random() * services.length);
                const service = services[svcIdx];
                const weight = service.unit === 'kg' ? (Math.random() * 4 + 1).toFixed(1) : 0;
                const qty = service.unit !== 'kg' ? Math.floor(Math.random() * 3 + 1) : 1;
                const total = service.price * (parseFloat(weight) || qty);
                orders.push({ id: nextInvoiceNum, invoice, customerId: customers[custIdx].id, items: [{ serviceId: service.id, weight: parseFloat(weight) || 0, qty, subtotal: total }], total, status: a.status, payment: a.payment, paid: a.paid, cashPaid: a.payment === 'tunai' ? Math.ceil(total / 1000) * 1000 : 0, change: 0, cashierId: a.userId, date: orderDate, time, notes: '', createdAt: date.toISOString() });
                nextInvoiceNum++;
            });
        }

        // ==================== NAVIGATION ====================
        function navigate(page) {
            document.querySelectorAll('.page-content').forEach(el => el.classList.add('hidden'));
            const target = document.getElementById(`page-${page}`);
            if (target) target.classList.remove('hidden');
            document.querySelectorAll('.sidebar-link').forEach(el => el.classList.remove('active'));
            const link = document.querySelector(`.sidebar-link[data-page="${page}"]`);
            if (link) link.classList.add('active');
            const titles = { dashboard: ['Dashboard', 'Ringkasan aktivitas laundry Anda'], orders: ['Pesanan', 'Kelola semua pesanan laundry'], neworder: ['Pesanan Baru', 'Buat pesanan laundry baru'], services: ['Layanan & Harga', 'Kelola layanan dan tarif laundry'], customers: ['Pelanggan', 'Data pelanggan terdaftar'], reports: ['Laporan', 'Analisis pendapatan & transaksi'], users: ['Kelola User', 'Manajemen pengguna dan hak akses'], activity: ['Log Aktivitas', 'Riwayat aktivitas semua pengguna'] };
            if (titles[page]) { document.getElementById('pageTitle').textContent = titles[page][0]; document.getElementById('pageSubtitle').textContent = titles[page][1]; }
            if (page === 'dashboard') renderDashboard();
            if (page === 'orders') { renderOrders(); populateFilterUsers(); }
            if (page === 'services') renderServices();
            if (page === 'customers') renderCustomers();
            if (page === 'reports') renderReports();
            if (page === 'users') renderUsers();
            if (page === 'activity') { renderActivity(); populateActivityUsers(); }
            if (page === 'neworder') { populateServiceSelects(); populateCustomerList(); calcOrderTotal(); document.getElementById('orderCashier').textContent = `${currentUser.name} (${currentUser.role})`; }
        }

        function toggleSidebar() { document.getElementById('sidebar').classList.toggle('-translate-x-full'); }

        // ==================== DASHBOARD ====================
        function renderDashboard() {
            const today = new Date().toISOString().split('T')[0];
            const todayOrders = orders.filter(o => o.date === today);
            const todayRevenue = todayOrders.reduce((s, o) => s + o.total, 0);
            const processing = orders.filter(o => o.status === 'diproses').length;
            const activeUsers = users.filter(u => u.status === 'active').length;
            document.getElementById('statTodayOrders').textContent = todayOrders.length;
            document.getElementById('statTodayRevenue').textContent = formatRupiah(todayRevenue);
            document.getElementById('statProcessing').textContent = processing;
            document.getElementById('statActiveUsers').textContent = activeUsers;
            const unpaidCount = orders.filter(o => !o.paid).length;
            const badge = document.getElementById('orderBadge');
            if (unpaidCount > 0) { badge.classList.remove('hidden'); badge.textContent = unpaidCount; } else { badge.classList.add('hidden'); }

            const recent = [...orders].reverse().slice(0, 5);
            const tbody = document.getElementById('dashboardRecentOrders');
            if (recent.length === 0) { tbody.innerHTML = '<tr><td colspan="5" class="py-8 text-center text-surface-400">Belum ada pesanan</td></tr>'; }
            else {
                tbody.innerHTML = recent.map(o => {
                    const cust = customers.find(c => c.id === o.customerId);
                    const cashier = users.find(u => u.id === o.cashierId);
                    return `<tr class="border-b border-surface-50 hover:bg-surface-50 transition-all cursor-pointer" onclick="viewOrder(${o.id})"><td class="py-3 px-3 font-medium text-primary-600">${o.invoice}</td><td class="py-3 px-3">${cust ? cust.name : '-'}</td><td class="py-3 px-3 font-semibold">${formatRupiah(o.total)}</td><td class="py-3 px-3">${getStatusBadge(o.status)}</td><td class="py-3 px-3"><span class="text-xs px-2 py-1 rounded-full ${getRoleBadgeClass(cashier?.role || '')}">${cashier ? cashier.name : '-'}</span></td></tr>`;
                }).join('');
            }

            const statusCounts = { menunggu: orders.filter(o => o.status === 'menunggu').length, diproses: orders.filter(o => o.status === 'diproses').length, selesai: orders.filter(o => o.status === 'selesai').length, diambil: orders.filter(o => o.status === 'diambil').length };
            const total = orders.length || 1;
            const sc = { menunggu: 'bg-amber-500', diproses: 'bg-blue-500', selesai: 'bg-green-500', diambil: 'bg-purple-500' };
            const sl = { menunggu: 'Menunggu', diproses: 'Diproses', selesai: 'Selesai', diambil: 'Diambil' };
            document.getElementById('statusChart').innerHTML = Object.entries(statusCounts).map(([k, c]) => `<div class="flex items-center gap-3"><div class="w-3 h-3 rounded-full ${sc[k]}"></div><span class="text-sm text-surface-600 flex-1">${sl[k]}</span><div class="w-32 bg-surface-100 rounded-full h-2.5 overflow-hidden"><div class="${sc[k]} h-full rounded-full transition-all" style="width: ${(c / total * 100)}%"></div></div><span class="text-sm font-semibold w-8 text-right">${c}</span></div>`).join('');

            const rl = [...users].filter(u => u.lastLogin).sort((a, b) => b.lastLogin.localeCompare(a.lastLogin)).slice(0, 4);
            document.getElementById('recentLogins').innerHTML = rl.length === 0 ? '<p class="text-xs text-surface-400">Belum ada login</p>' : rl.map(u => `<div class="flex items-center gap-2"><div class="w-7 h-7 ${getRoleBgClass(u.role)} rounded-full flex items-center justify-center text-white text-xs font-bold">${u.name.charAt(0)}</div><div class="flex-1 min-w-0"><p class="text-xs font-medium truncate">${u.name}</p><p class="text-[10px] text-surface-400">${u.lastLogin}</p></div><span class="text-[10px] px-1.5 py-0.5 rounded-full ${getRoleBadgeClass(u.role)}">${u.role}</span></div>`).join('');
        }

        function getRoleBadgeClass(role) { const c = { admin: 'bg-red-100 text-red-700', kasir: 'bg-blue-100 text-blue-700', staff: 'bg-purple-100 text-purple-700' }; return c[role] || 'bg-surface-100 text-surface-600'; }
        function getRoleBgClass(role) { const c = { admin: 'bg-gradient-to-br from-red-400 to-red-600', kasir: 'bg-gradient-to-br from-blue-400 to-blue-600', staff: 'bg-gradient-to-br from-purple-400 to-purple-600' }; return c[role] || 'bg-surface-400'; }

        // ==================== ORDERS ====================
        function populateFilterUsers() { document.getElementById('filterUser').innerHTML = '<option value="all">Semua User</option>' + users.map(u => `<option value="${u.id}">${u.name}</option>`).join(''); }

        function renderOrders() {
            const search = (document.getElementById('searchOrder')?.value || '').toLowerCase();
            const filterStatus = document.getElementById('filterStatus')?.value || 'all';
            const filterUser = document.getElementById('filterUser')?.value || 'all';
            let filtered = orders.filter(o => {
                const cust = customers.find(c => c.id === o.customerId);
                return o.invoice.toLowerCase().includes(search) || (cust && cust.name.toLowerCase().includes(search)) ? (filterStatus === 'all' || o.status === filterStatus) && (filterUser === 'all' || o.cashierId === parseInt(filterUser)) : false;
            });
            filtered.sort((a, b) => b.id - a.id);
            document.getElementById('orderCount').textContent = `${filtered.length} pesanan`;
            const tbody = document.getElementById('ordersTableBody');
            const empty = document.getElementById('emptyOrders');
            if (filtered.length === 0) { tbody.innerHTML = ''; empty.classList.remove('hidden'); return; }
            empty.classList.add('hidden');
            const canPay = currentUser.role === 'admin' || currentUser.role === 'kasir';
            const canEdit = currentUser.role === 'admin' || currentUser.role === 'kasir';
            tbody.innerHTML = filtered.map(o => {
                const cust = customers.find(c => c.id === o.customerId);
                const cashier = users.find(u => u.id === o.cashierId);
                const serviceNames = o.items.map(i => { const s = services.find(sv => sv.id === i.serviceId); return s ? s.name : ''; }).filter(Boolean).join(', ');
                const payIcon = o.paid ? '<i class="ri-check-line text-green-500"></i>' : '<i class="ri-close-line text-red-400"></i>';
                return `<tr class="border-b border-surface-50 hover:bg-surface-50 transition-all"><td class="py-3 px-3 font-medium text-primary-600 cursor-pointer" onclick="viewOrder(${o.id})">${o.invoice}</td><td class="py-3 px-3 text-surface-500">${o.date}</td><td class="py-3 px-3">${cust ? cust.name : '-'}</td><td class="py-3 px-3 text-xs">${serviceNames}</td><td class="py-3 px-3 text-right font-semibold">${formatRupiah(o.total)}</td><td class="py-3 px-3 text-center">${getStatusBadge(o.status)}</td><td class="py-3 px-3 text-center">${payIcon}</td><td class="py-3 px-3"><span class="text-xs px-2 py-1 rounded-full ${getRoleBadgeClass(cashier?.role || '')}">${cashier ? cashier.name : '-'}</span></td><td class="py-3 px-3 text-center"><div class="flex items-center justify-center gap-1">${!o.paid && canPay ? `<button onclick="openPaymentModal(${o.id})" class="p-1.5 text-green-600 hover:bg-green-50 rounded-lg" title="Bayar"><i class="ri-money-dollar-circle-line"></i></button>` : ''}<button onclick="viewOrder(${o.id})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg" title="Detail"><i class="ri-eye-line"></i></button>${canEdit ? `<button onclick="changeOrderStatus(${o.id})" class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg" title="Update Status"><i class="ri-refresh-line"></i></button>` : ''}${currentUser.role === 'admin' ? `<button onclick="deleteOrder(${o.id})" class="p-1.5 text-red-400 hover:bg-red-50 rounded-lg" title="Hapus"><i class="ri-delete-bin-line"></i></button>` : ''}</div></td></tr>`;
            }).join('');
        }

        function getStatusBadge(status) { const s = { menunggu: 'bg-amber-100 text-amber-700', diproses: 'bg-blue-100 text-blue-700', selesai: 'bg-green-100 text-green-700', diambil: 'bg-purple-100 text-purple-700' }; const l = { menunggu: 'Menunggu', diproses: 'Diproses', selesai: 'Selesai', diambil: 'Diambil' }; return `<span class="text-xs px-2.5 py-1 rounded-full font-medium ${s[status] || ''}">${l[status] || status}</span>`; }

        function viewOrder(id) { const o = orders.find(or => or.id === id); if (o) showReceipt(o); }
        function changeOrderStatus(id) {
            const o = orders.find(or => or.id === id); if (!o) return;
            const flow = ['menunggu', 'diproses', 'selesai', 'diambil'];
            const idx = flow.indexOf(o.status);
            if (idx < flow.length - 1) {
                o.status = flow[idx + 1];
                addActivity('order', `Status ${o.invoice} diubah ke "${o.status}" oleh ${currentUser.name}`);
                showToast(`Status ${o.invoice} diperbarui ke "${o.status}"`, 'success');
                renderOrders();
            }
        }
        function deleteOrder(id) {
            if (confirm('Hapus pesanan ini?')) {
                orders = orders.filter(o => o.id !== id);
                addActivity('order', `Pesanan INV-${String(id).padStart(4, '0')} dihapus oleh ${currentUser.name}`);
                showToast('Pesanan dihapus', 'info');
                renderOrders();
            }
        }

        // ==================== NEW ORDER ====================
        function addOrderItem() {
            const container = document.getElementById('orderItems');
            const div = document.createElement('div');
            div.className = 'order-item bg-white rounded-xl p-4 border border-surface-200 fade-in';
            div.innerHTML = `<div class="grid grid-cols-1 md:grid-cols-4 gap-3"><div><label class="block text-xs text-surface-500 mb-1">Layanan</label><select class="service-select w-full px-3 py-2.5 border border-surface-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" onchange="calcOrderTotal()"><option value="">Pilih Layanan</option></select></div><div><label class="block text-xs text-surface-500 mb-1">Berat (kg)</label><input type="number" class="weight-input w-full px-3 py-2.5 border border-surface-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" step="0.1" min="0" placeholder="0.0" oninput="calcOrderTotal()"></div><div><label class="block text-xs text-surface-500 mb-1">Jumlah</label><input type="number" class="qty-input w-full px-3 py-2.5 border border-surface-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" min="1" value="1" oninput="calcOrderTotal()"></div><div class="flex items-end gap-2"><div class="flex-1"><label class="block text-xs text-surface-500 mb-1">Subtotal</label><p class="px-3 py-2.5 text-sm font-semibold subtotal-display">Rp 0</p></div><button onclick="removeOrderItem(this)" class="p-2.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"><i class="ri-delete-bin-line"></i></button></div></div>`;
            container.appendChild(div);
            populateServiceSelects();
        }
        function removeOrderItem(btn) {
            const container = document.getElementById('orderItems');
            if (container.children.length > 1) { btn.closest('.order-item').remove(); calcOrderTotal(); }
            else { showToast('Minimal 1 item harus ada', 'warning'); }
        }
        function populateServiceSelects() { document.querySelectorAll('.service-select').forEach(sel => { const cv = sel.value; sel.innerHTML = `<option value="">Pilih Layanan</option>${services.map(s => `<option value="${s.id}">${s.icon} ${s.name} - ${formatRupiah(s.price)}/${s.unit}</option>`).join('')}`; if (cv) sel.value = cv; }); }
        function populateCustomerList() { document.getElementById('customerList').innerHTML = customers.map(c => `<option value="${c.name}">${c.name} - ${c.phone}</option>`).join(''); }
        function calcOrderTotal() {
            let total = 0;
            document.querySelectorAll('.order-item').forEach(item => {
                const serviceId = parseInt(item.querySelector('.service-select').value);
                const weight = parseFloat(item.querySelector('.weight-input').value) || 0;
                const qty = parseInt(item.querySelector('.qty-input').value) || 1;
                const service = services.find(s => s.id === serviceId);
                if (service) {
                    const subtotal = service.unit === 'kg' && weight > 0 ? service.price * weight * qty : service.price * qty;
                    item.querySelector('.subtotal-display').textContent = formatRupiah(subtotal);
                    total += subtotal;
                } else { item.querySelector('.subtotal-display').textContent = 'Rp 0'; }
            });
            document.getElementById('orderTotalDisplay').textContent = formatRupiah(total);
            const maxDur = Math.max(...Array.from(document.querySelectorAll('.order-item')).map(i => { const s = services.find(sv => sv.id === parseInt(i.querySelector('.service-select').value)); return s ? s.duration : 0; }));
            if (maxDur > 0) { const est = new Date(); est.setHours(est.getHours() + maxDur); document.getElementById('orderEstimate').textContent = est.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }) + ' ' + est.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }); }
            else { document.getElementById('orderEstimate').textContent = '-'; }
        }

        function saveOrder() {
            const name = document.getElementById('customerName').value.trim();
            const phone = document.getElementById('customerPhone').value.trim();
            const notes = document.getElementById('orderNotes').value.trim();
            if (!name) { showToast('Masukkan nama pelanggan', 'warning'); return; }
            let cust = customers.find(c => c.name.toLowerCase() === name.toLowerCase());
            if (!cust) { cust = { id: nextCustomerId++, name, phone, address: '' }; customers.push(cust); populateCustomerList(); }
            else if (phone) { cust.phone = phone; }
            const items = []; let total = 0;
            document.querySelectorAll('.order-item').forEach(item => {
                const serviceId = parseInt(item.querySelector('.service-select').value);
                const weight = parseFloat(item.querySelector('.weight-input').value) || 0;
                const qty = parseInt(item.querySelector('.qty-input').value) || 1;
                const service = services.find(s => s.id === serviceId);
                if (service) {
                    const subtotal = service.unit === 'kg' && weight > 0 ? service.price * weight * qty : service.price * qty;
                    items.push({ serviceId, weight, qty, subtotal });
                    total += subtotal;
                }
            });
            if (items.length === 0) { showToast('Pilih minimal 1 layanan', 'warning'); return; }
            const now = new Date();
            const order = { id: nextInvoiceNum, invoice: `INV-${String(nextInvoiceNum).padStart(4, '0')}`, customerId: cust.id, items, total, status: 'menunggu', payment: '', paid: false, cashPaid: 0, change: 0, cashierId: currentUser.id, date: now.toISOString().split('T')[0], time: now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }), notes, createdAt: now.toISOString() };
            orders.push(order); nextInvoiceNum++;
            addActivity('order', `Pesanan baru ${order.invoice} dibuat oleh ${currentUser.name} - ${formatRupiah(total)}`);
            showToast(`Pesanan ${order.invoice} berhasil dibuat!`, 'success');
            resetOrderForm();
            openPaymentModal(order.id);
        }

        function resetOrderForm() {
            document.getElementById('customerName').value = ''; document.getElementById('customerPhone').value = ''; document.getElementById('orderNotes').value = '';
            document.getElementById('orderItems').innerHTML = `<div class="order-item bg-white rounded-xl p-4 border border-surface-200"><div class="grid grid-cols-1 md:grid-cols-4 gap-3"><div><label class="block text-xs text-surface-500 mb-1">Layanan</label><select class="service-select w-full px-3 py-2.5 border border-surface-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" onchange="calcOrderTotal()"><option value="">Pilih Layanan</option></select></div><div><label class="block text-xs text-surface-500 mb-1">Berat (kg)</label><input type="number" class="weight-input w-full px-3 py-2.5 border border-surface-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" step="0.1" min="0.1" placeholder="0.0" oninput="calcOrderTotal()"></div><div><label class="block text-xs text-surface-500 mb-1">Jumlah</label><input type="number" class="qty-input w-full px-3 py-2.5 border border-surface-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" min="1" value="1" oninput="calcOrderTotal()"></div><div class="flex items-end gap-2"><div class="flex-1"><label class="block text-xs text-surface-500 mb-1">Subtotal</label><p class="px-3 py-2.5 text-sm font-semibold subtotal-display">Rp 0</p></div><button onclick="removeOrderItem(this)" class="p-2.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"><i class="ri-delete-bin-line"></i></button></div></div></div>`;
            populateServiceSelects(); calcOrderTotal();
        }

        // ==================== PAYMENT ====================
        function openPaymentModal(orderId) {
            currentPaymentOrderId = orderId;
            const order = orders.find(o => o.id === orderId);
            if (!order) return;
            document.getElementById('paymentTotal').textContent = formatRupiah(order.total);
            document.getElementById('paymentInvoice').textContent = `${order.invoice} - ${customers.find(c => c.id === order.customerId)?.name || ''}`;
            selectedPaymentMethod = null;
            document.querySelectorAll('.payment-method-btn').forEach(btn => { btn.classList.remove('border-primary-500', 'bg-primary-50'); btn.classList.add('border-surface-200'); });
            document.getElementById('qrisSection').classList.add('hidden'); document.getElementById('cashSection').classList.add('hidden');
            document.getElementById('cashAmount').value = ''; document.getElementById('changeAmount').textContent = 'Rp 0';
            document.getElementById('paymentModal').classList.remove('hidden'); document.getElementById('paymentModal').classList.add('flex');
        }
        function closePaymentModal() { document.getElementById('paymentModal').classList.add('hidden'); document.getElementById('paymentModal').classList.remove('flex'); currentPaymentOrderId = null; }
        function setPaymentMethod(method) {
            selectedPaymentMethod = method;
            document.querySelectorAll('.payment-method-btn').forEach(btn => { btn.classList.remove('border-primary-500', 'bg-primary-50'); btn.classList.add('border-surface-200'); if (btn.dataset.method === method) { btn.classList.add('border-primary-500', 'bg-primary-50'); btn.classList.remove('border-surface-200'); } });
            document.getElementById('qrisSection').classList.toggle('hidden', method !== 'qris');
            document.getElementById('cashSection').classList.toggle('hidden', method !== 'tunai');
            if (method === 'qris') generateQRISCode();
        }
        function generateQRISCode() {
            const order = orders.find(o => o.id === currentPaymentOrderId); if (!order) return;
            const qrDiv = document.getElementById('qrisPlaceholder');
            const canvas = document.createElement('canvas'); canvas.width = 192; canvas.height = 192;
            const ctx = canvas.getContext('2d'); ctx.fillStyle = '#ffffff'; ctx.fillRect(0, 0, 192, 192); ctx.fillStyle = '#000000';
            const seed = order.id * 1000 + order.total; const cellSize = 6; const gridSize = 32;
            for (let y = 0; y < gridSize; y++) for (let x = 0; x < gridSize; x++) { const hash = ((x * 31 + y * 17 + seed) * 7) % 100; if (hash < 40) ctx.fillRect(x * cellSize + 4, y * cellSize + 4, cellSize - 1, cellSize - 1); }
            function drawFinder(sx, sy) { ctx.fillStyle = '#000000'; ctx.fillRect(sx, sy, 21, 21); ctx.fillStyle = '#ffffff'; ctx.fillRect(sx + 3, sy + 3, 15, 15); ctx.fillStyle = '#000000'; ctx.fillRect(sx + 6, sy + 6, 9, 9); }
            drawFinder(4, 4); drawFinder(156, 4); drawFinder(4, 156);
            qrDiv.innerHTML = ''; qrDiv.style.width = '192px'; qrDiv.style.height = '192px'; qrDiv.appendChild(canvas);
        }
        function setCashAmount(amount) { const order = orders.find(o => o.id === currentPaymentOrderId); if (!order) return; document.getElementById('cashAmount').value = amount === 0 ? order.total : amount; calcChange(); }
        function calcChange() { const order = orders.find(o => o.id === currentPaymentOrderId); if (!order) return; const paid = parseFloat(document.getElementById('cashAmount').value) || 0; document.getElementById('changeAmount').textContent = formatRupiah(Math.max(0, paid - order.total)); }

        function confirmPayment() {
            if (!selectedPaymentMethod) { showToast('Pilih metode pembayaran', 'warning'); return; }
            const order = orders.find(o => o.id === currentPaymentOrderId); if (!order) return;
            if (selectedPaymentMethod === 'tunai') {
                const paid = parseFloat(document.getElementById('cashAmount').value) || 0;
                if (paid < order.total) { showToast('Jumlah pembayaran kurang', 'error'); return; }
                order.cashPaid = paid; order.change = paid - order.total;
            }
            order.payment = selectedPaymentMethod; order.paid = true; order.cashierId = currentUser.id;
            addActivity('payment', `Pembayaran ${order.invoice} ${formatRupiah(order.total)} via ${selectedPaymentMethod.toUpperCase()} oleh ${currentUser.name}`);
            closePaymentModal();
            showToast(`Pembayaran ${order.invoice} berhasil! (${selectedPaymentMethod.toUpperCase()})`, 'success');
            showReceipt(order);
            renderOrders();
        }

        // ==================== RECEIPT & PRINT (FIXED) ====================
        function showReceipt(order) {
            const cust = customers.find(c => c.id === order.customerId);
            const cashier = users.find(u => u.id === order.cashierId);
            document.getElementById('receiptInvoice').textContent = order.invoice;
            document.getElementById('receiptDate').textContent = order.date + ' ' + order.time;
            document.getElementById('receiptCustomer').textContent = cust ? cust.name : '-';
            document.getElementById('receiptCashier').textContent = cashier ? cashier.name : '-';
            document.getElementById('receiptTotal').textContent = formatRupiah(order.total);
            const payLabels = { qris: 'QRIS', tunai: 'Tunai', transfer: 'Transfer Bank', '': 'Belum Dibayar' };
            document.getElementById('receiptPayment').textContent = payLabels[order.payment] || '-';
            document.getElementById('receiptChange').textContent = order.change > 0 ? formatRupiah(order.change) : '-';
            document.getElementById('receiptItems').innerHTML = order.items.map(item => {
                const svc = services.find(s => s.id === item.serviceId);
                const desc = svc ? svc.name : '';
                const qtyText = svc.unit === 'kg' ? `${item.weight} kg` : `${item.qty} item`;
                return `<div class="flex justify-between"><div><span class="font-medium">${desc}</span><br><span class="text-xs text-surface-400">${qtyText} × ${formatRupiah(svc ? svc.price : 0)}</span></div><span class="font-semibold">${formatRupiah(item.subtotal)}</span></div>`;
            }).join('');
            if (order.notes) document.getElementById('receiptItems').innerHTML += `<div class="text-xs text-surface-400 mt-2"><i class="ri-sticky-note-line"></i> ${order.notes}</div>`;

            document.getElementById('receiptModal').classList.remove('hidden');
            document.getElementById('receiptModal').classList.add('flex');
        }

        function closeReceiptModal() {
            document.getElementById('receiptModal').classList.add('hidden');
            document.getElementById('receiptModal').classList.remove('flex');
        }

        // PRINT FUNCTION — FIXED
        function printReceipt() {
            // Pastikan modal receipt terlihat sebelum print
            const modal = document.getElementById('receiptModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Gunakan setTimeout agar browser sempat merender modal sebelum print dialog muncul
            setTimeout(() => {
                window.print();
            }, 200);
        }

        // ==================== SERVICES ====================
        function renderServices() {
            const canEdit = currentUser.role === 'admin';
            document.getElementById('btnAddService').style.display = canEdit ? '' : 'none';
            document.getElementById('servicesGrid').innerHTML = services.map(s => `
                <div class="bg-white rounded-2xl border border-surface-100 p-5 card-hover">
                    <div class="flex items-start justify-between mb-3">
                        <div class="text-3xl">${s.icon}</div>
                        ${canEdit ? `<div class="flex gap-1"><button onclick="editService(${s.id})" class="p-1.5 text-surface-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all"><i class="ri-edit-line"></i></button><button onclick="deleteService(${s.id})" class="p-1.5 text-surface-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"><i class="ri-delete-bin-line"></i></button></div>` : ''}
                    </div>
                    <h4 class="font-semibold text-surface-800 mb-1">${s.name}</h4>
                    <p class="text-lg font-bold text-primary-600">${formatRupiah(s.price)}<span class="text-sm text-surface-400 font-normal">/${s.unit}</span></p>
                    <p class="text-xs text-surface-400 mt-2"><i class="ri-time-line"></i> Estimasi ${s.duration} jam</p>
                </div>
            `).join('');
        }
        function showAddServiceModal() {
            document.getElementById('serviceModalTitle').textContent = 'Tambah Layanan';
            document.getElementById('serviceEditId').value = '';
            document.getElementById('serviceName').value = '';
            document.getElementById('servicePrice').value = '';
            document.getElementById('serviceUnit').value = 'kg';
            document.getElementById('serviceDuration').value = '24';
            document.getElementById('serviceIcon').value = '👕';
            document.getElementById('serviceModal').classList.remove('hidden'); document.getElementById('serviceModal').classList.add('flex');
        }
        function editService(id) {
            const svc = services.find(s => s.id === id); if (!svc) return;
            document.getElementById('serviceModalTitle').textContent = 'Edit Layanan';
            document.getElementById('serviceEditId').value = svc.id;
            document.getElementById('serviceName').value = svc.name;
            document.getElementById('servicePrice').value = svc.price;
            document.getElementById('serviceUnit').value = svc.unit;
            document.getElementById('serviceDuration').value = svc.duration;
            document.getElementById('serviceIcon').value = svc.icon;
            document.getElementById('serviceModal').classList.remove('hidden'); document.getElementById('serviceModal').classList.add('flex');
        }
        function closeServiceModal() { document.getElementById('serviceModal').classList.add('hidden'); document.getElementById('serviceModal').classList.remove('flex'); }
        function saveService() {
            const name = document.getElementById('serviceName').value.trim();
            const price = parseInt(document.getElementById('servicePrice').value) || 0;
            const unit = document.getElementById('serviceUnit').value;
            const duration = parseInt(document.getElementById('serviceDuration').value) || 24;
            const icon = document.getElementById('serviceIcon').value || '👕';
            const editId = document.getElementById('serviceEditId').value;
            if (!name || price <= 0) { showToast('Lengkapi data layanan', 'warning'); return; }
            if (editId) {
                const svc = services.find(s => s.id === parseInt(editId));
                if (svc) { Object.assign(svc, { name, price, unit, duration, icon }); showToast('Layanan diperbarui', 'success'); }
            } else {
                services.push({ id: nextServiceId++, name, price, unit, duration, icon });
                showToast('Layanan ditambahkan', 'success');
            }
            addActivity('user', `Layanan "${name}" ${editId ? 'diperbarui' : 'ditambahkan'} oleh ${currentUser.name}`);
            closeServiceModal(); renderServices(); populateServiceSelects();
        }
        function deleteService(id) {
            if (confirm('Hapus layanan ini?')) {
                const svc = services.find(s => s.id === id);
                services = services.filter(s => s.id !== id);
                addActivity('user', `Layanan "${svc?.name}" dihapus oleh ${currentUser.name}`);
                showToast('Layanan dihapus', 'info'); renderServices();
            }
        }

        // ==================== CUSTOMERS ====================
        function renderCustomers() {
            const search = (document.getElementById('searchCustomer')?.value || '').toLowerCase();
            let filtered = customers.filter(c => c.name.toLowerCase().includes(search) || c.phone.includes(search));
            document.getElementById('customersTableBody').innerHTML = filtered.map(c => {
                const custOrders = orders.filter(o => o.customerId === c.id);
                return `<tr class="border-b border-surface-50 hover:bg-surface-50 transition-all"><td class="py-3 px-4"><div class="flex items-center gap-3"><div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-accent-400 rounded-full flex items-center justify-center text-white font-bold text-sm">${c.name.charAt(0)}</div><div><p class="font-semibold text-surface-800">${c.name}</p><p class="text-xs text-surface-400">${c.address || '-'}</p></div></div></td><td class="py-3 px-4 text-surface-500">${c.phone}</td><td class="py-3 px-4 text-right font-semibold">${custOrders.length}</td><td class="py-3 px-4 text-right font-semibold">${formatRupiah(custOrders.reduce((s, o) => s + o.total, 0))}</td><td class="py-3 px-4 text-center"><div class="flex items-center justify-center gap-1"><button onclick="editCustomer(${c.id})" class="p-1.5 text-surface-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all"><i class="ri-edit-line"></i></button><button onclick="deleteCustomer(${c.id})" class="p-1.5 text-surface-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"><i class="ri-delete-bin-line"></i></button></div></td></tr>`;
            }).join('');
        }
        function showAddCustomerModal() {
            document.getElementById('customerModalTitle').textContent = 'Tambah Pelanggan';
            document.getElementById('customerEditId').value = '';
            document.getElementById('custName').value = ''; document.getElementById('custPhone').value = ''; document.getElementById('custAddress').value = '';
            document.getElementById('customerModal').classList.remove('hidden'); document.getElementById('customerModal').classList.add('flex');
        }
        function editCustomer(id) {
            const cust = customers.find(c => c.id === id); if (!cust) return;
            document.getElementById('customerModalTitle').textContent = 'Edit Pelanggan';
            document.getElementById('customerEditId').value = cust.id;
            document.getElementById('custName').value = cust.name; document.getElementById('custPhone').value = cust.phone; document.getElementById('custAddress').value = cust.address || '';
            document.getElementById('customerModal').classList.remove('hidden'); document.getElementById('customerModal').classList.add('flex');
        }
        function closeCustomerModal() { document.getElementById('customerModal').classList.add('hidden'); document.getElementById('customerModal').classList.remove('flex'); }
        function saveCustomer() {
            const name = document.getElementById('custName').value.trim();
            const phone = document.getElementById('custPhone').value.trim();
            const address = document.getElementById('custAddress').value.trim();
            const editId = document.getElementById('customerEditId').value;
            if (!name) { showToast('Masukkan nama pelanggan', 'warning'); return; }
            if (editId) { const cust = customers.find(c => c.id === parseInt(editId)); if (cust) { Object.assign(cust, { name, phone, address }); showToast('Pelanggan diperbarui', 'success'); } }
            else { customers.push({ id: nextCustomerId++, name, phone, address }); showToast('Pelanggan ditambahkan', 'success'); }
            addActivity('user', `Pelanggan "${name}" ${editId ? 'diperbarui' : 'ditambahkan'} oleh ${currentUser.name}`);
            closeCustomerModal(); renderCustomers(); populateCustomerList();
        }
        function deleteCustomer(id) {
            if (orders.filter(o => o.customerId === id).length > 0) { showToast('Pelanggan memiliki pesanan aktif', 'error'); return; }
            if (confirm('Hapus pelanggan ini?')) {
                const cust = customers.find(c => c.id === id);
                customers = customers.filter(c => c.id !== id);
                addActivity('user', `Pelanggan "${cust?.name}" dihapus oleh ${currentUser.name}`);
                showToast('Pelanggan dihapus', 'info'); renderCustomers();
            }
        }

        // ==================== REPORTS ====================
        function setDefaultReportDates() {
            const today = new Date(); const weekAgo = new Date(today); weekAgo.setDate(weekAgo.getDate() - 7);
            document.getElementById('reportDateFrom').value = weekAgo.toISOString().split('T')[0];
            document.getElementById('reportDateTo').value = today.toISOString().split('T')[0];
        }
        function renderReports() {
            const from = document.getElementById('reportDateFrom')?.value;
            const to = document.getElementById('reportDateTo')?.value;
            let filtered = orders.filter(o => o.paid);
            if (from) filtered = filtered.filter(o => o.date >= from);
            if (to) filtered = filtered.filter(o => o.date <= to);

            const last7Days = [];
            for (let i = 6; i >= 0; i--) {
                const d = new Date(); d.setDate(d.getDate() - i);
                const dateStr = d.toISOString().split('T')[0];
                const dayOrders = orders.filter(o => o.date === dateStr && o.paid);
                last7Days.push({ date: dateStr, day: d.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric' }), revenue: dayOrders.reduce((s, o) => s + o.total, 0), count: dayOrders.length });
            }
            const maxRev = Math.max(...last7Days.map(d => d.revenue), 1);
            document.getElementById('revenueChart').innerHTML = last7Days.map(d => `<div class="flex items-center gap-3"><span class="text-xs text-surface-500 w-16">${d.day}</span><div class="flex-1 bg-surface-100 rounded-full h-6 overflow-hidden"><div class="bg-gradient-to-r from-primary-500 to-accent-500 h-full rounded-full flex items-center px-2 transition-all" style="width: ${Math.max((d.revenue / maxRev) * 100, d.revenue > 0 ? 8 : 0)}%"><span class="text-xs text-white font-semibold">${d.revenue > 0 ? formatRupiahShort(d.revenue) : ''}</span></div></div><span class="text-xs text-surface-400 w-12 text-right">${d.count} trx</span></div>`).join('');

            const cashierStats = {};
            orders.filter(o => o.paid).forEach(o => { const cid = o.cashierId; if (!cashierStats[cid]) cashierStats[cid] = { count: 0, revenue: 0 }; cashierStats[cid].count++; cashierStats[cid].revenue += o.total; });
            const maxCR = Math.max(...Object.values(cashierStats).map(s => s.revenue), 1);
            document.getElementById('cashierPerformance').innerHTML = Object.entries(cashierStats).length === 0 ? '<p class="text-sm text-surface-400">Belum ada data</p>' : Object.entries(cashierStats).map(([uid, stat]) => {
                const u = users.find(user => user.id === parseInt(uid));
                return `<div class="flex items-center gap-3"><div class="w-8 h-8 ${getRoleBgClass(u?.role || '')} rounded-full flex items-center justify-center text-white text-xs font-bold">${u ? u.name.charAt(0) : '?'}</div><div class="flex-1"><div class="flex justify-between mb-1"><span class="text-sm font-medium">${u ? u.name : 'Unknown'}</span><span class="text-xs text-surface-500">${stat.count} trx</span></div><div class="bg-surface-100 rounded-full h-2.5 overflow-hidden"><div class="bg-gradient-to-r from-primary-500 to-green-500 h-full rounded-full transition-all" style="width: ${(stat.revenue / maxCR) * 100}%"></div></div><p class="text-xs text-surface-400 mt-1">${formatRupiah(stat.revenue)}</p></div></div>`;
            }).join('');

            filtered.sort((a, b) => b.id - a.id);
            const totalRev = filtered.reduce((s, o) => s + o.total, 0);
            document.getElementById('reportTotalRevenue').textContent = formatRupiah(totalRev);
            document.getElementById('reportsTableBody').innerHTML = filtered.map(o => {
                const cust = customers.find(c => c.id === o.customerId);
                const cashier = users.find(u => u.id === o.cashierId);
                const payIcons = { qris: '📱 QRIS', tunai: '💵 Tunai', transfer: '🏦 Transfer' };
                return `<tr class="border-b border-surface-50 hover:bg-surface-50 transition-all cursor-pointer" onclick="viewOrder(${o.id})"><td class="py-3 px-3 text-surface-500">${o.date}</td><td class="py-3 px-3 font-medium text-primary-600">${o.invoice}</td><td class="py-3 px-3">${cust ? cust.name : '-'}</td><td class="py-3 px-3 text-right font-semibold">${formatRupiah(o.total)}</td><td class="py-3 px-3 text-center"><span class="text-xs">${payIcons[o.payment] || '-'}</span></td><td class="py-3 px-3"><span class="text-xs px-2 py-1 rounded-full ${getRoleBadgeClass(cashier?.role || '')}">${cashier ? cashier.name : '-'}</span></td></tr>`;
            }).join('');
        }

        // ==================== USER MANAGEMENT ====================
        function renderUsers() {
            document.getElementById('countAdmin').textContent = users.filter(u => u.role === 'admin').length;
            document.getElementById('countKasir').textContent = users.filter(u => u.role === 'kasir').length;
            document.getElementById('countStaff').textContent = users.filter(u => u.role === 'staff').length;
            document.getElementById('usersTableBody').innerHTML = users.map(u => {
                const uOrders = orders.filter(o => o.cashierId === u.id);
                const isCurrentUser = currentUser.id === u.id;
                return `<tr class="border-b border-surface-50 hover:bg-surface-50 transition-all"><td class="py-3 px-4"><div class="flex items-center gap-3"><div class="w-10 h-10 ${getRoleBgClass(u.role)} rounded-full flex items-center justify-center text-white font-bold">${u.name.charAt(0)}</div><div><p class="font-semibold text-surface-800">${u.name} ${isCurrentUser ? '<span class="text-xs text-primary-500">(Anda)</span>' : ''}</p><p class="text-xs text-surface-400">${u.phone || '-'}</p></div></div></td><td class="py-3 px-4 font-mono text-sm">@${u.username}</td><td class="py-3 px-4 text-center"><span class="text-xs px-3 py-1 rounded-full font-semibold text-white ${u.role === 'admin' ? 'bg-red-500' : u.role === 'kasir' ? 'bg-blue-500' : 'bg-purple-500'}">${u.role.charAt(0).toUpperCase() + u.role.slice(1)}</span></td><td class="py-3 px-4 text-center"><span class="text-xs px-2 py-1 rounded-full ${u.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}">${u.status === 'active' ? 'Aktif' : 'Nonaktif'}</span></td><td class="py-3 px-4 text-sm text-surface-500">${u.lastLogin || '-'}</td><td class="py-3 px-4 text-right font-semibold">${uOrders.length}</td><td class="py-3 px-4 text-center"><div class="flex items-center justify-center gap-1"><button onclick="editUser(${u.id})" class="p-1.5 text-surface-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all"><i class="ri-edit-line"></i></button>${!isCurrentUser ? `<button onclick="toggleUserStatus(${u.id})" class="p-1.5 text-surface-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all" title="${u.status === 'active' ? 'Nonaktifkan' : 'Aktifkan'}"><i class="ri-${u.status === 'active' ? 'user-unfollow-line' : 'user-follow-line'}"></i></button><button onclick="deleteUser(${u.id})" class="p-1.5 text-surface-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"><i class="ri-delete-bin-line"></i></button>` : ''}</div></td></tr>`;
            }).join('');
        }
        function showAddUserModal() {
            document.getElementById('userModalTitle').textContent = 'Tambah User';
            document.getElementById('userEditId').value = '';
            document.getElementById('userFullName').value = '';
            document.getElementById('userUsername').value = '';
            document.getElementById('userPassword').value = '';
            document.getElementById('userPassword').placeholder = 'Minimal 6 karakter';
            document.getElementById('userRoleSelect').value = 'kasir';
            document.getElementById('userPhone').value = '';
            document.getElementById('userStatusSelect').value = 'active';
            document.getElementById('userModal').classList.remove('hidden'); document.getElementById('userModal').classList.add('flex');
        }
        function editUser(id) {
            const u = users.find(user => user.id === id); if (!u) return;
            document.getElementById('userModalTitle').textContent = 'Edit User';
            document.getElementById('userEditId').value = u.id;
            document.getElementById('userFullName').value = u.name;
            document.getElementById('userUsername').value = u.username;
            document.getElementById('userPassword').value = '';
            document.getElementById('userPassword').placeholder = 'Kosongkan jika tidak diubah';
            document.getElementById('userRoleSelect').value = u.role;
            document.getElementById('userPhone').value = u.phone || '';
            document.getElementById('userStatusSelect').value = u.status;
            document.getElementById('userModal').classList.remove('hidden'); document.getElementById('userModal').classList.add('flex');
        }
        function closeUserModal() { document.getElementById('userModal').classList.add('hidden'); document.getElementById('userModal').classList.remove('flex'); }
        function saveUser() {
            const name = document.getElementById('userFullName').value.trim();
            const username = document.getElementById('userUsername').value.trim().toLowerCase();
            const password = document.getElementById('userPassword').value;
            const role = document.getElementById('userRoleSelect').value;
            const phone = document.getElementById('userPhone').value.trim();
            const status = document.getElementById('userStatusSelect').value;
            const editId = document.getElementById('userEditId').value;
            if (!name || !username) { showToast('Lengkapi nama dan username', 'warning'); return; }
            if (!editId && password.length < 6) { showToast('Password minimal 6 karakter', 'warning'); return; }
            const dupUser = users.find(u => u.username === username && u.id !== parseInt(editId));
            if (dupUser) { showToast('Username sudah digunakan', 'error'); return; }
            if (editId) {
                const u = users.find(user => user.id === parseInt(editId));
                if (u) { u.name = name; u.username = username; if (password.length >= 6) u.password = password; u.role = role; u.phone = phone; u.status = status; showToast('User diperbarui', 'success'); }
            } else {
                users.push({ id: nextUserId++, name, username, password, role, phone, status, lastLogin: '', createdAt: new Date().toISOString().split('T')[0] });
                showToast('User ditambahkan', 'success');
            }
            addActivity('user', `User "${name}" (@${username}, ${role}) ${editId ? 'diperbarui' : 'ditambahkan'} oleh ${currentUser.name}`);
            closeUserModal(); renderUsers();
        }
        function toggleUserStatus(id) {
            const u = users.find(user => user.id === id); if (!u) return;
            u.status = u.status === 'active' ? 'inactive' : 'active';
            addActivity('user', `Status user "${u.name}" diubah ke ${u.status} oleh ${currentUser.name}`);
            showToast(`User ${u.name} ${u.status === 'active' ? 'diaktifkan' : 'dinonaktifkan'}`, 'info');
            renderUsers();
        }
        function deleteUser(id) {
            if (currentUser.id === id) { showToast('Tidak bisa menghapus akun sendiri', 'error'); return; }
            if (confirm('Hapus user ini? Semua data pesanan tetap tersimpan.')) {
                const u = users.find(user => user.id === id);
                users = users.filter(user => user.id !== id);
                addActivity('user', `User "${u?.name}" dihapus oleh ${currentUser.name}`);
                showToast('User dihapus', 'info'); renderUsers();
            }
        }

        // ==================== ACTIVITY LOG ====================
        function populateActivityUsers() { document.getElementById('filterActivityUser').innerHTML = '<option value="all">Semua User</option>' + users.map(u => `<option value="${u.id}">${u.name}</option>`).join(''); }
        function renderActivity() {
            const filterUser = document.getElementById('filterActivityUser')?.value || 'all';
            const filterType = document.getElementById('filterActivityType')?.value || 'all';
            let filtered = activityLog.filter(a => (filterUser === 'all' || a.userId === parseInt(filterUser)) && (filterType === 'all' || a.type === filterType));
            const listDiv = document.getElementById('activityList');
            const emptyDiv = document.getElementById('emptyActivity');
            if (filtered.length === 0) { listDiv.innerHTML = ''; emptyDiv.classList.remove('hidden'); return; }
            emptyDiv.classList.add('hidden');
            const typeIcons = { login: { icon: 'ri-login-circle-line', bg: 'bg-green-100', color: 'text-green-600' }, logout: { icon: 'ri-logout-box-line', bg: 'bg-red-100', color: 'text-red-600' }, order: { icon: 'ri-file-list-3-line', bg: 'bg-blue-100', color: 'text-blue-600' }, payment: { icon: 'ri-money-dollar-circle-line', bg: 'bg-green-100', color: 'text-green-600' }, user: { icon: 'ri-user-settings-line', bg: 'bg-purple-100', color: 'text-purple-600' } };
            listDiv.innerHTML = filtered.map(a => {
                const ti = typeIcons[a.type] || { icon: 'ri-information-line', bg: 'bg-surface-100', color: 'text-surface-600' };
                const u = users.find(user => user.id === a.userId);
                return `<div class="p-4 hover:bg-surface-50 transition-all flex items-start gap-4"><div class="w-10 h-10 ${ti.bg} ${ti.color} rounded-xl flex items-center justify-center flex-shrink-0"><i class="${ti.icon} text-xl"></i></div><div class="flex-1 min-w-0"><p class="text-sm text-surface-700">${a.description}</p><div class="flex items-center gap-2 mt-1"><span class="text-xs px-2 py-0.5 rounded-full ${getRoleBadgeClass(a.userRole || '')}">${a.userRole || 'system'}</span><span class="text-xs text-surface-400">${a.displayDate} ${a.displayTime}</span></div></div><span class="text-xs text-surface-400 flex-shrink-0">${u ? u.name : 'System'}</span></div>`;
            }).join('');
        }

        // ==================== PROFILE ====================
        function showProfileModal() {
            if (!currentUser) return;
            const u = users.find(user => user.id === currentUser.id);
            if (u) currentUser = { ...u };
            document.getElementById('profileAvatar').textContent = currentUser.name.charAt(0).toUpperCase();
            document.getElementById('profileName').textContent = currentUser.name;
            document.getElementById('profileUsername').textContent = `@${currentUser.username}`;
            const badge = document.getElementById('profileRoleBadge');
            badge.textContent = currentUser.role.charAt(0).toUpperCase() + currentUser.role.slice(1);
            badge.className = `inline-block mt-2 text-xs text-white font-semibold px-3 py-1 rounded-full ${currentUser.role === 'admin' ? 'bg-red-500' : currentUser.role === 'kasir' ? 'bg-blue-500' : 'bg-purple-500'}`;
            document.getElementById('profileLastLogin').textContent = currentUser.lastLogin || '-';
            const uOrders = orders.filter(o => o.cashierId === currentUser.id);
            document.getElementById('profileTotalOrders').textContent = uOrders.length;
            document.getElementById('profileTotalRevenue').textContent = formatRupiah(uOrders.reduce((s, o) => s + o.total, 0));
            document.getElementById('profileModal').classList.remove('hidden'); document.getElementById('profileModal').classList.add('flex');
        }
        function closeProfileModal() { document.getElementById('profileModal').classList.add('hidden'); document.getElementById('profileModal').classList.remove('flex'); }
        function showChangePasswordModal() {
            closeProfileModal();
            document.getElementById('oldPassword').value = ''; document.getElementById('newPassword').value = ''; document.getElementById('confirmPassword').value = '';
            document.getElementById('changePasswordModal').classList.remove('hidden'); document.getElementById('changePasswordModal').classList.add('flex');
        }
        function closeChangePasswordModal() { document.getElementById('changePasswordModal').classList.add('hidden'); document.getElementById('changePasswordModal').classList.remove('flex'); }
        function changePassword() {
            const oldPass = document.getElementById('oldPassword').value;
            const newPass = document.getElementById('newPassword').value;
            const confirmPass = document.getElementById('confirmPassword').value;
            if (oldPass !== currentUser.password) { showToast('Password lama salah', 'error'); return; }
            if (newPass.length < 6) { showToast('Password baru minimal 6 karakter', 'warning'); return; }
            if (newPass !== confirmPass) { showToast('Konfirmasi password tidak cocok', 'error'); return; }
            const u = users.find(user => user.id === currentUser.id);
            if (u) { u.password = newPass; currentUser.password = newPass; addActivity('user', `${currentUser.name} mengganti password`); showToast('Password berhasil diubah', 'success'); closeChangePasswordModal(); }
        }

        // ==================== UTILITIES ====================
        function formatRupiah(num) { return 'Rp ' + num.toLocaleString('id-ID'); }
        function formatRupiahShort(num) { if (num >= 1000000) return (num / 1000000).toFixed(1) + 'jt'; if (num >= 1000) return (num / 1000).toFixed(0) + 'rb'; return num.toString(); }
        function showToast(message, type = 'info') {
            const container = document.getElementById('toastContainer');
            const colors = { success: 'bg-green-500', error: 'bg-red-500', warning: 'bg-amber-500', info: 'bg-primary-500' };
            const icons = { success: 'ri-check-line', error: 'ri-close-line', warning: 'ri-alert-line', info: 'ri-information-line' };
            const toast = document.createElement('div');
            toast.className = `${colors[type]} text-white px-4 py-3 rounded-xl shadow-lg flex items-center gap-2 text-sm font-medium slide-up min-w-[280px]`;
            toast.innerHTML = `<i class="${icons[type]} text-lg"></i> ${message}`;
            container.appendChild(toast);
            setTimeout(() => { toast.style.opacity = '0'; toast.style.transform = 'translateX(20px)'; toast.style.transition = 'all 0.3s ease'; setTimeout(() => toast.remove(), 300); }, 3000);
        }

        document.addEventListener('input', (e) => {
            if (e.target.id === 'customerName') {
                const cust = customers.find(c => c.name.toLowerCase() === e.target.value.toLowerCase());
                if (cust) document.getElementById('customerPhone').value = cust.phone;
            }
        });
    </script>
</body>
</html>

