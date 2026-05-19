import './bootstrap';
import Alpine from 'alpinejs';
import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay, EffectFade } from 'swiper/modules';

// Import Swiper styles
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/effect-fade';

window.Alpine = Alpine;
Alpine.start();

window.Swiper = Swiper;
window.SwiperModules = { Navigation, Pagination, Autoplay, EffectFade };

// ─── CMS Realtime Sync Logic ──────────────────────────────────────────────────
(function() {
    let currentVersion = null;
    const checkInterval = 60000; // Cek setiap 60 detik (lebih hemat resource)
    
    async function checkCmsVersion() {
        // Jangan cek jika tab tidak aktif
        if (document.hidden) return;

        try {
            const response = await fetch('/api/sync/version');
            const data = await response.json();
            
            if (currentVersion === null) {
                currentVersion = data.version;
            } else if (data.version !== currentVersion) {
                console.log('CMS Update Detected!');
                // Alih-alih reload paksa, kita bisa tampilkan notifikasi halus
                showUpdateNotification();
                currentVersion = data.version;
            }
        } catch (e) {
            // silent fail
        }
    }

    function showUpdateNotification() {
        const notify = document.createElement('div');
        notify.className = 'fixed bottom-24 left-1/2 -translate-x-1/2 z-[200] animate-in fade-in slide-in-from-bottom-4 duration-500';
        notify.innerHTML = `
            <div class="bg-slate-900/90 backdrop-blur-xl border border-white/10 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4">
                <div class="w-2 h-2 bg-lake-light rounded-full animate-pulse"></div>
                <span class="text-xs font-bold uppercase tracking-widest">Konten Baru Tersedia</span>
                <button onclick="window.location.reload()" class="bg-lake-blue px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-lake-light transition-all">Perbarui</button>
            </div>
        `;
        document.body.appendChild(notify);
    }

    if (!window.location.pathname.startsWith('/admin')) {
        setInterval(checkCmsVersion, checkInterval);
    }
})();

console.log('Sujailake Toba Premium Experience Loaded');
