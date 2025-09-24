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
var report_no='<?php echo $report_number; ?>';
var activate_selected = "<?php echo __('Activate');?>";
var deactivate_selected = "<?php echo __('Deactivate');?>";
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
var remidial_action  = "<?php echo __('Remedial Action');?>";
var delete_mobile_site = "<?php echo __('Do you really want to delete this report?');?>";
var deactivate_mobile_site = "<?php echo __('Do you really want to deactivate this report?');?>";
var activate_mobile_site = "<?php echo __('Do you really want to activate this report?');?>";
var admins = "<?php echo __('Gyro ob Data Management');?>";

var name_tag = "<?php echo __('[Create On]');?>";
var conveyance_value  = "<?php echo __('Conveyance');?>";
var gyro_sn_val = "<?php echo __('Gyro SN#');?>";
var conveyance_by_value  = "<?php echo __('Conveyed By');?>";
var conveyance_type_value  = "<?php echo __('Conveyance Type');?>";
var top_survey= "<?php echo __('Top Survey');?>";
var buttom_survey= "<?php echo __('Bottom Survey');?>";
var latitude = "<?php echo __('Latitude');?>";
var longitude = "<?php echo __('Longitude');?>"; 
var comments = "<?php echo __('Comments');?>";
var lastupdate = "<?php echo __('Last Update');?>"; gyro_no
var gyro_no = "<?php echo __('Gyro No');?>";
      
var report_id = "<?php echo $id; ?>"; 
var report_val="<?php echo $report_val; ?>";

var act = "<?php echo __('Active');?>";
var inact = "<?php echo __('Inactive');?>";

</script>
<aside>
<?php echo $this->Element('left_menu'); ?>
</aside>
 <section>
            <?php   echo $this->Element('jobtab');    ?>
          
           <script language="javascript" type="text/javascript">
              $(document).ready(function() {
                 $("#main").removeClass("selectedtab");
	         $("#job_data").removeClass("selectedtab");
	         $("#gyro_job_data").addClass("selectedtab");
	         $("#gauge_data").removeClass("selectedtab");
	         $("#action_item").removeClass("selectedtab");
	         $("#attachments").removeClass("selectedtab");
	         $("#link").removeClass("selectedtab");
	         $("#csr").removeClass("selectedtab");
	         $("#view").removeClass("selectedtab");
                 $("#print").removeClass("selectedtab");
                });
              function redirect_page(){
                 var path='<?php echo $this->webroot ?>';
                 document.location=path+"Jobs/add_gyro_job_data/<?php echo base64_encode($id); ?>/";  		
                
              }
              
            </script>

           <div class="topadmin_heading clearfix">
           <h2>Job Report(Gyro Job Data-<?php echo $report_number; ?>) List</h2>
           <?php if($is_add==1){ ?>
                 <input type="button" name="button" value="Add Gyro Job Data" onclick="redirect_page();" class="buttonadmin fright" />
           <?php } ?>
          </div>
            
            

       <div id="grid-paging" ><?php echo $this->Html->script('report_job_gyrol_grid'); ?></div>
 </section>
