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
  <v-row>
    <v-col>
      <v-container>
      <div class="tech-project">
        <router-view></router-view>
      </div>
      </v-container>
    </v-col>
  </v-row>
</v-app>
</template>

<style></style>