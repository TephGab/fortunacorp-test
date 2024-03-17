import { ref } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import router from '../router';
import useUtils from './utilsservices';

export default function useReplenishments() {
    const { isLoading, buttonIsLoading, buttonIsDisabled } = useUtils();
    const required_replenishments = ref([]);
    const replenishment = ref([]);
    const replenishments = ref([]);
    const replenishmentUser = ref([]);
    const envoyCashout = ref([]);
    const pendingCashout = ref([]);
    const totalCashoutsCount = ref([]);
    

    const getRequiredReplenishments = async (page = 1) => {
        isLoading.value = true;
        let response = await axios.get("/api/requiredreplenishments?page=" + page);
        required_replenishments.value = await response.data;
        isLoading.value = false;
    };

     const getReplenishments = async (page = 1) => {
        isLoading.value = true;
        let response = await axios.get("/api/replenishments?page=" + page);
        replenishments.value = await response.data;
        isLoading.value = false;
    };

    // const getTotalCashoutsCount = async (page = 1) => {
    //     let response = await axios.get("/api/totalreplenishmentscount");
    //     totalCashoutsCount.value = await response.data;
    // };

    const getReplenishment = async (id) => {
        let response = await axios.get("/api/replenishments/" + id);
        replenishment.value = await response.data;
    };

    const confirmReplenishment = async (data) => {
        buttonIsDisabled.value = true;
        let response = await axios.post("/api/confirmreplenishment", data);
        replenishment.value = await response.data;
        Swal.fire({
            text: 'Replenishment confirmed!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 4500
            });
         buttonIsDisabled.value = false;
    };

    const storeReplenishment = async (data) => {
        isLoading.value = true;
        buttonIsDisabled.value = true;
        await axios.post("/api/replenishments", data);
        Swal.fire({
            text: 'New replenishment!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 4500
            });
        buttonIsDisabled.value = false;
        isLoading.value = false;
        router.push({ name: 'replenishment.index' });
    };
    
    return {
        getReplenishments,
        replenishments,
        getReplenishment,
        replenishment,
        getRequiredReplenishments,
        required_replenishments,
        storeReplenishment,
        confirmReplenishment,
        buttonIsDisabled,
        isLoading
    };
}