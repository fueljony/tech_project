import { defineStore } from "pinia";
import {ref, computed, triggerRef} from "vue";
import useProcessRequest from "@js/shared-composables/useProcessRequest";

export const useSurveyStore = defineStore(
  'surveyStore',
  () => {
    // state
    const surveysMap = ref(new Map()); // Using a Map as the source of truth
    const surveys = computed(() => Array.from(surveysMap.value.values()));
    const processRequest = useProcessRequest();
    /**
     *
     */
    // functions/actions
    const loadInitialData = async () => {
      await processRequest(
        { processing: 'isProcessing' },
        async () => {
          const { data } = await axios.get('/surveys');
          surveysMap.value = new Map(
              data.surveys.map(survey => [survey.id, survey])
          );


        },
        (error) => {
          // toastr.error(error?.response?.data?.message || "Error");
        }
      );
    };


      const getSurveyWithQuestionsById = async (id) => {
          await processRequest(
              { processing: 'isProcessing' },
              async () => {
                  const { data } = await axios.get(`/surveys/${id}`);
                  surveysMap.value.set(Number(id), data.survey)
                  triggerRef(surveysMap);
              },
              (error) => {
                  console.error(error)
              }
          );
      };

      const addQuestion = async (id, question) => (
          await processRequest( { processing: 'isProcessing' },
              async () => {
                  const { data } = await axios.post(`/survey/${id}/add_question`, question);
                  if (data.success) {
                      void getQuestionsBySurveyId(id) // I would prefer to return the updated question in data
                  }
              },
              (error) => {
                  console.error(error)
              })
      )

      const editQuestion = async (surveyId, questionId, question) => {
          console.log(typeof surveyId)
          await processRequest({processing: 'isProcessing'},
              async () => {
                  const {data} = await axios.put(`/survey/${surveyId}/update_question/${questionId}`, question);
                  const survey = surveysMap.value.get(surveyId);
                  if (data.success && survey) {
                      const updatedSurvey = {
                          ...survey,
                          questions: survey.questions.map(q =>
                              q.id == questionId ? question : q
                          )
                      };
                      surveysMap.value.set(surveyId, updatedSurvey);
                      triggerRef(surveysMap);
                  }
              },
              (error) => {
                  console.error(error)
              })
      }

      const deleteQuestion = async (surveyId, questionId) => {
          await processRequest(
              { processing: 'isProcessing' },
              async () => {

                  const { data } = await axios.delete(`/survey/${surveyId}/delete_question/${questionId}`);
                  const survey = surveysMap.value.get(surveyId);

                  if (data.success && survey) {
                      const updatedSurvey = {
                          ...survey,
                          questions: survey.questions.filter(question => question.id !== questionId)
                      };

                      surveysMap.value.set(surveyId, updatedSurvey);
                      triggerRef(surveysMap);
                  }
              },
              (error) => {
                  console.error(error);
              }
          );
      };



      const getQuestionsBySurveyId = async (id) => {
          await processRequest(
              { processing: 'isProcessing' },
              async () => {
                  const { data } = await axios.get(`/surveys/${id}`);
                  surveysMap.value.set(id, data.survey)
                  triggerRef(surveysMap);
              },
              (error) => {
                  console.error(error)
              }
          );
      }



      return {
      addQuestion,
      deleteQuestion,
      editQuestion,
      getQuestionsBySurveyId,
      getSurveyWithQuestionsById,
      loadInitialData,
      surveys,
      surveysMap,
    }
  });
