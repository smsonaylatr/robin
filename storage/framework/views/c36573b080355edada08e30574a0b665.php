

<?php $__env->startSection('title', 'Spor Bahisleri - Betlucky'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-zinc-900 via-zinc-800 to-black">
    <div class="w-full h-screen">
        <iframe 
            src="" 
            class="w-full h-full border-0"
            frameborder="0"
            allowfullscreen
            id="sportsIframe">
        </iframe>

        <div id="loadingState" class="absolute inset-0 bg-zinc-900/90 flex items-center justify-center">
            <div class="text-center">
                <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-red-500 mx-auto mb-4"></div>
                <p class="text-white text-lg">Spor sistemi yükleniyor...</p>
                <p class="text-zinc-400 text-sm mt-2">Lütfen bekleyin</p>
            </div>
        </div>

        <div id="errorState" class="absolute inset-0 bg-zinc-900/90 flex items-center justify-center hidden">
            <div class="text-center">
                <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <p class="text-white text-lg mb-2">Bağlantı Hatası</p>
                <p class="text-zinc-400 text-sm mb-4">Spor sistemi şu anda kullanılamıyor</p>
                <button onclick="reloadIframe()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                    Tekrar Dene
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const iframe = document.getElementById('sportsIframe');
    const loadingState = document.getElementById('loadingState');
    const errorState = document.getElementById('errorState');

    initializeSportsIframe();

    iframe.addEventListener('load', () => {
        loadingState.style.display = 'none';
    });

    iframe.addEventListener('error', () => {
        loadingState.style.display = 'none';
        errorState.classList.remove('hidden');
    });

    const allowedOrigin = new URL('<?php echo e($sportsApi->api_url); ?>').origin;
    window.addEventListener('message', event => {
        if (event.origin !== allowedOrigin) return;
        console.log('Iframe mesajı:', event.data);
    });
});

async function initializeSportsIframe() {
    const iframe = document.getElementById('sportsIframe');
    const loadingState = document.getElementById('loadingState');
    const errorState = document.getElementById('errorState');

    try {
        loadingState.style.display = 'flex';
        errorState.classList.add('hidden');

        const authData = {
            api_secret_key: '<?php echo e($sportsApi->api_secret_key); ?>',
            api_token: '<?php echo e($sportsApi->api_token); ?>',
            username: '<?php echo e($user->username); ?>',
            user_id: <?php echo e($user->id); ?>

            // ⚡ agent_code artık gönderilmiyor
        };

        const response = await fetch('<?php echo e(route('sports.login')); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify(authData)
        });

        if (!response.ok) {
            const text = await response.text();
            throw new Error(`HTTP ${response.status}: ${text}`);
        }

        const result = await response.json();

        if (result.success) {
            iframe.src = result.redirect_url + '/tr/presports?session_token=' + result.session_token;
        } else {
            throw new Error(result.error || 'Doğrulama başarısız');
        }
    } catch (error) {
        console.error('Sports iframe initialization error:', error);
        loadingState.style.display = 'none';
        errorState.classList.remove('hidden');

        const errTitle = document.querySelector('#errorState p.text-white.text-lg.mb-2');
        if (errTitle) errTitle.textContent = 'API Bağlantı Hatası';

        const errDesc = document.querySelector('#errorState p.text-zinc-400.text-sm.mb-4');
        if (errDesc) errDesc.textContent = error.message || 'Spor sistemi şu anda kullanılamıyor';
    }
}

function reloadIframe() {
    initializeSportsIframe();
}
</script>

<style>
iframe::-webkit-scrollbar { width: 8px; }
iframe::-webkit-scrollbar-track { background: #374151; }
iframe::-webkit-scrollbar-thumb { background: #6b5563; border-radius: 4px; }
iframe::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/robinbet118.com/httpdocs/resources/views/sports-iframe.blade.php ENDPATH**/ ?>