import { getCurrentInstance } from 'vue';

export default () => {
	const instance = getCurrentInstance();
	if (instance) return instance.proxy.$vuetify;
};
