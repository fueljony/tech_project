import { storeToRefs } from "pinia";
import { useAdminStore } from "../store/store";

export default () => {
  // store
  const store = useAdminStore();
  const {
    isProcessing,
    isTableProcessing,
    isModalProcessing,
    isFormProcessing,
  } = storeToRefs(store);

  return ({ processing, value }) => {
    if (processing === "isTableProcessing") {
      isTableProcessing.value = value;
    } else if (processing === "isModalProcessing") {
      isModalProcessing.value = value;
    } else if (processing === "isFormProcessing") {
      isFormProcessing.value = value;
    } else {
      // page loader
      isProcessing.value = value;
    }
  };
};
