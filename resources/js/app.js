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

// Make Swiper available globally if needed, or export it
window.Swiper = Swiper;
window.SwiperModules = { Navigation, Pagination, Autoplay, EffectFade };

console.log('Wonderful Toba Assets Loaded');
