<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="secure-token" content="{{ request()->get('secure_token') }}">
    <meta name="callback-username" content="{{ session('callback_username') }}">
    <meta name="callback-user-id" content="{{ session('callback_user_id') }}">
    <meta name="callback-agent-code" content="{{ session('callback_agent_code') }}">
    <title>UEFA Şampiyonlar Ligi - Spor Bahisleri ({{ session('api_agent_code') }})</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: "Inter", sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        
        /* Performance optimizations */
        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* GPU acceleration for animations */
        .mobile-betting-slip,
        .mobile-match-detail,
        .mobile-odds-btn {
            transform: translateZ(0);
            will-change: transform;
        }
        
        /* Optimize transitions */
        .transition-all {
            transition: all 0.15s ease-out;
        }
        
        /* Reduce paint operations */
        .bg-gradient-to-r {
            backface-visibility: hidden;
        }
        
        /* Mobile-first responsive design */
        .mobile-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: 100%;
            background: #1e1e1e;
        }
        
        /* Scrollbar for horizontal scroll */
        .scrollbar-thin::-webkit-scrollbar {
            height: 6px;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background-color: #4b5563;
            border-radius: 3px;
        }
        /* Hide scrollbar for Firefox */
        .scrollbar-thin {
            scrollbar-width: thin;
            scrollbar-color: #4b5563 transparent;
        }
        
        /* Mobile sports container - prevent wrapping */
        #mobile-sports {
            flex-wrap: nowrap !important;
            display: flex !important;
        }
        
        #mobile-sports > div {
            flex-wrap: nowrap !important;
            display: flex !important;
        }
        
        #mobile-sports button {
            flex-shrink: 0 !important;
            white-space: nowrap !important;
        }
        
        /* Mobile betting slip - Full Screen Beautiful Design */
        .mobile-betting-slip {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #2a2a2a 100%);
            transform: translateY(100%);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 100;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        
        .mobile-betting-slip.active {
            transform: translateY(0);
        }
        
        .mobile-slip-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }
        
        .mobile-slip-title {
            font-size: 16px;
            font-weight: 700;
            color: #d1d1d1;
        }
        
        .mobile-slip-close {
            background: none;
            border: none;
            color: #7a7a7a;
            font-size: 20px;
            cursor: pointer;
        }
        
        .mobile-slip-bets {
            margin-bottom: 16px;
        }
        
        .mobile-slip-bet {
            background: #3a3a3a;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 8px;
        }
        
        .mobile-slip-bet-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }
        
        .mobile-slip-bet-match {
            font-size: 14px;
            font-weight: 600;
            color: #d1d1d1;
        }
        
        .mobile-slip-bet-remove {
            background: #ff4444;
            border: none;
            border-radius: 4px;
            padding: 4px 8px;
            color: white;
            font-size: 12px;
            cursor: pointer;
        }
        
        .mobile-slip-bet-details {
            font-size: 12px;
            color: #7a7a7a;
            margin-bottom: 8px;
        }
        
        .mobile-slip-bet-odds {
            font-size: 14px;
            font-weight: 600;
            color: #f59e0b;
        }
        
        .mobile-slip-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-top: 1px solid #3a3a3a;
            margin-top: 12px;
        }
        
        .mobile-slip-amount {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .mobile-slip-amount input {
            background: #3a3a3a;
            border: 1px solid #4a4a4a;
            border-radius: 6px;
            padding: 8px 12px;
            color: #d1d1d1;
            font-size: 14px;
            width: 80px;
        }
        
        .mobile-slip-potential {
            text-align: right;
        }
        
        .mobile-slip-potential-label {
            font-size: 12px;
            color: #7a7a7a;
        }
        
        .mobile-slip-potential-amount {
            font-size: 16px;
            font-weight: 700;
            color: #f59e0b;
        }
        
        .mobile-slip-place-bet {
            width: 100%;
            background: #f59e0b;
            border: none;
            border-radius: 8px;
            padding: 16px;
            color: #1e1e1e;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s ease;
        }
        
        .mobile-slip-place-bet:hover {
            background: #e67e22;
        }
        
        .mobile-slip-place-bet:disabled {
            background: #7a7a7a;
            cursor: not-allowed;
        }
        
        /* Mobile betting slip - Full Screen Beautiful Design */
        .mobile-betting-slip {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #2a2a2a 100%);
            transform: translateY(100%);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 100;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        
        .mobile-betting-slip.active {
            transform: translateY(0);
        }
        
        .mobile-slip-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }
        
        .mobile-slip-title {
            font-size: 16px;
            font-weight: 700;
            color: #d1d1d1;
        }
        
        .mobile-slip-close {
            background: none;
            border: none;
            color: #7a7a7a;
            font-size: 20px;
            cursor: pointer;
        }
        
        .mobile-slip-bets {
            margin-bottom: 16px;
        }
        
        .mobile-slip-bet {
            background: #3a3a3a;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 8px;
        }
        
        .mobile-slip-bet-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }
        
        .mobile-slip-bet-match {
            font-size: 14px;
            font-weight: 600;
            color: #d1d1d1;
        }
        
        .mobile-slip-bet-remove {
            background: #ff4444;
            border: none;
            border-radius: 4px;
            padding: 4px 8px;
            color: white;
            font-size: 12px;
            cursor: pointer;
        }
        
        .mobile-slip-bet-details {
            font-size: 12px;
            color: #7a7a7a;
            margin-bottom: 8px;
        }
        
        .mobile-slip-bet-odds {
            font-size: 14px;
            font-weight: 600;
            color: #f59e0b;
        }
        
        .mobile-slip-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-top: 1px solid #3a3a3a;
            margin-top: 12px;
        }
        
        .mobile-slip-amount {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .mobile-slip-amount input {
            background: #3a3a3a;
            border: 1px solid #4a4a4a;
            border-radius: 6px;
            padding: 8px 12px;
            color: #d1d1d1;
            font-size: 14px;
            width: 80px;
        }
        
        .mobile-slip-potential {
            text-align: right;
        }
        
        .mobile-slip-potential-label {
            font-size: 12px;
            color: #7a7a7a;
        }
        
        .mobile-slip-potential-amount {
            font-size: 16px;
            font-weight: 700;
            color: #f59e0b;
        }
        
        .mobile-slip-place-bet {
            width: 100%;
            background: #f59e0b;
            border: none;
            border-radius: 8px;
            padding: 16px;
            color: #1e1e1e;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s ease;
        }
        
        .mobile-slip-place-bet:hover {
            background: #e67e22;
        }
        
        .mobile-slip-place-bet:disabled {
            background: #7a7a7a;
            cursor: not-allowed;
        }
        
        /* Mobile floating action button */
        .mobile-fab {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: linear-gradient(135deg, #f7931e 0%, #e67e22 100%);
            border: none;
            border-radius: 50%;
            width: 64px;
            height: 64px;
            color: #1e1e1e;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 8px 32px rgba(247, 147, 30, 0.4);
            z-index: 9998;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        .mobile-fab:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 12px 40px rgba(247, 147, 30, 0.5);
        }
        
        .mobile-fab:active {
            transform: scale(0.95);
        }
        
        .mobile-fab.has-bets {
            animation: pulse 2s infinite;
        }
        
        /* Pulse animation for FAB */
        @keyframes pulse {
            0% {
                box-shadow: 0 8px 32px rgba(247, 147, 30, 0.4);
            }
            50% {
                box-shadow: 0 8px 32px rgba(247, 147, 30, 0.6), 0 0 0 4px rgba(247, 147, 30, 0.2);
            }
            100% {
                box-shadow: 0 8px 32px rgba(247, 147, 30, 0.4);
            }
        }
        
        /* Desktop styles */
        @media (min-width: 1024px) {
            .mobile-container {
                display: none;
            }
            
            .desktop-container {
                display: flex;
                flex-direction: row;
                gap: 16px;
                padding: 16px;
                height: 100vh;
                max-width: 1920px;
                margin: 0 auto;
            }
        }

        /* Large screen optimizations */
        @media (min-width: 1440px) {
            .desktop-container {
                gap: 20px;
                padding: 20px;
            }
        }

        @media (min-width: 1920px) {
            .desktop-container {
                gap: 24px;
                padding: 24px;
            }
        }

        /* Ultra wide screen optimizations */
        @media (min-width: 2560px) {
            .desktop-container {
                max-width: 2400px;
                gap: 32px;
                padding: 32px;
            }
            
            .sidebar {
                min-width: 350px;
            }
            
            .main-content {
                min-width: 500px;
            }
            
            .odds-content {
                min-width: 600px;
            }
            
            /* Betting slip responsive width */
            aside[style*="font-feature-settings"] {
                min-width: 280px;
                max-width: 400px;
            }
            
            /* Sidebar overflow fix */
            .sidebar {
                overflow-x: hidden !important;
            }
            
            .sidebar .sport-toggle,
            .sidebar .country-btn {
                overflow: hidden;
            }
            
                    /* Selected odds visual feedback */
        .odds-btn.selected,
        button.selected {
            background: #f59e0b !important;
            color: #1e1e1e !important;
            border: 2px solid #f59e0b !important;
            box-shadow: 0 0 10px rgba(245, 158, 11, 0.5) !important;
            transform: scale(1.05);
            font-weight: bold !important;
        }
        
        .odds-btn.selected:hover,
        button.selected:hover {
            background: #e5890a !important;
            color: #1e1e1e !important;
            border-color: #e5890a !important;
        }
        
        /* Pulse animation for newly selected odds */
        .odds-btn.newly-selected,
        button.newly-selected {
            animation: pulseOrange 0.6s ease-in-out;
        }
        
        @keyframes pulseOrange {
            0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(245, 158, 11, 0); }
            100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
        }
        
        /* Mobile odds button selected styles */
        .mobile-odds-selected {
            background: #f59e0b !important;
            color: #1e1e1e !important;
            border: 2px solid #f59e0b !important;
            box-shadow: 0 0 8px rgba(245, 158, 11, 0.6) !important;
        }
        
        /* Mobile match detail odds buttons */
        .mobile-odds-btn.selected {
            background: #f59e0b !important;
            border-color: #f59e0b !important;
            box-shadow: 0 0 12px rgba(245, 158, 11, 0.6) !important;
            transform: scale(1.02);
        }
        
        .mobile-odds-btn.selected span {
            color: #1e1e1e !important;
            font-weight: bold !important;
        }
        
        .mobile-odds-btn.newly-selected {
            animation: mobileOddsPulse 0.6s ease-in-out;
        }
        
        @keyframes mobileOddsPulse {
            0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7); }
            70% { box-shadow: 0 0 0 8px rgba(245, 158, 11, 0); }
            100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
        }
        
        /* Selected match item styles */
        .match-item.text-\[#f59e0b\].bg-\[#3a3a3a\] {
            border-left: 4px solid #f59e0b !important;
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3) !important;
            transform: translateX(2px);
        }
        }

        /* 4K screen optimizations */
        @media (min-width: 3840px) {
            .desktop-container {
                max-width: 3200px;
            }
        }
        
        /* Hide desktop container on mobile */
        @media (max-width: 1023px) {
            .desktop-container {
                display: none !important;
            }
            
            /* Mobile footer responsive adjustments */
            #mobile-footer {
                padding-left: 8px;
                padding-right: 8px;
                z-index: 9999 !important;
            }
            
            #mobile-footer .text-xs {
                font-size: 10px;
            }
            
            #mobile-footer .text-xl {
                font-size: 18px;
            }
        }
        
        /* Extra small mobile adjustments */
        @media (max-width: 375px) {
            #mobile-footer .text-xs {
                font-size: 9px;
            }
            
            #mobile-footer .text-xl {
                font-size: 16px;
            }
            
            #mobile-footer .space-x-2 > * + * {
                margin-left: 4px;
            }
        }
        
        /* Custom Scrollbar Styles */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #2a2a2a;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #555;
            border-radius: 3px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #777;
        }
        
        /* Firefox scrollbar */
        * {
            scrollbar-width: thin;
            scrollbar-color: #555 #2a2a2a;
        }
        
        /* Live mode styles */
        .sidebar.live-mode {
            max-width: min(400px, 35vw);
        }
        
        .main-content.live-mode {
            display: none !important;
        }
        
        .odds-content.expanded-odds {
            flex: 1;
            width: auto;
            max-width: none;
        }
        
        .live-match-item:hover {
            background-color: #4a4a4a !important;
        }
        
        @media (min-width: 1024px) {
            .odds-content.expanded-odds {
                flex: 2;
                min-width: 50vw;
            }
        }

        @media (min-width: 1440px) {
            .odds-content.expanded-odds {
                flex: 3;
                min-width: 55vw;
            }
        }
        
        #sportradar-iframe-container {
            overflow: visible;
        }
        
        #sportradar-iframe-container img {
            max-height: 300px;
            object-fit: cover;
        }
        
        /* Live Match Iframe Overlay for Desktop */
        #live-iframe-overlay {
            position: absolute;
            top: 0;
            right: 0;
            width: 40%;
            height: 100%;
            background: transparent;
            border-radius: 8px;
            border: none;
            box-shadow: none;
            z-index: 1000;
            display: none;
        }
        
        /* Hide overlay on mobile and tablet */
        @media (max-width: 1023px) {
            #live-iframe-overlay {
                display: none !important;
            }
        }
        
        #live-iframe-overlay .overlay-content {
            height: 100%;
            padding: 0;
        }
        
        /* Responsive iframe overlay */
        @media (max-width: 1280px) {
            #live-iframe-overlay {
                width: 45%;
            }
        }
        
        @media (max-width: 1024px) {
            #live-iframe-overlay {
                width: 50%;
            }
        }
        
        #live-iframe-overlay iframe {
            width: 100%;
            height: 100%;
            border: none;
            border-radius: 0 0 6px 6px;
        }
        
        /* Mobile live page - Full Screen Beautiful Design */
        .mobile-live-page {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #2a2a2a 100%);
            transform: translateY(100%);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 50;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        
        .mobile-live-page.active {
            transform: translateY(0);
        }
        
        /* Enhanced Live Odds Modal Styles */
        #mobile-odds-modal {
            animation: modalFadeIn 0.3s ease-out;
        }
        
        #mobile-odds-modal .bg-\[#0a1220\] {
            animation: modalSlideUp 0.3s ease-out;
        }
        
        @keyframes modalFadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        @keyframes modalSlideUp {
            from {
                transform: translateY(20px) scale(0.95);
                opacity: 0;
            }
            to {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
        }
        
        /* Enhanced odds button animations */
        #mobile-odds-modal button {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        #mobile-odds-modal button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }
        
        #mobile-odds-modal button:active {
            transform: translateY(0);
        }
        
        /* Live indicator pulse animation */
        .animate-pulse {
            animation: livePulse 2s infinite;
        }
        
        @keyframes livePulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
        
        /* Enhanced scrollbar for modal */
        #mobile-odds-modal .overflow-y-auto::-webkit-scrollbar {
            width: 6px;
        }
        
        #mobile-odds-modal .overflow-y-auto::-webkit-scrollbar-track {
            background: #1a2a4a;
            border-radius: 3px;
        }
        
        #mobile-odds-modal .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #3a4a6a;
            border-radius: 3px;
        }
        
        #mobile-odds-modal .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #4a5a7a;
        }
    </style>
</head>
<body class="bg-[#111213] text-[#d1d1d1] min-h-screen">
    <!-- Mobile Container -->
    <div class="mobile-container">
        <div class="w-full p-3 space-y-3 bg-[#0b1422] min-h-screen">
            <!-- Back Button (hidden by default) -->
            <div class="flex items-center gap-2 mb-2" id="mobile-back-btn" style="display: none;">
                <button type="button" class="text-[#7a8dbd] hover:text-white" onclick="goBackToLeagues()">
                    <i class="fas fa-arrow-left text-lg"></i>
                </button>
                <span class="text-[#7a8dbd] text-sm font-semibold" id="mobile-back-text">Geri</span>
                        </div>
            


            <!-- Search and List View Section -->
            <div class="px-3 py-2">
                <div class="flex items-center gap-2">
                    <!-- Search Input -->
                    <div class="relative flex-1">
                        <input type="text" 
                               placeholder="Bir lig veya takım ara..." 
                               class="w-full bg-transparent text-white text-xs rounded-lg pl-4 pr-12 h-8 focus:outline-none focus:ring-1 focus:ring-[#1a2a4a] transition-all duration-200 placeholder-gray-400 border border-[#1a2a4a] hover:border-[#2a3a4a]" 
                               oninput="searchMobileMatches(event)" 
                               id="mobile-search-input" />
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center gap-1">
                            <button type="button" aria-label="Clear search" class="text-gray-400 hover:text-white transition-colors p-1" onclick="clearMobileSearch()">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                            <div class="w-px h-3 bg-gray-500"></div>
                            <i class="fas fa-search text-gray-400 text-xs"></i>
                        </div>
                        <!-- Search Results Dropdown -->
                        <div id="mobile-search-results" class="absolute top-full left-0 right-0 bg-[#0b1422] border border-[#1a2a4a] rounded-lg max-h-60 overflow-y-auto z-40 hidden shadow-lg mt-1">
                            <!-- Search results will be populated here -->
                        </div>
                    </div>
                    
                    <!-- List View Toggle Button - Modern Design -->
                    <button onclick="toggleMobileListView()" 
                            class="flex items-center gap-1.5 bg-[#0b1422] hover:bg-[#1a2a4a] rounded-lg h-8 px-2.5 border border-[#1a2a4a] transition-all duration-200 shrink-0 group"
                            id="list-toggle-btn">
                        <!-- Icon with background circle -->
                        <div class="w-5 h-5 rounded-full bg-[#1a2a4a] flex items-center justify-center transition-colors duration-200" id="icon-bg">
                            <i class="fas fa-list text-gray-400 text-[8px] transition-colors duration-200" id="list-view-icon"></i>
                        </div>
                        
                        <!-- Status indicator dot -->
                        <div class="w-1.5 h-1.5 rounded-full bg-gray-500 transition-colors duration-200" id="status-dot"></div>
                    </button>
                </div>
            </div>

            <!-- Sports categories -->
            <div id="mobile-sports" class="flex flex-nowrap space-x-1.5 overflow-x-auto scrollbar-thin no-scrollbar" style="scrollbar-width: thin; scrollbar-color: #4b5563 transparent">
                <!-- Sports will be populated dynamically -->
            </div>

            <!-- Leagues list -->
            <div class="space-y-1 text-xs font-normal" id="mobile-leagues-list">
                <!-- Leagues will be populated here -->
            </div>
            
            <!-- Matches list -->
            <div class="space-y-1.5" id="mobile-matches-list" style="display: none;">
                <!-- Matches will be populated here -->
            </div>
            
            <!-- Match Detail Page -->
            <div id="mobile-match-detail" style="display: none;">
                <!-- Match detail content will be populated here -->
            </div>
        </div>
        
        <!-- Mobile Betting Slip - Full Screen Beautiful Design -->
        <div class="mobile-betting-slip" id="mobile-betting-slip">
            <div class="w-full h-full bg-[#1f1f1f] text-white font-sans">
                <!-- Header with close button -->
                <div class="relative bg-[#2a2a2a]">
                    <!-- Close button - Modern design -->
                    <button aria-label="Close" 
                            class="absolute top-2 right-2 w-8 h-8 bg-red-500/20 hover:bg-red-500 rounded-full flex items-center justify-center text-red-400 hover:text-white focus:outline-none z-10 transition-all duration-300 border border-red-500/30 hover:border-red-500" 
                            onclick="closeMobileSlip()">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                    
                    <!-- Tabs -->
                    <div class="flex text-xs font-semibold">
                        <button class="mobile-slip-tab flex-1 bg-[#f7931e] py-2 text-center transition-all duration-300 active" type="button" onclick="switchMobileTab('coupon', this)">
                            BAHİS KUPONU
                        </button>
                        <button class="mobile-slip-tab flex-1 bg-[#3a3a3a] py-2 text-center transition-all duration-300" type="button" onclick="switchMobileTab('open', this)">
                            AÇIK BAHİSLER
                        </button>
                    </div>

                    <!-- Settings Row -->
                    <div id="mobile-settings-row" class="flex items-center justify-between px-3 py-2 border-b border-[#3a3a3a] text-sm">
                        <button class="flex items-center space-x-1 text-white w-full text-left">
                            <i class="fas fa-cog"></i>
                            <span>Daha yüksek oranları kabul et</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                    </div>

                    <!-- Bet Type Header -->
                    <div id="mobile-bet-type-header" class="flex items-center justify-between px-3 py-2 border-b border-[#3a3a3a] text-sm text-[#a0a0a0]">
                        <button class="flex items-center space-x-1">
                            <span>Kombine</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <button class="text-[#4a90e2] font-semibold text-xs" onclick="clearAllMobileBets()">Tümünü Kaldır</button>
                    </div>
                </div>
                
                <!-- Bet Items Container -->
                <div id="mobile-slip-bets" class="flex-1 overflow-y-auto divide-y divide-[#3a3a3a] text-xs">
                    <!-- Bets will be populated here -->
                </div>
                
                <!-- Open Bets Container -->
                <div id="mobile-open-content" class="flex-1 flex flex-col text-xs" style="display: none;">
                    <!-- Filter Buttons -->
                    <div class="px-3 py-2 border-b border-[#3a3a3a] bg-[#2a2a2a]">
                        <div class="flex gap-1 text-xs">
                            <button class="mobile-filter-btn flex-1 py-1 px-2 rounded text-center transition-all duration-200 bg-[#f7931e] text-black font-semibold active" 
                                    onclick="filterMobileBets('ongoing', this)" data-filter="ongoing">
                                Beklemede
                            </button>
                            <button class="mobile-filter-btn flex-1 py-1 px-2 rounded text-center transition-all duration-200 bg-[#3a3a3a] text-gray-300 hover:bg-[#4a4a4a]" 
                                    onclick="filterMobileBets('won', this)" data-filter="won">
                                Kazanan
                            </button>
                            <button class="mobile-filter-btn flex-1 py-1 px-2 rounded text-center transition-all duration-200 bg-[#3a3a3a] text-gray-300 hover:bg-[#4a4a4a]" 
                                    onclick="filterMobileBets('lost', this)" data-filter="lost">
                                Kaybeden
                            </button>
                        </div>
                    </div>
                    
                    <!-- Bets Container -->
                    <div class="flex-1 overflow-y-auto divide-y divide-[#3a3a3a]">
                        <div id="mobile-open-bets-container">
                    <!-- Open bets will be populated here -->
                        </div>
                    </div>
                    
                    <!-- Pagination -->
                    <div id="mobile-pagination" class="px-3 py-2 border-t border-[#3a3a3a] bg-[#2a2a2a] hidden">
                        <div class="flex justify-between items-center text-xs">
                            <button id="mobile-prev-btn" class="flex items-center gap-1 px-2 py-1 rounded bg-[#3a3a3a] text-gray-300 hover:bg-[#4a4a4a] transition-colors disabled:opacity-50 disabled:cursor-not-allowed" 
                                    onclick="changeMobilePage(-1)" disabled>
                                <i class="fas fa-chevron-left"></i>
                                Geri
                            </button>
                            
                            <div class="flex items-center gap-2">
                                <span id="mobile-page-info" class="text-gray-400">1 / 1</span>
                                <span class="text-gray-500">•</span>
                                <span id="mobile-total-info" class="text-gray-400">0 kupon</span>
                            </div>
                            
                            <button id="mobile-next-btn" class="flex items-center gap-1 px-2 py-1 rounded bg-[#3a3a3a] text-gray-300 hover:bg-[#4a4a4a] transition-colors disabled:opacity-50 disabled:cursor-not-allowed" 
                                    onclick="changeMobilePage(1)" disabled>
                                İleri
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Bet Button Section -->
                <div id="mobile-bet-button-section" class="bg-[#2a2a2a] border-t border-[#3a3a3a]">
                    <!-- Amount Input -->
                    <div class="px-3 py-2 text-xs flex flex-col space-y-2">
                        <label for="mobile-coupon-amount" class="font-semibold text-[#b0b0b0]">Kupon Tutarı</label>
                        <div class="flex space-x-2">
                            <input
                                id="mobile-coupon-amount"
                                type="number"
                                value="10"
                                placeholder="Kupon Tutarı"
                                class="flex-1 rounded bg-[#3a3a3a] border border-[#4a4a4a] px-3 py-1 text-white text-sm focus:outline-none focus:ring-2 focus:ring-[#f7931e]"
                                oninput="calculateTotalMobileWin()"
                            />
                            <button class="bg-[#4a4a4a] text-white font-semibold px-3 py-1 rounded text-sm hover:bg-[#5a5a5a] transition-colors duration-300" onclick="setMaxAmount()">MAKS</button>
                        </div>
                    </div>

                    <!-- Total Odds -->
                    <div class="px-3 py-1 border-t border-[#3a3a3a] text-xs flex justify-between items-center">
                        <span>Toplam Oran:</span>
                        <span class="text-[#f7b600] font-semibold text-right min-w-[60px]" id="mobile-total-odds">0.00</span>
                    </div>

                    <!-- Potential Win -->
                    <div class="px-3 py-1 border-t border-[#3a3a3a] text-xs flex justify-between items-center">
                        <span>Olası kazanç:</span>
                        <span class="text-[#3ecf4e] font-semibold text-right min-w-[60px]" id="mobile-total-win">0 ₺</span>
                    </div>

                    <!-- Place Bet Button -->
                    <button
                        disabled
                        class="w-full bg-[#3a3a3a] text-[#6a6a6a] text-xs font-semibold py-2 rounded-b cursor-not-allowed transition-all duration-300"
                        id="mobile-place-bet"
                        onclick="placeMobileBet()"
                    >
                        BAHİS YAP
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Live Betting Page -->
        <div class="mobile-live-page" id="mobile-live-page">
            <div class="w-full h-full bg-[#1e1e1e] text-white font-sans">
                <!-- Header with close button -->
                <div class="relative bg-[#2a2a2a]">
                    <!-- Close icon top right -->
                    <button aria-label="Close" class="absolute top-3 right-3 text-gray-400 hover:text-white focus:outline-none z-10 transition-colors duration-300" onclick="closeMobileLive()">
                        <i class="fas fa-times"></i>
                    </button>
                    
                    <!-- Header -->
                    <div class="text-center py-4">
                        <div class="flex items-center justify-center space-x-2 mb-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-[#1a7f1a] to-[#0f5e0f] rounded-full flex items-center justify-center">
                                <i class="fas fa-play text-white text-sm"></i>
                            </div>
                            <h1 class="text-xl font-bold text-white">Canlı Bahisler</h1>
                        </div>
                        <p class="text-gray-400 text-xs">Canlı maçları takip edin ve bahis yapın</p>
                    </div>
                    
                    <!-- Top bar -->
                    <div class="flex items-center space-x-2 px-4 pb-4">
                        <button class="flex items-center justify-center w-10 h-8 rounded border border-gray-600 text-gray-300 hover:bg-gray-700 transition-colors duration-300">
                            <i class="fas fa-video"></i>
                        </button>
                        <button class="flex items-center justify-center w-10 h-8 rounded border border-gray-600 text-gray-300 hover:bg-gray-700 transition-colors duration-300">
                            <i class="fas fa-globe"></i>
                        </button>
                        <div class="flex-1 relative">
                            <input type="text" placeholder="Canlı maç ara" class="w-full bg-[#3a3a3a] rounded border border-gray-600 text-gray-300 placeholder-gray-500 pl-3 pr-10 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#1a7f1a]" oninput="searchMobileLiveMatches(event)" />
                            <i class="fas fa-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Sports categories -->
                <div class="flex space-x-2 overflow-x-auto pb-2 px-4 bg-[#2a2a2a] border-b border-gray-700" id="mobile-live-sports">
                    <!-- Sports will be populated dynamically -->
                </div>
                
                <!-- Dropdown -->
                <div class="px-4 py-2 bg-[#2a2a2a] border-b border-gray-700">
                    <select class="bg-[#3a3a3a] text-gray-300 text-sm rounded px-3 py-2 w-full max-w-xs" id="mobile-live-market-select">
                        <option value="match_result">Maç Sonucu</option>
                        <option value="first_half">İlk Yarı Sonucu</option>
                        <option value="total_goals">Toplam Gol</option>
                    </select>
                </div>
                
                <!-- Live matches container -->
                <div class="flex-1 overflow-y-auto" id="mobile-live-matches">
                    <!-- Live matches will be populated here -->
                </div>
            </div>
        </div>
        
        <!-- Mobile Footer -->
        <div id="mobile-footer" class="fixed bottom-0 left-0 right-0 bg-[rgb(9,18,31)] border-t border-[#2a2a2a] z-[9999] lg:hidden">
            <div class="flex justify-between items-center px-4 py-2">
                <div class="flex items-center space-x-2 relative cursor-pointer hover:bg-[#2a2a2a] px-2 py-1 rounded transition-colors duration-200" onclick="openMobileLive()">
                    <i class="far fa-dot-circle text-gray-400 text-xl"></i>
                    <span class="text-gray-400 text-xs font-semibold tracking-widest">CANLI</span>
                    <div class="absolute -top-2 -right-2 bg-[#f7931e] text-[8px] font-bold text-black px-1 rounded select-none" id="mobile-live-count">0</div>
                </div>
                <div class="flex items-center space-x-2 relative cursor-pointer hover:bg-[#2a2a2a] px-2 py-1 rounded transition-colors duration-200" onclick="openMobileBulletin()">
                <i class="fas fa-stopwatch text-gray-400 text-xl"></i>
                    <span class="text-gray-400 text-xs font-semibold tracking-widest">BAHİS</span>
                    <div class="absolute -top-2 -right-2 bg-[#f7931e] text-[8px] font-bold text-black px-1 rounded select-none" id="mobile-bulletin-count">0</div>
                </div>
             
                <div class="flex items-center space-x-2 cursor-pointer hover:bg-[#2a2a2a] px-2 py-1 rounded transition-colors duration-200 relative" onclick="openMobileSlip()">
                    <i class="far fa-list-alt text-gray-400 text-xl"></i>
                    <span class="text-gray-400 text-xs font-semibold tracking-widest">BAHİS KUPONU</span>
                    <div class="absolute -top-2 -right-2 bg-[#f7931e] text-[8px] font-bold text-black px-1 rounded select-none" id="mobile-coupon-count">0</div>
                </div>
                <div class="flex items-center space-x-2 relative">
                    <i class="fas fa-ellipsis-v text-gray-400 text-xl"></i>
                    <div class="absolute -top-2 -right-2 bg-[#f7931e] text-[8px] font-bold text-black px-1 rounded select-none">1</div>
                </div>
            </div>
        </div>
        

    </div>
    
    <!-- Desktop Container -->
    <div class="desktop-container">
        <div class="flex flex-col lg:flex-row gap-2 lg:gap-4 w-full h-full min-h-0">
        <!-- Left Sidebar -->
        <aside class="flex flex-col gap-2 w-full lg:w-1/4 xl:w-1/5 2xl:w-1/6 lg:min-w-[280px] xl:min-w-[300px] bg-[#1a1a1a] border border-[#333] rounded-lg p-4 overflow-y-auto h-full order-1 lg:order-1 transition-all duration-300 sidebar shadow-lg" id="sidebar">
            <form class="flex gap-2 mb-3" onsubmit="searchMatches(event)">
                <div class="relative flex-grow">
                    <input id="search-input" class="w-full bg-transparent border border-[#404040] rounded-lg pl-3 pr-10 py-2.5 text-sm placeholder:text-[#8a8a8a] focus:outline-none focus:ring-2 focus:ring-[#f59e0b] focus:border-transparent text-white hover:border-[#555555] transition-all duration-200" placeholder="Lig veya takım ara..." type="text" oninput="searchMatches(event)"/>
                    <i class="fas fa-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                </div>
            </form>
            
            <div class="flex gap-2 mb-3">
                <button class="flex-grow bg-[#333] hover:bg-[#444] text-xs uppercase py-2.5 rounded-lg text-[#ccc] live-btn transition-all duration-200 font-medium" type="button" data-mode="live">
                    CANLI ({{ $sportsData['liveMatchCount'] ?? 0 }})
                </button>
                <button class="flex-grow bg-[#f59e0b] hover:bg-[#d97706] text-xs uppercase py-2.5 rounded-lg text-[#1a1a1a] font-semibold pre-match-btn transition-all duration-200" type="button" data-mode="pre-match">
                    MAÇ ÖNCESİ ({{ $sportsData['matches']->count() }})
                </button>
            </div>
            
            <!-- Live matches content (hidden by default) -->
            <div id="live-content" class="hidden">
                <!-- Video and Globe icons -->
                <div class="flex gap-2 mb-3">
                    <button class="flex items-center justify-center w-10 h-10 bg-[#333] border border-[#444] rounded-lg text-[#ccc] hover:bg-[#444] hover:text-white transition-all duration-200" type="button">
                        <i class="fas fa-video"></i>
                    </button>
                    <button class="flex items-center justify-center w-10 h-10 bg-[#333] border border-[#444] rounded-lg text-[#ccc] hover:bg-[#444] hover:text-white transition-all duration-200" type="button">
                        <i class="fas fa-globe"></i>
                    </button>
                </div>
                
                <!-- Live matches will be loaded here -->
                <div id="live-matches-container">
                    <div class="text-center text-gray-400 py-4">
                        <i class="fas fa-spinner fa-spin"></i>
                        <p class="mt-2">Canlı maçlar yüklenyor...</p>
                    </div>
                </div>
                
            </div>
            
            <!-- Pre-match content (visible by default) -->
            <div id="pre-match-content">
            
            <div class="relative mb-2">
                <button aria-expanded="false" aria-haspopup="listbox" class="flex items-center gap-2 bg-[#222] hover:bg-[#2a2a2a] border border-[#333] rounded-lg w-full px-3 py-2.5 text-xs text-[#ccc] hover:text-white font-semibold transition-all duration-200" type="button">
                    <i class="far fa-clock text-[#f59e0b]"></i>
                    TÜMÜ
                    <i class="fas fa-chevron-down ml-auto text-[#8a8a8a]"></i>
                </button>
            </div>
            
      
                                 
               
              
                 <!-- Sports separator line -->
                <div class="border-t border-[#333] my-3"></div>
                
                <!-- All Sports Display -->
                @foreach($sportsData['sports'] as $sport)
                    @php
                        $sportLower = strtolower($sport);
                        $isExpanded = $sport === 'futbol';
                    @endphp
                    
                    <button aria-controls="{{ $sportLower }}-submenu" aria-expanded="{{ $isExpanded ? 'true' : 'false' }}" class="flex justify-between items-center w-full bg-[#222] hover:bg-[#2a2a2a] rounded-lg px-3 py-2.5 mt-1 text-xs font-semibold text-[#ccc] hover:text-white cursor-pointer sport-toggle min-h-[40px] transition-all duration-200 border border-transparent hover:border-[#444]" type="button" data-sport="{{ $sport }}">
                        <div class="flex items-center gap-2 flex-1 min-w-0">
                            @php 
                                $decodedSport = html_entity_decode($sport, ENT_QUOTES, 'UTF-8');
                                $sportIcon = strtolower(str_replace(' ', '', $decodedSport)); 
                            @endphp
                            @switch($sportIcon)
                                @case('futbol')
                                    <i class="fas fa-futbol text-green-500 flex-shrink-0"></i>
                                    @break
                                @case('basketbol')
                                    <i class="fas fa-basketball-ball text-orange-500 flex-shrink-0"></i>
                                    @break
                                @case('tenis')
                                    <i class="fas fa-table-tennis text-green-400"></i>
                                    @break
                                @case('voleybol')
                                    <i class="fas fa-volleyball-ball text-blue-400"></i>
                                    @break
                                @case('beysbol')
                                    <i class="fas fa-baseball-ball text-red-400"></i>
                                    @break
                                @case('buzhokeyi')
                                    <i class="fas fa-hockey-puck text-cyan-400"></i>
                                    @break
                                @case('beachvolley')
                                    <i class="fas fa-volleyball-ball text-yellow-400"></i>
                                    @break
                                @case('atletizm')
                                    <i class="fas fa-running text-purple-400"></i>
                                    @break
                                @case('badminton')
                                    <i class="fas fa-table-tennis text-pink-400"></i>
                                    @break
                                @case('rugbyleague')
                                @case('ragbi')
                                @case('australianrules')
                                    <i class="fas fa-football-ball text-amber-600"></i>
                                    @break
                                @case('golf')
                                    <i class="fas fa-flag text-green-500"></i>
                                    @break
                                @case('amerikanfutbolu')
                                    <i class="fas fa-football-ball text-red-600"></i>
                                    @break
                                @case('cricket')
                                    <i class="fas fa-baseball-ball text-green-600"></i>
                                    @break
                                @case('snooker')
                                @case('masatenisi')
                                    <i class="fas fa-table-tennis text-red-500"></i>
                                    @break
                                @case('virtualsports')
                                    <i class="fas fa-gamepad text-purple-500"></i>
                                    @break
                                @case('darts')
                                    <i class="fas fa-dot-circle text-red-600"></i>
                                    @break
                                @case('bisiklet')
                                    <i class="fas fa-bicycle text-blue-500"></i>
                                    @break
                                @case('wintersports')
                                    <i class="fas fa-snowflake text-cyan-300"></i>
                                    @break
                                @case('hentbol')
                                    <i class="fas fa-basketball-ball text-orange-400"></i>
                                    @break
                                @case('formula1')
                                    <i class="fas fa-car-side text-red-500"></i>
                                    @break
                                @case('trotting')
                                    <i class="fas fa-horse text-amber-600"></i>
                                    @break
                                @default
                                    <i class="fas fa-trophy text-[#f5b942] flex-shrink-0"></i>
                            @endswitch
                            <span class="{{ $isExpanded ? 'text-white font-semibold' : 'text-[#7a7a7a]' }} truncate">{{ ucfirst($decodedSport) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[#7a7a7a]">
                                {{ $sportsData['sportCounts'][$sport] ?? 0 }}
                            </span>
                            <i class="fas fa-chevron-{{ $isExpanded ? 'up' : 'down' }} {{ $isExpanded ? 'text-green-500' : 'text-[#7a7a7a]' }}"></i>
                        </div>
                    </button>
                    
                    <!-- Avrupa section style container -->
                    <div class="bg-[#222] border border-[#333] rounded-lg mt-1 p-3 space-y-1 text-xs text-[#ccc] {{ $isExpanded ? 'block' : 'hidden' }}" id="{{ $sportLower }}-submenu">
                        @if(isset($sportsData['sportCountries'][$sport]))
                            @foreach($sportsData['sportCountries'][$sport] as $country)
                                <button class="flex justify-between items-center w-full cursor-pointer hover:bg-[#333] rounded-lg px-2 py-1.5 country-btn transition-all duration-200" type="button" data-country="{{ $country }}" data-sport="{{ $sport }}">
                                    <div class="flex items-center gap-2 flex-1 min-w-0">
                                        <img alt="{{ $country }} bayrağı simgesi" class="w-5 h-3 object-cover rounded-sm flex-shrink-0" height="12" src="/images/flags/default.png" width="20" data-country="{{ $country }}" onerror="this.src='/images/flags/defaults.png'"/>
                                        <span class="truncate">{{ $country }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span>{{ $sportsData['sportLeagues'][$sport]->get($country, collect())->count() }}</span>
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </button>
                                
                                @if($sportsData['sportLeagues'][$sport]->has($country))
                                    <div class="country-leagues space-y-1" data-country="{{ $country }}" data-sport="{{ $sport }}" style="display: none;">
                                        @foreach($sportsData['sportLeagues'][$sport][$country] as $league)
                                            <button class="w-full bg-[#333] hover:bg-[#444] rounded-lg text-[#ccc] hover:text-white text-left px-2 py-1.5 text-xs font-normal league-btn transition-all duration-200" type="button" data-league="{{ $league->lig_isim }}" data-sport="{{ $sport }}">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-2">
                                                        <i class="fas fa-trophy text-[#f59e0b] text-xs"></i>
                                                        <span>{{ html_entity_decode($league->lig_isim, ENT_QUOTES, 'UTF-8') }}</span>
                                                    </div>
                                                    <span class="text-[#7a7a7a]">
                                                        {{ $sportsData['matches']->where('lig_isim', $league->lig_isim)->where('tur', $sport)->count() }}
                                                    </span>
                                                </div>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                                
                                <!-- Country separator line -->
                                @if(!$loop->last)
                                    <div class="border-t border-[#333]/50 my-1"></div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                    
                    <!-- Sports separator line -->
                    @if(!$loop->last)
                        <div class="border-t border-[#3a3a3a]/60 my-1"></div>
                    @endif
                @endforeach
            </nav>
            </div> <!-- End pre-match-content -->
        </aside>
        
        <!-- Middle Content -->
        <main class="flex flex-col w-full lg:w-1/2 xl:w-1/2 2xl:w-2/5 bg-[#1a1a1a] border border-[#333] rounded-lg p-4 h-full overflow-y-auto order-2 lg:order-2 transition-all duration-300 main-content shadow-lg" id="main-content">
            <header class="flex items-center gap-3 mb-3 text-sm font-semibold border-b border-[#333] pb-3">
                <i class="fas fa-star text-[#f59e0b]"></i>
                <img alt="Avrupa bayrağı simgesi" class="w-5 h-5 rounded-sm" height="20" src="https://storage.googleapis.com/a1aa/image/9f028405-acd8-4c35-df42-15618c2ad0ce.jpg" width="20"/>
                <span id="main-header-title" class="text-white">Futbol</span>
                <span class="ml-auto flex items-center gap-2 text-xs text-[#ccc]">
                    <span class="text-[#8a8a8a]">Çoklu sütunlu</span>
                    <label class="relative inline-flex items-center cursor-pointer" for="toggle-columns">
                        <input checked="" class="sr-only peer" id="toggle-columns" type="checkbox"/>
                        <div class="w-11 h-6 bg-[#333] border border-[#444] rounded-full peer peer-checked:bg-[#f59e0b] peer-checked:border-[#f59e0b] peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                    </label>
                    <div class="flex gap-1">
                        <i class="fas fa-list-ul text-[#8a8a8a] hover:text-[#f59e0b] cursor-pointer transition-colors"></i>
                        <i class="fas fa-list text-[#8a8a8a] hover:text-[#f59e0b] cursor-pointer transition-colors"></i>
                    </div>
                </span>
            </header>
            
            <section class="text-sm font-semibold mb-3 border-b border-[#333] pb-2 text-[#f59e0b]">
                <i class="fas fa-calendar-alt mr-2"></i>{{ date('d.m.Y') }}
            </section>
            
            <section id="match-list">
            
                
                @foreach($sportsData['matches'] as $index => $match)
                <div class="bg-[#2a2a2a] rounded-md px-3 py-2 flex justify-between items-center mb-2 match-item {{ strtolower(trim($match->tur ?? '')) !== 'futbol' ? 'hidden' : '' }}" data-match-id="{{ $index + 1 }}" data-league="{{ $match->lig_isim }}" data-sport="{{ $match->tur }}">
                    <div class="flex items-center space-x-2">
                        <div class="flex flex-col items-center space-y-1">
                            <button aria-label="Favorite {{ html_entity_decode($match->evsahibi_isim, ENT_QUOTES, 'UTF-8') }} vs {{ html_entity_decode($match->misafir_isim, ENT_QUOTES, 'UTF-8') }}" class="text-[#d9d9d9] hover:text-yellow-400 focus:outline-none">
                                <i class="far fa-star text-xs"></i>
                            </button>
                            <span class="text-[10px] text-[#999999] select-none">
                                {{ $match->baslangic->format('H:i') }}
                            </span>
                        </div>
                        <div>
                            <p class="text-xs font-normal leading-4 text-white">
                                {{ html_entity_decode($match->evsahibi_isim, ENT_QUOTES, 'UTF-8') }}
                            </p>
                            <p class="text-xs font-normal leading-4 text-white">
                                {{ html_entity_decode($match->misafir_isim, ENT_QUOTES, 'UTF-8') }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-1 ml-2">
                        <button class="w-10 h-7 {{ ($match->oran1 ?? 0) > 0 ? 'bg-[#555555] text-[#d9b24a] hover:bg-[#666666]' : 'bg-[#2a2a2a] text-gray-600 cursor-not-allowed' }} rounded-md font-normal text-xs flex items-center justify-center transition-colors" 
                                type="button" 
                                @if(($match->oran1 ?? 0) > 0) onclick="addBet('1', {{ $match->oran1 ?? 2.0 }}, '{{ $index + 1 }}', 'Maç Sonucu', 'Evsahibi')" @else disabled @endif>
                            @if(($match->oran1 ?? 0) > 0)
                                {{ number_format($match->oran1 ?? 2.0, 2) }}
                            @else
                                <i class="fas fa-lock"></i>
                            @endif
                        </button>
                        <button class="w-10 h-7 {{ ($match->oran0 ?? 0) > 0 ? 'bg-[#555555] text-[#d9b24a] hover:bg-[#666666]' : 'bg-[#2a2a2a] text-gray-600 cursor-not-allowed' }} rounded-md font-normal text-xs flex items-center justify-center transition-colors" 
                                type="button" 
                                @if(($match->oran0 ?? 0) > 0) onclick="addBet('X', {{ $match->oran0 ?? 3.0 }}, '{{ $index + 1 }}', 'Maç Sonucu', 'Berabere')" @else disabled @endif>
                            @if(($match->oran0 ?? 0) > 0)
                                {{ number_format($match->oran0 ?? 3.0, 2) }}
                            @else
                                <i class="fas fa-lock"></i>
                            @endif
                        </button>
                        <button class="w-10 h-7 {{ ($match->oran2 ?? 0) > 0 ? 'bg-[#555555] text-[#d9b24a] hover:bg-[#666666]' : 'bg-[#2a2a2a] text-gray-600 cursor-not-allowed' }} rounded-md font-normal text-xs flex items-center justify-center transition-colors" 
                                type="button" 
                                @if(($match->oran2 ?? 0) > 0) onclick="addBet('2', {{ $match->oran2 ?? 3.5 }}, '{{ $index + 1 }}', 'Maç Sonucu', 'Deplasman')" @else disabled @endif>
                            @if(($match->oran2 ?? 0) > 0)
                                {{ number_format($match->oran2 ?? 3.5, 2) }}
                            @else
                                <i class="fas fa-lock"></i>
                            @endif
                        </button>
                        <span class="text-[#999999] text-[10px] font-normal ml-1 select-none w-8 text-center">
                            +{{ $match->oran_adet ?? 0 }}
                        </span>
                        <i class="fas fa-chevron-right text-[#d9d9d9] ml-1"></i>
                    </div>
                </div>
                @endforeach
            </section>
            
        </main>
        
        <!-- Right Content -->
        <section class="flex flex-col w-full lg:w-[30%] xl:w-[30%] 2xl:w-[30%] bg-[#2a2a2a] rounded-md p-3 h-full overflow-y-auto order-3 lg:order-3 odds-content">
            <header class="relative mb-2 rounded-md overflow-visible">
                <!-- Sportradar iframe container -->
                <div id="sportradar-iframe-container" class="relative">
                    <img alt="Futbol sahası" class="w-full h-auto object-cover" src="/images/soccer.png" width="480"/>
                    
                    <!-- Match Info Overlay -->
                    <div id="match-info-overlay" class="absolute bottom-2 left-2 text-white font-semibold text-sm bg-[#000000b3] rounded px-3 py-2" style="display: none;">
                        <div id="match-teams" class="text-white font-semibold text-sm"></div>
                        <div id="match-details" class="text-gray-300 text-xs mt-1"></div>
                    </div>
                    
                    <!-- Live Match Iframe Overlay for Desktop -->
                    <div id="live-iframe-overlay">
                        <div class="overlay-content">
                            <!-- Iframe will be loaded here -->
                        </div>
                    </div>
                </div>
                <div class="absolute top-2 left-2 flex flex-col gap-1 text-xs text-[#d1d1d1] bg-[#000000b3] rounded px-3 py-2 min-w-[200px]">
                    <!-- League name at top -->
                    <div class="flex items-center gap-2">
                        <i class="fas fa-futbol" id="selected-sport-icon"></i>
                        <img alt="Avrupa bayrağı simgesi" class="w-4 h-4" height="16" src="https://storage.googleapis.com/a1aa/image/9f028405-acd8-4c35-df42-15618c2ad0ce.jpg" width="16" id="selected-country-flag"/>
                        <span id="selected-league-title" class="font-semibold">Futbol Maçları</span>
                    </div>
                    <!-- Match date/time in middle -->
                    <div class="text-[#a0a0a0] text-[10px]" id="selected-match-date">
                        {{ date('d.m.Y, H:i') }}
                    </div>
                </div>
            </header>
            
            <nav class="flex items-center gap-2 text-xs font-semibold border-b border-[#3a3a3a] pb-1 mb-2">
                <button class="flex items-center gap-1 border-b-2 border-[#f59e0b] text-[#f59e0b] font-semibold market-filter" type="button" data-filter="tumu">
                    <i class="far fa-star"></i>
                    TÜMÜ
                    <sup class="text-xs text-[#6b6b6b]" id="tumu-count"></sup>
                </button>
                <button class="flex items-center gap-1 text-[#6b6b6b] hover:text-[#f59e0b] market-filter" type="button" data-filter="altust">
                    Alt/Üst
                    <sup class="text-xs text-[#6b6b6b]" id="altust-count"></sup>
                </button>
                <button class="flex items-center gap-1 text-[#6b6b6b] hover:text-[#f59e0b] market-filter" type="button" data-filter="ilkyari">
                    İlk Yarı
                    <sup class="text-xs text-[#6b6b6b]" id="ilkyari-count"></sup>
                </button>
                <button class="flex items-center gap-1 text-[#6b6b6b] hover:text-[#f59e0b] market-filter" type="button" data-filter="handikap">
                    Handikap
                    <sup class="text-xs text-[#6b6b6b]" id="handikap-count"></sup>
                </button>
            </nav>
            
            <section class="text-xs font-semibold mb-2 border-b border-[#3a3a3a] pb-1">
                Marketler
            </section>
            
            <section class="flex-1 min-h-0">
                <div class="overflow-y-auto scrollbar-thin scrollbar-thumb-[#555] scrollbar-track-[#2a2a2a] h-full" id="kazanan-submenu">
                    <!-- Odds will be loaded dynamically via JavaScript -->
                    <div class="text-center text-xs text-[#6b6b6b] py-8">
                        <i class="fas fa-mouse-pointer text-2xl mb-2 block"></i>
                        Oranları görmek için bir maç seçin
                    </div>
                </div>
            </section>
        </section>
        
        
        <!-- Right Sidebar - Betting Slip -->
        <aside class="flex flex-col w-full lg:w-1/4 xl:w-1/5 2xl:w-1/4 bg-[#2a2a2a] rounded-md text-white font-sans select-none h-full overflow-visible order-4 lg:order-4" style="font-feature-settings: 'tnum';">
            
            <!-- Header Tabs -->
            <div class="flex text-xs font-semibold border-b border-[#f59e0b] bg-[#2a2a2a]">
                <button class="desktop-slip-tab flex-1 bg-[#f59e0b] text-black py-2 text-center transition-all duration-300 active" type="button" onclick="switchDesktopTab('coupon', this)">
                BAHİS KUPONU
                </button>
                <button class="desktop-slip-tab flex-1 bg-[#3a3a3a] text-gray-300 py-2 text-center transition-all duration-300" type="button" onclick="switchDesktopTab('open', this)">
                    AÇIK BAHİSLER
                </button>
            </div>

            <!-- Coupon Content -->
            <div id="desktop-coupon-content" class="flex-1 flex flex-col">
            <!-- Settings and Dropdown -->
            <div class="flex items-center gap-2 px-3 py-2 border-b border-[#3a3a3a] bg-[#2a2a2a]">
                <i class="fas fa-cog text-gray-400 text-base"></i>
                <button type="button" class="flex items-center gap-1 text-gray-300 text-xs font-normal focus:outline-none">
                    Yükselen Oranı Kabul Et
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
            </div>

            <!-- Combine and Clear All -->
            <div class="flex justify-between items-center px-3 py-2 border-b border-[#3a3a3a] bg-[#2a2a2a]">
                <button type="button" class="flex items-center gap-1 text-gray-300 text-xs font-normal focus:outline-none">
                    Kombine
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
                <button type="button" class="text-xs text-gray-400 underline hover:text-[#f59e0b] font-normal focus:outline-none" onclick="clearAllBets()">
                    Tümünü Kaldır
                </button>
            </div>

            <!-- Bet Items Container -->
            <div class="flex-1 min-h-0 overflow-y-auto bg-[#2a2a2a] scrollbar-thin scrollbar-thumb-[#f59e0b] scrollbar-track-transparent" id="bet-slip-content">
                <div id="empty-slip" class="text-center text-xs font-semibold py-8 text-[#6b6b6b]">
                    <i class="fas fa-ticket-alt text-2xl mb-2 block"></i>
                    Bahis kuponunuz boş
                    <div class="text-[10px] mt-1">Maç seçip oran üzerine tıklayın</div>
                </div>
                <div id="selected-bets" class="flex flex-col gap-2 px-3 py-3" style="display: none;"></div>
            </div>

            <!-- Total Odds and Input -->
            <div id="bet-summary" class="px-3 py-2 bg-[#2a2a2a] border-t border-[#3a3a3a]" style="display: none;">
                <div class="flex justify-between items-center mb-2 text-xs font-semibold text-white">
                    <span>Toplam Oran</span>
                    <span class="text-[#f59e0b]" id="total-odds">1.00</span>
                </div>
                <input type="text" placeholder="Bahis Miktarı Girin" class="w-full rounded-md bg-[#3a3a3a] border border-gray-600 text-white text-xs placeholder:text-gray-400 py-2 px-3 focus:outline-none focus:ring-1 focus:ring-[#f59e0b]" inputmode="numeric" pattern="[0-9]*" id="bet-amount-input" />
                <div class="mt-2 text-xs font-semibold text-green-600">Olası kazanç: <span id="estimated-winnings">0 ₺</span></div>
                </div>
            </div>

            <!-- Open Bets Content -->
            <div id="desktop-open-content" class="flex-1 flex flex-col hidden">
                <!-- Filter Buttons -->
                <div class="px-3 py-2 border-b border-[#3a3a3a] bg-[#2a2a2a]">
                    <div class="flex gap-1 text-xs">
                        <button class="desktop-filter-btn flex-1 py-1 px-2 rounded text-center transition-all duration-200 bg-[#f59e0b] text-black font-semibold active" 
                                onclick="filterDesktopBets('ongoing', this)" data-filter="ongoing">
                            Beklemede
                        </button>
                        <button class="desktop-filter-btn flex-1 py-1 px-2 rounded text-center transition-all duration-200 bg-[#3a3a3a] text-gray-300 hover:bg-[#4a4a4a]" 
                                onclick="filterDesktopBets('won', this)" data-filter="won">
                            Kazanan
                        </button>
                        <button class="desktop-filter-btn flex-1 py-1 px-2 rounded text-center transition-all duration-200 bg-[#3a3a3a] text-gray-300 hover:bg-[#4a4a4a]" 
                                onclick="filterDesktopBets('lost', this)" data-filter="lost">
                            Kaybeden
                        </button>
                    </div>
                </div>
                
                <!-- Bets Container -->
                <div class="flex-1 min-h-0 overflow-y-auto bg-[#2a2a2a] scrollbar-thin scrollbar-thumb-[#f59e0b] scrollbar-track-transparent">
                    <div id="desktop-open-bets-container">
                        <!-- Open bets will be loaded here -->
                        <div class="text-center text-xs font-semibold py-8 text-[#6b6b6b]">
                            <i class="fas fa-spinner fa-spin text-2xl mb-2 block"></i>
                            Açık bahisler yükleniyor...
                        </div>
                    </div>
                </div>
                
                <!-- Pagination -->
                <div id="desktop-pagination" class="px-3 py-2 border-t border-[#3a3a3a] bg-[#2a2a2a] hidden">
                    <div class="flex justify-between items-center text-xs">
                        <button id="desktop-prev-btn" class="flex items-center gap-1 px-2 py-1 rounded bg-[#3a3a3a] text-gray-300 hover:bg-[#4a4a4a] transition-colors disabled:opacity-50 disabled:cursor-not-allowed" 
                                onclick="changeDesktopPage(-1)" disabled>
                            <i class="fas fa-chevron-left"></i>
                            Geri
                        </button>
                        
                        <div class="flex items-center gap-2">
                            <span id="desktop-page-info" class="text-gray-400">1 / 1</span>
                            <span class="text-gray-500">•</span>
                            <span id="desktop-total-info" class="text-gray-400">0 kupon</span>
                        </div>
                        
                        <button id="desktop-next-btn" class="flex items-center gap-1 px-2 py-1 rounded bg-[#3a3a3a] text-gray-300 hover:bg-[#4a4a4a] transition-colors disabled:opacity-50 disabled:cursor-not-allowed" 
                                onclick="changeDesktopPage(1)" disabled>
                            İleri
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- User Info -->
            <div class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-gray-300 border-t border-[#3a3a3a]">
                <i class="fas fa-user"></i>
                <span>{{ session('username') ? session('username') : (session('agent_code') ? session('agent_code') : 'API Kullanıcısı') }}</span>
            </div>

            <!-- Quick Amount Buttons -->
            <div class="px-3 py-3 bg-[#2a2a2a] border-t border-[#3a3a3a]">
                <div class="text-xs text-gray-400 mb-2 font-medium">Hızlı Miktarlar</div>
                <div class="grid grid-cols-2 gap-2 mb-2">
                    <button type="button" class="rounded-md bg-[#3a3a3a] text-white text-xs font-semibold py-2 hover:bg-[#4a4a4a] focus:outline-none quick-amount-btn" data-amount="250">250 ₺</button>
                    <button type="button" class="rounded-md bg-[#3a3a3a] text-white text-xs font-semibold py-2 hover:bg-[#4a4a4a] focus:outline-none quick-amount-btn" data-amount="500">500 ₺</button>
                    <button type="button" class="rounded-md bg-[#3a3a3a] text-white text-xs font-semibold py-2 hover:bg-[#4a4a4a] focus:outline-none quick-amount-btn" data-amount="1000">1000 ₺</button>
                    <button type="button" class="rounded-md bg-[#3a3a3a] text-white text-xs font-semibold py-2 hover:bg-[#4a4a4a] focus:outline-none quick-amount-btn" data-amount="2500">2500 ₺</button>
                </div>
                <button type="button" class="w-full rounded-md bg-[#3a3a3a] text-white text-xs font-semibold py-2 hover:bg-[#4a4a4a] focus:outline-none flex items-center justify-center gap-2" onclick="document.getElementById('bet-amount-input').focus()">
                    <i class="fas fa-pencil-alt"></i>
                    Özel Miktar Gir
                </button>
            </div>

            <!-- Bet Button -->
            <button type="button" id="place-bet-btn" class="w-full py-3 text-xs font-semibold bg-[#f59e0b] text-[#2a2a2a] hover:bg-[#e5890a]" onclick="placeBet()">
                BAHİS YAP
            </button>
        </aside>
    </div>

    <script>
        // Global variables
        let selectedBets = [];
        let selectedMatchId = null;
        let currentMarketFilter = 'tumu';
        let allOddsData = null;
        let liveMatches = []; // Store live matches data
        let selectedOddsButtons = new Set(); // Track selected odds buttons
        
        // Add bet function for pre-match odds buttons
        function addBet(betType, odds, matchId, market, selection) {
            console.log('addBet called:', { betType, odds, matchId, market, selection });
            
            // Validate parameters
            if (!betType || !odds || !matchId || !market || !selection) {
                console.log('Invalid bet parameters');
                return;
            }
            
            // Get match data
            const eventId = displayIdToEventId[parseInt(matchId)];
            if (!eventId) {
                console.log('No eventId found for matchId:', matchId);
                return;
            }
            
            const match = matchData[eventId];
            if (!match) {
                console.log('No match data found for eventId:', eventId);
                return;
            }
            
            // Create bet object
            const bet = {
                id: `${eventId}_${market}_${betType}`, // Unique bet ID
                eventId: eventId,
                betType: betType,
                odds: parseFloat(odds),
                market: market,
                selection: selection,
                match: `${match.home} vs ${match.away}`, // Match display text
                matchInfo: {
                    home: match.home,
                    away: match.away,
                    league: match.league,
                    date: match.date
                }
            };
            
            // CRITICAL: Remove any existing bet for this match (1 match = 1 selection only)
            const existingBetIndex = selectedBets.findIndex(b => b.eventId === bet.eventId);
            
            if (existingBetIndex !== -1) {
                // Remove existing bet for this match
                selectedBets.splice(existingBetIndex, 1);
                console.log('Removed existing bet for this match');
            }
            
            // Add new bet
            selectedBets.push(bet);
            console.log('Added new bet');
            
            // Update UI
            updateBetSlip();
            saveBettingSlipToStorage();
            
            // Show visual feedback
            const button = document.querySelector(`button[onclick*="addBet('${betType}'"]`);
            if (button) {
                button.classList.add('selected', 'newly-selected');
                setTimeout(() => {
                    button.classList.remove('newly-selected');
                }, 600);
            }
        }
        
        // Odds button selection management
        function markOddsButtonAsSelected(button, betId) {
            // Remove selected class from all buttons
            document.querySelectorAll('.odds-btn.selected').forEach(btn => {
                btn.classList.remove('selected', 'newly-selected');
            });
            
            // Allow multiple bets from different matches
            
            // Add new selection
            selectedOddsButtons.add(betId);
            button.classList.add('selected', 'newly-selected');
            
            // Remove newly-selected animation after it completes
            setTimeout(() => {
                button.classList.remove('newly-selected');
            }, 600);
        }
        
        function removeOddsButtonSelection(betId) {
            selectedOddsButtons.delete(betId);
            const button = document.querySelector(`[data-bet-type="${betId}"]`);
            if (button) {
                button.classList.remove('selected', 'newly-selected');
            }
        }
        
        function updateOddsButtonSelections() {
            // Clear all selections first
            document.querySelectorAll('.odds-btn.selected, .mobile-odds-btn.selected').forEach(btn => {
                btn.classList.remove('selected', 'newly-selected');
            });
            
            // Re-apply selections based on current bets
            selectedBets.forEach(bet => {
                const button = document.querySelector(`[data-bet-type="${bet.betType}"][data-market="${bet.market}"]`) ||
                              document.querySelector(`[data-bet-id*="${bet.eventId}_${bet.market}_${bet.betType}"]`);
                if (button) {
                    button.classList.add('selected');
                }
            });
        }
        
        function toggleMobileOddsSelection(button, betId) {
            // Clear previous selections for this match/market combination
            const parts = betId.split('_');
            const eventId = parts[0];
            const market = parts[1];
            
            // Remove selection from other buttons in same market
            document.querySelectorAll(`[data-bet-id^="${eventId}_${market}_"]`).forEach(btn => {
                btn.classList.remove('selected', 'newly-selected');
            });
            
            // Add selection to clicked button
            button.classList.add('selected', 'newly-selected');
            
            // Remove newly-selected animation after it completes
            setTimeout(() => {
                button.classList.remove('newly-selected');
            }, 600);
        }

        // Load betting slip from localStorage on page load
        function loadBettingSlipFromStorage() {
            const saved = localStorage.getItem('bettingSlip');
            if (saved) {
                try {
                    const savedBets = JSON.parse(saved);
                    selectedBets = [];
                    
                    // Filter out invalid bets and update structure
                    savedBets.forEach(bet => {
                        // Check if this is an old format bet (using numeric IDs)
                        if (bet.matchId && typeof bet.matchId === 'number') {
                            return; // Skip old format bets
                        }
                        
                        // Check if bet has eventId and the match still exists
                        if (bet.eventId && matchData[bet.eventId]) {
                            // Update bet structure if needed
                            if (!bet.matchInfo) {
                                const match = matchData[bet.eventId];
                                bet.matchInfo = {
                                    home: match.home,
                                    away: match.away,
                                    league: match.league,
                                    date: match.date
                                };
                            }
                            selectedBets.push(bet);
                        } else if (bet.eventId && bet.eventId.toString().startsWith('live_')) {
                            // This is a live bet, keep it even if not in matchData
                            // The matchInfo should already be complete from when it was added
                            if (bet.matchInfo) {
                                selectedBets.push(bet);
                            }
                        } else {
                            // Skip invalid bet
                        }
                    });
                    
                    // Update slip display based on device type
                    if (shouldShowMobileView()) {
                        updateMobileSlip();
                    } else {
                        updateBetSlip();
                    }
                    
                    // Restore odds button selections after a short delay
                    setTimeout(() => {
                        updateOddsButtonSelections();
                    }, 500);
                    // Loaded bets from storage
                } catch (e) {
                    selectedBets = [];
                    // Clear corrupted data
                    localStorage.removeItem('bettingSlip');
                }
            }
        }
        
        // Save betting slip to localStorage
        function saveBettingSlipToStorage() {
            localStorage.setItem('bettingSlip', JSON.stringify(selectedBets));
        }
        
        // Match data for reference - using eventId as key for persistence
        let matchData = {
            @foreach($sportsData['matches'] as $index => $match)
            '{{ $match->eventid }}': { 
                displayId: {{ $index + 1 }},
                home: decodeHtmlEntities('{{ addslashes($match->evsahibi_isim) }}'), 
                away: decodeHtmlEntities('{{ addslashes($match->misafir_isim) }}'), 
                league: decodeHtmlEntities('{{ addslashes($match->lig_isim) }}'),
                country: decodeHtmlEntities('{{ addslashes($match->ulke_isim ?? '') }}'),
                sport: '{{ $match->tur }}',
                date: '{{ $match->baslangic->format('d.m.Y, H:i') }}',
                eventId: '{{ $match->eventid }}',
                odds1: {{ $match->oran1 ?? 2.15 }},
                oddsX: {{ $match->oran0 ?? 3.40 }},
                odds2: {{ $match->oran2 ?? 3.75 }}
            },
            @endforeach
        };
        
        // Reverse lookup for display IDs to eventIds
        const displayIdToEventId = {
            @foreach($sportsData['matches'] as $index => $match)
            {{ $index + 1 }}: '{{ $match->eventid }}',
            @endforeach
        };

        // HTML entity decoder function
        function decodeHtmlEntities(text) {
            if (!text) return '';
            const textarea = document.createElement('textarea');
            textarea.innerHTML = text;
            return textarea.value;
        }
        
        // Debug: Log matchData on page load
        console.log('matchData loaded:', matchData);
        console.log('displayIdToEventId loaded:', displayIdToEventId);
        
        // Auto-select first match on page load
        function autoSelectFirstMatch() {
            if (Object.keys(matchData).length > 0) {
                const firstEventId = Object.keys(matchData)[0];
                const firstMatch = matchData[firstEventId];
                if (firstMatch && firstMatch.displayId) {
                    console.log('Auto-selecting first match:', firstMatch);
                    selectMatch(firstMatch.displayId);
                }
            }
        }

        // Bayrak fonksiyonu - PNG yoksa SVG dener, o da yoksa defaults.png
        function getCountryFlag(countryName) {
            if (!countryName) return '/images/flags/defaults.png';
            
            const country = countryName.toLowerCase().trim();
            
            // Eğer zaten 2 harfli kod ise direkt kullan
            if (country.length === 2 && /^[a-z]{2}$/.test(country)) {
                return `/images/flags/${country}.png`;
            }
            
            // Ülke ismi mapping'leri - Doğru ISO kodları
            const countryMappings = {
                // Türkiye
                'türkiye': 'tr', 'turkey': 'tr', 'turkish': 'tr',
                
                // Almanya
                'almanya': 'de', 'germany': 'de', 'german': 'de',
                
                // Fransa
                'fransa': 'fr', 'france': 'fr', 'french': 'fr',
                
                // İngiltere
                'ingiltere': 'gb', 'england': 'gb', 'britain': 'gb', 'uk': 'gb', 'british': 'gb',
                
                // İspanya
                'ispanya': 'es', 'spain': 'es', 'spanish': 'es',
                
                // İtalya
                'italya': 'it', 'italy': 'it', 'italian': 'it',
                
                // Portekiz
                'portekiz': 'pt', 'portugal': 'pt', 'portuguese': 'pt',
                
                // Hollanda
                'hollanda': 'nl', 'netherlands': 'nl', 'dutch': 'nl',
                
                // Belçika
                'belçika': 'be', 'belgium': 'be', 'belgian': 'be',
                
                // İsviçre
                'isviçre': 'ch', 'switzerland': 'ch', 'swiss': 'ch',
                
                // Avusturya
                'avusturya': 'at', 'austria': 'at', 'austrian': 'at',
                
                // Polonya
                'polonya': 'pl', 'poland': 'pl', 'polish': 'pl',
                
                // Çek Cumhuriyeti
                'çek cumhuriyeti': 'cz', 'czech republic': 'cz', 'czech': 'cz',
                
                // Macaristan
                'macaristan': 'hu', 'hungary': 'hu', 'hungarian': 'hu',
                
                // Romanya
                'romanya': 'ro', 'romania': 'ro', 'romanian': 'ro',
                
                // Bulgaristan
                'bulgaristan': 'bg', 'bulgaria': 'bg', 'bulgarian': 'bg',
                
                // Hırvatistan
                'hırvatistan': 'hr', 'croatia': 'hr', 'croatian': 'hr',
                
                // Sırbistan
                'sırbistan': 'rs', 'serbia': 'rs', 'serbian': 'rs',
                
                // Slovakya
                'slovakya': 'sk', 'slovakia': 'sk', 'slovakian': 'sk',
                
                // Slovenya
                'slovenya': 'si', 'slovenia': 'si', 'slovenian': 'si',
                
                // Rusya
                'rusya': 'ru', 'russia': 'ru', 'russian': 'ru',
                
                // Ukrayna
                'ukrayna': 'ua', 'ukraine': 'ua', 'ukrainian': 'ua',
                
                // Yunanistan
                'yunanistan': 'gr', 'greece': 'gr', 'greek': 'gr',
                
                // Norveç
                'norveç': 'no', 'norway': 'no', 'norwegian': 'no',
                
                // İsveç
                'isveç': 'se', 'sweden': 'se', 'swedish': 'se',
                
                // Danimarka
                'danimarka': 'dk', 'denmark': 'dk', 'danish': 'dk',
                
                // Finlandiya
                'finlandiya': 'fi', 'finland': 'fi', 'finnish': 'fi',
                
                // İrlanda
                'irlanda': 'ie', 'ireland': 'ie', 'irish': 'ie',
                
                // İsrail
                'israil': 'il', 'israel': 'il', 'israeli': 'il',
                
                // Amerika
                'amerika': 'us', 'america': 'us', 'usa': 'us', 'united states': 'us', 'american': 'us',
                
                // Kanada
                'kanada': 'ca', 'canada': 'ca', 'canadian': 'ca',
                
                // Brezilya
                'brezilya': 'br', 'brazil': 'br', 'brazilian': 'br',
                
                // Arjantin
                'arjantin': 'ar', 'argentina': 'ar', 'argentine': 'ar',
                
                // Mısır
                'mısır': 'eg', 'egypt': 'eg', 'egyptian': 'eg',
                
                // Tunus
                'tunus': 'tn', 'tunisia': 'tn', 'tunisian': 'tn',
                
                // Cezayir
                'cezayir': 'dz', 'algeria': 'dz', 'algerian': 'dz',
                
                // Fas
                'fas': 'ma', 'morocco': 'ma', 'moroccan': 'ma',
                
                // Çin
                'çin': 'cn', 'china': 'cn', 'chinese': 'cn',
                
                // Japonya
                'japonya': 'jp', 'japan': 'jp', 'japanese': 'jp',
                
                // Güney Kore
                'güney kore': 'kr', 'south korea': 'kr', 'korea': 'kr', 'korean': 'kr',
                
                // Avustralya
                'avustralya': 'au', 'australia': 'au', 'australian': 'au',
                
                // Yeni Zelanda
                'yeni zelanda': 'nz', 'new zealand': 'nz', 'zealand': 'nz',
                
                // Meksika
                'meksika': 'mx', 'mexico': 'mx', 'mexican': 'mx'
            };
            
            // Mapping'den kod bul
            let countryCode = countryMappings[country];
            if (!countryCode && country.length >= 2) {
                // Fallback: ülke isminden ilk 2 harfi al
                countryCode = country.substring(0, 2);
            }
            
            if (countryCode) {
                // Önce PNG'yi dene, yoksa SVG'yi dene
                return `/images/flags/${countryCode}.png`;
            }
            
            // Bilinmeyen ülkeler için default
            return '/images/flags/defaults.png';
        }
        
        // Load country flags in sidebar
        function loadCountryFlags() {
            const countryImages = document.querySelectorAll('img[data-country]');
            countryImages.forEach(img => {
                const country = img.getAttribute('data-country');
                if (country) {
                    img.src = getCountryFlag(country);
                    // Add error handling for failed flag loads
                    img.onerror = function() {
                        // PNG bulunamadı, SVG'yi dene
                        const countryCode = getCountryCode(country);
                        if (countryCode) {
                            this.src = `/images/flags/${countryCode}.svg`;
                            // SVG de bulunamazsa defaults.png kullan
                            this.onerror = function() {
                                this.src = '/images/flags/defaults.png';
                            };
                        } else {
                            this.src = '/images/flags/defaults.png';
                        }
                    };
                }
            });
        }
        
        // Ülke isminden 2 harfli kod çıkar
        function getCountryCode(countryName) {
            if (!countryName) return null;
            
            const country = countryName.toLowerCase().trim();
            
            // Eğer zaten 2 harfli kod ise direkt kullan
            if (country.length === 2 && /^[a-z]{2}$/.test(country)) {
                return country;
            }
            
            // Ülke ismi mapping'leri
            const countryMappings = {
                // Türkiye
                'türkiye': 'tr', 'turkey': 'tr', 'turkish': 'tr',
                
                // Almanya
                'almanya': 'de', 'germany': 'de', 'german': 'de',
                
                // Fransa
                'fransa': 'fr', 'france': 'fr', 'french': 'fr',
                
                // İngiltere
                'ingiltere': 'gb', 'england': 'gb', 'britain': 'gb', 'uk': 'gb', 'british': 'gb',
                
                // İspanya
                'ispanya': 'es', 'spain': 'es', 'spanish': 'es',
                
                // İtalya
                'italya': 'it', 'italy': 'it', 'italian': 'it',
                
                // Portekiz
                'portekiz': 'pt', 'portugal': 'pt', 'portuguese': 'pt',
                
                // Hollanda
                'hollanda': 'nl', 'netherlands': 'nl', 'dutch': 'nl',
                
                // Belçika
                'belçika': 'be', 'belgium': 'be', 'belgian': 'be',
                
                // İsviçre
                'isviçre': 'ch', 'switzerland': 'ch', 'swiss': 'ch',
                
                // Avusturya
                'avusturya': 'at', 'austria': 'at', 'austrian': 'at',
                
                // Polonya
                'polonya': 'pl', 'poland': 'pl', 'polish': 'pl',
                
                // Çek Cumhuriyeti
                'çek cumhuriyeti': 'cz', 'czech republic': 'cz', 'czech': 'cz',
                
                // Macaristan
                'macaristan': 'hu', 'hungary': 'hu', 'hungarian': 'hu',
                
                // Romanya
                'romanya': 'ro', 'romania': 'ro', 'romanian': 'ro',
                
                // Bulgaristan
                'bulgaristan': 'bg', 'bulgaria': 'bg', 'bulgarian': 'bg',
                
                // Hırvatistan
                'hırvatistan': 'hr', 'croatia': 'hr', 'croatian': 'hr',
                
                // Sırbistan
                'sırbistan': 'rs', 'serbia': 'rs', 'serbian': 'rs',
                
                // Slovakya
                'slovakya': 'sk', 'slovakia': 'sk', 'slovakian': 'sk',
                
                // Slovenya
                'slovenya': 'si', 'slovenia': 'si', 'slovenian': 'si',
                
                // Rusya
                'rusya': 'ru', 'russia': 'ru', 'russian': 'ru',
                
                // Ukrayna
                'ukrayna': 'ua', 'ukraine': 'ua', 'ukrainian': 'ua',
                
                // Yunanistan
                'yunanistan': 'gr', 'greece': 'gr', 'greek': 'gr',
                
                // Norveç
                'norveç': 'no', 'norway': 'no', 'norwegian': 'no',
                
                // İsveç
                'isveç': 'se', 'sweden': 'se', 'swedish': 'se',
                
                // Danimarka
                'danimarka': 'dk', 'denmark': 'dk', 'danish': 'dk',
                
                // Finlandiya
                'finlandiya': 'fi', 'finland': 'fi', 'finnish': 'fi',
                
                // İrlanda
                'irlanda': 'ie', 'ireland': 'ie', 'irish': 'ie',
                
                // İsrail
                'israil': 'il', 'israel': 'il', 'israeli': 'il',
                
                // Amerika
                'amerika': 'us', 'america': 'us', 'usa': 'us', 'united states': 'us', 'american': 'us',
                
                // Kanada
                'kanada': 'ca', 'canada': 'ca', 'canadian': 'ca',
                
                // Brezilya
                'brezilya': 'br', 'brazil': 'br', 'brazilian': 'br',
                
                // Arjantin
                'arjantin': 'ar', 'argentina': 'ar', 'argentine': 'ar',
                
                // Mısır
                'mısır': 'eg', 'egypt': 'eg', 'egyptian': 'eg',
                
                // Tunus
                'tunus': 'tn', 'tunisia': 'tn', 'tunisian': 'tn',
                
                // Cezayir
                'cezayir': 'dz', 'algeria': 'dz', 'algerian': 'dz',
                
                // Fas
                'fas': 'ma', 'morocco': 'ma', 'moroccan': 'ma',
                
                // Çin
                'çin': 'cn', 'china': 'cn', 'chinese': 'cn',
                
                // Japonya
                'japonya': 'jp', 'japan': 'jp', 'japanese': 'jp',
                
                // Güney Kore
                'güney kore': 'kr', 'south korea': 'kr', 'korea': 'kr', 'korean': 'kr',
                
                // Avustralya
                'avustralya': 'au', 'australia': 'au', 'australian': 'au',
                
                // Yeni Zelanda
                'yeni zelanda': 'nz', 'new zealand': 'nz', 'zealand': 'nz',
                
                // Meksika
                'meksika': 'mx', 'mexico': 'mx', 'mexican': 'mx'
            };
            
            // Mapping'den kod bul
            let countryCode = countryMappings[country];
            if (!countryCode && country.length >= 2) {
                // Fallback: ülke isminden ilk 2 harfi al
                countryCode = country.substring(0, 2);
            }
            
            return countryCode;
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Clean up old localStorage format first
            cleanupOldLocalStorage();
            
            // Load betting slip from localStorage
            loadBettingSlipFromStorage();
            
            // Load country flags in sidebar
            loadCountryFlags();
            
            // Add event listeners for popular and upcoming matches buttons
            const popularMatchesBtn = document.getElementById('popular-matches-btn');
            const upcomingMatchesBtn = document.getElementById('upcoming-matches-btn');
            
            if (popularMatchesBtn) {
                popularMatchesBtn.addEventListener('click', function() {
                    showPopularMatches();
                });
            }
            
            if (upcomingMatchesBtn) {
                upcomingMatchesBtn.addEventListener('click', function() {
                    showUpcomingMatches();
                });
            }
            
            // Left odds panel close button removed - panel no longer exists
            
            // Desktop odds panel close button removed - panel no longer exists
            
            // Auto-select first match on page load - REMOVED
            // setTimeout(() => {
            //     autoSelectFirstMatch();
            // }, 1000); // Wait 1 second for everything to load
            
            // Attach match click listeners on page load
            setTimeout(() => {
                attachMatchClickListeners();
            }, 500);
        });
        
        // Odds button selection management
        function markOddsButtonAsSelected(button, betId) {
            // Remove selected class from all buttons
            document.querySelectorAll('.odds-btn.selected').forEach(btn => {
                btn.classList.remove('selected', 'newly-selected');
            });
            
            // Allow multiple bets from different matches
            
            // Add new selection
            selectedOddsButtons.add(betId);
            button.classList.add('selected', 'newly-selected');
            
            // Remove newly-selected class after animation
            setTimeout(() => {
                button.classList.remove('newly-selected');
            }, 600);
        }
        
        function removeOddsButtonSelection(betId) {
            selectedOddsButtons.delete(betId);
            
            // Find and remove the button's selected state
            const button = document.querySelector(`[data-bet-id="${betId}"]`);
            if (button) {
                button.classList.remove('selected', 'newly-selected');
            }
        }
        
        function updateOddsButtonSelections() {
            // Clear all selections first
            document.querySelectorAll('.odds-btn.selected, .mobile-odds-btn.selected').forEach(btn => {
                btn.classList.remove('selected', 'newly-selected');
            });
            
            // Re-apply selections based on current bets
            selectedBets.forEach(bet => {
                const button = document.querySelector(`[data-bet-type="${bet.betType}"][data-market="${bet.market}"]`) ||
                              document.querySelector(`[data-bet-id*="${bet.eventId}_${bet.market}_${bet.betType}"]`);
                if (button) {
                    button.classList.add('selected');
                }
            });
        }
        
        // Load betting slip from localStorage on page load
        function loadBettingSlipFromStorage() {
            const saved = localStorage.getItem('bettingSlip');
            if (saved) {
                try {
                    const savedBets = JSON.parse(saved);
                    selectedBets = [];
                    
                    // Filter out invalid bets and update structure
                    savedBets.forEach(bet => {
                        // Skip old format bets
                        if (bet.matchId && typeof bet.matchId === 'number') {
                            return; // Skip old format bets
                        }
                        
                        // Check if bet has eventId and the match still exists
                        if (bet.eventId && matchData[bet.eventId]) {
                            // Update bet structure if needed
                            if (!bet.matchInfo) {
                                const match = matchData[bet.eventId];
                                bet.matchInfo = {
                                    match: match.match,
                                    league: match.league,
                                    date: match.date
                                };
                            }
                            selectedBets.push(bet);
                        } else if (bet.eventId && bet.eventId.toString().startsWith('live_')) {
                            // Keep live bets as they are
                            selectedBets.push(bet);
                        }
                    });
                    
                    // Update bet slip display
                    updateBetSlip();
                    
                    // Restore odds button selections after a short delay
                    setTimeout(() => {
                        updateOddsButtonSelections();
                    }, 500);
                    // Loaded bets from storage
                } catch (e) {
                    selectedBets = [];
                }
            }
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Clean up old localStorage format first
            cleanupOldLocalStorage();
            
            // Load betting slip from localStorage
            loadBettingSlipFromStorage();
            
            // Load country flags in sidebar
            loadCountryFlags();
            
            // Add event listeners for popular and upcoming matches buttons
            const popularMatchesBtn = document.getElementById('popular-matches-btn');
            const upcomingMatchesBtn = document.getElementById('upcoming-matches-btn');
            
            if (popularMatchesBtn) {
                popularMatchesBtn.addEventListener('click', function() {
                    showPopularMatches();
                });
            }
            
            if (upcomingMatchesBtn) {
                upcomingMatchesBtn.addEventListener('click', function() {
                    showUpcomingMatches();
                });
            }
            
            // Sport toggle functionality
            document.querySelectorAll('.sport-toggle').forEach(btn => {
                btn.addEventListener('click', function() {
                    const sport = this.dataset.sport;
                    const submenu = document.getElementById(`${sport.toLowerCase()}-submenu`);
                    const chevron = this.querySelector('.fas');
                    
                    // Check if we're in live mode
                    const sidebar = document.getElementById('sidebar');
                    const isLiveMode = sidebar && sidebar.classList.contains('live-mode');
                    
                    if (submenu.classList.contains('hidden')) {
                        document.querySelectorAll('[id$="-submenu"]').forEach(menu => {
                            menu.classList.add('hidden');
                        });
                        document.querySelectorAll('.sport-toggle').forEach(button => {
                            button.classList.remove('bg-[#3a3a3a]');
                            button.classList.add('bg-[#2a2a2a]');
                            const icon = button.querySelector('.fas:last-child');
                            if (icon) {
                                icon.classList.remove('fa-chevron-up', 'text-green-500');
                                icon.classList.add('fa-chevron-down');
                            }
                        });
                        
                        submenu.classList.remove('hidden');
                        this.classList.remove('bg-[#2a2a2a]');
                        this.classList.add('bg-[#3a3a3a]');
                        chevron.classList.remove('fa-chevron-down');
                        chevron.classList.add('fa-chevron-up', 'text-green-500');
                        
                        // Filter matches based on mode
                        if (isLiveMode) {
                            filterLiveMatchesBySport(sport);
                        } else {
                            filterMatchesBySport(sport);
                        }
                    } else {
                        submenu.classList.add('hidden');
                        this.classList.remove('bg-[#3a3a3a]');
                        this.classList.add('bg-[#2a2a2a]');
                        chevron.classList.remove('fa-chevron-up', 'text-green-500');
                        chevron.classList.add('fa-chevron-down');
                        
                        // Show all matches based on mode
                        if (isLiveMode) {
                            showAllLiveMatches();
                        } else {
                            showAllMatches();
                        }
                    }
                });
            });

            // Country button clicks
            document.querySelectorAll('.country-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const country = this.dataset.country;
                    const sport = this.dataset.sport;
                    const leaguesDiv = document.querySelector(`[data-country="${country}"][data-sport="${sport}"].country-leagues`);
                    
                    if (leaguesDiv) {
                        leaguesDiv.style.display = leaguesDiv.style.display === 'none' ? 'block' : 'none';
                    }
                });
            });

            // League button clicks
            document.querySelectorAll('.league-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const league = this.dataset.league;
                    const sport = this.dataset.sport;
                    filterMatchesByLeagueAndSport(league, sport);
                    updateHeaders(league);
                });
            });
            
            // Major league buttons (new section)
            document.querySelectorAll('.league-btn[data-league]').forEach(btn => {
                btn.addEventListener('click', function() {
                    const league = this.dataset.league;
                    
                    // Create allMatches array from matchData
                    const allMatches = Object.values(matchData);
                    window.allMatches = allMatches; // Store for sport filtering
                    
                    let filteredMatches = [];
                    
                    // Special filtering for different leagues
                    if (league === 'UEFA Champions League') {
                        filteredMatches = allMatches.filter(match => {
                            const matchLeague = match.league || match.lig_isim || '';
                            const leagueLower = matchLeague.toLowerCase();
                            
                            // Check for various Champions League keywords
                            return leagueLower.includes('champion') || 
                                   leagueLower.includes('sampiyon') || 
                                   leagueLower.includes('şampiyon') ||
                                   leagueLower.includes('champions league') ||
                                   leagueLower.includes('uefa champions') ||
                                   leagueLower.includes('ucl') ||
                                   leagueLower.includes('champions ligi') ||
                                   leagueLower.includes('sampiyonlar ligi') ||
                                   leagueLower.includes('şampiyonlar ligi');
                        });
                        
                        // Champions League matches found
                    } else if (league === 'UEFA Europa League') {
                        filteredMatches = allMatches.filter(match => {
                            const matchLeague = match.league || match.lig_isim || '';
                            const leagueLower = matchLeague.toLowerCase();
                            
                            // Check for Europa League keywords
                            return leagueLower.includes('europe') || 
                                   leagueLower.includes('europa') || 
                                   leagueLower.includes('avrupa') ||
                                   leagueLower.includes('europa league') ||
                                   leagueLower.includes('uefa europa') ||
                                   leagueLower.includes('europa ligi') ||
                                   leagueLower.includes('avrupa ligi');
                        });
                        
                        // Europa League matches found
                    } else if (league === 'Bundesliga') {
                        filteredMatches = allMatches.filter(match => {
                            const matchLeague = match.league || match.lig_isim || '';
                            const leagueLower = matchLeague.toLowerCase();
                            
                            // Check for Bundesliga keywords
                            return leagueLower.includes('bundesliga') ||
                                   leagueLower.includes('bundes liga') ||
                                   leagueLower.includes('almanya ligi') ||
                                   leagueLower.includes('german league');
                        });
                        
                        // Bundesliga matches found
                    } else if (league === 'Serie A') {
                        filteredMatches = allMatches.filter(match => {
                            const matchLeague = match.league || match.lig_isim || '';
                            const leagueLower = matchLeague.toLowerCase();
                            
                            // Check for Serie A keywords
                            return leagueLower.includes('serie a') ||
                                   leagueLower.includes('serie-a') ||
                                   leagueLower.includes('italya ligi') ||
                                   leagueLower.includes('italian league');
                        });
                        
                        // Serie A matches found
                    } else if (league === 'NBA') {
                        filteredMatches = allMatches.filter(match => {
                            const matchLeague = match.league || match.lig_isim || '';
                            const leagueLower = matchLeague.toLowerCase();
                            
                            // Check for NBA keywords
                            return leagueLower.includes('nba') ||
                                   leagueLower.includes('national basketball') ||
                                   leagueLower.includes('amerika basketbol') ||
                                   leagueLower.includes('basketball association');
                        });
                        
                        // NBA matches found
                    } else {
                        // Regular filtering for other leagues
                        filteredMatches = allMatches.filter(match => {
                            const matchLeague = match.league || match.lig_isim || '';
                            return matchLeague.toLowerCase().includes(league.toLowerCase()) ||
                                   league.toLowerCase().includes(matchLeague.toLowerCase());
                        });
                    }
                    
                    if (filteredMatches.length > 0) {
                        updateMatchesDisplay(filteredMatches, league);
                        updateHeaders(league);
                        
                        // Update active state
                        document.querySelectorAll('.league-btn').forEach(b => b.classList.remove('bg-[#3a3a3a]'));
                        this.classList.add('bg-[#3a3a3a]');
                    } else {
                        // Show empty state
                        const mainContent = document.getElementById('main-content');
                        if (mainContent) {
                            mainContent.innerHTML = `
                                <div class="text-center text-[#7a7a7a] py-8">
                                    <i class="fas fa-trophy text-4xl mb-4 block text-[#f59e0b]"></i>
                                    <div class="text-sm font-semibold mb-2">${league}</div>
                                    <div class="text-xs">Bu lig için henüz maç bulunmuyor</div>
                                </div>
                            `;
                        }
                        updateHeaders(league);
                    }
                });
            });

            // Match item clicks
            attachMatchClickListeners();
            
            // Show only football matches by default - PHP already handles this
            setTimeout(() => {
                // filterMatchesBySport('futbol'); // REMOVED: PHP already shows football matches
                
                const footballToggle = document.querySelector('[data-sport="futbol"]');
                if (footballToggle) {
                    const submenu = document.getElementById('futbol-submenu');
                    if (submenu) {
                        submenu.classList.remove('hidden');
                        footballToggle.classList.remove('bg-[#2a2a2a]');
                        footballToggle.classList.add('bg-[#3a3a3a]');
                        const chevron = footballToggle.querySelector('.fas:last-child');
                        if (chevron) {
                            chevron.classList.remove('fa-chevron-down');
                            chevron.classList.add('fa-chevron-up', 'text-green-500');
                        }
                    }
                }
            }, 100);
            
            // Market filter clicks
            document.querySelectorAll('.market-filter').forEach(btn => {
                btn.addEventListener('click', function() {
                    const filter = this.dataset.filter;
                    currentMarketFilter = filter;
                    
                    
                    // Update active tab
                    document.querySelectorAll('.market-filter').forEach(button => {
                        button.classList.remove('border-b-2', 'border-[#f59e0b]', 'text-[#f59e0b]', 'font-semibold');
                        button.classList.add('text-[#6b6b6b]');
                    });
                    
                    this.classList.remove('text-[#6b6b6b]');
                    this.classList.add('border-b-2', 'border-[#f59e0b]', 'text-[#f59e0b]', 'font-semibold');
                    
                    // Filter and display odds
                    if (window.allOddsData && window.selectedMatchId) {
                        displayFilteredOdds(window.allOddsData, filter);
                    }
                });
            });
            
            // Match item clicks
            document.addEventListener('click', function(e) {
                if (e.target.closest('.match-item')) {
                    const matchItem = e.target.closest('.match-item');
                    const matchId = matchItem.dataset.matchId;
                    if (matchId) {
                        selectMatch(matchId);
                    }
                }
            });
            
            // Odds button clicks
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('odds-btn') || e.target.closest('.odds-btn')) {
                    const btn = e.target.classList.contains('odds-btn') ? e.target : e.target.closest('.odds-btn');
                    
                    if (btn.disabled || btn.classList.contains('cursor-not-allowed')) {
                        return;
                    }
                    
                    const betType = btn.dataset.betType;
                    const odds = parseFloat(btn.dataset.odds);
                    const market = btn.dataset.market || 'Kazanan';
                    const selection = btn.dataset.selection || btn.textContent.split('\\n')[0].trim();
                    
                    
                    // CRITICAL: Enhanced validation for odds and bet type
                    if (!odds || odds <= 0 || odds === 0 || odds === '0' || odds === '0.00') {
                        alert('Bu seçenek için geçerli oran bulunamadı. Lütfen başka bir seçenek seçin.');
                        return;
                    }
                    
                    if (!betType || betType === '' || betType === 'undefined' || betType === 'null') {
                        alert('Bahis türü bulunamadı. Lütfen tekrar deneyin.');
                        return;
                    }
                    
                    // Allow both main match bet types and custom market bet types
                    const isValidMainBetType = ['1', 'X', '0', '2'].includes(betType);
                    const isValidCustomBetType = typeof betType === 'string' && betType.includes('_') && betType.length > 1;
                    
                    if (!isValidMainBetType && !isValidCustomBetType) {
                        alert('Geçersiz bahis türü. Lütfen geçerli bir seçenek seçin.');
                        return;
                    }
                    
                    if (!selectedMatchId) {
                        alert('Lütfen önce bir maç seçin.');
                        return;
                    }
                    
                    // Additional validation for live matches
                    const isLiveMatch = !matchData[selectedMatchId];
                    if (isLiveMatch) {
                        // For live matches, ensure we have valid selection
                        if (!selection || selection === 'Bilinmeyen') {
                            alert('Canlı maç için geçersiz seçenek. Lütfen tekrar deneyin.');
                            return;
                        }
                    }
                    
                    // Get match ID from button data or fallback to selectedMatchId
                    const matchId = btn.dataset.matchId || selectedMatchId;
                    
                    if (betType && odds && matchId) {
                        // Create unique bet ID for tracking
                        const betId = `${matchId}_${market}_${betType}`;
                        
                        // Mark button as selected with visual feedback
                        markOddsButtonAsSelected(btn, betId);
                        
                        addToBetSlip(betType, odds, matchId, market, selection);
                    }
                }
            });
            
            // Bet amount input change listener
            document.addEventListener('input', function(e) {
                if (e.target.id === 'bet-amount-input') {
                    updateBetCalculations();
                }
            });
            
            // Quick amount buttons
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('quick-amount-btn')) {
                    const amount = e.target.dataset.amount;
                    const betAmountInput = document.getElementById('bet-amount-input');
                    if (betAmountInput && amount) {
                        betAmountInput.value = amount;
                        updateBetCalculations();
                    }
                }
            });
            
            // Place bet button click
            document.addEventListener('click', function(e) {
                if (e.target.id === 'place-bet-btn' && !e.target.disabled) {
                    e.preventDefault();
                    placeBet();
                }
            });
            
            // Also add direct onclick to the button (fallback)
            setTimeout(() => {
                const betButton = document.getElementById('place-bet-btn');
                if (betButton) {
                    // Force enable button and add multiple event listeners
                    betButton.disabled = false;
                    betButton.style.pointerEvents = 'auto';
                    betButton.style.cursor = 'pointer';
                    
                    // Add multiple ways to trigger the bet
                    betButton.onclick = function(e) {
                        e.preventDefault();
                        placeBet();
                    };
                    
                    betButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        placeBet();
                    });
                    
                    // Also try mousedown as backup
                    betButton.addEventListener('mousedown', function(e) {
                        if (e.button === 0) { // Left click only
                            e.preventDefault();
                            placeBet();
                        }
                    });
                    
                    // Button event listeners added
                }
            }, 100);
            
            // Initialize live mode functionality
            initModeSwitch();
        });
        
        // Live/Pre-match mode switching functionality
        function initModeSwitch() {
            const liveBtn = document.querySelector('.live-btn');
            const preMatchBtn = document.querySelector('.pre-match-btn');
            const liveContent = document.getElementById('live-content');
            const preMatchContent = document.getElementById('pre-match-content');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content'); // Middle matches section
            const oddsContent = document.querySelector('.odds-content'); // Right odds section
            
            if (liveBtn && preMatchBtn && liveContent && preMatchContent) {
                // Live mode button click
                liveBtn.addEventListener('click', function() {
                    // Update button styles
                    liveBtn.classList.remove('bg-[#3a3a3a]', 'text-[#a0a0a0]');
                    liveBtn.classList.add('bg-[#f59e0b]', 'text-[#1e1e1e]', 'font-semibold');
                    
                    preMatchBtn.classList.remove('bg-[#f59e0b]', 'text-[#1e1e1e]', 'font-semibold');
                    preMatchBtn.classList.add('bg-[#3a3a3a]', 'text-[#a0a0a0]');
                    
                    // Switch content
                    preMatchContent.classList.add('hidden');
                    liveContent.classList.remove('hidden');
                    
                    // Adjust sidebar for live mode (simpler layout like canli.html)
                    sidebar.classList.add('live-mode');
                    
                    // Hide the middle matches section and expand odds section
                    if (mainContent) {
                        mainContent.classList.add('live-mode'); // This will hide it via CSS
                    }
                    if (oddsContent) {
                        oddsContent.classList.add('expanded-odds');
                    }
                    
                    // Load live matches
                    loadLiveMatches();
                    
                    // Initialize Sportradar iframe with default soccer image
                    const container = document.getElementById('sportradar-iframe-container');
                    if (container) {
                        container.innerHTML = '<img alt="Futbol sahası" class="w-full h-auto object-cover" height="400" src="/images/soccer.png" width="480"/>';
                    }
                });
                
                // Pre-match mode button click
                preMatchBtn.addEventListener('click', function() {
                    // Update button styles
                    preMatchBtn.classList.remove('bg-[#3a3a3a]', 'text-[#a0a0a0]');
                    preMatchBtn.classList.add('bg-[#f59e0b]', 'text-[#1e1e1e]', 'font-semibold');
                    
                    liveBtn.classList.remove('bg-[#f59e0b]', 'text-[#1e1e1e]', 'font-semibold');
                    liveBtn.classList.add('bg-[#3a3a3a]', 'text-[#a0a0a0]');
                    
                    // Switch content
                    liveContent.classList.add('hidden');
                    preMatchContent.classList.remove('hidden');
                    
                    // Restore normal sidebar
                    sidebar.classList.remove('live-mode');
                    
                    // Restore normal layout - show middle section, normal odds section
                    if (mainContent) {
                        mainContent.classList.remove('live-mode');
                    }
                    if (oddsContent) {
                        oddsContent.classList.remove('expanded-odds');
                    }
                    
                    // Restore the default soccer image in pre-match mode
                    const container = document.getElementById('sportradar-iframe-container');
                    if (container) {
                        container.innerHTML = '<img alt="Futbol sahası" class="w-full h-auto object-cover" height="400" src="/images/soccer.png" width="480"/>';
                    }
                });
            }
        }
        
        // Load live matches from canlibulten table
        function loadLiveMatches() {
            const container = document.getElementById('live-matches-container');
            if (!container) return;
            
            // Show loading state
            container.innerHTML = `
                <div class="text-center text-gray-400 py-4">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p class="mt-2">Canlı maçlar yükleniyor...</p>
                </div>
            `;
            
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            // Clean up old localStorage format first
            cleanupOldLocalStorage();
            
            // Load betting slip from localStorage
            loadBettingSlipFromStorage();
            
            // Initialize live mode if needed
            initLiveModeIfNeeded();
            
            // Close search dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const searchInput = document.getElementById('mobile-search-input');
                const resultsContainer = document.getElementById('mobile-search-results');
                
                if (searchInput && resultsContainer && !searchInput.parentElement.contains(event.target)) {
                    resultsContainer.classList.add('hidden');
                }
            });
            
            // Sport toggle functionality - REMOVED: This was causing conflicts with the main sport toggle
            // The main sport toggle is handled above at line 2358
            
            // Bet button functionality
            document.querySelectorAll('.bet-button').forEach(function(betButton) {
                betButton.addEventListener('click', function() {
                    placeBet();
                });
                
                // Also try mousedown as backup
                betButton.addEventListener('mousedown', function(e) {
                    if (e.button === 0) { // Left click only
                        e.preventDefault();
                        placeBet();
                    }
                });
                
                });
            
            // Initialize live mode functionality
            initModeSwitch();
        });
        
        // Live/Pre-match mode switching functionality
        function initModeSwitch() {
            const liveBtn = document.querySelector('.live-btn');
            const preMatchBtn = document.querySelector('.pre-match-btn');
            const liveContent = document.getElementById('live-content');
            const preMatchContent = document.getElementById('pre-match-content');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content'); // Middle matches section
            const oddsContent = document.querySelector('.odds-content'); // Right odds section
            
            if (liveBtn && preMatchBtn && liveContent && preMatchContent) {
                // Live mode button click
                liveBtn.addEventListener('click', function() {
                    // Update button styles
                    liveBtn.classList.remove('bg-[#3a3a3a]', 'text-[#a0a0a0]');
                    liveBtn.classList.add('bg-[#f59e0b]', 'text-[#1e1e1e]', 'font-semibold');
                    
                    preMatchBtn.classList.remove('bg-[#f59e0b]', 'text-[#1e1e1e]', 'font-semibold');
                    preMatchBtn.classList.add('bg-[#3a3a3a]', 'text-[#a0a0a0]');
                    
                    // Switch content
                    preMatchContent.classList.add('hidden');
                    liveContent.classList.remove('hidden');
                    
                    // Adjust sidebar for live mode (simpler layout like canli.html)
                    sidebar.classList.add('live-mode');
                    
                    // Hide the middle matches section and expand odds section
                    if (mainContent) {
                        mainContent.classList.add('live-mode'); // This will hide it via CSS
                    }
                    if (oddsContent) {
                        oddsContent.classList.add('expanded-odds');
                    }
                    
                    // Load live matches
                    loadLiveMatches();
                    
                    // Initialize Sportradar iframe with default soccer image
                    const container = document.getElementById('sportradar-iframe-container');
                    if (container) {
                        container.innerHTML = '<img alt="Futbol sahası" class="w-full h-auto object-cover" height="400" src="/images/soccer.png" width="480"/>';
                    }
                });
                
                // Pre-match mode button click
                preMatchBtn.addEventListener('click', function() {
                    // Update button styles
                    preMatchBtn.classList.remove('bg-[#3a3a3a]', 'text-[#a0a0a0]');
                    preMatchBtn.classList.add('bg-[#f59e0b]', 'text-[#1e1e1e]', 'font-semibold');
                    
                    liveBtn.classList.remove('bg-[#f59e0b]', 'text-[#1e1e1e]', 'font-semibold');
                    liveBtn.classList.add('bg-[#3a3a3a]', 'text-[#a0a0a0]');
                    
                    // Switch content
                    liveContent.classList.add('hidden');
                    preMatchContent.classList.remove('hidden');
                    
                    // Restore normal sidebar
                    sidebar.classList.remove('live-mode');
                    
                    // Restore normal layout - show middle section, normal odds section
                    if (mainContent) {
                        mainContent.classList.remove('live-mode');
                    }
                    if (oddsContent) {
                        oddsContent.classList.remove('expanded-odds');
                    }
                    
                    // Restore the default soccer image in pre-match mode
                    const container = document.getElementById('sportradar-iframe-container');
                    if (container) {
                        container.innerHTML = '<img alt="Futbol sahası" class="w-full h-auto object-cover" height="400" src="/images/soccer.png" width="480"/>';
                    }
                });
            }
        }
        
        // Load live matches from canlibulten table
        // Load live matches from canlibulten table
        function loadLiveMatches() {
            console.log('loadLiveMatches called');
            const container = document.getElementById('live-matches-container');
            if (!container) return;
            
            // Clear any existing odds data when switching to live mode
            window.allOddsData = null;
            window.selectedMatchId = null;
            currentMarketFilter = 'tumu';
            
            // Clear the odds display
            const oddsContainer = document.getElementById('kazanan-submenu');
            if (oddsContainer) {
                oddsContainer.innerHTML = `
                    <div class="text-center text-xs text-[#a0a0a0] py-8">
                        <i class="fas fa-futbol text-2xl mb-2 block text-[#f59e0b]"></i>
                        Canlı maç seçin
                        <div class="text-[10px] mt-1">Oranları görmek için bir canlı maça tıklayın</div>
                    </div>
                `;
            }
            
            // Reset filter buttons to default state
            document.querySelectorAll('.market-filter').forEach(button => {
                button.classList.remove('border-b-2', 'border-[#f59e0b]', 'text-[#f59e0b]', 'font-semibold');
                button.classList.add('text-[#6b6b6b]');
            });
            
            // Set TÜMÜ as active
            const tumuButton = document.querySelector('.market-filter[data-filter="tumu"]');
            if (tumuButton) {
                tumuButton.classList.remove('text-[#6b6b6b]');
                tumuButton.classList.add('border-b-2', 'border-[#f59e0b]', 'text-[#f59e0b]', 'font-semibold');
            }
            
            // Show loading state
            container.innerHTML = `
                <div class="text-center text-gray-400 py-4">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p class="mt-2">Canlı maçlar yükleniyor...</p>
                </div>
            `;
            
            // Fetch live matches from the server
            fetch('/api/live-matches', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayLiveMatches(data.matches);
                } else {
                    container.innerHTML = `
                        <div class="text-center text-gray-400 py-4">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p class="mt-2">Canlı maç verisi yüklenemedi</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                container.innerHTML = `
                    <div class="text-center text-gray-400 py-4">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p class="mt-2">Bağlantı hatası</p>
                    </div>
                `;
            });
        }
        
        // Load matches for a specific sport
        function loadMatchesForSport(sport) {
            console.log('loadMatchesForSport called with sport:', sport);
            
            // Check if we're in live mode or pre-match mode
            const liveContent = document.getElementById('live-content');
            const preMatchContent = document.getElementById('pre-match-content');
            
            if (liveContent && !liveContent.classList.contains('hidden')) {
                // We're in live mode - use the existing live matches logic
                const container = document.getElementById('live-matches-container');
                if (!container) return;
                
                // Show loading state
                container.innerHTML = `
                    <div class="text-center text-gray-400 py-4">
                        <i class="fas fa-spinner fa-spin"></i>
                        <p class="mt-2">Canlı maçlar yükleniyor...</p>
                    </div>
                `;
                
                // Fetch live matches from the server
                fetch('/api/live-matches', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayLiveMatches(data.matches);
                    } else {
                        container.innerHTML = `
                            <div class="text-center text-gray-400 py-4">
                                <i class="fas fa-exclamation-triangle"></i>
                                <p class="mt-2">Canlı maç verisi yüklenemedi</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    container.innerHTML = `
                        <div class="text-center text-gray-400 py-4">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p class="mt-2">Bağlantı hatası</p>
                        </div>
                    `;
                });
            } else {
                // We're in pre-match mode - filter existing matches
                filterPreMatchMatchesBySport(sport);
            }
        }
        
        // Filter pre-match matches by sport
        function filterPreMatchMatchesBySport(sport) {
            console.log('filterPreMatchMatchesBySport called with sport:', sport);
            
            const matchItems = document.querySelectorAll('[data-sport]');
            let visibleCount = 0;
            
            matchItems.forEach(item => {
                const itemSport = item.getAttribute('data-sport');
                if (itemSport && itemSport.toLowerCase() === sport.toLowerCase()) {
                    item.classList.remove('hidden');
                    visibleCount++;
                } else {
                    item.classList.add('hidden');
                }
            });
            
            // Update header
            const headerTitle = document.getElementById('main-header-title');
            if (headerTitle) {
                headerTitle.textContent = sport;
            }
            
            // Show message if no matches found
            if (visibleCount === 0) {
                const matchList = document.getElementById('match-list');
                if (matchList) {
                    matchList.innerHTML = `
                        <div class="text-center text-[#7a7a7a] py-8">
                            <i class="fas fa-info-circle text-2xl mb-2 block text-[#f59e0b]"></i>
                            ${sport} için maç bulunamadı
                            <div class="text-[10px] mt-1">Bu spor dalında şu anda maç bulunmuyor</div>
                        </div>
                    `;
                }
            }
            
            // Attach click listeners to visible matches
            attachMatchClickListeners();
        }
        
        // Display live matches in canli.html style
        function displayLiveMatches(matches) {
            console.log('displayLiveMatches called with matches:', matches);
            // Store matches for filtering
            window.allLiveMatches = matches;
            
            const container = document.getElementById('live-matches-container');
            if (!container) return;
            
            if (!matches || matches.length === 0) {
                container.innerHTML = `
                    <div class="text-center text-gray-400 py-4">
                        <i class="fas fa-info-circle"></i>
                        <p class="mt-2">Şu anda canlı maç bulunmuyor</p>
                    </div>
                `;
                return;
            }
            
            // Group matches by sport
            const groupedMatches = {};
            matches.forEach(match => {
                const sport = match.tur || match.tip || 'Diğer';
                if (!groupedMatches[sport]) {
                    groupedMatches[sport] = [];
                }
                groupedMatches[sport].push(match);
            });
            
            // Sort sports to put "Futbol" first
            const sortedSports = Object.keys(groupedMatches).sort((a, b) => {
                if (a === 'futbol' || a === 'Futbol') return -1;
                if (b === 'futbol' || b === 'Futbol') return 1;
                return a.localeCompare(b);
            });
            
            let html = '';
            
            // Display each sport section
            sortedSports.forEach(sport => {
                const sportMatches = groupedMatches[sport];
                const sportName = sport === 'futbol' ? 'Futbol' : sport.charAt(0).toUpperCase() + sport.slice(1);
                
                // Get sport icon based on sport type
                let sportIcon = 'fa-trophy'; // default
                let sportColor = 'text-green-500'; // default
                
                const sportLower = sport.toLowerCase();
                if (sportLower === 'futbol') {
                    sportIcon = 'fa-futbol';
                    sportColor = 'text-green-500';
                } else if (sportLower === 'basketbol') {
                    sportIcon = 'fa-basketball-ball';
                    sportColor = 'text-orange-500';
                } else if (sportLower === 'tenis') {
                    sportIcon = 'fa-table-tennis';
                    sportColor = 'text-green-400';
                } else if (sportLower === 'voleybol') {
                    sportIcon = 'fa-volleyball-ball';
                    sportColor = 'text-blue-400';
                } else if (sportLower === 'beysbol') {
                    sportIcon = 'fa-baseball-ball';
                    sportColor = 'text-red-400';
                } else if (sportLower === 'buzhokeyi') {
                    sportIcon = 'fa-hockey-puck';
                    sportColor = 'text-cyan-400';
                } else if (sportLower === 'beachvolley') {
                    sportIcon = 'fa-volleyball-ball';
                    sportColor = 'text-yellow-400';
                } else if (sportLower === 'atletizm') {
                    sportIcon = 'fa-running';
                    sportColor = 'text-purple-400';
                } else if (sportLower === 'badminton') {
                    sportIcon = 'fa-table-tennis';
                    sportColor = 'text-pink-400';
                } else if (sportLower === 'rugbyleague' || sportLower === 'ragbi' || sportLower === 'australianrules') {
                    sportIcon = 'fa-football-ball';
                    sportColor = 'text-amber-600';
                } else if (sportLower === 'golf') {
                    sportIcon = 'fa-flag';
                    sportColor = 'text-green-500';
                } else if (sportLower === 'amerikanfutbolu') {
                    sportIcon = 'fa-football-ball';
                    sportColor = 'text-red-600';
                } else if (sportLower === 'cricket') {
                    sportIcon = 'fa-baseball-ball';
                    sportColor = 'text-green-600';
                } else if (sportLower === 'snooker' || sportLower === 'masatenisi') {
                    sportIcon = 'fa-table-tennis';
                    sportColor = 'text-red-500';
                } else if (sportLower === 'virtualsports') {
                    sportIcon = 'fa-gamepad';
                    sportColor = 'text-purple-500';
                } else if (sportLower === 'darts') {
                    sportIcon = 'fa-dot-circle';
                    sportColor = 'text-red-600';
                } else if (sportLower === 'bisiklet') {
                    sportIcon = 'fa-bicycle';
                    sportColor = 'text-blue-500';
                } else if (sportLower === 'wintersports') {
                    sportIcon = 'fa-snowflake';
                    sportColor = 'text-cyan-300';
                } else if (sportLower === 'hentbol') {
                    sportIcon = 'fa-basketball-ball';
                    sportColor = 'text-orange-400';
                } else if (sportLower === 'formula1') {
                    sportIcon = 'fa-car-side';
                    sportColor = 'text-red-500';
                } else if (sportLower === 'trotting') {
                    sportIcon = 'fa-horse';
                    sportColor = 'text-amber-600';
                }
                
                html += `
                    <div class="bg-[#2a2a2a] mb-2 rounded sport-section" data-sport="${sport}">
                        <div class="flex items-center justify-between px-3 py-2 border-b border-[#3a3a3a] cursor-pointer sport-header" data-sport="${sport}">
                            <div class="flex items-center gap-2 text-xs font-semibold text-white">
                                <i class="fas ${sportIcon} ${sportColor} w-4 h-4"></i>
                                ${sportName}
                            </div>
                            <div class="text-[#7a7a7a] text-xs font-normal ml-auto">
                                ${sportMatches.length}
                            </div>
                            <button aria-label="Collapse ${sportName} section" class="collapse-btn text-[#4caf50] bg-transparent rounded px-1 py-1" type="button" data-sport="${sport}">
                                <i class="fas ${sport === 'futbol' || sport === 'Futbol' ? 'fa-chevron-up' : 'fa-chevron-down'} text-xs"></i>
                            </button>
                        </div>
                        <div class="sport-content" data-sport="${sport}" style="display: ${sport === 'futbol' || sport === 'Futbol' ? 'block' : 'none'};">
                `;
                
                // Group matches by country and league
                const countryGroups = {};
                sportMatches.forEach(match => {
                    const country = match.ulke || 'Diğer';
                    const league = match.lig || 'Bilinmeyen Lig';
                    const key = `${country}_${league}`;
                    
                    if (!countryGroups[key]) {
                        countryGroups[key] = {
                            country: country,
                            league: league,
                            matches: []
                        };
                    }
                    countryGroups[key].matches.push(match);
                });
                
                // Display each country/league group
                Object.values(countryGroups).forEach(group => {
                    const flagSrc = getCountryFlag(group.country);
                    html += `
                        <div class="px-3 pt-2 pb-1">
                            <div class="flex items-center gap-2 text-xs font-semibold text-white mb-1">
                                <img alt="${group.country} bayrağı" class="w-4 h-3 object-cover rounded-sm flex-shrink-0" height="12" src="${flagSrc}" width="16" onerror="this.src='/images/flags/defaults.png'"/>
                                <span class="truncate">${group.country}</span>
                            </div>
                            <div class="text-[#7a7a7a] text-[11px] font-normal mb-2 ml-6">
                                ${group.league}
                            </div>
                    `;
                    
                    // Display matches
                    group.matches.forEach((match, index) => {
                        const homeTeam = decodeHtmlEntities(match.evsahibi_isim || 'Ev Sahibi');
                        const awayTeam = decodeHtmlEntities(match.misafir_isim || 'Misafir');
                        const score = match.skor || '0-0';
                        const minute = match.dakika || '';
                        const status = match.sure_detay || 'Canlı';
                        const time = match.baslangic ? new Date(match.baslangic).toLocaleTimeString('tr-TR', {hour: '2-digit', minute: '2-digit'}) : '';
                        
                        // Parse score properly - handle both : and - separators
                        let homeScore = '0';
                        let awayScore = '0';
                        if (score && (score.includes(':') || score.includes('-'))) {
                            const separator = score.includes(':') ? ':' : '-';
                            const scoreParts = score.split(separator);
                            if (scoreParts.length === 2) {
                                homeScore = scoreParts[0].trim();
                                awayScore = scoreParts[1].trim();
                            }
                        }
                        
                        html += `
                            <div class="bg-[#3a3a3a] rounded-md border border-[#4ade80] p-2 space-y-1 mb-2 live-match-item cursor-pointer hover:bg-[#4a4a4a] transition-colors" 
                                 data-match-id="${match.mac_id}" 
                                 data-sport="${match.tur || match.tip || 'Diğer'}" 
                                 data-betradar-id="${match.betradar_id || ''}"
                                 data-home-team="${homeTeam}"
                                 data-away-team="${awayTeam}"
                                 data-league="${match.lig_isim || match.lig || 'Lig'}"
                                 data-start-time="${match.baslangic || ''}">
                                <div class="text-xs font-semibold text-white leading-tight">
                                    ${homeTeam}
                                    <span class="float-right font-bold text-yellow-400">
                                        ${homeScore}
                                    </span>
                                    </div>
                                <div class="text-xs font-semibold text-white leading-tight">
                                    ${awayTeam}
                                    <span class="float-right font-bold text-yellow-400">
                                        ${awayScore}
                                    </span>
                                    </div>
                                <div class="text-[9px] font-normal text-gray-400 flex items-center space-x-1">
                                    <span>${minute ? minute + "'" : (status === 'Başlamadı' ? '0\'' : status)}</span>
                                    <span class="ml-1">${score}</span>
                                    <span class="ml-auto bg-gray-700 rounded px-1 text-[10px] font-semibold w-8 text-center">
                                        +${match.oran_adet || '0'}
                                    </span>
                                    <button aria-label="Star" class="ml-1 text-gray-400 hover:text-yellow-400">
                                        <i class="far fa-star"></i>
                                    </button>
                                </div>
                                <div class="text-[9px] font-normal text-gray-400 text-right">
                                        ${time}
                                    </div>
                                <div class="grid grid-cols-3 text-xs font-semibold rounded-md overflow-hidden select-none">
                                    <button class="${match.oran1 && match.oran1 > 0 ? 'bg-[#4a4a4a] text-yellow-400 hover:bg-[#5a5a5a]' : 'bg-[#2a2a2a] text-gray-600 cursor-not-allowed'} py-1 border-r border-[#555] odds-btn" data-bet-type="1" data-odds="${match.oran1 || 0}" data-market="Kazanan" data-selection="Evsahibi Kazanır" ${!match.oran1 || match.oran1 <= 0 ? 'disabled' : ''}>
                                        ${match.oran1 && match.oran1 > 0 ? parseFloat(match.oran1).toFixed(2) : '<i class="fas fa-lock"></i>'}
                            </button>
                                    <button class="${match.oran0 && match.oran0 > 0 ? 'bg-[#4a4a4a] text-yellow-400 hover:bg-[#5a5a5a]' : 'bg-[#2a2a2a] text-gray-600 cursor-not-allowed'} py-1 border-r border-[#555] odds-btn" data-bet-type="X" data-odds="${match.oran0 || 0}" data-market="Kazanan" data-selection="Berabere" ${!match.oran0 || match.oran0 <= 0 ? 'disabled' : ''}>
                                        ${match.oran0 && match.oran0 > 0 ? parseFloat(match.oran0).toFixed(2) : '<i class="fas fa-lock"></i>'}
                                    </button>
                                    <button class="${match.oran2 && match.oran2 > 0 ? 'bg-[#4a4a4a] text-yellow-400 hover:bg-[#5a5a5a]' : 'bg-[#2a2a2a] text-gray-600 cursor-not-allowed'} py-1 odds-btn" data-bet-type="2" data-odds="${match.oran2 || 0}" data-market="Kazanan" data-selection="Deplasman Kazanır" ${!match.oran2 || match.oran2 <= 0 ? 'disabled' : ''}>
                                        ${match.oran2 && match.oran2 > 0 ? parseFloat(match.oran2).toFixed(2) : '<i class="fas fa-lock"></i>'}
                                    </button>
                                </div>
                            </div>
                        `;
                    });
                    
                    html += `</div>`;
                });
                
                html += `</div>`;
            });
            
            container.innerHTML = html;
            
            // Add click listeners to collapse buttons
            container.querySelectorAll('.collapse-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation(); // Prevent triggering parent click
                    const sport = this.dataset.sport;
                    const content = container.querySelector(`.sport-content[data-sport="${sport}"]`);
                    const icon = this.querySelector('i');
                    
                    if (content.style.display === 'none') {
                        // Expand
                        content.style.display = 'block';
                        icon.classList.remove('fa-chevron-down');
                        icon.classList.add('fa-chevron-up');
                    } else {
                        // Collapse
                        content.style.display = 'none';
                        icon.classList.remove('fa-chevron-up');
                        icon.classList.add('fa-chevron-down');
                    }
                });
            });
            
            // Add click listeners to sport headers (entire header area)
            container.querySelectorAll('.sport-header').forEach(header => {
                header.addEventListener('click', function() {
                    const sport = this.dataset.sport;
                    const content = container.querySelector(`.sport-content[data-sport="${sport}"]`);
                    const icon = this.querySelector('.collapse-btn i');
                    
                    if (content.style.display === 'none') {
                        // Expand
                        content.style.display = 'block';
                        icon.classList.remove('fa-chevron-down');
                        icon.classList.add('fa-chevron-up');
                    } else {
                        // Collapse
                        content.style.display = 'none';
                        icon.classList.remove('fa-chevron-up');
                        icon.classList.add('fa-chevron-down');
                    }
                });
            });
            
            // Add click listeners to live match items
            container.querySelectorAll('.live-match-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    // Don't trigger if clicking on odds buttons
                    if (e.target.classList.contains('odds-btn')) {
                        return;
                    }
                    
                    const matchId = this.dataset.matchId;
                    const betradarId = this.dataset.betradarId;
                    console.log('Live match clicked:', { matchId, betradarId });
                    // Remove selected class from all live match items
                    container.querySelectorAll('.live-match-item').forEach(matchItem => {
                        matchItem.classList.remove('selected');
                    });
                    
                    // Add selected class to clicked item
                    this.classList.add('selected');
                    
                    // Update the Sportradar iframe if we have a betradar_id
                    if (betradarId) {
                        // Get match data from the clicked element
                        const matchData = {
                            evsahibi_isim: this.dataset.homeTeam || this.querySelector('.home-team')?.textContent?.trim(),
                            misafir_isim: this.dataset.awayTeam || this.querySelector('.away-team')?.textContent?.trim(),
                            lig_isim: this.dataset.league || this.querySelector('.league')?.textContent?.trim(),
                            baslangic: this.dataset.startTime || this.querySelector('.start-time')?.textContent?.trim()
                        };
                        updateSportradarIframe(betradarId, matchData);
                    }
                    
                    // Fetch and display live match odds (for right panel)
                    if (matchId) {
                        fetchLiveMatchOdds(matchId, this);
                    }
                });
                
                // Add click listeners to odds buttons within each match
                item.querySelectorAll('.odds-btn').forEach(oddsBtn => {
                    oddsBtn.addEventListener('click', function(e) {
                        e.stopPropagation(); // Prevent match click
                        
                        // Don't process if button is disabled (locked)
                        if (this.disabled || this.classList.contains('cursor-not-allowed')) {
                            return;
                        }
                        
                        const betType = this.dataset.betType;
                        const odds = parseFloat(this.dataset.odds);
                        const market = this.dataset.market;
                        const selection = this.dataset.selection;
                        const matchId = item.dataset.matchId;
                        
                        if (odds && odds > 0) {
                            // Add to betting slip
                            addToBettingSlip({
                                betType: betType,
                                odds: odds,
                                market: market,
                                selection: selection,
                                matchId: matchId,
                                isLive: true
                            });
                            
                            // Visual feedback
                            this.style.backgroundColor = '#f59e0b';
                            this.style.color = '#1a1a1a';
                            setTimeout(() => {
                                this.style.backgroundColor = '#4a4a4a';
                                this.style.color = '#fbbf24';
                            }, 200);
                        }
                    });
                });
            });
        }

        // Essential functions for page functionality
        function filterMatchesBySport(sportType) {
            console.log('filterMatchesBySport called with:', sportType);
            
            // Clear any existing league selection
            document.querySelectorAll('.league-btn').forEach(btn => {
                btn.classList.remove('bg-[#3a3a3a]');
                btn.classList.add('bg-[#2a2a2a]');
            });
            
            // Check if we have dynamic matches loaded (from league selection)
            if (window.allMatches && window.allMatches.length > 0) {
                console.log('Filtering dynamic matches for sport:', sportType);
                const filteredMatches = window.allMatches.filter(match => {
                    const matchSport = match.tur || match.sport || 'Diğer';
                    const normalizedMatchSport = matchSport.trim().toLowerCase();
                    const normalizedSportType = sportType.trim().toLowerCase();
                    return normalizedMatchSport === normalizedSportType;
                });
                
                console.log('Filtered dynamic matches:', filteredMatches.length);
                if (filteredMatches.length > 0) {
                    updateMatchesDisplay(filteredMatches, `${sportType.charAt(0).toUpperCase() + sportType.slice(1)} Maçları`);
                    updateHeaders(`${sportType.charAt(0).toUpperCase() + sportType.slice(1)} `);
                    return;
                }
            }
            
            // Fallback: Filter existing match items using hidden class (like initial load)
            const matchItems = document.querySelectorAll('.match-item');
            console.log('Found match items:', matchItems.length);
            
            let visibleCount = 0;
            matchItems.forEach(item => {
                const matchSport = item.dataset.sport;
                console.log('Match sport:', matchSport, 'Looking for:', sportType.toLowerCase());
                
                // More flexible matching - trim whitespace and case insensitive
                const normalizedMatchSport = matchSport ? matchSport.trim().toLowerCase() : '';
                const normalizedSportType = sportType.trim().toLowerCase();
                const isVisible = normalizedMatchSport === normalizedSportType;
                
                if (isVisible) {
                    item.classList.remove('hidden');
                    visibleCount++;
                    console.log('Showing match:', item);
                } else {
                    item.classList.add('hidden');
                    console.log('Hiding match:', item);
                }
            });
            
            console.log('Visible count:', visibleCount);
            
            // If no matches found, show empty state
            if (visibleCount === 0) {
                const matchList = document.getElementById('match-list');
                if (matchList) {
                    matchList.innerHTML = `
                        <div class="text-center text-[#7a7a7a] py-8">
                            <i class="fas fa-info-circle text-2xl mb-2 block text-[#f59e0b]"></i>
                            ${sportType.charAt(0).toUpperCase() + sportType.slice(1)} için maç bulunamadı
                        </div>
                    `;
                }
            }
            
            updateHeaders(`${sportType.charAt(0).toUpperCase() + sportType.slice(1)} `);
            attachMatchClickListeners();
        }
        
        // Filter live matches by sport
        function filterLiveMatchesBySport(sportType) {
            // Check if we have live matches data stored
            if (!window.allLiveMatches || window.allLiveMatches.length === 0) {
                return;
            }
            
            // Filter matches by sport
            const filteredMatches = window.allLiveMatches.filter(match => {
                const matchSport = match.tur || match.tip || 'Diğer';
                return matchSport.toLowerCase() === sportType.toLowerCase();
            });
            
            // Re-display filtered matches
            displayLiveMatches(filteredMatches);
        }
        
        // Show all live matches
        function showAllLiveMatches() {
            // Check if we have live matches data stored
            if (!window.allLiveMatches || window.allLiveMatches.length === 0) {
                return;
            }
            
            // Re-display all matches
            displayLiveMatches(window.allLiveMatches);
            }

        function filterMatchesByLeagueAndSport(leagueName, sport) {
            const matchItems = document.querySelectorAll('.match-item');
            
            matchItems.forEach(item => {
                const league = item.dataset.league;
                const matchSport = item.dataset.sport;
                item.style.display = (league === leagueName && matchSport === sport) ? 'grid' : 'none';
            });
            
            attachMatchClickListeners();
        }

        function showAllMatches() {
            // Clear any existing league selection
            document.querySelectorAll('.league-btn').forEach(btn => {
                btn.classList.remove('bg-[#3a3a3a]');
                btn.classList.add('bg-[#2a2a2a]');
            });
            
            // Show all matches by removing hidden class (like initial load)
            const matchItems = document.querySelectorAll('.match-item');
            matchItems.forEach(item => {
                item.classList.remove('hidden');
            });
            updateHeaders('Futbol Maçları');
            attachMatchClickListeners();
        }
        
        function attachMatchClickListeners() {
            console.log('attachMatchClickListeners called');
            
            // Remove existing listeners first
            const existingButtons = document.querySelectorAll('[data-match-id]');
            existingButtons.forEach(btn => {
                btn.replaceWith(btn.cloneNode(true));
            });
            
            // Add new listeners to all match items (both .match-item and [data-match-id])
            const newButtons = document.querySelectorAll('[data-match-id]');
            console.log('Found match buttons:', newButtons.length);
            
            newButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const matchId = this.dataset.matchId;
                    console.log('Match clicked, matchId:', matchId);
                    
                    if (matchId) {
                        selectMatch(matchId);
                    } else {
                        console.log('No matchId found');
                    }
                });
            });
        }
        
        function attachOddsButtonListeners() {
            
            // Remove existing listeners first
            const existingButtons = document.querySelectorAll('.odds-btn');
            existingButtons.forEach(btn => {
                btn.replaceWith(btn.cloneNode(true));
            });
            
            // Add new listeners
            const newButtons = document.querySelectorAll('.odds-btn');
            newButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    if (this.disabled || this.classList.contains('cursor-not-allowed')) {
                        return;
                    }
                    
                    const betType = this.dataset.betType;
                    const odds = parseFloat(this.dataset.odds);
                    const market = this.dataset.market || 'Kazanan';
                    const selection = this.dataset.selection || this.textContent.trim();
                    
                    
                    // CRITICAL: Enhanced validation for odds and bet type
                    if (!odds || odds <= 0 || odds === 0 || odds === '0' || odds === '0.00') {
                        alert('Bu seçenek için geçerli oran bulunamadı. Lütfen başka bir seçenek seçin.');
                        return;
                    }
                    
                    if (!betType) {
                        alert('Geçersiz bahis türü. Lütfen geçerli bir seçenek seçin.');
                        return;
                    }
                    
                    // For pre-match matches, use addBet function
                    if (selectedMatchId && matchData[selectedMatchId]) {
                        const match = matchData[selectedMatchId];
                        const matchId = match.displayId;
                        addBet(betType, odds, matchId, market, selection);
                    } else {
                        // For live matches, use the actual match ID from the selected live match
                        const selectedLiveMatch = document.querySelector('.live-match-item.selected');
                        if (selectedLiveMatch) {
                            const liveMatchId = selectedLiveMatch.dataset.matchId;
                            addToBetSlip(betType, odds, liveMatchId, market, selection);
                        } else {
                            // Fallback for live matches without matchData
                            const tempMatchId = 'live_' + Date.now();
                            addToBetSlip(betType, odds, tempMatchId, market, selection);
                        }
                    }
                    
                    // Create unique bet ID for tracking
                    const usedMatchId = selectedMatchId || (selectedLiveMatch ? selectedLiveMatch.dataset.matchId : ('live_' + Date.now()));
                    const betId = `${usedMatchId}_${market}_${betType}`;
                    
                    // Mark button as selected with visual feedback
                    markOddsButtonAsSelected(this, betId);
                });
            });
        }

        function updateHeaders(title) {
            // CRITICAL: Validate title before updating
            if (!title || title === 'undefined' || title === 'null' || title.trim() === '') {
                title = 'Maç Seçin';
            }
            
            const elements = ['main-header-title', 'selected-league-title'];
            elements.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.textContent = title;
            });
        }

        function selectMatch(matchId) {
            console.log('selectMatch called with matchId:', matchId);
            
            // CRITICAL: Validate matchId before processing
            if (!matchId || matchId === 'undefined' || matchId === 'null') {
                console.log('Invalid matchId:', matchId);
                return;
            }
            
            // Convert matchId to number if it's a string
            const numericMatchId = parseInt(matchId);
            
            if (isNaN(numericMatchId)) {
                console.log('matchId is not a number:', matchId);
                return;
            }
            
            const eventId = displayIdToEventId[numericMatchId];
            console.log('Found eventId:', eventId, 'for displayId:', numericMatchId);
            
            if (!eventId) {
                console.log('No eventId found for displayId:', numericMatchId);
                console.log('displayIdToEventId:', displayIdToEventId);
                return;
            }
            
            selectedMatchId = eventId;
            const match = matchData[eventId];
            console.log('Found match data:', match);
            
            if (match) {
                // CRITICAL: Validate match data before displaying
                if (!match.home || !match.away || !match.league) {
                    return;
                }
                
                
                const leagueTitle = document.getElementById('selected-league-title');
                if (leagueTitle) leagueTitle.textContent = decodeHtmlEntities(match.league);
                
                const matchDate = document.getElementById('selected-match-date');
                if (matchDate) matchDate.textContent = match.date;
                
                // Update country flag
                const countryFlag = document.getElementById('selected-country-flag');
                if (countryFlag && match.country) {
                    countryFlag.src = getCountryFlag(match.country);
                    countryFlag.alt = `${match.country} bayrağı`;
                }
                
                // Show loading state immediately
                showOddsLoadingState();
                
                // Make sure the odds container is visible
                const oddsContainer = document.getElementById('kazanan-submenu');
                if (oddsContainer) {
                    oddsContainer.classList.remove('hidden');
                }
                
                updateOddsDisplay(match);
                
                // Clear previous match selections and odds
                document.querySelectorAll('.match-item').forEach(item => {
                    item.classList.remove('text-[#f59e0b]', 'bg-[#3a3a3a]');
                    item.classList.add('text-[#a0a0a0]');
                });
                
                // Clear previous odds selections when switching matches
                if (selectedMatchId !== matchId) {
                    document.querySelectorAll('.odds-btn.selected').forEach(btn => {
                        btn.classList.remove('selected', 'newly-selected');
                    });
                }
                
                const selectedItem = document.querySelector(`[data-match-id="${matchId}"]`);
                if (selectedItem) {
                    selectedItem.classList.remove('text-[#a0a0a0]');
                    selectedItem.classList.add('text-[#f59e0b]', 'bg-[#3a3a3a]');
                }
            }
        }
        
        function showOddsLoadingState() {
            const oddsContainer = document.getElementById('kazanan-submenu');
            
            if (oddsContainer) {
                // Remove hidden class to make container visible
                oddsContainer.classList.remove('hidden');
                
                oddsContainer.innerHTML = `
                    <div class="text-center text-xs text-[#a0a0a0] py-8">
                        <i class="fas fa-spinner fa-spin text-2xl mb-2 block text-[#f59e0b]"></i>
                        Oranlar Yükleniyor
                        <div class="text-[10px] mt-1">Lütfen bekleyin...</div>
                    </div>
                `;
            } else {
            }
        }
        
        function updateOddsDisplay(match) {
            const oddsContainer = document.getElementById('kazanan-submenu');
            
            if (oddsContainer) {
                // CRITICAL: Validate match data before processing
                if (!match) {
                    oddsContainer.innerHTML = `
                        <div class="text-center text-xs text-[#a0a0a0] py-8">
                            <i class="fas fa-exclamation-triangle text-2xl mb-2 block text-[#f59e0b]"></i>
                            Maç verisi bulunamadı
                            <div class="text-[10px] mt-1">Lütfen tekrar deneyin</div>
                        </div>
                    `;
                    return;
                }
                
                let apiResponseReceived = false;
                
                // Fetch real odds from API with timeout
                if (match.eventId) {
                    
                    // Start API call
                    const apiPromise = fetchMatchOdds(match.eventId, match);
                    
                    // Set a timeout for 2 seconds
                    const timeoutPromise = new Promise((resolve) => {
                        setTimeout(() => {
                            if (!apiResponseReceived) {
                                showDatabaseOdds(match);
                            }
                        }, 2000);
                    });
                    
                    // Handle API response
                    apiPromise.then(() => {
                        apiResponseReceived = true;
                    }).catch(() => {
                        if (!apiResponseReceived) {
                            showDatabaseOdds(match);
                        }
                    });
                } else {
                    showDatabaseOdds(match);
                }
            }
        }
        
        function showDatabaseOdds(match) {
            console.log('showDatabaseOdds called with match:', match);
            const oddsContainer = document.getElementById('kazanan-submenu');
            
            if (oddsContainer) {
                // CRITICAL: Validate match data before displaying
                if (!match || match.odds1 === undefined || match.odds1 === null || 
                    match.oddsX === undefined || match.oddsX === null || 
                    match.odds2 === undefined || match.odds2 === null) {
                    console.log('Invalid match data or missing odds:', {
                        match: match,
                        odds1: match?.odds1,
                        oddsX: match?.oddsX,
                        odds2: match?.odds2
                    });
                    oddsContainer.innerHTML = `
                        <div class="text-center text-xs text-[#a0a0a0] py-8">
                            <i class="fas fa-exclamation-triangle text-2xl mb-2 block text-[#f59e0b]"></i>
                            Geçersiz maç verisi
                            <div class="text-[10px] mt-1">Bu maç için oran bilgisi bulunamadı</div>
                        </div>
                    `;
                    return;
                }
                
                // CRITICAL: Check if any valid odds exist
                const hasValidOdds = (match.odds1 && match.odds1 > 0) || 
                                   (match.oddsX && match.oddsX > 0) || 
                                   (match.odds2 && match.odds2 > 0);
                
                if (!hasValidOdds) {
                    oddsContainer.innerHTML = `
                        <div class="text-center text-xs text-[#a0a0a0] py-8">
                            <i class="fas fa-exclamation-triangle text-2xl mb-2 block text-[#f59e0b]"></i>
                            Geçerli oran bulunamadı
                            <div class="text-[10px] mt-1">Bu maç için şu anda oran mevcut değil</div>
                        </div>
                    `;
                    return;
                }
                
                // Remove hidden class to make container visible
                oddsContainer.classList.remove('hidden');
                oddsContainer.innerHTML = `
                    <section class="mb-4">
                        <header class="flex justify-between items-center text-xs font-semibold mb-2">
                            <div class="flex items-center space-x-1">
                                <i class="far fa-star text-[#6b6b6b]"></i>
                                <span>Kazanan</span>
                            </div>
                        </header>
                        <div class="grid grid-cols-3 gap-1 mb-1 text-center text-[#7a7a7a] text-[10px]">
                            <div class="bg-[#3a3a3a] rounded-md py-1">Evsahibi</div>
                            <div class="bg-[#3a3a3a] rounded-md py-1">Beraberlik</div>
                            <div class="bg-[#3a3a3a] rounded-md py-1">Deplasman</div>
                        </div>
                        <div class="grid grid-cols-3 gap-1 text-center text-xs font-semibold">
                            <button class="${!match.odds1 || match.odds1 <= 0 ? 'bg-[#2a2a2a] cursor-not-allowed opacity-50 text-[#6b6b6b]' : 'bg-[#3a3a3a] hover:bg-[#4a4a4a] text-[#f2b90f] odds-btn'} rounded-md py-1" ${!match.odds1 || match.odds1 <= 0 ? 'disabled' : ''} data-bet-type="1" data-odds="${match.odds1 && match.odds1 > 0 ? match.odds1 : 0}" data-market="Maç Sonucu" data-selection="Evsahibi">
                                ${!match.odds1 || match.odds1 <= 0 ? '<i class="fas fa-lock"></i>' : match.odds1.toFixed(2)}
                            </button>
                            <button class="${!match.oddsX || match.oddsX <= 0 ? 'bg-[#2a2a2a] cursor-not-allowed opacity-50 text-[#6b6b6b]' : 'bg-[#3a3a3a] hover:bg-[#4a4a4a] text-[#f2b90f] odds-btn'} rounded-md py-1" ${!match.oddsX || match.oddsX <= 0 ? 'disabled' : ''} data-bet-type="X" data-odds="${match.oddsX && match.oddsX > 0 ? match.oddsX : 0}" data-market="Maç Sonucu" data-selection="Berabere">
                                ${!match.oddsX || match.oddsX <= 0 ? '<i class="fas fa-lock"></i>' : match.oddsX.toFixed(2)}
                            </button>
                            <button class="${!match.odds2 || match.odds2 <= 0 ? 'bg-[#2a2a2a] cursor-not-allowed opacity-50 text-[#6b6b6b]' : 'bg-[#3a3a3a] hover:bg-[#4a4a4a] text-[#f2b90f] odds-btn'} rounded-md py-1" ${!match.odds2 || match.odds2 <= 0 ? 'disabled' : ''} data-bet-type="2" data-odds="${match.odds2 && match.odds2 > 0 ? match.odds2 : 0}" data-market="Maç Sonucu" data-selection="Deplasman">
                                ${!match.odds2 || match.odds2 <= 0 ? '<i class="fas fa-lock"></i>' : match.odds2.toFixed(2)}
                            </button>
                        </div>
                    </section>`;
                
                // Attach event listeners to the odds buttons
                attachOddsButtonListeners();
                
                // Restore odds button selections after a short delay
                setTimeout(() => {
                    updateOddsButtonSelections();
                }, 100);
            }
        }
        
        async function fetchMatchOdds(eventId, match) {
            try {
                // CRITICAL: Validate eventId before making API call
                if (!eventId || eventId === 'undefined' || eventId === 'null') {
                    throw new Error('Geçersiz maç ID\'si');
                }
                
                const apiUrl = `/Match/Odds/${eventId}`;
                
                const response = await fetch(apiUrl);
                
                if (!response.ok) {
                    throw new Error(`API request failed with status: ${response.status}`);
                }
                
                const oddsData = await response.json();
                
                // CRITICAL: Validate API response data
                if (!oddsData || typeof oddsData !== 'object') {
                    throw new Error('Geçersiz API yanıtı');
                }
                
                // Process odds data similar to live matches
                let processedOddsData = oddsData;
                
                // Check if main_odds is empty but additional_odds has data
                if ((!oddsData.main_odds || Object.keys(oddsData.main_odds).length === 0) && 
                    oddsData.additional_odds) {
                    
                    let matchResult = null;
                    let reconstructedMainOdds = null;
                    
                    // Try "Maç Sonucu" first (Turkish)
                    if (oddsData.additional_odds['Maç Sonucu']) {
                        matchResult = oddsData.additional_odds['Maç Sonucu'];
                        reconstructedMainOdds = {
                            '1': matchResult['1'] || 0,
                            'X': matchResult['X'] || 0,
                            '2': matchResult['2'] || 0
                        };
                    }
                    // Try "Regular Time (3-way)" (English)
                    else if (oddsData.additional_odds['Regular Time (3-way)']) {
                        matchResult = oddsData.additional_odds['Regular Time (3-way)'];
                        
                        // Extract team names and map to 1X2 format
                        const teamNames = Object.keys(matchResult);
                        const oddsValues = Object.values(matchResult);
                        
                        // Find "Beraberlik" (Draw) or similar
                        const drawIndex = teamNames.findIndex(name => 
                            name.toLowerCase().includes('beraberlik') || 
                            name.toLowerCase().includes('draw') ||
                            name.toLowerCase().includes('tie')
                        );
                        
                        if (drawIndex !== -1 && teamNames.length >= 3) {
                            // Standard 1X2 format with draw
                            reconstructedMainOdds = {
                                '1': oddsValues[0] || 0,
                                'X': oddsValues[drawIndex] || 0,
                                '2': oddsValues[1] || 0
                            };
                        } else if (teamNames.length >= 2) {
                            // Two-way format, no draw
                            reconstructedMainOdds = {
                                '1': oddsValues[0] || 0,
                                'X': 0, // No draw option
                                '2': oddsValues[1] || 0
                            };
                        }
                    }
                    
                    // Create modified oddsData with processed odds if we found valid data
                    if (reconstructedMainOdds) {
                        processedOddsData = {
                            ...oddsData,
                            main_odds: reconstructedMainOdds
                        };
                    }
                }
                
                // Store the processed odds data for filtering
                window.allOddsData = processedOddsData;
                window.selectedMatchId = eventId;
                
                // CRITICAL: Update the odds display with the fetched data
                // For pre-match odds, set isLive to false
                if (match) {
                    match.isLive = false;
                }
                updateOddsDisplayFromAPI(processedOddsData, match);
                
                // Return the odds data for mobile display
                return oddsData;
                
            } catch (error) {
                
                // Don't display error message here - let the calling function handle it
                // This allows the fallback to showDatabaseOdds to work
                throw error; // Re-throw the error so updateOddsDisplay can catch it
            }
        }
        
        async function fetchLiveMatchOdds(matchId, matchElement) {
            console.log('fetchLiveMatchOdds called with matchId:', matchId);
            const oddsContainer = document.getElementById('kazanan-submenu');
            
            // CRITICAL: Validate matchId before making API call
            if (!matchId || matchId === 'undefined' || matchId === 'null') {
                if (oddsContainer) {
                    oddsContainer.innerHTML = `
                        <div class="text-center text-xs text-[#a0a0a0] py-8">
                            <i class="fas fa-exclamation-triangle text-2xl mb-2 block text-[#f59e0b]"></i>
                            Geçersiz maç ID'si
                            <div class="text-[10px] mt-1">Lütfen tekrar deneyin</div>
                        </div>
                    `;
                }
                return;
            }
            
            // Show loading state
            if (oddsContainer) {
                oddsContainer.innerHTML = `
                    <div class="text-center text-xs text-[#a0a0a0] py-8">
                        <i class="fas fa-spinner fa-spin text-2xl mb-2 block"></i>
                        Canlı oranlar yükleniyor...
                    </div>
                `;
            }
            
            try {
                const apiUrl = `/Live/Odds/${matchId}`;
                
                const response = await fetch(apiUrl);
                
                if (!response.ok) {
                    throw new Error(`Live API request failed with status: ${response.status}`);
                }
                
                const oddsData = await response.json();
                console.log('Live odds API response:', oddsData);
                
                // CRITICAL: Validate API response structure
                if (!oddsData || typeof oddsData !== 'object') {
                    throw new Error('Live API returned invalid response format');
                }
                
                if (oddsData.success) {
                    // Check if we have any odds data (main_odds or additional_odds)
                    let hasValidOdds = false;
                    let processedOdds = {};
                    
                    // Check main_odds first
                    if (oddsData.main_odds && typeof oddsData.main_odds === 'object') {
                        const validMainOdds = Object.values(oddsData.main_odds).some(odds => 
                        odds && odds > 0 && odds !== '0' && odds !== '0.00'
                    );
                        if (validMainOdds) {
                            processedOdds = { ...processedOdds, ...oddsData.main_odds };
                            hasValidOdds = true;
                        }
                    }
                    
                    // If no main_odds, check additional_odds for main markets
                    if (!hasValidOdds && oddsData.additional_odds && typeof oddsData.additional_odds === 'object') {
                        console.log('Checking additional_odds for main markets:', Object.keys(oddsData.additional_odds));
                        // Look for main markets like "Maç Sonucu" (1X2) - Turkish
                        if (oddsData.additional_odds['Maç Sonucu']) {
                            const matchResult = oddsData.additional_odds['Maç Sonucu'];
                            processedOdds = {
                                '1': matchResult['1'] || matchResult['1'] || 0,
                                'X': matchResult['X'] || matchResult['X'] || 0,
                                '2': matchResult['2'] || matchResult['2'] || 0
                            };
                            hasValidOdds = true;
                        }
                        // Look for "Uzatmalar dahil" (1X2) - Turkish
                        else if (oddsData.additional_odds['Uzatmalar dahil']) {
                            console.log('Found "Uzatmalar dahil" market:', oddsData.additional_odds['Uzatmalar dahil']);
                            const matchResult = oddsData.additional_odds['Uzatmalar dahil'];
                            
                            // Extract team names and map to 1X2 format
                            const teamNames = Object.keys(matchResult);
                            const oddsValues = Object.values(matchResult);
                            
                            console.log('Team names:', teamNames, 'Odds values:', oddsValues);
                            
                            if (teamNames.length >= 2) {
                                // Two-way format, no draw
                                processedOdds = {
                                    '1': oddsValues[0] || 0,
                                    'X': 0, // No draw option
                                    '2': oddsValues[1] || 0
                                };
                                console.log('Processed odds for "Uzatmalar dahil":', processedOdds);
                            }
                            hasValidOdds = true;
                        }
                        // Look for "Maç Oranı" (1X2) - Turkish
                        else if (oddsData.additional_odds['Maç Oranı']) {
                            console.log('Found "Maç Oranı" market:', oddsData.additional_odds['Maç Oranı']);
                            const matchResult = oddsData.additional_odds['Maç Oranı'];
                            
                            // Extract team names and map to 1X2 format
                            const teamNames = Object.keys(matchResult);
                            const oddsValues = Object.values(matchResult);
                            
                            console.log('Team names:', teamNames, 'Odds values:', oddsValues);
                            
                            if (teamNames.length >= 2) {
                                // Two-way format, no draw
                                processedOdds = {
                                    '1': oddsValues[0] || 0,
                                    'X': 0, // No draw option
                                    '2': oddsValues[1] || 0
                                };
                                console.log('Processed odds for "Maç Oranı":', processedOdds);
                            }
                            hasValidOdds = true;
                        }
                        // Look for "Regular Time (3-way)" (1X2) - English
                        else if (oddsData.additional_odds['Regular Time (3-way)']) {
                            const matchResult = oddsData.additional_odds['Regular Time (3-way)'];
                            
                            // Extract team names and map to 1X2 format
                            const teamNames = Object.keys(matchResult);
                            const oddsValues = Object.values(matchResult);
                            
                            // Find "Beraberlik" (Draw) or similar
                            const drawIndex = teamNames.findIndex(name => 
                                name.toLowerCase().includes('beraberlik') || 
                                name.toLowerCase().includes('draw') ||
                                name.toLowerCase().includes('tie')
                            );
                            
                            if (drawIndex !== -1 && teamNames.length >= 3) {
                                // Standard 1X2 format with draw
                                processedOdds = {
                                    '1': oddsValues[0] || 0,
                                    'X': oddsValues[drawIndex] || 0,
                                    '2': oddsValues[1] || 0
                                };
                            } else if (teamNames.length >= 2) {
                                // Two-way format, no draw
                                processedOdds = {
                                    '1': oddsValues[0] || 0,
                                    'X': 0, // No draw option
                                    '2': oddsValues[1] || 0
                                };
                            }
                            hasValidOdds = true;
                        }
                        // Also add some popular additional markets
                        if (oddsData.additional_odds['Handikap']) {
                            processedOdds.handikap = oddsData.additional_odds['Handikap'];
                        }
                        if (oddsData.additional_odds['Handikap - Uzatmalara Dahil']) {
                            processedOdds.handikap = oddsData.additional_odds['Handikap - Uzatmalara Dahil'];
                        }
                        if (oddsData.additional_odds['Toplam Goller - 2. Yarı']) {
                            processedOdds.altust = oddsData.additional_odds['Toplam Goller - 2. Yarı'];
                        }
                        if (oddsData.additional_odds['Uzatmalar Dahil Toplam Puan/Sayı/Gol']) {
                            processedOdds.altust = oddsData.additional_odds['Uzatmalar Dahil Toplam Puan/Sayı/Gol'];
                        }
                        if (oddsData.additional_odds['İlk Yarı']) {
                            processedOdds.ilkyari = oddsData.additional_odds['İlk Yarı'];
                        }
                    }
                    
                    if (!hasValidOdds) {
                        throw new Error('Live API returned no valid odds');
                    }
                    
                    // Update match title and info
                    try {
                        updateLiveMatchInfo(matchElement);
                    } catch (infoError) {
                        // Continue with odds display even if info update fails
                    }
                    
                    // Store the full odds data for filtering
                    window.allOddsData = oddsData;
                    window.selectedMatchId = matchId;
                    
                    // Update odds display with live API data
                    // For live matches, set isLive to true
                    const liveMatch = { isLive: true };
                    // Create modified oddsData with processed odds
                    const modifiedOddsData = {
                        ...oddsData,
                        main_odds: processedOdds
                    };
                    updateOddsDisplayFromAPI(modifiedOddsData, liveMatch);
                } else {
                    throw new Error('Live API returned success: false');
                }
                
            } catch (error) {
                
                // Show detailed error message based on error type
                let errorMessage = 'Bu karşılaşmaya bahis alınamıyor';
                let errorDetail = 'Lütfen tekrar deneyin';
                
                if (error.message.includes('invalid response format')) {
                    errorMessage = 'Geçersiz API yanıtı';
                    errorDetail = 'Sunucu hatası, lütfen tekrar deneyin';
                } else if (error.message.includes('invalid odds structure')) {
                    errorMessage = 'Geçersiz oran yapısı';
                    errorDetail = 'Bu maç için oran bilgisi bulunamadı';
                } else if (error.message.includes('no valid odds')) {
                    errorMessage = 'Geçerli oran bulunamadı';
                    errorDetail = 'Bu maç için şu anda oran mevcut değil';
                } else if (error.message.includes('API request failed')) {
                    errorMessage = 'Bu Karsilasmaya Bahis Alınamıyor';
                    errorDetail = '--';
                }
                
                // Show error message
                if (oddsContainer) {
                    oddsContainer.innerHTML = `
                        <div class="text-center text-xs text-[#a0a0a0] py-8">
                            <i class="fas fa-exclamation-triangle text-2xl mb-2 block text-[#f59e0b]"></i>
                            ${errorMessage}
                            <div class="text-[10px] mt-1">${errorDetail}</div>
                        </div>
                    `;
                }
            }
        }
        
        function updateLiveMatchInfo(matchElement) {
            // CRITICAL: Validate matchElement before processing
            if (!matchElement) {
                return;
            }
            
            
            // Update league title - use a more reliable selector
            const leagueTitle = document.getElementById('selected-league-title');
            if (leagueTitle && matchElement) {
                // Find the league element by looking for the div with league text
                const parentDiv = matchElement.closest('.px-3');
                if (parentDiv) {
                    const leagueElement = parentDiv.querySelector('div:nth-child(2)'); // Second div contains league name
                    if (leagueElement && leagueElement.textContent && leagueElement.textContent.trim() !== '') {
                        leagueTitle.textContent = leagueElement.textContent;
                    } else {
                        leagueTitle.textContent = 'Canlı Maç';
                    }
                } else {
                    leagueTitle.textContent = 'Canlı Maç';
                }
            }
            
            // Update match date to show "CANLI"
            const matchDate = document.getElementById('selected-match-date');
            if (matchDate) {
                matchDate.textContent = 'CANLI';
            }
        }
        
        function updateOddsDisplayFromAPI(oddsData, match) {
            // Update right panel only
            const oddsContainer = document.getElementById('kazanan-submenu');
            
            if (!oddsData) {
                return;
            }
            
            // Left odds panel removed - using right sidebar only
            
            // Desktop panel removed - odds are shown in right sidebar only
            
            // Main odds container title update removed - using right sidebar only
            
            // CRITICAL: Validate odds data structure before processing
            if (!oddsData.main_odds || typeof oddsData.main_odds !== 'object') {
                // Don't show error message, just return empty
                return;
            }
            
            // CRITICAL: Check if any valid odds exist
            const hasValidOdds = Object.values(oddsData.main_odds).some(odds => 
                odds && odds > 0 && odds !== '0' && odds !== '0.00'
            );
            
            if (!hasValidOdds) {
                // Don't show error message, just return empty
                return;
            }
            
            // Store all odds data for filtering
            allOddsData = oddsData;
            
            // Display filtered odds based on current filter
            displayFilteredOdds(oddsData, currentMarketFilter);
            
            // Update market counts
            updateMarketCounts(oddsData);
            
            // Re-attach odds button event listeners for live matches
            setTimeout(() => {
                attachOddsButtonListeners();
                // Restore odds button selections for live matches
                updateOddsButtonSelections();
            }, 100);
        }
        
        // Left odds panel functions removed - using right sidebar only
        
        // Desktop odds panel functions removed - using right sidebar only
        
        function displayFilteredOdds(oddsData, filter) {
            // CRITICAL: Validate parameters before processing
            if (!oddsData || typeof oddsData !== 'object') {
                return;
            }
            
            if (!filter || typeof filter !== 'string') {
                filter = 'tumu';
            }
            
            const oddsContainer = document.getElementById('kazanan-submenu');
            let oddsHTML = '';
            
            // Make sure the container is visible
            if (oddsContainer) {
                oddsContainer.classList.remove('hidden');
                
            } else {
            }
            
            if (filter === 'tumu' || filter === 'all') {
                // Always show main odds first in a "Kazanan" section
                if (oddsData.main_odds && typeof oddsData.main_odds === 'object') {
                    // CRITICAL: Validate main_odds structure
                    const requiredKeys = ['1', 'X', '2'];
                    const hasAllRequiredKeys = requiredKeys.every(key => oddsData.main_odds.hasOwnProperty(key));
                    
                    if (!hasAllRequiredKeys) {
                        // Try to get main odds from additional_odds if main_odds is incomplete
                        let reconstructedMainOdds = null;
                        
                        // Try "Maç Sonucu" first (Turkish)
                        if (oddsData.additional_odds && oddsData.additional_odds['Maç Sonucu']) {
                            const matchResult = oddsData.additional_odds['Maç Sonucu'];
                            reconstructedMainOdds = {
                                '1': matchResult['1'] || 0,
                                'X': matchResult['X'] || 0,
                                '2': matchResult['2'] || 0
                            };
                        }
                        // Try "Regular Time (3-way)" (English)
                        else if (oddsData.additional_odds && oddsData.additional_odds['Regular Time (3-way)']) {
                            const matchResult = oddsData.additional_odds['Regular Time (3-way)'];
                            
                            // Extract team names and map to 1X2 format
                            const teamNames = Object.keys(matchResult);
                            const oddsValues = Object.values(matchResult);
                            
                            // Find "Beraberlik" (Draw) or similar
                            const drawIndex = teamNames.findIndex(name => 
                                name.toLowerCase().includes('beraberlik') || 
                                name.toLowerCase().includes('draw') ||
                                name.toLowerCase().includes('tie')
                            );
                            
                            if (drawIndex !== -1 && teamNames.length >= 3) {
                                // Standard 1X2 format with draw
                                reconstructedMainOdds = {
                                    '1': oddsValues[0] || 0,
                                    'X': oddsValues[drawIndex] || 0,
                                    '2': oddsValues[1] || 0
                                };
                            } else if (teamNames.length >= 2) {
                                // Two-way format, no draw
                                reconstructedMainOdds = {
                                    '1': oddsValues[0] || 0,
                                    'X': 0, // No draw option
                                    '2': oddsValues[1] || 0
                                };
                            }
                        }
                        
                        if (reconstructedMainOdds) {
                            oddsHTML += createKazananSection(reconstructedMainOdds);
                        } else {
                            // Show clean message when no odds available
                        oddsHTML += `
                            <div class="text-center text-xs text-[#a0a0a0] py-8">
                                <i class="fas fa-futbol text-2xl mb-2 block text-[#f59e0b]"></i>
                                Maç seçin
                                <div class="text-[10px] mt-1">Oranları görmek için bir maça tıklayın</div>
                            </div>
                        `;
                        }
                    } else {
                    // Add beautiful Kazanan header
                    oddsHTML += `
                        <section class="mb-4">
                            <header class="flex justify-between items-center text-xs font-semibold mb-2">
                                <div class="flex items-center space-x-1">
                                    <i class="far fa-star text-[#6b6b6b]"></i>
                                    <span>Kazanan</span>
                                </div>
                                <button aria-label="Collapse/Expand" class="text-[#6b6b6b] hover:text-white">
                                    <i class="fas fa-chevron-up"></i>
                                </button>
                            </header>
                    `;
                    
                    // Selection names row
                    oddsHTML += `
                            <div class="grid grid-cols-3 gap-1 mb-1 text-center text-[#7a7a7a] text-[10px]">
                                <div class="bg-[#3a3a3a] rounded-md py-1">Evsahibi</div>
                                <div class="bg-[#3a3a3a] rounded-md py-1">Beraberlik</div>
                                <div class="bg-[#3a3a3a] rounded-md py-1">Deplasman</div>
                            </div>
                    `;
                    
                    // Odds values row
                    oddsHTML += `
                            <div class="grid grid-cols-3 gap-1 text-center text-xs font-semibold">`;
                    
                    // Home odds
                    if (oddsData.main_odds['1']) {
                        const odds1 = parseFloat(oddsData.main_odds['1']);
                        const isValidOdds1 = odds1 && odds1 > 0 && odds1 !== '0' && odds1 !== '0.00';
                        oddsHTML += `
                                <button class="${!isValidOdds1 ? 'bg-[#2a2a2a] cursor-not-allowed opacity-50 text-[#6b6b6b]' : 'bg-[#3a3a3a] hover:bg-[#4a4a4a] text-[#f2b90f] odds-btn'} rounded-md py-1" ${!isValidOdds1 ? 'disabled' : ''} data-bet-type="1" data-odds="${isValidOdds1 ? odds1 : 0}" data-market="Kazanan" data-selection="Evsahibi Kazanır" data-match-id="${selectedMatchId}">
                                    ${!isValidOdds1 ? '<i class="fas fa-lock"></i>' : odds1.toFixed(2)}
                                </button>`;
                    } else {
                        oddsHTML += `<div class="bg-[#2a2a2a] rounded-md py-1 text-[#6b6b6b]">-</div>`;
                    }
                    
                    // Draw odds
                    if (oddsData.main_odds['X']) {
                        const oddsX = parseFloat(oddsData.main_odds['X']);
                        const isValidOddsX = oddsX && oddsX > 0 && oddsX !== '0' && oddsX !== '0.00';
                        oddsHTML += `
                                <button class="${!isValidOddsX ? 'bg-[#2a2a2a] cursor-not-allowed opacity-50 text-[#6b6b6b]' : 'bg-[#3a3a3a] hover:bg-[#4a4a4a] text-[#f2b90f] odds-btn'} rounded-md py-1" ${!isValidOddsX ? 'disabled' : ''} data-bet-type="X" data-odds="${isValidOddsX ? oddsX : 0}" data-market="Kazanan" data-selection="Berabere" data-match-id="${selectedMatchId}">
                                    ${!isValidOddsX ? '<i class="fas fa-lock"></i>' : oddsX.toFixed(2)}
                                </button>`;
                    } else {
                        oddsHTML += `<div class="bg-[#2a2a2a] rounded-md py-1 text-[#6b6b6b]">-</div>`;
                    }
                    
                    // Away odds
                    if (oddsData.main_odds['2']) {
                        const odds2 = parseFloat(oddsData.main_odds['2']);
                        const isValidOdds2 = odds2 && odds2 > 0 && odds2 !== '0' && odds2 !== '0.00';
                        oddsHTML += `
                                <button class="${!isValidOdds2 ? 'bg-[#2a2a2a] cursor-not-allowed opacity-50 text-[#6b6b6b]' : 'bg-[#3a3a3a] hover:bg-[#4a4a4a] text-[#f2b90f] odds-btn'} rounded-md py-1" ${!isValidOdds2 ? 'disabled' : ''} data-bet-type="2" data-odds="${isValidOdds2 ? odds2 : 0}" data-market="Kazanan" data-selection="Deplasman Kazanır" data-match-id="${selectedMatchId}">
                                    ${!isValidOdds2 ? '<i class="fas fa-lock"></i>' : odds2.toFixed(2)}
                                </button>`;
                    } else {
                        oddsHTML += `<div class="bg-[#2a2a2a] rounded-md py-1 text-[#6b6b6b]">-</div>`;
                    }
                    
                    oddsHTML += `
                            </div>
                        </section>
                    `;
                    }
                }
                
                // If no main_odds, try to show main odds from additional_odds
                if (!oddsData.main_odds && oddsData.additional_odds && oddsData.additional_odds['Maç Sonucu']) {
                    const matchResult = oddsData.additional_odds['Maç Sonucu'];
                    const reconstructedMainOdds = {
                        '1': matchResult['1'] || 0,
                        'X': matchResult['X'] || 0,
                        '2': matchResult['2'] || 0
                    };
                    oddsHTML += createKazananSection(reconstructedMainOdds);
                }
                
                // Show all additional markets for "Tümü"
                if (oddsData.additional_odds && typeof oddsData.additional_odds === 'object') {
                    Object.keys(oddsData.additional_odds).forEach(marketName => {
                        const marketOdds = oddsData.additional_odds[marketName];
                        if (typeof marketOdds === 'object' && marketOdds !== null) {
                            // CRITICAL: Validate marketName before processing
                            if (marketName && typeof marketName === 'string') {
                                oddsHTML += addMarketToHTML(marketName, marketOdds);
                            } else {
                            }
                        }
                    });
                }
            } else {
                // For specific filters, still show main odds if it matches the filter criteria
                if (filter === 'kazanan' || shouldShowMainOdds(filter)) {
                    if (oddsData.main_odds && typeof oddsData.main_odds === 'object') {
                        oddsHTML += createKazananSection(oddsData.main_odds);
                    } else if (oddsData.additional_odds && oddsData.additional_odds['Maç Sonucu']) {
                        // Try to get main odds from additional_odds
                        const matchResult = oddsData.additional_odds['Maç Sonucu'];
                        const reconstructedMainOdds = {
                            '1': matchResult['1'] || 0,
                            'X': matchResult['X'] || 0,
                            '2': matchResult['2'] || 0
                        };
                        oddsHTML += createKazananSection(reconstructedMainOdds);
                    }
                }
                
                // Filter markets based on selected category
                if (oddsData.additional_odds && typeof oddsData.additional_odds === 'object') {
                    Object.keys(oddsData.additional_odds).forEach(marketName => {
                        const marketOdds = oddsData.additional_odds[marketName];
                        
                        if (typeof marketOdds === 'object' && marketOdds !== null && shouldShowMarket(marketName, filter)) {
                            // CRITICAL: Validate marketName before processing
                            if (marketName && typeof marketName === 'string') {
                                oddsHTML += addMarketToHTML(marketName, marketOdds);
                            } else {
                            }
                        }
                    });
                }
            }
            
            if (oddsHTML) {
                oddsContainer.innerHTML = oddsHTML;
                
                // CRITICAL: Validate that we actually have clickable odds buttons
                const oddsButtons = oddsContainer.querySelectorAll('.odds-btn:not([disabled])');
                if (oddsButtons.length === 0) {
                    // Don't show warning message, just continue
                }
                
                // Restore odds button selections after loading
                setTimeout(() => {
                    updateOddsButtonSelections();
                }, 100);
            } else {
                // Don't show fallback message, just return empty
                return;
            }
        }
        
        function addMarketToHTML(marketName, marketOdds) {
            // CRITICAL: Validate parameters before processing
            if (!marketName || typeof marketName !== 'string') {
                return '';
            }
            
            if (!marketOdds || typeof marketOdds !== 'object') {
                return '';
            }
            
            let html = '';
            
            // Get market entries and organize them
            const marketEntries = Object.entries(marketOdds);
            
            if (!marketEntries || marketEntries.length === 0) {
                return html;
            }
            
            // Start market section
            html += `
                <section class="mb-4">
                    <header class="flex justify-between items-center text-xs font-semibold mb-2">
                        <div class="flex items-center space-x-1">
                            <i class="far fa-star text-[#6b6b6b]"></i>
                            <span>${marketName}</span>
                        </div>
                        <button aria-label="Collapse/Expand" class="text-[#6b6b6b] hover:text-white">
                            <i class="fas fa-chevron-up"></i>
                        </button>
                    </header>
            `;
            
            // For markets with 3 or fewer options - use 3-column layout
            if (marketEntries.length <= 3) {
                // Selection names row
                html += `
                    <div class="grid grid-cols-${marketEntries.length} gap-1 mb-1 text-center text-[#7a7a7a] text-[10px]">`;
                
                marketEntries.forEach(([selectionName, odds]) => {
                    html += `
                        <div class="bg-[#3a3a3a] rounded-md py-1">${selectionName}</div>`;
                });
                
                html += `
                    </div>`;
                
                // Odds values row
                html += `
                    <div class="grid grid-cols-${marketEntries.length} gap-1 text-center text-xs font-semibold">`;
                
                marketEntries.forEach(([selectionName, odds]) => {
                    // CRITICAL: Validate selectionName and odds before processing
                    if (!selectionName || typeof selectionName !== 'string') {
                        return;
                    }
                    
                    if (typeof odds === 'number' || (typeof odds === 'string' && !isNaN(parseFloat(odds)))) {
                        const oddsValue = parseFloat(odds);
                        const betId = `${marketName}_${selectionName}`.replace(/[^a-zA-Z0-9]/g, '_');
                        
                        // CRITICAL: Enhanced validation for additional market odds
                        const isValidOdds = oddsValue && oddsValue > 0 && oddsValue !== '0' && oddsValue !== '0.00';
                        const isLocked = !isValidOdds;
                        
                        html += `
                            <button class="${isLocked ? 'bg-[#2a2a2a] cursor-not-allowed opacity-50 text-[#6b6b6b]' : 'bg-[#3a3a3a] hover:bg-[#4a4a4a] text-[#f2b90f] odds-btn'} rounded-md py-1" ${isLocked ? 'disabled' : ''} data-bet-type="${betId}" data-odds="${isValidOdds ? oddsValue : 0}" data-market="${marketName}" data-selection="${selectionName}" data-match-id="${selectedMatchId}">
                                ${isLocked ? '<i class="fas fa-lock"></i>' : oddsValue.toFixed(2)}
                            </button>`;
                    } else {
                        html += `<div class="bg-[#2a2a2a] rounded-md py-1 text-[#6b6b6b]">-</div>`;
                    }
                });
                
                html += `
                    </div>`;
            } else {
                // For markets with 4+ options, use compact design
                html += `<div class="grid grid-cols-2 gap-1 text-xs">`;
                
                marketEntries.forEach(([selectionName, odds]) => {
                    // CRITICAL: Validate selectionName and odds before processing
                    if (!selectionName || typeof selectionName !== 'string') {
                        console.warn('Invalid selectionName in addMarketToHTML (4+ options):', selectionName);
                        return;
                    }
                    if (typeof odds === 'number' || (typeof odds === 'string' && !isNaN(parseFloat(odds)))) {
                        const oddsValue = parseFloat(odds);
                        const betId = `${marketName}_${selectionName}`.replace(/[^a-zA-Z0-9]/g, '_');
                        // CRITICAL: Enhanced validation for additional market odds
                        const isValidOdds = oddsValue && oddsValue > 0 && oddsValue !== '0' && oddsValue !== '0.00';
                        const isLocked = !isValidOdds;
                        
                        html += `
                            <button class="${isLocked ? 'bg-[#2a2a2a] cursor-not-allowed opacity-50 text-[#6b6b6b]' : 'bg-[#3a3a3a] hover:bg-[#4a4a4a] text-white odds-btn'} rounded-md p-1.5 flex justify-between items-center text-xs" ${isLocked ? 'disabled' : ''} data-bet-type="${betId}" data-odds="${isValidOdds ? oddsValue : 0}" data-market="${marketName}" data-selection="${selectionName}" data-match-id="${selectedMatchId}">
                                <span class="text-[10px] truncate">${selectionName}</span>
                                <span class="${isLocked ? 'text-[#6b6b6b]' : 'text-[#f2b90f]'} font-semibold text-[10px]">${isLocked ? '<i class="fas fa-lock"></i>' : oddsValue.toFixed(2)}</span>
                            </button>`;
                    }
                });
                
                html += `</div>`;
            }
            
            html += `
                </section>
            `;
            
            return html;
        }
        
        function updateMarketCounts(oddsData) {
            // CRITICAL: Validate oddsData before processing
            if (!oddsData || typeof oddsData !== 'object') {
                return;
            }
            let counts = {
                tumu: 0,
                altust: 0,
                ilkyari: 0,
                handikap: 0
            };
            
            // Count main odds
            if (oddsData.main_odds && typeof oddsData.main_odds === 'object') {
                counts.tumu += Object.keys(oddsData.main_odds).length;
            }
            
            // Count additional markets
            if (oddsData.additional_odds && typeof oddsData.additional_odds === 'object') {
                Object.keys(oddsData.additional_odds).forEach(marketName => {
                    const marketOdds = oddsData.additional_odds[marketName];
                    if (typeof marketOdds === 'object' && marketOdds !== null) {
                        const marketCount = Object.keys(marketOdds).length;
                        counts.tumu += marketCount;
                        
                        if (shouldShowMarket(marketName, 'altust')) {
                            counts.altust += marketCount;
                        }
                        if (shouldShowMarket(marketName, 'ilkyari')) {
                            counts.ilkyari += marketCount;
                        }
                        if (shouldShowMarket(marketName, 'handikap')) {
                            counts.handikap += marketCount;
                        }
                    }
                });
            }
            
            // Update count displays
            const tumuCount = document.getElementById('tumu-count');
            const altustCount = document.getElementById('altust-count');
            const ilkyariCount = document.getElementById('ilkyari-count');
            const handikapCount = document.getElementById('handikap-count');
            
            if (tumuCount) tumuCount.textContent = counts.tumu;
            if (altustCount) altustCount.textContent = counts.altust;
            if (ilkyariCount) ilkyariCount.textContent = counts.ilkyari;
            if (handikapCount) handikapCount.textContent = counts.handikap;
        }
        
        function shouldShowMarket(marketName, filter) {
            // CRITICAL: Validate parameters before processing
            if (!marketName || typeof marketName !== 'string') {
                return false;
            }
            
            if (!filter || typeof filter !== 'string') {
                return false;
            }
            
            const marketLower = marketName.toLowerCase();
            
            switch(filter) {
                case 'altust':
                    // Alt/Üst related markets
                    return marketLower.includes('alt') || 
                           marketLower.includes('üst') || 
                           marketLower.includes('over') || 
                           marketLower.includes('under') || 
                           marketLower.includes('total') || 
                           marketLower.includes('gol') || 
                           marketLower.includes('goal') || 
                           marketLower.includes('toplam') || 
                           marketLower.includes('goller') ||
                           marketLower.includes('korner') || 
                           marketLower.includes('corner') || 
                           marketLower.includes('kart') || 
                           marketLower.includes('card') ||
                           marketLower.includes('ev sahibi') ||
                           marketLower.includes('misafir') ||
                           marketLower.includes('0:00') ||
                           marketLower.includes('10:00') ||
                           marketLower.includes('19:59');
                    
                case 'ilkyari':
                    // First half related markets
                    return marketLower.includes('yarı') || 
                           marketLower.includes('half') || 
                           marketLower.includes('1st') || 
                           marketLower.includes('first') || 
                           marketLower.includes('ilk') ||
                           marketLower.includes('0:00-29:59') ||
                           marketLower.includes('10:00-19:59');
                    
                case 'handikap':
                    // Handicap related markets
                    return marketLower.includes('handikap') || 
                           marketLower.includes('handicap') || 
                           marketLower.includes('asian') ||
                           marketLower.includes('3 yollu');
                    
                default:
                    return true;
            }
        }
        
        function shouldShowMainOdds(filter) {
            // CRITICAL: Validate filter before processing
            if (!filter || typeof filter !== 'string') {
                return false;
            }
            
            // Main odds should show in Tümü and when specifically looking for winner markets
            return filter === 'tumu' || filter === 'all' || filter === 'kazanan';
        }
        
        function createKazananSection(mainOdds) {
            // CRITICAL: Validate mainOdds before processing
            if (!mainOdds || typeof mainOdds !== 'object') {
                return '';
            }
            
            // CRITICAL: Check if required odds keys exist
            const requiredKeys = ['1', 'X', '2'];
            const hasAllRequiredKeys = requiredKeys.every(key => mainOdds.hasOwnProperty(key));
            
            if (!hasAllRequiredKeys) {
                return '';
            }
            let html = `
                <section class="mb-4">
                    <header class="flex justify-between items-center text-xs font-semibold mb-2">
                        <div class="flex items-center space-x-1">
                            <i class="far fa-star text-[#6b6b6b]"></i>
                            <span>Kazanan</span>
                        </div>
                        <button aria-label="Collapse/Expand" class="text-[#6b6b6b] hover:text-white">
                            <i class="fas fa-chevron-up"></i>
                        </button>
                    </header>
            `;
            
            // Selection names row
            html += `
                    <div class="grid grid-cols-3 gap-1 mb-1 text-center text-[#7a7a7a] text-[10px]">
                        <div class="bg-[#3a3a3a] rounded-md py-1">Evsahibi</div>
                        <div class="bg-[#3a3a3a] rounded-md py-1">Beraberlik</div>
                        <div class="bg-[#3a3a3a] rounded-md py-1">Deplasman</div>
                    </div>
            `;
            
            // Odds values row
            html += `
                    <div class="grid grid-cols-3 gap-1 text-center text-xs font-semibold">`;
            
            // Home odds
            if (mainOdds['1']) {
                const odds1 = parseFloat(mainOdds['1']);
                const isValidOdds1 = odds1 && odds1 > 0 && odds1 !== '0' && odds1 !== '0.00';
                html += `
                        <button class="${!isValidOdds1 ? 'bg-[#2a2a2a] cursor-not-allowed opacity-50 text-[#6b6b6b]' : 'bg-[#3a3a3a] hover:bg-[#4a4a4a] text-[#f2b90f] odds-btn'} rounded-md py-1" ${!isValidOdds1 ? 'disabled' : ''} data-bet-type="1" data-odds="${isValidOdds1 ? odds1 : 0}" data-market="Kazanan" data-selection="Evsahibi Kazanır">
                            ${!isValidOdds1 ? '<i class="fas fa-lock"></i>' : odds1.toFixed(2)}
                        </button>`;
            } else {
                html += `<div class="bg-[#2a2a2a] rounded-md py-1 text-[#6b6b6b]">-</div>`;
            }
            
            // Draw odds
            if (mainOdds['X']) {
                const oddsX = parseFloat(mainOdds['X']);
                const isValidOddsX = oddsX && oddsX > 0 && oddsX !== '0' && oddsX !== '0.00';
                html += `
                        <button class="${!isValidOddsX ? 'bg-[#2a2a2a] cursor-not-allowed opacity-50 text-[#6b6b6b]' : 'bg-[#3a3a3a] hover:bg-[#4a4a4a] text-[#f2b90f] odds-btn'} rounded-md py-1" ${!isValidOddsX ? 'disabled' : ''} data-bet-type="X" data-odds="${isValidOddsX ? oddsX : 0}" data-market="Kazanan" data-selection="Berabere">
                            ${!isValidOddsX ? '<i class="fas fa-lock"></i>' : oddsX.toFixed(2)}
                        </button>`;
            } else {
                html += `<div class="bg-[#2a2a2a] rounded-md py-1 text-[#6b6b6b]">-</div>`;
            }
            
            // Away odds
            if (mainOdds['2']) {
                const odds2 = parseFloat(mainOdds['2']);
                const isValidOdds2 = odds2 && odds2 > 0 && odds2 !== '0' && odds2 !== '0.00';
                html += `
                        <button class="${!isValidOdds2 ? 'bg-[#2a2a2a] cursor-not-allowed opacity-50 text-[#6b6b6b]' : 'bg-[#3a3a3a] hover:bg-[#4a4a4a] text-[#f2b90f] odds-btn'} rounded-md py-1" ${!isValidOdds2 ? 'disabled' : ''} data-bet-type="2" data-odds="${isValidOdds2 ? odds2 : 0}" data-market="Kazanan" data-selection="Deplasman Kazanır">
                            ${!isValidOdds2 ? '<i class="fas fa-lock"></i>' : odds2.toFixed(2)}
                        </button>`;
            } else {
                html += `<div class="bg-[#2a2a2a] rounded-md py-1 text-[#6b6b6b]">-</div>`;
            }
            
            html += `
                    </div>
                </section>
            `;
            
            return html;
        }

        function addToBetSlip(betType, odds, matchEventId, market = 'Kazanan', selection = null) {
            // Check if this is a live match (real match ID, not in matchData)
            const isLiveMatch = !matchData[matchEventId] && matchEventId && !matchEventId.toString().startsWith('live_');
            const match = matchData[matchEventId];
            
            // Handle live matches (without matchData)
            if (!match || isLiveMatch) {
                // For live matches, try to get match info from the selected match element
                const selectedMatchElement = document.querySelector('.live-match-item.selected') || 
                                           document.querySelector('.live-match-item[data-match-id="' + matchEventId.replace('live_', '') + '"]');
                
                let homeTeam = 'Ev Sahibi';
                let awayTeam = 'Misafir';
                let leagueName = 'Canlı';
                
                if (selectedMatchElement) {
                    const teamElements = selectedMatchElement.querySelectorAll('div:first-child div');
                    if (teamElements.length >= 2) {
                        homeTeam = teamElements[0].textContent || 'Ev Sahibi';
                        awayTeam = teamElements[1].textContent || 'Misafir';
                    }
                    
                    // Try to get league name from parent elements
                    const leagueElement = selectedMatchElement.closest('.px-3')?.querySelector('div:nth-child(2)');
                    if (leagueElement) {
                        leagueName = leagueElement.textContent || 'Canlı';
                    }
                }
                
                // CRITICAL: Check if odds are valid for live matches
                if (!odds || odds <= 0 || odds === '0' || odds === '0.00') {
                    alert('Bu seçenek için geçerli oran bulunamadı. Lütfen başka bir seçenek seçin.');
                    return;
                }
                
                // CRITICAL: Check if betType is valid
                if (!betType || betType === '' || betType === 'undefined' || betType === 'null') {
                    alert('Bahis türü bulunamadı. Lütfen tekrar deneyin.');
                    return;
                }
                
                // Allow both main match bet types and custom market bet types
                const isValidMainBetType = ['1', 'X', '0', '2'].includes(betType);
                const isValidCustomBetType = typeof betType === 'string' && betType.includes('_') && betType.length > 1;
                
                if (!isValidMainBetType && !isValidCustomBetType) {
                    alert('Geçersiz bahis türü. Lütfen geçerli bir seçenek seçin.');
                    return;
                }
                
                // CRITICAL: Validate selection name
                let validSelection = selection;
                if (!validSelection) {
                    switch(betType) {
                        case '1':
                            validSelection = 'Evsahibi Kazanır';
                            break;
                        case 'X':
                            validSelection = 'Berabere';
                            break;
                        case '2':
                            validSelection = 'Deplasman Kazanır';
                            break;
                        default:
                            validSelection = 'Bilinmeyen';
                    }
                }
                
                // For live matches, create a detailed bet structure
                const bet = {
                    id: `${matchEventId}-${betType}`,
                    match: `${homeTeam} vs ${awayTeam}`,
                    market: market,
                    selection: validSelection,
                    odds: odds,
                    eventId: matchEventId,
                    matchInfo: { 
                        home: homeTeam, 
                        away: awayTeam, 
                        league: leagueName, 
                        date: 'CANLI',
                        isLive: true
                    }
                };

                selectedBets.push(bet);
                updateBetSlip();
                return;
            }

            // CRITICAL: Check if odds are valid for normal matches
            if (!odds || odds <= 0 || odds === '0' || odds === '0.00') {
                alert('Bu seçenek için geçerli oran bulunamadı. Lütfen başka bir seçenek seçin.');
                return;
            }
            
            // CRITICAL: Check if betType is valid
            if (!betType || betType === '' || betType === 'undefined' || betType === 'null') {
                alert('Bahis türü bulunamadı. Lütfen tekrar deneyin.');
                return;
            }
            
            // Allow both main match bet types and custom market bet types
            const isValidMainBetType = ['1', 'X', '0', '2'].includes(betType);
            const isValidCustomBetType = typeof betType === 'string' && betType.includes('_') && betType.length > 1;
            
            if (!isValidMainBetType && !isValidCustomBetType) {
                alert('Geçersiz bahis türü. Lütfen geçerli bir seçenek seçin.');
                return;
            }
            
            let selectionName = selection || (betType === '1' ? decodeHtmlEntities(match.home) : betType === '0' ? 'Beraberlik' : decodeHtmlEntities(match.away));

            const bet = {
                id: `${matchEventId}-${betType}`,
                match: `${decodeHtmlEntities(match.home)} vs ${decodeHtmlEntities(match.away)}`,
                market: market,
                selection: selectionName,
                odds: odds,
                eventId: matchEventId,
                matchInfo: { home: decodeHtmlEntities(match.home), away: decodeHtmlEntities(match.away), league: decodeHtmlEntities(match.league), date: match.date }
            };

            selectedBets.push(bet);
            updateBetSlip();
        }

        function updateBetSlip() {
            
            const emptySlip = document.getElementById('empty-slip');
            const selectedBetsDiv = document.getElementById('selected-bets');
            const betSummary = document.getElementById('bet-summary');
            
            saveBettingSlipToStorage();
            
            if (selectedBets.length === 0) {
                emptySlip.style.display = 'block';
                selectedBetsDiv.style.display = 'none';
                betSummary.style.display = 'none';
            } else {
                emptySlip.style.display = 'none';
                selectedBetsDiv.style.display = 'block';
                betSummary.style.display = 'block';
                
                selectedBetsDiv.innerHTML = selectedBets.map(bet => `
                    <div class="relative rounded-md bg-[#3a3a3a] p-3 text-xs">
                        <button type="button" class="absolute top-2 right-2 text-gray-400 hover:text-[#f59e0b]" onclick="removeBet('${bet.id}')">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="font-semibold text-white mb-0.5">${bet.selection}</div>
                        <div class="font-semibold text-white mb-0.5">${bet.market}</div>
                        <div class="text-gray-300 text-[10px] mb-0.5">${bet.match}</div>
                        <div class="flex justify-between items-center">
                            <div class="text-gray-500 text-[9px]">${bet.matchInfo.date}</div>
                            <div class="text-[#f59e0b] font-semibold">${bet.odds.toFixed(2)}</div>
                        </div>
                    </div>`).join('');
                
                updateBetCalculations();
            }
        }
        
        function updateBetCalculations() {
            const totalOdds = selectedBets.reduce((total, bet) => total * bet.odds, 1);
            const betAmountInput = document.getElementById('bet-amount-input');
            const betAmount = parseFloat(betAmountInput?.value) || 0;
            const estimatedWinnings = totalOdds * betAmount;
            
            // Update displays
            const totalOddsEl = document.getElementById('total-odds');
            const estimatedWinningsEl = document.getElementById('estimated-winnings');
            
            if (totalOddsEl) totalOddsEl.textContent = totalOdds.toFixed(2);
            if (estimatedWinningsEl) estimatedWinningsEl.textContent = estimatedWinnings.toFixed(2) + ' ₺';
            
            // Always keep button enabled if there are bets - let the placeBet function handle validation
            const betButton = document.getElementById('place-bet-btn');
            if (betButton && selectedBets.length > 0) {
                betButton.disabled = false;
                betButton.classList.remove('bg-[#3a3a3a]', 'text-gray-500', 'cursor-not-allowed');
                betButton.classList.add('bg-[#f59e0b]', 'text-[#2a2a2a]', 'hover:bg-[#e5890a]');
                betButton.style.pointerEvents = 'auto';
                betButton.style.cursor = 'pointer';
            } else if (betButton) {
                betButton.disabled = true;
                betButton.classList.remove('bg-[#f59e0b]', 'text-[#2a2a2a]', 'hover:bg-[#e5890a]');
                betButton.classList.add('bg-[#3a3a3a]', 'text-gray-500', 'cursor-not-allowed');
                betButton.style.pointerEvents = 'none';
                betButton.style.cursor = 'not-allowed';
            }
        }
        
        function removeBet(betId) {
            selectedBets = selectedBets.filter(bet => bet.id !== betId);
            removeOddsButtonSelection(betId);
            updateBetSlip();
        }
        
        function clearAllBets() {
            selectedBets = [];
            selectedOddsButtons.clear();
            // Remove selected class from all odds buttons
            document.querySelectorAll('.odds-btn.selected').forEach(btn => {
                btn.classList.remove('selected', 'newly-selected');
            });
            saveBettingSlipToStorage();
            updateBetSlip();
        }
        
        // Debug function to test button
        function testButton() {
            const betButton = document.getElementById('place-bet-btn');
            
            const betAmountInput = document.getElementById('bet-amount-input');
            
            // Try to enable button manually for testing
            if (betButton) {
                betButton.disabled = false;
                betButton.classList.remove('bg-[#3a3a3a]', 'text-gray-500', 'cursor-not-allowed');
                betButton.classList.add('bg-[#f59e0b]', 'text-[#2a2a2a]');
            }
        }
        
        // Make function available globally for testing
        window.testButton = testButton;
        window.placeBet = placeBet;
        
        // Place bet function
        async function placeBet() {
            
            if (selectedBets.length === 0) {
                alert('Lütfen en az bir maç seçin!');
                return;
            }
            
            // CRITICAL: Validate all bets before proceeding
            for (let i = 0; i < selectedBets.length; i++) {
                const bet = selectedBets[i];
                
                // Check if bet has valid odds
                if (!bet.odds || bet.odds <= 0 || bet.odds === '0' || bet.odds === '0.00') {
                    alert(`"${bet.match}" maçı için geçersiz oran: ${bet.odds}. Lütfen bu maçı kuponunuzdan çıkarın.`);
                    return;
                }
                
                // Check if bet has valid selection
                if (!bet.selection || bet.selection === 'Bilinmeyen') {
                    alert(`"${bet.match}" maçı için geçersiz seçenek: ${bet.selection}. Lütfen bu maçı kuponunuzdan çıkarın.`);
                    return;
                }
                
                // Check if bet has valid eventId
                if (!bet.eventId) {
                    alert(`"${bet.match}" maçı için geçersiz maç ID'si. Lütfen bu maçı kuponunuzdan çıkarın.`);
                    return;
                }
            }
            
            const betAmountInput = document.getElementById('bet-amount-input');
            const betAmount = parseFloat(betAmountInput.value);
            
            if (!betAmount || betAmount <= 0) {
                alert('Lütfen geçerli bir bahis miktarı girin!');
                return;
            }
            
            // Check if any bet is live
            const hasLiveBet = selectedBets.some(bet => {
                return (bet.matchInfo && bet.matchInfo.isLive === true) ||
                       (bet.eventId && bet.eventId.toString().startsWith('live_'));
            });
            
            if (hasLiveBet) {
                await validateLiveBetOdds();
            } else {
                await processBetPlacement();
            }
        }
        
        // Validate live bet odds before placing bet
        async function validateLiveBetOdds() {
            const betButton = document.getElementById('place-bet-btn');
            const originalText = betButton.textContent;
            
            try {
                // Show loading state
                betButton.textContent = 'CANLI ORANLAR KONTROL EDİLİYOR...';
                betButton.disabled = true;
                betButton.style.backgroundColor = '#f59e0b';
                betButton.style.color = '#1e1e1e';
                
                const validationResults = [];
                let hasOddsChange = false;
                let hasSelectionUnavailable = false;
                
                // Check each live bet
                for (let i = 0; i < selectedBets.length; i++) {
                    const bet = selectedBets[i];
                    
                    // Only check live bets
                    if ((bet.matchInfo && bet.matchInfo.isLive === true) ||
                        (bet.eventId && !matchData[bet.eventId] && !bet.eventId.toString().startsWith('live_'))) {
                        
                        // Get the real match ID (remove live_ prefix if exists)
                        let realMatchId = bet.eventId.toString();
                        if (realMatchId.startsWith('live_')) {
                            realMatchId = realMatchId.replace('live_', '');
                        }
                        
                        try {
                            const response = await fetch(`/Live/Odds/${realMatchId}`);
                            
                            if (!response.ok) {
                                throw new Error(`API response not ok: ${response.status} ${response.statusText}`);
                            }
                            
                            const oddsData = await response.json();
                            
                            if (oddsData.success && (oddsData.main_odds || oddsData.additional_odds)) {
                                let currentOdds = null;
                                let betType = null;
                                let newOdds = null;
                                
                                // CRITICAL: Validate odds data structure
                                if (!oddsData.main_odds || typeof oddsData.main_odds !== 'object') {
                                    hasSelectionUnavailable = true;
                                    validationResults.push({
                                        bet: bet,
                                        status: 'unavailable',
                                        reason: 'Invalid odds structure'
                                    });
                                    continue;
                                }
                                
                                // First check main_odds for basic selections
                                if (oddsData.main_odds) {
                                    if (bet.selection === 'Evsahibi Kazanır' || bet.selection === '1') {
                                        betType = '1';
                                        currentOdds = oddsData.main_odds;
                                    } else if (bet.selection === 'Deplasman Kazanır' || bet.selection === '2') {
                                        betType = '2';
                                        currentOdds = oddsData.main_odds;
                                    } else if (bet.selection === 'Berabere' || bet.selection === 'X') {
                                        betType = 'X';
                                        currentOdds = oddsData.main_odds;
                                    }
                                }
                                
                                // If not found in main_odds, check additional_odds
                                if (!currentOdds && oddsData.additional_odds) {
                                    // Check each additional market
                                    for (const [marketName, marketOdds] of Object.entries(oddsData.additional_odds)) {
                                        if (marketOdds[bet.selection]) {
                                            currentOdds = marketOdds;
                                            betType = bet.selection;
                                            break;
                                        }
                                    }
                                }
                                
                                if (currentOdds) {
                                    newOdds = currentOdds[betType];
                                }
                                
                                // CRITICAL: Validate new odds before proceeding
                                if (!newOdds || newOdds <= 0 || newOdds === '0' || newOdds === '0.00') {
                                    hasSelectionUnavailable = true;
                                    validationResults.push({
                                        bet: bet,
                                        status: 'unavailable',
                                        reason: 'Selection no longer available'
                                    });
                                    continue;
                                }
                                
                                const oldOdds = bet.odds;
                                
                                if (currentOdds && betType && newOdds) {
                                    if (newOdds > oldOdds) {
                        // Odds increased - update the bet with new higher odds
                        selectedBets[i].odds = newOdds;
                        validationResults.push({
                            bet: bet,
                            status: 'increased',
                            oldOdds: oldOdds,
                            newOdds: newOdds
                        });
                        hasOddsChange = true;
                                                        } else if (newOdds < oldOdds) {
                                        // Odds decreased - update the bet with new lower odds
                                        selectedBets[i].odds = newOdds;
                                        validationResults.push({
                                            bet: bet,
                                            status: 'decreased',
                                            oldOdds: oldOdds,
                                            newOdds: newOdds
                                        });
                                        hasOddsChange = true;
                                    } else {
                                        // Odds unchanged
                                        validationResults.push({
                                            bet: bet,
                                            status: 'unchanged',
                                            odds: oldOdds
                                        });
                                    }
                                } else {
                                    // Selection not available
                                    validationResults.push({
                                        bet: bet,
                                        status: 'unavailable',
                                        selection: bet.selection,
                                        availableMainOdds: oddsData.main_odds,
                                        availableAdditionalOdds: oddsData.additional_odds
                                    });
                                    hasSelectionUnavailable = true;
                                }
                            } else {
                                // API error
                                validationResults.push({
                                    bet: bet,
                                    status: 'api_error',
                                    error: 'Oranlar alınamadı'
                                });
                            }
                            
                            // Wait 1 second between requests
                            await new Promise(resolve => setTimeout(resolve, 1000));
                            
                        } catch (error) {
                            validationResults.push({
                                bet: bet,
                                status: 'error',
                                error: error.message,
                                matchId: realMatchId
                            });
                        }
                    } else {
                        // Non-live bet
                        validationResults.push({
                            bet: bet,
                            status: 'non_live'
                        });
                    }
                }
                
                // Process validation results
                if (hasSelectionUnavailable) {
                    showLiveBetError('Bazı seçenekler artık mevcut değil. Lütfen kuponunuzu güncelleyin.');
                    return;
                }
                
                if (hasOddsChange) {
                    const decreasedBets = validationResults.filter(r => r.status === 'decreased');
                    const increasedBets = validationResults.filter(r => r.status === 'increased');
                    
                    if (decreasedBets.length > 0) {
                        const decreasedDetails = decreasedBets.map(bet => 
                            `${bet.bet.selection}: ${bet.oldOdds} → ${bet.newOdds}`
                        ).join(', ');
                        
                        showLiveBetError(`Bazı oranlar düştü: ${decreasedDetails}. Kuponunuz güncellendi.`);
                        
                        // Force update the display
                        updateBetSlip(); // Update the display
                        updateBetCalculations();
                        
                        return; // Don't proceed with bet placement
                    }
                    
                    if (increasedBets.length > 0) {
                        updateBetSlip(); // Update the display with new odds
                        updateBetCalculations();
                        
                        // Show success message for increased odds
                        const increasedDetails = increasedBets.map(bet => 
                            `${bet.bet.selection}: ${bet.oldOdds} → ${bet.newOdds}`
                        ).join(', ');
                        showLiveBetSuccess(`${increasedBets.length} maçın oranı yükseldi: ${increasedDetails}. Yeni oranlarla devam ediliyor...`);
                        
                        // Wait 2 seconds then proceed with bet placement
                        setTimeout(async () => {
                            await processBetPlacement();
                        }, 2000);
                        return; // Exit here, processBetPlacement will be called after timeout
                    }
                }
                
                // Proceed with bet placement
                await processBetPlacement();
                
            } catch (error) {
                
                // Check if there are any API errors in validation results
                const apiErrors = validationResults.filter(r => r.status === 'error');
                if (apiErrors.length > 0) {
                    const errorDetails = apiErrors.map(e => `Maç ${e.matchId}: ${e.error}`).join(', ');
                    showLiveBetError(`API Hatası: ${errorDetails}`);
                } else {
                    showLiveBetError('Canlı oran kontrolü sırasında hata oluştu: ' + error.message);
                }
            } finally {
                // Reset button
                betButton.textContent = originalText;
                betButton.disabled = false;
                betButton.style.backgroundColor = '';
                betButton.style.color = '';
            }
        }
        
        // Show live bet error message
        function showLiveBetError(message) {
            const betButton = document.getElementById('place-bet-btn');
            betButton.textContent = message;
            betButton.style.backgroundColor = '#ef4444';
            betButton.style.color = 'white';
            
            // Also show a toast notification
            showToast(message, 'error');
            
            setTimeout(() => {
                betButton.textContent = 'BAHİS YAP';
                betButton.style.backgroundColor = '';
                betButton.style.color = '';
                updateBetCalculations();
            }, 5000);
        }
        
        // Show live bet success message
        function showLiveBetSuccess(message) {
            const betButton = document.getElementById('place-bet-btn');
            betButton.textContent = message;
            betButton.style.backgroundColor = '#10b981';
            betButton.style.color = 'white';
            
            // Also show a toast notification
            showToast(message, 'success');
        }
        
        // Show toast notification
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            
            // For mobile, show toast at bottom center
            const isMobile = shouldShowMobileView();
            const position = isMobile ? 'bottom-4 left-1/2 transform -translate-x-1/2' : 'top-4 right-4';
            
            toast.className = `fixed ${position} z-[9999] px-4 py-3 rounded-md text-white text-sm font-medium max-w-sm text-center ${
                type === 'error' ? 'bg-red-500' : 
                type === 'success' ? 'bg-green-500' : 
                type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500'
            }`;
            
            // Handle long messages and line breaks
            if (message.includes('\n')) {
                const lines = message.split('\n');
                lines.forEach((line, index) => {
                    if (line.trim()) {
                        const lineDiv = document.createElement('div');
                        lineDiv.textContent = line.trim();
                        if (index > 0) lineDiv.style.marginTop = '2px';
                        toast.appendChild(lineDiv);
                    }
                });
            } else {
                toast.textContent = message;
            }
            
            document.body.appendChild(toast);
            
            // Auto-remove after 6 seconds for longer messages
            const duration = message.length > 100 ? 6000 : 5000;
            setTimeout(() => {
                toast.remove();
            }, duration);
        }
        
        // Process bet placement (original placeBet logic)
        async function processBetPlacement() {
            const betAmountInput = document.getElementById('bet-amount-input');
            const betAmount = parseFloat(betAmountInput.value);
            
            // Calculate total odds
            const totalOdds = selectedBets.reduce((total, bet) => total * bet.odds, 1);
            
            // Prepare bet data
            const betData = {
                bets: selectedBets,
                total_amount: betAmount,
                total_odds: totalOdds
            };
            
            // For mobile users, add callback session data to request body
            if (isMobileDevice()) {
                // Get callback session data from meta tags
                const callbackUsername = document.querySelector('meta[name="callback-username"]')?.getAttribute('content');
                const callbackUserId = document.querySelector('meta[name="callback-user-id"]')?.getAttribute('content');
                const callbackAgentCode = document.querySelector('meta[name="callback-agent-code"]')?.getAttribute('content');
                
                if (callbackUsername && callbackUserId) {
                    betData.username = callbackUsername;
                    betData.user_id = callbackUserId;
                    betData.agent_code = callbackAgentCode;
                    
                    console.log('Mobile bet data with callback info:', {
                        username: callbackUsername,
                        user_id: callbackUserId,
                        agent_code: callbackAgentCode
                    });
                }
            }
            
            try {
                // Show loading state
                const betButton = document.getElementById('place-bet-btn');
                const originalText = betButton.textContent;
                betButton.textContent = 'KUPON YATIRILIYOR...';
                betButton.disabled = true;
                
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                // Try to get secure token from URL parameters
                const urlParams = new URLSearchParams(window.location.search);
                const secureToken = urlParams.get('secure_token');
                
                // Also try to get secure token from meta tag (set by callback)
                const metaSecureToken = document.querySelector('meta[name="secure-token"]')?.getAttribute('content');
                const finalSecureToken = secureToken || metaSecureToken;
                
                // Prepare headers
                const headers = {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                    'X-Requested-With': 'XMLHttpRequest'
                };
                
                // Add secure token to headers if available
                if (finalSecureToken) {
                    headers['X-Secure-Token'] = finalSecureToken;
                }
                
                // For mobile users, also add user data to request body
                if (isMobileDevice()) {
                    betData.username = '{{ session("callback_username") }}' || '{{ session("username") }}';
                    betData.user_id = '{{ session("callback_user_id") }}' || '{{ session("user_id") }}';
                    betData.agent_code = '{{ session("callback_agent_code") }}' || '{{ session("agent_code") }}';
                    
                    console.log('Mobile bet data being sent:', {
                        username: betData.username,
                        user_id: betData.user_id,
                        agent_code: betData.agent_code,
                        secure_token: finalSecureToken
                    });
                }
                
                const response = await fetch('/api/place-bet-slip', {
                    method: 'POST',
                    headers: headers,
                    body: JSON.stringify(betData)
                });
                
                
                
                
                const result = await response.json();
                
                
                if (result.success) {
                    // Success - Show green "KUPON BAŞARIYLA YATIRILDI" on button
                    betButton.textContent = 'KUPON BAŞARIYLA YATIRILDI';
                    betButton.style.backgroundColor = '#10b981'; // Green color
                    betButton.style.color = 'white';
                    
                    // Show success toast
                    showToast('Kupon başarıyla yatırıldı!', 'success');
                    
                    // Clear the betting slip
                    selectedBets = [];
                    saveBettingSlipToStorage();
                    updateBetSlip();
                    betAmountInput.value = '';
                    
                    // Reset button after 3 seconds
                    setTimeout(() => {
                        betButton.textContent = 'BAHİS YAP';
                        betButton.style.backgroundColor = '';
                        betButton.style.color = '';
                        updateBetCalculations(); // This will set correct button state
                    }, 3000);
                    
                } else {
                    // Error - Check if it's a balance error or started matches error
                    if (result.error && result.error.includes('Hay Aksi')) {
                        // Show balance error on button
                        betButton.textContent = result.error;
                        betButton.style.backgroundColor = '#ef4444'; // Red color
                        betButton.style.color = 'white';
                        
                        // Reset button after 4 seconds
                        setTimeout(() => {
                            betButton.textContent = 'BAHİS YAP';
                            betButton.style.backgroundColor = '';
                            betButton.style.color = '';
                            updateBetCalculations();
                        }, 4000);
                    } else if (result.error && result.error.includes('başlamış maçlar') && result.action === 'remove_started_matches') {
                        // Remove started matches from bet slip
                        
                        
                        // Remove started matches from selectedBets
                        result.started_matches.forEach(startedMatch => {
                            selectedBets = selectedBets.filter(bet => bet.eventId !== startedMatch.eventId);
                        });
                        
                        // Update bet slip display
                        updateBetSlip();
                        updateBetCalculations();
                        
                        // Show error message
                        betButton.textContent = result.error;
                        betButton.style.backgroundColor = '#ef4444'; // Red color
                        betButton.style.color = 'white';
                        
                        // Show toast notification
                        showToast(`${result.started_matches.length} başlamış maç kupondan kaldırıldı`, 'warning');
                        
                        // Reset button after 4 seconds
                        setTimeout(() => {
                            betButton.textContent = 'BAHİS YAP';
                            betButton.style.backgroundColor = '';
                            betButton.style.color = '';
                            updateBetCalculations();
                        }, 4000);
                    } else {
                        // Show detailed error message
                        let errorMessage = result.error || 'Kupon yatırılırken hata oluştu!';
                        
                        // Check if it's a specific match error
                        if (result.error && result.error.includes('maç')) {
                            // Extract match information from the error
                            const matchError = result.error;
                            betButton.textContent = matchError;
                            betButton.style.backgroundColor = '#ef4444'; // Red color
                            betButton.style.color = 'white';
                            
                            // Reset button after 4 seconds
                            setTimeout(() => {
                                betButton.textContent = 'BAHİS YAP';
                                betButton.style.backgroundColor = '';
                                betButton.style.color = '';
                                updateBetCalculations();
                            }, 4000);
                        } else {
                            // Show general error
                            betButton.textContent = errorMessage;
                            betButton.style.backgroundColor = '#ef4444'; // Red color
                            betButton.style.color = 'white';
                            
                            // Reset button after 4 seconds
                            setTimeout(() => {
                                betButton.textContent = 'BAHİS YAP';
                                betButton.style.backgroundColor = '';
                                betButton.style.color = '';
                                updateBetCalculations();
                            }, 4000);
                        }
                        
                        // Show error toast
                        showToast(errorMessage, 'error');
                    }
                }
                
            } catch (error) {
                alert(`❌ Bağlantı hatası: ${error.message}`);
            } finally {
                // Only restore button state if it's still showing loading
                // Don't override the success or error states
                if (betButton.textContent === 'KUPON YATIRILIYOR...') {
                    betButton.textContent = originalText;
                    updateBetCalculations(); // This will set correct button state
                }
            }
        }
        
        function cleanupOldLocalStorage() {
            try {
                const saved = localStorage.getItem('bettingSlip');
                if (saved) {
                    const savedBets = JSON.parse(saved);
                    if (savedBets.some(bet => bet.matchId && typeof bet.matchId === 'number' && !bet.eventId)) {
                        localStorage.removeItem('bettingSlip');
                        return true;
                    }
                }
            } catch (e) {
                localStorage.removeItem('bettingSlip');
                return true;
            }
            return false;
        }
        
        // Function to initialize live mode if needed
        function initLiveModeIfNeeded() {
            try {
                // Check if we should start in live mode (e.g., if there's a URL parameter)
                const urlParams = new URLSearchParams(window.location.search);
                const startLiveMode = urlParams.has('live') || window.location.hash.includes('live');
                
                if (startLiveMode) {
                    // Initialize live mode
                    const liveBtn = document.querySelector('.live-btn');
                    const preMatchBtn = document.querySelector('.pre-match-btn');
                    const liveContent = document.getElementById('live-content');
                    const preMatchContent = document.getElementById('pre-match-content');
                    const sidebar = document.getElementById('sidebar');
                    const mainContent = document.getElementById('main-content');
                    const oddsContent = document.querySelector('.odds-content');
                    
                    if (liveBtn && preMatchBtn && liveContent && preMatchContent) {
                        // Update button styles
                        liveBtn.classList.remove('bg-[#3a3a3a]', 'text-[#a0a0a0]');
                        liveBtn.classList.add('bg-[#f59e0b]', 'text-[#1e1e1e]', 'font-semibold');
                        
                        preMatchBtn.classList.remove('bg-[#f59e0b]', 'text-[#1e1e1e]', 'font-semibold');
                        preMatchBtn.classList.add('bg-[#3a3a3a]', 'text-[#a0a0a0]');
                        
                        // Switch content
                        preMatchContent.classList.add('hidden');
                        liveContent.classList.remove('hidden');
                        
                        // Adjust sidebar for live mode
                        if (sidebar) {
                            sidebar.classList.add('live-mode');
                        }
                        
                        // Hide the middle matches section and expand odds section
                        if (mainContent) {
                            mainContent.classList.add('live-mode');
                        }
                        if (oddsContent) {
                            oddsContent.classList.add('expanded-odds');
                        }
                        
                        // Initialize Sportradar iframe with default message
                        updateSportradarIframe(null);
                        
                        // Load live matches
                        loadLiveMatches();
                    }
                }
            } catch (error) {
            }
        }
        
        // Function to update the Sportradar iframe with the selected match's betradar_id
        function updateSportradarIframe(betradarId, matchData = null) {
            const container = document.getElementById('sportradar-iframe-container');
            if (!container) return;
            
            // Always show soccer.png in the original container
            container.innerHTML = `
                <img alt="Futbol sahası" class="w-full h-auto object-cover" src="/images/soccer.png" width="480"/>
                
                <!-- Match Info Overlay -->
                <div id="match-info-overlay" class="absolute bottom-2 left-2 text-white font-semibold text-sm bg-[#000000b3] rounded px-3 py-2" style="display: none;">
                    <div id="match-teams" class="text-white font-semibold text-sm"></div>
                </div>
                
                <!-- Live Match Iframe Overlay for Desktop -->
                <div id="live-iframe-overlay">
                    <div class="overlay-content">
                        <!-- Iframe will be loaded here -->
                    </div>
                </div>
            `;
                
                // Show the overlay elements when showing default image
                const sportIcon = document.getElementById('selected-sport-icon');
                const countryFlag = document.getElementById('selected-country-flag');
                const leagueTitle = document.getElementById('selected-league-title');
                const matchDate = document.getElementById('selected-match-date');
                
                if (sportIcon) sportIcon.style.display = 'flex';
                if (countryFlag) countryFlag.style.display = 'block';
                if (leagueTitle) leagueTitle.style.display = 'block';
                if (matchDate) matchDate.style.display = 'block';
            
            // If betradarId exists, show iframe in overlay for desktop
            if (betradarId && window.innerWidth >= 1024) {
                showLiveIframeOverlay(betradarId, true, matchData); // true for live matches
            }
        }
        
        function showLiveIframeOverlay(betradarId, isLive = false, matchData = null) {
            const overlay = document.getElementById('live-iframe-overlay');
            if (!overlay) return;
            
            const iframeUrl = `https://widgets.sir.sportradar.com/sportradar/tr/standalone/match.lmtPlus#matchId=${betradarId}&scoreboard=disable`;
            
            
            // Show match info overlay
            const matchInfoOverlay = document.getElementById('match-info-overlay');
            const matchTeams = document.getElementById('match-teams');
            
            if (matchData && matchInfoOverlay && matchTeams) {
                // Show only team names in bottom overlay
                matchTeams.innerHTML = `
                    ${matchData.evsahibi_isim || 'Evsahibi'} - ${matchData.misafir_isim || 'Misafir'}
                `;
                matchInfoOverlay.style.display = 'block';
                
                // Update top overlay with league and time
                const leagueTitle = document.getElementById('selected-league-title');
                const matchDate = document.getElementById('selected-match-date');
                
                if (leagueTitle && matchData.lig_isim) {
                    leagueTitle.textContent = matchData.lig_isim;
                    leagueTitle.style.display = 'block';
                }
                
                if (matchDate && matchData.baslangic) {
                    const date = new Date(matchData.baslangic);
                    matchDate.textContent = date.toLocaleDateString('tr-TR', { day: '2-digit', month: '2-digit', year: 'numeric' }) + ', ' + 
                                          date.toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' });
                    matchDate.style.display = 'block';
                }
            }
            
            const overlayContent = overlay.querySelector('.overlay-content');
            if (overlayContent) {
                overlayContent.innerHTML = `
                    <iframe src="${iframeUrl}" 
                            width="100%" 
                            height="100%" 
                            frameborder="0" 
                            scrolling="no">
                    </iframe>
                `;
            }
            
            overlay.style.display = 'block';
        }
        
        function closeLiveIframeOverlay() {
            const overlay = document.getElementById('live-iframe-overlay');
            if (overlay) {
                overlay.style.display = 'none';
                const overlayContent = overlay.querySelector('.overlay-content');
                if (overlayContent) {
                    overlayContent.innerHTML = '';
                }
            }
            
            // Hide match info overlay
            const matchInfoOverlay = document.getElementById('match-info-overlay');
            if (matchInfoOverlay) {
                matchInfoOverlay.style.display = 'none';
            }
        }
        
        
        // Show popular matches (Turkey, Holland, Portugal, France, Italy, Germany)
        function showPopularMatches() {
            // Clear any existing live odds data when switching to bulletin mode
            window.allOddsData = null;
            window.selectedMatchId = null;
            currentMarketFilter = 'tumu';
            
            // Clear the odds display
            const oddsContainer = document.getElementById('kazanan-submenu');
            if (oddsContainer) {
                oddsContainer.innerHTML = `
                    <div class="text-center text-xs text-[#a0a0a0] py-8">
                        <i class="fas fa-futbol text-2xl mb-2 block text-[#f59e0b]"></i>
                        Maç seçin
                        <div class="text-[10px] mt-1">Oranları görmek için bir maça tıklayın</div>
                    </div>
                `;
            }
            
            // Clear match detail area (iframe and match info overlay)
            const sportradarContainer = document.getElementById('sportradar-iframe-container');
            if (sportradarContainer) {
                sportradarContainer.innerHTML = `
                    <img alt="Futbol sahası" class="w-full h-auto object-cover" src="/images/soccer.png" width="480"/>
                    
                    <!-- Match Info Overlay -->
                    <div id="match-info-overlay" class="absolute bottom-2 left-2 text-white font-semibold text-sm bg-[#000000b3] rounded px-3 py-2" style="display: none;">
                        <div id="match-teams" class="text-white font-semibold text-sm"></div>
                    </div>
                    
                    <!-- Live Match Iframe Overlay for Desktop -->
                    <div id="live-iframe-overlay">
                        <div class="overlay-content">
                            <!-- Iframe will be loaded here -->
                        </div>
                    </div>
                `;
            }
            
            // Reset filter buttons to default state
            document.querySelectorAll('.market-filter').forEach(button => {
                button.classList.remove('border-b-2', 'border-[#f59e0b]', 'text-[#f59e0b]', 'font-semibold');
                button.classList.add('text-[#6b6b6b]');
            });
            
            // Set TÜMÜ as active
            const tumuButton = document.querySelector('.market-filter[data-filter="tumu"]');
            if (tumuButton) {
                tumuButton.classList.remove('text-[#6b6b6b]');
                tumuButton.classList.add('border-b-2', 'border-[#f59e0b]', 'text-[#f59e0b]', 'font-semibold');
            }
            
            const popularCountries = ['turkey', 'hollanda', 'portekiz', 'fransa', 'italya', 'germany'];
            const popularCountryNames = ['Turkey', 'Hollanda', 'Portekiz', 'Fransa', 'İtalya', 'Germany'];
            
            // Filter matches by popular countries
            const filteredMatches = [];
            
            // Get all matches from matchData
            Object.values(matchData).forEach(match => {
                // Check if match country is in popular countries
                if (match.country && popularCountries.includes(match.country.toLowerCase())) {
                    filteredMatches.push(match);
                }
            });
            
            
            
            // Update the matches display
            updateMatchesDisplay(filteredMatches, 'Popüler Maçlar');
        }
        
        // Show upcoming matches (today's matches)
        function showUpcomingMatches() {
            // Clear any existing live odds data when switching to bulletin mode
            window.allOddsData = null;
            window.selectedMatchId = null;
            currentMarketFilter = 'tumu';
            
            // Clear the odds display
            const oddsContainer = document.getElementById('kazanan-submenu');
            if (oddsContainer) {
                oddsContainer.innerHTML = `
                    <div class="text-center text-xs text-[#a0a0a0] py-8">
                        <i class="fas fa-futbol text-2xl mb-2 block text-[#f59e0b]"></i>
                        Maç seçin
                        <div class="text-[10px] mt-1">Oranları görmek için bir maça tıklayın</div>
                    </div>
                `;
            }
            
            // Clear match detail area (iframe and match info overlay)
            const sportradarContainer = document.getElementById('sportradar-iframe-container');
            if (sportradarContainer) {
                sportradarContainer.innerHTML = `
                    <img alt="Futbol sahası" class="w-full h-auto object-cover" src="/images/soccer.png" width="480"/>
                    
                    <!-- Match Info Overlay -->
                    <div id="match-info-overlay" class="absolute bottom-2 left-2 text-white font-semibold text-sm bg-[#000000b3] rounded px-3 py-2" style="display: none;">
                        <div id="match-teams" class="text-white font-semibold text-sm"></div>
                    </div>
                    
                    <!-- Live Match Iframe Overlay for Desktop -->
                    <div id="live-iframe-overlay">
                        <div class="overlay-content">
                            <!-- Iframe will be loaded here -->
                        </div>
                    </div>
                `;
            }
            
            // Reset filter buttons to default state
            document.querySelectorAll('.market-filter').forEach(button => {
                button.classList.remove('border-b-2', 'border-[#f59e0b]', 'text-[#f59e0b]', 'font-semibold');
                button.classList.add('text-[#6b6b6b]');
            });
            
            // Set TÜMÜ as active
            const tumuButton = document.querySelector('.market-filter[data-filter="tumu"]');
            if (tumuButton) {
                tumuButton.classList.remove('text-[#6b6b6b]');
                tumuButton.classList.add('border-b-2', 'border-[#f59e0b]', 'text-[#f59e0b]', 'font-semibold');
            }
            
            const today = new Date();
            const todayString = today.toISOString().split('T')[0]; // YYYY-MM-DD format
            
            // Filter matches by today's date
            const filteredMatches = [];
            
            // Get all matches from matchData
            Object.values(matchData).forEach(match => {
                const matchDate = new Date(match.date);
                const matchDateString = matchDate.toISOString().split('T')[0];
                
                if (matchDateString === todayString) {
                    filteredMatches.push(match);
                }
            });
            
            // Update the matches display
            updateMatchesDisplay(filteredMatches, 'Yaklaşan Maçlar');
        }
        
        // Update matches display
        function updateMatchesDisplay(matches, title) {
            
            
            // Update header
            const headerTitle = document.getElementById('main-header-title');
            if (headerTitle) {
                headerTitle.textContent = title;
            }
            
            // Update matches container - target the correct container
            const matchesContainer = document.getElementById('match-list');
            if (matchesContainer) {
                if (matches.length === 0) {
                    matchesContainer.innerHTML = `
                        <div class="text-center text-gray-400 py-8">
                            <i class="fas fa-info-circle text-2xl mb-2"></i>
                            <div>${title} için maç bulunamadı.</div>
                        </div>
                    `;
                } else {
                    // Create matches without header (like initial load)
                    let html = ``;
                    
                    // Add matches
                    html += matches.map((match, index) => `
                        <div class="bg-[#2a2a2a] rounded-md px-3 py-2 flex justify-between items-center mb-2 match-item hover:bg-[#3a3a3a] transition-colors cursor-pointer" 
                             onclick="selectMatch('${match.displayId}')" 
                             data-match-id="${match.displayId}" 
                             data-league="${match.league}" 
                             data-sport="${match.sport}">
                            <div class="flex items-center space-x-2">
                                <div class="flex flex-col items-center space-y-1">
                                    <button aria-label="Favorite ${decodeHtmlEntities(match.home)} vs ${decodeHtmlEntities(match.away)}" class="text-[#d9d9d9] hover:text-yellow-400 focus:outline-none">
                                        <i class="far fa-star text-xs"></i>
                                    </button>
                                    <span class="text-[10px] text-[#999999] select-none">
                                        ${match.date.split(', ')[1] || match.date}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-xs font-normal leading-4 text-white">
                                        ${decodeHtmlEntities(match.home)}
                                    </p>
                                    <p class="text-xs font-normal leading-4 text-white">
                                        ${decodeHtmlEntities(match.away)}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-1 ml-2">
                                <button class="w-10 h-7 ${match.odds1 && match.odds1 > 0 ? 'bg-[#555555] text-[#d9b24a] hover:bg-[#666666]' : 'bg-[#2a2a2a] text-gray-600 cursor-not-allowed'} rounded-md font-normal text-xs flex items-center justify-center transition-colors" 
                                        type="button" 
                                        ${match.odds1 && match.odds1 > 0 ? `onclick="event.stopPropagation(); addBet('1', ${match.odds1}, '${match.displayId}', 'Maç Sonucu', '${decodeHtmlEntities(match.home)}')"` : 'disabled'}>
                                    ${match.odds1 && match.odds1 > 0 ? match.odds1.toFixed(2) : '<i class="fas fa-lock"></i>'}
                                </button>
                                <button class="w-10 h-7 ${match.oddsX && match.oddsX > 0 ? 'bg-[#555555] text-[#d9b24a] hover:bg-[#666666]' : 'bg-[#2a2a2a] text-gray-600 cursor-not-allowed'} rounded-md font-normal text-xs flex items-center justify-center transition-colors" 
                                        type="button" 
                                        ${match.oddsX && match.oddsX > 0 ? `onclick="event.stopPropagation(); addBet('X', ${match.oddsX}, '${match.displayId}', 'Maç Sonucu', 'Berabere')"` : 'disabled'}>
                                    ${match.oddsX && match.oddsX > 0 ? match.oddsX.toFixed(2) : '<i class="fas fa-lock"></i>'}
                                </button>
                                <button class="w-10 h-7 ${match.odds2 && match.odds2 > 0 ? 'bg-[#555555] text-[#d9b24a] hover:bg-[#666666]' : 'bg-[#2a2a2a] text-gray-600 cursor-not-allowed'} rounded-md font-normal text-xs flex items-center justify-center transition-colors" 
                                        type="button" 
                                        ${match.odds2 && match.odds2 > 0 ? `onclick="event.stopPropagation(); addBet('2', ${match.odds2}, '${match.displayId}', 'Maç Sonucu', '${decodeHtmlEntities(match.away)}')"` : 'disabled'}>
                                    ${match.odds2 && match.odds2 > 0 ? match.odds2.toFixed(2) : '<i class="fas fa-lock"></i>'}
                                </button>
                                <span class="text-[#999999] text-[10px] font-normal ml-1 select-none w-8 text-center">
                                    +${match.oran_adet || match.oddsCount || 0}
                                </span>
                                <i class="fas fa-chevron-right text-[#d9d9d9] ml-1"></i>
                            </div>
                        </div>
                    `).join('');
                    
                    matchesContainer.innerHTML = html;
                }
            } else {
            }
            
            // No need to re-attach listeners since we're using onclick attribute
            
        }
        
        // Mobile Functions
        function switchMobileTab(tab) {
            // Update tab buttons
            document.querySelectorAll('button').forEach(btn => {
                btn.classList.remove('bg-gray-700', 'text-gray-300');
                btn.classList.add('bg-gray-800', 'text-purple-400');
            });
            
            event.target.classList.remove('bg-gray-800', 'text-purple-400');
            event.target.classList.add('bg-gray-700', 'text-gray-300');
            
            // Show leagues by default
            document.getElementById('mobile-leagues-list').style.display = 'block';
            document.getElementById('mobile-matches-list').style.display = 'none';
            
            if (tab === 'upcoming') {
                showUpcomingMatches();
            } else if (tab === 'popular') {
                showPopularMatches();
            } else if (tab === 'live') {
                // Show live matches
                populateMobileLiveMatches();
            }
        }
        
        function populateMobileLeagues() {
            const leaguesContainer = document.getElementById('mobile-leagues-list');
            if (!leaguesContainer) return;
            
            // Use bulletin data when in bulletin mode
            const dataToUse = currentMode === 'bulletin' ? (savedBulletinData || matchData || {}) : (matchData || {});
            
            // Check if we have data
            if (!dataToUse || Object.keys(dataToUse).length === 0) {
                leaguesContainer.innerHTML = '<div class="text-center py-4 text-gray-500">Maç verisi yükleniyor...</div>';
                return;
            }
            
            // Group matches by country
            const countries = {};
            Object.values(dataToUse).forEach(match => {
                const country = match.country || 'Diğer';
                if (!countries[country]) {
                    countries[country] = [];
                }
                countries[country].push(match);
            });
            
            let html = '';
            // Sort countries: Turkey first, then alphabetically
            const sortedCountries = Object.keys(countries).sort((a, b) => {
                const aIsTurkey = (a.toLowerCase().includes('turkey') || a.toLowerCase().includes('türkiye'));
                const bIsTurkey = (b.toLowerCase().includes('turkey') || b.toLowerCase().includes('türkiye'));
                
                // Turkey first
                if (aIsTurkey && !bIsTurkey) return -1;
                if (!aIsTurkey && bIsTurkey) return 1;
                
                // Then alphabetically
                return a.localeCompare(b);
            });
            
            sortedCountries.forEach(country => {
                const matches = countries[country];
                const flagSrc = getCountryFlag(country);
                
                html += `
                    <div class="bg-[#192231] rounded">
                        <button type="button" class="w-full flex items-center justify-between px-3 py-1.5 text-gray-300" onclick="toggleMobileCountry('${country}')">
                            <div class="flex items-center space-x-2">
                                <img src="${flagSrc}" alt="${country} bayrağı" class="w-5 h-3.5 object-cover" width="20" height="14" loading="lazy" onerror="this.src='/images/flags/defaults.png'" />
                                <span>${country}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span>${matches.length}</span>
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </button>
                        <div class="border-t border-gray-700" id="mobile-country-${country}" style="display: none;">
                            ${(() => {
                                // Get unique leagues for this country
                                const uniqueLeagues = [...new Set(matches.map(match => match.league))];
                                return uniqueLeagues.map(league => {
                                    const leagueMatchCount = matches.filter(m => m.league === league).length;
                                    return `
                                        <button type="button" class="w-full flex items-center justify-between px-3 py-1.5 text-gray-300 hover:bg-gray-700" onclick="showMobileMatches('${league}')">
                                            <div class="flex items-center space-x-2">
                                                <i class="far fa-star text-gray-400"></i>
                                                <span>${league}</span>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-xs">${leagueMatchCount}</span>
                                                <i class="fas fa-chevron-right text-gray-400"></i>
                                            </div>
                                        </button>
                                    `;
                                }).join('');
                            })()}
                        </div>
                    </div>
                `;
            });
            
            leaguesContainer.innerHTML = html;
        }
        
        function toggleMobileCountry(country) {
            const content = document.getElementById(`mobile-country-${country}`);
            content.style.display = content.style.display === 'none' ? 'block' : 'none';
        }
        
        function showMobileMatches(league) {
            const filteredMatches = Object.values(matchData).filter(match => match.league === league);
            
            const matchesContainer = document.getElementById('mobile-matches-list');
            const leaguesContainer = document.getElementById('mobile-leagues-list');
            const backBtn = document.getElementById('mobile-back-btn');
            const searchContainer = document.querySelector('.px-3.py-2');
            const sportsContainer = document.getElementById('mobile-sports');
            
            // Hide all unnecessary elements
            leaguesContainer.style.display = 'none';
            if (searchContainer) searchContainer.style.display = 'none';
            if (sportsContainer) sportsContainer.style.display = 'none';
            
            // Show matches and back button
            matchesContainer.style.display = 'block';
            backBtn.style.display = 'flex';
            document.getElementById('mobile-back-text').textContent = league;
            
            let html = '';
            filteredMatches.forEach(match => {
                // Get sport icon and color
                let sportIcon = 'fa-futbol';
                let sportColor = 'text-green-500';
                const sportLower = (match.sport || 'futbol').toLowerCase();
                if (sportLower === 'futbol') {
                    sportIcon = 'fa-futbol';
                    sportColor = 'text-green-500';
                } else if (sportLower === 'basketbol') {
                    sportIcon = 'fa-basketball-ball';
                    sportColor = 'text-orange-500';
                } else if (sportLower === 'tenis') {
                    sportIcon = 'fa-table-tennis';
                    sportColor = 'text-green-400';
                } else if (sportLower === 'voleybol') {
                    sportIcon = 'fa-volleyball-ball';
                    sportColor = 'text-blue-400';
                } else if (sportLower === 'masatenisi') {
                    sportIcon = 'fa-table-tennis';
                    sportColor = 'text-red-500';
                }
                
                html += `
                    <section class="mb-3 border-b border-[#1a2a4a] pb-3 cursor-pointer bg-gradient-to-r from-[#0f1a2b] to-[#0a1220] rounded-lg p-2.5" onclick="showMobileMatchDetail('${match.eventId}')">
                        <div class="flex justify-between text-[#7a8dbd] text-[10px] mb-1.5">
                            <time datetime="${match.date}" class="truncate">${match.date.split(' ')[1] || match.date}</time>
                            <span class="opacity-60">⚽</span>
                        </div>
                        <div class="flex justify-between items-center mb-1">
                            <div class="text-sm text-[#b9b9b9] flex-1 truncate pr-1">${decodeHtmlEntities(match.home)}</div>
                            <div class="flex flex-col items-center">
                                <div class="text-[9px] text-[#7a8dbd] font-medium mb-0.5">Maç Sonucu</div>
                                <div class="text-[10px] text-[#7a8dbd]">VS</div>
                            </div>
                            <div class="text-sm text-[#b9b9b9] flex-1 truncate pl-1 text-right">${decodeHtmlEntities(match.away)}</div>
                        </div>
                        <div class="grid grid-cols-3 gap-1.5">
                            <button class="bg-[#1a2a4a] hover:bg-[#2a3a5a] rounded py-1.5 text-yellow-400 font-semibold transition-colors text-xs" type="button" onclick="event.stopPropagation(); addMobileBet('1', ${match.oran1 || match.odds1 || 2.0}, '${match.eventId}', 'Maç Sonucu', '${decodeHtmlEntities(match.home)}')">
                                ${(match.oran1 || match.odds1 || 2.0).toFixed(2)}
                            </button>
                            <button class="bg-[#1a2a4a] hover:bg-[#2a3a5a] rounded py-1.5 text-yellow-400 font-semibold transition-colors text-xs" type="button" onclick="event.stopPropagation(); addMobileBet('X', ${match.oran0 || match.oddsX || 3.0}, '${match.eventId}', 'Maç Sonucu', 'Berabere')">
                                ${(match.oran0 || match.oddsX || 3.0).toFixed(2)}
                            </button>
                            <button class="bg-[#1a2a4a] hover:bg-[#2a3a5a] rounded py-1.5 text-yellow-400 font-semibold transition-colors text-xs" type="button" onclick="event.stopPropagation(); addMobileBet('2', ${match.oran2 || match.odds2 || 3.5}, '${match.eventId}', 'Maç Sonucu', '${decodeHtmlEntities(match.away)}')">
                                ${(match.oran2 || match.odds2 || 3.5).toFixed(2)}
                            </button>
                        </div>
                        <div class="flex items-center justify-between text-[#7a8dbd] text-[10px] mt-2">
                            <button aria-label="Favorite match" class="text-[#7a8dbd] hover:text-white" onclick="event.stopPropagation();">
                                <i class="far fa-star text-xs"></i>
                            </button>
                            <div class="flex items-center space-x-1">
                                <span class="text-[9px] bg-[#1a2a4a] px-1.5 py-0.5 rounded-full">+${Math.floor(Math.random() * 200) + 100}</span>
                                <button class="text-[#7a8dbd] hover:text-white" onclick="event.stopPropagation(); showMobileMatchDetail('${match.eventId}')">
                                    <i class="fas fa-chevron-right text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </section>
                `;
            });
            
            matchesContainer.innerHTML = html;
        }
        
        function showMobileMatchDetail(eventId) {
            const match = matchData[eventId];
            if (!match) return;
            
            // Show loading state immediately for instant feedback
            const matchDetailContainer = document.getElementById('mobile-match-detail');
            const matchesContainer = document.getElementById('mobile-matches-list');
            const backBtn = document.getElementById('mobile-back-btn');
            
            // Instant UI changes
            matchesContainer.style.display = 'none';
            matchDetailContainer.style.display = 'block';
            backBtn.style.display = 'flex';
            document.getElementById('mobile-back-text').textContent = `${match.home} vs ${match.away}`;
            
            // Show loading content immediately
            matchDetailContainer.innerHTML = `
                <div class="flex items-center justify-center py-12">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin text-2xl text-[#f7931e] mb-2"></i>
                        <div class="text-gray-400 text-sm">Maç detayları yükleniyor...</div>
                    </div>
                </div>
            `;
            
            // Use requestAnimationFrame for smooth rendering
            requestAnimationFrame(() => {
                // Get sport icon based on sport type
                let sportIcon = 'fa-futbol';
                let sportColor = 'text-green-500';
            
            const sportLower = (match.sport || 'futbol').toLowerCase();
            if (sportLower === 'futbol') {
                sportIcon = 'fa-futbol';
                sportColor = 'text-green-500';
            } else if (sportLower === 'basketbol') {
                sportIcon = 'fa-basketball-ball';
                sportColor = 'text-orange-500';
            } else if (sportLower === 'tenis') {
                sportIcon = 'fa-table-tennis';
                sportColor = 'text-yellow-500';
            } else if (sportLower === 'voleybol') {
                sportIcon = 'fa-volleyball-ball';
                sportColor = 'text-blue-500';
            } else if (sportLower === 'masatenisi') {
                sportIcon = 'fa-table-tennis';
                sportColor = 'text-red-500';
            }
            
            // Fetch all odds from API
            fetchMatchOdds(eventId, match).then(oddsData => {
                let marketsHtml = '';
                let marketCount = 0;
                
                // Parse the odds data - handle both string and object formats
                let parsedOdds = oddsData;
                if (typeof oddsData === 'string') {
                    try {
                        parsedOdds = JSON.parse(oddsData);
                    } catch (e) {
                        
                        parsedOdds = oddsData;
                    }
                }
                
                if (parsedOdds) {
                    // Main odds (Kazanan)
                    if (parsedOdds.main_odds) {
                        marketCount++;
                        marketsHtml += `
                            <div class="px-3 py-2 text-sm text-white font-semibold flex justify-between items-center bg-[#1a2a4a] border-b border-[#2a3a5a]">
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-trophy text-[#f59e0b] text-sm"></i>
                                    Kazanan
                                </span>
                                <i class="fas fa-sync-alt text-[#3a7d3a] text-xs"></i>
                            </div>
                            <div class="p-2.5 grid grid-cols-3 gap-1.5">
                        `;
                        
                        Object.keys(parsedOdds.main_odds).forEach(outcomeKey => {
                            const odds = parsedOdds.main_odds[outcomeKey];
                            let outcomeName = outcomeKey;
                            if (outcomeKey === '1') outcomeName = match.home;
                            else if (outcomeKey === '2') outcomeName = match.away;
                            else if (outcomeKey === 'X') outcomeName = 'Berabere';
                            
                            const betId = `${match.eventId}_Kazanan_${outcomeKey}`;
                            
                            marketsHtml += `
                                <button class="mobile-odds-btn flex flex-col items-center justify-center bg-[#2a3a5a] hover:bg-[#3a4a6a] rounded-lg py-2 px-2 transition-all duration-200 border border-[#3a4a6a] hover:border-[#f59e0b]" 
                                        data-bet-id="${betId}" 
                                        onclick="addMobileBet('${outcomeKey}', ${odds}, '${match.eventId}', 'Kazanan', '${outcomeName}'); toggleMobileOddsSelection(this, '${betId}')">
                                    <span class="text-white text-[10px] font-medium mb-0.5 truncate w-full text-center">${outcomeName}</span>
                                    <span class="text-[#f59e0b] font-bold text-xs">${odds.toFixed(2)}</span>
                                </button>
                            `;
                        });
                        
                        marketsHtml += `</div>`;
                    }
                    
                    // Additional odds - show ALL markets from API
                    if (parsedOdds.additional_odds) {
                        Object.keys(parsedOdds.additional_odds).forEach(marketKey => {
                            const market = parsedOdds.additional_odds[marketKey];
                            marketCount++;
                            
                            marketsHtml += `
                                <div class="px-3 py-2 text-sm text-white font-semibold flex justify-between items-center bg-[#1a2a4a] border-b border-[#2a3a5a] mt-3">
                                    <span class="flex items-center gap-2">
                                        <i class="fas fa-chart-line text-[#f59e0b] text-sm"></i>
                                        ${marketKey}
                                    </span>
                                    <i class="fas fa-sync-alt text-[#3a7d3a] text-xs"></i>
                                </div>
                                <div class="p-2.5 grid grid-cols-2 gap-1.5">
                            `;
                            
                            Object.keys(market).forEach(outcomeKey => {
                                const odds = market[outcomeKey];
                                const betId = `${match.eventId}_${marketKey}_${outcomeKey}`;
                                
                                marketsHtml += `
                                    <button class="mobile-odds-btn flex items-center justify-between bg-[#2a3a5a] hover:bg-[#3a4a6a] rounded-lg py-2 px-2.5 transition-all duration-200 border border-[#3a4a6a] hover:border-[#f59e0b]" 
                                            data-bet-id="${betId}" 
                                            onclick="addMobileBet('${marketKey}_${outcomeKey}', ${odds}, '${match.eventId}', '${marketKey}', '${outcomeKey}'); toggleMobileOddsSelection(this, '${betId}')">
                                        <span class="text-white text-[10px] font-medium truncate">${outcomeKey}</span>
                                        <span class="text-[#f59e0b] font-bold text-xs ml-2">${odds.toFixed(2)}</span>
                                    </button>
                                `;
                            });
                            
                            marketsHtml += `</div>`;
                        });
                    }
                }
                
                // If no API data, show basic odds
                if (!marketsHtml) {
                    marketsHtml = `
                        <div class="px-3 py-2 text-xs text-[#7a8dbd] font-semibold flex justify-between items-center cursor-pointer border-b border-[#1a2a4a]">
                            <span>Kazanan</span>
                            <i class="fas fa-sync-alt text-[#3a7d3a]"></i>
                        </div>
                        <div class="px-3 py-2 text-xs grid grid-cols-2 gap-x-6 gap-y-1 text-[#b9b9b9]">
                            <div class="flex justify-between" onclick="addMobileBet('1', ${match.oran1 || match.odds1}, '${match.eventId}', 'Kazanan', '${match.home}')">
                                <span>${match.home}</span>
                                <span class="text-yellow-400 font-semibold">${(match.oran1 || match.odds1).toFixed(2)}</span>
                            </div>
                            <div class="flex justify-between" onclick="addMobileBet('X', ${match.oran0 || match.oddsX}, '${match.eventId}', 'Kazanan', 'Berabere')">
                                <span>Berabere</span>
                                <span class="text-yellow-400 font-semibold">${(match.oran0 || match.oddsX).toFixed(2)}</span>
                            </div>
                            <div class="flex justify-between" onclick="addMobileBet('2', ${match.oran2 || match.odds2}, '${match.eventId}', 'Kazanan', '${match.away}')">
                                <span>${match.away}</span>
                                <span class="text-yellow-400 font-semibold">${(match.oran2 || match.odds2).toFixed(2)}</span>
                            </div>
                        </div>
                    `;
                }
                
                // Create match detail page with all markets - exactly like mac.html
                const html = `
                    <div class="fixed inset-0 bg-[#0a1220] z-20 overflow-y-auto pb-16">
                        <div class="w-full rounded-md overflow-hidden shadow-lg bg-gradient-to-b from-[#0f1a2b] to-[#0a1220] min-h-screen">
                        <!-- Back Button -->
                        <div class="flex justify-start p-2">
                            <button onclick="closeMobileMatchDetail()" class="text-[#7a8dbd] hover:text-white flex items-center gap-2">
                                <i class="fas fa-arrow-left text-lg"></i>
                                <span class="text-sm font-medium">Geri</span>
                            </button>
                        </div>
                        
                        <!-- Banner with image and text -->
                        <div class="relative">
                            <img alt="Match banner" class="w-full object-cover h-28" height="120" src="https://storage.googleapis.com/a1aa/image/3ab0efd3-cba0-45b6-6d75-3914ff165093.jpg" width="400"/>
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0a1220] via-transparent to-transparent"></div>
                            <div class="absolute bottom-2 left-3 text-white">
                                <div class="flex items-center gap-1 text-xs font-semibold text-[#7a8dbd]">
                                    <i class="fas ${sportIcon} ${sportColor}"></i>
                                    <span>${match.league}</span>
                                </div>
                                <div class="text-[10px] mt-0.5 text-[#7a8dbd]">
                                    ${match.date}
                                </div>
                                <div class="text-base font-semibold mt-1">
                                    ${match.home} vs ${match.away}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Navigation Tabs -->
                        <nav class="flex items-center gap-4 bg-[#0a1220] px-3 py-2 border-b border-[#1a2a4a] text-xs font-semibold text-[#7a8dbd]">
                            <button class="flex items-center gap-1 text-white border-b-2 border-white pb-1" onclick="switchMobileMarketTab('all')">
                                <i class="fas fa-star"></i>
                                Tümü
                            </button>
                            <button class="flex items-center gap-1 hover:text-white" onclick="switchMobileMarketTab('general')">
                                Genel
                                <sup class="ml-0.5 text-[8px] font-normal">${marketCount}</sup>
                            </button>
                            <button class="flex items-center gap-1 hover:text-white" onclick="switchMobileMarketTab('players')">
                                Oyuncular
                                <sup class="ml-0.5 text-[8px] font-normal">0</sup>
                            </button>
                        </nav>
                        
                        <!-- Market Section -->
                        <div class="px-3 py-2 text-xs text-[#7a8dbd] font-semibold border-b border-[#1a2a4a]">
                            Marketler
                        </div>
                        
                        <!-- All Markets -->
                        ${marketsHtml}
                        </div>
                    </div>
                `;
                
                matchDetailContainer.innerHTML = html;
            }).catch(error => {
                // Show basic odds if API fails
                showBasicMobileOdds(match, matchDetailContainer, sportIcon, sportColor);
            });
            }); // Close requestAnimationFrame
        }
        
        function closeMobileMatchDetail() {
            const matchDetailContainer = document.getElementById('mobile-match-detail');
            const matchesContainer = document.getElementById('mobile-matches-list');
            const leaguesContainer = document.getElementById('mobile-leagues-list');
            const tabsContainer = document.getElementById('mobile-tabs');
            const backBtn = document.getElementById('mobile-back-btn');
            
            if (matchDetailContainer) {
                matchDetailContainer.innerHTML = '';
                matchDetailContainer.style.display = 'none';
                
                // Show matches list again
                if (matchesContainer) {
                    matchesContainer.style.display = 'block';
                }
                
                // Show back button if it exists
                if (backBtn) {
                    backBtn.style.display = 'flex';
                }
                
                // Show tabs if they exist
                if (tabsContainer) {
                    tabsContainer.style.display = 'flex';
                }
            }
        }
        
        function showBasicMobileOdds(match, container, sportIcon, sportColor) {
            const html = `
                <div class="fixed inset-0 bg-[#0a1220] z-40 overflow-y-auto pb-16">
                    <div class="w-full rounded-md overflow-hidden shadow-lg bg-gradient-to-b from-[#0f1a2b] to-[#0a1220] min-h-screen">
                    <!-- Back Button -->
                    <div class="flex justify-start p-2">
                        <button onclick="closeMobileMatchDetail()" class="text-[#7a8dbd] hover:text-white flex items-center gap-2">
                            <i class="fas fa-arrow-left text-lg"></i>
                            <span class="text-sm font-medium">Geri</span>
                        </button>
                    </div>
                    
                    <!-- Banner with image and text -->
                    <div class="relative">
                        <img alt="Match banner" class="w-full object-cover h-28" height="120" src="https://storage.googleapis.com/a1aa/image/3ab0efd3-cba0-45b6-6d75-3914ff165093.jpg" width="400"/>
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0a1220] via-transparent to-transparent"></div>
                        <div class="absolute bottom-2 left-3 text-white">
                            <div class="flex items-center gap-1 text-xs font-semibold text-[#7a8dbd]">
                                <i class="fas ${sportIcon} ${sportColor}"></i>
                                <span>${match.league}</span>
                            </div>
                            <div class="text-[10px] mt-0.5 text-[#7a8dbd]">
                                ${match.date}
                            </div>
                            <div class="text-base font-semibold mt-1">
                                ${match.home} vs ${match.away}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Navigation Tabs -->
                    <nav class="flex items-center gap-4 bg-[#0a1220] px-3 py-2 border-b border-[#1a2a4a] text-xs font-semibold text-[#7a8dbd]">
                        <button class="flex items-center gap-1 text-white border-b-2 border-white pb-1" onclick="switchMobileMarketTab('all')">
                            <i class="fas fa-star"></i>
                            Tümü
                        </button>
                        <button class="flex items-center gap-1 hover:text-white" onclick="switchMobileMarketTab('general')">
                            Genel
                            <sup class="ml-0.5 text-[8px] font-normal">2</sup>
                        </button>
                        <button class="flex items-center gap-1 hover:text-white" onclick="switchMobileMarketTab('players')">
                            Oyuncular
                            <sup class="ml-0.5 text-[8px] font-normal">0</sup>
                        </button>
                    </nav>
                    
                    <!-- Market Section -->
                    <div class="px-3 py-2 text-xs text-[#7a8dbd] font-semibold border-b border-[#1a2a4a]">
                        Marketler
                    </div>
                    
                    <!-- Basic Odds -->
                    <div class="px-3 py-2 text-sm text-white font-semibold flex justify-between items-center bg-[#1a2a4a] border-b border-[#2a3a5a]">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-trophy text-[#f59e0b] text-sm"></i>
                            Kazanan
                        </span>
                        <i class="fas fa-sync-alt text-[#3a7d3a] text-xs"></i>
                    </div>
                    <div class="p-2.5 grid grid-cols-3 gap-1.5">
                        <button class="mobile-odds-btn flex flex-col items-center justify-center bg-[#2a3a5a] hover:bg-[#3a4a6a] rounded-lg py-2 px-2 transition-all duration-200 border border-[#3a4a6a] hover:border-[#f59e0b]" 
                                data-bet-id="${match.eventId}_Kazanan_1" 
                                onclick="addMobileBet('1', ${match.oran1 || match.odds1}, '${match.eventId}', 'Kazanan', '${match.home}'); toggleMobileOddsSelection(this, '${match.eventId}_Kazanan_1')">
                            <span class="text-white text-[10px] font-medium mb-0.5 truncate w-full text-center">${match.home}</span>
                            <span class="text-[#f59e0b] font-bold text-xs">${(match.oran1 || match.odds1).toFixed(2)}</span>
                        </button>
                        <button class="mobile-odds-btn flex flex-col items-center justify-center bg-[#2a3a5a] hover:bg-[#3a4a6a] rounded-lg py-2 px-2 transition-all duration-200 border border-[#3a4a6a] hover:border-[#f59e0b]" 
                                data-bet-id="${match.eventId}_Kazanan_X" 
                                onclick="addMobileBet('X', ${match.oran0 || match.oddsX}, '${match.eventId}', 'Kazanan', 'Berabere'); toggleMobileOddsSelection(this, '${match.eventId}_Kazanan_X')">
                            <span class="text-white text-[10px] font-medium mb-0.5 truncate w-full text-center">Berabere</span>
                            <span class="text-[#f59e0b] font-bold text-xs">${(match.oran0 || match.oddsX).toFixed(2)}</span>
                        </button>
                        <button class="mobile-odds-btn flex flex-col items-center justify-center bg-[#2a3a5a] hover:bg-[#3a4a6a] rounded-lg py-2 px-2 transition-all duration-200 border border-[#3a4a6a] hover:border-[#f59e0b]" 
                                data-bet-id="${match.eventId}_Kazanan_2" 
                                onclick="addMobileBet('2', ${match.oran2 || match.odds2}, '${match.eventId}', 'Kazanan', '${match.away}'); toggleMobileOddsSelection(this, '${match.eventId}_Kazanan_2')">
                            <span class="text-white text-[10px] font-medium mb-0.5 truncate w-full text-center">${match.away}</span>
                            <span class="text-[#f59e0b] font-bold text-xs">${(match.oran2 || match.odds2).toFixed(2)}</span>
                        </button>
                    </div>
                </div>
            `;
            
            container.innerHTML = html;
        }
        
        function switchMobileMarketTab(tab) {
            // Update tab buttons
            document.querySelectorAll('#mobile-match-detail nav button').forEach(btn => {
                btn.classList.remove('text-white', 'border-b-2', 'border-white', 'pb-1');
                btn.classList.add('hover:text-white');
            });
            
            event.target.classList.remove('hover:text-white');
            event.target.classList.add('text-white', 'border-b-2', 'border-white', 'pb-1');
        }
        
        function goBackToLeagues() {
            const matchDetailContainer = document.getElementById('mobile-match-detail');
            const matchesContainer = document.getElementById('mobile-matches-list');
            const leaguesContainer = document.getElementById('mobile-leagues-list');
            const tabsContainer = document.getElementById('mobile-tabs');
            const backBtn = document.getElementById('mobile-back-btn');
            const searchContainer = document.querySelector('.px-3.py-2');
            const sportsContainer = document.getElementById('mobile-sports');
            
            if (matchDetailContainer.style.display !== 'none') {
                // Go back from match detail to matches list
                matchDetailContainer.style.display = 'none';
                matchesContainer.style.display = 'block';
                backBtn.style.display = 'flex';
                document.getElementById('mobile-back-text').textContent = document.getElementById('mobile-back-text').textContent.split(' vs ')[0] + ' vs ' + document.getElementById('mobile-back-text').textContent.split(' vs ')[1];
            } else {
                // Check if we're in live mode (back text contains "Canlı")
                const backText = document.getElementById('mobile-back-text').textContent;
                if (backText.includes('Canlı')) {
                    // Go back from live matches to live sports
                    matchesContainer.style.display = 'none';
                    leaguesContainer.style.display = 'none';
                    if (searchContainer) searchContainer.style.display = 'block';
                    if (sportsContainer) {
                        sportsContainer.style.display = 'block';
                        // Repopulate live sports
                        populateMobileLiveSportsInMain();
                    }
                    backBtn.style.display = 'none';
                } else {
                    // Go back from matches list to leagues (normal mode)
                    matchesContainer.style.display = 'none';
                    leaguesContainer.style.display = 'block';
                    if (searchContainer) searchContainer.style.display = 'block';
                    if (sportsContainer) {
                        sportsContainer.style.display = 'block';
                        // Reset sports container to horizontal layout with exact original classes
                        sportsContainer.className = 'flex space-x-2 overflow-x-auto scrollbar-thin no-scrollbar';
                        sportsContainer.setAttribute('style', 'scrollbar-width: thin; scrollbar-color: #4b5563 transparent');
                    }
                    backBtn.style.display = 'none';
                }
            }
        }
        
        function populateMobileSports() {
            const sportsContainer = document.getElementById('mobile-sports');
            if (!sportsContainer) return;
            
            // Use bulletin data for sports when in bulletin mode
            const dataToUse = currentMode === 'bulletin' ? (savedBulletinData || matchData || {}) : (matchData || {});
            
            // Check if we have data
            if (!dataToUse || Object.keys(dataToUse).length === 0) {
                return;
            }
            
            // Get unique sports from matchData
            const sports = [...new Set(Object.values(dataToUse).map(match => match.sport))];
            
            // Sport configuration with FontAwesome icons
            const sportConfig = {
                'futbol': {
                    icon: '<i class="fas fa-futbol text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                },
                'basketbol': {
                    icon: '<i class="fas fa-basketball-ball text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                },
                'tenis': {
                    icon: '<i class="fas fa-table-tennis text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                },
                'voleybol': {
                    icon: '<i class="fas fa-volleyball-ball text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                },
                'masatenisi': {
                    icon: '<i class="fas fa-table-tennis text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                },
                'beysbol': {
                    icon: '<i class="fas fa-baseball-ball text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                },
                'z_sports': {
                    icon: '<i class="fas fa-gamepad text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                },
                'darts': {
                    icon: '<i class="fas fa-bullseye text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                },
                'snooker': {
                    icon: '<i class="fas fa-circle text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                },
                'cricket': {
                    icon: '<i class="fas fa-circle text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                },
                'lacrosse': {
                    icon: '<i class="fas fa-hockey-puck text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                },
                'hentbol': {
                    icon: '<i class="fas fa-hand-holding text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                },
                'politics': {
                    icon: '<i class="fas fa-landmark text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                },
                'amerikan futbolu': {
                    icon: '<i class="fas fa-football-ball text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                },
                'golf': {
                    icon: '<i class="fas fa-golf-ball text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                },
                'virtual sports': {
                    icon: '<i class="fas fa-vr-cardboard text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                },
                'hokey': {
                    icon: '<i class="fas fa-hockey-puck text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                },
                'boks': {
                    icon: '<i class="fas fa-fist-raised text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                },
                'mma': {
                    icon: '<i class="fas fa-hand-rock text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                },
                'espor': {
                    icon: '<i class="fas fa-gamepad text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                }
            };
            
            let html = '<div class="flex flex-nowrap space-x-2 overflow-x-auto pb-2">';
            sports.forEach(sport => {
                // Get sport configuration based on sport name
                let config = {
                    icon: '⚽',
                    color: 'bg-gray-700',
                    textColor: 'text-white',
                    countColor: 'bg-gray-900 text-gray-400'
                };
                
                // Map sport names to configurations
                if (sport.toLowerCase().includes('futbol')) {
                    config = sportConfig['futbol'];
                } else if (sport.toLowerCase().includes('basketbol')) {
                    config = sportConfig['basketbol'];
                } else if (sport.toLowerCase().includes('tenis')) {
                    config = sportConfig['tenis'];
                } else if (sport.toLowerCase().includes('voleybol')) {
                    config = sportConfig['voleybol'];
                } else if (sport.toLowerCase().includes('masatenisi')) {
                    config = sportConfig['masatenisi'];
                } else if (sport.toLowerCase().includes('beysbol')) {
                    config = sportConfig['beysbol'];
                } else if (sport.toLowerCase().includes('z_sports')) {
                    config = sportConfig['z_sports'];
                } else if (sport.toLowerCase().includes('darts')) {
                    config = sportConfig['darts'];
                } else if (sport.toLowerCase().includes('snooker')) {
                    config = sportConfig['snooker'];
                } else if (sport.toLowerCase().includes('cricket')) {
                    config = sportConfig['cricket'];
                } else if (sport.toLowerCase().includes('lacrosse')) {
                    config = sportConfig['lacrosse'];
                } else if (sport.toLowerCase().includes('hentbol')) {
                    config = sportConfig['hentbol'];
                } else if (sport.toLowerCase().includes('politics')) {
                    config = sportConfig['politics'];
                } else if (sport.toLowerCase().includes('amerikan futbolu')) {
                    config = sportConfig['amerikan futbolu'];
                } else if (sport.toLowerCase().includes('golf')) {
                    config = sportConfig['golf'];
                } else if (sport.toLowerCase().includes('virtual sports')) {
                    config = sportConfig['virtual sports'];
                } else if (sport.toLowerCase().includes('hokey')) {
                    config = sportConfig['hokey'];
                } else if (sport.toLowerCase().includes('boks')) {
                    config = sportConfig['boks'];
                } else if (sport.toLowerCase().includes('mma')) {
                    config = sportConfig['mma'];
                } else if (sport.toLowerCase().includes('espor')) {
                    config = sportConfig['espor'];
                }
                
                const count = Object.values(matchData).filter(match => match.sport === sport).length;
                
                html += `
                    <button type="button" class="flex items-center space-x-2 ${config.color} rounded px-3 py-2 min-w-[80px] shrink-0" onclick="showMobileCountriesBySport('${sport}')">
                        <div class="text-white opacity-70">${config.icon}</div>
                        <span class="text-[11px] font-semibold ${config.textColor}">${sport.charAt(0).toUpperCase() + sport.slice(1)}</span>
                        <span class="${config.countColor} text-[10px] font-semibold rounded px-1.5 py-0.5">${count}</span>
                    </button>
                `;
            });
            
            html += '</div>';
            sportsContainer.innerHTML = html;
        }
        
        function showMobileCountriesBySport(sport) {
            // Check if list view is active
            if (isListViewActive) {
                // In list view, show matches directly without countries
                const filteredMatches = Object.values(matchData).filter(match => 
                    match.sport && match.sport.toLowerCase() === sport.toLowerCase()
                );
                displayMobileMatchesList(filteredMatches);
                return;
            }
            
            // Normal behavior - show countries for this sport
            const leaguesContainer = document.getElementById('mobile-leagues-list');
            
            // Clear existing content first
            leaguesContainer.innerHTML = '';
            
            // Get countries for this sport
            const countries = [...new Set(Object.values(matchData)
                .filter(match => match.sport === sport)
                .map(match => match.country || 'Diğer'))];
            
            let html = '';
            countries.forEach(country => {
                const countryMatches = Object.values(matchData).filter(match => 
                    match.sport === sport && (match.country || 'Diğer') === country
                );
                const count = countryMatches.length;
                
                // Get country flag using the same function as desktop
                let flagUrl = getCountryFlag(country);
                
                html += `
                    <div class="bg-[#192231] rounded mb-2">
                        <button type="button" class="w-full flex items-center justify-between px-3 py-2 text-gray-300" onclick="showMobileLeaguesByCountry('${country}', '${sport}')">
                            <div class="flex items-center space-x-2">
                                <img src="${flagUrl}" alt="${country} flag" class="w-4 h-4 rounded-sm" onerror="this.src='/images/flags/defaults.png'" />
                                <span class="text-sm font-medium">${country}</span>
                            </div>
                            <span class="text-xs text-gray-400">${count} maç</span>
                        </button>
                    </div>
                `;
            });
            
            leaguesContainer.innerHTML = html;
            leaguesContainer.style.display = 'block';
        }
        
        function showMobileLeaguesByCountry(country, sport) {
            const leaguesContainer = document.getElementById('mobile-leagues-list');
            
            // Find the country button that was clicked
            const countryButtons = leaguesContainer.querySelectorAll('button');
            let targetCountryButton = null;
            
            countryButtons.forEach(button => {
                const countryText = button.querySelector('span').textContent;
                if (countryText === country) {
                    targetCountryButton = button;
                }
            });
            
            // If we found the country button, insert leagues after it
            if (targetCountryButton) {
                const countryDiv = targetCountryButton.closest('div');
                
                // Check if leagues are already shown for this country
                const existingLeagues = countryDiv.nextElementSibling;
                if (existingLeagues && existingLeagues.classList.contains('leagues-for-' + country.replace(/\s+/g, '-'))) {
                    // Remove existing leagues
                    existingLeagues.remove();
                    return;
                }
                
                // Get leagues for this country and sport
                const leagues = [...new Set(Object.values(matchData)
                    .filter(match => 
                        match.sport === sport && 
                        (match.country || 'Diğer') === country
                    )
                    .map(match => match.league))];
                
                let html = '';
                leagues.forEach(league => {
                    const leagueMatches = Object.values(matchData).filter(match => 
                        match.sport === sport && 
                        (match.country || 'Diğer') === country &&
                        match.league === league
                    );
                    const count = leagueMatches.length;
                    
                    html += `
                        <div class="bg-[#1a2a4a] rounded mb-1 ml-4">
                            <button type="button" class="w-full flex items-center justify-between px-3 py-1.5 text-gray-300" onclick="showMobileMatchesByLeague('${league}', '${sport}')">
                                <div class="flex items-center space-x-2">
                                    <i class="far fa-star text-[#6b6b6b] text-xs"></i>
                                    <span class="text-sm font-medium">${league}</span>
                                </div>
                                <span class="text-xs text-gray-400">${count} maç</span>
                            </button>
                        </div>
                    `;
                });
                
                // Create leagues container and insert after country
                const leaguesDiv = document.createElement('div');
                leaguesDiv.className = 'leagues-for-' + country.replace(/\s+/g, '-');
                leaguesDiv.innerHTML = html;
                countryDiv.parentNode.insertBefore(leaguesDiv, countryDiv.nextSibling);
            }
        }
        
        function showMobileMatchesByLeague(league, sport) {
            const matchesContainer = document.getElementById('mobile-matches');
            const leaguesContainer = document.getElementById('mobile-leagues-list');
            
            // Hide leagues list and show matches
            if (leaguesContainer) leaguesContainer.style.display = 'none';
            
            // Get matches for this league and sport
            const filteredMatches = Object.values(matchData).filter(match => 
                match.league === league && match.sport === sport
            );
            
            // Show matches in the same container
            showMobileMatches(league);
        }
        
        function filterMobileBySport(sport) {
            currentSportFilter = sport;
            
            // Check which mode we're in
            if (currentMode === 'live') {
                // Filter live matches by sport
                const filteredMatches = liveMatches.filter(match => {
                    const matchSport = match.tur || 'Diğer';
                    return matchSport.toLowerCase() === sport.toLowerCase();
                });
                
                // Display filtered live matches
                populateMobileLiveMatches(filteredMatches);
            } else {
                // Bulletin mode
                if (isListViewActive) {
                    // In list view, show all matches for this sport directly
                    const filteredMatches = Object.values(matchData).filter(match => 
                        match.sport && match.sport.toLowerCase() === sport.toLowerCase()
                    );
                    displayMobileMatchesList(filteredMatches);
                } else {
                    // Normal behavior - show first league of this sport
            const filteredMatches = Object.values(matchData).filter(match => match.sport === sport);
            showMobileMatches(filteredMatches[0]?.league || '');
                }
            }
        }
        
        function searchMobileMatches(event) {
            const searchTerm = event.target.value.toLowerCase().trim();
            const resultsContainer = document.getElementById('mobile-search-results');
            
            // Hide dropdown if less than 3 characters
            if (searchTerm.length < 3) {
                resultsContainer.classList.add('hidden');
                return;
            }
            
            // Search in both regular matches and live matches
            let allMatches = [];
            
            // Add pre-match data
            if (matchData && Object.keys(matchData).length > 0) {
                allMatches = [...allMatches, ...Object.values(matchData)];
            }
            
            // Add live matches data
            if (liveMatches && liveMatches.length > 0) {
                allMatches = [...allMatches, ...liveMatches.map(match => ({
                    ...match,
                    home: match.evsahibi_isim || match.home,
                    away: match.misafir_isim || match.away,
                    league: match.lig || match.league,
                    eventId: match.mac_id || match.eventId,
                    isLive: true
                }))];
            }
            
            // Filter matches
            const filteredMatches = allMatches.filter(match => 
                (match.home && match.home.toLowerCase().includes(searchTerm)) ||
                (match.away && match.away.toLowerCase().includes(searchTerm)) ||
                (match.league && match.league.toLowerCase().includes(searchTerm))
            );
            
            // Show dropdown with results
            showSearchResults(filteredMatches, searchTerm);
        }
        
        function showSearchResults(matches, searchTerm) {
            const resultsContainer = document.getElementById('mobile-search-results');
            
            if (matches.length === 0) {
                resultsContainer.innerHTML = `
                    <div class="p-3 text-center text-gray-400 text-xs">
                        <i class="fas fa-search-minus mb-1"></i>
                        <p>"${searchTerm}" için sonuç bulunamadı</p>
                    </div>
                `;
                resultsContainer.classList.remove('hidden');
                return;
            }
            
            // Limit results to 10 for performance
            const limitedMatches = matches.slice(0, 10);
            
            let html = '';
            limitedMatches.forEach(match => {
                const isLive = match.isLive || false;
                const liveIndicator = isLive ? '<span class="text-red-500 text-xs animate-pulse">🔴 CANLI</span>' : '';
                const matchVs = `${match.home} vs ${match.away}`;
                
                html += `
                    <div class="p-2 hover:bg-[#2a3a4a] cursor-pointer border-b border-[#2a3a4a] last:border-b-0 transition-colors" onclick="selectSearchMatch('${match.eventId}', ${isLive})">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="text-white text-xs font-medium truncate">${matchVs}</div>
                                <div class="text-gray-400 text-xs mt-0.5 truncate">${match.league}</div>
                            </div>
                            <div class="ml-2 text-right">
                                ${liveIndicator}
                                <div class="text-gray-500 text-xs mt-0.5">
                                    <i class="fas fa-chevron-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            if (matches.length > 10) {
                html += `
                    <div class="p-2 text-center text-gray-400 text-xs border-t border-[#2a3a4a]">
                        +${matches.length - 10} maç daha...
                    </div>
                `;
            }
            
            resultsContainer.innerHTML = html;
            resultsContainer.classList.remove('hidden');
        }
        
        function selectSearchMatch(eventId, isLive = false) {
            // Hide search results
            const resultsContainer = document.getElementById('mobile-search-results');
            resultsContainer.classList.add('hidden');
            
            // Clear search input
            const searchInput = document.getElementById('mobile-search-input');
            searchInput.value = '';
            
            if (isLive) {
                // For live matches, show live odds detail
                showMobileLiveOdds(eventId);
            } else {
                // For pre-match, show match detail
                showMobileMatchDetail(eventId);
            }
        }
        
        function clearMobileSearch() {
            const searchInput = document.getElementById('mobile-search-input');
            const resultsContainer = document.getElementById('mobile-search-results');
            
            searchInput.value = '';
            resultsContainer.classList.add('hidden');
        }
        
        // Mobile List View Functions
        function toggleMobileListView() {
            isListViewActive = !isListViewActive;
            updateListViewUI();
            saveMobileListViewPreference();
            
            if (isListViewActive) {
                // Show all matches directly
                showAllMobileMatches();
            } else {
                // Show normal country/league view
                showMobileCountryView();
            }
        }
        
        function updateListViewUI() {
            const icon = document.getElementById('list-view-icon');
            const iconBg = document.getElementById('icon-bg');
            const statusDot = document.getElementById('status-dot');
            const toggleBtn = document.getElementById('list-toggle-btn');
            
            if (isListViewActive) {
                // Active state - Modern orange theme
                if (icon) {
                    icon.classList.remove('text-gray-400');
                    icon.classList.add('text-white');
                }
                
                if (iconBg) {
                    iconBg.classList.remove('bg-[#1a2a4a]');
                    iconBg.classList.add('bg-[#f7931e]');
                }
                
                if (statusDot) {
                    statusDot.classList.remove('bg-gray-500');
                    statusDot.classList.add('bg-[#f7931e]');
                }
                
                if (toggleBtn) {
                    toggleBtn.classList.remove('border-[#1a2a4a]');
                    toggleBtn.classList.add('border-[#f7931e]');
                }
            } else {
                // Inactive state - Default gray theme
                if (icon) {
                    icon.classList.remove('text-white');
                    icon.classList.add('text-gray-400');
                }
                
                if (iconBg) {
                    iconBg.classList.remove('bg-[#f7931e]');
                    iconBg.classList.add('bg-[#1a2a4a]');
                }
                
                if (statusDot) {
                    statusDot.classList.remove('bg-[#f7931e]');
                    statusDot.classList.add('bg-gray-500');
                }
                
                if (toggleBtn) {
                    toggleBtn.classList.remove('border-[#f7931e]');
                    toggleBtn.classList.add('border-[#1a2a4a]');
                }
            }
        }
        
        function saveMobileListViewPreference() {
            localStorage.setItem('mobileListViewActive', isListViewActive);
        }
        
        function loadMobileListViewPreference() {
            const saved = localStorage.getItem('mobileListViewActive');
            if (saved !== null) {
                isListViewActive = saved === 'true';
                updateListViewUI();
                
                // If list view is active, show all matches instead of leagues
                if (isListViewActive) {
                    const leaguesContainer = document.getElementById('mobile-leagues-list');
                    const matchesContainer = document.getElementById('mobile-matches-list');
                    
                    if (leaguesContainer) leaguesContainer.style.display = 'none';
                    if (matchesContainer) matchesContainer.style.display = 'block';
                    
                    // Show all matches in list view
                    showAllMobileMatches();
                }
            }
        }
        
        function showAllMobileMatches() {
            const leaguesContainer = document.getElementById('mobile-leagues-list');
            const matchesContainer = document.getElementById('mobile-matches-list');
            const backBtn = document.getElementById('mobile-back-btn');
            
            // Hide leagues, show matches
            if (leaguesContainer) leaguesContainer.style.display = 'none';
            if (matchesContainer) matchesContainer.style.display = 'block';
            if (backBtn) backBtn.style.display = 'none';
            
            // Use bulletin data when in bulletin mode
            const dataToUse = currentMode === 'bulletin' ? (savedBulletinData || matchData || {}) : (matchData || {});
            
            // Check if we have data
            if (!dataToUse || Object.keys(dataToUse).length === 0) {
                if (matchesContainer) {
                    matchesContainer.innerHTML = '<div class="text-center py-4 text-gray-500">Maç verisi yükleniyor...</div>';
                }
                return;
            }
            
            // Get all matches
            const allMatches = Object.values(dataToUse);
            
            // Group by sport and display
            displayMobileMatchesByCurrentSport(allMatches);
        }
        
        function showMobileCountryView() {
            const leaguesContainer = document.getElementById('mobile-leagues-list');
            const matchesContainer = document.getElementById('mobile-matches-list');
            const backBtn = document.getElementById('mobile-back-btn');
            
            // Show leagues, hide matches
            leaguesContainer.style.display = 'block';
            matchesContainer.style.display = 'none';
            backBtn.style.display = 'none';
            
            // Repopulate leagues
            populateMobileLeagues();
        }
        
        function displayMobileMatchesByCurrentSport(allMatches) {
            // Ensure we're in list view mode - hide leagues, show matches
            const leaguesContainer = document.getElementById('mobile-leagues-list');
            const matchesContainer = document.getElementById('mobile-matches-list');
            const backBtn = document.getElementById('mobile-back-btn');
            
            leaguesContainer.style.display = 'none';
            matchesContainer.style.display = 'block';
            backBtn.style.display = 'none';
            
            // Get currently active sport
            const activeSportBtn = document.querySelector('#mobile-sports button.bg-\\[\\#1a2a4a\\]');
            let activeSport = 'all';
            
            if (activeSportBtn) {
                activeSport = activeSportBtn.getAttribute('data-sport') || 'all';
            }
            
            // Filter matches by sport
            let filteredMatches = allMatches;
            if (activeSport !== 'all') {
                filteredMatches = allMatches.filter(match => 
                    match.sport && match.sport.toLowerCase() === activeSport.toLowerCase()
                );
            }
            
            // Display matches
            displayMobileMatchesList(filteredMatches);
        }
        
        function displayMobileMatchesList(matches) {
            const matchesContainer = document.getElementById('mobile-matches-list');
            
            if (matches.length === 0) {
                matchesContainer.innerHTML = `
                    <div class="px-4 py-8 text-center">
                        <div class="text-[#6a6a6a] text-sm">
                            <i class="fas fa-futbol text-2xl mb-2 block"></i>
                            <p>Bu kategoride maç bulunamadı</p>
                        </div>
                    </div>
                `;
                return;
            }
            
            let html = '';
            
            matches.forEach(match => {
                const liveIndicator = match.isLive ? '<span class="text-red-500 text-xs animate-pulse ml-2">🔴 CANLI</span>' : '';
                
                html += `
                    <section class="mb-3 border-b border-[#1a2a4a] pb-3 cursor-pointer bg-gradient-to-r from-[#0f1a2b] to-[#0a1220] rounded-lg p-2.5" onclick="showMobileMatchDetail('${match.eventId}')">
                        <div class="flex justify-between text-[#7a8dbd] text-[10px] mb-1.5">
                            <time datetime="${match.date}" class="truncate">${match.date.split(' ')[1] || match.date}</time>
                            <span class="opacity-60">⚽</span>
                        </div>
                        <div class="flex justify-between items-center mb-1">
                            <div class="text-xs text-[#b9b9b9] flex-1 truncate pr-1">${match.home}${liveIndicator}</div>
                            <div class="flex flex-col items-center">
                                <div class="text-[9px] text-[#7a8dbd] font-medium mb-0.5">Maç Sonucu</div>
                                <div class="text-[10px] text-[#7a8dbd]">VS</div>
                            </div>
                            <div class="text-xs text-[#b9b9b9] flex-1 truncate pl-1 text-right">${match.away}</div>
                        </div>
                        <div class="flex items-center gap-1 mb-2">
                            <button class="bg-[#1a2a4a] hover:bg-[#2a3a5a] rounded py-1.5 text-yellow-400 font-semibold transition-colors text-xs flex-1" type="button" onclick="event.stopPropagation(); addMobileBet('1', ${match.oran1 || match.odds1 || 2.5}, '${match.eventId}', 'Maç Sonucu', '${match.home}')">
                                ${(match.oran1 || match.odds1 || 2.5).toFixed(2)}
                            </button>
                            <button class="bg-[#1a2a4a] hover:bg-[#2a3a5a] rounded py-1.5 text-yellow-400 font-semibold transition-colors text-xs flex-1" type="button" onclick="event.stopPropagation(); addMobileBet('X', ${match.oran0 || match.oddsX || 3.2}, '${match.eventId}', 'Maç Sonucu', 'Berabere')">
                                ${(match.oran0 || match.oddsX || 3.2).toFixed(2)}
                            </button>
                            <button class="bg-[#1a2a4a] hover:bg-[#2a3a5a] rounded py-1.5 text-yellow-400 font-semibold transition-colors text-xs flex-1" type="button" onclick="event.stopPropagation(); addMobileBet('2', ${match.oran2 || match.odds2 || 3.5}, '${match.eventId}', 'Maç Sonucu', '${match.away}')">
                                ${(match.oran2 || match.odds2 || 3.5).toFixed(2)}
                            </button>
                        </div>
                        <div class="flex items-center justify-between text-[#7a8dbd] text-[10px] mt-2">
                            <div class="flex items-center space-x-1">
                                <img src="${getCountryFlag(match.country || 'Bilinmeyen')}" alt="${match.country} bayrağı" class="w-3 h-2 object-cover rounded-sm" onerror="this.src='/images/flags/defaults.png'" />
                                <span class="truncate">${decodeHtmlEntities(match.league || 'Bilinmeyen Lig')}</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <span class="text-[9px] bg-[#1a2a4a] px-1.5 py-0.5 rounded-full">+${Math.floor(Math.random() * 200) + 100}</span>
                                <button class="text-[#7a8dbd] hover:text-white" onclick="event.stopPropagation(); showMobileMatchDetail('${match.eventId}')">
                                    <i class="fas fa-chevron-right text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </section>
                `;
            });
            
            matchesContainer.innerHTML = html;
        }
        
        // Desktop open bets global variables
        let allDesktopBets = [];
        let filteredDesktopBets = [];
        let currentDesktopFilter = 'ongoing';
        let currentDesktopPage = 1;
        const desktopBetsPerPage = 5;
        
        // Mobile open bets global variables
        let allMobileBets = [];
        let filteredMobileBets = [];
        let currentMobileFilter = 'ongoing';
        let currentMobilePage = 1;
        const mobileBetsPerPage = 5;
        
        // Mobile list view variables
        let isListViewActive = false;
        
        // Page mode tracking
        let currentMode = 'bulletin'; // 'live' or 'bulletin' - track current page mode
        let currentSportFilter = null; // Track current sport filter
        let liveMatchData = {}; // Store live matches separately
        let bulletinMatchData = {}; // Store bulletin matches separately
        let savedBulletinData = null; // Save bulletin data when switching to live
        
        // Loading state functions for performance
        function showMobileLoadingState() {
            const sportsContainer = document.getElementById('mobile-sports');
            const leaguesContainer = document.getElementById('mobile-leagues-list');
            
            if (sportsContainer) {
                sportsContainer.innerHTML = '<div class="flex items-center justify-center py-4"><i class="fas fa-spinner fa-spin text-gray-400"></i><span class="ml-2 text-gray-400 text-xs">Yükleniyor...</span></div>';
            }
            if (leaguesContainer) {
                leaguesContainer.innerHTML = '<div class="flex items-center justify-center py-8"><i class="fas fa-spinner fa-spin text-gray-400"></i><span class="ml-2 text-gray-400">Maçlar yükleniyor...</span></div>';
            }
        }
        
        function hideMobileLoadingState() {
            // Loading states will be replaced by actual content
        }
        
        // Desktop tab switching
        function switchDesktopTab(tab, clickedButton) {
            console.log('Desktop tab switching to:', tab);
            const couponContent = document.getElementById('desktop-coupon-content');
            const openContent = document.getElementById('desktop-open-content');
            const tabs = document.querySelectorAll('.desktop-slip-tab');
            
            // Update tab styles
            tabs.forEach(tabBtn => {
                tabBtn.classList.remove('bg-[#f59e0b]', 'text-black', 'active');
                tabBtn.classList.add('bg-[#3a3a3a]', 'text-gray-300');
            });
            
            clickedButton.classList.remove('bg-[#3a3a3a]', 'text-gray-300');
            clickedButton.classList.add('bg-[#f59e0b]', 'text-black', 'active');
            
            if (tab === 'coupon') {
                couponContent.classList.remove('hidden');
                openContent.classList.add('hidden');
            } else if (tab === 'open') {
                couponContent.classList.add('hidden');
                openContent.classList.remove('hidden');
                
                // Load open bets when switching to open tab
                loadDesktopOpenBets();
            }
        }
        
        // Load desktop open bets
        async function loadDesktopOpenBets() {
            console.log('loadDesktopOpenBets function called');
            try {
                const openContainer = document.getElementById('desktop-open-bets-container');
                
                // Show loading state
                openContainer.innerHTML = `
                    <div class="text-center text-xs font-semibold py-8 text-[#6b6b6b]">
                        <i class="fas fa-spinner fa-spin text-2xl mb-2 block"></i>
                        Açık bahisler yükleniyor...
                    </div>
                `;
                
                // Prepare headers - same as mobile version
                const headers = {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Agent-Code': '{{ session("api_agent_code") }}',
                    'API-Secret-Key': '{{ session("api_secret_key") }}',
                    'API-Token': '{{ session("api_token") }}'
                };
                
                // Add secure token if available
                const secureToken = sessionStorage.getItem('secure_token') || localStorage.getItem('secure_token');
                const metaSecureToken = document.querySelector('meta[name="secure-token"]')?.getAttribute('content');
                const finalSecureToken = secureToken || metaSecureToken;
                if (finalSecureToken) {
                    headers['X-Secure-Token'] = finalSecureToken;
                }
                
                // Debug: Log headers being sent
                console.log('Desktop API Headers:', headers);
                console.log('Desktop Session Data:', {
                    'api_agent_code': '{{ session("api_agent_code") }}',
                    'api_secret_key': '{{ session("api_secret_key") }}',
                    'api_token': '{{ session("api_token") }}',
                    'username': '{{ session("username") }}',
                    'user_id': '{{ session("user_id") }}',
                    'callback_username': '{{ session("callback_username") }}',
                    'callback_user_id': '{{ session("callback_user_id") }}'
                });
                
                // Fetch open bets from API
                const response = await fetch('/api/open-bets', {
                    method: 'GET',
                    headers: headers
                });
                
                if (response.ok) {
                    const data = await response.json();
                    
                    // Debug: Log response data
                    console.log('Desktop API Response:', data);
                    
                    // Debug: Log each bet's selections
                    if (data.bets && data.bets.length > 0) {
                        data.bets.forEach((bet, betIndex) => {
                            console.log(`Bet ${betIndex + 1} (ID: ${bet.bet_id}) selections:`, bet.selections);
                            bet.selections.forEach((selection, selIndex) => {
                                console.log(`  Selection ${selIndex + 1}:`, {
                                    selection: selection.selection,
                                    durum: selection.durum,
                                    durum_text: selection.durum_text,
                                    match_info: selection.match_info,
                                    full_selection: selection
                                });
                            });
                        });
                    }
                    
                    if (data.success && data.bets && data.bets.length > 0) {
                        // Store all bets globally
                        allDesktopBets = data.bets;
                        
                        // Reset filter and pagination
                        currentDesktopFilter = 'ongoing';
                        currentDesktopPage = 1;
                        
                        // Apply initial filter (show ongoing)
                        applyDesktopFilter();
                    } else {
                        // No open bets
                        allDesktopBets = [];
                        filteredDesktopBets = [];
                        openContainer.innerHTML = `
                            <div class="text-center text-xs font-semibold py-8 text-[#6b6b6b]">
                                <i class="fas fa-inbox text-2xl mb-2 block"></i>
                                Açık bahis bulunamadı
                                <div class="text-[10px] mt-1">Henüz aktif bahsiniz yok</div>
                            </div>
                        `;
                        document.getElementById('desktop-pagination').classList.add('hidden');
                    }
                } else {
                    throw new Error('Bu Karsilasmaya Bahis Alınamıyor');
                }
            } catch (error) {
                const openContainer = document.getElementById('desktop-open-bets-container');
                openContainer.innerHTML = `
                    <div class="text-center text-xs font-semibold py-8 text-[#6b6b6b]">
                        <i class="fas fa-exclamation-triangle text-2xl mb-2 block text-red-500"></i>
                        Açık bahisler yüklenemedi
                        <div class="text-[10px] mt-1">Lütfen daha sonra tekrar deneyin</div>
                    </div>
                `;
            }
        }
        
        // Fetch match scores from API
        async function fetchMatchScores(betradarIds) {
            try {
                const response = await fetch('/api/match-scores', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        betradar_ids: betradarIds
                    })
                });
                
                if (response.ok) {
                    const data = await response.json();
                    return data.success ? data.match_scores : {};
                }
            } catch (error) {
                console.error('Error fetching match scores:', error);
            }
            return {};
        }

        // Display desktop open bets
        function displayDesktopOpenBets(bets, startIndex = 0) {
            const container = document.getElementById('desktop-open-bets-container');
            
            let html = '<div class="px-3 py-2">';
            
            bets.forEach((bet, index) => {
                const globalIndex = startIndex + index;
                const statusColor = bet.status === 'ongoing' ? 'text-yellow-500' : 
                                   bet.status === 'won' ? 'text-green-500' : 'text-red-500';
                const statusText = bet.status === 'ongoing' ? 'Devam Ediyor' : 
                                  bet.status === 'won' ? 'Kazandı' : 'Kaybetti';
                
                html += `
                    <div class="bg-[#3a3a3a] rounded-md mb-3 overflow-hidden">
                        <div class="p-3 cursor-pointer" onclick="toggleDesktopBetDetails(${globalIndex})">
                            <div class="flex justify-between items-center mb-2">
                                <div class="text-xs font-semibold text-white">
                                    Kupon #${bet.bet_id}
                                </div>
                                <div class="text-xs ${statusColor} font-semibold">
                                    ${statusText}
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center mb-2">
                                <div class="text-xs text-gray-300">
                                    ${bet.created_at}
                                </div>
                                <div class="text-xs text-gray-300">
                                    ${bet.selections.length} Maç
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <div class="text-xs">
                                    <span class="text-gray-400">Bahis:</span>
                                    <span class="text-white font-semibold">${bet.amount} ₺</span>
                                </div>
                                <div class="text-xs">
                                    <span class="text-gray-400">Kazanç:</span>
                                    <span class="text-green-500 font-semibold">${bet.potential_win} ₺</span>
                                </div>
                            </div>
                            
                            <div class="text-center mt-2">
                                <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform" id="desktop-chevron-${globalIndex}"></i>
                            </div>
                        </div>
                        
                        <div class="hidden border-t border-[#2a2a2a] p-3" id="desktop-bet-details-${globalIndex}">
                            <div class="space-y-2" id="desktop-bet-selections-${globalIndex}">
                                ${bet.selections.map(selection => `
                                    <div class="bg-[#2a2a2a] rounded p-2">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-white text-xs font-medium">${selection.selection}</span>
                                            <span class="text-[#f59e0b] text-xs">${selection.odds}</span>
                                        </div>
                                        <div class="flex justify-between items-center mt-1">
                                            <div class="flex items-center gap-2">
                                                <span class="text-gray-400 text-xs">${selection.match_info}</span>
                                                ${selection.canli ? `
                                                    <span class="text-green-500 text-xs animate-pulse">
                                                        <i class="fas fa-circle text-xs"></i> Canlı
                                                    </span>
                                                ` : ''}
                                            </div>
                                            <span class="text-xs ${
                                                parseInt(selection.durum) === 0 ? 'text-yellow-500' : // Bekliyor
                                                parseInt(selection.durum) === 1 ? 'text-green-500' : // Kazandı  
                                                parseInt(selection.durum) === 2 ? 'text-red-500' : // Kaybetti
                                                parseInt(selection.durum) === 3 ? 'text-gray-500' : // İptal
                                                'text-yellow-500' // Default
                                            }">
                                                <i class="fas ${
                                                    parseInt(selection.durum) === 0 ? 'fa-clock' : // Bekliyor
                                                    parseInt(selection.durum) === 1 ? 'fa-check-circle' : // Kazandı
                                                    parseInt(selection.durum) === 2 ? 'fa-times-circle' : // Kaybetti
                                                    parseInt(selection.durum) === 3 ? 'fa-ban' : // İptal
                                                    'fa-clock' // Default
                                                } text-xs"></i> ${
                                                    parseInt(selection.durum) === 0 ? 'Bekliyor' :
                                                    parseInt(selection.durum) === 1 ? 'Kazandı' :
                                                    parseInt(selection.durum) === 2 ? 'Kaybetti' :
                                                    parseInt(selection.durum) === 3 ? 'İptal Edildi' :
                                                    'Bekliyor'
                                                }
                                            </span>
                                        </div>
                                        ${selection.betradar_id ? `
                                            <div class="mt-2 pt-2 border-t border-[#3a3a3a]" id="desktop-score-${globalIndex}-${selection.betradar_id}">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-gray-400 text-xs">Skor:</span>
                                                    <span class="text-white text-xs font-medium">
                                                        <i class="fas fa-spinner fa-spin text-xs"></i> Yükleniyor...
                                                    </span>
                                                </div>
                                            </div>
                                        ` : ''}
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            container.innerHTML = html;
        }
        
        // Toggle desktop bet details
        async function toggleDesktopBetDetails(index) {
            const details = document.getElementById(`desktop-bet-details-${index}`);
            const chevron = document.getElementById(`desktop-chevron-${index}`);
            
            if (details.classList.contains('hidden')) {
                details.classList.remove('hidden');
                chevron.style.transform = 'rotate(180deg)';
                
                // Load scores for this bet when opened
                await loadDesktopBetScores(index);
            } else {
                details.classList.add('hidden');
                chevron.style.transform = 'rotate(0deg)';
            }
        }
        
        // Load scores for a specific bet
        async function loadDesktopBetScores(betIndex) {
            const bet = allDesktopBets[betIndex];
            if (!bet || !bet.selections) return;
            
            // Collect betradar_ids for this bet only
            const betradarIds = bet.selections
                .filter(selection => selection.betradar_id)
                .map(selection => selection.betradar_id);
            
            if (betradarIds.length === 0) return;
            
            try {
                const matchScores = await fetchMatchScores(betradarIds);
                
                // Update each selection's score
                bet.selections.forEach(selection => {
                    if (selection.betradar_id) {
                        const scoreElement = document.getElementById(`desktop-score-${betIndex}-${selection.betradar_id}`);
                        if (scoreElement) {
                            const matchScore = matchScores[selection.betradar_id] || {};
                            const scoreInfo = matchScore.first_half_score || matchScore.full_time_score ? 
                                `${matchScore.first_half_score ? `İY: ${matchScore.first_half_score}` : ''}${matchScore.first_half_score && matchScore.full_time_score ? ' | ' : ''}${matchScore.full_time_score ? `MS: ${matchScore.full_time_score}` : ''}` : 
                                'Skor yok';
                            
                            scoreElement.innerHTML = `
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-400 text-xs">Skor:</span>
                                    <span class="text-white text-xs font-medium">${scoreInfo}</span>
                                </div>
                                ${matchScore.status ? `
                                    <div class="flex justify-between items-center mt-1">
                                        <span class="text-gray-400 text-xs">Durum:</span>
                                        <span class="text-xs ${matchScore.is_live ? 'text-green-500' : matchScore.is_finished ? 'text-blue-500' : 'text-yellow-500'}">
                                            ${matchScore.is_live ? 'Canlı' : matchScore.is_finished ? 'Bitti' : 'Beklemede'}
                                        </span>
                                    </div>
                                ` : ''}
                            `;
                        }
                    }
                });
            } catch (error) {
                console.error('Error loading bet scores:', error);
            }
        }
        
        // Filter desktop bets by status
        function filterDesktopBets(status, clickedButton) {
            // Update filter buttons
            document.querySelectorAll('.desktop-filter-btn').forEach(btn => {
                btn.classList.remove('bg-[#f59e0b]', 'text-black', 'font-semibold', 'active');
                btn.classList.add('bg-[#3a3a3a]', 'text-gray-300');
            });
            
            clickedButton.classList.remove('bg-[#3a3a3a]', 'text-gray-300');
            clickedButton.classList.add('bg-[#f59e0b]', 'text-black', 'font-semibold', 'active');
            
            // Update current filter and reset page
            currentDesktopFilter = status;
            currentDesktopPage = 1;
            
            // Apply filter
            applyDesktopFilter();
        }
        
        // Apply current filter to bets
        function applyDesktopFilter() {
            filteredDesktopBets = allDesktopBets.filter(bet => bet.status === currentDesktopFilter);
            
            // Update display
            updateDesktopPagination();
            displayDesktopCurrentPage();
        }
        
        // Update pagination controls
        function updateDesktopPagination() {
            const pagination = document.getElementById('desktop-pagination');
            const prevBtn = document.getElementById('desktop-prev-btn');
            const nextBtn = document.getElementById('desktop-next-btn');
            const pageInfo = document.getElementById('desktop-page-info');
            const totalInfo = document.getElementById('desktop-total-info');
            
            const totalPages = Math.ceil(filteredDesktopBets.length / desktopBetsPerPage);
            const totalBets = filteredDesktopBets.length;
            
            if (totalBets > 0) {
                pagination.classList.remove('hidden');
                
                // Update page info
                pageInfo.textContent = `${currentDesktopPage} / ${totalPages}`;
                totalInfo.textContent = `${totalBets} kupon`;
                
                // Update button states
                prevBtn.disabled = currentDesktopPage <= 1;
                nextBtn.disabled = currentDesktopPage >= totalPages;
            } else {
                pagination.classList.add('hidden');
            }
        }
        
        // Change page
        function changeDesktopPage(direction) {
            const totalPages = Math.ceil(filteredDesktopBets.length / desktopBetsPerPage);
            
            currentDesktopPage += direction;
            
            // Ensure page is within bounds
            if (currentDesktopPage < 1) currentDesktopPage = 1;
            if (currentDesktopPage > totalPages) currentDesktopPage = totalPages;
            
            // Update display
            updateDesktopPagination();
            displayDesktopCurrentPage();
        }
        
        // Display current page of bets
        function displayDesktopCurrentPage() {
            const startIndex = (currentDesktopPage - 1) * desktopBetsPerPage;
            const endIndex = startIndex + desktopBetsPerPage;
            const currentPageBets = filteredDesktopBets.slice(startIndex, endIndex);
            
            if (currentPageBets.length > 0) {
                displayDesktopOpenBets(currentPageBets, startIndex);
            } else {
                const container = document.getElementById('desktop-open-bets-container');
                const filterText = currentDesktopFilter === 'ongoing' ? 'beklemede olan kupon' :
                                 currentDesktopFilter === 'won' ? 'kazanan kupon' : 'kaybeden kupon';
                
                container.innerHTML = `
                    <div class="text-center text-xs font-semibold py-8 text-[#6b6b6b]">
                        <i class="fas fa-filter text-2xl mb-2 block"></i>
                        ${filterText} bulunamadı
                        <div class="text-[10px] mt-1">Başka bir filtre deneyin</div>
                    </div>
                `;
            }
        }
        
        // Mobile filtering and pagination functions
        function filterMobileBets(status, clickedButton) {
            // Update filter buttons
            document.querySelectorAll('.mobile-filter-btn').forEach(btn => {
                btn.classList.remove('bg-[#f7931e]', 'text-black', 'font-semibold', 'active');
                btn.classList.add('bg-[#3a3a3a]', 'text-gray-300');
            });
            
            clickedButton.classList.remove('bg-[#3a3a3a]', 'text-gray-300');
            clickedButton.classList.add('bg-[#f7931e]', 'text-black', 'font-semibold', 'active');
            
            // Update current filter and reset page
            currentMobileFilter = status;
            currentMobilePage = 1;
            
            // Apply filter
            applyMobileFilter();
        }
        
        function applyMobileFilter() {
            filteredMobileBets = allMobileBets.filter(bet => bet.status === currentMobileFilter);
            
            // Update display
            updateMobilePagination();
            displayMobileCurrentPage();
        }
        
        function updateMobilePagination() {
            const pagination = document.getElementById('mobile-pagination');
            const prevBtn = document.getElementById('mobile-prev-btn');
            const nextBtn = document.getElementById('mobile-next-btn');
            const pageInfo = document.getElementById('mobile-page-info');
            const totalInfo = document.getElementById('mobile-total-info');
            
            const totalPages = Math.ceil(filteredMobileBets.length / mobileBetsPerPage);
            const totalBets = filteredMobileBets.length;
            
            if (totalBets > 0) {
                pagination.classList.remove('hidden');
                
                // Update page info
                pageInfo.textContent = `${currentMobilePage} / ${totalPages}`;
                totalInfo.textContent = `${totalBets} kupon`;
                
                // Update button states
                prevBtn.disabled = currentMobilePage <= 1;
                nextBtn.disabled = currentMobilePage >= totalPages;
            } else {
                pagination.classList.add('hidden');
            }
        }
        
        function changeMobilePage(direction) {
            const totalPages = Math.ceil(filteredMobileBets.length / mobileBetsPerPage);
            
            currentMobilePage += direction;
            
            // Ensure page is within bounds
            if (currentMobilePage < 1) currentMobilePage = 1;
            if (currentMobilePage > totalPages) currentMobilePage = totalPages;
            
            // Update display
            updateMobilePagination();
            displayMobileCurrentPage();
        }
        
        function displayMobileCurrentPage() {
            const startIndex = (currentMobilePage - 1) * mobileBetsPerPage;
            const endIndex = startIndex + mobileBetsPerPage;
            const currentPageBets = filteredMobileBets.slice(startIndex, endIndex);
            
            const container = document.getElementById('mobile-open-bets-container');
            
            if (currentPageBets.length > 0) {
                displayMobileOpenBets(currentPageBets);
            } else {
                const filterText = currentMobileFilter === 'ongoing' ? 'beklemede olan kupon' :
                                 currentMobileFilter === 'won' ? 'kazanan kupon' : 'kaybeden kupon';
                
                container.innerHTML = `
                    <div class="px-3 py-8 text-center">
                        <div class="text-[#6a6a6a] text-sm">
                            <i class="fas fa-filter text-2xl mb-2 block"></i>
                            <p>${filterText} bulunamadı</p>
                            <p class="text-xs mt-1">Başka bir filtre deneyin</p>
                        </div>
                    </div>
                `;
            }
        }
        
        function displayMobileOpenBets(bets) {
            const container = document.getElementById('mobile-open-bets-container');
            
            let html = '';
            
            bets.forEach((bet, index) => {
                const statusColor = bet.status === 'ongoing' ? 'text-[#f7b600]' : 
                                   bet.status === 'won' ? 'text-[#3ecf4e]' : 'text-[#ef4444]';
                const statusText = bet.status === 'ongoing' ? 'Devam Ediyor' : 
                                  bet.status === 'won' ? 'Kazandı' : 'Kaybetti';
                
                html += `
                    <div class="bg-[#3a3a3a] rounded-lg mx-3 my-2 overflow-hidden">
                        <div class="p-3 cursor-pointer" onclick="toggleMobileBetDetails(${index})">
                            <!-- Kupon Başlığı ve Durum -->
                            <div class="flex justify-between items-center mb-2">
                                <div class="text-white text-sm font-semibold">
                                    Kupon #${bet.bet_id}
                                </div>
                                <div class="text-xs ${statusColor} font-semibold">
                                    ${statusText}
                                </div>
                            </div>
                            
                            <!-- Tarih ve Maç Sayısı -->
                            <div class="flex justify-between items-center mb-2">
                                <div class="text-[#b0b0b0] text-xs">
                                    ${bet.created_at}
                                </div>
                                <div class="text-[#b0b0b0] text-xs">
                                    ${bet.selections.length} Maç
                                </div>
                            </div>
                            
                            <!-- Bahis Miktarı ve Kazanç -->
                            <div class="flex justify-between items-center">
                                <div class="text-right">
                                    <div class="text-[#f7b600] font-semibold text-sm">${bet.amount} ₺</div>
                                    <div class="text-[#b0b0b0] text-xs">${bet.total_odds}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-[#3ecf4e] font-semibold text-sm">${bet.potential_win} ₺</div>
                                </div>
                            </div>
                            
                            <!-- Açılır/Kapanır İkon -->
                            <div class="text-center mt-2">
                                <i class="fas fa-chevron-down text-[#6a6a6a] text-xs transition-transform" id="mobile-chevron-${index}"></i>
                            </div>
                        </div>
                        
                        <!-- Kupon Detayları - Başlangıçta Gizli -->
                        <div class="hidden px-3 pb-3" id="mobile-bet-details-${index}">
                            <div class="space-y-2" id="mobile-bet-selections-${index}">
                                ${bet.selections.map(selection => `
                                    <div class="bg-[#2a2a2a] rounded p-2">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-white text-xs font-medium">${selection.selection}</span>
                                            <span class="text-[#f7b600] text-xs">${selection.odds}</span>
                                        </div>
                                        <div class="flex justify-between items-center mt-1">
                                            <div class="flex items-center gap-2">
                                                <span class="text-[#6a6a6a] text-xs">${selection.match_info}</span>
                                                ${selection.canli ? `
                                                    <span class="text-[#10b981] text-xs animate-pulse">
                                                        <i class="fas fa-circle text-xs"></i> Canlı
                                                    </span>
                                                ` : ''}
                                            </div>
                                            <span class="text-xs ${
                                                parseInt(selection.durum) === 0 ? 'text-[#f7b600]' : // Bekliyor - Sarı
                                                parseInt(selection.durum) === 1 ? 'text-[#10b981]' : // Kazandı - Yeşil  
                                                parseInt(selection.durum) === 2 ? 'text-[#ef4444]' : // Kaybetti - Kırmızı
                                                parseInt(selection.durum) === 3 ? 'text-[#6b7280]' : // İptal - Gri
                                                'text-[#f7b600]' // Default - Sarı
                                            }">
                                                <i class="fas ${
                                                    parseInt(selection.durum) === 0 ? 'fa-clock' : // Bekliyor
                                                    parseInt(selection.durum) === 1 ? 'fa-check-circle' : // Kazandı
                                                    parseInt(selection.durum) === 2 ? 'fa-times-circle' : // Kaybetti
                                                    parseInt(selection.durum) === 3 ? 'fa-ban' : // İptal
                                                    'fa-clock' // Default
                                                } text-xs"></i> ${
                                                    parseInt(selection.durum) === 0 ? 'Bekliyor' :
                                                    parseInt(selection.durum) === 1 ? 'Kazandı' :
                                                    parseInt(selection.durum) === 2 ? 'Kaybetti' :
                                                    parseInt(selection.durum) === 3 ? 'İptal Edildi' :
                                                    'Bekliyor'
                                                }
                                            </span>
                                        </div>
                                        ${selection.betradar_id ? `
                                            <div class="mt-2 pt-2 border-t border-[#4a4a4a]" id="mobile-score-${index}-${selection.betradar_id}">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-[#8a8a8a] text-xs">Skor:</span>
                                                    <span class="text-white text-xs font-medium">
                                                        <i class="fas fa-spinner fa-spin text-xs"></i> Yükleniyor...
                                                    </span>
                                                </div>
                                            </div>
                                        ` : ''}
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }
        
        async function toggleMobileBetDetails(index) {
            const details = document.getElementById(`mobile-bet-details-${index}`);
            const chevron = document.getElementById(`mobile-chevron-${index}`);
            
            if (details.classList.contains('hidden')) {
                details.classList.remove('hidden');
                chevron.style.transform = 'rotate(180deg)';
                
                // Load scores for this bet when opened
                await loadMobileBetScores(index);
            } else {
                details.classList.add('hidden');
                chevron.style.transform = 'rotate(0deg)';
            }
        }
        
        // Load scores for a specific mobile bet
        async function loadMobileBetScores(betIndex) {
            const bet = filteredMobileBets[betIndex];
            if (!bet || !bet.selections) return;
            
            // Collect betradar_ids for this bet only
            const betradarIds = bet.selections
                .filter(selection => selection.betradar_id)
                .map(selection => selection.betradar_id);
            
            if (betradarIds.length === 0) return;
            
            try {
                const matchScores = await fetchMatchScores(betradarIds);
                
                // Update each selection's score
                bet.selections.forEach(selection => {
                    if (selection.betradar_id) {
                        const scoreElement = document.getElementById(`mobile-score-${betIndex}-${selection.betradar_id}`);
                        if (scoreElement) {
                            const matchScore = matchScores[selection.betradar_id] || {};
                            const scoreInfo = matchScore.first_half_score || matchScore.full_time_score ? 
                                `${matchScore.first_half_score ? `İY: ${matchScore.first_half_score}` : ''}${matchScore.first_half_score && matchScore.full_time_score ? ' | ' : ''}${matchScore.full_time_score ? `MS: ${matchScore.full_time_score}` : ''}` : 
                                'Skor yok';
                            
                            scoreElement.innerHTML = `
                                <div class="flex justify-between items-center">
                                    <span class="text-[#8a8a8a] text-xs">Skor:</span>
                                    <span class="text-white text-xs font-medium">${scoreInfo}</span>
                                </div>
                                ${matchScore.status ? `
                                    <div class="flex justify-between items-center mt-1">
                                        <span class="text-[#8a8a8a] text-xs">Durum:</span>
                                        <span class="text-xs ${matchScore.is_live ? 'text-[#10b981]' : matchScore.is_finished ? 'text-[#3b82f6]' : 'text-[#f7b600]'}">
                                            ${matchScore.is_live ? 'Canlı' : matchScore.is_finished ? 'Bitti' : 'Beklemede'}
                                        </span>
                                    </div>
                                ` : ''}
                            `;
                        }
                    }
                });
            } catch (error) {
                console.error('Error loading mobile bet scores:', error);
            }
        }
        
        async function addMobileBet(betType, odds, eventId, market = null, selection = null) {
            const match = matchData[eventId];
            if (!match) return;
            
            // If market and selection are provided, use them directly
            if (market && selection) {
                // Use provided market and selection
            } else {
                // Default market determination
                if (betType === '1') {
                    market = 'Maç Sonucu';
                    selection = match.home;
                } else if (betType === '2') {
                    market = 'Maç Sonucu';
                    selection = match.away;
                } else if (betType === 'X') {
                    market = 'Maç Sonucu';
                    selection = 'Berabere';
                } else if (betType === '1H1') {
                    market = 'İlk Yarı Sonucu';
                    selection = `${match.home} (İlk Yarı)`;
                } else if (betType === '1H2') {
                    market = 'İlk Yarı Sonucu';
                    selection = `${match.away} (İlk Yarı)`;
                } else if (betType === '1HX') {
                    market = 'İlk Yarı Sonucu';
                    selection = 'Berabere (İlk Yarı)';
                } else {
                    // For other bet types, try to determine market from betType
                    if (betType.includes('Over') || betType.includes('Under')) {
                        market = 'Toplam Gol';
                        selection = betType;
                    } else if (betType.includes('Both') || betType.includes('Yes') || betType.includes('No')) {
                        market = 'Karşılıklı Gol';
                        selection = betType;
                    } else if (betType.includes('Handicap') || betType.includes('+') || betType.includes('-')) {
                        market = 'Handikap';
                        selection = betType;
                    } else {
                        market = 'Diğer';
                        selection = betType;
                    }
                }
            }
            
            const bet = {
                id: `${eventId}-${betType}`,
                match: `${match.home} vs ${match.away}`,
                market: market,
                selection: selection,
                odds: odds,
                eventId: eventId,
                matchInfo: {
                    home: match.home,
                    away: match.away,
                    league: match.league,
                    date: match.date
                }
            };
            
            selectedBets = selectedBets.filter(b => b.eventId !== eventId);
            selectedBets.push(bet);
            
            // Save to localStorage
            saveBettingSlipToStorage();
            
            updateMobileSlip();
            
            // Update coupon count immediately (INSTANT)
            updateMobileCouponCount();
            
            // Open mobile betting slip after adding bet
            showMobileSlip();
            
            // Update other footer counts asynchronously
            setTimeout(() => {
                updateMobileFooterCounts();
            }, 50);
        }
        
        function updateMobileSlip() {
            const slipContainer = document.getElementById('mobile-slip-bets');
            const placeBetBtn = document.getElementById('mobile-place-bet');
            
            if (selectedBets.length === 0) {
                // Check if user is logged in
                const username = '{{ session("username") }}';
                const isLoggedIn = '{{ session("is_logged_in") }}';
                
                if (isLoggedIn && username) {
                    // User is logged in, show username instead of "Kuponunuz boş"
                    slipContainer.innerHTML = `
                        <div class="px-3 py-8 text-center">
                            <div class="text-[#6a6a6a] text-sm">
                                <i class="fas fa-user text-2xl mb-2 block text-[#f7931e]"></i>
                                <p class="text-white font-semibold">${username}</p>
                                <p class="text-xs mt-1 text-[#b0b0b0]">Bahis yapmak için maç seçin</p>
                            </div>
                        </div>
                    `;
                } else {
                    // User not logged in, show "Kuponunuz boş"
                    slipContainer.innerHTML = `
                        <div class="px-3 py-8 text-center">
                            <div class="text-[#6a6a6a] text-sm">
                                <i class="fas fa-ticket-alt text-2xl mb-2 block"></i>
                                <p>Kuponunuz boş</p>
                                <p class="text-xs mt-1">Bahis yapmak için maç seçin</p>
                            </div>
                        </div>
                    `;
                }
                
                placeBetBtn.disabled = true;
                placeBetBtn.className = 'w-full bg-[#2a2a2a] text-gray-600 text-sm font-semibold py-4 rounded-lg cursor-not-allowed transition-all duration-300';
                return;
            }
            
            let html = '';
            
            selectedBets.forEach(bet => {
                html += `
                    <div class="px-3 py-2 relative" data-bet-id="${bet.id}">
                        <button aria-label="Remove" class="absolute right-3 top-3 text-[#6a6a6a] hover:text-white text-xs font-bold leading-none" onclick="removeMobileBet('${bet.id}')">×</button>
                        <p class="font-semibold text-white">${bet.selection} <span class="text-[#f7b600] float-right">${bet.odds.toFixed(2)}</span></p>
                        <p class="text-[#b0b0b0]">${bet.market}</p>
                        <p class="text-[#6a6a6a] mt-0.5">${bet.matchInfo.home} vs ${bet.matchInfo.away}</p>
                        <p class="text-[#4a4a4a] mt-0.5">${bet.matchInfo.date || 'Belirtilmemiş'}</p>
                    </div>
                `;
            });
            
            slipContainer.innerHTML = html;
            
            // Calculate total win based on coupon amount
            calculateTotalMobileWin();
            
            // Enable bet button
            placeBetBtn.disabled = false;
            placeBetBtn.className = 'w-full bg-[#f7931e] text-black text-sm font-semibold py-4 rounded-lg hover:bg-[#e67e22] transition-all duration-300 font-bold';
            

        }
        
        // This function is no longer needed since we don't have individual bet amounts
        // function calculateMobileWin(betId, amount, odds) { ... }
        
        // These functions are no longer needed since we don't have individual bet amounts
        // function updateMobileBetAmount(betId, amount, odds) { ... }
        // function setMobileBetAmount(betId, amount, odds) { ... }
        
        function calculateTotalMobileWin() {
            const couponAmount = parseFloat(document.getElementById('mobile-coupon-amount').value) || 0;
            let totalOdds = 1;
            
            // Calculate total odds by multiplying all bet odds
            selectedBets.forEach(bet => {
                totalOdds *= bet.odds;
            });
            
            // Update total odds display
            document.getElementById('mobile-total-odds').textContent = totalOdds.toFixed(2);
            
            // Calculate and update total win
            const totalWin = (couponAmount * totalOdds).toFixed(2);
            document.getElementById('mobile-total-win').textContent = totalWin + ' ₺';
        }
        
        function setMaxAmount() {
            document.getElementById('mobile-coupon-amount').value = '100000';
            calculateTotalMobileWin();
        }
        
        function switchMobileTab(tab, clickedElement) {
            const couponContent = document.getElementById('mobile-slip-bets');
            const openContent = document.getElementById('mobile-open-content');
            const tabs = document.querySelectorAll('.mobile-slip-tab');
            
            // Use IDs for reliable element selection
            const betButtonSection = document.getElementById('mobile-bet-button-section');
            const settingsRow = document.getElementById('mobile-settings-row');
            const betTypeHeader = document.getElementById('mobile-bet-type-header');
            
            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove('active'));
            
            // Add active class to clicked tab
            if (clickedElement) {
                clickedElement.classList.add('active');
            } else {
                // Fallback: find tab by tab name
                const targetTab = document.querySelector(`[onclick*="'${tab}'"]`);
                if (targetTab) {
                    targetTab.classList.add('active');
                }
            }
            
            if (tab === 'coupon') {
                couponContent.style.display = 'block';
                openContent.style.display = 'none';
                
                // Show coupon-related elements
                if (betButtonSection) betButtonSection.style.display = 'block';
                if (settingsRow) settingsRow.style.display = 'flex';
                if (betTypeHeader) betTypeHeader.style.display = 'flex';
                
            } else if (tab === 'open') {
                couponContent.style.display = 'none';
                openContent.style.display = 'block';
                
                // Hide coupon-related elements when showing open bets
                if (betButtonSection) betButtonSection.style.display = 'none';
                if (settingsRow) settingsRow.style.display = 'none';
                if (betTypeHeader) betTypeHeader.style.display = 'none';
                
                // Load open bets when switching to open tab
                loadMobileOpenBets();
            }
        }
        
        async function clearAllMobileBets() {
            selectedBets = [];
            
            // Save to localStorage (IMPORTANT!)
            saveBettingSlipToStorage();
            
            updateMobileSlip();
            
            // Update coupon count immediately (INSTANT)
            updateMobileCouponCount();
            
            // Update other footer counts asynchronously
            setTimeout(() => {
                updateMobileFooterCounts();
            }, 50);
        }
        
        async function removeMobileBet(betId) {
            selectedBets = selectedBets.filter(bet => bet.id !== betId);
            
            // Save to localStorage
            saveBettingSlipToStorage();
            
            updateMobileSlip();
            
            // Update coupon count immediately (INSTANT)
            updateMobileCouponCount();
            
            // Update other footer counts asynchronously
            setTimeout(() => {
                updateMobileFooterCounts();
            }, 50);
        }
        
        function openMobileSlip() {
            document.getElementById('mobile-betting-slip').classList.add('active');
            // Update slip display when opening
            updateMobileSlip();
        }
        
        function closeMobileSlip() {
            document.getElementById('mobile-betting-slip').classList.remove('active');
        }
        
        async function openMobileLive() {
            // Set mode to live
            currentMode = 'live';
            currentSportFilter = null;
            
            // Save current bulletin data before switching
            if (Object.keys(matchData || {}).length > 0) {
                savedBulletinData = {...matchData};
            }
            
            // Close match detail if open
            const matchDetailContainer = document.getElementById('mobile-match-detail');
            if (matchDetailContainer && matchDetailContainer.style.display !== 'none') {
                closeMobileMatchDetail();
            }
            
            // Close live odds detail if open (from live betting page)
            closeMobileLiveOddsDetail();
            
            // Hide the separate live page
            document.getElementById('mobile-live-page').classList.remove('active');
            
            // Hide list view toggle in live mode
            const listViewToggle = document.getElementById('mobile-list-view-toggle');
            if (listViewToggle) {
                listViewToggle.style.display = 'none';
            }
            
            // Show normal structure but with live data
            const leaguesContainer = document.getElementById('mobile-leagues-list');
            const backBtn = document.getElementById('mobile-back-btn');
            const searchContainer = document.querySelector('.px-3.py-2');
            const sportsContainer = document.getElementById('mobile-sports');
            const matchesContainer = document.getElementById('mobile-matches-list');
            
            // Hide back button and leagues
            backBtn.style.display = 'none';
            if (leaguesContainer) leaguesContainer.style.display = 'none';
            
            // Show sports, search, and matches
            if (sportsContainer) sportsContainer.style.display = 'block';
            if (searchContainer) searchContainer.style.display = 'block';
            if (matchesContainer) matchesContainer.style.display = 'block';
            
            // Load live data first
            await loadMobileLiveData();
            
            // Populate sports with live match counts
            populateMobileLiveSportsInMain();
            
            // Show all live matches (Futbol first)
            showAllLiveMatches();
        }
        
        function openMobileBulletin() {
            try {
                // Set mode to bulletin
                currentMode = 'bulletin';
                currentSportFilter = null;
                
                // Restore bulletin data if we have it, otherwise matchData should already have the data
                if (savedBulletinData && Object.keys(savedBulletinData).length > 0) {
                    matchData = {...savedBulletinData};
                }
                
                // Close match detail if open
                const matchDetailContainer = document.getElementById('mobile-match-detail');
                if (matchDetailContainer && matchDetailContainer.style.display !== 'none') {
                    closeMobileMatchDetail();
                }
                
                // Close live odds detail if open (from live betting page)
                closeMobileLiveOddsDetail();
                
                // Show list view toggle in bulletin mode
                const listViewToggle = document.getElementById('mobile-list-view-toggle');
                if (listViewToggle) {
                    listViewToggle.style.display = 'flex';
                }
                
            // Show normal pre-match matches by showing leagues first
            const matchesContainer = document.getElementById('mobile-matches-list');
            const leaguesContainer = document.getElementById('mobile-leagues-list');
            const backBtn = document.getElementById('mobile-back-btn');
                const searchContainer = document.querySelector('.px-3.py-2');
            const sportsContainer = document.getElementById('mobile-sports');
            
                // Hide matches and back button
                if (matchesContainer) matchesContainer.style.display = 'none';
                if (backBtn) backBtn.style.display = 'none';
                
                // Show search and sports
            if (searchContainer) searchContainer.style.display = 'block';
            if (sportsContainer) sportsContainer.style.display = 'block';
                
                // Re-populate sports for bulletin mode
                populateMobileSports();
                
                // Check list view state and apply accordingly
                if (isListViewActive) {
                    // If list view was active, show all matches directly
                    if (leaguesContainer) leaguesContainer.style.display = 'none';
                    showAllMobileMatches();
                } else {
                    // Show normal leagues view
                    if (leaguesContainer) leaguesContainer.style.display = 'block';
                    populateMobileLeagues();
                }
            } catch (error) {
                console.error('Error in openMobileBulletin:', error);
            }
        }
        
        function closeMobileLive() {
            document.getElementById('mobile-live-page').classList.remove('active');
        }
        
        function populateMobileLiveSportsInMain() {
            const sportsContainer = document.getElementById('mobile-sports');
            if (!sportsContainer) {
                return;
            }
            
            // If no live matches, show loading state
            if (!liveMatches || liveMatches.length === 0) {
                sportsContainer.innerHTML = `
                    <div class="flex flex-nowrap space-x-2 overflow-x-auto pb-2">
                        <div class="flex items-center space-x-2 bg-[#192231] rounded px-3 py-2 min-w-[80px] shrink-0">
                            <div class="text-white opacity-70"><i class="fas fa-spinner fa-spin"></i></div>
                            <span class="text-[11px] font-semibold text-white">Yükleniyor...</span>
                        </div>
                    </div>
                `;
                return;
            }
            
            const sports = {};
            
            // Count matches by sport
            liveMatches.forEach(match => {
                const sport = match.tur || 'Diğer';
                sports[sport] = (sports[sport] || 0) + 1;
            });
            
            // Sport configuration with FontAwesome icons (same as bulletin)
            const sportConfig = {
                'Futbol': {
                    icon: '<i class="fas fa-futbol text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                },
                'Basketbol': {
                    icon: '<i class="fas fa-basketball-ball text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                },
                'Tenis': {
                    icon: '<i class="fas fa-table-tennis text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                },
                'Voleybol': {
                    icon: '<i class="fas fa-volleyball-ball text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                },
                'Masatenisi': {
                    icon: '<i class="fas fa-table-tennis text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                },
                'Beysbol': {
                    icon: '<i class="fas fa-baseball-ball text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                },
                'Amerikan Futbolu': {
                    icon: '<i class="fas fa-football-ball text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                },
                'Cricket': {
                    icon: '<i class="fas fa-trophy text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                },
                'Buz Hokeyi': {
                    icon: '<i class="fas fa-hockey-puck text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                },
                'Snooker': {
                    icon: '<i class="fas fa-circle text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                }
            };
            
            let html = '<div class="flex space-x-2 overflow-x-auto pb-2">';
            
            // Add "Tümü" button first with bulletin style
            html += `
                <button type="button" class="flex items-center space-x-2 bg-[#1a2a4a] rounded px-3 py-2 min-w-[80px] shrink-0" onclick="showAllLiveMatches()" data-sport="all">
                    <div class="text-white opacity-70"><i class="fas fa-list"></i></div>
                    <span class="text-[11px] font-semibold text-white">Tümü</span>
                    <span class="bg-[#1a2a4a] text-white text-[10px] font-semibold rounded px-1.5 py-0.5">${liveMatches.length}</span>
                </button>
            `;
            
            Object.keys(sports).forEach((sport, index) => {
                const count = sports[sport];
                
                // Get sport configuration based on sport name
                let config = {
                    icon: '<i class="fas fa-trophy text-white opacity-70"></i>',
                    color: 'bg-[#192231]',
                    textColor: 'text-white',
                    countColor: 'bg-[#1a2a4a] text-white'
                };
                
                // Map sport names to configurations
                if (sportConfig[sport]) {
                    config = sportConfig[sport];
                }
                
                html += `
                    <button type="button" class="flex items-center space-x-2 ${config.color} rounded px-3 py-2 min-w-[80px] shrink-0" onclick="showMobileLiveMatchesBySport('${sport}')" data-sport="${sport}">
                        <div class="text-white opacity-70">${config.icon}</div>
                        <span class="text-[11px] font-semibold ${config.textColor}">${sport}</span>
                        <span class="${config.countColor} text-[10px] font-semibold rounded px-1.5 py-0.5">${count}</span>
                    </button>
                `;
            });
            
            html += '</div>';
            sportsContainer.innerHTML = html;
        }
        
        function showMobileLiveMatchesBySport(sport) {
            const filteredMatches = liveMatches.filter(match => match.tur === sport);
            
            const matchesContainer = document.getElementById('mobile-matches-list');
            
            // Update active sport button (bulletin style)
            const sportButtons = document.querySelectorAll('#mobile-sports button');
            sportButtons.forEach(btn => {
                btn.classList.remove('bg-[#1a2a4a]');
                btn.classList.add('bg-[#192231]');
            });
            
            const activeButton = document.querySelector(`#mobile-sports button[data-sport="${sport}"]`);
            if (activeButton) {
                activeButton.classList.remove('bg-[#192231]');
                activeButton.classList.add('bg-[#1a2a4a]');
            }
            
            // Keep everything visible, just filter the matches
            if (filteredMatches.length === 0) {
                matchesContainer.innerHTML = `
                    <div class="px-4 py-8 text-center">
                        <div class="text-[#6a6a6a] text-sm">
                            <i class="fas fa-play text-2xl mb-2 block"></i>
                            <p>Bu sporda canlı maç bulunamadı</p>
                            <p class="text-xs mt-1">Şu anda ${sport} canlı maçı yok</p>
                        </div>
                    </div>
                `;
                return;
            }
            
            let html = '';
            filteredMatches.forEach(match => {
                const score = match.skor || '0-0';
                const [homeScore, awayScore] = score.split('-').map(s => s.trim());
                const isLive = match.oynuyormu === '1';
                const liveText = isLive ? `${match.dakika || '0'}'` : (match.dakika ? `${match.dakika}'` : '0\'');
                const liveColor = isLive ? 'text-red-600' : 'text-blue-400';
                
                html += `
                    <div class="bg-[rgb(18,29,47)] rounded mb-4 cursor-pointer hover:bg-[rgb(25,40,60)] transition-colors" onclick="showMobileLiveOdds('${match.mac_id}', this)">
                        <div class="w-full flex items-center justify-between px-4 py-3 border-b border-gray-700">
                            <div class="flex items-center space-x-2 text-sm font-semibold text-gray-300">
                                <img alt="${match.ulke || 'Bilinmeyen'} bayrağı" class="w-4 h-3 object-cover rounded-sm flex-shrink-0" height="12" src="${getCountryFlag(match.ulke || 'Bilinmeyen')}" width="16" onerror="this.src='/images/flags/defaults.png'"/>
                                ${match.ulke && match.lig && match.ulke !== match.lig ? 
                                    `<span class="truncate">${match.ulke}</span>
                                     <span class="ml-1 font-normal truncate">${match.lig}</span>` :
                                    `<span class="truncate">${match.lig || match.ulke || 'Bilinmeyen Lig'}</span>`
                                }
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs ${liveColor} font-semibold">
                                    <i class="fas fa-circle text-[6px]"></i>
                                    ${liveText}
                                </span>
                                <i class="fas fa-chevron-up text-gray-400"></i>
                            </div>
                        </div>
                        <div class="px-4 py-3 text-xs text-gray-400 space-y-3">
                            <div class="text-gray-500">${formatDateTime(match.baslangic)}</div>
                            
                            <div class="flex items-center justify-between text-gray-300 font-semibold">
                                <div class="flex-1">Maç Sonucu</div>
                                <div class="text-xs text-gray-500">Canlı</div>
                            </div>
                            
                            <div class="grid grid-cols-7 gap-2 text-xs text-gray-300 font-semibold">
                                <div class="col-span-2 flex flex-col space-y-2">
                                    <div class="flex justify-between items-center">
                                        <span class="truncate">${match.evsahibi_isim}</span>
                                        <span class="text-yellow-400 font-bold ml-2">${homeScore}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="truncate">${match.misafir_isim}</span>
                                        <span class="text-yellow-400 font-bold ml-2">${awayScore}</span>
                                    </div>
                                </div>
                                <button class="col-span-2 bg-[rgb(11,20,34)] rounded text-yellow-400 px-2 py-2 flex items-center justify-center hover:bg-[#4a4a4a] transition-colors duration-200" onclick="event.stopPropagation(); addLiveBet('1', ${match.oran1 || 2.0}, '${match.mac_id}')">
                                    ${match.oran1 || '2.00'}
                                </button>
                                <button class="col-span-1 bg-[rgb(11,20,34)] rounded text-yellow-400 px-2 py-2 flex items-center justify-center hover:bg-[#4a4a4a] transition-colors duration-200" onclick="event.stopPropagation(); addLiveBet('X', ${match.oranX || 3.0}, '${match.mac_id}')">
                                    ${match.oranX || '3.00'}
                                </button>
                                <button class="col-span-2 bg-[rgb(11,20,34)] rounded text-yellow-400 px-2 py-2 flex items-center justify-center hover:bg-[#4a4a4a] transition-colors duration-200" onclick="event.stopPropagation(); addLiveBet('2', ${match.oran2 || 3.0}, '${match.mac_id}')">
                                    ${match.oran2 || '3.00'}
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            // Replace addLiveBet calls with showMobileLiveOdds calls
            html = html.replace(/onclick="addLiveBet\('([^']+)', [^,]+,\s*'([^']+)'\)"/g, 
                               'onclick="showMobileLiveOdds(\'$2\', this)"');
            
            matchesContainer.innerHTML = html;
        }
        
        // Show mobile live odds - Bultendeki gibi güzel tasarımda açılsın
        async function showMobileLiveOdds(matchId, matchElement) {
            
            
            try {
                const apiUrl = `/Live/Odds/${matchId}`;
                
                
                const response = await fetch(apiUrl);
                
                
                if (!response.ok) {
                    throw new Error(`Live API request failed with status: ${response.status}`);
                }
                
                const oddsData = await response.json();
                
                
                if (oddsData.success) {
                    // Bultendeki gibi güzel tasarımda açılsın
                    showMobileLiveOddsDetail(oddsData, matchElement, matchId);
                } else {
                    throw new Error('Live API returned success: false');
                }
                
            } catch (error) {
                showToast('Bu karşılaşmaya bahis alınamıyor!', 'error');
            }
        }
        
        // Show mobile live odds detail - Bultendeki gibi güzel tasarımda açılsın
        function showMobileLiveOddsDetail(oddsData, matchElement, matchId) {
            // Maç bilgilerini al
            const match = liveMatches.find(m => m.mac_id === matchId);
            if (!match) {
                showToast('Maç bilgileri bulunamadı!', 'error');
                return;
            }
            
            // Spor ikonunu belirle
            let sportIcon = 'fa-futbol';
            let sportColor = 'text-green-500';
            
            const sportLower = (match.tur || 'futbol').toLowerCase();
            if (sportLower.includes('basket')) {
                sportIcon = 'fa-basketball-ball';
                sportColor = 'text-orange-500';
            } else if (sportLower.includes('tenis')) {
                sportIcon = 'fa-table-tennis';
                sportColor = 'text-yellow-500';
            } else if (sportLower.includes('voleybol')) {
                sportIcon = 'fa-volleyball-ball';
                sportColor = 'text-blue-500';
            }
            
            // Maç skorunu al
            const score = match.skor || '0-0';
            const [homeScore, awayScore] = score.split('-').map(s => s.trim());
            const isLive = match.oynuyormu === '1';
            const liveText = isLive ? `${match.dakika || '0'}'` : (match.dakika ? `${match.dakika}'` : '0\'');
            const liveColor = isLive ? 'text-red-600' : 'text-blue-400';
            
            // Bultendeki gibi güzel tasarımda HTML oluştur
            const html = `
                <div class="fixed inset-0 bg-[#0a1220] z-20 overflow-y-auto pb-16">
                    <div class="w-full rounded-md overflow-hidden shadow-lg bg-gradient-to-b from-[#0f1a2b] to-[#0a1220] min-h-screen">
                        <!-- Back Button -->
                        <div class="flex justify-start p-2">
                            <button onclick="closeMobileLiveOddsDetail()" class="text-[#7a8dbd] hover:text-white flex items-center gap-2">
                                <i class="fas fa-arrow-left text-lg"></i>
                                <span class="text-sm font-medium">Geri</span>
                            </button>
                        </div>
                        
                        <!-- Banner with Sportradar iframe for live matches -->
                        <div class="relative">
                            ${match.betradar_id ? 
                                `<iframe 
                                    src="https://widgets.sir.sportradar.com/sportradar/tr/standalone/match.lmtPlus#matchId=sr:match:${match.betradar_id}&scoreboard=disable" 
                                    class="w-full h-[500px] border-0" 
                                    frameborder="0" 
                                    allowfullscreen>
                                </iframe>` : 
                                `<img alt="Match banner" class="w-full object-cover h-28" height="120" src="https://storage.googleapis.com/a1aa/image/3ab0efd3-cba0-45b6-6d75-3914ff165093.jpg" width="400"/>`
                            }
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0a1220] via-transparent to-transparent"></div>
                            <div class="absolute bottom-2 left-3 text-white">
                                <div class="flex items-center gap-1 text-xs font-semibold text-[#7a8dbd]">
                                    <i class="fas ${sportIcon} ${sportColor}"></i>
                                    <span>${match.lig || 'Canlı Maç'}</span>
                                </div>
                                <div class="text-[10px] mt-0.5 text-[#7a8dbd]">
                                    ${formatDateTime(match.baslangic)}
                                </div>
                                <div class="text-sm font-semibold mt-1">
                                    ${match.evsahibi_isim} vs ${match.misafir_isim}
                                </div>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-lg font-bold text-[#f59e0b]">${homeScore}</span>
                                    <span class="text-[#7a8dbd] text-xs">-</span>
                                    <span class="text-lg font-bold text-[#f59e0b]">${awayScore}</span>
                                    <span class="text-xs ${liveColor} ml-2">
                                        <i class="fas fa-circle text-xs animate-pulse"></i>
                                        ${liveText}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Navigation Tabs -->
                        <nav class="flex items-center gap-4 bg-[#0a1220] px-3 py-2 border-b border-[#1a2a4a] text-xs font-semibold text-[#7a8dbd]">
                            <button class="flex items-center gap-1 text-white border-b-2 border-white pb-1" onclick="switchMobileLiveMarketTab('all')">
                                <i class="fas fa-star"></i>
                                Tümü
                            </button>
                            <button class="flex items-center gap-1 hover:text-white" onclick="switchMobileLiveMarketTab('general')">
                                Genel
                                <sup class="ml-0.5 text-[8px] font-normal">${Object.keys(oddsData.additional_odds || {}).length + 1}</sup>
                            </button>
                            <button class="flex items-center gap-1 hover:text-white" onclick="switchMobileLiveMarketTab('players')">
                                Oyuncular
                                <sup class="ml-0.5 text-[8px] font-normal">0</sup>
                            </button>
                        </nav>
                        
                        <!-- Market Section -->
                        <div class="px-3 py-2 text-xs text-[#7a8dbd] font-semibold border-b border-[#1a2a4a]">
                            Marketler
                        </div>
                        
                        <!-- All Markets - Bultendeki normal maç tasarımının aynısı -->
                        ${generateMobileLiveOddsSimpleHTML(oddsData)}
                    </div>
                </div>
            `;
            
            // Insert the HTML directly into the body as a fixed overlay
            document.body.insertAdjacentHTML('beforeend', html);
        }
        
        // Generate mobile live odds simple HTML - Bultendeki normal maç tasarımının aynısı
        function generateMobileLiveOddsSimpleHTML(oddsData) {
            let html = '';
            
            // Show main odds first (Kazanan) - Maç öncesi gibi güzel tasarım
            if (oddsData.main_odds) {
                html += `
                    <div class="px-3 py-2 text-sm text-white font-semibold flex justify-between items-center bg-[#1a2a4a] border-b border-[#2a3a5a]">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-trophy text-[#f59e0b] text-sm"></i>
                            Kazanan
                        </span>
                        <i class="fas fa-sync-alt text-[#3a7d3a] text-xs"></i>
                    </div>
                    <div class="p-2.5 grid grid-cols-3 gap-1.5">
                `;
                
                // Home odds
                if (oddsData.main_odds['1']) {
                    const odds1 = parseFloat(oddsData.main_odds['1']);
                    const betId = `live_${oddsData.match_id || Date.now()}_Kazanan_1`;
                    html += `
                        <button class="mobile-odds-btn flex flex-col items-center justify-center bg-[#2a3a5a] hover:bg-[#3a4a6a] rounded-lg py-2 px-2 transition-all duration-200 border border-[#3a4a6a] hover:border-[#f59e0b]" 
                                data-bet-id="${betId}" 
                                onclick="addMobileLiveBetFromModal('1', ${odds1}, 'Evsahibi Kazanır', 'Kazanan'); toggleMobileOddsSelection(this, '${betId}')">
                            <span class="text-white text-[10px] font-medium mb-0.5 truncate w-full text-center">Evsahibi</span>
                            <span class="text-[#f59e0b] font-bold text-xs">${odds1.toFixed(2)}</span>
                        </button>`;
                }
                
                // Draw odds
                if (oddsData.main_odds['X']) {
                    const oddsX = parseFloat(oddsData.main_odds['X']);
                    const betId = `live_${oddsData.match_id || Date.now()}_Kazanan_X`;
                    html += `
                        <button class="mobile-odds-btn flex flex-col items-center justify-center bg-[#2a3a5a] hover:bg-[#3a4a6a] rounded-lg py-2 px-2 transition-all duration-200 border border-[#3a4a6a] hover:border-[#f59e0b]" 
                                data-bet-id="${betId}" 
                                onclick="addMobileLiveBetFromModal('X', ${oddsX}, 'Berabere', 'Kazanan'); toggleMobileOddsSelection(this, '${betId}')">
                            <span class="text-white text-[10px] font-medium mb-0.5 truncate w-full text-center">Berabere</span>
                            <span class="text-[#f59e0b] font-bold text-xs">${oddsX.toFixed(2)}</span>
                        </button>`;
                }
                
                // Away odds
                if (oddsData.main_odds['2']) {
                    const odds2 = parseFloat(oddsData.main_odds['2']);
                    const betId = `live_${oddsData.match_id || Date.now()}_Kazanan_2`;
                    html += `
                        <button class="mobile-odds-btn flex flex-col items-center justify-center bg-[#2a3a5a] hover:bg-[#3a4a6a] rounded-lg py-2 px-2 transition-all duration-200 border border-[#3a4a6a] hover:border-[#f59e0b]" 
                                data-bet-id="${betId}" 
                                onclick="addMobileLiveBetFromModal('2', ${odds2}, 'Deplasman Kazanır', 'Kazanan'); toggleMobileOddsSelection(this, '${betId}')">
                            <span class="text-white text-[10px] font-medium mb-0.5 truncate w-full text-center">Deplasman</span>
                            <span class="text-[#f59e0b] font-bold text-xs">${odds2.toFixed(2)}</span>
                        </button>`;
                }
                
                html += `</div>`;
            }
            
            // Show additional markets - Maç öncesi gibi güzel tasarım
            if (oddsData.additional_odds) {
                Object.keys(oddsData.additional_odds).forEach(marketName => {
                    const marketOdds = oddsData.additional_odds[marketName];
                    if (typeof marketOdds === 'object' && marketOdds !== null) {
                        html += `
                            <div class="px-3 py-2 text-sm text-white font-semibold flex justify-between items-center bg-[#1a2a4a] border-b border-[#2a3a5a] mt-3">
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-chart-line text-[#f59e0b] text-sm"></i>
                                    ${marketName}
                                </span>
                                <i class="fas fa-sync-alt text-[#3a7d3a] text-xs"></i>
                            </div>
                            <div class="p-2.5 grid grid-cols-2 gap-1.5">
                        `;
                        
                        Object.keys(marketOdds).forEach(selectionName => {
                            const odds = marketOdds[selectionName];
                            if (typeof odds === 'number' || (typeof odds === 'string' && !isNaN(parseFloat(odds)))) {
                                const oddsValue = parseFloat(odds);
                                const betId = `live_${oddsData.match_id || Date.now()}_${marketName}_${selectionName}`.replace(/[^a-zA-Z0-9_]/g, '_');
                                
                                html += `
                                    <button class="mobile-odds-btn flex items-center justify-between bg-[#2a3a5a] hover:bg-[#3a4a6a] rounded-lg py-2 px-2.5 transition-all duration-200 border border-[#3a4a6a] hover:border-[#f59e0b]" 
                                            data-bet-id="${betId}" 
                                            onclick="addMobileLiveBetFromModal('${betId}', ${oddsValue}, '${selectionName}', '${marketName}'); toggleMobileOddsSelection(this, '${betId}')">
                                        <span class="text-white text-[10px] font-medium truncate">${selectionName}</span>
                                        <span class="text-[#f59e0b] font-bold text-xs ml-2">${oddsValue.toFixed(2)}</span>
                                    </button>`;
                            }
                        });
                        
                        html += `</div>`;
                    }
                });
            }
            
            return html || '<div class="text-gray-400 text-center py-4">Oran bulunamadı</div>';
        }
        
        // Generate mobile odds HTML - Bultendeki gibi güzel tasarımda
        function generateMobileLiveOddsHTML(oddsData) {
            let html = '';
            
            // Show main odds first (Kazanan section) - Bultendeki gibi güzel tasarımda
            if (oddsData.main_odds) {
                html += `
                    <div class="mb-3">
                        <div class="px-3 py-2 bg-gradient-to-r from-[#1a2a4a] to-[#2a3a5a] rounded-t-lg border-b border-[#3a4a6a]">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-trophy text-[#f59e0b] text-sm"></i>
                                    <span class="text-white font-semibold text-sm">Kazanan</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-sync-alt text-[#3a7d3a] text-xs animate-pulse"></i>
                                    <span class="text-[#7a8dbd] text-xs">Canlı</span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-[#1a2a4a] rounded-b-lg p-3">
                            <div class="grid grid-cols-3 gap-2">
                `;
                
                // Home odds
                if (oddsData.main_odds['1']) {
                    const odds1 = parseFloat(oddsData.main_odds['1']);
                    const betId = `live_${oddsData.match_id || Date.now()}_Kazanan_1`;
                    html += `
                        <button class="mobile-odds-btn flex flex-col items-center justify-center py-2 px-2 bg-[#2a3a5a] rounded-lg hover:bg-[#3a4a6a] transition-all duration-200 cursor-pointer border border-[#3a4a6a] hover:border-[#f59e0b]" 
                                data-bet-id="${betId}"
                                onclick="addMobileLiveBetFromModal('1', ${odds1}, 'Evsahibi Kazanır', 'Kazanan'); toggleMobileOddsSelection(this, '${betId}')">
                            <div class="text-white text-[10px] mb-0.5 font-medium">Evsahibi</div>
                            <div class="text-[#f59e0b] font-bold text-xs">${odds1.toFixed(2)}</div>
                        </button>`;
                } else {
                    html += `<div class="flex flex-col items-center justify-center p-2 bg-[#2a2a2a] rounded-lg border border-[#3a4a6a]">
                        <div class="text-[#7a8dbd] text-[10px] mb-1">Evsahibi</div>
                        <div class="text-[#6b6b6b] font-bold text-base">-</div>
                    </div>`;
                }
                
                // Draw odds
                if (oddsData.main_odds['X']) {
                    const oddsX = parseFloat(oddsData.main_odds['X']);
                    const betId = `live_${oddsData.match_id || Date.now()}_Kazanan_X`;
                    html += `
                        <button class="mobile-odds-btn flex flex-col items-center justify-center py-2 px-2 bg-[#2a3a5a] rounded-lg hover:bg-[#3a4a6a] transition-all duration-200 cursor-pointer border border-[#3a4a6a] hover:border-[#f59e0b]" 
                                data-bet-id="${betId}"
                                onclick="addMobileLiveBetFromModal('X', ${oddsX}, 'Berabere', 'Kazanan'); toggleMobileOddsSelection(this, '${betId}')">
                            <div class="text-white text-[10px] mb-0.5 font-medium">Berabere</div>
                            <div class="text-[#f59e0b] font-bold text-xs">${oddsX.toFixed(2)}</div>
                        </button>`;
                } else {
                    html += `<div class="flex flex-col items-center justify-center p-2 bg-[#2a2a2a] rounded-lg border border-[#3a4a6a]">
                        <div class="text-[#7a8dbd] text-[10px] mb-1">Beraberlik</div>
                        <div class="text-[#6b6b6b] font-bold text-base">-</div>
                    </div>`;
                }
                
                // Away odds
                if (oddsData.main_odds['2']) {
                    const odds2 = parseFloat(oddsData.main_odds['2']);
                    const betId = `live_${oddsData.match_id || Date.now()}_Kazanan_2`;
                    html += `
                        <button class="mobile-odds-btn flex flex-col items-center justify-center py-2 px-2 bg-[#2a3a5a] rounded-lg hover:bg-[#3a4a6a] transition-all duration-200 cursor-pointer border border-[#3a4a6a] hover:border-[#f59e0b]" 
                                data-bet-id="${betId}"
                                onclick="addMobileLiveBetFromModal('2', ${odds2}, 'Deplasman Kazanır', 'Kazanan'); toggleMobileOddsSelection(this, '${betId}')">
                            <div class="text-white text-[10px] mb-0.5 font-medium">Deplasman</div>
                            <div class="text-[#f59e0b] font-bold text-xs">${odds2.toFixed(2)}</div>
                        </button>`;
                } else {
                    html += `<div class="flex flex-col items-center justify-center p-2 bg-[#2a2a2a] rounded-lg border border-[#3a4a6a]">
                        <div class="text-[#7a8dbd] text-[10px] mb-1">Deplasman</div>
                        <div class="text-[#6b6b6b] font-bold text-base">-</div>
                    </div>`;
                }
                
                html += `
                            </div>
                        </div>
                    </div>
                `;
            }
            
            // Show additional markets - Bultendeki gibi güzel tasarımda
            if (oddsData.additional_odds) {
                Object.keys(oddsData.additional_odds).forEach(marketName => {
                    const marketOdds = oddsData.additional_odds[marketName];
                    if (typeof marketOdds === 'object' && marketOdds !== null) {
                        html += generateMobileLiveMarketHTML(marketName, marketOdds);
                    }
                });
            }
            
            return html || '<div class="text-gray-400 text-center py-8"><i class="fas fa-exclamation-triangle text-2xl mb-2 block"></i>Oran bulunamadı</div>';
        }
        
        // Generate mobile live market HTML (for additional markets) - Bultendeki gibi güzel tasarımda
        function generateMobileLiveMarketHTML(marketName, marketOdds) {
            let html = '';
            
            // Get market entries and organize them
            const marketEntries = Object.entries(marketOdds);
            
            if (marketEntries.length === 0) {
                return html;
            }
            
            // Start market section with enhanced styling - Bultendeki gibi
            html += `
                <div class="mb-3">
                    <div class="px-3 py-2 bg-gradient-to-r from-[#1a2a4a] to-[#2a3a5a] rounded-t-lg border-b border-[#3a4a6a]">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-chart-line text-[#3a7d3a] text-sm"></i>
                                <span class="text-white font-semibold text-sm">${marketName}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-sync-alt text-[#3a7d3a] text-xs animate-pulse"></i>
                                <span class="text-[#7a8dbd] text-xs">Canlı</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-[#1a2a4a] rounded-b-lg p-3">
            `;
            
            // For markets with 3 or fewer options - use 3-column layout
            if (marketEntries.length <= 3) {
                html += `<div class="grid grid-cols-${marketEntries.length} gap-2">`;
                
                marketEntries.forEach(([selectionName, odds]) => {
                    if (typeof odds === 'number' || (typeof odds === 'string' && !isNaN(parseFloat(odds)))) {
                        const oddsValue = parseFloat(odds);
                        const betId = `${marketName}_${selectionName}`.replace(/[^a-zA-Z0-9]/g, '_');
                        
                        html += `
                            <button class="mobile-odds-btn flex flex-col items-center justify-center py-2 px-2 bg-[#2a3a5a] rounded-lg hover:bg-[#3a4a6a] transition-all duration-200 cursor-pointer border border-[#3a4a6a] hover:border-[#f59e0b]" 
                                    data-bet-id="live_${Date.now()}_${marketName}_${selectionName}"
                                    onclick="addMobileLiveBetFromModal('${betId}', ${oddsValue}, '${selectionName}', '${marketName}'); toggleMobileOddsSelection(this, 'live_${Date.now()}_${marketName}_${selectionName}')">
                                <div class="text-white text-[10px] mb-0.5 font-medium text-center">${selectionName}</div>
                                <div class="text-[#f59e0b] font-bold text-xs">${oddsValue.toFixed(2)}</div>
                            </button>`;
                    } else {
                        html += `<div class="flex flex-col items-center justify-center p-2 bg-[#2a2a2a] rounded-lg border border-[#3a4a6a]">
                            <div class="text-[#7a8dbd] text-[10px] mb-1 text-center">${selectionName}</div>
                            <div class="text-[#6b6b6b] font-bold text-base">-</div>
                        </div>`;
                    }
                });
                
                html += `</div>`;
            } else {
                // For markets with 4+ options, use compact design
                html += `<div class="grid grid-cols-2 gap-2">`;
                
                marketEntries.forEach(([selectionName, odds]) => {
                    if (typeof odds === 'number' || (typeof odds === 'string' && !isNaN(parseFloat(odds)))) {
                        const oddsValue = parseFloat(odds);
                        const betId = `${marketName}_${selectionName}`.replace(/[^a-zA-Z0-9]/g, '_');
                        
                        html += `
                            <button class="mobile-odds-btn flex items-center justify-between py-2 px-2.5 bg-[#2a3a5a] rounded-lg hover:bg-[#3a4a6a] transition-all duration-200 cursor-pointer border border-[#3a4a6a] hover:border-[#f59e0b]" 
                                    data-bet-id="live_${Date.now()}_${marketName}_${selectionName}"
                                    onclick="addMobileLiveBetFromModal('${betId}', ${oddsValue}, '${selectionName}', '${marketName}'); toggleMobileOddsSelection(this, 'live_${Date.now()}_${marketName}_${selectionName}')">
                                <span class="text-white font-medium text-[10px] truncate">${selectionName}</span>
                                <span class="text-[#f59e0b] font-bold text-xs ml-2">${oddsValue.toFixed(2)}</span>
                            </button>`;
                    }
                });
                
                html += `</div>`;
            }
            
            html += `
                    </div>
                </div>
            `;
            
            return html;
        }
        
        // Get match title from match element
        function getMatchTitle(matchElement) {
            // Try to find team names in the match element
            const homeTeamElement = matchElement.querySelector('.flex-1 .truncate');
            const awayTeamElement = matchElement.querySelectorAll('.flex-1 .truncate')[1];
            
            if (homeTeamElement && awayTeamElement) {
                return `${homeTeamElement.textContent} vs ${awayTeamElement.textContent}`;
            }
            
            // Fallback: try to find any text that looks like team names
            const textElements = matchElement.querySelectorAll('span');
            const teamNames = [];
            
            textElements.forEach(span => {
                const text = span.textContent.trim();
                if (text && text.length > 2 && !text.includes('vs') && !text.includes('Canlı') && !text.includes('Maç')) {
                    teamNames.push(text);
                }
            });
            
            if (teamNames.length >= 2) {
                return `${teamNames[0]} vs ${teamNames[1]}`;
            }
            
            return 'Canlı Maç';
        }
        
        // Close mobile live odds detail
        function closeMobileLiveOddsDetail() {
            // Remove the fixed overlay that was created by showMobileLiveOddsDetail
            const overlay = document.querySelector('.fixed.inset-0.bg-\\[\\#0a1220\\]');
            if (overlay) {
                overlay.remove();
            }
        }
        
        // Close mobile odds modal
        function closeMobileOddsModal() {
            const modal = document.getElementById('mobile-odds-modal');
            if (modal) {
                modal.remove();
            }
        }
        
        // Switch mobile live market tab
        function switchMobileLiveMarketTab(tab) {
            // Tab switching logic can be added here
            
        }
        
        // Add mobile live bet from modal
        function addMobileLiveBetFromModal(selectionId, odds, selectionName, marketName) {
            // For main odds (1, X, 2), use the current live match ID
            let eventId = selectionId;
            let liveMatch = null;
            
            // Find the live match data from the current context
            // Look for the match detail container that was created by showMobileLiveOddsDetail
            const detailPage = document.querySelector('.fixed.inset-0.bg-\\[\\#0a1220\\]');
            if (detailPage) {
                // Get match info from the detail page title
                const titleElement = detailPage.querySelector('.text-sm.font-semibold');
                if (titleElement) {
                    const titleText = titleElement.textContent;
                    // Find match in liveMatches by comparing team names
                    liveMatch = liveMatches.find(match => 
                        titleText.includes(match.evsahibi_isim) && titleText.includes(match.misafir_isim)
                    );
                }
            }
            
            if (!liveMatch) {
                showToast('Maç bulunamadı!', 'error');
                return;
            }
            
            // Create unique eventId for all selections
            if (selectionId === '1' || selectionId === 'X' || selectionId === '2') {
                eventId = `live_${liveMatch.mac_id}_${marketName}_${selectionId}`;
            } else {
                eventId = `live_${liveMatch.mac_id}_${marketName}_${selectionId}`;
            }
            
            // Enhanced odds validation - more strict checking
            const parsedOdds = parseFloat(odds);
            if (isNaN(parsedOdds) || parsedOdds <= 1 || parsedOdds === Infinity || parsedOdds === -Infinity) {
                showToast('Geçersiz oran! Bu seçenek için oran bulunamadı.', 'error');
                return;
            }
            
            // Additional validation: check if the odds value is reasonable
            if (parsedOdds > 1000) {
                showToast('Geçersiz oran! Oran değeri çok yüksek.', 'error');
                return;
            }
            
            // Create bet object for live match
            const bet = {
                eventId: eventId,
                selection: selectionName,
                odds: parsedOdds,
                market: marketName,
                matchInfo: {
                    home: liveMatch.evsahibi_isim,
                    away: liveMatch.misafir_isim,
                    league: liveMatch.lig,
                    date: formatDateTime(liveMatch.baslangic),
                    time: liveMatch.dakika ? `${liveMatch.dakika}'` : 'Canlı'
                }
            };
            
            // Check if bet already exists from the same match
            const existingBetIndex = selectedBets.findIndex(b => 
                b.eventId && b.eventId.startsWith(`live_${liveMatch.mac_id}_`)
            );
            
            if (existingBetIndex !== -1) {
                // Replace existing bet from same match with new selection
                selectedBets[existingBetIndex] = bet;
                showToast('Maç oranı güncellendi!', 'info');
            } else {
                // Add new bet
                selectedBets.push(bet);
                showToast('Canlı bahis eklendi!', 'success');
            }
            
            localStorage.setItem('selectedBets', JSON.stringify(selectedBets));
            
            // Save to localStorage using the standard function
            saveBettingSlipToStorage();
            
            // Update displays
            updateMobileSlip();
            updateMobileFooterCounts();
        }
        
        async function loadMobileLiveData() {
            try {
                // Load live matches from canlibulten table
                const response = await fetch('/api/live-matches');
                
                if (response.ok) {
                    const data = await response.json();
                    
                    if (data.success) {
                        liveMatches = data.matches; // Store globally
                        // Don't call populateMobileLiveSports here as it's for the separate live page
                        // populateMobileLiveMatches(data.matches);
                    } else {
                    }
                } else {
                }
            } catch (error) {
            }
        }
        
        function populateMobileLiveSports(matches) {
            const sportsContainer = document.getElementById('mobile-live-sports');
            const sports = {};
            
            // Count matches by sport
            matches.forEach(match => {
                const sport = match.tur || 'Diğer';
                sports[sport] = (sports[sport] || 0) + 1;
            });
            
            let html = '';
            Object.keys(sports).forEach((sport, index) => {
                const count = sports[sport];
                const isActive = index === 0 ? 'bg-[#1a7f1a]' : 'bg-[#3a2a00]';
                const textColor = index === 0 ? 'text-white' : 'text-yellow-400';
                const icon = getSportIcon(sport);
                
                html += `
                    <button class="flex items-center space-x-2 ${isActive} rounded px-3 py-2 min-w-[90px] text-sm font-semibold ${textColor} transition-all duration-300" onclick="filterMobileLiveBySport('${sport}')">
                        <i class="${icon}"></i>
                        <span>${sport}</span>
                        <span class="ml-auto bg-opacity-80 rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold">${count}</span>
                    </button>
                `;
            });
            
            sportsContainer.innerHTML = html;
        }
        
        function populateMobileLiveMatches(matches) {
            // Use the main matches container when in main view
            const matchesContainer = currentMode === 'live' && document.getElementById('mobile-matches-list') 
                ? document.getElementById('mobile-matches-list') 
                : document.getElementById('mobile-live-matches');
            
            if (matches.length === 0) {
                matchesContainer.innerHTML = `
                    <div class="px-4 py-8 text-center">
                        <div class="text-[#6a6a6a] text-sm">
                            <i class="fas fa-play text-2xl mb-2 block"></i>
                            <p>Canlı maç bulunamadı</p>
                            <p class="text-xs mt-1">Şu anda canlı maç yok</p>
                        </div>
                    </div>
                `;
                return;
            }
            
            let html = '<div class="space-y-2 p-2.5">';
            
            matches.forEach(match => {
                const score = match.skor || '0-0';
                const [homeScore, awayScore] = score.split('-').map(s => s.trim());
                const isLive = match.oynuyormu === '1';
                const liveText = isLive ? `${match.dakika || '0'}'` : (match.dakika ? `${match.dakika}'` : '0\'');
                const liveColor = isLive ? 'text-red-600' : 'text-blue-400';
                
                html += `
                    <div class="bg-[#2a2a2a] rounded-lg cursor-pointer hover:bg-[#3a3a3a] transition-colors" onclick="showMobileLiveOdds('${match.mac_id}', this)">
                        <div class="w-full flex items-center justify-between px-3 py-1.5 border-b border-gray-700">
                            <div class="flex items-center space-x-1.5 text-xs font-semibold text-gray-300">
                                <img alt="${match.ulke || 'Bilinmeyen'} bayrağı" class="w-4 h-3 object-cover rounded-sm flex-shrink-0" height="12" src="${getCountryFlag(match.ulke || 'Bilinmeyen')}" width="16" onerror="this.src='/images/flags/defaults.png'"/>
                                ${match.ulke && match.lig && match.ulke !== match.lig ? 
                                    `<span class="truncate">${match.ulke}</span>
                                     <span class="ml-1 font-normal truncate">${match.lig}</span>` :
                                    `<span class="truncate">${match.lig || match.ulke || 'Bilinmeyen Lig'}</span>`
                                }
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="${liveColor} text-xs font-bold">${liveText}</span>
                                <i class="fas fa-chevron-up text-gray-400 text-xs"></i>
                            </div>
                        </div>
                        <div class="px-3 py-1.5 text-[10px] text-gray-400 space-y-1.5">
                            <div>${formatDateTime(match.baslangic)}</div>
                            <div class="flex items-center space-x-2 text-gray-300 font-semibold">
                                <div class="flex-1">Maç Sonucu</div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex-1 flex flex-col space-y-1 text-xs">
                                    <div class="flex justify-between items-center">
                                        <span class="truncate text-white">${match.evsahibi_isim}</span>
                                        <span class="text-[#f59e0b] font-bold ml-2">${homeScore}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="truncate text-white">${match.misafir_isim}</span>
                                        <span class="text-[#f59e0b] font-bold ml-2">${awayScore}</span>
                                    </div>
                                </div>
                                <div class="flex space-x-1 ml-3">
                                    <button class="mobile-odds-btn bg-[#2a3a5a] hover:bg-[#3a4a6a] rounded px-2 py-1.5 text-[#f59e0b] font-bold text-xs transition-colors duration-200 border border-[#3a4a6a] hover:border-[#f59e0b] min-w-[45px]" 
                                            data-bet-id="live_${match.mac_id}_Kazanan_1"
                                            onclick="event.stopPropagation(); addLiveBet('1', ${match.oran1 || 2.0}, '${match.mac_id}'); toggleMobileOddsSelection(this, 'live_${match.mac_id}_Kazanan_1')">
                                        ${match.oran1 || '2.00'}
                                    </button>
                                    <button class="mobile-odds-btn bg-[#2a3a5a] hover:bg-[#3a4a6a] rounded px-2 py-1.5 text-[#f59e0b] font-bold text-xs transition-colors duration-200 border border-[#3a4a6a] hover:border-[#f59e0b] min-w-[40px]" 
                                            data-bet-id="live_${match.mac_id}_Kazanan_X"
                                            onclick="event.stopPropagation(); addLiveBet('X', ${match.oranX || 3.0}, '${match.mac_id}'); toggleMobileOddsSelection(this, 'live_${match.mac_id}_Kazanan_X')">
                                        ${match.oranX || '3.00'}
                                    </button>
                                    <button class="mobile-odds-btn bg-[#2a3a5a] hover:bg-[#3a4a6a] rounded px-2 py-1.5 text-[#f59e0b] font-bold text-xs transition-colors duration-200 border border-[#3a4a6a] hover:border-[#f59e0b] min-w-[45px]" 
                                            data-bet-id="live_${match.mac_id}_Kazanan_2"
                                            onclick="event.stopPropagation(); addLiveBet('2', ${match.oran2 || 3.0}, '${match.mac_id}'); toggleMobileOddsSelection(this, 'live_${match.mac_id}_Kazanan_2')">
                                        ${match.oran2 || '3.00'}
                                    </button>
                                </div>
                            </div>
                            <div class="flex items-center space-x-1 text-xs ${liveColor} font-semibold mt-1">
                                <i class="fas fa-circle text-[6px]"></i>
                                <span>${liveText}</span>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            matchesContainer.innerHTML = html;
        }
        
        function getSportIcon(sport) {
            const icons = {
                'Futbol': 'fas fa-futbol',
                'Basketbol': 'fas fa-basketball-ball',
                'Tenis': 'fas fa-table-tennis',
                'Voleybol': 'fas fa-volleyball-ball',
                'Masa Tenisi': 'fas fa-table-tennis',
                'Buz hokey': 'fas fa-hockey-puck'
            };
            return icons[sport] || 'fas fa-trophy';
        }
        
        function formatDateTime(dateString) {
            if (!dateString) return 'Belirtilmemiş';
            try {
                const date = new Date(dateString);
                return date.toLocaleDateString('tr-TR') + ' ' + date.toLocaleTimeString('tr-TR', {hour: '2-digit', minute: '2-digit'});
            } catch (e) {
                return dateString;
            }
        }
        
        function filterMobileLiveBySport(sport) {
            // Update active sport button
            const buttons = document.querySelectorAll('#mobile-live-sports button');
            buttons.forEach(btn => {
                btn.classList.remove('bg-[#1a7f1a]', 'text-white');
                btn.classList.add('bg-[#3a2a00]', 'text-yellow-400');
            });
            
            event.target.closest('button').classList.remove('bg-[#3a2a00]', 'text-yellow-400');
            event.target.closest('button').classList.add('bg-[#1a7f1a]', 'text-white');
            
            // Filter matches by sport
            const filteredMatches = liveMatches.filter(match => match.tur === sport);
            populateMobileLiveMatches(filteredMatches);
        }
        
        function searchMobileLiveMatches(event) {
            const searchTerm = event.target.value.toLowerCase();
            
            if (!searchTerm.trim()) {
                // If search is empty, show all matches
                populateMobileLiveMatches(liveMatches);
                return;
            }
            
            // Filter matches by search term
            const filteredMatches = liveMatches.filter(match => 
                match.evsahibi_isim.toLowerCase().includes(searchTerm) ||
                match.misafir_isim.toLowerCase().includes(searchTerm) ||
                match.lig.toLowerCase().includes(searchTerm) ||
                match.ulke.toLowerCase().includes(searchTerm)
            );
            
            populateMobileLiveMatches(filteredMatches);
        }
        
        // Function to open live match market instead of adding to bet slip
        function openLiveMatchMarket(matchId, clickedElement) {
            
            // Find the match element - try multiple selectors
            let matchElement = clickedElement.closest('.live-match-item') || 
                              clickedElement.closest('.bg-[rgb(18,29,47)]') ||
                              clickedElement.closest('[onclick*="showMobileLiveOdds"]') ||
                              document.querySelector(`.live-match-item[data-match-id="${matchId}"]`);
            
            if (!matchElement) {
                // If we can't find the element, create a temporary one
                matchElement = document.createElement('div');
                matchElement.dataset.matchId = matchId;
            }
            
            // Remove selected class from all live match items
            document.querySelectorAll('.live-match-item, .bg-[rgb(18,29,47)]').forEach(item => {
                item.classList.remove('selected');
            });
            
            // Add selected class to clicked item
            matchElement.classList.add('selected');
            
            // Call the existing function to show live odds
            showMobileLiveOdds(matchId, matchElement);
        }

        function addLiveBet(betType, odds, eventId) {
            // Find the live match data
            const liveMatch = liveMatches.find(match => match.mac_id === eventId);
            if (!liveMatch) {
                showToast('Maç bulunamadı!', 'error');
                return;
            }
            
            // Create bet object for live match
            const bet = {
                eventId: eventId,
                betType: betType,
                selection: betType === '1' ? 'Ev Sahibi' : betType === 'X' ? 'Beraberlik' : 'Deplasman',
                odds: parseFloat(odds),
                market: 'Maç Sonucu',
                matchInfo: {
                    home: liveMatch.evsahibi_isim,
                    away: liveMatch.misafir_isim,
                    league: liveMatch.lig,
                    date: formatDateTime(liveMatch.baslangic),
                    time: liveMatch.dakika ? `${liveMatch.dakika}'` : 'Canlı'
                }
            };
            
            // Check if bet already exists for this match
            const existingBetIndex = selectedBets.findIndex(b => b.eventId === eventId && b.market === 'Maç Sonucu');
            if (existingBetIndex !== -1) {
                selectedBets[existingBetIndex] = bet; // Update existing bet
                showToast('Canlı bahis güncellendi!', 'info');
            } else {
                selectedBets.push(bet); // Add new bet
                showToast('Canlı bahis eklendi!', 'success');
            }
            
            localStorage.setItem('selectedBets', JSON.stringify(selectedBets));
            
            // Save to localStorage using the standard function
            saveBettingSlipToStorage();
            
            // Update displays
            updateMobileSlip();
            updateMobileFooterCounts();
        }
        
        function showLiveMatchesInMainList() {
            const matchesContainer = document.getElementById('mobile-matches-list');
            if (!matchesContainer) return;
            
            // Show the matches container
            matchesContainer.style.display = 'block';
            
            if (liveMatches.length === 0) {
                matchesContainer.innerHTML = `
                    <div class="px-4 py-8 text-center">
                        <div class="text-[#6a6a6a] text-sm">
                            <i class="fas fa-play text-2xl mb-2 block"></i>
                            <p>Canlı maç bulunamadı</p>
                            <p class="text-xs mt-1">Şu anda canlı maç yok</p>
                        </div>
                    </div>
                `;
                return;
            }
            
            let html = '<div class="space-y-4 p-4">';
            
            liveMatches.forEach(match => {
                const score = match.skor || '0-0';
                const [homeScore, awayScore] = score.split('-').map(s => s.trim());
                const isLive = match.oynuyormu === '1';
                const liveText = isLive ? `${match.dakika || '0'}'` : 'Başlamadı';
                const liveColor = isLive ? 'text-red-600' : 'text-gray-400';
                
                html += `
                    <div class="bg-[#2a2a2a] rounded-lg shadow-lg border border-gray-700">
                        <div class="w-full flex items-center justify-between px-4 py-3 border-b border-gray-700 cursor-pointer hover:bg-[#3a3a3a] transition-colors" onclick="showMobileLiveOdds('${match.mac_id}', this)">
                            <div class="flex items-center space-x-2 text-sm font-semibold text-gray-300">
                                <img alt="${match.ulke || 'Bilinmeyen'} bayrağı" class="w-4 h-3 object-cover rounded-sm flex-shrink-0" height="12" src="${getCountryFlag(match.ulke || 'Bilinmeyen')}" width="16" onerror="this.src='/images/flags/defaults.png'"/>
                                ${match.ulke && match.lig && match.ulke !== match.lig ? 
                                    `<span class="truncate">${match.ulke}</span>
                                     <span class="ml-1 font-normal truncate">${match.lig}</span>` :
                                    `<span class="truncate">${match.lig || match.ulke || 'Bilinmeyen Lig'}</span>`
                                }
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs ${liveColor} font-semibold">
                                    <i class="fas fa-circle text-[6px]"></i>
                                    ${liveText}
                                </span>
                                <i class="fas fa-chevron-up text-gray-400"></i>
                            </div>
                        </div>
                        <div class="px-4 py-3 text-xs text-gray-400 space-y-3">
                            <div class="text-gray-500">${formatDateTime(match.baslangic)}</div>
                            
                            <div class="flex items-center justify-between text-gray-300 font-semibold">
                                <div class="flex-1">Maç Sonucu</div>
                                <div class="text-xs text-gray-500">Canlı</div>
                            </div>
                            
                            <div class="grid grid-cols-7 gap-2 text-xs text-gray-300 font-semibold">
                                <div class="col-span-2 flex flex-col space-y-2">
                                    <div class="flex justify-between items-center">
                                        <span class="truncate">${match.evsahibi_isim}</span>
                                        <span class="text-yellow-400 font-bold ml-2">${homeScore}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="truncate">${match.misafir_isim}</span>
                                        <span class="text-yellow-400 font-bold ml-2">${awayScore}</span>
                                    </div>
                                </div>
                                <button class="col-span-2 bg-[rgb(11,20,34)] rounded text-yellow-400 px-2 py-2 flex items-center justify-center hover:bg-[#4a4a4a] transition-colors duration-200" onclick="event.stopPropagation(); addLiveBet('1', ${match.oran1 || 2.0}, '${match.mac_id}')">
                                    ${match.oran1 || '2.00'}
                                </button>
                                <button class="col-span-1 bg-[rgb(11,20,34)] rounded text-yellow-400 px-2 py-2 flex items-center justify-center hover:bg-[#4a4a4a] transition-colors duration-200" onclick="event.stopPropagation(); addLiveBet('X', ${match.oranX || 3.0}, '${match.mac_id}')">
                                    ${match.oranX || '3.00'}
                                </button>
                                <button class="col-span-2 bg-[rgb(11,20,34)] rounded text-yellow-400 px-2 py-2 flex items-center justify-center hover:bg-[#4a4a4a] transition-colors duration-200" onclick="event.stopPropagation(); addLiveBet('2', ${match.oran2 || 3.0}, '${match.mac_id}')">
                                    ${match.oran2 || '3.00'}
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            matchesContainer.innerHTML = html;
        }
        
        function showAllLiveMatches() {
            const matchesContainer = document.getElementById('mobile-matches-list');
            if (!matchesContainer) return;
            
            // Update active sport button to "Tümü" (bulletin style)
            const sportButtons = document.querySelectorAll('#mobile-sports button');
            sportButtons.forEach(btn => {
                btn.classList.remove('bg-[#1a2a4a]');
                btn.classList.add('bg-[#192231]');
            });
            
            const allButton = document.querySelector('#mobile-sports button[data-sport="all"]');
            if (allButton) {
                allButton.classList.remove('bg-[#192231]');
                allButton.classList.add('bg-[#1a2a4a]');
            }
            
            if (liveMatches.length === 0) {
                matchesContainer.innerHTML = `
                    <div class="px-4 py-8 text-center">
                        <div class="text-[#6a6a6a] text-sm">
                            <i class="fas fa-play text-2xl mb-2 block"></i>
                            <p>Canlı maç bulunamadı</p>
                            <p class="text-xs mt-1">Şu anda canlı maç yok</p>
                        </div>
                    </div>
                `;
                return;
            }
            
            // Sort matches: Turkey first, then Futbol, then others
            const sortedMatches = [...liveMatches].sort((a, b) => {
                // Check if country is Turkey/Türkiye
                const aIsTurkey = (a.ulke && (a.ulke.toLowerCase().includes('turkey') || a.ulke.toLowerCase().includes('türkiye')));
                const bIsTurkey = (b.ulke && (b.ulke.toLowerCase().includes('turkey') || b.ulke.toLowerCase().includes('türkiye')));
                
                // Turkey matches first
                if (aIsTurkey && !bIsTurkey) return -1;
                if (!aIsTurkey && bIsTurkey) return 1;
                
                // If both or neither are Turkey, then Futbol first
                if (a.tur === 'Futbol' && b.tur !== 'Futbol') return -1;
                if (a.tur !== 'Futbol' && b.tur === 'Futbol') return 1;
                return 0;
            });
            
            let html = '';
            sortedMatches.forEach(match => {
                const score = match.skor || '0-0';
                const [homeScore, awayScore] = score.split('-').map(s => s.trim());
                const isLive = match.oynuyormu === '1';
                const liveText = isLive ? `${match.dakika || '0'}'` : 'Başlamadı';
                const liveColor = isLive ? 'text-red-600' : 'text-gray-400';
                
                html += `
                    <div class="bg-[rgb(18,29,47)] rounded mb-4 cursor-pointer hover:bg-[rgb(25,40,60)] transition-colors" onclick="showMobileLiveOdds('${match.mac_id}', this)">
                        <div class="w-full flex items-center justify-between px-4 py-3 border-b border-gray-700">
                            <div class="flex items-center space-x-2 text-sm font-semibold text-gray-300">
                                <img alt="${match.ulke || 'Bilinmeyen'} bayrağı" class="w-4 h-3 object-cover rounded-sm flex-shrink-0" height="12" src="${getCountryFlag(match.ulke || 'Bilinmeyen')}" width="16" onerror="this.src='/images/flags/defaults.png'"/>
                                ${match.ulke && match.lig && match.ulke !== match.lig ? 
                                    `<span class="truncate">${match.ulke}</span>
                                     <span class="ml-1 font-normal truncate">${match.lig}</span>` :
                                    `<span class="truncate">${match.lig || match.ulke || 'Bilinmeyen Lig'}</span>`
                                }
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs ${liveColor} font-semibold">
                                    <i class="fas fa-circle text-[6px]"></i>
                                    ${liveText}
                                </span>
                                <i class="fas fa-chevron-up text-gray-400"></i>
                            </div>
                        </div>
                        <div class="px-4 py-3 text-xs text-gray-400 space-y-3">
                            <div class="text-gray-500">${formatDateTime(match.baslangic)}</div>
                            
                            <div class="flex items-center justify-between text-gray-300 font-semibold">
                                <div class="flex-1">Maç Sonucu</div>
                                <div class="text-xs text-gray-500">Canlı</div>
                            </div>
                            
                            <div class="grid grid-cols-7 gap-2 text-xs text-gray-300 font-semibold">
                                <div class="col-span-2 flex flex-col space-y-2">
                                    <div class="flex justify-between items-center">
                                        <span class="truncate">${match.evsahibi_isim}</span>
                                        <span class="text-yellow-400 font-bold ml-2">${homeScore}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="truncate">${match.misafir_isim}</span>
                                        <span class="text-yellow-400 font-bold ml-2">${awayScore}</span>
                                    </div>
                                </div>
                                <button class="col-span-2 bg-[rgb(11,20,34)] rounded text-yellow-400 px-2 py-2 flex items-center justify-center hover:bg-[#4a4a4a] transition-colors duration-200" onclick="event.stopPropagation(); addLiveBet('1', ${match.oran1 || 2.0}, '${match.mac_id}')">
                                    ${match.oran1 || '2.00'}
                                </button>
                                <button class="col-span-1 bg-[rgb(11,20,34)] rounded text-yellow-400 px-2 py-2 flex items-center justify-center hover:bg-[#4a4a4a] transition-colors duration-200" onclick="event.stopPropagation(); addLiveBet('X', ${match.oranX || 3.0}, '${match.mac_id}')">
                                    ${match.oranX || '3.00'}
                                </button>
                                <button class="col-span-2 bg-[rgb(11,20,34)] rounded text-yellow-400 px-2 py-2 flex items-center justify-center hover:bg-[#4a4a4a] transition-colors duration-200" onclick="event.stopPropagation(); addLiveBet('2', ${match.oran2 || 3.0}, '${match.mac_id}')">
                                    ${match.oran2 || '3.00'}
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            // Replace addLiveBet calls with showMobileLiveOdds calls
            html = html.replace(/onclick="addLiveBet\('([^']+)', [^,]+,\s*'([^']+)'\)"/g, 
                               'onclick="showMobileLiveOdds(\'$2\', this)"');
            
            matchesContainer.innerHTML = html;
        }
        
        async function placeMobileBet() {
            
            
            
            
            // Log each bet details
            selectedBets.forEach((bet, index) => {
            });
            
            if (selectedBets.length === 0) {
                showToast('Lütfen en az bir maç seçin!', 'error');
                return;
            }
            
            const couponAmountInput = document.getElementById('mobile-coupon-amount');
            const couponAmount = parseFloat(couponAmountInput.value);
            
            
            
            if (!couponAmount || couponAmount <= 0) {
                showToast('Lütfen geçerli bir kupon miktarı girin!', 'error');
                return;
            }
            
            // Check if any bet is live
            const hasLiveBet = selectedBets.some(bet => {
                return (bet.matchInfo && bet.matchInfo.isLive === true) ||
                       (bet.eventId && !matchData[bet.eventId] && !bet.eventId.toString().startsWith('live_'));
            });
            
            if (hasLiveBet) {
                
                await validateMobileLiveBetOdds();
            } else {
                
                await processMobileBetPlacement();
            }
        }
        
        // Validate mobile live bet odds before placing bet
        async function validateMobileLiveBetOdds() {
            const betButton = document.getElementById('mobile-place-bet');
            const originalText = betButton.innerHTML;
            
            try {
                // Show loading state
                betButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>CANLI ORANLAR KONTROL EDİLİYOR...';
                betButton.disabled = true;
                betButton.className = 'w-full bg-[#f59e0b] text-black text-sm font-semibold py-4 rounded-lg transition-all duration-300 font-bold';
                
                const validationResults = [];
                let hasOddsChange = false;
                let hasSelectionUnavailable = false;
                
                // Check each live bet
                for (let i = 0; i < selectedBets.length; i++) {
                    const bet = selectedBets[i];
                    
                    // Only check live bets
                    if ((bet.matchInfo && bet.matchInfo.isLive === true) ||
                        (bet.eventId && !matchData[bet.eventId] && !bet.eventId.toString().startsWith('live_'))) {
                        
                        
                        
                        // Get the real match ID (remove live_ prefix if exists)
                        let realMatchId = bet.eventId.toString();
                        if (realMatchId.startsWith('live_')) {
                            realMatchId = realMatchId.replace('live_', '');
                        }
                        
                        try {
                            
                            const response = await fetch(`/Live/Odds/${realMatchId}`);
                            
                            if (!response.ok) {
                                throw new Error(`API response not ok: ${response.status} ${response.statusText}`);
                            }
                            
                            const oddsData = await response.json();
                            
                            
                            if (oddsData.success && (oddsData.main_odds || oddsData.additional_odds)) {
                                let currentOdds = null;
                                let betType = null;
                                let newOdds = null;
                                
                                // Determine bet type and get current odds
                                if (bet.selection === bet.matchInfo.home) {
                                    betType = '1';
                                    currentOdds = bet.odds;
                                    if (oddsData.main_odds && oddsData.main_odds.odds1) {
                                        newOdds = oddsData.main_odds.odds1;
                                    }
                                } else if (bet.selection === bet.matchInfo.away) {
                                    betType = '2';
                                    currentOdds = bet.odds;
                                    if (oddsData.main_odds && oddsData.main_odds.odds2) {
                                        newOdds = oddsData.main_odds.odds2;
                                    }
                                } else if (bet.selection === 'Berabere') {
                                    betType = 'X';
                                    currentOdds = bet.odds;
                                    if (oddsData.main_odds && oddsData.main_odds.oddsX) {
                                        newOdds = oddsData.main_odds.oddsX;
                                    }
                                }
                                
                                if (newOdds !== null && newOdds !== currentOdds) {
                                    hasOddsChange = true;
                                    validationResults.push({
                                        match: bet.match,
                                        oldOdds: currentOdds,
                                        newOdds: newOdds,
                                        betType: betType
                                    });
                                }
                                
                                if (newOdds === null) {
                                    hasSelectionUnavailable = true;
                                    validationResults.push({
                                        match: bet.match,
                                        selection: bet.selection,
                                        betType: betType,
                                        unavailable: true
                                    });
                                }
                            }
                        } catch (error) {
                            validationResults.push({
                                match: bet.match,
                                error: error.message
                            });
                        }
                    }
                }
                
                // Process validation results
                if (hasOddsChange || hasSelectionUnavailable) {
                    let message = 'Canlı oranlarda değişiklik tespit edildi:\n\n';
                    
                    validationResults.forEach(result => {
                        if (result.unavailable) {
                            message += `${result.match} - ${result.selection} seçeneği artık mevcut değil\n`;
                        } else if (result.oldOdds !== result.newOdds) {
                            message += `${result.match} - ${result.betType}: ${result.oldOdds} → ${result.newOdds}\n`;
                        }
                    });
                    
                    message += '\nLütfen kuponunuzu güncelleyin.';
                    
                    // Reset button
                    betButton.innerHTML = originalText;
                    betButton.disabled = false;
                    betButton.className = 'w-full bg-[#f7931e] text-black text-sm font-semibold py-4 rounded-lg hover:bg-[#e67e22] transition-all duration-300 font-bold';
                    
                    showToast(message, 'error');
                    return;
                }
                
                // All validations passed, proceed with bet placement
                
                await processMobileBetPlacement();
                
            } catch (error) {
                
                // Reset button
                betButton.innerHTML = originalText;
                betButton.disabled = false;
                betButton.className = 'w-full bg-[#f7931e] text-black text-sm font-semibold py-4 rounded-lg hover:bg-[#e67e22] transition-all duration-300 font-bold';
                
                showToast('Canlı oran kontrolü sırasında hata oluştu. Lütfen tekrar deneyin.', 'error');
            }
        }
        
        // Process mobile bet placement
        async function processMobileBetPlacement() {
            const couponAmountInput = document.getElementById('mobile-coupon-amount');
            const couponAmount = parseFloat(couponAmountInput.value);
            
            // Calculate total odds
            const totalOdds = selectedBets.reduce((total, bet) => total * bet.odds, 1);
            
            // Prepare bet data
            const betData = {
                bets: selectedBets,
                total_amount: couponAmount,
                total_odds: totalOdds
            };
            
            try {
                // Show loading state
                const betButton = document.getElementById('mobile-place-bet');
                const originalText = betButton.innerHTML;
                betButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>KUPON YATIRILIYOR...';
                betButton.disabled = true;
                betButton.className = 'w-full bg-[#f59e0b] text-black text-sm font-semibold py-4 rounded-lg transition-all duration-300 font-bold';
                
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                // Try to get secure token from URL parameters
                const urlParams = new URLSearchParams(window.location.search);
                const secureToken = urlParams.get('secure_token');
                
                // Also try to get secure token from meta tag (set by callback)
                const metaSecureToken = document.querySelector('meta[name="secure-token"]')?.getAttribute('content');
                const finalSecureToken = secureToken || metaSecureToken;
                
                // Prepare headers
                const headers = {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                    'X-Requested-With': 'XMLHttpRequest'
                };
                
                // Add secure token to headers if available
                if (finalSecureToken) {
                    headers['X-Secure-Token'] = finalSecureToken;
                }
                
                // For mobile users, also add user data to request body
                if (isMobileDevice()) {
                    betData.username = '{{ session("callback_username") }}' || '{{ session("username") }}';
                    betData.user_id = '{{ session("callback_user_id") }}' || '{{ session("user_id") }}';
                    betData.agent_code = '{{ session("callback_agent_code") }}' || '{{ session("agent_code") }}';
                    
                    console.log('Mobile bet data being sent:', {
                        username: betData.username,
                        user_id: betData.user_id,
                        agent_code: betData.agent_code,
                        secure_token: finalSecureToken
                    });
                }
                
                const response = await fetch('/api/place-bet-slip', {
                    method: 'POST',
                    headers: headers,
                    body: JSON.stringify(betData)
                });
                
                
                
                
                const result = await response.json();
                
                
                if (result.success) {
                    // Success - Show green "KUPON BAŞARIYLA YATIRILDI" on button
                    betButton.innerHTML = '<i class="fas fa-check mr-2"></i>KUPON BAŞARIYLA YATIRILDI';
                    betButton.style.backgroundColor = '#10b981'; // Green color
                    betButton.style.color = 'white';
                    
                    // Show success toast
                    showToast('Kupon başarıyla yatırıldı!', 'success');
                    
                    // Clear the betting slip
                    selectedBets = [];
                    updateMobileSlip();
                    couponAmountInput.value = '';
                    
                    // Close mobile slip
                    closeMobileSlip();
                    

                    
                    // Update footer counts
                    await updateMobileFooterCounts();
                    
                    // Reset button after 3 seconds
                    setTimeout(() => {
                        betButton.innerHTML = originalText;
                        betButton.style.backgroundColor = '';
                        betButton.style.color = '';
                        betButton.disabled = true;
                        betButton.className = 'w-full bg-[#2a2a2a] text-gray-600 text-sm font-semibold py-4 rounded-lg cursor-not-allowed transition-all duration-300';
                    }, 3000);
                    
                } else {
                    // Error - Check if it's a balance error or started matches error
                    if (result.error && result.error.includes('Hay Aksi')) {
                        // Show balance error on button
                        betButton.innerHTML = result.error;
                        betButton.style.backgroundColor = '#ef4444'; // Red color
                        betButton.style.color = 'white';
                        
                        // Reset button after 4 seconds
                        setTimeout(() => {
                            betButton.innerHTML = originalText;
                            betButton.style.backgroundColor = '';
                            betButton.style.color = '';
                            betButton.disabled = false;
                            betButton.className = 'w-full bg-[#f7931e] text-black text-sm font-semibold py-4 rounded-lg hover:bg-[#e67e22] transition-all duration-300 font-bold';
                        }, 4000);
                        
                    } else if (result.error && result.error.includes('başlamış maçlar') && result.action === 'remove_started_matches') {
                        // Remove started matches from bet slip
                        
                        
                        // Remove started matches from selectedBets
                        result.started_matches.forEach(startedMatch => {
                            selectedBets = selectedBets.filter(bet => bet.eventId !== startedMatch.eventId);
                        });
                        
                        // Update bet slip display
                        updateMobileSlip();
                        
                        // Update footer counts
                        await updateMobileFooterCounts();
                        
                        // Show error message on button
                        betButton.innerHTML = result.error;
                        betButton.style.backgroundColor = '#ef4444'; // Red color
                        betButton.style.color = 'white';
                        
                        // Show toast notification
                        showToast(`${result.started_matches.length} başlamış maç kupondan kaldırıldı`, 'warning');
                        
                        // Reset button after 4 seconds
                        setTimeout(() => {
                            betButton.innerHTML = originalText;
                            betButton.style.backgroundColor = '';
                            betButton.style.color = '';
                            betButton.disabled = false;
                            betButton.className = 'w-full bg-[#f7931e] text-black text-sm font-semibold py-4 rounded-lg hover:bg-[#e67e22] transition-all duration-300 font-bold';
                        }, 4000);
                        
                    } else {
                        // Show detailed error message
                        let errorMessage = result.error || 'Kupon yatırılırken hata oluştu!';
                        
                        // Check if it's a specific match error
                        if (result.error && result.error.includes('maç')) {
                            // Extract match information from the error
                            const matchError = result.error;
                            betButton.innerHTML = matchError;
                        } else {
                            betButton.innerHTML = errorMessage;
                        }
                        
                        betButton.style.backgroundColor = '#ef4444'; // Red color
                        betButton.style.color = 'white';
                        
                        // Reset button after 4 seconds
                        setTimeout(() => {
                            betButton.innerHTML = originalText;
                            betButton.style.backgroundColor = '';
                            betButton.style.color = '';
                            betButton.disabled = false;
                            betButton.className = 'w-full bg-[#f7931e] text-black text-sm font-semibold py-4 rounded-lg hover:bg-[#e67e22] transition-all duration-300 font-bold';
                        }, 4000);
                    }
                    
                    // Show error toast with detailed message
                    let toastMessage = result.error || 'Kupon yatırılırken hata oluştu!';
                    
                    // If it's a started match error, show specific message
                    if (result.error && result.error.includes('başlamış maçlar')) {
                        toastMessage = 'Başlamış maçlar kupondan kaldırıldı. Lütfen tekrar deneyin.';
                    }
                    
                    showToast(toastMessage, 'error');
                }
                
            } catch (error) {
                
                // Reset button
                const betButton = document.getElementById('mobile-place-bet');
                betButton.innerHTML = originalText;
                betButton.disabled = false;
                betButton.className = 'w-full bg-[#f7931e] text-black text-sm font-semibold py-4 rounded-lg hover:bg-[#e67e22] transition-all duration-300 font-bold';
                
                showToast('Kupon yatırılırken hata oluştu! Lütfen tekrar deneyin.', 'error');
            }
        }
        
        // Function to detect if we're in an iframe
        function isInIframe() {
            try {
                return window.self !== window.top;
            } catch (e) {
                return true;
            }
        }

        // Function to detect if we should show mobile view
        function shouldShowMobileView() {
            // If we're in an iframe, always show mobile view for better compatibility
            if (isInIframe()) {
                return true;
            }
            
            // Otherwise, use the original logic
            return window.innerWidth < 1024;
        }

        // Function to detect if this is a mobile device (for API calls)
        function isMobileDevice() {
            // If we're in an iframe, treat as mobile for API compatibility
            if (isInIframe()) {
                return true;
            }
            
            // Otherwise, use the original logic
            return window.innerWidth <= 768 || /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        }
        
        // Initialize mobile view - OPTIMIZED
        document.addEventListener('DOMContentLoaded', function() {
            if (shouldShowMobileView()) {
                // Show loading state immediately
                showMobileLoadingState();
                
                // Use requestAnimationFrame for smooth loading
                requestAnimationFrame(() => {
                    // Load betting slip from localStorage for mobile
                    loadBettingSlipFromStorage();
                    
                    // Populate sports first
                    populateMobileSports(); // Populate sports dynamically
                    
                    // Load list view preference and apply it
                    loadMobileListViewPreference();
                    
                    // Only populate leagues if list view is not active
                    if (!isListViewActive) {
                        populateMobileLeagues();
                    }
                    
                    // Update mobile slip display after loading from storage
                    updateMobileSlip();
                    
                    // Hide loading state
                    hideMobileLoadingState();
                    
                    // Update footer counts asynchronously
                    setTimeout(() => {
                        updateMobileFooterCounts();
                    }, 100);
                });
            }
        });
        
        // Place mobile bet function with odds validation
        async function placeMobileBet() {
            
            
            
            if (selectedBets.length === 0) {
                showToast('Lütfen en az bir maç seçin!', 'error');
                return;
            }
            
            const betAmountInput = document.getElementById('mobile-coupon-amount');
            const betAmount = parseFloat(betAmountInput.value);
            
            if (!betAmount || betAmount <= 0) {
                showToast('Lütfen geçerli bir bahis miktarı girin!', 'error');
                return;
            }
            
            // Check if any bet is live
            const hasLiveBet = selectedBets.some(bet => {
                return (bet.matchInfo && bet.matchInfo.isLive === true) ||
                       (bet.eventId && bet.eventId.toString().startsWith('live_'));
            });
            
            if (hasLiveBet) {
                
                await validateMobileLiveBetOdds();
            } else {
                
                await processMobileBetPlacement();
            }
        }
        
        // Validate mobile live bet odds before placing bet
        async function validateMobileLiveBetOdds() {
            const betButton = document.getElementById('mobile-place-bet');
            const originalText = betButton.textContent;
            
            try {
                // Show loading state
                betButton.textContent = 'CANLI ORANLAR KONTROL EDİLİYOR...';
                betButton.disabled = true;
                betButton.className = 'w-full bg-[#f59e0b] text-[#1e1e1e] text-sm font-semibold py-3 rounded-md transition-all duration-300';
                
                const validationResults = [];
                let hasOddsChange = false;
                let hasSelectionUnavailable = false;
                
                // Check each live bet
                for (let i = 0; i < selectedBets.length; i++) {
                    const bet = selectedBets[i];
                    
                    // Only check live bets
                    if ((bet.matchInfo && bet.matchInfo.isLive === true) ||
                        (bet.eventId && bet.eventId.toString().startsWith('live_'))) {
                        
                        
                                                                
                                        
                                        
                                        
                                        
                                        
                        
                        // Get the real match ID (remove live_ prefix if exists)
                        let realMatchId = bet.eventId.toString();
                        if (realMatchId.startsWith('live_')) {
                            // Extract match ID from eventId format: live_${matchId}_${marketName}_${selectionId}
                            const parts = realMatchId.split('_');
                            if (parts.length >= 2) {
                                realMatchId = parts[1];
                            }
                        }
                        
                        try {
                            
                            const response = await fetch(`/Live/Odds/${realMatchId}`);
                            
                            if (!response.ok) {
                                throw new Error(`API response not ok: ${response.status} ${response.statusText}`);
                            }
                            
                            const oddsData = await response.json();
                            
                            
                            if (oddsData.success && (oddsData.main_odds || oddsData.additional_odds)) {
                                let currentOdds = null;
                                let betType = null;
                                let newOdds = null;
                                
                                // First check main_odds for basic selections
                                if (oddsData.main_odds) {
                                    if (bet.selection === 'Evsahibi Kazanır' || bet.selection === '1') {
                                        betType = '1';
                                        currentOdds = oddsData.main_odds;
                                    } else if (bet.selection === 'Deplasman Kazanır' || bet.selection === '2') {
                                        betType = '2';
                                        currentOdds = oddsData.main_odds;
                                    } else if (bet.selection === 'Berabere' || bet.selection === 'X') {
                                        betType = 'X';
                                        currentOdds = oddsData.main_odds;
                                    }
                                }
                                
                                // If not found in main_odds, check additional_odds
                                if (!currentOdds && oddsData.additional_odds) {
                                    
                                    
                                    
                                    // Check each additional market
                                    for (const [marketName, marketOdds] of Object.entries(oddsData.additional_odds)) {
                                        
                                        
                                        
                                        
                                        // Normalize both strings for comparison (remove accents, convert to lowercase)
                                        const normalizeString = (str) => {
                                            return str.toLowerCase()
                                                .normalize('NFD')
                                                .replace(/[\u0300-\u036f]/g, '') // Remove diacritics
                                                .replace(/[ğ]/g, 'g')
                                                .replace(/[ü]/g, 'u')
                                                .replace(/[ş]/g, 's')
                                                .replace(/[ı]/g, 'i')
                                                .replace(/[ö]/g, 'o')
                                                .replace(/[ç]/g, 'c');
                                        };
                                        
                                        const normalizedBetSelection = normalizeString(bet.selection);
                                        const normalizedAvailableSelection = normalizeString(Object.keys(marketOdds).join('|'));
                                        
                                        
                                        
                                        
                                        // Try exact match first
                                        if (marketOdds[bet.selection]) {
                                            currentOdds = marketOdds;
                                            betType = bet.selection;
                                            
                                            break;
                                        }
                                        
                                        // Try to find by market name similarity
                                        const normalizeMarketName = (str) => str.toLowerCase()
                                            .replace(/\s+/g, '')           // Boşlukları kaldır
                                            .replace(/[ğüşıöç]/g, 'gusio')  // Türkçe karakterleri normalize et
                                            .replace(/[-_]/g, '');          // Tire ve alt çizgileri kaldır
                                        
                                        const normalizedBetMarket = normalizeMarketName(bet.market);
                                        const normalizedApiMarket = normalizeMarketName(marketName);
                                        
                                        
                                        
                                        
                                        
                                        
                                        if (normalizedBetMarket === normalizedApiMarket) {
                                            
                                            
                                            // Now check if selection exists in this market
                                            if (marketOdds[bet.selection]) {
                                                currentOdds = marketOdds;
                                                betType = bet.selection;
                                                
                                                break;
                                            } else {
                                                
                                                
                                                
                                                // Try partial match for selection
                                                for (const [availableSelection, odds] of Object.entries(marketOdds)) {
                                                    if (availableSelection.includes(bet.selection) || bet.selection.includes(availableSelection)) {
                                                        
                                                        currentOdds = marketOdds;
                                                        betType = availableSelection;
                                                        
                                                        break;
                                                    }
                                                }
                                                
                                                if (currentOdds) break;
                                            }
                                        }
                                        
                                        // Try normalized match
                                        for (const [availableSelection, odds] of Object.entries(marketOdds)) {
                                            if (normalizeString(availableSelection) === normalizedBetSelection) {
                                                currentOdds = marketOdds;
                                                betType = availableSelection; // Use the actual available selection name
                                                
                                                break;
                                            }
                                        }
                                        
                                        if (currentOdds) break;
                                    }
                                    
                                    if (!currentOdds) {
                                        
                                        
                                        Object.entries(oddsData.additional_odds).forEach(([marketName, marketOdds]) => {
                                            
                                        });
                                    }
                                }
                                
                                
                                
                                
                                
                                if (currentOdds && betType) {
                                    const oldOdds = bet.odds;
                                    newOdds = currentOdds[betType];
                                    
                                    
                                    
                                    
                                    if (newOdds > oldOdds) {
                                        // Odds increased - update the bet with new higher odds
                                        selectedBets[i].odds = newOdds;
                                        validationResults.push({
                                            bet: bet,
                                            status: 'increased',
                                            oldOdds: oldOdds,
                                            newOdds: newOdds
                                        });
                                        hasOddsChange = true;
                                        
                                    } else if (newOdds < oldOdds) {
                                        // Odds decreased - update the bet with new lower odds
                                        selectedBets[i].odds = newOdds;
                                        validationResults.push({
                                            bet: bet,
                                            status: 'decreased',
                                            oldOdds: oldOdds,
                                            newOdds: newOdds
                                        });
                                        hasOddsChange = true;
                                        
                                    } else {
                                        // Odds unchanged
                                        validationResults.push({
                                            bet: bet,
                                            status: 'unchanged',
                                            odds: oldOdds
                                        });
                                        
                                    }
                                } else {
                                    // Selection not available
                                    
                                    
                                    
                                    
                                    // Try to find similar selections (case-insensitive, partial match)
                                    let foundSimilar = false;
                                    if (oddsData.additional_odds) {
                                        for (const [marketName, marketOdds] of Object.entries(oddsData.additional_odds)) {
                                            for (const [availableSelection, odds] of Object.entries(marketOdds)) {
                                                if (availableSelection.toLowerCase().includes(bet.selection.toLowerCase()) || 
                                                    bet.selection.toLowerCase().includes(availableSelection.toLowerCase())) {
                                                    
                                                    foundSimilar = true;
                                                }
                                            }
                                        }
                                    }
                                    
                                    validationResults.push({
                                        bet: bet,
                                        status: 'unavailable',
                                        selection: bet.selection,
                                        availableMainOdds: oddsData.main_odds,
                                        availableAdditionalOdds: oddsData.additional_odds
                                    });
                                    hasSelectionUnavailable = true;
                                }
                            } else {
                                // API error
                                validationResults.push({
                                    bet: bet,
                                    status: 'api_error',
                                    error: 'Oranlar alınamadı'
                                });
                            }
                            
                            // Wait 1 second between requests
                            await new Promise(resolve => setTimeout(resolve, 1000));
                            
                        } catch (error) {
                            validationResults.push({
                                bet: bet,
                                status: 'error',
                                error: error.message,
                                matchId: realMatchId
                            });
                        }
                    } else {
                        // Non-live bet
                        validationResults.push({
                            bet: bet,
                            status: 'non_live'
                        });
                    }
                }
                
                // Process validation results
                if (hasSelectionUnavailable) {
                    const unavailableBets = validationResults.filter(r => r.status === 'unavailable');
                    const unavailableDetails = unavailableBets.map(bet => 
                        `${bet.bet.selection} (${bet.bet.market})`
                    ).join(', ');
                    
                    showToast(`Seçenek bulunamadı: ${unavailableDetails}. Lütfen kuponunuzu güncelleyin.`, 'error');
                    return;
                }
                
                if (hasOddsChange) {
                    const decreasedBets = validationResults.filter(r => r.status === 'decreased');
                    const increasedBets = validationResults.filter(r => r.status === 'increased');
                    
                    if (decreasedBets.length > 0) {
                        
                        
                        const decreasedDetails = decreasedBets.map(bet => 
                            `${bet.bet.selection}: ${bet.oldOdds} → ${bet.newOdds}`
                        ).join(', ');
                        
                        showToast(`Kuponunuzda düşen oranlar var: ${decreasedDetails}. Kuponunuz güncellendi.`, 'warning');
                        
                        // Force update the display
                        updateMobileSlip();
                        
                        return; // Don't proceed with bet placement
                    }
                    
                    if (increasedBets.length > 0) {
                        
                        updateMobileSlip();
                        
                        // Show success message for increased odds
                        const increasedDetails = increasedBets.map(bet => 
                            `${bet.bet.selection}: ${bet.oldOdds} → ${bet.newOdds}`
                        ).join(', ');
                        showToast(`${increasedBets.length} maçın oranı yükseldi: ${increasedDetails}. Yeni oranlarla devam ediliyor...`, 'success');
                        
                        // Wait 2 seconds then proceed with bet placement
                        setTimeout(async () => {
                            await processMobileBetPlacement();
                        }, 2000);
                        return;
                    }
                }
                
                // Proceed with bet placement
                await processMobileBetPlacement();
                
            } catch (error) {
                showToast('Canlı oran kontrolü sırasında hata oluştu: ' + error.message, 'error');
            } finally {
                // Reset button
                betButton.textContent = originalText;
                betButton.disabled = false;
                betButton.className = 'w-full bg-[#f7931e] text-black text-sm font-semibold py-3 rounded-md hover:bg-[#e67e22] transition-all duration-300';
            }
        }
        
        // Process mobile bet placement
        async function processMobileBetPlacement() {
            const betButton = document.getElementById('mobile-place-bet');
            const originalText = betButton.textContent;
            
            try {
                // Show loading state
                betButton.textContent = 'BAHİS YATIRILIYOR...';
                betButton.disabled = true;
                betButton.className = 'w-full bg-[#f59e0b] text-[#1e1e1e] text-sm font-semibold py-3 rounded-md transition-all duration-300';
                
                const betAmount = parseFloat(document.getElementById('mobile-coupon-amount').value);
                
                // Calculate total odds
                const totalOdds = selectedBets.reduce((total, bet) => total * bet.odds, 1);
                
                // Prepare bet data
                const betData = {
                    bets: selectedBets.map(bet => ({
                        eventId: bet.eventId,
                        market: bet.market,
                        selection: bet.selection,
                        odds: bet.odds,
                        matchInfo: bet.matchInfo
                    })),
                    total_amount: betAmount,
                    total_odds: totalOdds
                };
                
                
                
                // Submit bet
                const headers = {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                
                // Add secure token for mobile users
                const secureToken = new URLSearchParams(window.location.search).get('secure_token');
                if (secureToken) {
                    headers['X-Secure-Token'] = secureToken;
                    betData.secure_token = secureToken;
                }
                
                const response = await fetch('/api/place-bet-slip', {
                    method: 'POST',
                    headers: headers,
                    body: JSON.stringify(betData)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Success
                    showToast('Bahis başarıyla yatırıldı!', 'success');
                    
                    // Clear selected bets
                    selectedBets = [];
                    updateMobileSlip();
                    
                    // Close slip
                    closeMobileSlip();
                    
                    // Update footer counts
                    await updateMobileFooterCounts();
                    
                } else {
                    // Error
                    showToast(result.error || 'Bahis yatırılırken hata oluştu!', 'error');
                }
                
            } catch (error) {
                showToast('Bahis yatırılırken hata oluştu! Lütfen tekrar deneyin.', 'error');
            } finally {
                // Reset button
                betButton.textContent = originalText;
                betButton.disabled = false;
                betButton.className = 'w-full bg-[#f7931e] text-black text-sm font-semibold py-3 rounded-md hover:bg-[#e67e22] transition-all duration-300';
            }
        }
        
        // Instant coupon count update for performance
        function updateMobileCouponCount() {
            const couponCountElement = document.getElementById('mobile-coupon-count');
            if (couponCountElement) {
                couponCountElement.textContent = selectedBets.length;
                
                // Add visual feedback
                couponCountElement.style.transform = 'scale(1.2)';
                couponCountElement.style.color = '#f7931e';
                
                setTimeout(() => {
                    couponCountElement.style.transform = 'scale(1)';
                    couponCountElement.style.color = '';
                }, 200);
            }
        }
        
        // Update mobile footer counts
        async function updateMobileFooterCounts() {
            try {
                // Update live count from canlibulten table
                const liveResponse = await fetch('/api/live-matches');
                if (liveResponse.ok) {
                    const liveData = await liveResponse.json();
                    const liveCount = liveData.matches ? liveData.matches.length : 0;
                    document.getElementById('mobile-live-count').textContent = liveCount;
                } else {
                    document.getElementById('mobile-live-count').textContent = '0';
                }
                
                // Update bulletin count (pre-match matches from matchData)
                const bulletinCount = Object.values(matchData).filter(match => 
                    !match.isLive && !match.eventId.toString().startsWith('live_')
                ).length;
                document.getElementById('mobile-bulletin-count').textContent = bulletinCount;
                
                // Update coupon count (selected bets)
                const couponCount = selectedBets.length;
                document.getElementById('mobile-coupon-count').textContent = couponCount;
                
            } catch (error) {
                // Fallback to 0 if API call fails
                document.getElementById('mobile-live-count').textContent = '0';
                document.getElementById('mobile-bulletin-count').textContent = Object.values(matchData).length;
                document.getElementById('mobile-coupon-count').textContent = selectedBets.length;
            }
        }
        
        // User data is now retrieved from session
        
        // Mobile session restoration
        async function restoreMobileSession() {
            // Check if this is a mobile device
            const isMobile = isMobileDevice();
            
            if (isMobile) {
                const currentUsername = '{{ session("username") }}';
                const currentUserId = '{{ session("user_id") }}';
                const callbackUsername = '{{ session("callback_username") }}';
                const callbackUserId = '{{ session("callback_user_id") }}';
                
                // If we don't have current session data but have callback data, try to restore
                if ((!currentUsername || !currentUserId) && callbackUsername && callbackUserId) {
                    console.log('Mobile device detected with missing session data, attempting restoration...');
                    
                    try {
                        // Try to restore session from callback data
                        const response = await fetch('/api/restore-session', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                username: callbackUsername,
                                user_id: callbackUserId,
                                agent_code: '{{ session("callback_agent_code") }}'
                            })
                        });
                        
                        const result = await response.json();
                        
                        if (result.success) {
                            console.log('Session restored successfully:', result.session_data);
                            // Reload the page to get the updated session data
                            window.location.reload();
                        } else {
                            console.log('Session restoration failed:', result.message);
                        }
                    } catch (error) {
                        console.error('Session restoration error:', error);
                    }
                }
            }
        }
        
        // Call session restoration on page load
        document.addEventListener('DOMContentLoaded', function() {
            restoreMobileSession();
        });
        
        // Load open bets from API
        async function loadMobileOpenBets() {
            try {
                const openContainer = document.getElementById('mobile-open-bets-container');
                
                // Show loading state
                openContainer.innerHTML = `
                    <div class="px-3 py-8 text-center">
                        <div class="text-[#6a6a6a] text-sm">
                            <i class="fas fa-spinner fa-spin text-2xl mb-2 block"></i>
                            <p>Açık bahisler yükleniyor...</p>
                        </div>
                    </div>
                `;
                
                // User info is retrieved from session in the backend
                
                // Get secure token for mobile users
                const urlParams = new URLSearchParams(window.location.search);
                const secureToken = urlParams.get('secure_token');
                const metaSecureToken = document.querySelector('meta[name="secure-token"]')?.getAttribute('content');
                const finalSecureToken = secureToken || metaSecureToken;
                
                // Prepare headers
                const headers = {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Agent-Code': '{{ session("api_agent_code") }}',
                    'API-Secret-Key': '{{ session("api_secret_key") }}',
                    'API-Token': '{{ session("api_token") }}'
                };
                
                // Add secure token to headers if available
                if (finalSecureToken) {
                    headers['X-Secure-Token'] = finalSecureToken;
                }
                
                // Fetch open bets from API
                const response = await fetch('/api/open-bets', {
                    method: 'GET',
                    headers: headers
                });
                
                if (response.ok) {
                    const data = await response.json();
                    
                    if (data.success && data.bets && data.bets.length > 0) {
                        // Store all bets globally
                        allMobileBets = data.bets;
                        
                        // Reset filter and pagination
                        currentMobileFilter = 'ongoing';
                        currentMobilePage = 1;
                        
                        // Apply initial filter (show ongoing)
                        applyMobileFilter();
                    } else {
                        // No open bets
                        allMobileBets = [];
                        filteredMobileBets = [];
                        openContainer.innerHTML = `
                            <div class="px-3 py-8 text-center">
                                <div class="text-[#6a6a6a] text-sm">
                                    <i class="fas fa-ticket-alt text-2xl mb-2 block"></i>
                                    <p>Açık bahis bulunamadı</p>
                                    <p class="text-xs mt-1">Henüz bahis yapmadınız</p>
                                </div>
                            </div>
                        `;
                        document.getElementById('mobile-pagination').classList.add('hidden');
                    }
                    
                } else {
                    const errorText = await response.text();
                    throw new Error(`API response not ok: ${response.status} ${response.statusText} - ${errorText}`);
                }
                
            } catch (error) {
                
                // Show error state
                const openContainer = document.getElementById('mobile-open-bets-container');
                openContainer.innerHTML = `
                    <div class="px-3 py-8 text-center">
                        <div class="text-[#6a6a6a] text-sm">
                            <i class="fas fa-exclamation-triangle text-2xl mb-2 block text-red-500"></i>
                            <p>Açık bahisler yüklenemedi</p>
                            <p class="text-xs mt-1">Lütfen tekrar deneyin</p>
                            <button class="mt-3 bg-[#f7931e] text-black px-4 py-2 rounded text-sm font-semibold" onclick="loadMobileOpenBets()">
                                Tekrar Dene
                            </button>
                        </div>
                    </div>
                `;
                document.getElementById('mobile-pagination').classList.add('hidden');
            }
        }
        
        function formatMatchInfo(matchInfo) {
            if (!matchInfo || typeof matchInfo !== 'object') {
                return matchInfo || '';
            }
            
            let info = '';
            
            // Add home vs away
            if (matchInfo.home && matchInfo.away) {
                info += `${matchInfo.home} vs ${matchInfo.away}`;
            }
            
            // Add league
            if (matchInfo.league) {
                if (info) info += ', ';
                info += matchInfo.league;
            }
            
            // Add date
            if (matchInfo.date) {
                if (info) info += ', ';
                info += matchInfo.date;
            }
            
            // Add time (for live matches)
            if (matchInfo.time) {
                if (info) info += ', ';
                info += matchInfo.time;
            }
            
            return info || 'Maç bilgisi bulunamadı';
        }
        
        function toggleOpenBetDetails(index) {
            const detailsElement = document.getElementById(`bet-details-${index}`);
            const chevronElement = document.getElementById(`chevron-${index}`);
            
            if (detailsElement && chevronElement) {
                if (detailsElement.classList.contains('hidden')) {
                    // Aç
                    detailsElement.classList.remove('hidden');
                    chevronElement.classList.add('rotate-180');
                    chevronElement.style.transform = 'rotate(180deg)';
                } else {
                    // Kapat
                    detailsElement.classList.add('hidden');
                    chevronElement.classList.remove('rotate-180');
                    chevronElement.style.transform = 'rotate(0deg)';
                }
            }
        }
        
        function updateSportCounts() {
            const sports = ['futbol', 'basketbol', 'tenis', 'voleybol', 'masatenisi'];
            sports.forEach(sport => {
                const count = Object.values(matchData).filter(match => match.sport === sport).length;
                const countElement = document.getElementById(`${sport}-count`);
                if (countElement) {
                    countElement.textContent = count;
                }
            });
        }
    </script>
</body>
</html>
</html>

