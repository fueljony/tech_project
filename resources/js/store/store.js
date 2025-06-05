import { defineStore } from "pinia";
import { ref, reactive, computed } from "vue";

export const useAdminStore = defineStore("admin", () => {
  // state
  const isLocalDev = ref(false);
  const appDomain = ref("");
  const intelvioDomain = ref(JSON.parse(document.getElementById('intelvio_domain')?.value ?? "{}"));
  const appUrl = ref("");
  const drawerBar = ref(null);
  const dark = ref(true);
  const user = reactive({});
  const userName = ref("");
  const userRoles = ref([]);
  const addUpTitle = ref("");
  const errorMessage = ref("");
  const successMessage = ref("");
  const isProcessing = ref(false);
  const isTableProcessing = ref(false);
  const isModalProcessing = ref(false);
  const isFormProcessing = ref(false);
  // computed
  const isAdmin = computed(
    () => userRoles.value.find((role) => role === "super-admin") || false
  );
  const studentAutoLoginUrl = computed(
    () => (email) =>
      `http://students.${intelvioDomain.value}/instant_login?h=${btoa(email)}`
  );

  return {
    isLocalDev,
    appDomain,
    intelvioDomain,
    appUrl,
    drawerBar,
    dark,
    user,
    userName,
    userRoles,
    addUpTitle,
    errorMessage,
    successMessage,
    isProcessing,
    isTableProcessing,
    isModalProcessing,
    isFormProcessing,
    isAdmin,
    studentAutoLoginUrl,
  };
});
