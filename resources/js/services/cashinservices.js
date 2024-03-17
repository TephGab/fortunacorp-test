import { ref } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import router from '../router';
import useUtils from './utilsservices';
import useTransactionUtils from './transactionutils/transactionutils';


export default function useCashin() {
    const { isLoading, buttonIsLoading, buttonIsDisabled, sendButtonDisabled, isButtonLoading } = useUtils();
    const { getDailyRate, dailyrateSale, dailyratePurchase, amount, receiverAmount, receiverAmountMinusP2pFee,
            moncashTransfertSiP2PFee, moncashTransfertSiCashOutFee, moncashReceptionSi,
            calculateMoncashFee, natcashTransfertSiP2PFee, natcashTransfertSiCashOutFee,
            calculateNatcashFee } = useTransactionUtils();
    const cashins = ref([]);
    const cashin = ref([]);
    const providercashin = ref([]);
    const accountscashin = ref([]);
    const isProviderExist = ref(true);
    const senderMinusP2pfeeEntreSi = ref(0);

    const getCashins = async (page = 1) => {
        isLoading.value = true;
        let response = await axios.get("/api/cashins?page=" + page);
        cashins.value = await response.data;
        isLoading.value = false;
    };

    const storeCashin = async (data) => {
        isButtonLoading.value = true;
        sendButtonDisabled.value = true;
        await axios.post("/api/cashins", data);
        Swal.fire({
            text: 'Done!',
            toast: true,
            position: 'top-right',
            icon: 'success',
            color: '#000',
            padding: '0',
            showConfirmButton: false,
            timer: 2500
            });
        //isButtonLoading.value = false;
        //sendButtonDisabled.value = false;
        router.push({ name: 'cashins.index' });
    };

    const getAccountsCashin = async (data) => {
        let response = await axios.post("/api/accountscashin", data);
        accountscashin.value = await response.data.data;
    };



    const checkIfProviderExist = async (phone) => {
        let response = await axios.post("/api/checkprovider", { phone: phone });
        if (response.data.length != 0) {
            providercashin.value = response.data;
            isProviderExist.value = true;
        } else {
            providercashin.value = response.data;
            isProviderExist.value = false;
        }
    };

    const cashinSort = async (type) => {
        let response = await axios.post("/api/cashinsort", type);
        cashins.value = await response.data;
    };

    const cachinHtgToPesos = async (amount, operation, type) => {
        if (amount > 100) {
            switch (operation) {
                case "reception_si":
                      // Check moncash fee for reception si
                        if (amount >= 20 && amount <= 99) {
                        moncashTransfertSiP2PFee.value = 2
                        senderMinusP2pfeeEntreSi.value = (amount - moncashTransfertSiP2PFee.value);
                        }
                        if (amount >= 100 && amount <= 249) {
                            moncashTransfertSiP2PFee.value = 4;
                            senderMinusP2pfeeEntreSi.value = (amount - moncashTransfertSiP2PFee.value);
                        }
                        if (amount >= 250 && amount <= 499) {
                            moncashTransfertSiP2PFee.value = 7;
                            senderMinusP2pfeeEntreSi.value = (amount - moncashTransfertSiP2PFee.value);
                        }
                        if (amount >= 500 && amount <= 999) {
                            moncashTransfertSiP2PFee.value = 10;
                            senderMinusP2pfeeEntreSi.value = (amount - moncashTransfertSiP2PFee.value);
                        }
                        if (amount >= 1000 && amount <= 1999) {
                            moncashTransfertSiP2PFee.value = 30;
                            senderMinusP2pfeeEntreSi.value = (amount - moncashTransfertSiP2PFee.value);
                        }
                        if (amount >= 2000 && amount <= 3999) {
                            moncashTransfertSiP2PFee.value = 40;
                            senderMinusP2pfeeEntreSi.value = (amount - moncashTransfertSiP2PFee.value);
                        }
                        if (amount >= 4000 && amount <= 7999) {
                            moncashTransfertSiP2PFee.value = 55;
                            senderMinusP2pfeeEntreSi.value = (amount - moncashTransfertSiP2PFee.value);
                        }
                        if (amount >= 8000 && amount <= 11999) {
                            moncashTransfertSiP2PFee.value = 70;
                            senderMinusP2pfeeEntreSi.value = (amount - moncashTransfertSiP2PFee.value);
                        }
                        if (amount >= 12000 && amount <= 19999) {
                            moncashTransfertSiP2PFee.value = 80;
                            senderMinusP2pfeeEntreSi.value = (amount - moncashTransfertSiP2PFee.value);
                        }
                        if (amount >= 20000 && amount <= 39999) {
                            moncashTransfertSiP2PFee.value = 90;
                            senderMinusP2pfeeEntreSi.value = (amount - moncashTransfertSiP2PFee.value);
                        }
                        if (amount >= 40000 && amount <= 59999) {
                            moncashTransfertSiP2PFee.value = 110;
                            senderMinusP2pfeeEntreSi.value = (amount - moncashTransfertSiP2PFee.value);
                        }
                        if (amount >= 60000 && amount <= 75125) {
                            moncashTransfertSiP2PFee.value = 125;
                            senderMinusP2pfeeEntreSi.value = (amount - moncashTransfertSiP2PFee.value);
                        }
                        if (amount > 75125) {
                            moncashTransfertSiP2PFee.value = 0;
                            senderMinusP2pfeeEntreSi.value = (amount - moncashTransfertSiP2PFee.value);
                        }
                    // End check moncash fee for reception si
                  //  let receiverAmountConvertedToPesos = amount / dailyratePurchase.value;
                    switch (type) {
                        case "moncash":
                            calculateMoncashFee(senderMinusP2pfeeEntreSi.value,operation,0);
                            break;
                        case "natcash":
                            calculateNatcashFee(amount,operation);
                            break;
                        default:
                        // code block
                    }
                    break;
                case "reception":
                    //receiverAmountConvertedToPesos = amount / dailyratePurchase.value;
                    switch (type) {
                        case "moncash":
                            calculateMoncashFee(amount,operation,0);
                            break;
                        case "natcash":
                            calculateNatcashFee(amount,operation);
                            break;
                        default:
                        // code block
                    }
                    break;
                default:
                // code block
            }
        }
    }

    
    return {
       cashins,
       getCashins,
       storeCashin,
       accountscashin,
       getAccountsCashin,
       checkIfProviderExist,
       isProviderExist,
       providercashin,
       cachinHtgToPesos,
       dailyrateSale,
       dailyratePurchase,
       amount, 
       receiverAmount,
       receiverAmountMinusP2pFee,
       cashinSort,
       isLoading,
       buttonIsLoading,
       buttonIsDisabled,
       sendButtonDisabled,
       isButtonLoading
    };
}