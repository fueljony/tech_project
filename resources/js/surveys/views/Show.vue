<script>
import { useSurveyStore } from '../store/survey'

export default {
  name: 'SurveyShowView',
  data() {
    return {
      /** questions in the survey */
      questions: [],
      /** user entered answers */
      answers: {},
      /** loading flag in case we need to show status in UI */
      loading: false,
      /** error state of form */
      error: null,
      /** if form is touched */
      touched: false,
      /** marks form valid */
      formValid: false,
    }
  },

  computed: {
    surveyStore() {
      return useSurveyStore()
    },

    surveyId() {
      return this.$route.params.id
    },

    survey() {
      return this.surveyStore.getSurveyById(this.surveyId)
    },

    /**
     * Validation (for now) checks that all fields have values.
     *
     * @returns {Boolean}
     */
    isValid() {
      return (
        this.questions.length > 0 &&

        this.questions.every(q => {
          const val = this.answers[q.id]
          if (q.value_type === 'multiple_answer') {
            return Array.isArray(val) && val.length > 0
          }

          return val !== '' && val !== null && val !== undefined
        })
      )
    },
  },

  methods: {
    /**
     * calls fetchSurveyQuestions in store to get all questions for this survey
     */
    async fetchQuestions() {
      this.loading = true
      this.error = null

      try {
        const response = await this.surveyStore.fetchSurveyQuestions(this.surveyId)

        this.questions = response.data.questions

        // Initialize answers object
        this.questions.forEach(q => {
          if (q.value_type === 'multiple_answer') {
            this.$set(this.answers, q.id, [])
          } else {
            this.$set(this.answers, q.id, '')
          }
        })
      } catch (e) {
        this.error = e.response?.data?.message || 'Failed to load questions'
      } finally {
        this.loading = false
      }
    },

    /**
     * gets array of options for multiple choice question
     *
     * @param {Object} question
     * @returns {String[]|[]}
     */
    getOptions(question) {
      if (!question.options) return []
      if (Array.isArray(question.options)) return question.options
      if (typeof question.options === 'object') return Object.values(question.options)
      if (typeof question.options === 'string') return question.options.split('\n')

      return []
    },

    /**
     * saves user input.
     *   - marks form as touched
     *   - checks isValid flag
     *   - attempts to submit/save answers
     */
    async save() {
      this.touched = true
      if (!this.isValid) return

      try {
        await this.surveyStore.submitAnswers(this.surveyId, this.answers)

        // goes back to surveys list view
        this.$router.push('/')
      } catch (e) {
        this.error = e.response?.data?.message || 'Failed to save answers'
      }
    },
  },

  async mounted() {
    // this should never happen, but is here as a sanity check
    if (!this.survey) {
      await this.surveyStore.ensureSurveyData(this.surveyId)
    }

    await this.fetchQuestions()
  },
}
</script>

<template>
  <div class="survey-show">
    <v-btn text color="primary" @click="$router.push('/')">
      <v-icon left>mdi-arrow-left</v-icon>
      back to surveys
    </v-btn>

    <v-card class="mb-4 pt-4">
      <v-card-title class="background-secondary text-white">
        {{ survey?.name || 'Survey' }}
      </v-card-title>

      <v-card-text class="pt-4">
        <v-alert v-if="error" type="error" class="mb-4">{{ error }}</v-alert>
        <v-progress-linear v-else-if="loading" indeterminate class="mb-4" />
        <v-form
          v-else
          ref="form"
          v-model="formValid"
          lazy-validation
          color="neutral300"
        >
          <div>
            <div
              v-for="(q, idx) in questions"
              :key="q.id"
              class="survey-show__question mb-6"
            >
              <div class="font-weight-regular mb-2">
                {{ q.question }}
                <span class="text-error">*</span>
              </div>

              <div>
                <v-text-field
                  v-if="q.value_type === 'input_text'"
                  v-model="answers[q.id]"
                  :rules="[v => !!v || 'Required']"
                  :error-messages="touched && !answers[q.id] ? ['Required'] : []"
                  outlined
                  dense
                  required
                  class="survey-show__input"
                  label="Enter your answer"
                />

                <v-radio-group
                  v-else-if="q.value_type === 'multiple_choice'"
                  v-model="answers[q.id]"
                  :rules="[v => !!v || 'Required']"
                  :error-messages="touched && !answers[q.id] ? ['Required'] : []"
                  class="survey-show__input"
                  required
                >
                  <v-radio
                    v-for="opt in getOptions(q)"
                    :key="opt"
                    :label="opt"
                    :value="opt"
                    hide-details
                    class="mt-0 pt-0"
                  />
                </v-radio-group>

                <v-text-field
                  v-else-if="q.value_type === 'date_picker'"
                  v-model="answers[q.id]"
                  :rules="[v => !!v || 'Required']"
                  :error-messages="touched && !answers[q.id] ? ['Required'] : []"
                  outlined
                  dense
                  required
                  class="survey-show__input"
                  label="Choose a day"
                  type="date"
                  prepend-inner-icon="mdi-calendar"
                />

                <div
                  v-else-if="q.value_type === 'multiple_answer'"
                  class="survey-show__input d-flex flex-column gap-2"
                >
                  <v-checkbox
                    v-for="opt in getOptions(q)"
                    :key="opt"
                    :label="opt"
                    :value="opt"
                    hide-details
                    v-model="answers[q.id]"
                    :rules="[v => (Array.isArray(v) && v.length > 0) || 'Required']"
                    :error-messages="touched && (!answers[q.id] || answers[q.id].length === 0) ? ['Required'] : []"
                    class="mt-0 pt-0"
                  />
                </div>
              </div>
              <v-divider v-if="idx < questions.length - 1" class="my-6" />
            </div>
          </div>
        </v-form>
      </v-card-text>

      <v-card-actions class="justify-end">
        <v-btn
          color="secondary"
          rounded
          :disabled="!isValid"
          @click="save"
        >
          Save
        </v-btn>
      </v-card-actions>
    </v-card>
  </div>
</template>

<style lang="scss" scoped>
.survey-show {
  .survey-show__input {
    max-width: 500px;
  }
}
</style>
