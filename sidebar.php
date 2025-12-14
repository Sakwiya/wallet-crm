  <?php 
   include 'menu_functions.php';
  ?>
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

           <?php
                renderDashboardMenu();
              if($_SESSION['login_type'] == 1) { // Admin
                renderAccountsMenu();
                renderVSLAsMenu();
                renderBranchesMenu();
                renderSavingsPlanMenu();
                renderSchemesMenu();
                renderAgentsMenu();
                renderSMSMenu();
                renderTransactionsMenu();
                renderTransferFundsMenu();
                renderApproveFundTransferMenu();
                renderVouchersMenu();
                renderUSSDMenu();
                renderAgroDealersMenu();
                renderUsersMenu();
              } elseif ($_SESSION['login_type'] == 3) { // Accountant
                renderAccountsMenu();
                renderTransactionsMenu();
                renderSavingsPlanMenu();
                renderAgentsMenu();
                renderAgroDealersMenu();
                renderUSSDMenu();
                //renderTransferFundsMenu();
              } elseif ($_SESSION['login_type'] == 2) { // Data Entry
                renderAccountsMenu();
                renderSchemesMenu();
                renderSavingsPlanMenu();
                renderAgentsMenu();
                renderVouchersMenu();
                //renderTransactionsMenu();
                //renderTransferFundsMenu();
              }
            ?>
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