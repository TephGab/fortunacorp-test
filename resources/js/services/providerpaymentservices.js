import { ref } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import router from '../router';
import useUtils from './utilsservices';


export default function useProviderPayments() {
    const { isLoading, buttonIsLoading, buttonIsDisabled, sendButtonDisabled, isButtonLoading } = useUtils();
    const providerpayments = ref([]);
    const allaccountstransferts = ref([]);
    const providers = ref([]);

    const getProviderPayments = async (page = 1) => {
        isLoading.value = true;
        let response = await axios.get("/api/providerpayments?page=" + page);
        providerpayments.value = await response.data;
        isLoading.value = false;
    };

    const getProvidersToPay = async () => {
        let response = await axios.get("/api/providerstopay");
        providers.value = await response.data.data;
    };

    const storeProviderPayment = async (data) => {
        let response = await axios.post("/api/providerpayments", data);
        Swal.fire({
            text: 'Payment success!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 4500
            });
        router.push({ name: 'providerpayments.index' });
    };
    
    return {
       providerpayments,
       getProvidersToPay,
       getProviderPayments,
       storeProviderPayment,
       providers,
       isLoading, 
       buttonIsLoading, 
       buttonIsDisabled,
       sendButtonDisabled,
       isButtonLoading
    };
}