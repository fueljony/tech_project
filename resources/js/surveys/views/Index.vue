<script setup>
import { storeToRefs } from 'pinia';
import { useSurveyStore } from '../store';
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';
window.toastr = toastr;

const surveyStore = useSurveyStore();
const { surveys } = storeToRefs(surveyStore);
surveyStore.loadInitialData();
const deleteSurvey = (id) => {
    surveyStore.deleteSurvey(id)
}

const updateSurveyTitle = (survey) => {
    surveyStore.editSurvey(survey)
}
const tableHeaders = [
  { text: 'Id', value: 'id', sortable: false, align: 'start' },
  { text: 'Title', value: 'name', sortable: false, align: 'start' },
  { text: 'Actions', value: 'actions', sortable: false, align: 'start' },
];
</script>

<template>
  <div>
    <v-data-table :headers="tableHeaders" :items="surveys">
        <template v-slot:item.name="props">
            <v-edit-dialog
                :return-value.sync="props.item.name"
                @save="updateSurveyTitle(props.item)"
                large
            >
                <div class="d-flex align-center">
                    {{props.item.name}}
                    <v-icon
                        small
                        class="ml-2"
                        color="grey lighten-1"
                    >
                        mdi-pencil
                    </v-icon>
                </div>
                <template v-slot:input>
                    <v-text-field
                        v-model="props.item.name"
                        label="Edit Title"
                        single-line
                    ></v-text-field>
                </template>

            </v-edit-dialog>
        </template>
        <template v-slot:item.actions="{ item }">
        <RouterLink :to="{
          name: 'survey.show',
          params: { id: item.id }
        }">
          <v-btn color="primary" outlined class="mr-3" small>View</v-btn>
        </RouterLink>
        <RouterLink :to="{
          name: 'survey.edit',
          params: { id: item.id }
        }">
          <v-btn small>Edit</v-btn>
        </RouterLink>
            <v-btn
                small
                color="error"
                class="ml-3"
                @click="deleteSurvey(item.id)"
            >
                Delete
            </v-btn>
        </template>
    </v-data-table>
  </div>
</template>

<style></style>
