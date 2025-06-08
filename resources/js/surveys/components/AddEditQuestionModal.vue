<script setup>
import {ref, computed, watch} from "vue";
import { useSurveyStore } from "../store";

const props = defineProps({
    surveyId: {
        type: [String, Number],
        required: true
    },
    question: {
        type: [Object, null],
        default: null
    },
    modelValue: {
        type: Boolean,
        required: true
    }
});

const newQuestion = ref({
    question: '',
    value_type: 'input_text',
    options: []
});


const resetForm = () => {
    newQuestion.value = {
        question: '',
        value_type: 'input_text',
        options: []
    };
};

const isEditing = computed(() => props.question);
watch(() => props.question, (value) => {
    if (value) {
        newQuestion.value = {...props.question}
    } else {
        resetForm();
    }
}, { immediate: true})
const emit = defineEmits(['update:modelValue']);
const surveyStore = useSurveyStore();

const needsOptions = computed(() => {
    return ['multiple_choice', 'multiple_answer'].includes(newQuestion.value.value_type);
});


const questionTypes = [
    { text: 'Input Text', value: 'input_text' },
    { text: 'Multiple Choice (Single Answer)', value: 'multiple_choice' },
    { text: 'Multiple Choice (Multiple Answers)', value: 'multiple_answer' },
    { text: 'Date Picker', value: 'date_picker' }
];


const handleSubmit = async () => {
    if(!isFormValid.value) return

    if (!needsOptions.value) {
        newQuestion.value.options = [];
    }

    try {
        if (isEditing.value) {
            await surveyStore.editQuestion(props.surveyId, props.question.id, newQuestion.value);
        } else {
            await surveyStore.addQuestion(props.surveyId, newQuestion.value);
        }
        closeDialog();
    } catch (error) {
        console.error('Failed to add question:', error);
    }
};

const closeDialog = () => {
    resetForm();
    emit('update:modelValue', false);
};

const dialogVisible = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value)
});

const isFormValid = ref(false)
</script>

<template>
    <v-dialog
        v-model="dialogVisible"
        max-width="600px"
    >
        <v-card>
            <v-card-title>
                <span class="text-h5">{{isEditing ? 'Edit' : 'Create' }} question</span>
            </v-card-title>

            <v-card-text>
                <v-form v-model="isFormValid">
                    <v-container>
                        <v-row no-gutters>
                            <v-col cols="12">
                                <div class="text-subtitle-1 mb-2">1. Enter the question</div>
                                <v-text-field
                                    v-model="newQuestion.question"
                                    placeholder="Type your question here"
                                    required
                                    outlined
                                    :rules="[v => !!v || 'Question text is required']"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12">
                                <div class="text-subtitle-1 mb-2">2. Select the Question type</div>
                                <v-select
                                    v-model="newQuestion.value_type"
                                    :items="questionTypes"
                                    placeholder="Choose question type"
                                    required
                                    outlined
                                ></v-select>
                            </v-col>

                            <v-col v-if="needsOptions" cols="12">
                                <div class="text-subtitle-1 mb-2">3. Input the options</div>
                                <v-combobox
                                    v-model="newQuestion.options"
                                    placeholder="Type options and press Enter"
                                    multiple
                                    chips
                                    small-chips
                                    outlined
                                    :rules="[v => v.length >= 1 || 'At least one option is required']">
                                    <template v-slot:selection="{ item, index }">
                                        <v-chip
                                            color="primary"
                                            small
                                            :key="index"
                                            close
                                            @click:close="newQuestion.options.splice(index, 1)"
                                        >
                                            {{ item }}
                                        </v-chip>
                                    </template>
                                </v-combobox>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="grey"
                    text
                    @click="closeDialog"
                >
                    Close
                </v-btn>
                <v-btn
                    color="primary"
                    @click="handleSubmit"
                    :disabled="!isFormValid"
                    rounded
                >
                    Save
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
