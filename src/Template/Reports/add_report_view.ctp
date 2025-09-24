<aside>
<?php echo $this->Element('left_menu'); ?>
</aside>
<section>
     <?php
            echo $this->Element('hssetab');
    
     ?>
      
  <script language="javascript" type="text/javascript">
       
     $(document).ready(function() {
	   $("#main").removeClass("selectedtab");
	   $("#clientdata").removeClass("selectedtab");
	   $("#personnel").removeClass("selectedtab");
	   $("#incident").removeClass("selectedtab");
	   $("#investigation").removeClass("selectedtab");
	   $("#remidialaction").removeClass("selectedtab");
	   $("#attachment").removeClass("selectedtab");
	   $("#clientfeedback").removeClass("selectedtab");
	   $("#view").addClass("selectedtab");
	 
      });
    
    
    function close_reopen(type){
           
                    var rootpath="<?php echo $this->webroot; ?>";
                    $.ajax({
			  type: "POST",
			  url: rootpath+"Reports/main_close/",
			  data:"data=a&report_id=<?php echo $id; ?>&type="+type,
			  success: function(res)
			  {
		       	    var resval=res.split("~");
			    
			      if(resval[0]=='close'){
				   document.getElementById('<?php echo $id; ?>').innerHTML=resval[1]+' <input type="button" name="save" id="save" class="buttonview" onclick="close_reopen(\'reopen\');"  value="Reopen" />';  
	                      }else if(resval[0]=='reopen'){
				   document.getElementById('<?php echo $id; ?>').innerHTML='<input type="button" name="save" id="save" class="buttonview" onclick="close_reopen(\'close\');"  value="Close" />';  
			      }
	                     
				 
                          }
		 
	           });
	

	return false;
     
    }
    
    
    function download_file(filename){
     alert("<?php echo $this->webroot; ?>img/file_upload/"+filename);
     //document.location="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$this->webroot; ?>app/webroot/img/file_upload/"+filename;
     document.location="<?php echo $this->webroot; ?>img/file_upload/"+filename;   

    }
     
    
  </script>

<div class="view_sub_contentwrap">
  <table width="100%" border="0" cellspacing="1" cellpadding="0" class="reporttableHeader">
    <tr>
      <td width="22%" align="left" valign="middle" class="titles">Event Date:</td>
      <td width="27%" align="left" valign="middle" class="titles" style="border-right: 2px solid #C9A5E4;"><?php echo $event_date; ?></td>
      <td width="23%" align="left" valign="middle" class="titles">Report Number:</td>
      <td width="28%" align="left" valign="middle" class="titles" style="color:red;border-right: 2px solid #C9A5E4;""><?php echo $reportno; ?></td>
    </tr>

    <tr>
      <td align="left" valign="middle" class="titles">Days Since Event:</td>
      <td align="left" valign="middle" class="titles" style="border-right: 2px solid #C9A5E4;"><?php echo $since_event; ?></td>
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
      <td align="left" width="22%" valign="middle" class="label">Incident Type:</td>
      <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $incident_type; ?></td>
      <td align="left" width="23%" valign="middle" class="label">Created By:</td>
      <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $created_by; ?></td>
      </tr>
    <tr>
      <td align="left" valign="middle" class="label">Business Unit:</td>
      <td align="left" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $business_unit; ?></td>
      <td align="left" valign="middle" class="label">Client:</td>
      <td align="left" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $client; ?></td>
      </tr>
    <tr>
      <td align="left" valign="middle" class="label">Field Location:</td>
      <td align="left" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $fieldlocation; ?></td>
      <td align="left" valign="middle" class="label">Incident Location:</td>
      <td align="left" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $incidentLocation; ?></td>
      </tr>
    <tr>
      <td align="left" valign="middle" class="label" style="border-bottom:2px solid #C9A5E4">Country:</td>
      <td align="left" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;border-bottom:2px solid #C9A5E4""><?php echo $countrty; ?></td>
      <td align="left" valign="middle" class="label" style="border-bottom:2px solid #C9A5E4;">Reporter:</td>
      <td align="left" valign="middle" class="label1" style="border-bottom:2px solid #C9A5E4;border-right: 2px solid #C9A5E4;""><?php echo $reporter; ?></td>
      </tr>
  </table>
  <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">
    <tr>
      <td colspan="4" align="center" valign="middle" class="titles">Classification</td>
      </tr>
    <tr>
      <td width="22%" align="left" valign="middle" class="titles">Incident Severity:</td>
      <td width="27%" align="left" valign="middle" class="titles" <?php echo $incidentseveritydetailcolor; ?>><?php echo $incidentseveritydetail; ?></td>
      <td width="23%" align="left" valign="middle" class="titles" >Recordable:</td>
      <td width="28%" align="left" valign="middle" class="titles" <?php echo $recorablecolor; ?>><?php echo $recorable; ?></td>
    </tr>
    <tr>
      <td align="left" valign="middle" class="titles">Potential:</td>
      <td align="left" valign="middle" class="titles" <?php echo $potentialcolor; ?>><?php  echo $potential;?></td>
      <td align="left" valign="middle" class="titles">Residual:</td>
      <td align="left" valign="middle" class="titles" <?php echo $residualcolor; ?>><?php echo $residual; ?></td>
    </tr>
    <tr>
      <td align="left" valign="middle" class="label">Summary:</td>
      <td colspan="3" align="left" valign="middle" class="label1"><?php echo $summary; ?></td>
      </tr>
    <tr>
      <td align="left" valign="middle" class="label">Details:</td>
      <td colspan="3" align="left" valign="middle" class="label1"><?php echo $details ?></td>
      </tr>
  </table>
  <?php  if($clienttab!=0){    ?>
 
  <table width="100%" border="0" cellspacing="1" cellpadding="0" class="reporttablewithoutborder">
    <tr>
      <td colspan="4" align="center" valign="middle" class="titles" style="border-right:2px solid #C9A5E4">Client - <?php echo $client; ?></td>
      </tr>
    <tr>
      <td align="left" width="22%" valign="middle" class="label">Well:</td>
      <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $well; ?></td>
      <td align="left" width="23%" valign="middle" class="label">Client NCR:</td>
      <td align="left" width="28%" valign="middle" class="label1" style="border-right:2px solid #C9A5E4"><?php echo $clientncr; ?></td>
      </tr>
    <tr>
      <td align="left" valign="middle" class="label">Rig:</td>
      <td align="left" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $rig; ?></td>
      <td align="left" valign="middle" class="label">Client Reviewed:</td>
      <td align="left" valign="middle" class="label1" style="border-right:2px solid #C9A5E4"><?php echo $clientreviewed; ?></td>
      </tr>
    <tr>
      <td align="left" valign="middle" class="label" style="border-bottom:2px solid #C9A5E4">Wellsite Rep:</td>
      <td align="left" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;border-bottom:2px solid #C9A5E4"><?php echo $wellsiterep; ?></td>
      <td align="left" valign="middle" class="label" style="border-bottom:2px solid #C9A5E4">Client Reviewer:</td>
      <td align="left" valign="middle" class="label1" style="border-bottom:2px solid #C9A5E4;border-right:2px solid #C9A5E4""><?php echo $clientreviewer; ?></td>
      </tr>
  
  </table>

   <?php
  }
   if($personeldata==1){

       
	  ?>

	       <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">
		 <tr>
		   <td colspan="5" align="center" valign="middle" class="titles">Incident - Personnel</td>
		   </tr>
		 <tr>
		   <td align="left" width="22%" valign="middle" class="label">Name:</td>
		   <td align="left" width="27%" valign="middle" class="label">Seniority</td>
		   <td align="left" width="23%" valign="middle" class="label">Position</td>
		   <td align="left" width="14%" valign="middle" class="label">Hrs Last Sleep</td>
		   <td align="left" width="20%" valign="middle" class="label">Hrs since Sleep</td>
		   </tr>
		 <?php
	 
		 
		   for($i=0;$i<count($personeldetail);$i++){
		    
		    ?>
		        
		       <tr>
			      <td align="left" valign="middle" class="label"><?php echo $personeldetail[$i]['HssePersonnel']['name']; ?></td>
			      <td align="left" valign="middle" class="label1"><?php echo $personeldetail[$i]['HssePersonnel']['seniorty']; ?></td>
			      <td align="left" valign="middle" class="label1"><?php echo $personeldetail[$i]['HssePersonnel']['position']; ?></td>
			      <td align="left" valign="middle" class="label1"><?php echo $personeldetail[$i]['HssePersonnel']['last_sleep']; ?></td>
			      <td align="left" valign="middle" class="label1"><?php echo $personeldetail[$i]['HssePersonnel']['since_sleep']; ?></td>
		        </tr>
		    
		   <?php }
		 ?>
		 
		</table>

	  
	<?php  }else{
	  
	 }
      

      
     if(count($incidentdetailHolder)>0){
	  

	  
	  
	  for($i=0;$i<count($incidentdetailHolder);$i++){
	    
	         
	 ?>
        

       <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">
	 <tr>
	   <td colspan="4" align="center" valign="middle" class="titles">Incident   <?php echo $i+1; ?> </td>
	   </tr>
	 <tr>
	   <td width="22%" align="left" valign="middle" class="label">Time of incident:</td>
	   <td width="27%" align="left" valign="middle" class="label1"><?php echo $incidentdetailHolder[$i]['incident_time']; ?></td>
	   <td width="23%" align="left" valign="middle" class="label">Date of Incident:</td>
	   <td width="28%" align="left" valign="middle" class="label1"><?php echo $incidentdetailHolder[$i]['date_incident']; ?></td>
	 </tr>
	 <tr>
	   <td align="left" valign="middle" class="label">Incident Severity:</td>
	   <td align="left" valign="middle" class="label1"><?php echo $incidentdetailHolder[$i]['incident_severity']; ?></td>
	   <td align="left" valign="middle" class="label">Loss:</td>
	   <td align="left" valign="middle" class="label1"><?php echo $incidentdetailHolder[$i]['incident_loss']; ?></td>
	 </tr>
	 <tr>
	   <td align="left" valign="middle" class="label">Category:</td>
	   <td align="left" valign="middle" class="label1"><?php echo $incidentdetailHolder[$i]['incident_category']; ?></td>
	   <td align="left" valign="middle" class="label">Sub-Category:</td>
	   <td align="left" valign="middle" class="label1"><?php echo $incidentdetailHolder[$i]['incident_sub_category']; ?></td>
	 </tr>
	 <tr>
	   <td align="left" valign="middle" class="label">Incident Summary:</td>
	   <td colspan="3" align="left" valign="middle" class="label1"><?php echo $incidentdetailHolder[$i]['incident_summary']; ?></td>
	   </tr>
	 <tr>
	   <td align="left" valign="middle" class="label">Details:</td>
	   <td colspan="3" align="left" valign="middle" class="label1"><?php echo $incidentdetailHolder[$i]['detail']; ?></td>
	   </tr>
       </table>

       <?php
       

    
	
	  if($incidentdetailHolder[$i]['isdeleted']=='N' && $incidentdetailHolder[$i]['isblocked']=='N'){
	       if($incidentdetailHolder[$i]['view'][0]=='yes'){
		    
		    for($v=0;$v<count($incidentdetailHolder[$i]['investigation_no']);$v++){?>
		    
		          <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">
			      <tr>
				    <td colspan="4" align="center" valign="middle" class="titles">Incident <?php echo $incidentdetailHolder[$i]['incident_no_investigation'] ?>-Investigation <?php echo $incidentdetailHolder[$i]['investigation_no'][$v]; ?></td>
			      </tr>
			       <tr>
		                    <td width="22%" class="label">LOSS</td>
			            <td width="78%" class="label1"><?php echo $incidentdetailHolder[$i]['incident_loss'];?></td>
	                       </tr>
			        <?php if(isset($incidentdetailHolder[$i]['imd_cause'][$v])){ ?>  
				   <tr>
					<td width="22%" class="label">IMMEDIATE CAUSE </td>
					<td width="78%" class="label1"><?php echo $incidentdetailHolder[$i]['imd_cause'][$v];?></td>
				   </tr>
		                <?php }
				if(isset($incidentdetailHolder[$i]['imd_sub_cause'][$v])){ ?>
				   <tr>
					<td width="22%" class="label">IMMEDIATE-SUB CAUSE </td>
					<td width="78%" class="label1"><?php echo $incidentdetailHolder[$i]['imd_sub_cause'][$v];?></td>
				   </tr>
			         <?php } 
				if(isset($incidentdetailHolder[$i]['root_cause_val'][$v])){
		                        for($r=0;$r<count($incidentdetailHolder[$i]['root_cause_val'][$v]);$r++){?>
				   <tr>
				        <?php if($r==0){ ?>
					<td width="22%" class="label">ROOT CAUSE </td>
					<?php }else{ ?>
					 <td width="22%" class="label">SUB-ROOT CAUSE  <?php echo $r; ?></td>
					<?php } ?>		 
					 <td width="78%" class="label1"><?php echo $incidentdetailHolder[$i]['root_cause_val'][$v][$r];?></td>
				   </tr>
		                <?php 
		                     }
		                   }
				  if(isset($incidentdetailHolder[$i]['comment'])){?>
				   <tr>
					 <td width="22%" class="label">COMMENTS: </td>
					 <td width="78%" class="label1"><?php echo $incidentdetailHolder[$i]['comment']; ?></td>
				     </tr>
		            <?php } 
			           if(isset($incidentdetailHolder[$i]['rem_val'][$v])){
					for($r=0;$r<count($incidentdetailHolder[$i]['rem_val'][$v]);$r++){
					   ?>
					  <tr>
					   <td width="22%" class="label"><?php echo 'REM-'.$incidentdetailHolder[$i]['rem_val_id'][$v][$r]; ?> </td>
					   <td width="78%" class="label1"><?php echo $incidentdetailHolder[$i]['rem_val'][$v][$r]; ?> </td>
					  </tr> 
					   
					   <?php
					   
				      }
			 
		                  }
				  ?>
			  </table>
		           

			 
			 
		    <?php }
		    
	       }
          
	  ?>


	
     <?php }}} 
    
     if(count($investigation_team)>0){
	  ?>
	  

       <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">
	   <tr>
	   <td colspan="4" align="center" valign="middle" class="titles">Investigation - Team</td>
	   </tr>
	   <tr>
		   <td align="left" width="08%" valign="middle" class="label" colspan="2">Name:</td>
		   <td align="left" width="27%" valign="middle" class="label">Seniority</td>
		   <td align="left" width="23%" valign="middle" class="label">Position</td>
		   
		   
	   </tr>

	 <?php
	      for($i=0;$i<count($investigation_team);$i++){
	           $seniorty=explode(" ",$investigation_team[0]['AdminMaster']['created']);	
		   $snr=explode("-",  $seniorty[0]);
		   $snrdt=$snr[2]."/".$snr[1]."/".$snr[0];
	       ?>
	       
	        <tr>
		   <td align="left" width="14%" valign="middle" class="label1"  colspan="2"><?php echo $investigation_team[$i]['AdminMaster']['first_name']." ".$investigation_team[$i]['AdminMaster']['last_name']; ?></td>
		   <td align="left" width="27%" valign="middle" class="label1"><?php echo $snrdt; ?></td>
		   <td align="left" width="23%" valign="middle" class="label1" ><?php echo $investigation_team[$i]['RoleMaster']['role_name'] ;?></td>
		 </tr>
	        
	      
	  <?php
	      }
	    ?>

      

       </table>

  <?php if($invetigationDetail[0]['HsseInvestigation']['people_title']!=''|| $invetigationDetail[0]['HsseInvestigation']['people_description']!='' || $invetigationDetail[0]['HsseInvestigation']['paper_title']!='' ||$invetigationDetail[0]['HsseInvestigation']['paper_descrption']!=''){ ?>	  

    
       <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">
	   <tr>
	   <td colspan="4" align="center" valign="middle" class="titles">Investigation - Data Gathering</td>
	   </tr>
	   <?php if($invetigationDetail[0]['HsseInvestigation']['people_title']!=''){ ?>
	   <tr>
	       <td align="left" width="22%" valign="middle" rowspan="2" class="label">People:</td><td class="label1"><span style="color: #660066;"><?php echo  $invetigationDetail[0]['HsseInvestigation']['people_title']; ?>&nbsp;:</span></td>
	   </tr>
	    <tr><td  width="78%" class="label1"><?php echo  $invetigationDetail[0]['HsseInvestigation']['people_description']; ?>.</td></tr>
	     <?php }
	     if($invetigationDetail[0]['HsseInvestigation']['paper_title']!=''){ ?>
	   <tr>
	       <td align="left" valign="middle" rowspan="2" class="label">Paper:</td><td><span style="color: #660066;"><?php echo  $invetigationDetail[0]['HsseInvestigation']['paper_title']; ?>&nbsp;:</span></td>
	   </tr>
	   <tr><td class="label1"><?php echo  $invetigationDetail[0]['HsseInvestigation']['paper_descrption']; ?></td></tr>
	   <?php  }
	   
	    if($invetigationDetail[0]['HsseInvestigation']['position_title']!=''){ ?>
	   <tr>
	       <td align="left" valign="middle" rowspan="2" class="label">Position:</td><td class="label1"><span style="color: #660066;"><?php echo  $invetigationDetail[0]['HsseInvestigation']['position_title']; ?>&nbsp;:</span></td>
	   </tr>
	   <tr><td class="label1"><?php echo  $invetigationDetail[0]['HsseInvestigation']['position_description']; ?></td></tr>
	   <?php  }
	   
	   if($invetigationDetail[0]['HsseInvestigation']['parts_title']!=''){ ?>
	   <tr>
	       <td align="left" valign="middle" rowspan="2" class="label">Parts:</td><td class="label1"><span style="color: #660066;"><?php echo  $invetigationDetail[0]['HsseInvestigation']['parts_title']; ?>&nbsp;:</span></td>
	   </tr>
	   <tr><td class="label1"><?php echo  $invetigationDetail[0]['HsseInvestigation']['parts_description']; ?></td></tr>
	   <?php  }
	   
	   
	   ?>
       </table>

      <?php  }}
      
      
      if(count($remidialdetail)>0){
	  
         for($i=0;$i<count($remidialdetail);$i++){
	         
	 ?>
        

       <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">
	 <tr>
	   <td colspan="4" align="center" valign="middle" class="titles">Remedial Action Item  <?php echo $remidialdetail[$i]['HsseRemidial']['id']; ?></td>
	   </tr>
	 <tr>
	   <td width="22%" align="left" valign="middle" class="label">Created On:</td>
	   <td width="27%" align="left" valign="middle" class="label1"><?php echo $remidialdetail[$i]['HsseRemidial']['createRemidial']; ?></td>
	   <td width="23%" align="left" valign="middle" class="label">Closure Target:</td>
	   <td width="28%" align="left" valign="middle" class="label1"><?php echo $remidialdetail[$i]['HsseRemidial']['remidial_closure_target']; ?></td>
	 </tr>
	 <tr>
	   <td align="left" valign="middle" class="label">Created By:</td>
	   <td align="left" valign="middle" class="label1"><?php echo $remidialdetail[$i]['HsseRemidial']['remidial_createby']; ?></td>
	   <td align="left" valign="middle" class="label">Last Update:</td>
	   <td align="left" valign="middle" class="label1"><?php echo $remidialdetail[$i]['HsseRemidial']['lastupdate']; ?></td>
	 </tr>
	 <tr>
	   <td align="left" valign="middle" class="label">Responsibility:</td>
	   <td align="left" valign="middle" class="label1"><?php echo $remidialdetail[$i]['HsseRemidial']['remidial_responsibility']; ?></td>
	   <td align="left" valign="middle" class="label">Priority:</td>
	   <td align="left" valign="middle" class="label1" <?php  echo $remidialdetail[$i]['HsseRemidial']['priority_color']; ?>><?php echo $remidialdetail[$i]['HsseRemidial']['priority']; ?></td>
	 </tr>
	 <tr>
	   <td align="left" valign="middle" class="label">Summary:</td>
	   <td colspan="3" align="left" valign="middle" class="label1"><?php echo $remidialdetail[$i]['HsseRemidial']['remidial_summery']; ?></td>
	   </tr>
	 <tr>
	   <td align="left" valign="middle" class="label">Action Item:</td>
	   <td colspan="3" align="left" valign="middle" class="label1"><?php echo $remidialdetail[$i]['HsseRemidial']['remidial_action']; ?></td>
	   </tr>
	 <tr>
	   <td align="left" valign="middle" class="label">Closure Summary:</td>
	   <td colspan="3" align="left" valign="middle" class="label1"><?php echo $remidialdetail[$i]['HsseRemidial']['remidial_closer_summary']; ?></td>
	  </tr>
	 <tr>
	   <td align="left" valign="middle" class="label">Reminder Date:</td>
	   <td align="left" valign="middle" class="label1"><?php echo $remidialdetail[$i]['HsseRemidial']['remidial_reminder_data']; ?></td>
	   <td align="left" valign="middle" class="label">Closure Date:</td>
	   <td align="left" valign="middle" class="label1"><?php echo $remidialdetail[$i]['HsseRemidial']['closeDate']; ?></td>
	 </tr>
       </table>

	  
	
     <?php } }  if($attachmentTab!=0){ ?>
     

	        <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">
		    <tr>
                     <td colspan="2" align="center" valign="middle" class="titles" >Attachments</td>
                       
                   </tr>
		    <tr>
                        <td align="left" width="22%" valign="middle" class="label">Description:</td>
                        <td align="left" width="78%" valign="middle" class="label">File Name</td>
                   </tr>
		    <?php for($i=0;$i<count($attachmentData);$i++){?>
                   <tr>
                        <td align="left" valign="middle" class="label1"><?php echo $attachmentData[$i]['HsseAttachment']['description']; ?></td>
                        <td align="left" valign="middle" class="label1">
			 
			 
			 <?php  
				    
				    $file_type_array = array ('pdf', 'xlsx', 'xls','doc','xlsm');
		                    $filetype=explode('.',$attachmentData[$i]['HsseAttachment']['file_name']);
				    if(in_array($filetype[1],$file_type_array)){
					    $fileSRC='<a href='.$this->webroot.'img/file_upload/'.$attachmentData[$i]['HsseAttachment']['file_name'].'>'.$attachmentData[$i]['HsseAttachment']['file_name'].'</a>';
				    
				    }else{
					  
					  
					   $fileSRC='<a href='.$this->webroot.'resize.php?src=img/file_upload/'.$attachmentData[$i]['HsseAttachment']['file_name'].'&w=300&h=300 target="_blank">'.$attachmentData[$i]['HsseAttachment']['file_name'].'</a>';
					  
					 
				    }
						     
			          echo  $fileSRC ; ?>
			</td>
		      
                  </tr>

                   <?php }?>
		</table>
	       
    
      <?php }if($client_feedback==1){?>

	   <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">
		    <tr>
                      <td colspan="4" align="center" valign="middle" class="titles" >Client Feedback / Comments</td>
                    </tr>
		    <tr>
                      <td colspan="4" align="left"class="label1"><?php echo $clientfeedback_summary; ?></td>
                    </tr>
		    <tr>
                      <td width="22%" class="label">Client Name</td>
		      <td width="26%" class="label1"><?php echo $clientreviewer; ?></td>
		      <td width="22%" class="label">Date</td>
		      <td width="26%" class="label1"><?php echo $feedback_date; ?></td>
                    </tr>
	   </table>		    

      <?php }
      

?>   

</section>
