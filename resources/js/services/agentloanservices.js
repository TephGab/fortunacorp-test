import { ref } from "vue";
import axios from "axios";
import moment from "moment";
import Swal from "sweetalert2";
import router from '../router';
import useUtils from './utilsservices';
import jsPDF from 'jspdf';
import 'jspdf-autotable';


export default function useUsers() {
    const { isLoading, buttonIsLoading, buttonIsDisabled, isButtonLoading, sendButtonDisabled, formatDate } = useUtils();
    const loans = ref([]);

    const getAgentLoans = async (page = 1) => {
        isLoading.value = true;
        let response = await axios.get("/api/agentloan?page=" + page);
        loans.value = await response.data;
        isLoading.value = false;
    };

    const storeAgentLoan = async (data) => {
        let response = await axios.post("/api/agentloan", data);
        loans.value = response.data;
        Swal.fire({
            text: 'Agent loan success!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 4500
            });
        router.push({ name: 'agentloan.index' });
    };

    const confirmAgentLoan = async (data) => {
        let response = await axios.post("/api/agentloanconfirmation", data);
        loans.value = response.data;
        Swal.fire({
            text: 'Agent loan confirmed!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 4500
            });
        router.push({ name: 'agentloan.index' });
    };

    return {
       loans,
       getAgentLoans,
       storeAgentLoan,
       confirmAgentLoan,
    };
}
