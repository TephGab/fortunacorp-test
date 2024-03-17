import { createRouter, createWebHistory } from "vue-router";

import Swal from 'sweetalert2';

// Front end 
import Dashboard from '../pages/Dashboard.vue';

import TransactionIndex from '../pages/transaction/Index.vue';
import TransactionCreate from '../pages/transaction/Create.vue';
import TransactionShow from '../pages/transaction/Show.vue';
import TransactionSteps from '../pages/transaction/ApprovalSteps.vue';
import TransactionShowDetails from '../pages/transaction/ShowDetails.vue';
import TransactionsCompleted from '../pages/transaction/CompletedTransactions.vue';

import CashinIndex from '../pages/cashin/Index.vue';
import CashinCreate from '../pages/cashin/Create.vue';

import AccountIndex from '../pages/accounts/Index.vue';
import AccountCreate from '../pages/accounts/Create.vue';
import AccountEdit from '../pages/accounts/Edit.vue';

import AccountTransfertsEdit from '../pages/accountTransferts/Edit.vue';
import AccountTransfertsIndex from '../pages/accountTransferts/Index.vue';

import ProviderIndex from '../pages/providers/Index.vue';
import ProviderCreate from '../pages/providers/Create.vue';
import ProviderEdit from '../pages/providers/Edit.vue';

import ProviderPaymentsIndex from '../pages/providerPayments/Index.vue';
import ProviderPaymentsCreate from '../pages/providerPayments/Create.vue';

import UsersIndex from '../pages/users/Index.vue';
import UserCreate from '../pages/users/Create.vue';
import UserShow from '../pages/users/Edit.vue';
import Profile from '../pages/users/Profile.vue';

import DepartmentsIndex from '../pages/departments/Index.vue';
import DepartmentsCreate from '../pages/departments/Create.vue';
import DepartmentsEdit from '../pages/departments/Edit.vue';

import RoleAndPermissionIndex from '../pages/roleAndPermission/Index.vue';
import RoleAndPermissionCreate from '../pages/roleAndPermission/Create.vue';
import RoleAndPermissionEdit from '../pages/roleAndPermission/Edit.vue';
import RoleAndPermissionCreatePermission from '../pages/roleAndPermission/CreatePermission.vue';

import AgentsDepositsIndex from '../pages/agentsdeposits/Index.vue';
import AgentsDepositsCreate from '../pages/agentsdeposits/Create.vue';
import AgentsDepositsEdit from '../pages/agentsdeposits/Edit.vue';
import AgentsDepositsModify from '../pages/agentsdeposits/Modify.vue';

import EnvoyDepositsIndex from '../pages/envoydeposits/Index.vue';
import EnvoyDepositsCreate from '../pages/envoydeposits/Create.vue';

import EnvoyTransfertIndex from '../pages/envoyTransferts/Index.vue';
import EnvoyTransfertCreate from '../pages/envoyTransferts/Create.vue';
import EnvoyTransfertEdit from '../pages/envoyTransferts/Edit.vue';

import CashoutIndex from '../pages/cashout/Index.vue';
import CashoutEdit from '../pages/cashout/Edit.vue';
import CashoutCreate from '../pages/cashout/Create.vue';

import ReplenishmentIndex from '../pages/replenishments/Index.vue';
import ReplenishmentCreate from '../pages/replenishments/Create.vue';

import RequiredReplenishmentIndex from '../pages/replenishments/requiredReplenishments/Index.vue';

import ExpenseIndex from '../pages/expenses/Index.vue';
import ExpenseCreate from '../pages/expenses/Create.vue';

import SendMoneyIndex from '../pages/sendMoney/Index.vue';
import SendMoneyCreate from '../pages/sendMoney/Create.vue';

import ProfitToRechargeIndex from '../pages/profitToRecharge/Index.vue';
import ProfitToRechargeCreate from '../pages/profitToRecharge/Create.vue';

import UserActivityShow from '../pages/userActivity/Show.vue';

import AgentLoanIndex from '../pages/agentLoan/Index.vue';
import AgentLoanCreate from '../pages/agentLoan/Create.vue';
import AgentLoanEdit from '../pages/agentLoan/Edit.vue';

import AgentLoanRepayIndex from '../pages/agentLoanRepay/Index.vue';
import AgentLoanRepayCreate from '../pages/agentLoanRepay/Create.vue';
import AgentLoanRepayShow from '../pages/agentLoanRepay/Show.vue';

import EnvoyAgentInfosIndex from '../pages/envoyAgentInfos/Index.vue';
import EnvoyAgentInfosShow from '../pages/envoyAgentInfos/Show.vue';


function removeQueryParams(to) {
    if (Object.keys(to.query).length)
      return { path: to.path, query: {}, hash: to.hash }
  }
  
  function removeHash(to) {
    if (to.hash) return { path: to.path, query: to.query, hash: '' }
  }

const routes = [
    {
        path: '/home',
        name: 'dashboard',
        component: Dashboard,
        meta: { breadcrumb: 'Dashboard' }
    },
    {
      path: '/transaction',
      name: 'transaction.index',
      component: TransactionIndex,
      meta: { breadcrumb: 'Transactions' }
    },
    {
        path: '/transactionscompleted',
        name: 'transactionscompleted.index',
        component: TransactionsCompleted,
        meta: { breadcrumb: 'Completed transactions' }
    },
    {
        path: '/transaction/create',
        name: 'transaction.create',
        component: TransactionCreate,
        meta: { breadcrumb: 'New Transaction', parents: [
            { name: 'transaction.index', label: 'Transactions' },
            // { name: 'transaction.index', label: 'Transaction2' },
            // { meta: { breadcrumb: route => `Transaction ${route.params.id}`, parentRoute: 'transaction.index' }}
          ] }
    },
  {
    path: '/transaction/:id',
    name: 'transaction.show',
    component: TransactionShow,
    props: true,
    meta: { breadcrumb: 'Details', parents: [{ name: 'transaction.index', label: 'Transactions' }] }
  },
  {
    path: '/transactiondetails/:id',
    name: 'transaction.details',
    component: TransactionShowDetails,
    props: true,
    meta: { breadcrumb: 'Details', parents: [{ name: 'transaction.index', label: 'Transaction' }] }
  },
  {
    path: '/transactionsteps/:id',
    name: 'transactionsteps.show',
    component: TransactionSteps,
    props: true,
    meta: { breadcrumb: 'Steps', parents: [{ name: 'transaction.index', label: 'Transaction' }] }
  },
  {
    path: '/users',
    name: 'users.index',
    component: UsersIndex,
         beforeEnter: (to, from, next) => {
            axios.get("/api/getauthuser").then((res) => {
                        const user = res.data;
                        if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin')
                        {
                            next()
                        }
                        else {
                            Swal.fire({
                                toast: true,
                                position: 'top',
                                icon: 'info',
                                title: 'Avoid changing url manually, use buttons to navigate!',
                                showConfirmButton: false,
                                color: '#fff',
                                background: '#d9534f',
                                timer: 10500,
                                timerProgressBar: true
                            })
                            next('/home');
                        }
                    });
            },
    meta: { breadcrumb: 'Users' },
},
{
  path: '/users/create',
  name: 'user.create',
  component: UserCreate,
       beforeEnter: (to, from, next) => {
            axios.get("/api/getauthuser").then((res) => {
                        const user = res.data;
                        if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin')
                        {
                            next()
                        }
                        else {
                            Swal.fire({
                                toast: true,
                                position: 'top',
                                icon: 'info',
                                title: 'Avoid changing url manually, use buttons to navigate!',
                                showConfirmButton: false,
                                color: '#fff',
                                background: '#d9534f',
                                timer: 10500,
                                timerProgressBar: true
                            })
                            next('/home');
                        }
                    });
            },
  meta: { breadcrumb: 'Users', parents: [{ name: 'users.index', label: 'Users' }] }
},
{
  path: '/users/:id',
  name: 'user.show',
  component: UserShow,
  props: true,
  beforeEnter: (to, from, next) => {
    axios.get("/api/getauthuser").then((res) => {
                const user = res.data;
                if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin')
                {
                    next()
                }
                else {
                    Swal.fire({
                        toast: true,
                        position: 'top',
                        icon: 'info',
                        title: 'Avoid changing url manually, use buttons to navigate!',
                        showConfirmButton: false,
                        color: '#fff',
                        background: '#d9534f',
                        timer: 10500,
                        timerProgressBar: true
                    })
                    next('/home');
                }
            });
    },
 meta: { breadcrumb: 'New user', parents: [{ name: 'users.index', label: 'Users' }] },
},
{
    path: '/envoyagentinfos',
    name: 'envoyagentinfos.index',
    component: EnvoyAgentInfosIndex,
    beforeEnter: (to, from, next) => {
      axios.get("/api/getauthuser").then((res) => {
                  const user = res.data;
                  if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin' || user.roles[0].name == 'envoy')
                  {
                      next()
                  }
                  else {
                      Swal.fire({
                          toast: true,
                          position: 'top',
                          icon: 'info',
                          title: 'Avoid changing url manually, use buttons to navigate!',
                          showConfirmButton: false,
                          color: '#fff',
                          background: '#d9534f',
                          timer: 10500,
                          timerProgressBar: true
                      })
                      next('/home');
                  }
              });
      },
},
{
    path: '/envoyagentinfos/:id',
    name: 'envoyagentinfos.show',
    component: EnvoyAgentInfosShow,
    props: true,
    beforeEnter: (to, from, next) => {
      axios.get("/api/getauthuser").then((res) => {
                  const user = res.data;
                  if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin' || user.roles[0].name == 'envoy')
                  {
                      next()
                  }
                  else {
                      Swal.fire({
                          toast: true,
                          position: 'top',
                          icon: 'info',
                          title: 'Avoid changing url manually, use buttons to navigate!',
                          showConfirmButton: false,
                          color: '#fff',
                          background: '#d9534f',
                          timer: 10500,
                          timerProgressBar: true
                      })
                      next('/home');
                  }
              });
      },
},
{
    path: '/agentloan',
    name: 'agentloan.index',
    component: AgentLoanIndex,
         beforeEnter: (to, from, next) => {
            axios.get("/api/getauthuser").then((res) => {
                        const user = res.data;
                        if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'agent')
                        {
                            next()
                        }
                        else {
                            Swal.fire({
                                toast: true,
                                position: 'top',
                                icon: 'info',
                                title: 'Avoid changing url manually, use buttons to navigate!',
                                showConfirmButton: false,
                                color: '#fff',
                                background: '#d9534f',
                                timer: 10500,
                                timerProgressBar: true
                            })
                            next('/home');
                        }
                    });
            },
},
{
    path: '/agentloan/create',
    name: 'agentloan.create',
    component: AgentLoanCreate,
         beforeEnter: (to, from, next) => {
              axios.get("/api/getauthuser").then((res) => {
                          const user = res.data;
                          if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer')
                          {
                              next()
                          }
                          else {
                              Swal.fire({
                                  toast: true,
                                  position: 'top',
                                  icon: 'info',
                                  title: 'Avoid changing url manually, use buttons to navigate!',
                                  showConfirmButton: false,
                                  color: '#fff',
                                  background: '#d9534f',
                                  timer: 10500,
                                  timerProgressBar: true
                              })
                              next('/home');
                          }
                      });
              },
},
{
    path: '/agentloan/:id',
    name: 'agentloan.show',
    component: AgentLoanEdit,
    props: true,
    beforeEnter: (to, from, next) => {
      axios.get("/api/getauthuser").then((res) => {
                  const user = res.data;
                  if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer')
                  {
                      next()
                  }
                  else {
                      Swal.fire({
                          toast: true,
                          position: 'top',
                          icon: 'info',
                          title: 'Avoid changing url manually, use buttons to navigate!',
                          showConfirmButton: false,
                          color: '#fff',
                          background: '#d9534f',
                          timer: 10500,
                          timerProgressBar: true
                      })
                      next('/home');
                  }
              });
      },
},
{
    path: '/agentloanrepay',
    name: 'agentloanrepay.index',
    component: AgentLoanRepayIndex,
         beforeEnter: (to, from, next) => {
            axios.get("/api/getauthuser").then((res) => {
                        const user = res.data;
                        if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'agent' || user.roles[0].name == 'envoy')
                        {
                            next()
                        }
                        else {
                            Swal.fire({
                                toast: true,
                                position: 'top',
                                icon: 'info',
                                title: 'Avoid changing url manually, use buttons to navigate!',
                                showConfirmButton: false,
                                color: '#fff',
                                background: '#d9534f',
                                timer: 10500,
                                timerProgressBar: true
                            })
                            next('/home');
                        }
                    });
            },
},
{
    path: '/agentloanrepay/create',
    name: 'agentloanrepay.create',
    component: AgentLoanRepayCreate,
         beforeEnter: (to, from, next) => {
              axios.get("/api/getauthuser").then((res) => {
                          const user = res.data;
                          if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin' || user.roles[0].name == 'agent')
                          {
                              next()
                          }
                          else {
                              Swal.fire({
                                  toast: true,
                                  position: 'top',
                                  icon: 'info',
                                  title: 'Avoid changing url manually, use buttons to navigate!',
                                  showConfirmButton: false,
                                  color: '#fff',
                                  background: '#d9534f',
                                  timer: 10500,
                                  timerProgressBar: true
                              })
                              next('/home');
                          }
                      });
              },
},
{
    path: '/agentloanrepay/:id',
    name: 'agentloanrepay.show',
    component: AgentLoanRepayShow,
    props: true,
         beforeEnter: (to, from, next) => {
              axios.get("/api/getauthuser").then((res) => {
                          const user = res.data;
                          if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin' || user.roles[0].name == 'agent' || user.roles[0].name == 'envoy')
                          {
                              next()
                          }
                          else {
                              Swal.fire({
                                  toast: true,
                                  position: 'top',
                                  icon: 'info',
                                  title: 'Avoid changing url manually, use buttons to navigate!',
                                  showConfirmButton: false,
                                  color: '#fff',
                                  background: '#d9534f',
                                  timer: 10500,
                                  timerProgressBar: true
                              })
                              next('/home');
                          }
                      });
              },
},
{
    path: '/profile',
    name: 'profile',
    component: Profile,
    meta: { breadcrumb: 'Profile'},
},
{
    path: '/accounts',
    name: 'accounts.index',
    component: AccountIndex,
         beforeEnter: (to, from, next) => {
            axios.get("/api/getauthuser").then((res) => {
                        const user = res.data;
                        if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin' || user.roles[0].name == 'operator')
                        {
                            next()
                        }
                        else {
                            Swal.fire({
                                toast: true,
                                position: 'top',
                                icon: 'info',
                                title: 'Avoid changing url manually, use buttons to navigate!',
                                showConfirmButton: false,
                                color: '#fff',
                                background: '#d9534f',
                                timer: 10500,
                                timerProgressBar: true
                            })
                            next('/home');
                        }
                    });
            },
},
{
    path: '/accounts/create',
    name: 'accounts.create',
    component: AccountCreate,
         beforeEnter: (to, from, next) => {
              axios.get("/api/getauthuser").then((res) => {
                          const user = res.data;
                          if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin')
                          {
                              next()
                          }
                          else {
                              Swal.fire({
                                  toast: true,
                                  position: 'top',
                                  icon: 'info',
                                  title: 'Avoid changing url manually, use buttons to navigate!',
                                  showConfirmButton: false,
                                  color: '#fff',
                                  background: '#d9534f',
                                  timer: 10500,
                                  timerProgressBar: true
                              })
                              next('/home');
                          }
                      });
              },
},
{
    path: '/accounts/:id',
    name: 'accounts.edit',
    component: AccountEdit,
    props: true,
         beforeEnter: (to, from, next) => {
              axios.get("/api/getauthuser").then((res) => {
                          const user = res.data;
                          if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin' || user.roles[0].name == 'operator')
                          {
                              next()
                          }
                          else {
                              Swal.fire({
                                  toast: true,
                                  position: 'top',
                                  icon: 'info',
                                  title: 'Avoid changing url manually, use buttons to navigate!',
                                  showConfirmButton: false,
                                  color: '#fff',
                                  background: '#d9534f',
                                  timer: 10500,
                                  timerProgressBar: true
                              })
                              next('/home');
                          }
                      });
              },
},
{
    path: '/providers',
    name: 'providers.index',
    component: ProviderIndex,
         beforeEnter: (to, from, next) => {
            axios.get("/api/getauthuser").then((res) => {
                        const user = res.data;
                        if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin')
                        {
                            next()
                        }
                        else {
                            Swal.fire({
                                toast: true,
                                position: 'top',
                                icon: 'info',
                                title: 'Avoid changing url manually, use buttons to navigate!',
                                showConfirmButton: false,
                                color: '#fff',
                                background: '#d9534f',
                                timer: 10500,
                                timerProgressBar: true
                            })
                            next('/home');
                        }
                    });
            },
},
{
    path: '/providers/create',
    name: 'providers.create',
    component: ProviderCreate,
         beforeEnter: (to, from, next) => {
              axios.get("/api/getauthuser").then((res) => {
                          const user = res.data;
                          if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin')
                          {
                              next()
                          }
                          else {
                              Swal.fire({
                                  toast: true,
                                  position: 'top',
                                  icon: 'info',
                                  title: 'Avoid changing url manually, use buttons to navigate!',
                                  showConfirmButton: false,
                                  color: '#fff',
                                  background: '#d9534f',
                                  timer: 10500,
                                  timerProgressBar: true
                              })
                              next('/home');
                          }
                      });
              },
},
{
    path: '/providers/:id',
    name: 'providers.edit',
    component: ProviderEdit,
    props: true,
         beforeEnter: (to, from, next) => {
              axios.get("/api/getauthuser").then((res) => {
                          const user = res.data;
                          if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin')
                          {
                              next()
                          }
                          else {
                              Swal.fire({
                                  toast: true,
                                  position: 'top',
                                  icon: 'info',
                                  title: 'Avoid changing url manually, use buttons to navigate!',
                                  showConfirmButton: false,
                                  color: '#fff',
                                  background: '#d9534f',
                                  timer: 10500,
                                  timerProgressBar: true
                              })
                              next('/home');
                          }
                      });
              },
},
{
    path: '/cashins',
    name: 'cashins.index',
    component: CashinIndex,
         beforeEnter: (to, from, next) => {
            axios.get("/api/getauthuser").then((res) => {
                        const user = res.data;
                        if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin')
                        {
                            next()
                        }
                        else {
                            Swal.fire({
                                toast: true,
                                position: 'top',
                                icon: 'info',
                                title: 'Avoid changing url manually, use buttons to navigate!',
                                showConfirmButton: false,
                                color: '#fff',
                                background: '#d9534f',
                                timer: 10500,
                                timerProgressBar: true
                            })
                            next('/home');
                        }
                    });
            },
},
{
    path: '/cashins/create',
    name: 'cashins.create',
    component: CashinCreate,
         beforeEnter: (to, from, next) => {
              axios.get("/api/getauthuser").then((res) => {
                          const user = res.data;
                          if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin')
                          {
                              next()
                          }
                          else {
                              Swal.fire({
                                  toast: true,
                                  position: 'top',
                                  icon: 'info',
                                  title: 'Avoid changing url manually, use buttons to navigate!',
                                  showConfirmButton: false,
                                  color: '#fff',
                                  background: '#d9534f',
                                  timer: 10500,
                                  timerProgressBar: true
                              })
                              next('/home');
                          }
                      });
              },
},
{
    path: '/agentsdeposits',
    name: 'agentsdeposits.index',
    component: AgentsDepositsIndex,
         beforeEnter: (to, from, next) => {
            axios.get("/api/getauthuser").then((res) => {
                        const user = res.data;
                        if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin' || user.roles[0].name == 'envoy' || user.roles[0].name == 'agent')
                        {
                            next()
                        }
                        else {
                            Swal.fire({
                                toast: true,
                                position: 'top',
                                icon: 'info',
                                title: 'Avoid changing url manually, use buttons to navigate!',
                                showConfirmButton: false,
                                color: '#fff',
                                background: '#d9534f',
                                timer: 10500,
                                timerProgressBar: true
                            })
                            next('/home');
                        }
                    });
            },
},
{
    path: '/agentsdeposits/create',
    name: 'agentsdeposits.create',
    component: AgentsDepositsCreate,
         beforeEnter: (to, from, next) => {
            axios.get("/api/getauthuser").then((res) => {
                        const user = res.data;
                        if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin' || user.roles[0].name == 'agent')
                        {
                            next()
                        }
                        else {
                            Swal.fire({
                                toast: true,
                                position: 'top',
                                icon: 'info',
                                title: 'Avoid changing url manually, use buttons to navigate!',
                                showConfirmButton: false,
                                color: '#fff',
                                background: '#d9534f',
                                timer: 10500,
                                timerProgressBar: true
                            })
                            next('/home');
                        }
                    });
            },
},
{
    path: '/agentsdeposits/:id',
    name: 'agentsdeposits.edit',
    component: AgentsDepositsEdit,
    props: true,
    beforeEnter: (to, from, next) => {
      axios.get("/api/getauthuser").then((res) => {
                  const user = res.data;
                  if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin' || user.roles[0].name == 'envoy')
                  {
                      next()
                  }
                  else {
                      Swal.fire({
                          toast: true,
                          position: 'top',
                          icon: 'info',
                          title: 'Avoid changing url manually, use buttons to navigate!',
                          showConfirmButton: false,
                          color: '#fff',
                          background: '#d9534f',
                          timer: 10500,
                          timerProgressBar: true
                      })
                      next('/home');
                  }
              });
      },
},
{
    path: '/agentsdeposits/modify/:id',
    name: 'agentsdeposits.modify',
    component: AgentsDepositsModify,
    props: true,
    beforeEnter: (to, from, next) => {
      axios.get("/api/getauthuser").then((res) => {
                  const user = res.data;
                  if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin' || user.roles[0].name == 'agent')
                  {
                      next()
                  }
                  else {
                      Swal.fire({
                          toast: true,
                          position: 'top',
                          icon: 'info',
                          title: 'Avoid changing url manually, use buttons to navigate!',
                          showConfirmButton: false,
                          color: '#fff',
                          background: '#d9534f',
                          timer: 10500,
                          timerProgressBar: true
                      })
                      next('/home');
                  }
              });
      },
},
{
    path: '/envoydeposits',
    name: 'envoydeposits.index',
    component: EnvoyDepositsIndex,
         beforeEnter: (to, from, next) => {
            axios.get("/api/getauthuser").then((res) => {
                        const user = res.data;
                        if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin' || user.roles[0].name == 'envoy')
                        {
                            next()
                        }
                        else {
                            Swal.fire({
                                toast: true,
                                position: 'top',
                                icon: 'info',
                                title: 'Avoid changing url manually, use buttons to navigate!',
                                showConfirmButton: false,
                                color: '#fff',
                                background: '#d9534f',
                                timer: 10500,
                                timerProgressBar: true
                            })
                            next('/home');
                        }
                    });
            },
},
{
    path: '/envoydeposits/create',
    name: 'envoydeposits.create',
    component: EnvoyDepositsCreate,
         beforeEnter: (to, from, next) => {
            axios.get("/api/getauthuser").then((res) => {
                        const user = res.data;
                        if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin' || user.roles[0].name == 'envoy')
                        {
                            next()
                        }
                        else {
                            Swal.fire({
                                toast: true,
                                position: 'top',
                                icon: 'info',
                                title: 'Avoid changing url manually, use buttons to navigate!',
                                showConfirmButton: false,
                                color: '#fff',
                                background: '#d9534f',
                                timer: 10500,
                                timerProgressBar: true
                            })
                            next('/home');
                        }
                    });
            },
},
{
    path: '/envoytransferts',
    name: 'envoytransferts.index',
    component: EnvoyTransfertIndex,
         beforeEnter: (to, from, next) => {
            axios.get("/api/getauthuser").then((res) => {
                        const user = res.data;
                        if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin' || user.roles[0].name == 'envoy')
                        {
                            next()
                        }
                        else {
                            Swal.fire({
                                toast: true,
                                position: 'top',
                                icon: 'info',
                                title: 'Avoid changing url manually, use buttons to navigate!',
                                showConfirmButton: false,
                                color: '#fff',
                                background: '#d9534f',
                                timer: 10500,
                                timerProgressBar: true
                            })
                            next('/home');
                        }
                    });
            },
},
{
    path: '/envoytransferts/create',
    name: 'envoytransferts.create',
    component: EnvoyTransfertCreate,
         beforeEnter: (to, from, next) => {
            axios.get("/api/getauthuser").then((res) => {
                        const user = res.data;
                        if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin' || user.roles[0].name == 'envoy')
                        {
                            next()
                        }
                        else {
                            Swal.fire({
                                toast: true,
                                position: 'top',
                                icon: 'info',
                                title: 'Avoid changing url manually, use buttons to navigate!',
                                showConfirmButton: false,
                                color: '#fff',
                                background: '#d9534f',
                                timer: 10500,
                                timerProgressBar: true
                            })
                            next('/home');
                        }
                    });
            },
},
{
    path: '/envoytransferts/:id',
    name: 'envoytransferts.edit',
    component: EnvoyTransfertEdit,
         beforeEnter: (to, from, next) => {
            axios.get("/api/getauthuser").then((res) => {
                        const user = res.data;
                        if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin' || user.roles[0].name == 'envoy')
                        {
                            next()
                        }
                        else {
                            Swal.fire({
                                toast: true,
                                position: 'top',
                                icon: 'info',
                                title: 'Avoid changing url manually, use buttons to navigate!',
                                showConfirmButton: false,
                                color: '#fff',
                                background: '#d9534f',
                                timer: 10500,
                                timerProgressBar: true
                            })
                            next('/home');
                        }
                    });
            },
},
{
    path: '/cashout',
    name: 'cashout.index',
    component: CashoutIndex,
         beforeEnter: (to, from, next) => {
            axios.get("/api/getauthuser").then((res) => {
                        const user = res.data;
                        if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin' || user.roles[0].name == 'envoy' || user.roles[0].name == 'agent' || user.roles[0].name == 'operator')
                        {
                            next()
                        }
                        else {
                            Swal.fire({
                                toast: true,
                                position: 'top',
                                icon: 'info',
                                title: 'Avoid changing url manually, use buttons to navigate!',
                                showConfirmButton: false,
                                color: '#fff',
                                background: '#d9534f',
                                timer: 10500,
                                timerProgressBar: true
                            })
                            next('/home');
                        }
                    });
            },
},
{
    path: '/cashout/create/commission',
    name: 'cashout.create',
    props: true,
    component: CashoutCreate,
         beforeEnter: (to, from, next) => {
            axios.get("/api/getauthuser").then((res) => {
                        const user = res.data;
                        if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin' || user.roles[0].name == 'envoy' || user.roles[0].name == 'agent' || user.roles[0].name == 'operator')
                        {
                            next()
                        }
                        else {
                            Swal.fire({
                                toast: true,
                                position: 'top',
                                icon: 'info',
                                title: 'Avoid changing url manually, use buttons to navigate!',
                                showConfirmButton: false,
                                color: '#fff',
                                background: '#d9534f',
                                timer: 10500,
                                timerProgressBar: true
                            })
                            next('/home');
                        }
                    });
            },
},
{
    path: '/cashout/:id',
    name: 'cashout.edit',
    component: CashoutEdit,
    props: true,
    beforeEnter: (to, from, next) => {
      axios.get("/api/getauthuser").then((res) => {
                  const user = res.data;
                  if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin' || user.roles[0].name == 'envoy')
                  {
                      next()
                  }
                  else {
                      Swal.fire({
                          toast: true,
                          position: 'top',
                          icon: 'info',
                          title: 'Avoid changing url manually, use buttons to navigate!',
                          showConfirmButton: false,
                          color: '#fff',
                          background: '#d9534f',
                          timer: 10500,
                          timerProgressBar: true
                      })
                      next('/home');
                  }
              });
      },
},
{
    path: '/accounttransferts',
    name: 'accounttransferts.index',
    component: AccountTransfertsIndex,
    beforeEnter: (to, from, next) => {
      axios.get("/api/getauthuser").then((res) => {
                  const user = res.data;
                  if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin' || user.roles[0].name == 'operator')
                  {
                      next()
                  }
                  else {
                      Swal.fire({
                          toast: true,
                          position: 'top',
                          icon: 'info',
                          title: 'Avoid changing url manually, use buttons to navigate!',
                          showConfirmButton: false,
                          color: '#fff',
                          background: '#d9534f',
                          timer: 10500,
                          timerProgressBar: true
                      })
                      next('/home');
                  }
              });
      },
},
{
    path: '/accounttransferts/:id',
    name: 'accounttransferts.edit',
    component: AccountTransfertsEdit,
    props: true,
    beforeEnter: (to, from, next) => {
      axios.get("/api/getauthuser").then((res) => {
                  const user = res.data;
                  if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin' || user.roles[0].name == 'operator')
                  {
                      next()
                  }
                  else {
                      Swal.fire({
                          toast: true,
                          position: 'top',
                          icon: 'info',
                          title: 'Avoid changing url manually, use buttons to navigate!',
                          showConfirmButton: false,
                          color: '#fff',
                          background: '#d9534f',
                          timer: 10500,
                          timerProgressBar: true
                      })
                      next('/home');
                  }
              });
      },
},
{
    path: '/providerpayments',
    name: 'providerpayments.index',
    component: ProviderPaymentsIndex,
         beforeEnter: (to, from, next) => {
              axios.get("/api/getauthuser").then((res) => {
                          const user = res.data;
                          if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin')
                          {
                              next()
                          }
                          else {
                              Swal.fire({
                                  toast: true,
                                  position: 'top',
                                  icon: 'info',
                                  title: 'Avoid changing url manually, use buttons to navigate!',
                                  showConfirmButton: false,
                                  color: '#fff',
                                  background: '#d9534f',
                                  timer: 10500,
                                  timerProgressBar: true
                              })
                              next('/home');
                          }
                      });
              },
},
{
    path: '/providerpayments/create',
    name: 'providerpayments.create',
    component: ProviderPaymentsCreate,
         beforeEnter: (to, from, next) => {
              axios.get("/api/getauthuser").then((res) => {
                          const user = res.data;
                          if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin')
                          {
                              next()
                          }
                          else {
                              Swal.fire({
                                  toast: true,
                                  position: 'top',
                                  icon: 'info',
                                  title: 'Avoid changing url manually, use buttons to navigate!',
                                  showConfirmButton: false,
                                  color: '#fff',
                                  background: '#d9534f',
                                  timer: 10500,
                                  timerProgressBar: true
                              })
                              next('/home');
                          }
                      });
              },
},
{
    path: '/roleandpermission',
    name: 'roleandpermission.index',
    component: RoleAndPermissionIndex,
         beforeEnter: (to, from, next) => {
            axios.get("/api/getauthuser").then((res) => {
                        const user = res.data;
                        if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer')
                        {
                            next()
                        }
                        else {
                            Swal.fire({
                                toast: true,
                                position: 'top',
                                icon: 'info',
                                title: 'Avoid changing url manually, use buttons to navigate!',
                                showConfirmButton: false,
                                color: '#fff',
                                background: '#d9534f',
                                timer: 10500,
                                timerProgressBar: true
                            })
                            next('/home');
                        }
                    });
            },
},
{
    path: '/roleandpermission/create',
    name: 'roleandpermission.create',
    component: RoleAndPermissionCreate,
         beforeEnter: (to, from, next) => {
              axios.get("/api/getauthuser").then((res) => {
                          const user = res.data;
                          if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer')
                          {
                              next()
                          }
                          else {
                              Swal.fire({
                                  toast: true,
                                  position: 'top',
                                  icon: 'info',
                                  title: 'Avoid changing url manually, use buttons to navigate!',
                                  showConfirmButton: false,
                                  color: '#fff',
                                  background: '#d9534f',
                                  timer: 10500,
                                  timerProgressBar: true
                              })
                              next('/home');
                          }
                      });
              },
},
{
    path: '/roleandpermission/:id',
    name: 'roleandpermission.edit',
    component: RoleAndPermissionEdit,
    props: true,
         beforeEnter: (to, from, next) => {
              axios.get("/api/getauthuser").then((res) => {
                          const user = res.data;
                          if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer')
                          {
                              next()
                          }
                          else {
                              Swal.fire({
                                  toast: true,
                                  position: 'top',
                                  icon: 'info',
                                  title: 'Avoid changing url manually, use buttons to navigate!',
                                  showConfirmButton: false,
                                  color: '#fff',
                                  background: '#d9534f',
                                  timer: 10500,
                                  timerProgressBar: true
                              })
                              next('/home');
                          }
                      });
              },
},
{
    path: '/roleandpermission/createpermission',
    name: 'roleandpermission.createpermission',
    component: RoleAndPermissionCreatePermission,
         beforeEnter: (to, from, next) => {
              axios.get("/api/getauthuser").then((res) => {
                          const user = res.data;
                          if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer')
                          {
                              next()
                          }
                          else {
                              Swal.fire({
                                  toast: true,
                                  position: 'top',
                                  icon: 'info',
                                  title: 'Avoid changing url manually, use buttons to navigate!',
                                  showConfirmButton: false,
                                  color: '#fff',
                                  background: '#d9534f',
                                  timer: 10500,
                                  timerProgressBar: true
                              })
                              next('/home');
                          }
                      });
              },
},
{
    path: '/departments',
    name: 'departments.index',
    component: DepartmentsIndex,
    beforeEnter: (to, from, next) => {
      axios.get("/api/getauthuser").then((res) => {
                  const user = res.data;
                  if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'admin')
                  {
                      next()
                  }
                  else {
                      Swal.fire({
                          toast: true,
                          position: 'top',
                          icon: 'info',
                          title: 'Avoid changing url manually, use buttons to navigate!',
                          showConfirmButton: false,
                          color: '#fff',
                          background: '#d9534f',
                          timer: 10500,
                          timerProgressBar: true
                      })
                      next('/home');
                  }
              });
      },
},
{
    path: '/departments/create',
    name: 'departments.create',
    component: DepartmentsCreate,
         beforeEnter: (to, from, next) => {
              axios.get("/api/getauthuser").then((res) => {
                          const user = res.data;
                          if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer')
                          {
                              next()
                          }
                          else {
                              Swal.fire({
                                  toast: true,
                                  position: 'top',
                                  icon: 'info',
                                  title: 'Avoid changing url manually, use buttons to navigate!',
                                  showConfirmButton: false,
                                  color: '#fff',
                                  background: '#d9534f',
                                  timer: 10500,
                                  timerProgressBar: true
                              })
                              next('/home');
                          }
                      });
              },
},
{
    path: '/departments/:id',
    name: 'departments.edit',
    component: DepartmentsEdit,
    props: true,
    beforeEnter: (to, from, next) => {
      axios.get("/api/getauthuser").then((res) => {
                  const user = res.data;
                  if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer')
                  {
                      next()
                  }
                  else {
                      Swal.fire({
                          toast: true,
                          position: 'top',
                          icon: 'info',
                          title: 'Avoid changing url manually, use buttons to navigate!',
                          showConfirmButton: false,
                          color: '#fff',
                          background: '#d9534f',
                          timer: 10500,
                          timerProgressBar: true
                      })
                      next('/home');
                  }
              });
      },
},
{
    path: '/replenishment',
    name: 'replenishment.index',
    component: ReplenishmentIndex,
    beforeEnter: (to, from, next) => {
      axios.get("/api/getauthuser").then((res) => {
                  const user = res.data;
                  if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'agent' || user.roles[0].name == 'envoy')
                  {
                      next()
                  }
                  else {
                      Swal.fire({
                          toast: true,
                          position: 'top',
                          icon: 'info',
                          title: 'Avoid changing url manually, use buttons to navigate!',
                          showConfirmButton: false,
                          color: '#fff',
                          background: '#d9534f',
                          timer: 10500,
                          timerProgressBar: true
                      })
                      next('/home');
                  }
              });
      },
},
{
    path: '/replenishment/create/:agentid/:amount/:reqreplenishmentid',
    name: 'replenishment.create',
    component: ReplenishmentCreate,
    props: true,
    // props: (route) => ({ aid: route.query.id, a: route.query.a }),
    beforeEnter: (to, from, next) => {
      axios.get("/api/getauthuser").then((res) => {
                  const user = res.data;
                  if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer')
                  {
                      next()
                  }
                  else {
                      Swal.fire({
                          toast: true,
                          position: 'top',
                          icon: 'info',
                          title: 'Avoid changing url manually, use buttons to navigate!',
                          showConfirmButton: false,
                          color: '#fff',
                          background: '#d9534f',
                          timer: 10500,
                          timerProgressBar: true
                      })
                      next('/home');
                  }
              });
      },
},
{
    path: '/requiredreplenishment',
    name: 'requiredreplenishment.index',
    component: RequiredReplenishmentIndex,
    beforeEnter: (to, from, next) => {
      axios.get("/api/getauthuser").then((res) => {
                  const user = res.data;
                  if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'agent' || user.roles[0].name == 'envoy')
                  {
                      next()
                  }
                  else {
                      Swal.fire({
                          toast: true,
                          position: 'top',
                          icon: 'info',
                          title: 'Avoid changing url manually, use buttons to navigate!',
                          showConfirmButton: false,
                          color: '#fff',
                          background: '#d9534f',
                          timer: 10500,
                          timerProgressBar: true
                      })
                      next('/home');
                  }
              });
      },
},
{
    path: '/expenses',
    name: 'expenses.index',
    component: ExpenseIndex,
    beforeEnter: (to, from, next) => {
      axios.get("/api/getauthuser").then((res) => {
                  const user = res.data;
                  if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer')
                  {
                      next()
                  }
                  else {
                      Swal.fire({
                          toast: true,
                          position: 'top',
                          icon: 'info',
                          title: 'Avoid changing url manually, use buttons to navigate!',
                          showConfirmButton: false,
                          color: '#fff',
                          background: '#d9534f',
                          timer: 10500,
                          timerProgressBar: true
                      })
                      next('/home');
                  }
              });
      },
},
{
    path: '/expenses/create',
    name: 'expenses.create',
    component: ExpenseCreate,
    beforeEnter: (to, from, next) => {
      axios.get("/api/getauthuser").then((res) => {
                  const user = res.data;
                  if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer')
                  {
                      next()
                  }
                  else {
                      Swal.fire({
                          toast: true,
                          position: 'top',
                          icon: 'info',
                          title: 'Avoid changing url manually, use buttons to navigate!',
                          showConfirmButton: false,
                          color: '#fff',
                          background: '#d9534f',
                          timer: 10500,
                          timerProgressBar: true
                      })
                      next('/home');
                  }
              });
      },
},
{
    path: '/sendmoney',
    name: 'sendmoney.index',
    component: SendMoneyIndex,
    beforeEnter: (to, from, next) => {
      axios.get("/api/getauthuser").then((res) => {
                  const user = res.data;
                  if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'envoy' || user.roles[0].name == 'agent')
                  {
                      next()
                  }
                  else {
                      Swal.fire({
                          toast: true,
                          position: 'top',
                          icon: 'info',
                          title: 'Avoid changing url manually, use buttons to navigate!',
                          showConfirmButton: false,
                          color: '#fff',
                          background: '#d9534f',
                          timer: 10500,
                          timerProgressBar: true
                      })
                      next('/home');
                  }
              });
      },
},
{
    path: '/sendmoney/create/:agentid/:amount/:reqreplenishmentid',
    name: 'sendmoney.create',
    component: SendMoneyCreate,
    props: true,
    beforeEnter: (to, from, next) => {
      axios.get("/api/getauthuser").then((res) => {
                  const user = res.data;
                  if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer')
                  {
                      next()
                  }
                  else {
                      Swal.fire({
                          toast: true,
                          position: 'top',
                          icon: 'info',
                          title: 'Avoid changing url manually, use buttons to navigate!',
                          showConfirmButton: false,
                          color: '#fff',
                          background: '#d9534f',
                          timer: 10500,
                          timerProgressBar: true
                      })
                      next('/home');
                  }
              });
      },
},
{
    path: '/profittorecharge',
    name: 'profittorecharge.index',
    component: ProfitToRechargeIndex,
    beforeEnter: (to, from, next) => {
      axios.get("/api/getauthuser").then((res) => {
                  const user = res.data;
                  if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'agent')
                  {
                      next()
                  }
                  else {
                      Swal.fire({
                          toast: true,
                          position: 'top',
                          icon: 'info',
                          title: 'Avoid changing url manually, use buttons to navigate!',
                          showConfirmButton: false,
                          color: '#fff',
                          background: '#d9534f',
                          timer: 10500,
                          timerProgressBar: true
                      })
                      next('/home');
                  }
              });
      },
},
{
    path: '/profittorecharge/create/:commission',
    name: 'profittorecharge.create',
    component: ProfitToRechargeCreate,
    props: true,
    beforeEnter: (to, from, next) => {
      axios.get("/api/getauthuser").then((res) => {
                  const user = res.data;
                  if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'agent')
                  {
                      next()
                  }
                  else {
                      Swal.fire({
                          toast: true,
                          position: 'top',
                          icon: 'info',
                          title: 'Avoid changing url manually, use buttons to navigate!',
                          showConfirmButton: false,
                          color: '#fff',
                          background: '#d9534f',
                          timer: 10500,
                          timerProgressBar: true
                      })
                      next('/home');
                  }
              });
      },
},
{
    path: '/userativities/:id',
    name: 'userativities.show',
    component: UserActivityShow,
    props: true,
    beforeEnter: (to, from, next) => {
      axios.get("/api/getauthuser").then((res) => {
                  const user = res.data;
                  if (user.roles[0].name == 'super-admin' || user.roles[0].name == 'system-engineer' || user.roles[0].name == 'envoy' || user.roles[0].name == 'agent' || user.roles[0].name == 'operator' || user.roles[0].name == 'admin')
                  {
                      next()
                  }
                  else {
                      Swal.fire({
                          toast: true,
                          position: 'top',
                          icon: 'info',
                          title: 'Avoid changing url manually, use buttons to navigate!',
                          showConfirmButton: false,
                          color: '#fff',
                          background: '#d9534f',
                          timer: 10500,
                          timerProgressBar: true
                      })
                      next('/home');
                  }
              });
      },
},
{
  path: '/:pathMatch(.*)*',
  component: Dashboard,
  beforeEnter: (to, from, next) => {
          next('/home')
      },
},
]

export default createRouter({
    history: createWebHistory(),
    routes
});