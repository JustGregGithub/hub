import './bootstrap';
import Alpine from 'alpinejs';
import Clipboard from "@ryangjchandler/alpine-clipboard"
import "@melloware/coloris/dist/coloris.css";
import Coloris from "@melloware/coloris";
import focus from '@alpinejs/focus'

Coloris.init();
Coloris({
    alpha: false,
})

Alpine.plugin(Clipboard);
Alpine.plugin(focus)

window.Alpine = Alpine;
Alpine.start();
