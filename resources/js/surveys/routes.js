import Index from "./views/Index.vue";
import SurveyEdit from "./views/SurveyEdit.vue";
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
            default: SurveyEdit,
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
