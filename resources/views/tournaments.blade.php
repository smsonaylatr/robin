@extends('layouts.app')

@section('title', 'Turnuvalar - ' . ($settings->site_adi ?? 'BetNow'))

@section('content')
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
            <div class="space-y-6">
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-zinc-900/50 via-zinc-900/30 to-transparent border border-zinc-800/50 p-6">
                    <div class="absolute inset-0 bg-[url('/assets/noise.png')] opacity-70"></div>
                    <div class="absolute inset-0 bg-gradient-to-r from-red-500/5 via-transparent to-transparent"></div>
                    <div class="relative flex items-center gap-4">
                        <div class="relative">
                            <div class="absolute inset-0 bg-red-500/10 blur-xl rounded-full"></div>
                            <div class="relative p-3 bg-gradient-to-br from-red-500/30 to-red-500/10 rounded-xl border border-red-500/20">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trophy w-6 h-6 text-red-500" aria-hidden="true">
                                    <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                                    <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                                    <path d="M4 22h16"></path>
                                    <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path>
                                    <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"></path>
                                    <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold bg-gradient-to-r from-red-500 via-red-400 to-red-500 bg-clip-text text-transparent">Turnuvalar</h2>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @for ($i = 0; $i < 6; $i++)
                    <div class="aspect-video rounded-2xl overflow-hidden">
                        <div class="w-full h-full animate-pulse bg-gradient-to-br from-zinc-900 to-zinc-800 relative">
                            <div class="absolute inset-0 bg-[url('/assets/noise.png')] opacity-70"></div>
                            <div class="absolute inset-0 flex flex-col items-center justify-center p-4">
                                <div class="w-16 h-4 mb-3 bg-zinc-800 animate-pulse rounded-full"></div>
                                <div class="w-24 h-10 bg-zinc-800 animate-pulse rounded-full"></div>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>
    </section>
</div>
@endsection 