import "./bootstrap";

import Alpine from "alpinejs";
import mask from "@alpinejs/mask";
import collapse from "@alpinejs/collapse";

Alpine.plugin(mask);
Alpine.plugin(collapse);
window.Alpine = Alpine;

Alpine.start();
