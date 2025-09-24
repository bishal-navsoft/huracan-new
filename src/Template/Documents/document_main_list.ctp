<script language="JavaScript" type="text/javascript">
var action = "<?php echo $action;?>";
var path = "<?php echo $this->webroot;?>";
var AdminListPage = "<?php echo 'http://'.$_SERVER['HTTP_HOST'].$this->webroot; ?>";
var is_add = "<?php echo $is_add; ?>";
var is_edit = "<?php echo $is_edit; ?>";
var is_view = "<?php echo $is_view; ?>";
var is_block = "<?php echo $is_block; ?>";
var is_delete = "<?php echo $is_delete; ?>";
var pagelmt = "<?php if($limit=='all'){ echo '1000';}else{ echo $limit; }?>";

var activate_selected = "<?php echo __('Activate ');?>";
var deactivate_selected = "<?php echo __('Deactivate ');?>";
var delete_selected = "<?php echo __('Delete');?>";
var search_by = "<?php echo __('Search by');?>";
var search_text = "<?php echo __('search text...');?>";
var go = "<?php echo __('Go');?>";
var stts = "<?php echo __('Status');?>";
var acts = "<?php echo __('Actions');?>";
var edt = "<?php echo __('Edit');?>";
var activ = "<?php echo __('Activate');?>";
var deactiv = "<?php echo __('Deactivate');?>";
var view = "<?php echo __('View');?>";
var dataentry = "<?php echo __('Data Entry');?>";
var rejection = "<?php echo __('Rejection');?>";
var dlt = "<?php echo __('Delete');?>";
var activ_select_record = "<?php echo __('Activate selected record(s)');?>";
var deactiv_select_record = "<?php echo __('Deactivate selected record(s)');?>";
var select_one_record = "<?php echo __('Please select at least one record.');?>";
var activate_select_record = "<?php echo __('Do you really want to activate selected record(s)?');?>";
var deactivate_select_record = "<?php echo __('Do you really want to deactivate selected record(s)?');?>";
var delete_select_record = "<?php echo __('Do you really want to delete selected record(s)?');?>";
var please_wait = "<?php echo __('Please wait ....');?>";
var performing_actions = "<?php echo __('Performing Actions');?>";
var warning = "<?php echo __('Warning');?>";
var not_allowed_access = "<?php echo __('Sorry! You are not allowed to access this link.');?>";
var err = "<?php echo __('ERROR');?>";
var err_unblock = "<?php echo __('Error during unblock ');?>";
var err_block = "<?php echo __('Error during block ');?>";
var err_delete = "<?php echo __('Error during delete ');?>";
var del_select_record = "<?php echo __('Delete selected record(s)');?>";
var no_undo = "<?php echo __('There is no undo.');?>";
var display_topics = "<?php echo __('Displaying topics {0} - {1} of {2}');?>";
var no_display_records = "<?php echo __('No records to display');?>";
var message = "<?php echo __('Message');?>";
var already_delivered = "<?php echo __('Already Activated.');?>";
var already_not_delivered = "<?php echo __('Already Deactivated.');?>";
var saving_scores = "<?php echo __('Saving scores in this page now...');?>";
var set_column_search = "<?php echo __('set column for search.');?>";
var provide_search_string = "<?php echo __('Please provide search string.');?>";
var del_record = "<?php echo __('Delete record');?>";

var delete_mobile_site = "<?php echo __('Do you really want to delete this report?');?>";
var deactivate_mobile_site = "<?php echo __('Do you really want to deactivate this report?');?>";
var activate_mobile_site = "<?php echo __('Do you really want to activate this report?');?>";
var admins = "<?php echo __('Report Management');?>";
var name = "<?php echo __('Email');?>";
var name_tag = "<?php echo __('[Report No]');?>";
var created = "<?php echo __('Created Date');?>";
var reportno  = "<?php echo __('Report');?>"; 
var incident_type_name  = "<?php echo __('Incident Type');?>";
var type  = "<?php echo __('Type');?>";
var  closure = "<?php echo __('Closer Date');?>";
var createdby  = "<?php echo __('Created By');?>";
var summary  = "<?php echo __('Summary');?>"; 
var filedlocation_type_name  = "<?php echo __('Field Location');?>";
var loss_time  = "<?php echo __('Loss Time');?>";
var client = "<?php echo __('Client');?>";
var business_type_name  = "<?php echo __('Business Unit');?>";
var incident_serverity = "<?php echo __('Incident Serverity');?>";
var create_date_val = "<?php echo __('Create On');?>";
var validate_name = "<?php echo __('Validate By');?>";
var validate_date_val = "<?php echo __('Validate Date');?>";
var re_revalidation_date = "<?php echo __('Revalidate Date');?>";
var act = "<?php echo __('Active');?>";
var inact = "<?php echo __('Inactive');?>";
//var login_history = "<?php echo __('Login History');?>";
var  listVal='<?php echo implode(",",$_SESSION['idDocumentBoolen']);  ?>';
function pageRedrection(){
                
               document.location=path+"Documents/add_document_report_main/";  		
                
   }
</script>
<aside>
<?php echo $this->Element('left_menu'); ?>
</aside>
 <section>
<div class="topadmin_heading clearfix">
<h2>Documentation(Main) List</h2>
 <?php if($is_add==1){ ?>
<input type="button" name="button" value="Add Main" onclick="pageRedrection();" class="buttonadmin fright" />
<?php } ?>
</div>
 <div id="grid-paging" ><?php  echo $this->Html->script('report_document_grid'); ?></div>
 </section>