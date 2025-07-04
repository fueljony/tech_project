import Index from "./views/Index.vue";
import Edit from "./views/Edit.vue";
import Show from "./views/Show.vue";

export default [
    {
        path: "/",
        name: "surveys",
        components: {
            default: Index,
        },
    },
    {
        path: "/:id/edit",
        name: "survey.edit",
        components: {
            default: Edit,
        },
    },
    {
        path: "/:id",
        name: "survey.show",
        components: {
            default: Show,
        },
    },
];
