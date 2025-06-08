import Index from "./views/Index.vue";
import Edit from "./views/Edit.vue";
import Show from "./views/Show.vue";
import Create from "./views/Create.vue";

export default [
    {
        path: "/",
        name: "surveys",
        components: {
            default: Index,
        },
    },
    {
        path: "/create",
        name: "survey.create",
        components: {
            default: Create,
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
