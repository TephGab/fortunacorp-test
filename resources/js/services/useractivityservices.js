import { ref } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import router from '../router';
import useUtils from './utilsservices';


export default function useUserActivities() {
    const { isLoading, buttonIsLoading, buttonIsDisabled } = useUtils();
    const userActivities = ref([]);

    const getUserActivities = async (data) => {
        isLoading.value = true;
        let response = await axios.post("/api/userativities", data);
        userActivities.value = await response.data;
        isLoading.value = false;
    };
    
    return {
       getUserActivities,
       userActivities,
    };
}