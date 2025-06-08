<template>
  <div class="survey-create">
    <v-container>
      <v-row>
        <v-col cols="12">
          <h1>Create Survey</h1>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" md="8">
          <v-form
            ref="form"
            v-model="valid"
            @submit.prevent="submit"
          >
            <v-text-field
              v-model="form.name"
              label="Survey Name"
              :rules="[v => !!v || 'Name is required']"
              required
            />

            <v-textarea
              v-model="form.description"
              label="Description"
              rows="3"
            />

            <v-select
              v-model="form.status"
              :items="statusOptions"
              label="Status"
              :rules="[v => !!v || 'Status is required']"
              required
            />

            <v-btn
              type="submit"
              color="primary"
              :loading="store.loading"
              :disabled="!valid"
            >
              Create Survey
            </v-btn>

            <v-btn
              text
              @click="$router.push('/surveys')"
              class="ml-2"
            >
              Cancel
            </v-btn>
          </v-form>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import { useSurveyStore } from '../store/survey'

export default {
  name: 'SurveyCreate',

  data() {
    return {
      form: {
        name: '',
        description: '',
        status: 'draft'
      },
      valid: false,
      statusOptions: [
        { text: 'Draft', value: 'draft' },
        { text: 'Active', value: 'active' },
        { text: 'Archived', value: 'archived' }
      ]
    }
  },

  computed: {
    store() {
      return useSurveyStore()
    }
  },

  methods: {
    async submit() {
      try {
        const survey = await this.store.createSurvey(this.form)
        this.$router.push(`/surveys/${survey.id}/edit`)
      } catch (error) {
        // Error is handled in the store
      }
    }
  }
}
</script> 