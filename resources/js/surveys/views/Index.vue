<script>
import { ref, onMounted } from 'vue'
import { useSurveyStore } from '../store/survey'

export default {
  name: 'SurveysIndex',
  
  setup() {
    const store = useSurveyStore()
    const deleteDialog = ref(false)
    const surveyToDelete = ref(null)

    const headers = [
      { text: 'Name', value: 'name' },
      { text: 'Status', value: 'status' },
      { text: 'Actions', value: 'actions', sortable: false },
    ]

    const getStatusColor = (status) => {
      const colors = {
        draft: 'grey',
        active: 'green',
        archived: 'red'
      }
      
      return colors[status] || 'grey'
    }

    const confirmDelete = (survey) => {
      surveyToDelete.value = survey
      deleteDialog.value = true
    }

    const deleteSurvey = async () => {
      if (surveyToDelete.value) {
        try {
          await store.deleteSurvey(surveyToDelete.value.id)
          deleteDialog.value = false
          surveyToDelete.value = null
        } catch (error) {
          // Error is handled in the store
        }
      }
    }

    onMounted(() => {
      store.fetchSurveys()
    })

    return {
      store,
      headers,
      deleteDialog,
      getStatusColor,
      confirmDelete,
      deleteSurvey
    }
  }
}
</script>

<template>
  <div class="surveys-index">
    <div class="d-flex align-center justify-space-between mb-4">
      <h1 class="">Surveys</h1>
      <v-btn 
        color="secondary" 
        rounded
        @click="$router.push('/create')"
      >
        Create Survey
      </v-btn>
    </div>

    <v-progress-linear 
      v-if="store.loading" 
      indeterminate 
      class="mb-4" 
    />

    <v-alert 
      v-else-if="store.error" 
      type="error" 
      class="mb-4"
    >
      {{ store.error }}
    </v-alert>

    <v-data-table
      v-else
      :headers="headers"
      :items="store.surveys"
      :items-per-page="10"
      class="elevation-1"
    >
      <template #item.status="{ item }">
        <v-chip :color="getStatusColor(item.status)" small>{{ item.status }}</v-chip>
      </template>
      <template #item.actions="{ item }">
        <v-btn text @click="$router.push(`/${item.id}/edit`)">Edit</v-btn>
        <v-btn text color="error" @click="confirmDelete(item)">Delete</v-btn>
      </template>
    </v-data-table>

    <v-dialog v-model="deleteDialog" max-width="400">
      <v-card>
        <v-card-title>Delete Survey</v-card-title>
        <v-card-text>
          Are you sure you want to delete this survey?
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn text @click="deleteDialog = false">Cancel</v-btn>
          <v-btn color="error" @click="deleteSurvey">Delete</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<style></style>