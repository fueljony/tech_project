import usePageProgress from "./usePageProgress";

export default () => {
  // composables
  const pageProgress = usePageProgress();

  return async ({ processing }, handler, errorHandler = () => {}) => {
    try {
      pageProgress({ processing, value: true });
      await handler();
      pageProgress({ processing, value: false });
    } catch (error) {
      pageProgress({ processing, value: false });
      errorHandler(error);
      //console.log(error);
    }
  };
};
