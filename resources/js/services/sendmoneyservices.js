import { ref } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import router from '../router';
import useUtils from './utilsservices';

export default function useSendMoney() {
    const { isLoading, buttonIsLoading, buttonIsDisabled } = useUtils();
    const required_money_to_send = ref([]);
    const sendmoney = ref([]);
    const sendmoneys = ref([]);
    const replenishmentUser = ref([]);
    const envoyCashout = ref([]);
    const pendingCashout = ref([]);
    const totalCashoutsCount = ref([]);
    

    // const getRequiredReplenishments = async (page = 1) => {
    //     isLoading.value = true;
    //     let response = await axios.get("/api/requiredreplenishments?page=" + page);
    //     required_replenishments.value = await response.data;
    //     isLoading.value = false;
    // };

     const getSendMoneys = async (page = 1) => {
        isLoading.value = true;
        let response = await axios.get("/api/sendmoney?page=" + page);
        sendmoneys.value = await response.data;
        isLoading.value = false;
    };

    // const getTotalCashoutsCount = async (page = 1) => {
    //     let response = await axios.get("/api/totalreplenishmentscount");
    //     totalCashoutsCount.value = await response.data;
    // };

    const getSendMoney = async (id) => {
        let response = await axios.get("/api/sendmoneys/" + id);
        sendmoney.value = await response.data;
    };

    const confirmSendMoney = async (data) => {
        buttonIsDisabled.value = true;
        let response = await axios.post("/api/confirmsendmoney", data);
        sendmoney.value = await response.data;
        Swal.fire({
            text: 'Confirmed!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 10500
            });
         buttonIsDisabled.value = false;
    };

    const storeSendMoney = async (data) => {
        isLoading.value = true;
        buttonIsDisabled.value = true;
        await axios.post("/api/sendmoney", data);
        Swal.fire({
            text: 'Done!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 10500
            });
        // buttonIsDisabled.value = false;
        // isLoading.value = false;
        router.push({ name: 'sendmoney.index' });
    };
    
    return {
        getSendMoneys,
        sendmoneys,
        getSendMoney,
        sendmoney,
        // getRequiredReplenishments,
        // required_replenishments,
        storeSendMoney,
        confirmSendMoney,
        buttonIsDisabled,
        isLoading
    };
}