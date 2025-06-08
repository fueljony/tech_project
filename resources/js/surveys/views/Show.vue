<script setup>
import {ref, computed, watch, reactive} from 'vue';
import {useSurveyStore} from "../store/index.js";
import {storeToRefs} from "pinia";
import {useRoute} from "vue-router/composables";
import toastr from 'toastr';
const surveyStore = useSurveyStore();
const { surveysMap } = storeToRefs(surveyStore);
const id = Number(useRoute().params.id);
surveyStore.getSurveyWithQuestionsById(id);

const survey = computed(() => surveysMap.value.get(id));

const answers = ref({});
const isFormValid = ref(false);


// Initialize answers object with empty values for each question
const initializeAnswers = () => {
    const newAnswers = {};
    survey.value?.questions.forEach(question => {
        if (['multiple_choice', 'multiple_answer'].includes(question.value_type)) {
            newAnswers[question.id] = [];
        } else if (question.value_type === 'date_picker') {
            newAnswers[question.id] = '';
        } else {
            newAnswers[question.id] = '';
        }
    });
    answers.value = newAnswers;
};

watch(survey, (value) => {
    if(value) initializeAnswers()
    }
)

const rules = {
    required: v => !!v || 'This field is required',
    multipleAnswer: v => v?.length > 0 || 'Please select at least one option'
};

const handleSubmit = async () => {
    if (!isFormValid.value) return;

    try {
        const {data} = await axios.post(`/survey/${id}/answers`, { answers: answers.value });
        if (data.success) {
            toastr.success(data.msg, 'Success');
        }
    } catch (error) {
        toastr.error('Something went wrong', 'Error')
    }
};

const dateMenus = ref({});

</script>

<template>
        <!--    show the survey to the respondent, extra credit if you can validate and store the answers-->
    <v-form v-model="isFormValid" v-if="Object.keys(answers).length" @submit.prevent="handleSubmit">
            <v-card class="d-flex flex-column pa-0" max-height="80vh" elevation="3" rounded>
            <v-card-title class="text-h4 mb-4 secondary white--text">{{ survey?.name }}</v-card-title>
            <v-card-text class="px-0 flex-grow-1 overflow-y-auto">
                 <div v-for="(question, index) in survey?.questions" :key="question.id">
                     <div class="px-6">
                        <div class="text-h6 mb-2">
                            {{ index + 1 }}. {{ question.question }}
                        </div>
                     <v-text-field
                         v-if="question.value_type === 'input_text'"
                         v-model="answers[question.id]"
                         outlined
                         :rules="[rules.required]"
                     ></v-text-field>

                     <!-- Single Choice (Radio Buttons) -->
                     <v-radio-group
                         v-else-if="question.value_type === 'multiple_choice'"
                         v-model="answers[question.id]"
                         :rules="[rules.required]"
                     >
                         <v-radio :label="option" v-for="option in question.options" :key="option" :value="[option]">
                         </v-radio>
                     </v-radio-group>

                     <!-- Multiple Answer (Checkboxes) -->
                     <div v-else-if="question.value_type === 'multiple_answer'">
                         <v-checkbox
                             v-model="answers[question.id]"
                             v-for="option in question.options"
                             :rules="[rules.multipleAnswer]"
                             :key="option"
                             :label="option"
                             :value="option"
                             dense
                             hide-details
                         ></v-checkbox>
                     </div>

                     <div v-else-if="question.value_type === 'date_picker'">
                         <v-menu
                             v-model="dateMenus[question.id]"
                             :close-on-content-click="false"
                             transition="scale-transition"
                             offset-y
                             min-width="auto"
                         >
                             <template v-slot:activator="{ on, attrs }">
                                 <v-text-field
                                     v-model="answers[question.id]"
                                     :rules="[rules.required]"
                                     placeholder="Choose a day"
                                     readonly
                                     outlined
                                     v-bind="attrs"
                                     v-on="on"
                                 >
                                     <template v-slot:prepend-inner>
                                         <v-btn
                                             small
                                             icon
                                             v-bind="attrs"
                                             v-on="on"
                                         >
                                             <v-icon>mdi-calendar</v-icon>
                                         </v-btn>
                                     </template>
                                 </v-text-field>
                             </template>
                             <v-date-picker
                                 v-model="answers[question.id]"
                                 @input="dateMenus[question.id] = false"
                             ></v-date-picker>
                         </v-menu>
                     </div>


                     </div>
                     <v-divider class="my-4"></v-divider>
                 </div>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="primary"
                    type="submit"
                    :disabled="!isFormValid"
                    size="large"
                    rounded
                >
                    Save
                </v-btn>
            </v-card-actions>
            </v-card>
        </v-form>
    </div>
</template>

<style scoped>
</style>
