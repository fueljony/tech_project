<script setup>
import { storeToRefs } from 'pinia';
import { useSurveyStore } from '../store';
const surveyStore = useSurveyStore();
const { surveys } = storeToRefs(surveyStore);
surveyStore.loadInitialData();
const tableHeaders = [
  { text: 'Id', value: 'id', sortable: false, align: 'start' },
  { text: 'Title', value: 'name', sortable: false, align: 'start' },
  { text: 'Options', value: 'options', sortable: false, align: 'start' },
];
</script>

<template>
  <div>
    <p class="red--text">
      hello
    </p>
    {{ surveys }}
    <v-data-table :headers="tableHeaders" :items="surveys"></v-data-table>
  </div>
</template>



<style></style>