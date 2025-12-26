/**
 * CreateSurveyModal.vue
 * Modal for creating a new survey.
 * Props:
 *   - value: Boolean - v-model for dialog open/close
 * Emits:
 *   - save(survey): when a valid survey is submitted
 *   - cancel(): when the modal is closed without saving
 */
<script>
export default {
  name: 'CreateSurveyModal',
  props: {
    value: { 
      type: Boolean, 
      required: true 
    }, // v-model for dialog open/close
  },

  data() {
    return {
      form: {
        name: '',
        description: '',
        status: 'draft'
      },

      valid: false,
      touched: false,
      
      statusOptions: [
        { text: 'Draft', value: 'draft' },
        { text: 'Active', value: 'active' },
        { text: 'Archived', value: 'archived' }
      ]
    }
  },

  computed: {
    isValid() {
      return this.form.name && this.form.status
    }
  },

  methods: {
    close() {
      this.$emit('cancel')
      this.reset()
    },

    save() {
      this.touched = true
      
      if (!this.isValid) return

      this.$emit('save', { ...this.form })
      this.reset()
    },

    reset() {
      this.form = {
        name: '',
        description: '',
        status: 'draft'
      }
      this.valid = false
      this.touched = false
    }
  }
}
</script>

<template>
  <v-dialog
    v-model="value"
    max-width="600"
    persistent
  >
    <v-card class="create-survey-modal">
      <div class="d-flex flex-column gap-4">
        <v-card-title class="headline font-weight-bold">
          Create Survey
        </v-card-title>

        <v-card-text>
          <v-form
            ref="form"
            v-model="valid"
            @submit.prevent="save"
          >
            <v-text-field
              v-model="form.name"
              label="Survey Name"
              placeholder="Enter survey name"
              hide-details="auto"
              :error-messages="touched && !form.name ? ['Name is required'] : []"
              class="grey lighten-5 mb-6"
              outlined
              dense
              required
            />

            <v-textarea
              v-model="form.description"
              label="Description"
              placeholder="Enter survey description"
              hide-details="auto"
              class="grey lighten-5 mb-6"
              outlined
              dense
              auto-grow
              rows="3"
            />

            <v-select
              v-model="form.status"
              :items="statusOptions"
              label="Status"
              hide-details="auto"
              :error-messages="touched && !form.status ? ['Status is required'] : []"
              class="grey lighten-5 mb-6"
              outlined
              dense
              required
              item-text="text"
              item-value="value"
            />
          </v-form>
        </v-card-text>
      </div>

      <v-card-actions class="justify-end">
        <v-btn 
          text 
          rounded 
          color="secondary" 
          @click="close"
        >
          Cancel
        </v-btn>
        
        <v-btn 
          rounded 
          color="secondary" 
          :disabled="!isValid" 
          @click="save"
        >
          Create
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<style scoped lang="scss">
.create-survey-modal {
  background-color: #e1e1e1;

  .text-h5 {
    font-weight: 300;
  }
  
  .v-messages__message {
    background: transparent !important;
  }
}
</style> 