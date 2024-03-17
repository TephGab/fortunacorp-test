import { ref } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import router from '../router';
import useUtils from './utilsservices';


export default function useExpenses() {
    const { isLoading, buttonIsLoading, buttonIsDisabled } = useUtils();
    const expenses = ref([]);
    const expense = ref([]);
    const expensetypes = ref([]);

    const getExpenses = async (page = 1) => {
        isLoading.value = true;
        let response = await axios.get("/api/expenses?page=" + page);
        expenses.value = await response.data;
        isLoading.value = false;
    };

    const getExpensetypes = async (page = 1) => {
        isLoading.value = true;
        let response = await axios.get("/api/expensetypes");
        expensetypes.value = await response.data;
        isLoading.value = false;
    };

    // const getAccount = async (id) => {
    //     let response = await axios.get("/api/accounts/" + id);
    //     account.value = await response.data;
    // };

    const storeExpense = async (data) => {
        await axios.post("/api/expenses", data);
        Swal.fire({
            text: 'Registred!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 4500
            });
        router.push({ name: 'expenses.index' });
    };

    // const updateAccount = async (id) => {
    //     let response = await axios.put("/api/accounts/" + id, account.value);
    //     accounts.value = response.data;
    //     Swal.fire({
    //         text: 'Account updated!',
    //         toast: true,
    //         position: 'top-right',
    //         icon: 'success',
    //         color: '#000',
    //         padding: '0',
    //         showConfirmButton: false,
    //         timer: 2500
    //         });
    //     router.push({ name: 'accounts.index' });
    // };


    
    return {
       expenses,
       getExpenses,
       storeExpense,
       isLoading,
       getExpensetypes,
       expensetypes
    };
}