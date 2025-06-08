<script>
import { useSurveyStore } from '../store/survey'

import CreateQuestionModal from './CreateQuestionModal.vue'

export default {
  name: 'SurveyEditView',
  
  data() {
    return {
      deleteDialog: false,
      questionToDelete: null,
      titleInput: '',
      titleError: false,
      showCreateModal: false,
      headers: [
        { text: 'Question', value: 'question' },
        { text: 'Type', value: 'value_type' },
        { text: 'Options', value: 'options' },
        { text: 'Actions', value: 'actions', sortable: false },
      ],
    }
  },
  
  computed: {
    surveyStore() {
      return useSurveyStore();
    },

    surveyId() {
      return this.$route.params.id;
    },

    survey() {
      return this.surveyStore.getSurveyById(this.surveyId);
    },

    questions() {
      return this.survey?.questions || [];
    },
  },

  watch: {
    survey: {
      handler(newSurvey) {
        if (newSurvey && newSurvey.name !== this.titleInput) {
          this.titleInput = newSurvey.name
        }
      },

      immediate: true,
    },
  },

  methods: {
    getOptionsText(options, type) {
      if (!options || (Array.isArray(options) && options.length === 0)) return '-'
    
      if (type === 'multiple_choice' || type === 'multiple_answer') {
        if (Array.isArray(options)) return options.join('\n')
        if (typeof options === 'string') return options
        if (typeof options === 'object') return Object.values(options).join('\n')
      }

      return '-'
    },

    confirmDelete(question) {
      this.questionToDelete = question
      this.deleteDialog = true
    },

    deleteQuestion() {
      // TODO: implement actual delete logic
      this.deleteDialog = false
      this.questionToDelete = null
    },

    async updateTitle() {
      if (!this.titleInput) {
        this.titleError = true;
        return;
      }

      this.titleError = false;
      
      if (this.survey && this.titleInput !== this.survey.name) {
        try {
          await this.surveyStore.updateSurvey(this.survey.id, { ...this.survey, name: this.titleInput })
        } catch (e) {
          // Optionally handle error
        }
      }
    },

    openCreateModal() {
      this.showCreateModal = true
    },
    closeCreateModal() {
      this.showCreateModal = false
    },
    async saveNewQuestion(question) {
      try {
        await this.surveyStore.addQuestion(this.surveyId, question)
        this.showCreateModal = false
      } catch (e) {
        // Optionally handle error
      }
    },
  },

  components: {
    CreateQuestionModal
  },
}
</script>

<template>
  <div class="survey-editor-view">
    <div class="d-flex align-center justify-space-between mb-4">
      <v-btn text color="primary" @click="$router.push('/')">
        <v-icon left>mdi-arrow-left</v-icon>
        back to surveys
      </v-btn>
     
      <v-btn 
        color="secondary" 
        rounded
        @click="openCreateModal"
      >
        <v-icon left>mdi-plus</v-icon>
        add question
      </v-btn>
    </div>
    
    <v-card color="secondary" class="mb-4">
      <v-card-title class="white--text">
        <div>
          <v-text-field
            v-model="titleInput"
            class="survey-editor-view__title-input"
            solo
            flat
            hide-details="auto"
            :rules="[v => !!v || 'Survey title is required']"
            @blur="updateTitle"
            @keyup.enter="updateTitle"
          >
          </v-text-field>
        </div>
      </v-card-title>
    
      <v-card-text style="padding:0;">
        <v-data-table
          :headers="headers"
          :items="questions"
          :items-per-page="10"
          class="elevation-0"
          color="neutral300"
        >
          <template #item.options="{ item }">
            <span style="white-space: pre-line;">{{ getOptionsText(item.options, item.value_type) }}</span>
          </template>
    
          <template #item.actions="{ item }">
            <v-btn text color="primary" disabled>Edit</v-btn>
            <v-btn text color="error" @click="confirmDelete(item)">Delete</v-btn>
          </template>
        </v-data-table>
      </v-card-text>
    </v-card>

    <v-dialog v-model="deleteDialog" max-width="400">
      <v-card>
        <v-card-title>Delete Question</v-card-title>
        
        <v-card-text>
          Are you sure you want to delete this question?
        </v-card-text>

        <v-card-actions>
          <v-spacer />
          <v-btn text @click="deleteDialog = false">Cancel</v-btn>
          <v-btn color="error" @click="deleteQuestion">Delete</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <CreateQuestionModal
      v-model="showCreateModal"
      @save="saveNewQuestion"
      @cancel="closeCreateModal"
    />
  </div>
</template>

<style scoped lang="scss">
.survey-editor-view {
  .survey-editor-view__title-input {
    width: 500px;
    font-size: 1rem;
    font-weight: 400;
  }
}
</style> 