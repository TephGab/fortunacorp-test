import { ref, onBeforeUnmount, onMounted } from "vue";
import Swal from "sweetalert2";
import router from '../router';
import moment from "moment";

export default function useUtils() {
    const isLoading = ref(false);
    const buttonIsDisabled = ref(false);
    const isButtonLoading = ref(false);
    const sendButtonDisabled = ref(false);
    const cancelButtonDisabled = ref(false);

    const formatDecimalNumber = (number) => {
        if(number){
            return number.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
        else{
            const number = 0;
            return number.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
    }

    // const formatDecimalNumber = (number) => {
    //    if(number){
    //         if(number.toString().includes('.')){
    //             return number.toFixed(2);
                // var parts = number.toString().split(".");
                // var formattedNumber = parts[0] + '.' + parts[1].substr(0,2);
                // return formattedNumber;
    //         }
    //         else{
    //             return number;
    //         }
    //    }
    //    else{
    //         return 0;
    //    }
    // }

    const storeSystemBankAccount = async (data) => {
        await axios.post("/api/systembankaccount", data);
        Swal.fire({
            text: 'New bank account registred!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 3500
            });
        // router.push({ name: 'users.index' });
    };

    const formatDate = (data) => {
        moment.locale("en");
        return moment(data).format("D MMMM, YYYY HH:mm");
    };

    const formatDateCalendar = (data) => {
        moment.locale("en");
        return moment(data).calendar();
    };


    /////////////////////////////////
    const confirmLeave = (e) => {
        const message = "Are you sure you want to leave this page?"
        e.preventDefault()
        e.returnValue = message // For some browsers
        return message
      }
    
      onMounted(() => {
        window.addEventListener('beforeunload', confirmLeave)
      })
    
      onBeforeUnmount(() => {
        window.removeEventListener('beforeunload', confirmLeave)
      })
      ////////////////////////////////////////
     
    return {
        isLoading,
        buttonIsDisabled,
        isButtonLoading,
        formatDecimalNumber,
        storeSystemBankAccount,
        sendButtonDisabled,
        cancelButtonDisabled,
        formatDate,
        formatDateCalendar
    };
}