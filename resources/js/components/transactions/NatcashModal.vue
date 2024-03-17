<template>
  <!--natcash Card -->
  <!-- <div class="container-fluid" v-show="type == 'natcash'">
    <div class="row d-flex justify-content-center align-items-center">
      <div class="col-md-12 col-lg-12 col-xl-12 m-0 p-0"> -->
        <div class="card p-0 m-0"  v-show="type == 'natcash'">
          <div class="card-body p-0 m-0">

            <p class="lead fw-bold m3-5 text-center" style="color: #f37a27;">Details </p>

            <div class="row px-4 py-3 m-0 text-right">
              <div class="col mb-3">
                <p class="m-0 ml-2" v-show="operation == 'transfert_si' || operation == 'transfert'">{{ formatDecimalNumber(senderAmount) }} RD$</p>
                <p class="m-0 ml-2" v-show="operation == 'reception_si' || operation == 'reception'">{{ formatDecimalNumber(receiverAmountMinusP2pFee + fortunaFee) }} RD$</p> <p>
                  <span class="small text-muted mb-1 text-xs">(Fortuna fee)</span> - {{ formatDecimalNumber(fortunaFee) }} RD$ 
                  <!-- <span class="small text-muted mb-1">(Fortuna fee)</span> -->
                </p>
                <p class="border-top m-0 w-100"></p>
                <p class="m-0 ml-2" v-show="operation == 'transfert_si' || operation == 'transfert'">{{ formatDecimalNumber(senderAmount - fortunaFee) }} RD$</p>
                <p class="m-0 ml-2" v-show="operation == 'reception_si' || operation == 'reception'">{{ formatDecimalNumber(receiverAmountMinusP2pFee) }} RD$</p>
              </div>
              <div class="col mb-3">
                <p class="m-0 ml-2" v-show="operation == 'transfert_si' || operation == 'transfert'">{{ formatDecimalNumber(receiverAmount) }} HTG</p>
                <p class="m-0 ml-2" v-show="operation == 'reception_si' || operation == 'reception'">{{ formatDecimalNumber((receiverAmount + fortunaFee) / dailyratePurchase) }} HTG</p>
                <p v-show="operation == 'transfert_si' || operation == 'transfert'"> <span class="small text-muted mb-1 text-xs">(Natcash fee)</span> - {{ formatDecimalNumber(natcashTransfertSiP2PFee) }} HTG </p>
                <p v-show="operation == 'reception_si' || operation == 'reception'"> <span class="small text-muted mb-1 text-xs">(Natcash fee)</span> - {{ formatDecimalNumber(natcashTransfertSiP2PFee) }} HTG </p>
                <p class="border-top m-0 w-100"></p>
                <p class="m-0 ml-2" v-show="operation == 'transfert_si' || operation == 'transfert'">{{ formatDecimalNumber(receiverAmountMinusP2pFee) }} HTG</p>
                <p class="m-0 ml-2" v-show="operation == 'reception_si' || operation == 'reception'">{{ formatDecimalNumber((receiverAmountMinusP2pFee + fortunaFee) / dailyratePurchase) }} HTG</p>
              </div>
            </div>

            <div class="px-4 py-3 m-0 text-right" style="background-color: #f2f2f2;">
              <div class="row">
                <div class="col"> Client will receive </div>
                <div class="col">
                  <p class="m-0 ml-2" v-show="operation == 'transfert_si' || operation == 'transfert'">{{ formatDecimalNumber(receiverAmountMinusP2pFee) }} HTG</p>
                  <p class="m-0 ml-2" v-show="operation == 'reception_si' || operation == 'reception'">{{ formatDecimalNumber(receiverAmountMinusP2pFee) }} RD$</p>
                  <p v-show="operation == 'transfert_si' || operation == 'transfert'"> <span class="small text-muted mb-1 text-xs">(Natcash cashout fee)</span> - {{ formatDecimalNumber(natcashTransfertSiCashOutFee) }} HTG  </p>
                  <p class="border-top m-0 w-100"></p>
                  <p class="m-0 ml-2" v-show="operation == 'transfert_si' || operation == 'transfert'">{{ formatDecimalNumber(receiverAmountMinusP2pFee - natcashTransfertSiCashOutFee) }} HTG</p>
                </div>
              </div>
            </div>

          </div>
        </div>
      <!-- </div>
    </div>
  </div> -->
  <!-- natcash Card end -->
</template>

<script>
import useUtils from "../../services/utilsservices";
export default {
  props: ['senderAmount', 'receiverAmount', 'receiverAmountMinusP2pFee', 'fortunaFee', 'natcashTransfertSiCashOutFee', 'natcashTransfertSiP2PFee', 'type', 'operation', 'dailyrateSale', 'dailyratePurchase'],
  setup(props) {
    const { formatDecimalNumber } = useUtils();
    return {
      props,
      formatDecimalNumber
    }
  },
}
</script>