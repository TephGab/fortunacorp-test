import { ref } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import router from '../router';
import useUtils from './utilsservices';

export default function useLoanRepays() {
    const { isLoading, buttonIsLoading, buttonIsDisabled } = useUtils();
    const loanRepayments = ref([]);
    const loanRepayment = ref([]);

    const getAgentLoanRepayments = async (page = 1) => {
        isLoading.value = true;
        let response = await axios.get("/api/agentloanrepay?page=" + page);
        loanRepayments.value = await response.data;
        isLoading.value = false;
    };

    const getAgentLoanRepayment = async (id) => {
        let response = await axios.get("/api/agentloanrepay/" + id);
        loanRepayment.value = await response.data;
    };

    const confirmAgentLoanRepayment = async (data) => {
        buttonIsDisabled.value = true;
        let response = await axios.post("/api/confirmagentloanrepay", data);
        loanRepayment.value = await response.data;
        Swal.fire({
            text: 'Payment confirmed!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 4500
            });
        buttonIsDisabled.value = false;
        router.push({ name: 'agentloanrepay.index' });
    };

    const storeAgentLoanRepay = async (data) => {
        buttonIsDisabled.value = true;
        await axios.post("/api/agentloanrepay", data);
        Swal.fire({
            text: 'Deposit registred!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 3500
            });
        buttonIsDisabled.value = false;
        router.push({ name: 'agentloanrepay.index' });
    };
    
    return {
        getAgentLoanRepayments,
        loanRepayments,
        confirmAgentLoanRepayment,
        storeAgentLoanRepay,

        getAgentLoanRepayment,
        loanRepayment, 
        confirmAgentLoanRepayment
    };
}