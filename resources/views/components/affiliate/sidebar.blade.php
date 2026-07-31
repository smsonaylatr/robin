<aside id="affiliate-sidebar" class="hidden md:block fixed left-0 top-14 h-[calc(100vh-56px)] w-64 border-r border-amber-500/20 bg-black/90 backdrop-blur-md z-50 md:z-10 md:bg-black/90">
	<div class="h-full flex flex-col">
		<div class="p-4 border-b border-amber-500/20">
			<div class="text-xs text-zinc-400">Hoş geldin</div>
			<div class="text-sm text-white font-semibold">{{ auth('admin')->user()->username ?? '' }}</div>
		</div>
		<nav class="flex-1 overflow-y-auto p-3 space-y-1">
			<a href="{{ route('affiliate.panel') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('affiliate.panel') ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'text-gray-300 hover:bg-zinc-800 hover:text-white' }}">
				<i data-lucide="layout-dashboard" class="w-4 h-4"></i>
				<span>Gösterge Paneli</span>
			</a>
			<a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-gray-500 cursor-not-allowed">
				<i data-lucide="users" class="w-4 h-4"></i>
				<span>Alt Üyeler</span>
			</a>
			<a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-gray-500 cursor-not-allowed">
				<i data-lucide="banknote" class="w-4 h-4"></i>
				<span>Komisyonlar</span>
			</a>
		</nav>
		<div class="p-4 border-t border-amber-500/20 text-xs text-zinc-400">&copy; {{ date('Y') }} Affiliate</div>
	</div>
</aside>


