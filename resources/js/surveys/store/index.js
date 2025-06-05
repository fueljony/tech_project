import { defineStore } from "pinia";
import { ref } from "vue";
import useProcessRequest from "@js/shared-composables/useProcessRequest";

export const useSurveyStore = defineStore(
  'surveyStore',
  () => {
    // state
    const surveys = ref();
    const processRequest = useProcessRequest();
    /**
     *
     */
    // functions/actions

    const loadInitialData = async () => {
      await processRequest(
        { processing: 'isProcessing' },
        async () => {
          const { data } = await axios.get('');
          console.log(data, 'data');
          surveys.value = data.surveys;

        },
        (error) => {
          // toastr.error(error?.response?.data?.message || "Error");
        }
      );
    };
    return {
      surveys,
      loadInitialData,
    }
  });