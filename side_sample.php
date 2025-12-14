  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="dropdown">
   	<a href="javascript:void(0)" class="brand-link dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
        <span class="brand-image img-circle elevation-3 d-flex justify-content-center align-items-center bg-primary text-white font-weight-500" style="width: 38px;height:50px"><?php echo strtoupper(substr($_SESSION['login_firstname'], 0,1).substr($_SESSION['login_lastname'], 0,1)) ?></span>
        <span class="brand-text font-weight-light">System Settings</span>

      </a>
      <div class="dropdown-menu" style="">
        <a class="dropdown-item manage_account" href="javascript:void(0)" data-id="<?php echo $_SESSION['login_id'] ?>">Manage Account</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="ajax.php?action=logout">Logout</a>
      </div>
    </div>


    <div class="sidebar">

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">

          <li class="nav-item dropdown">
            <a href="./" class="nav-link nav-home">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>     
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link nav-add_account">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Manage Accounts
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./?page=new_account" class="nav-link nav-new_account tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>New Account</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./?page=account_list" class="nav-link nav-account_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Individual Accounts List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./?page=new_cooperative" class="nav-link nav-new_cooperative tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Cooperative Account</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./?page=cooperative_list" class="nav-link nav-cooperative_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Cooperative Accounts List</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
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
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link nav-add_savings">
              <i class="nav-icon fas fa-piggy-bank"></i>
              <p>
                Manage Saving Plans
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
            </ul>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link nav-add_scheme">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Manage Schemes
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./?page=new_scheme" class="nav-link nav-new_scheme tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>New Saving Scheme</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="./?page=schemes_list" class="nav-link nav-schemes_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Savings Scheme List</p>
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
          </li>

          <li class="nav-item">
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
                <a href="./?page=value_offloading" class="nav-link nav-value_offloading tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Value Offloading</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
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
            </ul>
          </li>

          <li class="nav-item">
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
          </li>

           <li class="nav-item">
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
          </li>

           <li class="nav-item">
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
          </li>

          <li class="nav-item">
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
                <a href="./?page=activity_log" class="nav-link nav-activity_log tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Activity Log</p>
                </a>
              </li>
            </ul>
          </li>

          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </aside>
  <script>
  	$(document).ready(function(){
  		var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
  		if($('.nav-link.nav-'+page).length > 0){
  			$('.nav-link.nav-'+page).addClass('active')
          console.log($('.nav-link.nav-'+page).hasClass('tree-item'))
  			if($('.nav-link.nav-'+page).hasClass('tree-item') == true){
          $('.nav-link.nav-'+page).closest('.nav-treeview').siblings('a').addClass('active')
  				$('.nav-link.nav-'+page).closest('.nav-treeview').parent().addClass('menu-open')
  			}
  		}
      $('.manage_account').click(function(){
        uni_modal('Manage Account','manage_user.php?id='+$(this).attr('data-id'))
      })
  	})
  </script>