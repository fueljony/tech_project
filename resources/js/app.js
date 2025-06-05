import "./bootstrap";

import Vue from "vue";
import vuetify from "@js/plugins/vuetify";
import { createPinia, PiniaVuePlugin } from "pinia";
Vue.use(PiniaVuePlugin);
const pinia = createPinia();

import { app, router } from "./app-factory";

import "./../sass/app.scss";

new Vue({
    components: { app },
    el: "#app",
    router,
    vuetify,
    pinia,
});
