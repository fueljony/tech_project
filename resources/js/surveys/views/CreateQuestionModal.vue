<script>
/**
 * CreateQuestionModal.vue
 * Modal for creating a new survey question.
 * Emits:
 *   - save(question): when a valid question is submitted
 *   - cancel(): when the modal is closed without saving
 */
export default {
  name: 'CreateQuestionModal',
  props: {
    value: { 
      type: Boolean, 
      required: true 
    }, // v-model for dialog open/close
  },

  data() {
    return {
      questionText: '',
      questionType: '',
      optionsInput: '',
      optionsArray: [],
      optionsError: false,
      touched: false,
      types: [
        { text: 'Multiple Choice', value: 'multiple_choice' },
        { text: 'Date Picker', value: 'date_picker' },
        { text: 'Input Text', value: 'input_text' },
        { text: 'Multiple Answer', value: 'multiple_answer' },
      ],
    }
  },

  computed: {
    showOptions() {
      return (
        this.questionType === 'multiple_choice' ||
        this.questionType === 'multiple_answer'
      )
    },

    isValid() {
      if (!this.questionText || !this.questionType) return false
      if (this.showOptions && this.optionsArray.length === 0) return false
      return true
    },
  },

  methods: {
    close() {
      this.$emit('cancel')
      this.reset()
    },

    save() {
      this.touched = true
      
      if (!this.isValid) return

      const question = {
        question: this.questionText,
        value_type: this.questionType,
        options: this.showOptions ? this.optionsArray : null,
      }
      
      this.$emit('save', question)
      this.reset()
    },

    reset() {
      this.questionText = ''
      this.questionType = ''
      this.optionsInput = ''
      this.optionsArray = []
      this.touched = false
    },

    parseOptions() {
      const newOptions = this.optionsInput
        .split('\n')
        .map(opt => opt.trim())
        .filter(opt => opt.length > 0)
      // Only add options that aren't already present
      newOptions.forEach(opt => {
        if (opt && !this.optionsArray.includes(opt)) {
          this.optionsArray.push(opt)
        }
      })
      this.optionsInput = ''
    },

    handleOptionInput(e) {
      if (e && e.key === 'Enter') {
        this.parseOptions()
      }
    },

    removeOption(idx) {
      this.optionsArray.splice(idx, 1)
    },

    onOptionsBlur() {
      this.parseOptions()
    },
  },
}
</script>

<template>
  <v-dialog
    v-model="value"
    max-width="600"
    persistent
  >
    <v-card class="create-question-modal">
      <div class="d-flex flex-column gap-4">
        <v-card-title class="headline font-weight-bold">
          Create question
        </v-card-title>

        <v-card-text>
          <div class="create-question-modal__section">
            <div class="text-h5 mb-2">1. Enter the question</div>
            <v-text-field
              v-model="questionText"
              label="Question"
              placeholder="Question text"
              hide-details="auto"
              :error-messages="touched && !questionText ? ['Question is required'] : []"
              class="grey lighten-5 mb-6"
              outlined
              dense
              required
            />
          </div>

          <div class="create-question-modal__section">
            <div class="text-h5 mb-2">2. Select the question type</div>
            <v-select
              v-model="questionType"
              :items="types"
              label="Question type"
              placeholder="-"
              hide-details="auto"
              :error-messages="touched && !questionType ? ['Type is required'] : []"
              class="create-question-modal__type-input grey lighten-5 mb-6"
              outlined
              dense
              required
              item-text="text"
              item-value="value"
            />
          </div>

          <div v-if="showOptions" class="create-question-modal__section">
            <div class="text-h5 mb-2">3. Input the options</div>
            <v-textarea
              v-model="optionsInput"
              label="Options"
              placeholder="Option 1\nOption 2\nOption 3"
              hide-details="auto"
              :error-messages="touched && optionsArray.length === 0 ? ['Options are required'] : []"
              class="grey lighten-5 mb-2"
              outlined
              dense
              required
              auto-grow
              rows="3"
              @blur="onOptionsBlur"
              @keyup.enter="handleOptionInput"
            />
            <div class="d-flex flex-wrap mt-2">
              <v-chip
                v-for="(option, idx) in optionsArray"
                :key="option + idx"
                class="ma-1"
                close
                @click:close="removeOption(idx)"
                color="secondary"
                text-color="white"
                label
              >
                {{ option }}
              </v-chip>
            </div>
          </div>
         </v-card-text>
        </div>
      <v-card-actions class="justify-end">
        <v-btn 
          text 
          rounded 
          color="secondary" 
          @click="close"
        >
          Close
        </v-btn>
        
        <v-btn 
          rounded 
          color="secondary" 
          :disabled="!isValid" 
          @click="save"
        >
          Save
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<style scoped lang="scss">
.create-question-modal {
  background-color: #e1e1e1;

  .text-h5 {
    font-weight: 300;
  }
  
  .v-messages__message {
    background: transparent !important;
  }

  .create-question-modal__title {
    margin-bottom: 0;
  }

  .create-question-modal__type-input {
    width: 210px;
  }
}
</style>
