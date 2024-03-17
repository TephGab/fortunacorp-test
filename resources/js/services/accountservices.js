import { ref } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import router from '../router';
import useUtils from './utilsservices';


export default function useAccounts() {
    const { isLoading, buttonIsLoading, buttonIsDisabled, isButtonLoading } = useUtils();
    const accounts = ref([]);
    const account = ref([]);
    const accountTotalMonthlyTransactions = ref('');
    const totalSum = ref(0);
    const operatorAccounts = ref([]);

    const getAccounts = async (page = 1) => {
        isLoading.value = true;
        let response = await axios.get("/api/accounts?page=" + page);
        accounts.value = await response.data;
        isLoading.value = false;
    };

    const getAccount = async (id) => {
        let response = await axios.get("/api/accounts/" + id);
        account.value = await response.data;
    };

    const getAccountTotalMonthlyTransactions = async (id) => {
        let response = await axios.post("/api/accounttotalmonthlytransactions", {'id':id});
        if (response.data.total_monthly_transactions == 0) {
            accountTotalMonthlyTransactions.value = await 'no_transactions_yet';
        }else{
            accountTotalMonthlyTransactions.value = await response.data.total_monthly_transactions;
        }
    };

    const accountSort = async (type) => {
        let response = await axios.post("/api/accountsort", type);
        accounts.value = await response.data;
    };

    const storeAccount = async (data) => {
        isButtonLoading.value = true;
        await axios.post("/api/accounts", data);
        Swal.fire({
            text: 'New account registred!',
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

    const getOperatorAccounts = async (data) => {
        let response = await axios.post("/api/getoperatoraccounts", data);
        operatorAccounts.value = response.data;
    };

    const updateAccount = async (id) => {
        isButtonLoading.value = true;
        let response = await axios.put("/api/accounts/" + id, account.value);
        accounts.value = response.data;
        Swal.fire({
            text: 'Account updated!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 2500
            });
        router.push({ name: 'accounts.index' });
    };

    const destroyAccount = async (id) => {
        await Swal.fire({
            title: 'Are you sure you want to this account?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
          }).then((result) => {
            if (result.isConfirmed) {
                axios.delete("/api/accounts/" + id);
                getAccounts();
                router.push({ name: 'accounts.index' });
              Swal.fire({
                toast: true,
                position: 'top-right',
                icon: 'info',
                title: 'Account deleted!',
                showConfirmButton: false,
                color: '#fff',
                background: '#87adbd',
                timer: 1500,
                timerProgressBar: true
              }
              )
            }
          })
    };

    const calculAccountBalanceSum = async () => {
        let response = await axios.get("/api/accountsum");
        totalSum.value = await response.data;
    };

    
    return {
       accounts,
       getAccounts,
       account,
       getAccount,
       storeAccount,
       updateAccount,
       destroyAccount,
       isLoading,
       buttonIsLoading,
       buttonIsDisabled,
       accountSort,
       getAccountTotalMonthlyTransactions,
       accountTotalMonthlyTransactions,
       calculAccountBalanceSum,
       totalSum,
       isButtonLoading,
       getOperatorAccounts,
       operatorAccounts
    };
}