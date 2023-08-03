import './bootstrap';
import Alpine from 'alpinejs';
import "@melloware/coloris/dist/coloris.css";
import Coloris from "@melloware/coloris";

Coloris.init();
Coloris({
    alpha: false,
})

window.Alpine = Alpine;

Alpine.start();
