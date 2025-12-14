<?php
ob_start();
$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();
if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}
if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}
if($action == "fetch_epas_by_district"){
	$fetch = $crud->fetch_epas_by_district();
	if($fetch)
		echo $fetch;
}
if($action == 'save_user'){
	$save = $crud->save_user();
	if($save)
		echo $save;
}

if($action == 'resend_sms'){
	$save = $crud->resend_sms();
	if($save)
		echo $save;
}
if($action == 'update_saving_status'){
	$save = $crud->update_saving_status();
	if($save)
		echo $save;
}
if($action == 'update_target_amount'){
	$save = $crud->update_target_amount();
	if($save)
		echo $save;
}
if($action == 'toggle_status_account'){
	$save = $crud->toggle_status_account();
	if($save)
		echo $save;
}
if($action == "save_account"){
	$save = $crud->save_account();
	if($save)
		echo $save;
}
if($action == "save_savings_plan"){
	$save = $crud->save_savings_plan();
	if($save)
		echo $save;
}

if($action == "fetch_scheme_inputs"){
	$save = $crud->fetch_scheme_inputs();
	if($save)
		echo $save;
}

if($action == "get_customer_schemes"){
	$save = $crud->get_customer_schemes();
	if($save)
		echo $save;
}

if($action == "save_scheme_input_item"){
	$save = $crud->save_scheme_input_item();
	if($save)
		echo $save;
}
if($action == "delete_customer"){
	$delete = $crud->delete_customer();
	if($delete)
		echo $delete;
}

if($action == "delete_scheme_input"){
	$delete = $crud->delete_scheme_input();
	if($delete)
		echo $delete;
}

if($action == "save_scheme"){
	$save = $crud->save_scheme();
	if($save)
		echo $save;
}

if($action == "initiate_fund_transfer"){
	$save = $crud->initiate_fund_transfer();
	if($save)
		echo $save;
}
if($action == "save_voucher"){
	$save = $crud->save_voucher();
	if($save)
		echo $save;
}
if($action == "save_ussd_access"){
	$save = $crud->save_ussd_access();
	if($save)
		echo $save;
}
if($action == "save_agent"){
	$save = $crud->save_agent();
	if($save)
		echo $save;
}
if($action == "fetch_schemes_by_account"){
	$fetch = $crud->fetch_schemes_by_account();
	if($fetch)
		echo $fetch;
}
if($action == "save_charge"){
	$save = $crud->save_charge();
	if($save)
		echo $save;
}
if($action == "get_accounts_by_type"){
	$save = $crud->get_accounts_by_type();
	if($save)
		echo $save;
}
if($action == "save_branch"){
	$save = $crud->save_branch();
	if($save)
		echo $save;
}
if($action == "toggle_scheme_status"){
	$save = $crud->toggle_scheme_status();
	if($save)
		echo $save;
}

if($action == "toggle_agent_status"){
	$save = $crud->toggle_agent_status();
	if($save)
		echo $save;
}
if($action == "reset_agent_pin"){
	$save = $crud->reset_agent_pin();
	if($save)
		echo $save;
}
if($action == "save_manual_reconciliation"){
	$save = $crud->save_manual_reconciliation();
	if($save)
		echo $save;
}
if($action == "get_customers_by_agent"){
	$save = $crud->get_customers_by_agent();
	if($save)
		echo $save;
}
if($action == "get_savings_by_customer"){
	$save = $crud->get_savings_by_customer();
	if($save)
		echo $save;
}

if($action == "send_sms"){
	$save = $crud->send_sms();
	if($save)
		echo $save;
}

if($action == "send_test_sms"){
	$save = $crud->send_test_sms();
	if($save)
		echo $save;
}

ob_end_flush();
?>
