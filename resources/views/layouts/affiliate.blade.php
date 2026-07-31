<!DOCTYPE html>
<html lang="tr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Affiliate Panel - @yield('title', 'Dashboard')</title>

	@php
		$settings = \App\Models\Ayarlar::getSettings();
		$faviconPath = $settings && $settings->favicon ? $settings->favicon : 'favicon.ico';
	@endphp
	<link rel="icon" type="image/x-icon" href="{{ asset($faviconPath) }}">

	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<script src="https://cdn.tailwindcss.com"></script>
	<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
	<link href="/css/admin.css" rel="stylesheet">

	<style>
		body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #000; color: #fff; }
		#affiliate-sidebar { width: 16rem; }
		#main-content { width: 100%; }
		@media (min-width: 768px) { #main-content { margin-left: 16rem; } }
	</style>

	@stack('styles')
</head>
<body class="bg-black text-white">
	<div class="fixed inset-0 bg-[url('/hero-pattern/background-effect.webp')] opacity-40 pointer-events-none"></div>
	<div class="flex min-h-screen flex-col">
		<!-- Top bar -->
		<nav class="fixed top-0 w-full z-50 bg-black/90 backdrop-blur-md border-b border-amber-500/30">
			<div class="max-w-7xl mx-auto px-3">
				<div class="h-14 flex items-center justify-between">
					<div class="flex items-center gap-2">
						<i data-lucide="link" class="w-5 h-5 text-amber-500"></i>
						<span class="text-amber-400 font-semibold tracking-wide">AFFILIATE PANEL</span>
					</div>
					<div class="flex items-center gap-2">
						<a href="{{ route('home') }}" class="px-3 py-1.5 rounded-lg text-sm bg-zinc-800 hover:bg-zinc-700 border border-zinc-600/40">Siteye Dön</a>
						<form action="{{ route('logout') }}" method="POST">
							@csrf
							<button class="px-3 py-1.5 rounded-lg text-sm bg-rose-600/20 text-rose-400 border border-rose-600/30 hover:bg-rose-600/30">
								Çıkış
							</button>
						</form>
					</div>
				</div>
			</div>
		</nav>

		<div class="flex pt-14 relative">
			@include('components.affiliate.sidebar')
			<div id="main-content" class="p-4 md:p-6 w-full relative z-10">
				@yield('content')
			</div>
		</div>
	</div>

	<script>
		lucide.createIcons();
	</script>

	@stack('scripts')
</body>
</html>


