import { defineStore } from 'pinia'
import axios from 'axios'

export const useSurveyStore = defineStore('survey', {
    state: () => ({
        surveys: [],
        currentSurvey: null,
        loading: false,
        error: null
    }),

    actions: {
        async fetchSurveys() {
            this.loading = true
            try {
                const response = await axios.get('/surveys')
                this.surveys = response.data.surveys
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch surveys'
            } finally {
                this.loading = false
            }
        },

        async fetchSurvey(id) {
            this.loading = true
            try {
                const response = await axios.get(`/surveys/${id}`)
                this.currentSurvey = response.data
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch survey'
            } finally {
                this.loading = false
            }
        },

        async createSurvey(surveyData) {
            this.loading = true
            try {
                const response = await axios.post('/surveys', surveyData)
                this.surveys.push(response.data)
                return response.data
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create survey'
                throw error
            } finally {
                this.loading = false
            }
        },

        async updateSurvey(id, surveyData) {
            this.loading = true
            try {
                const response = await axios.put(`/surveys/${id}`, surveyData)
                const index = this.surveys.findIndex(s => s.id === id)
                if (index !== -1) {
                    this.surveys[index] = response.data
                }
                return response.data
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to update survey'
                throw error
            } finally {
                this.loading = false
            }
        },

        async deleteSurvey(id) {
            this.loading = true
            try {
                await axios.delete(`/surveys/${id}`)
                this.surveys = this.surveys.filter(s => s.id !== id)
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to delete survey'
                throw error
            } finally {
                this.loading = false
            }
        }
    }
}) 