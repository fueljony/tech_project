<script setup>
import { storeToRefs } from 'pinia';
import { useSurveyStore } from '../store';
const surveyStore = useSurveyStore();
const { surveys } = storeToRefs(surveyStore);
surveyStore.loadInitialData();
const tableHeaders = [
  { text: 'Id', value: 'id', sortable: false, align: 'start' },
  { text: 'Title', value: 'name', sortable: false, align: 'start' },
  { text: 'Actions', value: 'actions', sortable: false, align: 'start' },
];
</script>

<template>
  <div>
    {{ surveys }}
    <v-data-table :headers="tableHeaders" :items="surveys">
      <template v-slot:item.actions="{ item }">
        <RouterLink :to="{
          name: 'survey.show',
          params: { id: item.id }
        }">
          <v-btn color="primary" outlined @click="surveyStore.editSurvey(item.id)" class="mr-3" small>View</v-btn>
        </RouterLink>
        <RouterLink :to="{
          name: 'survey.edit',
          params: { id: item.id }
        }">
          <v-btn @click="surveyStore.editSurvey(item.id)" small>Edit</v-btn>
        </RouterLink>
      </template>
    </v-data-table>
  </div>
</template>



<style></style>