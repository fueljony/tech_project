<script setup>
import {computed, ref} from "vue";
import { useSurveyStore } from "../store";
import { storeToRefs } from "pinia";
import { useRoute } from "vue-router/composables";
import AddEditQuestionModal from "../components/AddEditQuestionModal.vue";
const surveyStore = useSurveyStore();
const { surveysMap } = storeToRefs(surveyStore);
const id = Number(useRoute().params.id);
surveyStore.getSurveyWithQuestionsById(id);

const hasSurvey = computed(() => surveysMap.value.has(id));
const survey = computed(() => surveysMap.value.get(id));

const headers = [
    { text: 'Question', value: 'question', sortable: true },
    { text: 'Type', value: 'value_type', sortable: true },
    { text: 'Options', value: 'options', sortable: true },
    { text: 'Actions', value: 'actions', sortable: false }
];

const handleModelUpdate = (value) => {
    showAddQuestionDialog.value = value;
    if (!value) {
        questionToEdit.value = null
    }
};

const questionToEdit = ref(null)
const editQuestion = (editedQuestion) => {
    questionToEdit.value = editedQuestion
    showAddQuestionDialog.value = true
}

const deleteQuestion = (questionToDelete) => {
    surveyStore.deleteQuestion(questionToDelete.survey_id, questionToDelete.id)
}

const showAddQuestionDialog = ref(false)


</script>

<template>
    <div>
        <div v-if="hasSurvey">
            <v-container>
                <!-- Survey Header -->
                <v-row class="mb-6">
                    <v-col>
                        <h1 class="text-h4">Survey: {{ survey.name }}</h1>
                    </v-col>
                </v-row>

                <!-- Add Question Button -->
                <v-row class="mb-4">
                    <v-col class="d-flex justify-end">
                        <v-btn
                            rounded
                            color="primary"
                            @click="showAddQuestionDialog = true"
                        >
                            <v-icon left>mdi-plus</v-icon>
                            Add Question
                        </v-btn>
                    </v-col>
                </v-row>

                <!-- Questions Table -->
                <v-row>
                    <v-col>
                        <v-card>
                            <v-data-table
                                :headers="headers"
                                :items="survey.questions || []"
                                :items-per-page="10"
                            >
                                <template v-slot:item.required="{ item }">
                                    <v-chip
                                        :color="item.required ? 'success' : 'default'"
                                        :text="item.required ? 'Yes' : 'No'"
                                    ></v-chip>
                                </template>

                                <template v-slot:item.actions="{ item }">
                                    <v-btn
                                        icon
                                        small
                                        color="primary"
                                        class="mr-2"
                                        @click="() => editQuestion(item)"
                                    >
                                        <v-icon>mdi-pencil</v-icon>
                                    </v-btn>
                                    <v-btn
                                        icon
                                        small
                                        color="error"
                                        @click="() => deleteQuestion(item)"
                                    >
                                        <v-icon>mdi-delete</v-icon>
                                    </v-btn>
                                </template>

                                <template v-slot:no-data>
                                    No questions added yet
                                </template>
                            </v-data-table>
                        </v-card>
                    </v-col>
                </v-row>
                <AddEditQuestionModal
                    :modelValue="showAddQuestionDialog"
                    @update:modelValue="handleModelUpdate"
                    :survey-id="id"
                    :question="questionToEdit"
                />
            </v-container>
        </div>
        <div v-else>
            <v-alert
                type="warning"
                class="ma-4"
            >
                Survey Not Found!
            </v-alert>
        </div>
    </div>
</template>

<style scoped>
.v-data-table {
    width: 100%;
}
</style>
