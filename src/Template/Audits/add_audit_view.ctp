<aside>
<?php echo $this->Element('left_menu'); ?>
</aside>
<section>
      <?php
            echo $this->Element('audittab');
    
     ?>
    <script language="javascript" type="text/javascript">
     
      
     $(document).ready(function() {
	   $("#main").removeClass("selectedtab");
	   $("#client").removeClass("selectedtab");
	   $("#action").removeClass("selectedtab");
	   $("#attachments").removeClass("selectedtab");
	   $("#link").removeClass("selectedtab");
	   $("#view").addClass("selectedtab");
	   $("#print").removeClass("selectedtab");
	 
      });
    </script>
  <div class="view_sub_contentwrap">
	  	  <table width="100%" border="0" cellspacing="1" cellpadding="0" class="reporttableHeader">
		    <tr>
			      <td width="22%" align="left" valign="middle" class="titles">Date of Report:</td>
			      <td width="27%" align="left" valign="middle" class="titles" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['AuditReportMain']['event_date_format']; ?></td>
			      <td width="23%" align="left" valign="middle" class="titles">Report Number:</td>
			      <td width="28%" align="left" valign="middle" class="titles" style="color:red;border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['AuditReportMain']['report_no']; ?></td>
		    </tr>
		    <tr>
			     <td align="left" valign="middle" class="titles">Days Since Event:</td>
			     <td align="left" valign="middle" class="titles" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['AuditReportMain']['since_event']; ?></td>
			     <td align="left" valign="middle" class="titles">Closure Date:</td>
			      <?php
			      if($_SESSION['adminData']['RoleMaster']['id']==1 && $closer_date!='00/00/0000'){?>
			      <td align="left" valign="middle" class="titles" id="<?php echo $id; ?>" style="border-right: 2px solid #C9A5E4;"><?php echo $closer_date; ?> <input type="button" name="save" id="save" class="buttonview" onclick="close_reopen('reopen');"  value="Reopen" /></td>  
			      <?php }elseif($_SESSION['adminData']['RoleMaster']['id']==1 && $closer_date=='00/00/0000'){?> 
			      <td align="left" valign="middle" class="titles" id="<?php echo $id; ?>" style="border-right: 2px solid #C9A5E4;"><input type="button" name="save" id="save" class="buttonview" onclick="close_reopen('close');"  value="Close" /></td>
			      <?php }else{ ?>
			       <td align="left" valign="middle" class="titles" id="<?php echo $id; ?>" style="border-right: 2px solid #C9A5E4;"><input type="button" name="save" id="save" class="buttonview" value="Close" /></td>
			      <?php } ?>
		   </tr>
	 </table>
         <table width="100%" border="0" cellspacing="1" cellpadding="0" class="reporttablewithoutborder">
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Report Type:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['AuditType']['type']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Created By:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['AdminMaster']['first_name']." ".$reportdetail[0]['AdminMaster']['last_name']; ?></td>
                   </tr>
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Business Unit:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php if(isset($reportdetail[0]['BusinessType'])){echo $reportdetail[0]['BusinessType']['type'];}else{} ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Official?</td>
                         
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php  echo $reportdetail[0]['AuditReportMain']['official_format']; ?></td>
                   </tr>
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Field Location:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php if(isset($reportdetail[0]['Fieldlocation'])){echo $reportdetail[0]['Fieldlocation']['type'];}else{} ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Reporter:</td>
                         
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php   echo $reportdetail[0]['AuditReportMain']['reporter_name']; ?></td>
                   </tr>
		
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Country:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['Country']['name']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Client:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['Client']['name']; ?></td>
                   </tr>
         </table>
         <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">
		    <tr>
		          <td colspan="2" align="center" valign="middle" class="titles">Classification</td>
		    </tr>
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Summary:</td>
                           <td align="left" width="78%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php if(isset($reportdetail[0]['AuditReportMain']['summary'])){echo $reportdetail[0]['AuditReportMain']['summary'];}else{} ?></td>
                          
                   </tr>
                    <tr>
                           <td align="left" width="22%" valign="middle" class="label">Details:</td>
                           <td align="left" width="78%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php if(isset($reportdetail[0]['AuditReportMain']['summary'])){echo $reportdetail[0]['AuditReportMain']['details'];}else{} ?></td>
                          
                   </tr>
         </table>
         <?php
	 if($_SESSION['audit_client']!='N/A'){
	 if(isset($reportdetail[0]['AuditClient'][0])){
	    
	    ?>
         <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">
		    <tr>
		          <td colspan="4" align="center" valign="middle" class="titles">Client - <?php echo $reportdetail[0]['Client']['name']; ?></td>
		    </tr>
                    <tr>
                           <td align="left" width="22%" valign="middle" class="label">Well:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['AuditClient'][0]['well']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Client Reviewed:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $clientreviewed; ?></td>
                   </tr>
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Rig:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['AuditClient'][0]['rig']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Client Reviewer:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['AuditClient'][0]['clientreviewer']; ?></td>
                   </tr>
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Wellsite Rep:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['AuditClient'][0]['wellsiterep']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Review Date:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo  $reportdetail[0]['AuditReportMain']['review_date_format']; ?></td>
                   </tr>
        </table>
        <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">
		    <tr>
		          <td colspan="4" align="center" valign="middle" class="titles">Client Feedback / Comments</td>
		    </tr>
                     <tr>
		          <td colspan="4" align="left" valign="middle" class="label1"><?php echo $reportdetail[0]['AuditClient'][0]['client_feedback']; ?></td>
		    </tr>
                     <tr>
                           <td align="left" width="22%" valign="middle" class="label">Client Name:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['Client']['name']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Date:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo  $reportdetail[0]['AuditReportMain']['client_feedback_date_format']; ?></td>
                   </tr> 
                     
        </table>
       <?php }
	 }
        if(isset($reportdetail[0]['AuditRemidial'][0])){
          ?>
          <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">

           <?php
             for($s=0;$s<count($reportdetail[0]['AuditRemidial']);$s++){?>
                             <tr><td colspan="4" align="center" valign="middle" class="titles">Remedial Action Item <?php echo $reportdetail[0]['AuditRemidial'][$s]['remedial_no']; ?></td></tr> 
                   <tr>

                         <td align="left" width="22%" valign="middle" class="label">Created On:</td>
			 <td align="left"  width="27%" valign="middle"><?php echo $reportdetail[0]['AuditRemidial'][$s]['remidial_create'];?></td>
                         <td align="left" width="23%" valign="middle" class="label">Closure Target:</td>
			 <td align="left"  width="28%" valign="middle"><?php echo $reportdetail[0]['AuditRemidial'][$s]['rem_cls_trgt'];?></td>

                   </tr>
                  <tr>

                         <td align="left" width="22%" valign="middle" class="label">Created By:</td>
			 <td align="left"  width="27%" valign="middle"><?php echo $reportdetail[0]['AuditRemidial'][$s]['AdminMaster']['first_name'].' '.$reportdetail[0]['AuditRemidial'][$s]['AdminMaster']['last_name'];?></td>
                         <td align="left" width="23%" valign="middle" class="label">Last Update:</td>
			 <td align="left"  width="28%" valign="middle"><?php echo $reportdetail[0]['AuditRemidial'][$s]['modified'];?></td>

                   </tr>
                   <tr>

                         <td align="left" width="22%" valign="middle" class="label">Responsibility:</td>
			 <td align="left"  width="27%" valign="middle"><?php echo $reportdetail[0]['AuditRemidial'][$s]['responsibility_person'];?></td>
                         <td align="left" width="23%" valign="middle" class="label">Priority:</td>
			 <td align="left"  width="28%" valign="middle"><?php echo $reportdetail[0]['AuditRemidial'][$s]['Priority']['prority_colorcode'];?></td>

                   </tr>
                   <tr>
                         <td align="left" width="22%" valign="middle" class="label">Summary:</td>
			 <td align="left"  width="78%" colspan="3" valign="middle"><?php echo $reportdetail[0]['AuditRemidial'][$s]['remidial_summery'];?></td>
                        
                  </tr> 
                  <tr>
                         <td align="left" width="22%" valign="middle" class="label">Action Item:</td>
			 <td align="left"  width="78%" colspan="3" valign="middle"><?php echo $reportdetail[0]['AuditRemidial'][$s]['remidial_action'];?></td>
                        
                  </tr> 
                  <tr>
                         <td align="left" width="22%" valign="middle" class="label">Closure Summary:</td>
			 <td align="left"  width="78%" colspan="3" valign="middle"><?php echo $reportdetail[0]['AuditRemidial'][$s]['remidial_closer_summary'];?></td>
                        
                  </tr>
                   <tr>

                         <td align="left" width="22%" valign="middle" class="label">Reminder Date:</td>
			 <td align="left"  width="27%" valign="middle"><?php echo $reportdetail[0]['AuditRemidial'][$s]['rem_rim_data'];?></td>
                         <td align="left" width="23%" valign="middle" class="label">Closure Date:</td>
			 <td align="left"  width="28%" valign="middle" class="label"><?php echo $reportdetail[0]['AuditRemidial'][$s]['closeDate'];?></td>

                   </tr>
       <?php   }   ?>
       </table>
   
      <?php  }   
     
       if(isset($reportdetail[0]['AuditAttachment'][0])){?>

           <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">

                <tr><td colspan="2" align="center" valign="middle" class="titles">Audit Upload and Attachments</td></tr>
                <tr><td align="left" width="22%" valign="middle" class="label">Description</td><td align="left" width="78%" valign="middle" class="label">File Name</td></tr>    
                   <?php    for($k=0;$k<count($reportdetail[0]['AuditAttachment']);$k++){ ?>

                          <tr>

                                  <td align="left" width="22%" valign="middle" ><?php echo $reportdetail[0]['AuditAttachment'][$k]['description'];?> </td>
				  <td align="left"  width="78%" valign="middle">	    <?php  
				    
				    $file_type_array = array ('pdf', 'xlsx', 'xls','doc','xlsm');
		                    $filetype=explode('.',$reportdetail[0]['AuditAttachment'][$k]['file_name']);
				    if(in_array($filetype[1],$file_type_array)){
					    $fileSRC='<a href='.$this->webroot.'img/file_upload/'.$reportdetail[0]['AuditAttachment'][$k]['file_name'].'>'.$reportdetail[0]['AuditAttachment'][$k]['file_name'].'</a>';
				    
				    }else{
					  
					   $fileSRC='<a href='.$this->webroot.'resize.php?src=img/file_upload/'.$reportdetail[0]['AuditAttachment'][$k]['file_name'].'&w=300&h=300 target="_blank">'.$reportdetail[0]['AuditAttachment'][$k]['file_name'].'</a>';
					  
					 
				    }
						     
			          echo  $fileSRC ; ?>	</td>

                          </tr>

                 
                       <?php } ?>
           </table>

       <?php 
          
          }
    
    ?>
       
    </div>
  
    <?php
    //echo '<pre>';
   /// print_r($reportdetail);
    ?>
</section>   