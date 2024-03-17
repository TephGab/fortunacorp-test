import { ref } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import router from '../router';
import useUtils from './utilsservices';


export default function useAccountActivities() {
    const { isLoading, buttonIsLoading, buttonIsDisabled } = useUtils();
    const accountActivities = ref([]);

    const getAccountActivities = async (data) => {
        isLoading.value = true;
        let response = await axios.post("/api/accountativities", data);
        accountActivities.value = await response.data;
        isLoading.value = false;
    };
    
    return {
       getAccountActivities,
       accountActivities,
    };
}