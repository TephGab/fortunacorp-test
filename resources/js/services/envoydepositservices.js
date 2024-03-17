import { ref } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import router from '../router';
import useUtils from './utilsservices';

export default function useEnvoyDeposits() {
    const { isLoading, buttonIsLoading, buttonIsDisabled, isButtonLoading, sendButtonDisabled } = useUtils();
    const envoyDeposits = ref([]);
    const envoyDeposit = ref([]);
    const envoyTransferts = ref([]);

    const getEnvoyDeposits = async (page = 1) => {
        isLoading.value = true;
        let response = await axios.get("/api/envoydeposits?page=" + page);
        envoyDeposits.value = await response.data;
        isLoading.value = false;
    };

    const storeEnvoyDeposit = async (data) => {
        isButtonLoading.value = true;
        await axios.post("/api/envoydeposits", data);
        Swal.fire({
            text: 'Deposit registred!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 4500
            });
        router.push({ name: 'envoydeposits.index' });
        // isButtonLoading.value = false;
    };
  
    const confirmEnvoyDeposit = async (data) => {
        buttonIsDisabled.value = true;
        let response = await axios.post("/api/confirmenvoydeposit", data);
        //envoyDeposit.value = await response.data;
        Swal.fire({
            text: 'Deposit confirmed!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 5500
            });
        buttonIsDisabled.value = false;
        router.push({ name: 'envoydeposits.index' });
    };

    const getEnvoyTransferts = async (page = 1) => {
        isLoading.value = true;
        let response = await axios.get("/api/envoytransferts?page=" + page);
        envoyTransferts.value = await response.data;
        isLoading.value = false;
    };

    const storeEnvoyTransfert = async (data) => {
        isButtonLoading.value = true;
        await axios.post("/api/envoytransferts", data);
        Swal.fire({
            text: 'Transfert registred!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 4500
            });
        router.push({ name: 'envoytransferts.index' });
        // isButtonLoading.value = false;
    };

    const confirmEnvoyTransfert = async (data) => {
        buttonIsDisabled.value = true;
        let response = await axios.post("/api/envoytransfertsconfirm", data);
        Swal.fire({
            text: 'Confirmed!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 5500
            });
        buttonIsDisabled.value = false;
        router.push({ name: 'envoytransferts.index' });
    };
    
    return {
        getEnvoyDeposits,
        envoyDeposits,
        storeEnvoyDeposit,
        confirmEnvoyDeposit,
        isLoading,
        buttonIsDisabled,
        isButtonLoading,
        sendButtonDisabled,
        storeEnvoyTransfert,
        getEnvoyTransferts,
        envoyTransferts,
        confirmEnvoyTransfert
    };
}