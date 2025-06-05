import Vue from "vue";
import VueRouter from "vue-router";

import SurveysApp from "./surveys/app";

Vue.use(VueRouter);

const instance = {
    app: null,
    routes: null,
};

if (document.location.pathname.indexOf("/surveys") === 0) {
    // default index route

    instance.app = SurveysApp.app;

    instance.routes = {
        mode: "history",
        base: "/surveys",
        routes: SurveysApp.routes,
    };
}

export const app = instance.app;
export const router = new VueRouter(instance.routes);
