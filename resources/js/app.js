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

// ───────────────────────────────────────────────────────────
// Smart Comparison store (persists in localStorage, lintas halaman)
// ───────────────────────────────────────────────────────────
document.addEventListener('alpine:init', () => {
    Alpine.store('compare', {
        max: 3,
        open: false,
        items: (() => {
            try { return JSON.parse(localStorage.getItem('wt_compare') || '[]'); }
            catch (e) { return []; }
        })(),
        save() {
            try { localStorage.setItem('wt_compare', JSON.stringify(this.items)); } catch (e) { }
        },
        has(id) {
            return this.items.some((i) => String(i.id) === String(id));
        },
        toggle(pkg) {
            if (this.has(pkg.id)) {
                this.remove(pkg.id);
                return true;
            }
            if (this.items.length >= this.max) {
                window.dispatchEvent(new CustomEvent('compare-full', { detail: { max: this.max } }));
                return false;
            }
            // Simpan hanya field yang dibutuhkan tabel perbandingan
            this.items.push({
                id: pkg.id,
                name: pkg.name,
                slug: pkg.slug,
                price: pkg.price,
                duration: pkg.duration,
                location: pkg.location || '',
                image: pkg.image || '',
                includes: pkg.includes || [],
                excludes: pkg.excludes || [],
            });
            this.save();
            return true;
        },
        remove(id) {
            this.items = this.items.filter((i) => String(i.id) !== String(id));
            this.save();
            if (this.items.length === 0) this.open = false;
        },
        clear() {
            this.items = [];
            this.save();
            this.open = false;
        },
        get count() {
            return this.items.length;
        },
    });
});

Alpine.start();

// Make Swiper available globally if needed, or export it
window.Swiper = Swiper;
window.SwiperModules = { Navigation, Pagination, Autoplay, EffectFade };

console.log('Wonderful Toba Assets Loaded');
