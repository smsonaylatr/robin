<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Admin Panel - <?php echo $__env->yieldContent('title', 'Dashboard'); ?></title>
    
    <!-- Favicon -->
    <?php
        $settings = \App\Models\Ayarlar::getSettings();
        $faviconPath = $settings && $settings->favicon ? $settings->favicon : 'favicon.ico';
    ?>
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset($faviconPath)); ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo e(asset($faviconPath)); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset($faviconPath)); ?>">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    <!-- Custom CSS -->
    <link href="/css/admin.css" rel="stylesheet">
    
    <style>
        /* Fallback CSS if external file doesn't load */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #000; /* site ile uyumlu */
            color: #fff;
            line-height: 1.6;
            overflow-x: hidden;
        }
        
        .bg-pattern {
            background-image: url('/hero-pattern/background-effect.webp');
            background-size: cover;
            background-position: center;
            opacity: 0.3;
            pointer-events: none;
        }
        
        .stat-card {
            background: rgba(16,16,16,0.6);
            border: 1px solid rgba(244, 63, 94, 0.2); /* red-500/20 */
            border-radius: 0.75rem;
            padding: 1.5rem;
            transition: all 0.3s;
            backdrop-filter: blur(8px);
        }
        
        .stat-card:hover {
            border-color: rgba(244, 63, 94, 0.35);
            box-shadow: 0 8px 30px rgba(244, 63, 94, 0.15);
            transform: translateY(-2px);
        }
        
        .search-card, .content-card {
            background: rgba(16,16,16,0.6);
            border: 1px solid rgba(244, 63, 94, 0.2);
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin: 1.5rem 0;
            transition: all 0.3s;
            backdrop-filter: blur(8px);
        }
        
        .search-card:hover, .content-card:hover {
            border-color: rgba(244, 63, 94, 0.35);
            box-shadow: 0 8px 30px rgba(244, 63, 94, 0.15);
        }
        
        .tabs-list {
            display: inline-flex;
            background: rgba(16,16,16,0.6);
            border: 1px solid rgba(244, 63, 94, 0.2);
            border-radius: 0.5rem;
            padding: 0.1875rem;
            gap: 0.125rem;
        }
        
        .tab-button {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #d1d5db;
            background: transparent;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }
        
        .tab-button:hover {
            color: #fff;
            background: rgba(244,63,94,0.08);
        }
        
        .tab-button.active {
            background: rgba(244, 63, 94, 0.12);
            color: #ef4444;
        }
        
        .system-status {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
            margin: 1.5rem 0;
        }
        
        .status-item {
            background: rgba(16, 185, 129, 0.1);
            border-radius: 0.5rem;
            padding: 1rem;
            transition: all 0.3s;
        }
        
        .status-item:hover {
            transform: scale(1.02);
        }
        
        .status-bar {
            width: 100%;
            height: 0.25rem;
            background: #374151;
            border-radius: 9999px;
            overflow: hidden;
        }
        
        .status-progress {
            height: 100%;
            background: #10b981;
            transition: width 0.3s ease;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1rem;
            padding: 0;
            margin: 0;
        }
        
        .stat-card-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .stat-card-icon {
            width: 2rem;
            height: 2rem;
            padding: 0.5rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        
        .stat-card.emerald .stat-card-icon {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }
        
        .stat-card.amber .stat-card-icon {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }
        
        .stat-card.blue .stat-card-icon {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }
        
        .stat-card.rose .stat-card-icon {
            background: rgba(244, 63, 94, 0.1);
            color: #f43f5e;
        }
        
        .stat-card.purple .stat-card-icon {
            background: rgba(147, 51, 234, 0.1);
            color: #9333ea;
        }
        
        .stat-card.cyan .stat-card-icon {
            background: rgba(6, 182, 212, 0.1);
            color: #06b6d4;
        }
        
        .stat-card.green .stat-card-icon {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
        }
        
        .stat-card.red .stat-card-icon {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }
        
        .search-input {
            width: 100%;
            height: 3rem;
            background: rgba(24, 24, 27, 0.5);
            border: 1px solid rgba(63, 63, 70, 0.5);
            border-radius: 0.375rem;
            padding: 0 1rem 0 2.5rem;
            color: #fff;
            font-size: 0.875rem;
            transition: all 0.2s;
        }
        
        .search-input:focus {
            outline: none;
            border-color: rgba(234, 179, 8, 0.5);
            box-shadow: 0 0 0 3px rgba(234, 179, 8, 0.1);
        }
        
        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            width: 1rem;
            height: 1rem;
            color: #6b7280;
        }
        
        /* User dropdown styles */
        .user-dropdown {
            position: relative;
        }
        
        .user-dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 0.5rem;
            background: rgba(10, 10, 10, 0.95);
            border: 1px solid rgba(244, 63, 94, 0.2);
            border-radius: 0.5rem;
            padding: 0.5rem;
            min-width: 200px;
            backdrop-filter: blur(12px);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s;
        }
        
        .user-dropdown-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .user-dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            color: #d1d5db;
            text-decoration: none;
            border-radius: 0.375rem;
            transition: all 0.2s;
            font-size: 0.875rem;
        }
        
        .user-dropdown-item:hover {
            background: rgba(244, 63, 94, 0.1);
            color: #fff;
        }
        
        .user-dropdown-item.logout {
            color: #f43f5e;
        }
        
        .user-dropdown-item.logout:hover {
            background: rgba(244, 63, 94, 0.1);
            color: #f43f5e;
        }
        
        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
                padding: 0;
            }
            
            .content-card,
            .search-card {
                margin: 1rem 0;
            }
            
            .system-status {
                grid-template-columns: 1fr;
                margin: 1rem 0;
            }
        }
        
        /* Main content area fixes */
        #main-content {
            width: 100%;
            max-width: 100%;
            margin-left: 0;
            transition: margin-left 0.3s ease;
        }
        
        #main-content main {
            width: 100%;
            max-width: 100%;
            padding: 1rem;
        }
        
        @media (min-width: 768px) {
            #main-content {
                margin-left: 16rem; /* 256px = 16rem - sidebar width */
            }
            
            #main-content.full-width {
                margin-left: 0;
            }
            
            #main-content main {
                padding: 1.5rem;
            }
        }
    </style>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-black text-white">
    <!-- Background Pattern -->
    <div class="fixed inset-0 bg-[url('/hero-pattern/background-effect.webp')] opacity-50 pointer-events-none"></div>
    
    <div class="flex min-h-screen flex-col relative bg-black/30">
        <div class="flex-1 flex flex-col md:flex-row">
            <main class="flex-1 mt-14 pb-16 md:pb-0">
                <div class="">
                    <!-- Top Navigation -->
                    <nav class="fixed top-0 w-full z-50 bg-black/95 backdrop-blur-md border-b border-red-500/40">
                        <div class="mx-auto px-2 sm:px-4">
                            <div class="flex items-center justify-between h-14 sm:h-16">
                                <div class="flex items-center gap-2 sm:gap-4">
                                    <!-- Sidebar Toggle Button -->
                                    <button id="sidebar-toggle" class="p-2 rounded-lg bg-zinc-800 hover:bg-zinc-700 transition-colors">
                                        <i data-lucide="menu" class="w-5 h-5 text-red-500"></i>
                                    </button>
                                    
                                    <!-- Sidebar Close Button (Desktop) -->
                                    <button id="sidebar-close" class="hidden md:block p-2 rounded-lg bg-zinc-800 hover:bg-zinc-700 transition-colors">
                                        <i data-lucide="x" class="w-5 h-5 text-red-500"></i>
                                    </button>
                                    
                                    <a class="flex items-center gap-1.5 sm:gap-2 text-base sm:text-lg md:text-2xl font-semibold md:font-bold text-red-500 hover:text-red-400 transition-colors" href="<?php echo e(route('admin.dashboard')); ?>">
                                        <i data-lucide="slack" class="w-4 h-4 sm:w-5 sm:h-5 md:w-7 md:h-7"></i>
                                        <span class="tracking-wider">Robin-BET</span>
                                    </a>
                                </div>
                                
                                <div class="flex items-center gap-2 sm:gap-3 md:gap-4">
                                    <!-- User Dropdown -->
                                    <div class="user-dropdown">
                                        <button id="user-dropdown-btn" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-zinc-800 hover:bg-zinc-700 transition-colors">
                                            <div class="w-8 h-8 rounded-full bg-red-500 flex items-center justify-center">
                                                <span class="text-black font-semibold text-sm">
                                                    <?php if(auth()->guard('yonetici')->check()): ?>
                                                        <?php echo e(strtoupper(substr(auth()->guard('yonetici')->user()->kullanici_adi, 0, 1))); ?>

                                                    <?php else: ?>
                                                        <?php echo e(strtoupper(substr(auth()->guard('admin')->user()->username, 0, 1))); ?>

                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                            <span class="text-sm font-medium text-white hidden sm:block">
                                                <?php if(auth()->guard('yonetici')->check()): ?>
                                                    <?php echo e(auth()->guard('yonetici')->user()->kullanici_adi); ?>

                                                <?php else: ?>
                                                    <?php echo e(auth()->guard('admin')->user()->username); ?>

                                                <?php endif; ?>
                                            </span>
                                            <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                                        </button>
                                        
                                        <div id="user-dropdown-menu" class="user-dropdown-menu">
                                            <div class="px-3 py-2 border-b border-gray-700">
                                                <div class="text-sm font-medium text-white">
                                                    <?php if(auth()->guard('yonetici')->check()): ?>
                                                        <?php echo e(auth()->guard('yonetici')->user()->kullanici_adi); ?>

                                                    <?php else: ?>
                                                        <?php echo e(auth()->guard('admin')->user()->username); ?>

                                                    <?php endif; ?>
                                                </div>
                                                <div class="text-xs text-gray-400">
                                                    <?php if(auth()->guard('yonetici')->check()): ?>
                                                        Yönetici
                                                    <?php else: ?>
                                                        Admin
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <a href="<?php echo e(route('admin.settings')); ?>" class="user-dropdown-item">
                                                <i data-lucide="settings" class="w-4 h-4"></i>
                                                Ayarlar
                                            </a>
                                            <a href="<?php echo e(route('admin.clear-cache')); ?>" class="user-dropdown-item" onclick="event.preventDefault(); document.getElementById('clear-cache-form').submit();">
                                                <i data-lucide="trash" class="w-4 h-4"></i>
                                                Cache Temizle
                                            </a>
                                            <form id="clear-cache-form" action="<?php echo e(route('admin.clear-cache')); ?>" method="POST" style="display: none;">
                                                <?php echo csrf_field(); ?>
                                            </form>
                                            <a href="<?php echo e(route('logout')); ?>" class="user-dropdown-item logout">
                                                <i data-lucide="log-out" class="w-4 h-4"></i>
                                                Çıkış Yap
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </nav>

                    <!-- Main Content Area -->
                    <div class="flex min-h-screen w-full bg-black">
                        <!-- Sidebar -->
                        <?php echo $__env->make('components.admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                        <!-- Main Content -->
                        <div class="flex-1 w-full transition-all duration-300" id="main-content">
                            <main class="p-4 md:p-6">
                                <!-- Success Messages -->
                                <?php if(session('success')): ?>
                                    <div class="mb-4 p-4 bg-green-500/10 border border-green-500/20 text-green-500 rounded-lg">
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="check-circle" class="w-5 h-5"></i>
                                            <span><?php echo e(session('success')); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Error Messages -->
                                <?php if(session('error')): ?>
                                    <div class="mb-4 p-4 bg-red-500/10 border border-red-500/20 text-red-500 rounded-lg">
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="x-circle" class="w-5 h-5"></i>
                                            <span><?php echo e(session('error')); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php echo $__env->yieldContent('content'); ?>
                            </main>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="/js/admin.js"></script>
    
    <script>
        // Initialize Lucide icons
        lucide.createIcons();
        
        // Sidebar toggle functionality
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebarClose = document.getElementById('sidebar-close');
        const sidebar = document.getElementById('admin-sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const mainContent = document.getElementById('main-content');
        
        function toggleSidebar() {
            if (sidebar && sidebarOverlay) {
                sidebar.classList.toggle('open');
                sidebarOverlay.classList.toggle('show');
                
                // Update main content margin on mobile
                if (window.innerWidth < 768) {
                    if (sidebar.classList.contains('open')) {
                        mainContent.style.marginLeft = '0';
                    } else {
                        mainContent.style.marginLeft = '0';
                    }
                }
            }
        }
        
        function closeSidebar() {
            if (sidebar && sidebarOverlay) {
                sidebar.classList.remove('open');
                sidebarOverlay.classList.remove('show');
                
                // Desktop'ta sidebar'ı kapat
                if (window.innerWidth >= 768) {
                    sidebar.classList.add('closed');
                    mainContent.classList.add('full-width');
                }
            }
        }
        
        function openSidebar() {
            if (sidebar && sidebarOverlay) {
                sidebar.classList.add('open');
                sidebarOverlay.classList.add('show');
                
                // Desktop'ta sidebar'ı aç
                if (window.innerWidth >= 768) {
                    sidebar.classList.remove('closed');
                    mainContent.classList.remove('full-width');
                }
            }
        }
        
        // Event listeners
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                if (window.innerWidth >= 768) {
                    // Desktop'ta toggle
                    if (sidebar.classList.contains('closed')) {
                        openSidebar();
                    } else {
                        closeSidebar();
                    }
                } else {
                    // Mobile'ta toggle
                    toggleSidebar();
                }
            });
        }
        
        if (sidebarClose) {
            sidebarClose.addEventListener('click', closeSidebar);
        }
        
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', closeSidebar);
        }
        
        // Close sidebar when clicking on menu items on mobile
        const sidebarLinks = sidebar?.querySelectorAll('a');
        if (sidebarLinks) {
            sidebarLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 768) {
                        closeSidebar();
                    }
                });
            });
        }
        
        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                sidebarOverlay.classList.remove('show');
                // Desktop'ta varsayılan olarak sidebar açık
                if (!sidebar.classList.contains('closed')) {
                    sidebar.classList.remove('open');
                    sidebar.classList.remove('closed');
                    mainContent.classList.remove('full-width');
                }
            } else {
                // Mobile'ta sidebar kapalı
                sidebar.classList.remove('open');
                sidebar.classList.remove('closed');
                mainContent.classList.remove('full-width');
            }
        });
        
        // User dropdown
        const userDropdownBtn = document.getElementById('user-dropdown-btn');
        const userDropdownMenu = document.getElementById('user-dropdown-menu');
        
        if (userDropdownBtn && userDropdownMenu) {
            userDropdownBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdownMenu.classList.toggle('show');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function() {
                userDropdownMenu.classList.remove('show');
            });
        }
    </script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html> <?php /**PATH /var/www/vhosts/robinbet777.com/httpdocs/resources/views/layouts/admin.blade.php ENDPATH**/ ?>