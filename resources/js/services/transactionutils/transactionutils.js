import { ref } from "vue";
import Swal from "sweetalert2";

export default function useTransactionVariables() {
    const senderAmount = ref(0);
    const receiverAmount = ref(0);
    const receiverAmountMinusP2pFee = ref(0);
    const dailyrateSale = ref(0);
    const dailyratePurchase = ref(0);
    const moncashTransfertSiP2PFee = ref(0);
    const moncashTransfertSiCashOutFee = ref(0);
    const natcashTransfertSiP2PFee = ref(0);
    const natcashTransfertSiCashOutFee = ref(0);

    const getDailyRate = async () => {
        let response = await axios.get("/api/dailyrate");
        dailyrateSale.value = await response.data.dailyRateSale;
        dailyratePurchase.value = await response.data.dailyRatePurchase;
    };

    getDailyRate();
    
    const checkMoncashAmountLimit = async (amount, operation) => {
        if(operation == 'transfert_si'){
            if(amount > 75120){
                Swal.fire({
                    icon: 'info',
                    text: 'Max amount for moncash transafert_si is 75120 HTG'
                  })
              }
        }
        if(operation == 'reception_si'){
            if(amount > 75125){
                Swal.fire({
                    icon: 'info',
                    text: 'Max amount for moncash reception_si is 75125 HTG'
                  })
              }
        }
        if(operation == 'transfert'){
            if(amount > 75000){
                Swal.fire({
                    icon: 'info',
                    text: 'Max amount for moncash transafert is 75000 HTG'
                  })
              }
        }
        if(operation == 'reception'){
            if(amount > 500000000){
                Swal.fire({
                    icon: 'info',
                    text: 'Max amount for moncash reception is 500000000 HTG'
                  })
              }
        }
    }

    const checkNatcashAmountLimit = async (amount, operation) => {
        if(operation == 'transfert_si'){
            if(amount > 41040.1){
                Swal.fire({
                    icon: 'info',
                    text: 'Max amount for natcash transafert_si is 41040.1 HTG'
                  })
              }
        }
        if(operation == 'reception_si'){
            if(amount > 40274){
                Swal.fire({
                    icon: 'info',
                    text: 'Max amount for natcash reception_si is 40274 HTG'
                  })
              }
        }
        if(operation == 'transfert'){
            if(amount > 40999){
                Swal.fire({
                    icon: 'info',
                    text: 'Max amount for natcash transafert is 40999 HTG'
                  })
              }
        }
        if(operation == 'reception'){
            if(amount > 500000000){
                Swal.fire({
                    icon: 'info',
                    text: 'Max amount for natcash reception is 500000000 HTG'
                  })
              }
        }
    }
    // Moncash
    const calculateMoncashFee = async (amount, operation, fortunafee) => {
        switch (operation) {
            case "transfert_si":
            case "transfert":
                if (amount >= 20 && amount <= 99) {
                    moncashTransfertSiP2PFee.value = 0;
                    moncashTransfertSiCashOutFee.value = 0;
                    receiverAmountMinusP2pFee.value = amount - moncashTransfertSiP2PFee.value;
                    receiverAmount.value = amount;
                }
                if (amount >= 100 && amount <= 249) {
                    moncashTransfertSiP2PFee.value = 0;
                    moncashTransfertSiCashOutFee.value = 0;
                    receiverAmountMinusP2pFee.value = amount - moncashTransfertSiP2PFee.value;
                    receiverAmount.value = amount;
                }
                if (amount >= 250 && amount <= 499) {
                    moncashTransfertSiP2PFee.value = 6;
                    moncashTransfertSiCashOutFee.value = 13;
                    receiverAmountMinusP2pFee.value = amount - moncashTransfertSiP2PFee.value;
                    receiverAmount.value = amount;
                }
                if (amount >= 500 && amount <= 999) {
                    moncashTransfertSiP2PFee.value = 9;
                    moncashTransfertSiCashOutFee.value = 30;
                    receiverAmountMinusP2pFee.value = amount - moncashTransfertSiP2PFee.value;
                    receiverAmount.value = amount;
                }
                if (amount >= 1000 && amount <= 1999) {
                    moncashTransfertSiP2PFee.value = 25;
                    moncashTransfertSiCashOutFee.value = 60;
                    receiverAmountMinusP2pFee.value = amount - moncashTransfertSiP2PFee.value;
                    receiverAmount.value = amount;
                }
                if (amount >= 2000 && amount <= 3999) {
                    moncashTransfertSiP2PFee.value = 35;
                    moncashTransfertSiCashOutFee.value = 105;
                    receiverAmountMinusP2pFee.value = amount - moncashTransfertSiP2PFee.value;
                    receiverAmount.value = amount;
                }
                if (amount >= 4000 && amount <= 7999) {
                    moncashTransfertSiP2PFee.value = 50;
                    moncashTransfertSiCashOutFee.value = 171;
                    receiverAmountMinusP2pFee.value = amount - moncashTransfertSiP2PFee.value;
                    receiverAmount.value = amount;
                }
                if (amount >= 8000 && amount <= 11999) {
                    moncashTransfertSiP2PFee.value = 60;
                    moncashTransfertSiCashOutFee.value = 247;
                    receiverAmountMinusP2pFee.value = amount - moncashTransfertSiP2PFee.value;
                    receiverAmount.value = amount;
                }
                if (amount >= 12000 && amount <= 19999) {
                    moncashTransfertSiP2PFee.value = 70;
                    moncashTransfertSiCashOutFee.value = 366;
                    receiverAmountMinusP2pFee.value = amount - moncashTransfertSiP2PFee.value;
                    receiverAmount.value = amount;
                }
                if (amount >= 20000 && amount <= 39999) {
                    moncashTransfertSiP2PFee.value = 75;
                    moncashTransfertSiCashOutFee.value = 629;
                    receiverAmountMinusP2pFee.value = amount - moncashTransfertSiP2PFee.value;
                    receiverAmount.value = amount;
                }
                if (amount >= 40000 && amount <= 59999) {
                    moncashTransfertSiP2PFee.value = 100;
                    moncashTransfertSiCashOutFee.value = 1011;
                    receiverAmountMinusP2pFee.value = amount - moncashTransfertSiP2PFee.value;
                    receiverAmount.value = amount;
                }
                if (amount >= 60000 && amount <= 75120) {
                    moncashTransfertSiP2PFee.value = 120;
                    moncashTransfertSiCashOutFee.value = 1368;
                    receiverAmountMinusP2pFee.value = amount - moncashTransfertSiP2PFee.value;
                    receiverAmount.value = amount;
                }
                if (amount > 75120) {
                    checkMoncashAmountLimit(amount, operation);
                }
                break;
                // case "transfert":
                // if (amount >= 20 && amount <= 99) {
                //     moncashTransfertSiP2PFee.value = 0;
                //     moncashTransfertSiCashOutFee.value = 0;
                //     receiverAmountMinusP2pFee.value = amount - moncashTransfertSiP2PFee.value;
                //     receiverAmount.value = amount;
                // }
                // if (amount >= 100 && amount <= 249) {
                //     moncashTransfertSiP2PFee.value = 0;
                //     moncashTransfertSiCashOutFee.value = 0;
                //     receiverAmountMinusP2pFee.value = amount - moncashTransfertSiP2PFee.value;
                //     receiverAmount.value = amount;
                // }
                // if (amount >= 250 && amount <= 499) {
                //     moncashTransfertSiP2PFee.value = 0;
                //     moncashTransfertSiCashOutFee.value = 13;
                //     receiverAmountMinusP2pFee.value = amount - moncashTransfertSiP2PFee.value;
                //     receiverAmount.value = amount;
                // }
                // if (amount >= 500 && amount <= 999) {
                //     moncashTransfertSiP2PFee.value = 0;
                //     moncashTransfertSiCashOutFee.value = 30;
                //     receiverAmountMinusP2pFee.value = amount - moncashTransfertSiP2PFee.value;
                //     receiverAmount.value = amount;
                // }
                // if (amount >= 1000 && amount <= 1999) {
                //     moncashTransfertSiP2PFee.value = 0;
                //     moncashTransfertSiCashOutFee.value = 60;
                //     receiverAmountMinusP2pFee.value = amount - moncashTransfertSiP2PFee.value;
                //     receiverAmount.value = amount;
                // }
                // if (amount >= 2000 && amount <= 3999) {
                //     moncashTransfertSiP2PFee.value = 0;
                //     moncashTransfertSiCashOutFee.value = 105;
                //     receiverAmountMinusP2pFee.value = amount - moncashTransfertSiP2PFee.value;
                //     receiverAmount.value = amount;
                // }
                // if (amount >= 4000 && amount <= 7999) {
                //     moncashTransfertSiP2PFee.value = 0;
                //     moncashTransfertSiCashOutFee.value = 171;
                //     receiverAmountMinusP2pFee.value = amount - moncashTransfertSiP2PFee.value;
                //     receiverAmount.value = amount;
                // }
                // if (amount >= 8000 && amount <= 11999) {
                //     moncashTransfertSiP2PFee.value = 0;
                //     moncashTransfertSiCashOutFee.value = 247;
                //     receiverAmountMinusP2pFee.value = amount - moncashTransfertSiP2PFee.value;
                //     receiverAmount.value = amount;
                // }
                // if (amount >= 12000 && amount <= 19999) {
                //     moncashTransfertSiP2PFee.value = 0;
                //     moncashTransfertSiCashOutFee.value = 366;
                //     receiverAmountMinusP2pFee.value = amount - moncashTransfertSiP2PFee.value;
                //     receiverAmount.value = amount;
                // }
                // if (amount >= 20000 && amount <= 39999) {
                //     moncashTransfertSiP2PFee.value = 0;
                //     moncashTransfertSiCashOutFee.value = 629;
                //     receiverAmountMinusP2pFee.value = amount - moncashTransfertSiP2PFee.value;
                //     receiverAmount.value = amount;
                // }
                // if (amount >= 40000 && amount <= 59999) {
                //     moncashTransfertSiP2PFee.value = 0;
                //     moncashTransfertSiCashOutFee.value = 0;
                //     receiverAmount.value = amount - moncashTransfertSiP2PFee.value;
                // }
                // if (amount >= 60000 && amount <= 75000) {
                //     moncashTransfertSiP2PFee.value = 0;
                //     moncashTransfertSiCashOutFee.value = 1368;
                //     receiverAmountMinusP2pFee.value = amount - moncashTransfertSiP2PFee.value;
                //     receiverAmount.value = amount;
                // }
                // if (amount > 75000) {
                //     checkMoncashAmountLimit(amount, operation);
                // }
                // break;
            case "reception_si":
                if (amount >= 20 && amount <= 99) {
                    receiverAmount.value = ((amount + moncashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value =  (amount * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 100 && amount <= 249) {
                    receiverAmount.value = ((amount + moncashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value =  (amount * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 250 && amount <= 499) {
                    receiverAmount.value = ((amount + moncashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value =  (amount * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 500 && amount <= 999) {
                    receiverAmount.value = ((amount + moncashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value =  (amount * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 1000 && amount <= 1999) {
                    receiverAmount.value = ((amount + moncashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value =  (amount * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 2000 && amount <= 3999) {
                    receiverAmount.value = ((amount + moncashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value =  (amount * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 4000 && amount <= 7999) {
                    receiverAmount.value = ((amount + moncashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value =  (amount * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 8000 && amount <= 11999) {
                    receiverAmount.value = ((amount + moncashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value =  (amount * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 12000 && amount <= 19999) {
                    receiverAmount.value = ((amount + moncashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value =  (amount * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 20000 && amount <= 39999) {
                    receiverAmount.value = ((amount + moncashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value =  (amount * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 40000 && amount <= 59999) {
                    receiverAmount.value = ((amount + moncashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value =  (amount * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 60000 && amount <= 75125) {
                    receiverAmount.value = ((amount + moncashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value =  (amount * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                if (amount > 75125) {
                    checkMoncashAmountLimit(amount, operation);
                }
                break;
            case "reception":
                if (amount >= 20 && amount <= 99) {
                    moncashTransfertSiP2PFee.value = 0;
                    receiverAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value = ((amount - moncashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 100 && amount <= 249) {
                    moncashTransfertSiP2PFee.value = 0;
                    receiverAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value = ((amount - moncashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                }

                if (amount >= 250 && amount <= 499) {
                    moncashTransfertSiP2PFee.value = 0;
                    receiverAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value = ((amount - moncashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 500 && amount <= 999) {
                    moncashTransfertSiP2PFee.value = 0;
                    receiverAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value = ((amount - moncashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 1000 && amount <= 1999) {
                    moncashTransfertSiP2PFee.value = 0;
                    receiverAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value = ((amount - moncashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 2000 && amount <= 3999) {
                    moncashTransfertSiP2PFee.value = 0;
                    receiverAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value = ((amount - moncashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 4000 && amount <= 7999) {
                    moncashTransfertSiP2PFee.value = 0;
                    receiverAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value = ((amount - moncashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 8000 && amount <= 11999) {
                    moncashTransfertSiP2PFee.value = 0;
                    receiverAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value = ((amount - moncashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 12000 && amount <= 19999) {
                    moncashTransfertSiP2PFee.value = 0;
                    receiverAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value = ((amount - moncashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 20000 && amount <= 39999) {
                    moncashTransfertSiP2PFee.value = 0;
                    receiverAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value = ((amount - moncashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 40000 && amount <= 59999) {
                    moncashTransfertSiP2PFee.value = 0;
                    receiverAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value = ((amount - moncashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 60000 && amount <= 75000) {
                    moncashTransfertSiP2PFee.value = 0;
                    receiverAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value = ((amount - moncashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                if (amount > 75000) {
                    moncashTransfertSiP2PFee.value = 0;
                    receiverAmount.value = (amount * dailyratePurchase.value);
                    receiverAmountMinusP2pFee.value = (amount * dailyratePurchase.value);
                    senderAmount.value = (amount / dailyratePurchase.value);
                    //checkMoncashAmountLimit(amount, operation);
                }
                break;
            default:
            // code block
        }
    };
    // Moncash end




    
    // Natcash
    const calculateNatcashFee = async (amount, operation, fortunafee) => {
        getDailyRate();
        switch (operation) {
            case "transfert_si":
            case "transfert":
                    if (amount >= 20 && amount <= 99) {
                        natcashTransfertSiP2PFee.value = 0.55;
                        natcashTransfertSiCashOutFee.value = 5.50;
                        receiverAmountMinusP2pFee.value = amount - natcashTransfertSiP2PFee.value;
                        receiverAmount.value = amount;
                    }
                    if (amount >= 100 && amount <= 249) {
                        natcashTransfertSiP2PFee.value = 1.15;
                        natcashTransfertSiCashOutFee.value = 11.50;
                        receiverAmountMinusP2pFee.value = amount - natcashTransfertSiP2PFee.value;
                        receiverAmount.value = amount;
                    }
                    if (amount >= 250 && amount <= 499) {
                          natcashTransfertSiP2PFee.value = 1.35;
                        natcashTransfertSiCashOutFee.value = 13.50;
                        receiverAmountMinusP2pFee.value = amount - natcashTransfertSiP2PFee.value;
                        receiverAmount.value = amount;
                    }
                    if (amount >= 500 && amount <= 999) {
                        natcashTransfertSiP2PFee.value = 4.20;
                        natcashTransfertSiCashOutFee.value = 21;
                        receiverAmountMinusP2pFee.value = amount - natcashTransfertSiP2PFee.value;
                        receiverAmount.value = amount;
                    }
                    if (amount >= 1000 && amount <= 1999) {
                        natcashTransfertSiP2PFee.value = 8.20;
                        natcashTransfertSiCashOutFee.value = 41;
                        receiverAmountMinusP2pFee.value = amount - natcashTransfertSiP2PFee.value;
                        receiverAmount.value = amount;
                    }
                    if (amount >= 2000 && amount <= 3999) {
                        natcashTransfertSiP2PFee.value = 16.60;
                        natcashTransfertSiCashOutFee.value = 68;
                        receiverAmountMinusP2pFee.value = amount - natcashTransfertSiP2PFee.value;
                        receiverAmount.value = amount;
                    }
                    if (amount >= 4000 && amount <= 7999) {
                        natcashTransfertSiP2PFee.value = 14.55;
                        natcashTransfertSiCashOutFee.value = 97;
                        receiverAmountMinusP2pFee.value = amount - natcashTransfertSiP2PFee.value;
                        receiverAmount.value = amount;
                    }
                    if (amount >= 8000 && amount <= 11999) {
                       natcashTransfertSiP2PFee.value = 18.75;
                        natcashTransfertSiCashOutFee.value = 125;
                        receiverAmountMinusP2pFee.value = amount - natcashTransfertSiP2PFee.value;
                        receiverAmount.value = amount;
                    }
                    if (amount >= 12000 && amount <= 19999) {
                        natcashTransfertSiP2PFee.value = 24.75;
                        natcashTransfertSiCashOutFee.value = 165;
                        receiverAmountMinusP2pFee.value = amount - natcashTransfertSiP2PFee.value;
                        receiverAmount.value = amount;
                    }
                    if (amount >= 20000 && amount <= 40041.10) {
                        natcashTransfertSiP2PFee.value = 41.10;
                        natcashTransfertSiCashOutFee.value = 274;
                        receiverAmountMinusP2pFee.value = amount - natcashTransfertSiP2PFee.value;
                        receiverAmount.value = amount;
                   }
                    if (amount > 41040.1) {
                        checkNatcashAmountLimit(amount, operation);
                    }
            break;
            // case "transfert":
            //     if (amount >= 20 && amount <= 99) {
            //         natcashTransfertSiP2PFee.value = 0;
            //         natcashTransfertSiCashOutFee.value = 5.50;
            //         receiverAmount.value = amount;
            //     }
            //     if (amount >= 100 && amount <= 249) {
            //         natcashTransfertSiP2PFee.value = 0;
            //         natcashTransfertSiCashOutFee.value = 11.50;
            //         receiverAmount.value = amount;
            //     }
            //     if (amount >= 250 && amount <= 499) {
            //         natcashTransfertSiP2PFee.value = 0;
            //         natcashTransfertSiCashOutFee.value = 13.50;
            //         receiverAmount.value = amount;
            //     }
            //     if (amount >= 500 && amount <= 999) {
            //         natcashTransfertSiP2PFee.value = 0;
            //         natcashTransfertSiCashOutFee.value = 21;
            //         receiverAmount.value = amount;
            //     }
            //     if (amount >= 1000 && amount <= 1999) {
            //         natcashTransfertSiP2PFee.value = 8.20;
            //         natcashTransfertSiCashOutFee.value = 41;
            //         receiverAmount.value = amount;
            //     }
            //     if (amount >= 2000 && amount <= 3999) {
            //         natcashTransfertSiP2PFee.value = 0;
            //         natcashTransfertSiCashOutFee.value = 68;
            //         receiverAmount.value = amount;
            //     }
            //     if (amount >= 4000 && amount <= 7999) {
            //         natcashTransfertSiP2PFee.value = 0;
            //         natcashTransfertSiCashOutFee.value = 97;
            //         receiverAmount.value = amount;
            //     }
            //     if (amount >= 8000 && amount <= 11999) {
            //         natcashTransfertSiP2PFee.value = 0;
            //         natcashTransfertSiCashOutFee.value = 125;
            //         receiverAmount.value = amount;
            //     }
            //     if (amount >= 12000 && amount <= 19999) {
            //         natcashTransfertSiP2PFee.value = 0;
            //         natcashTransfertSiCashOutFee.value = 165;
            //         receiverAmount.value = amount;
            //     }
            //     if (amount >= 20000 && amount <= 40999) {
            //         natcashTransfertSiP2PFee.value = 0;
            //         natcashTransfertSiCashOutFee.value = 274;
            //         receiverAmount.value = amount;
            //     }
            //     if (amount > 40999) {
            //         checkNatcashAmountLimit(amount, operation);
            //     }
            // break;
            case "reception_si":
                if (amount >= 20 && amount <= 99) {
                    receiverAmount.value = ((amount + natcashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value =  (amount * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 100 && amount <= 249) {
                    receiverAmount.value = ((amount + natcashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value =  (amount * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }

                if (amount >= 250 && amount <= 499) {
                    receiverAmount.value = ((amount + natcashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value =  (amount * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 500 && amount <= 999) {
                   receiverAmount.value = ((amount + natcashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value =  (amount * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 1000 && amount <= 1999) {
                   receiverAmount.value = ((amount + natcashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value =  (amount * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 2000 && amount <= 3999) {
                   receiverAmount.value = ((amount + natcashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value =  (amount * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 4000 && amount <= 7999) {
                    receiverAmount.value = ((amount + natcashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value =  (amount * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 8000 && amount <= 11999) {
                    receiverAmount.value = ((amount + natcashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value =  (amount * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 12000 && amount <= 19999) {
                    receiverAmount.value = ((amount + natcashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value =  (amount * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 20000 && amount <= 40274) {
                    receiverAmount.value = ((amount + natcashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value =  (amount * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                if (amount > 40274) {
                    checkNatcashAmountLimit(amount, operation);
                }
                break;
            case "reception":
                if (amount >= 20 && amount <= 99) {
                    natcashTransfertSiP2PFee.value = 0;
                    receiverAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value = ((amount - natcashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 100 && amount <= 249) {
                    natcashTransfertSiP2PFee.value = 0;
                    receiverAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value = ((amount - natcashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                }

                if (amount >= 250 && amount <= 499) {
                    natcashTransfertSiP2PFee.value = 0;
                    receiverAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value = ((amount - natcashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 500 && amount <= 999) {
                    natcashTransfertSiP2PFee.value = 0;
                    receiverAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value = ((amount - natcashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 1000 && amount <= 1999) {
                    natcashTransfertSiP2PFee.value = 0;
                    receiverAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value = ((amount - natcashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 2000 && amount <= 3999) {
                    natcashTransfertSiP2PFee.value = 0;
                    receiverAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value = ((amount - natcashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 4000 && amount <= 7999) {
                    natcashTransfertSiP2PFee.value = 0;
                    receiverAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value = ((amount - natcashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 8000 && amount <= 11999) {
                    natcashTransfertSiP2PFee.value = 0;
                    receiverAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value = ((amount - natcashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 12000 && amount <= 19999) {
                    natcashTransfertSiP2PFee.value = 0;
                    receiverAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value = ((amount - natcashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                if (amount >= 20000 && amount <= 40000) {
                    natcashTransfertSiP2PFee.value = 0;
                    receiverAmount.value = (amount * dailyratePurchase.value) - fortunafee;
                    receiverAmountMinusP2pFee.value = ((amount - natcashTransfertSiP2PFee.value) * dailyratePurchase.value) - fortunafee;
                    senderAmount.value = (amount / dailyratePurchase.value) - fortunafee;
                }
                 if (amount > 40000) {
                    receiverAmount.value = (amount * dailyratePurchase.value);
                    receiverAmountMinusP2pFee.value = (amount * dailyratePurchase.value);
                    senderAmount.value = (amount / dailyratePurchase.value);
                    //checkNatcashAmountLimit(amount, operation);
                }
                break;
            default:
            // code block
        }
    }
    // Natcash End

    return {
        dailyrateSale,
        dailyratePurchase,
        senderAmount,
        receiverAmount,
        receiverAmountMinusP2pFee,
        getDailyRate,
        moncashTransfertSiP2PFee,
        moncashTransfertSiCashOutFee,
        moncashTransfertSiP2PFee,
        calculateMoncashFee,
        natcashTransfertSiP2PFee,
        natcashTransfertSiCashOutFee,
        calculateNatcashFee,
    };
}