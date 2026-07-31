<aside id="admin-sidebar" class="fixed left-0 top-14 z-40 h-screen w-64 transform -translate-x-full border-r border-red-500/20 bg-black/95 backdrop-blur-md transition-transform duration-300 ease-in-out md:translate-x-0 md:top-16">
    <div class="flex h-full flex-col">
        <!-- Sidebar Header -->
        <div class="flex h-14 items-center border-b border-red-500/20 px-4">
            <div class="flex items-center gap-2">
                <i data-lucide="shield" class="w-5 h-5 text-red-500"></i>
                <span class="text-sm font-semibold text-red-500">ADMIN PANEL</span>
            </div>
        </div>

        <!-- Sidebar Content -->
        <div class="flex-1 overflow-y-auto py-4">
            <nav class="space-y-1 px-3">
                <!-- Dashboard -->
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors <?php echo e(request()->routeIs('admin.dashboard') ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'text-gray-300 hover:bg-zinc-800 hover:text-white'); ?>">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Users Section -->
                <div class="space-y-1">
                    <div class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Kullanıcı Yönetimi</div>
                    
                    <a href="<?php echo e(route('admin.users')); ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors <?php echo e(request()->routeIs('admin.users') ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'text-gray-300 hover:bg-zinc-800 hover:text-white'); ?>">
                        <i data-lucide="users" class="w-4 h-4"></i>
                        <span>Oyuncular</span>
                    </a>
                    
                    <a href="<?php echo e(route('admin.affiliates')); ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors <?php echo e(request()->routeIs('admin.affiliates') ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'text-gray-300 hover:bg-zinc-800 hover:text-white'); ?>">
                        <i data-lucide="network" class="w-4 h-4"></i>
                        <span>Affialiteler</span>
                    </a>
                    
                    <a href="<?php echo e(route('admin.yoneticiler')); ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors <?php echo e(request()->routeIs('admin.yoneticiler') ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'text-gray-300 hover:bg-zinc-800 hover:text-white'); ?>">
                        <i data-lucide="shield-check" class="w-4 h-4"></i>
                        <span>Yöneticiler</span>
                    </a>
                </div>

                <!-- Financial Section -->
                <div class="space-y-1">
                    <div class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Finansal İşlemler</div>
                    
                    <a href="<?php echo e(route('admin.deposits')); ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors <?php echo e(request()->routeIs('admin.deposits') ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'text-gray-300 hover:bg-zinc-800 hover:text-white'); ?>">
                        <i data-lucide="trending-up" class="w-4 h-4"></i>
                        <span>Para Yatırma</span>
                    </a>
                    
                    <a href="<?php echo e(route('admin.withdrawals')); ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors <?php echo e(request()->routeIs('admin.withdrawals') ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'text-gray-300 hover:bg-zinc-800 hover:text-white'); ?>">
                        <i data-lucide="trending-down" class="w-4 h-4"></i>
                        <span>Para Çekme</span>
                    </a>
                    
                    <a href="<?php echo e(route('admin.transactions')); ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors <?php echo e(request()->routeIs('admin.transactions') ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'text-gray-300 hover:bg-zinc-800 hover:text-white'); ?>">
                        <i data-lucide="credit-card" class="w-4 h-4"></i>
                        <span>İşlemler</span>
                    </a>
                </div>

                <!-- Games Section -->
                <div class="space-y-1">
                    <div class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Oyun Yönetimi</div>
                    
                    <a href="<?php echo e(route('admin.providers')); ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors <?php echo e(request()->routeIs('admin.providers') ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'text-gray-300 hover:bg-zinc-800 hover:text-white'); ?>">
                        <i data-lucide="building" class="w-4 h-4"></i>
                        <span>Sağlayıcılar</span>
                    </a>
                    
                    <a href="<?php echo e(route('admin.games-list')); ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors <?php echo e(request()->routeIs('admin.games-list') ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'text-gray-300 hover:bg-zinc-800 hover:text-white'); ?>">
                        <i data-lucide="gamepad-2" class="w-4 h-4"></i>
                        <span>Oyunlar</span>
                    </a>
                </div>

                <!-- Content Section -->
                <div class="space-y-1">
                    <div class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">İçerik Yönetimi</div>
                    
                    <a href="<?php echo e(route('admin.banners')); ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors <?php echo e(request()->routeIs('admin.banners') ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'text-gray-300 hover:bg-zinc-800 hover:text-white'); ?>">
                        <i data-lucide="image" class="w-4 h-4"></i>
                        <span>Bannerlar</span>
                    </a>
                    
                    <a href="<?php echo e(route('admin.duyurular')); ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors <?php echo e(request()->routeIs('admin.duyurular') ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'text-gray-300 hover:bg-zinc-800 hover:text-white'); ?>">
                        <i data-lucide="megaphone" class="w-4 h-4"></i>
                        <span>Duyurular</span>
                    </a>
                    
                    <a href="<?php echo e(route('admin.bonuses')); ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors <?php echo e(request()->routeIs('admin.bonuses') ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'text-gray-300 hover:bg-zinc-800 hover:text-white'); ?>">
                        <i data-lucide="gift" class="w-4 h-4"></i>
                        <span>Bonuslar</span>
                    </a>
                    
                    <a href="<?php echo e(route('admin.promo-codes')); ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors <?php echo e(request()->routeIs('admin.promo-codes') ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'text-gray-300 hover:bg-zinc-800 hover:text-white'); ?>">
                        <i data-lucide="ticket" class="w-4 h-4"></i>
                        <span>Promosyon Kodları</span>
                    </a>
                    
                    <a href="<?php echo e(route('admin.tournaments')); ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors <?php echo e(request()->routeIs('admin.tournaments') ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'text-gray-300 hover:bg-zinc-800 hover:text-white'); ?>">
                        <i data-lucide="trophy" class="w-4 h-4"></i>
                        <span>Turnuvalar</span>
                    </a>
                    
                    <a href="<?php echo e(route('admin.home-sections')); ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors <?php echo e(request()->routeIs('admin.home-sections') ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'text-gray-300 hover:bg-zinc-800 hover:text-white'); ?>">
                        <i data-lucide="layout-grid" class="w-4 h-4"></i>
                        <span>Anasayfa Bölümleri</span>
                    </a>
                </div>

                <!-- System Section -->
                <div class="space-y-1">
                    <div class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Sistem</div>
                    
                    <a href="<?php echo e(route('admin.settings')); ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors <?php echo e(request()->routeIs('admin.settings') ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'text-gray-300 hover:bg-zinc-800 hover:text-white'); ?>">
                        <i data-lucide="settings" class="w-4 h-4"></i>
                        <span>Ayarlar</span>
                    </a>
                    
                    <a href="<?php echo e(route('admin.sms-send')); ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors <?php echo e(request()->routeIs('admin.sms-send') ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'text-gray-300 hover:bg-zinc-800 hover:text-white'); ?>">
                        <i data-lucide="message-circle" class="w-4 h-4"></i>
                        <span>SMS Gönder</span>
                    </a>
                    
                    <a href="<?php echo e(route('admin.payment-settings')); ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors <?php echo e(request()->routeIs('admin.payment-settings') ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'text-gray-300 hover:bg-zinc-800 hover:text-white'); ?>">
                        <i data-lucide="credit-card" class="w-4 h-4"></i>
                        <span>Ödeme Ayarları</span>
                    </a>
                    
                    <a href="<?php echo e(route('admin.payment-methods.index')); ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors <?php echo e(request()->routeIs('admin.payment-methods.*') ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'text-gray-300 hover:bg-zinc-800 hover:text-white'); ?>">
                        <i data-lucide="list" class="w-4 h-4"></i>
                        <span>Ödeme Yöntemleri</span>
                    </a>
                    
                    <a href="<?php echo e(route('admin.visual-settings')); ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors <?php echo e(request()->routeIs('admin.visual-settings') ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'text-gray-300 hover:bg-zinc-800 hover:text-white'); ?>">
                        <i data-lucide="images" class="w-4 h-4"></i>
                        <span>Görsel Ayarları</span>
                    </a>
                    
                    <a href="<?php echo e(route('admin.footer-payments')); ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors <?php echo e(request()->routeIs('admin.footer-payments') ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'text-gray-300 hover:bg-zinc-800 hover:text-white'); ?>">
                        <i data-lucide="credit-card" class="w-4 h-4"></i>
                        <span>Footer Payments</span>
                    </a>
                    
                    <a href="<?php echo e(route('admin.provider-photos')); ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors <?php echo e(request()->routeIs('admin.provider-photos') ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'text-gray-300 hover:bg-zinc-800 hover:text-white'); ?>">
                        <i data-lucide="building-2" class="w-4 h-4"></i>
                        <span>Footer Provider </span>
                    </a>
                    
                    <a href="<?php echo e(route('admin.logs')); ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors <?php echo e(request()->routeIs('admin.logs') ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'text-gray-300 hover:bg-zinc-800 hover:text-white'); ?>">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                        <span>Sistem Logları</span>
                    </a>
                    
                    <a href="<?php echo e(route('admin.backup')); ?>" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors <?php echo e(request()->routeIs('admin.backup') ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'text-gray-300 hover:bg-zinc-800 hover:text-white'); ?>">
                        <i data-lucide="database" class="w-4 h-4"></i>
                        <span>Yedekleme</span>
                    </a>
                </div>
            </nav>
        </div>

        <!-- Sidebar Footer -->
        <div class="border-t border-red-500/20 p-4">
            <div class="flex items-center gap-2 text-xs text-gray-400">
                <i data-lucide="shield-check" class="w-3 h-3 text-red-500"></i>
                <span>Güvenli Bağlantı</span>
            </div>
        </div>
    </div>
</aside>

<!-- Sidebar Overlay for Mobile -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-30 opacity-0 visibility-hidden transition-all duration-300 md:hidden"></div>

<style>
/* Sidebar styles */
#admin-sidebar {
    transform: translateX(-100%);
}

#admin-sidebar.open {
    transform: translateX(0);
}

/* Desktop sidebar */
@media (min-width: 768px) {
    #admin-sidebar {
        transform: translateX(0);
    }
    
    #admin-sidebar.closed {
        transform: translateX(-100%);
    }
}

/* Mobile sidebar */
@media (max-width: 767px) {
    #admin-sidebar {
        transform: translateX(-100%);
    }
    
    #admin-sidebar.open {
        transform: translateX(0);
    }
    
    #sidebar-overlay.show {
        opacity: 1;
        visibility: visible;
    }
}

/* Sidebar overlay */
#sidebar-overlay {
    opacity: 0;
    visibility: hidden;
}

#sidebar-overlay.show {
    opacity: 1;
    visibility: visible;
}
</style>
<?php /**PATH /var/www/vhosts/robinbet118.com/httpdocs/resources/views/components/admin/sidebar.blade.php ENDPATH**/ ?>