//import VuexPersistence from 'vuex-persist';

import {
	SET_DRAWER_BAR,
	SET_THEME,
	SET_USER,
	SET_USERNAME,
	SET_USER_ROLES,
	SET_ERROR_MESSAGE,
	SET_SUCCESS_MESSAGE,
	SET_ADD_UP_TITLE,
} from './app-mutations';

import courseAdminTrainingStore from '../training/course/store';
import moduleSectionsAdminTrainingStore from '../training/sections/store';
import moduleSectionQuizzesAdminTrainingStore from '../training/quizzes/store';
import practiceExamAdminTrainingStore from '../training/practice_exam/store';
import moduleSectionHighlightsAdminTrainingStore from '../training/highlights/store';
import reviewQuizzesAdminTrainingStore from '../training/review_quizzes/store';
import externshipFacilitiesDatesAdminStore from '../externship/calendar/store';
import externshipFacilitiesDatesStudentAdminStore from '../externship/calendar-student/store';

import externshipStudentsIndexAdminStore from '../externship/students/store';
import externshipStudentsAssignmentAdminStore from '../externship/students-assignment/store';

// const vuexLocal = new VuexPersistence({
//   storage: window.localStorage,
//   modules: ['admin/training/module/sections'],
// });

const store = {
	state: () => ({
		intelvioDomain: JSON.parse(document.getElementById('intelvio_domain').value),
		drawerBar: null,
		dark: true,
		user: null,
		userName: '',
		userRoles: [],
		addUpTitle: '',
		errorMessage: '',
		successMessage: '',
		isProcessing: false,
		isModalProcessing: false,
	}),
	mutations: {
		[SET_DRAWER_BAR](state, payload) {
			state.drawerBar = payload;
		},
		[SET_THEME](state, payload) {
			state.dark = payload;
		},
		[SET_USER]: (state, payload) => {
			state.user = {
				...state.user,
				...payload,
			};
		},
		[SET_USERNAME](state, payload) {
			state.userName = payload;
		},
		[SET_USER_ROLES]: (state, payload) => {
			state.userRoles = payload;
		},
		[SET_ADD_UP_TITLE](state, payload) {
			state.addUpTitle = payload;
		},
		[SET_ERROR_MESSAGE](state, payload) {
			state.errorMessage = payload;
		},
		[SET_SUCCESS_MESSAGE](state, payload) {
			state.successMessage = payload;
		},
		['IS_PROCESSING']: (state, payload) => {
			state.isProcessing = payload;
		},
		['IS_MODAL_PROCESSING']: (state, payload) => {
			state.isModalProcessing = payload;
		},
	},
	actions: {},
	getters: {},
	modules: {
		'admin/training/modules': courseAdminTrainingStore,
		'admin/training/review_quizzes': reviewQuizzesAdminTrainingStore,
		'admin/training/module/sections': moduleSectionsAdminTrainingStore,
		'admin/training/module/section/quizzes':
			moduleSectionQuizzesAdminTrainingStore,
		'admin/training/practice_exam': practiceExamAdminTrainingStore,
		'admin/training/module/section/highlights':
			moduleSectionHighlightsAdminTrainingStore,
		'admin/externship/calendar': externshipFacilitiesDatesAdminStore,
		'admin/externship/calendar/student':
			externshipFacilitiesDatesStudentAdminStore,
		//'admin/externship/reports/students': externshipReportsStudentsAdminStore,
		'admin/externship/students/index': externshipStudentsIndexAdminStore,
		'admin/externship/students/assignment':
			externshipStudentsAssignmentAdminStore,
		//'admin/externship/student/info': externshipStudentInfoAdminStore,
	},
	//plugins: [vuexLocal.plugin],
};

export default store;
