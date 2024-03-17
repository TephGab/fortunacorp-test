// pageReloadServices.js
import { onBeforeUnmount, onMounted } from "vue";

export default function usePreventPageReload() {
    const confirmLeave = (e) => {
      const message = "Are you sure you want to leave this page?";
      e.preventDefault();
      e.returnValue = message; // For some browsers
      return message;
    };

  const attachEvent = () => {
    window.addEventListener('beforeunload', confirmLeave);
  };

  const detachEvent = () => {
    window.removeEventListener('beforeunload', confirmLeave);
  };

  // Always attach the event when the component is mounted
  onMounted(() => {
    attachEvent();
  });

  // Ensure the event stays attached even when navigating away from the component
  onBeforeUnmount(() => {
    attachEvent();
  });

  return {
    detachEvent // If needed to detach manually in specific scenarios
  };
}
