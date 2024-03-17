import { ref } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import router from '../router';
import useUtils from './utilsservices';


export default function useAccountTransferts() {
    const { isLoading, buttonIsLoading, buttonIsDisabled } = useUtils();
    const accountstransferts = ref([]);
    const allaccountstransferts = ref([]);

    const getAllAccountsTransferts = async (page = 1) => {
        isLoading.value = true;
        let response = await axios.get("/api/accountstransferts?page=" + page);
        allaccountstransferts.value = await response.data;
        isLoading.value = false;
    };

    const getAccountsTransferts = async (data) => {
        let response = await axios.post("/api/getaccountstransferts", data);
        accountstransferts.value = await response.data.data;
    };

    const storeAccountTransfert = async (data) => {
        let response = await axios.post("/api/accountstransferts", data);
        Swal.fire({
            text: 'Account transfert success!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 4500
            });
        router.push({ name: 'accounts.index' });
    };
    
    return {
       accountstransferts,
       getAccountsTransferts,
       storeAccountTransfert,
       getAllAccountsTransferts,
       allaccountstransferts,
       isLoading
    };
}