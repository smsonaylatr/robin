@extends('layouts.app')
@section('title', ($settings->site_adi ?? 'BetNow') . ' - Casino ve Spor Bahisleri')
@section('content')
<div class="max-w-[1400px] mx-auto">
    <div class="flex flex-col">
        <div class="relative w-full">
            <div class="relative w-full aspect-[2.5/1] bg-zinc-900 animate-pulse">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-8 h-8 md:w-12 md:h-12 border-4 border-yellow-500 border-t-transparent rounded-full animate-spin"></div>
                </div>
            </div>
        </div>
        <section class="py-4 md:py-8">
            <div class="px-4 space-y-8">
                @php $settings = \App\Models\Ayarlar::getSettings(); @endphp
                @if($settings && ($settings->bottom_banner_active ?? 0) == 1 && !empty($settings->bottom_banner_image))
                <div class="relative w-full rounded-xl overflow-hidden border border-zinc-800/60 shadow-sm">
                    @if(!empty($settings->bottom_banner_url))
                        <a href="{{ $settings->bottom_banner_url }}" target="_blank" rel="noopener" class="block">
                            <img src="{{ asset($settings->bottom_banner_image) }}" alt="Alt Banner" class="w-full h-auto object-cover"/>
                        </a>
                    @else
                        <img src="{{ asset($settings->bottom_banner_image) }}" alt="Alt Banner" class="w-full h-auto object-cover"/>
                    @endif
                </div>
                @php $bottomBannerBelowImages = \App\Models\BottomBannerImage::where('aktif',1)->orderBy('sira')->get(); @endphp
                @if($bottomBannerBelowImages->count() > 0)
                <div class="mt-3">
                    @php $settings = $settings ?? \App\Models\Ayarlar::getSettings(); @endphp
                    @if(($settings->bottom_below_mobile_grid ?? 0) == 1)
                        <div class="grid gap-2" style="grid-template-columns: repeat({{ $bottomBannerBelowImages->count() }}, minmax(0, 1fr));">
                            @foreach($bottomBannerBelowImages as $bb)
                                @if($bb->url)
                                    <a href="{{ $bb->url }}" target="_blank" rel="noopener" class="block">
                                        <img src="{{ asset($bb->gorsel) }}" alt="Alt Banner Altı" class="w-full h-auto rounded-lg border border-zinc-800/60"/>
                                    </a>
                                @else
                                    <img src="{{ asset($bb->gorsel) }}" alt="Alt Banner Altı" class="block w-full h-auto rounded-lg border border-zinc-800/60"/>
                                @endif
                            @endforeach
                        </div>
                        <style>
                            @media (max-width: 640px) {
                                .grid[style*="grid-template-columns"] {
                                    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                                }
                            }
                        </style>
                    @else
                        <div class="flex gap-2">
                            @php $__bbCount = max(1, $bottomBannerBelowImages->count()); $__gap = 8; @endphp
                            @foreach($bottomBannerBelowImages as $bb)
                                @php $__width = 'calc((100% - ' . ($__bbCount - 1) * $__gap . 'px)/' . $__bbCount . ')'; @endphp
                                @if($bb->url)
                                    <a href="{{ $bb->url }}" target="_blank" rel="noopener" class="block" style="width: {{ $__width }};">
                                        <img src="{{ asset($bb->gorsel) }}" alt="Alt Banner Altı" class="w-full h-auto rounded-lg border border-zinc-800/60"/>
                                    </a>
                                @else
                                    <img src="{{ asset($bb->gorsel) }}" alt="Alt Banner Altı" class="block w-full h-auto rounded-lg border border-zinc-800/60" style="width: {{ $__width }};"/>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
                @endif
                @endif
                <section class="relative w-full h-24 rounded-xl overflow-hidden border border-zinc-800/50 animate-pulse"></section>
                <div class="space-y-6">
                    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-zinc-900/50 via-zinc-900/30 to-transparent border border-zinc-800/50 p-6">
                        <div class="absolute inset-0 bg-[url('{{ asset('assets/noise.png') }}')] opacity-70"></div>
                        <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(235, 255, 0, 0.05), transparent, transparent);"></div>
                        <div class="relative flex items-center gap-4">
                            <div class="relative">
                                <div class="absolute inset-0 blur-xl rounded-full" style="background-color: rgba(235, 255, 0, 0.1);"></div>
                                <div class="relative p-3 rounded-xl" style="background: linear-gradient(to bottom right, rgba(235, 255, 0, 0.3), rgba(235, 255, 0, 0.1)); border: 1px solid rgba(235, 255, 0, 0.2);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dices w-6 h-6" style="color: #ebff00;" aria-hidden="true"><rect width="12" height="12" x="2" y="10" rx="2" ry="2"></rect><path d="m17.92 14 3.5-3.5a2.24 2.24 0 0 0 0-3l-5-4.92a2.24 2.24 0 0 0-3 0L10 6"></path><path d="M6 18h.01"></path><path d="M10 14h.01"></path><path d="M15 6h.01"></path><path d="M18 9h.01"></path></svg>
                                </div>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #ebff00, #ebff00, #ebff00); -webkit-background-clip: text; background-clip: text; color: transparent;">Popüler Slot Oyunları</h2>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                        @for($i = 0; $i < 12; $i++)
                        <div class="aspect-square rounded-2xl overflow-hidden">
                            <div class="w-full h-full animate-pulse bg-gradient-to-br from-zinc-900 to-zinc-800 relative">
                                <div class="absolute inset-0 bg-[url('{{ asset('assets/noise.png') }}')] opacity-70"></div>
                                <div class="absolute inset-0 flex flex-col items-center justify-center p-4">
                                    <div class="w-16 h-4 mb-3 bg-zinc-800 animate-pulse rounded-full"></div>
                                    <div class="w-24 h-10 bg-zinc-800 animate-pulse rounded-full"></div>
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
                <div class="space-y-4 sm:space-y-6">
                    <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-zinc-900/50 via-zinc-900/30 to-transparent  p-3 xs:p-4 sm:p-5 md:p-6">
                        <div class="absolute inset-0 bg-[url('{{ asset('assets/noise.png') }}')] opacity-70"></div>
                        <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(235, 255, 0, 0.05), transparent, transparent);"></div>
                        <div class="relative flex items-center justify-between gap-4">
                            <div class="flex items-center gap-2 xs:gap-3 sm:gap-4">
                                <div class="relative">
                                    <div class="absolute inset-0 blur-xl rounded-full" style="background-color: rgba(235, 255, 0, 0.1);"></div>
                                    <div class="relative p-2 xs:p-2.5 sm:p-3 rounded-xl shadow-md" style="background: linear-gradient(to bottom right, rgba(235, 255, 0, 0.3), rgba(235, 255, 0, 0.1)); border: 1px solid rgba(235, 255, 0, 0.2); box-shadow: 0 4px 6px rgba(235, 255, 0, 0.05);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dices w-4 h-4 xs:w-5 xs:h-5 sm:w-6 sm:h-6" style="color: #ebff00;" aria-hidden="true"><rect width="12" height="12" x="2" y="10" rx="2" ry="2"></rect><path d="m17.92 14 3.5-3.5a2.24 2.24 0 0 0 0-3l-5-4.92a2.24 2.24 0 0 0-3 0L10 6"></path><path d="M6 18h.01"></path><path d="M10 14h.01"></path><path d="M15 6h.01"></path><path d="M18 9h.01"></path></svg>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex items-center gap-1">
                                        <h2 class="text-base xs:text-lg sm:text-xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #ebff00, #ebff00, #ebff00); -webkit-background-clip: text; background-clip: text; color: transparent;">Popüler Canlı Casino</h2>
                                    </div>
                                </div>
                            </div>
                            <a href="/live-casino" class="group relative flex items-center justify-center gap-1.5 xs:gap-2 px-3 xs:px-4 sm:px-5 py-1.5 xs:py-2 sm:py-2.5 rounded-lg bg-gradient-to-r from-zinc-800/50 to-zinc-800/30 hover:from-zinc-800/80 hover:to-zinc-800/60 text-[11px] xs:text-xs sm:text-sm text-zinc-300 transition-all duration-300 border border-zinc-700/50 whitespace-nowrap" style="hover:color: #ebff00; hover:border-color: rgba(235, 255, 0, 0.2);" onmouseover="this.style.color='#ebff00'; this.style.borderColor='rgba(235, 255, 0, 0.2)';" onmouseout="this.style.color='rgb(161 161 170)'; this.style.borderColor='rgba(63, 63, 70, 0.5)';">
                                <span class="relative z-10">Tüm Oyunlar</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right w-3 h-3 xs:w-3.5 xs:h-3.5 sm:w-4 sm:h-4 transform group-hover:translate-x-1 transition-transform relative z-10" aria-hidden="true"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300" style="background: linear-gradient(to right, rgba(235, 255, 0, 0), rgba(235, 255, 0, 0.05), rgba(235, 255, 0, 0));"></div>
                            </a>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 xs:grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2 sm:gap-3">
                        @for($i = 0; $i < 6; $i++)
                        <div class="group relative h-28 sm:h-32 md:h-36 rounded-lg overflow-hidden bg-muted/40" style="opacity:0">
                            <div class="w-full h-full animate-pulse bg-gradient-to-br from-zinc-900 to-zinc-800"></div>
                            <div class="absolute inset-0 flex flex-col items-center justify-center p-2 sm:p-4">
                                <div class="w-16 h-4 mb-2 bg-zinc-800 animate-pulse rounded"></div>
                                <div class="w-20 h-8 bg-zinc-800 animate-pulse rounded-full"></div>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
            <div class="pt-6 md:pt-8 md:pb-8">
                <div class="max-w-[1400px] mx-auto">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-px flex-1 bg-gradient-to-r from-transparent via-red-500/50 to-transparent"></div>
                        <h2 class="text-xl md:text-2xl font-bold bg-gradient-to-r from-red-500 to-red-400 bg-clip-text text-transparent">Hızlı İletişim</h2>
                        <div class="h-px flex-1 bg-gradient-to-r from-transparent via-red-500/50 to-transparent"></div>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2 max-w-[1400px] mx-auto px-4"></div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection 