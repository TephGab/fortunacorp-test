import { ref } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import router from '../router';
import useUtils from './utilsservices';

export default function useCashouts() {
    const { isLoading, buttonIsLoading, buttonIsDisabled } = useUtils();
    const cashouts = ref([]);
    const cashout = ref([]);
    const cashoutUser = ref([]);
    const envoyCashout = ref([]);
    const pendingCashout = ref([]);
    const totalCashoutsCount = ref([]);
    

    const getCashouts = async (page = 1) => {
        isLoading.value = true;
        let response = await axios.get("/api/cashouts?page=" + page);
        cashouts.value = await response.data;
        isLoading.value = false;
    };

    const getTotalCashoutsCount = async (page = 1) => {
        let response = await axios.get("/api/totalcashoutscount");
        totalCashoutsCount.value = await response.data;
    };

    const getCashout = async (id) => {
        let response = await axios.get("/api/cashouts/" + id);
        cashout.value = await response.data;
    };

    const getUserCashout = async (id) => {
        let response = await axios.post("/api/usercashout", {'id': id});
        cashout.value = response.data;
    };

    const getCashoutDetails = async (data) => {
        let response = await axios.post("/api/getcashoutdetails", data);
        cashout.value = await response.data;
        cashoutUser.value = await response.data.user;
    };

    const confirmCashout = async (data) => {
        buttonIsDisabled.value = true;
        let response = await axios.post("/api/confirmcashout", data);
        cashout.value = await response.data;
        Swal.fire({
            text: 'Cashout confirmed!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 4500
            });
         buttonIsDisabled.value = false;
         router.push({ name: 'cashout.index' });
    };

    const completeCashout = async (data) => {
        buttonIsDisabled.value = true;
        let response = await axios.post("/api/completecashout", data);
        cashout.value = await response.data;
        Swal.fire({
            text: 'Cashout completed!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 4500
            });
        buttonIsDisabled.value = false;
        router.push({ name: 'cashout.index' });
    };

    const getEnvoyCashout = async (id) => {
        let response = await axios.post("/api/getenvoycashout", {'id': id});
        envoyCashout.value = response.data;
    };

    const storeCashout = async (data) => {
        buttonIsDisabled.value = true;
        await axios.post("/api/cashouts", data);
        Swal.fire({
            text: 'Cashout registred!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 4500
            });
        buttonIsDisabled.value = false;
        router.push({ name: 'cashout.index' });
    };

    const checkPendingCashout = async (id) => {
        let response = await axios.post("/api/checkpendingcashout", {'id': id});
        pendingCashout.value = response.data;
    };
    
    return {
        getCashouts,
        cashouts,
        getCashout,
        getUserCashout,
        getCashoutDetails,
        cashout,
        storeCashout,
        getEnvoyCashout,
        envoyCashout,
        cashoutUser,
        confirmCashout,
        completeCashout,
        checkPendingCashout,
        pendingCashout,
        getTotalCashoutsCount,
        totalCashoutsCount,
        buttonIsDisabled,
    };
}