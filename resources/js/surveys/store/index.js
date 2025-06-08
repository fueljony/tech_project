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

      const deleteSurvey = async (id) => {
          if (!confirm('Are you sure you want to delete this survey?')) {
              return;
          }

          await processRequest(
              { processing: 'isProcessing' },
              async () => {
                  await axios.delete(`/surveys/${id}`);
                  surveysMap.value.delete(id);
                  triggerRef(surveysMap);
                  toastr.success('Survey deleted successfully');
              },
              (error) => {
                  console.error('Error deleting survey:', error);
                  toastr.error('Failed to delete survey');
              }
          );
      }

      const editSurvey = async (survey) => {
          await processRequest(
              { processing: 'isProcessing' },
              async () => {
                  const { data } = await axios.patch(`/surveys/${survey.id}`, {
                      name: survey.name
                  });
                  surveysMap.value.set(survey.id, data.survey);
                  triggerRef(surveysMap);
                  toastr.success('Survey updated successfully');
              },
              (error) => {
                  console.error('Error updating survey:', error);
                  toastr.error('Failed to update survey');
              }
          );
      }

      return {
      addQuestion,
      deleteQuestion,
      deleteSurvey,
      editQuestion,
      editSurvey,
      getQuestionsBySurveyId,
      getSurveyWithQuestionsById,
      loadInitialData,
      surveys,
      surveysMap,
    }
  });
