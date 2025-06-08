<script>
import { useSurveyStore } from './store/survey';

export default {
  name: 'App',
  created() {
    // central point to initialize data in store
    this.surveyStore.fetchSurveys();
  },

  computed: {
    surveyStore() {
      return useSurveyStore();
    }
  }
}
</script>

<template>
  <v-app>
    <v-container class="tech-project">
      <router-view></router-view>
    </v-container>
  </v-app>
</template>

<style></style>