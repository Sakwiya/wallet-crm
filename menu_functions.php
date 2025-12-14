<?php
function renderDashboardMenu() {
  echo '<li class="nav-item dropdown">
            <a href="./" class="nav-link nav-home">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>     
         </li>';
}

function renderTransactionsMenu() {
  echo '<li class="nav-item">
            <a href="#" class="nav-link nav-add_transactions">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Manage Transactions
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./?page=all_transactions" class="nav-link nav-all_transactions tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>All Transactions</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="./?page=momo_transactions" class="nav-link nav-momo_transactions tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>MoMo Transactions</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./?page=payment_callbacks" class="nav-link nav-payment_callbacks tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Payment Callbacks</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./?page=manual_reconciliation" class="nav-link nav-manual_reconciliation tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Manual Reconciliation</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./?page=check_duplicates" class="nav-link nav-check_duplicates tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Duplicate Transactions</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./?page=credit_scoring" class="nav-link nav-credit_scoring tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Credit Scoring</p>
                </a>
              </li>
            </ul>
          </li>';
}

function renderTransferFundsMenu() {
  echo '<li class="nav-item">
            <a href="#" class="nav-link nav-add_transfer">
              <i class="nav-icon fas fa-cart-arrow-down"></i>
              <p>
                Manage Transfer Funds
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./?page=scheme_to_scheme" class="nav-link nav-scheme_to_scheme tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Scheme To Scheme</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./?page=deposit_on_behalf" class="nav-link nav-deposit_on_behalf tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Deposit for Agent</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="./?page=scheme_transfer_list" class="nav-link nav-scheme_transfer_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Scheme Transfer Lists</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="./?page=wallet_to_wallet" class="nav-link nav-wallet_to_wallet tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Wallet to Wallet</p>
                </a>
              </li>
            </ul>
          </li>';
}

function renderApproveFundTransferMenu() {
  echo '<li class="nav-item">
            <a href="#" class="nav-link nav-add_transfer">
              <i class="nav-icon fas fa-clipboard-check"></i>
              <p>
                Approve Funds Transfer
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./?page=approve_fund_transfer" class="nav-link nav-approve_fund_transfer tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Approve Fund Transfer</p>
                </a>
              </li>
            </ul>
          </li>';
}

function renderAccountsMenu() {
  echo '<li class="nav-item">
            <a href="#" class="nav-link nav-add_account">
              <i class="nav-icon fas fa-wallet"></i>
              <p>
                Manage Wallets
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./?page=new_wallet" class="nav-link nav-new_wallet tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>New Wallet</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./?page=wallet_list" class="nav-link nav-wallet_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Individual Wallet List</p>
                </a>
              </li>
            </ul>
          </li>';
}

function renderVSLAsMenu() {
  echo '<li class="nav-item">
            <a href="#" class="nav-link nav-add_account">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Manage VSLAs
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./?page=new_vsla" class="nav-link nav-new_vsla tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>New VSLA</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./?page=new_vsla_member" class="nav-link nav-new_vsla_member tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>New VSLA Members</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./?page=vsla_contributions" class="nav-link nav-vsla_contributions tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>VSLA Contributions</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./?page=vsla_loans" class="nav-link nav-vsla_loans tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>VSLA Loans</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./?page=vsla_transactions" class="nav-link nav-vsla_transactions tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>VSLA Transactions</p>
                </a>
              </li>
            </ul>
          </li>';
}


function renderBranchesMenu() {
  echo '<li class="nav-item">
            <a href="#" class="nav-link nav-add_branch">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Manage Branches
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./?page=new_branch" class="nav-link nav-new_branch tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>New Branch</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="./?page=branch_list" class="nav-link nav-branch_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Branch List</p>
                </a>
              </li>
            </ul>
          </li>';
}

function renderSavingsPlanMenu() {
  echo '<li class="nav-item">
            <a href="#" class="nav-link nav-add_savings">
              <i class="nav-icon fas fa-piggy-bank"></i>
              <p>
                Manage Saving Invoices
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./?page=new_savings_plan" class="nav-link nav-new_savings_plan tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>New Savings Plan</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="./?page=savings_plan_list" class="nav-link nav-savings_plan_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Savings Plan List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./?page=farm_input_summary" class="nav-link nav-farm_input_summary tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Branch Input Summary</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./?page=pending_input_redeem_summary" class="nav-link nav-pending_input_redeem_summary tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Branch Pending Redeems</p>
                </a>
              </li>
              <li class="nav-item">
              <li class="nav-item">
                <a href="./?page=branch_input_quantities" class="nav-link nav-branch_input_quantities tree-item">
                <i class="fas fa-angle-right nav-icon"></i>
                <p>Branch Input Quantities</p>
               </a>
             </li>                          
              <li class="nav-item">
                <a href="./?page=savings_by_wallet" class="nav-link nav-savings_by_wallet tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Total Savings By Wallet</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./?page=savings_balance_report" class="nav-link nav-savings_balance_report tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Savings Balance Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./?page=best_farmers_report" class="nav-link nav-best_farmers_report tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Best Farmers Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./?page=reports" class="nav-link nav-reports tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Reports</p>
                </a>
              </li>
            </ul>
          </li>';
          
}

function renderSchemesMenu() {
  echo '<li class="nav-item">
            <a href="#" class="nav-link nav-add_scheme">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Manage Invoices
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./?page=new_scheme" class="nav-link nav-new_scheme tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>New Saving Invoice</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="./?page=schemes_list" class="nav-link nav-schemes_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Savings Invoice List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./?page=new_scheme_inputs" class="nav-link nav-new_scheme_inputs tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>New Scheme Inputs</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./?page=new_charge" class="nav-link nav-new_charge tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>New Charge Fee</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./?page=charges_list" class="nav-link nav-charges_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Charge Fees List</p>
                </a>
              </li>
            </ul>
          </li>';
}


function renderAgentsMenu() {
  echo '<li class="nav-item">
            <a href="#" class="nav-link nav-add_agent">
              <i class="nav-icon fas fa-user-shield"></i>
              <p>
                Manage Agents
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./?page=new_agent" class="nav-link nav-new_agent tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>New Agents</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="./?page=agents_list" class="nav-link nav-agents_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Agents List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./?page=agents_balances" class="nav-link nav-agents_balances tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Agents Balances</p>
                </a>
              </li>
              <li class="nav-item">
                  <a href="./?page=agent_impact" class="nav-link nav-agent_impact tree-item">
                    <i class="fas fa-angle-right nav-icon"></i>
                    <p>Agent Impact</p>
                  </a>
                </li>
               <li class="nav-item">
                <a href="./?page=value_offloading" class="nav-link nav-value_offloading tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Value Offloading</p>
                </a>
              </li>
            </ul>
          </li>';
}

function renderVouchersMenu() {
  echo '<li class="nav-item">
            <a href="#" class="nav-link nav-add_e_voucher">
              <i class="nav-icon fas fa-credit-card"></i>
              <p>
                Manage E-vouchers
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./?page=new_voucher" class="nav-link nav-new_voucher tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>New E-Voucher</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="./?page=voucher_list" class="nav-link nav-voucher_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>E-Voucher Lists</p>
                </a>
              </li>
            </ul>
          </li>';
}

function renderSMSMenu() {
  echo '<li class="nav-item">
            <a href="#" class="nav-link nav-add_agent">
              <i class="nav-icon fas fa-bullhorn"></i>
              <p>
                Advisory Messages
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./?page=new_message" class="nav-link nav-new_message tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>New Advisory SMS</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="./?page=advisory_list" class="nav-link nav-advisory_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Advisory SMS List</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="./?page=assign_advisory" class="nav-link nav-assign_advisory tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Assign Advisory SMSs</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./?page=bulk_sms" class="nav-link nav-bulk_sms tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Bulk SMS</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./?page=sms_logs" class="nav-link nav-sms_logs tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>SMS Logs</p>
                </a>
              </li>
            </ul>
          </li>';
}


function renderUSSDMenu() {
  echo '<li class="nav-item">
            <a href="#" class="nav-link nav-add_ussd_access">
              <i class="nav-icon fas fa-sitemap"></i>
              <p>
                Manage USSD Access
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./?page=new_ussd_access" class="nav-link nav-new_ussd_access tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>New USSD Access</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="./?page=ussd_access_list" class="nav-link nav-ussd_access_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>USSD Access Lists</p>
                </a>
              </li>
            </ul>
          </li>';
}

function renderAgroDealersMenu() {
  echo '<li class="nav-item">
            <a href="#" class="nav-link nav-add_agro_dealer">
              <i class="nav-icon fas fa-seedling"></i>
              <p>
                Manage Agro Dealers
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./?page=new_agro_dealer" class="nav-link nav-new_agro_dealer tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>New Agro Dealer</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="./?page=agro_dealer_list" class="nav-link nav-agro_dealer_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Agro Dealer Lists</p>
                </a>
              </li>
            </ul>
          </li>';
}

function renderUsersMenu() {
  echo '<li class="nav-item">
            <a href="#" class="nav-link nav-add_user">
              <i class="nav-icon fas fa-user-clock"></i>
              <p>
                Manage Users
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./?page=new_user" class="nav-link nav-new_user tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>New Users</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="./?page=users_list" class="nav-link nav-users_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Users List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./?page=new_organization" class="nav-link nav-new_organization tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>New Organization</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./?page=activity_log" class="nav-link nav-activity_log tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Activity Log</p>
                </a>
              </li>
            </ul>
          </li>';
}
?>
