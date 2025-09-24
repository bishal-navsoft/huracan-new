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
var report_id ="<?php echo $report_id; ?>";
var delete_mobile_site = "<?php echo __('Do you really want to delete this report?');?>";
var deactivate_mobile_site = "<?php echo __('Do you really want to deactivate this report?');?>";
var activate_mobile_site = "<?php echo __('Do you really want to activate this report?');?>";
var admins = "<?php echo __('Report Management');?>";
var name = "<?php echo __('Report No');?>";
var name_tag = "<?php echo __('[Report No]');?>";

var filterTYPE = "<?php echo  $typSearch ;?>";
var type_name = "<?php echo __('Type');?>";
var link_report_no = "<?php echo __('Report No');?>";


var act = "<?php echo __('Active');?>";
var inact = "<?php echo __('Inactive');?>";
var login_history = "<?php echo __('Login History');?>";

function passLinkType(){
	     var report_type=document.getElementById('report_type').value;
	     var rootpath='<?php echo $this->webroot ?>';
	     	     
      document.location='<?php echo $this->webroot; ?>Jobs/report_job_link_list/<?php echo $report_id_val; ?>/'+'/'+Base64.encode(report_type);
	    
	    
	    
}

function add_job_link()
    {

  
                var report_data=document.getElementById('report_data').value;
           	if(report_data!=0){   
                var rootpath='<?php echo $this->webroot ?>';
                document.getElementById('res_msg').innerHTML='<img src="<?php echo $this->webroot; ?>img/loader.gif" />';	
                  
                  
                   $.ajax({
                              type: "POST",
                              url: rootpath+"Jobs/linkrocess/",
                              data:"report_no=<?php echo $report_id ?>&type="+report_data,
                              success: function(res)
                              {
                                         
                                if(res=='ok'){
                                       document.getElementById('res_msg').innerHTML='&nbsp;<font color="green">Link  Data Added Successfully</font>';
                                       document.location='<?php echo $this->webroot; ?>Jobs/report_job_link_list/<?php echo $report_id_val; ?>/<?php echo base64_encode('all'); ?>';
                                 }else if(res=='avl'){
                                        document.getElementById('res_msg').innerHTML='&nbsp;<font color="red">Report number already exists</font>';
                                 }else if(res=='fail'){
                                       document.getElementById('res_msg').innerHTML='&nbsp;<font color="red">Please try again</font>';
                                 }
                             
                             
                                 
                                 
                     }
                     
            });
            
    
            return false;
                }else{
                 document.getElementById('report_data').focus(); 
                 document.getElementById('res_msg').innerHTML='<font color="red">Please select report no</font>';
                }
 
     }   

     
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
	   $("#gyro_job_data").removeClass("selectedtab");
	   $("#gauge_data").removeClass("selectedtab");
	   $("#action_item").removeClass("selectedtab");
	   $("#attachments").removeClass("selectedtab");
	   $("#link").addClass("selectedtab");
	   $("#csr").removeClass("selectedtab");
	   $("#view").removeClass("selectedtab");
           $("#print").removeClass("selectedtab");
	 
      });
</script>		  
		  
<div class="topadmin_heading clearfix">
 <?php if($is_add==1){ ?>
<h2>Job Report(<?php echo $report_number; ?>) Link List</h2>
<?php


} ?>
</div>

<div class="looptable_panel">

                 <table width="100%" border="0" cellspacing="0" cellpadding="0"  class="dyntable">
                           <tr>
			     <td width="15%" align="left" valign="middle">
		                 <?php   echo $this->Element("report_dropdown"); ?>
				    
			     </td>	    
                             <td width="15%" align="left" valign="middle">
                                 <?php   echo $this->Element("link_list"); ?>
                             </td>
                             <td width="60%" align="left" valign="middle" id="res_msg" ></td>
                             <td width="10%" align="right" valign="middle" >
                               <?php if($is_add==1){ ?>
                                <input type="button" name="button" value="Add" class="buttonsave" onclick="add_job_link();" />
                                <?php } ?>
                                </td>
                           </tr>
                         </table>
                 
</div>

<div id="grid-paging" ><?php  echo $this->Html->script('job_link_grid'); ?></div>
</section>

