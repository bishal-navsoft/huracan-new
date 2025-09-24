<script language="JavaScript" type="text/javascript">
      
var path = "<?php echo $this->webroot;?>";
var AdminListPage = "<?php echo 'http://'.$_SERVER['HTTP_HOST'].$this->webroot; ?>";
var is_add = 1;
var is_edit = 1;
var is_view = 1;
var is_block = 1;
var is_delete =1;
var pagelmt = "<?php if($limit=='all'){ echo '1000';}else{ echo $limit; }?>";

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

var delete_mobile_site = "<?php echo __('Do you really want to delete this report?');?>";
var deactivate_mobile_site = "<?php echo __('Do you really want to deactivate this report?');?>";
var activate_mobile_site = "<?php echo __('Do you really want to activate this report?');?>";
var admins = "<?php echo __('Report Management');?>";
var name = "<?php echo __('Name');?>";
var name_tag = "<?php echo __('[Name]');?>";
var lossname = "<?php echo __('Loss');?>";
var comment  = "<?php echo __('Comment');?>";
var imd_cause_name  = "<?php echo __('Immediate cause');?>";
var root_cause_list  = "<?php echo __('Root Cause');?>";
var investigation_no = "<?php echo __('Investigation No'); ?>";
var incident_no = "<?php echo __('Incident No'); ?>";
var report_id = "<?php echo $report_id; ?>";
var report_val = "<?php echo $report_val; ?>";

var act = "<?php echo __('Active');?>";
var inact = "<?php echo __('Inactive');?>";

</script>
<aside>
<?php echo $this->Element('left_menu'); ?>
</aside>
 <section>
            <?php   echo $this->Element('hssetab');    ?>
          
           <script language="javascript" type="text/javascript">
              $(document).ready(function() {
                  $("#main").removeClass("selectedtab");
                  $("#clientdata").removeClass("selectedtab");
                  $("#personnel").removeClass("selectedtab");
                  $("#incident").removeClass("selectedtab");
                  $("#investigation").removeClass("selectedtab");
                  $("#investigationdata").addClass("selectedtab");
                  $("#remidialaction").removeClass("selectedtab");
                  $("#attachment").removeClass("selectedtab");
                  $("#clientfeedback").removeClass("selectedtab");
                  $("#view").removeClass("selectedtab");
                });
              function pageRedrection(){
                 var path = "<?php echo $this->webroot;?>";
                 document.location=path+"Reports/add_investigation_data_analysis/<?php echo base64_encode($report_id); ?>/";  		
                
              }
              
            </script>
           
         
           <div class="topadmin_heading clearfix">
                      <h2>HSSE Report (Data Analysis- <?php echo $report_number; ?>) List</h2>
                      <?php if($is_add==1){ ?>
                             <input type="button" name="button" value="Add Incident Investigation" onclick="pageRedrection();" class="buttonadmin fright" />
                      <?php } ?>
           </div>
          
          
          
      <div id="grid-paging" ><?php echo $this->Html->script('report_hsse_incident_investigation_grid'); ?></div>
 </section>
