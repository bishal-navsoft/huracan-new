<aside>
<?php echo $this->Element('left_menu'); ?>
</aside>
<section>
      <?php
            echo $this->Element('sqtab');
    
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
			  url: rootpath+"Sqreports/sqreportmain_close/",
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
			      <td width="28%" align="left" valign="middle" class="titles" style="color:red;border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['SqReportMain']['report_no']; ?></td>
		    </tr>
		    <tr>
			     <td align="left" valign="middle" class="titles">Days Since Event:</td>
			     <td align="left" valign="middle" class="titles" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['SqReportMain']['since_event']; ?></td>
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
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['Incident']['type']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Client:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['Client']['name']; ?></td>
                   </tr>
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Created By:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['AdminMaster']['first_name']." ".$reportdetail[0]['AdminMaster']['last_name']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Well:</td>
                         
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php  if(isset($reportdetail[0]['SqWellData'][0])){echo $reportdetail[0]['SqWellData'][0]['well_name'];}else {} ?></td>
                   </tr>
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Business Unit:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php if(isset($reportdetail[0]['BusinessType'])){echo $reportdetail[0]['BusinessType']['type'];}else{} ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Rig:</td>
                         
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php  if(isset($reportdetail[0]['SqWellData'][0])){echo $rig_name;}else {} ?></td>
                   </tr>
		
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Operating Time:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo  $reportdetail[0]['SqReportMain']['operating_time']; ?>&nbsp;hrs</td>
                           <td align="left" width="23%" valign="middle" class="label">Lost Time:</td>
                         
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $loss_time; ?></td>
                   </tr>
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Field Location:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php if(isset($reportdetail[0]['Fieldlocation'])){echo $reportdetail[0]['Fieldlocation']['type'];}else {} ?> </td>
                           <td align="left" width="23%" valign="middle" class="label">Severity:</td>
                          <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php if(isset($reportdetail[0]['IncidentSeverity'])){echo $reportdetail[0]['IncidentSeverity']['incidentSeverity_color'];}else {} ?></td>
                   </tr>
                    <tr>
                           <td align="left" width="22%" valign="middle" class="label">Country:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"> <?php echo $reportdetail[0]['Country']['name']; ?> </td>
                           <td align="left" width="23%" valign="middle" class="label">Wellsite Rep:</td>
                         
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['SqReportMain']['wellsiterep']; ?></td>
                   </tr>
                    <tr>
                           <td align="left" width="22%" valign="middle" class="label">Incident Location:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php if(isset($reportdetail[0]['Incident_location']['id'])){echo $reportdetail[0]['Incident_location']['type'];}else {} ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Client NCR:</td>
                         
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['SqReportMain']['clientncr']; ?></td>
                   </tr>
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Field Ticket:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['SqReportMain']['field_ticket']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Client Reviewed:</td>
                         
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['SqReportMain']['clientreviewed_value']; ?></td>
                   </tr>
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Reporter:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['SqReportMain']['reporter_name']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Client Reviewer:</td>
                         
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php if(isset($reportdetail[0]['SqReportMain']['clientreviewer'])){ echo $reportdetail[0]['SqReportMain']['clientreviewer'];} ?></td>
                   </tr>

         </table>
         <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">
		    <tr>
		          <td colspan="4" align="center" valign="middle" class="titles">Classification</td>
		    </tr>
                    <tr>
                           <td align="left" width="22%" valign="middle" class="label">Potential:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['SqReportMain']['potential_color']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Residual:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['SqReportMain']['residula_color']; ?></td>
                   </tr>
                   <tr>
		            <td align="left" width="22%" valign="middle" class="label">Summary:</td>
		            <td align="left" width="78%" colspan="3" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['SqReportMain']['summary']; ?></td>
                   </tr>
                   <tr>
		            <td align="left" width="22%" valign="middle" class="label">Details:</td>
		            <td align="left" width="78%" colspan="3" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['SqReportMain']['details']; ?></td>
                           
                   </tr>
         </table>
         <?php if(count($reportdetail[0]['SqWellData'])>0){ ?>
         <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">
		    <tr>
		          <td colspan="6" align="center" valign="middle" class="titles">Well Data</td>
		    </tr>
                   <tr>
		           <td align="left" width="22%" valign="middle" class="label">Well Name:</td>
                           <td align="left" width="18%" valign="middle" ><?php echo $reportdetail[0]['SqWellData'][0]['well_name']; ?></td>
                           <td align="left" width="16%" valign="middle" class="label">Rig Name:</td>
                           <td align="left" width="13%" valign="middle" ><?php echo $reportdetail[0]['SqWellData'][0]['rig_name']; ?></td>
                           <td align="left" width="18%" valign="middle" class="label">Fluid:</td>
                           <td align="left" width="23%" valign="middle" ><?php echo $reportdetail[0]['SqWellData'][0]['Welldata']['type']; ?></td>
		   </tr>
                    <tr>
		           <td align="left" width="22%" valign="middle" class="label">Depth:</td>
                           <td align="left" width="18%" valign="middle" ><?php echo $reportdetail[0]['SqWellData'][0]['depth']; ?>&nbsp;m</td>
                           <td align="left" width="16%" valign="middle" class="label">Devi:</td>
                           <td align="left" width="13%" valign="middle" ><?php echo $reportdetail[0]['SqWellData'][0]['devi']; ?>&deg;</td>
                           <td align="left" width="18%" valign="middle" class="label">BHTemp:</td>
                           <td align="left" width="23%" valign="middle" ><?php echo $reportdetail[0]['SqWellData'][0]['bhtemp']; ?>&deg;C</td>
		   </tr>
                   <tr>
		           <td align="left" width="22%" valign="middle" class="label">SHTemp:</td>
                           <td align="left" width="18%" valign="middle" ><?php echo $reportdetail[0]['SqWellData'][0]['shtemp']; ?></td>
                           <td align="left" width="16%" valign="middle" class="label">Density:</td>
                           <td align="left" width="13%" valign="middle" ><?php echo $reportdetail[0]['SqWellData'][0]['density']; ?>&nbsp;ppg</td>
                           <td align="left" width="18%" valign="middle" class="label">WH Pres:</td>
                           <td align="left" width="23%" valign="middle" ><?php echo $reportdetail[0]['SqWellData'][0]['whpres']; ?>&nbsp;psi</td>
		   </tr>
                   <tr>
		           <td align="left" width="22%" valign="middle" class="label">BH Pres:</td>
                           <td align="left" width="18%" valign="middle" ><?php echo $reportdetail[0]['SqWellData'][0]['bhpres']; ?></td>
                           <td align="left" width="16%" valign="middle" class="label">Status:</td>
                           <td align="left" width="13%" valign="middle" ><?php echo $reportdetail[0]['SqWellData'][0]['WellStatus']['type']; ?></td>
                           <td align="left" width="18%" valign="middle" class="label">H₂S:</td>
                           <td align="left" width="23%" valign="middle" ><?php echo $reportdetail[0]['SqWellData'][0]['hts']; ?>&nbsp;ppm</td>
		   </tr>
                   <tr> 
                           <td align="left" width="20%" valign="middle" class="label">CO₂:</td>
                           <td align="left" colspan="5" width="80%" valign="middle" ><?php echo $reportdetail[0]['SqWellData'][0]['cot']; ?>&nbsp;ppm</td>
                   </tr>
              
         </table> 
       <?php }
       if(count($reportdetail[0]['SqReportIncident'])){ 
            for($j=0;$j<count($reportdetail[0]['SqReportIncident']);$j++){

?>
			<table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">
				   <tr>
				           	    
					   <td colspan="4" align="center" valign="middle" class="titles">Incident <?php echo $reportdetail[0]['SqReportIncident'][$j]['incident_no']; ?></td>
				   <tr>
				   <tr>
					   <td align="left" width="22%" valign="middle" class="label">Incident Summary:</td>
				           <td align="left" colspan="3" width="78%" valign="middle" ><?php echo $reportdetail[0]['SqReportIncident'][$j]['incident_summary']; ?></td>
		
				   </tr>
				   <tr>
					   <td align="left" width="22%" valign="middle" class="label">Affected Service:</td>
				           <td align="left"  width="27%" valign="middle" ><?php echo $reportdetail[0]['SqReportIncident'][$j]['SqService']['type']; ?></td>
					   <td align="left" width="23%" valign="middle" class="label">Lost time:</td>
				           <td align="left" width="28%" valign="middle" ><?php echo $reportdetail[0]['SqReportIncident'][$j]['loss_time']; ?></td>
				    </tr>  
				    <tr>
					   <td align="left" width="22%" valign="middle" class="label">Time of incident:</td>
				           <td align="left"  width="27%" valign="middle" ><?php echo $reportdetail[0]['SqReportIncident'][$j]['incident_time']; ?></td>
					   <td align="left" width="23%" valign="middle" class="label">Date:</td>
				           <td align="left" width="28%" valign="middle" >
						   <?php 
						   if($reportdetail[0]['SqReportIncident'][$j]['incident_date']!='0000-00-00'){
							 $incidentdt=explode("-",$reportdetail[0]['SqReportIncident'][$j]['incident_date']);
							 $incdt=$incidentdt[2]."/".$incidentdt[1]."/".$incidentdt[0];
						    }else{
							  $incdt='00/00/0000';	
						    }
						    echo  $incdt;
						    ?>
				           </td>
				  </tr> 
				  <tr>
					    <td align="left" width="22%" valign="middle" class="label">Damage Category:</td>
				            <td align="left"  width="27%" valign="middle" ><?php echo $reportdetail[0]['SqReportIncident'][$j]['SqDamage']['type']; ?></td>
					    <td align="left" width="23%" valign="middle" class="label">Sub Category:</td>
				            <td align="left" width="28%" valign="middle" ><?php echo $reportdetail[0]['SqReportIncident'][$j]['sub_category_name']; ?></td> 
				         
				 </tr>
				 <tr>
					    <td align="left" width="22%" valign="middle" class="label">Incident Severity:</td>
				            <td align="left"  width="27%" valign="middle" ><?php echo $reportdetail[0]['IncidentSeverity']['incidentSeverity_color']; ?></td>
					    <td align="left" width="23%" valign="middle" class="label">Combination:</td>
				            <td align="left" width="28%" valign="middle" ></td> 
				         
				 </tr>   
		   
			</table>
       <?php  } 
          }if(count($reportdetail[0]['SqInvestigation'])>0){?>
       <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">
               <tr>
                           <td colspan="3" align="center" valign="middle" class="titles">Investigation - Team</td>
               </tr>
               <tr>
                            <td align="left" width="22%" valign="middle" class="label">Name:</td>
                            <td align="left"  width="30%" valign="middle" class="label">Seniority</td>
		            <td align="left" width="48%" valign="middle" class="label">Position</td>
               </tr>
               <?php for($i=0;$i<count($reportdetail[0]['SqInvestigation'][0]['name']);$i++){ ?>
                <tr>
                            <td align="left" width="22%" valign="middle" ><?php echo $reportdetail[0]['SqInvestigation'][0]['name'][$i]; ?></td>
                            <td align="left"  width="30%" valign="middle" ><?php echo $reportdetail[0]['SqInvestigation'][0]['seniorty'][$i]; ?></td>
		            <td align="left" width="48%" valign="middle" "><?php echo $reportdetail[0]['SqInvestigation'][0]['roll_name'][$i]; ?></td>
                </tr>  
               <?php } ?>


       </table>
   
		 
          <?php if($reportdetail[0]['SqInvestigation'][0]['people_title']=='' && $reportdetail[0]['SqInvestigation'][0]['position_title']=='' && $reportdetail[0]['SqInvestigation'][0]['parts_title']=='' && $reportdetail[0]['SqInvestigation'][0]['paper_title']==''){
                  }else{?>
		  
       <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">
  
                 <tr>
                           <td colspan="3" align="center" valign="middle" class="titles">Investigation - Data Gathering</td>
            
                 </tr>
			  
		  <?php
		      if($reportdetail[0]['SqInvestigation'][0]['people_title']!=''){ ?>
                
		 <tr>
			      <td align="left" width="22%" valign="middle" rowspan="2" class="label">People:</td>
			      <td class="label1">
			      <span style="color: #660066;">
                              <?php echo $reportdetail[0]['SqInvestigation'][0]['people_title']; ?>&nbsp;:
                              </span>
                              </td>
		 </tr>
		 <tr>
                             <td  width="78%" class="label1">
                                   <?php echo  $reportdetail[0]['SqInvestigation'][0]['people_description']; ?>
                             </td>
                </tr>
	       <?php }
               if($reportdetail[0]['SqInvestigation'][0]['position_title']!=''){ ?>
		 <tr>
			      <td align="left" width="22%" valign="middle" rowspan="2" class="label">Position:</td><td class="label1">               <span style="color: #660066;">
                              <?php echo $reportdetail[0]['SqInvestigation'][0]['position_title']; ?>&nbsp;:
                              </span>
                              </td>
		 </tr>
		 <tr>
                             <td  width="78%" class="label1">
                                   <?php echo  $reportdetail[0]['SqInvestigation'][0]['position_description']; ?>
                             </td>
                </tr>
	       <?php }
               if($reportdetail[0]['SqInvestigation'][0]['parts_title']!=''){ ?>
		 <tr>
			      <td align="left" width="22%" valign="middle" rowspan="2" class="label">Parts:</td><td class="label1">               <span style="color: #660066;">
                              <?php echo $reportdetail[0]['SqInvestigation'][0]['parts_title']; ?>&nbsp;:
                              </span>
                              </td>
		 </tr>
		 <tr>
                             <td  width="78%" class="label1">
                                   <?php echo  $reportdetail[0]['SqInvestigation'][0]['parts_description']; ?>
                             </td>
                </tr>
	       <?php }
                if($reportdetail[0]['SqInvestigation'][0]['paper_title']!=''){ ?>
		 <tr>
			      <td align="left" width="22%" valign="middle" rowspan="2" class="label">Paper:</td><td class="label1">               <span style="color: #660066;">
                              <?php echo $reportdetail[0]['SqInvestigation'][0]['paper_title']; ?>&nbsp;:
                              </span>
                              </td>
		 </tr>
		 <tr>
                             <td  width="78%" class="label1">
                                   <?php echo  $reportdetail[0]['SqInvestigation'][0]['paper_descrption']; ?>
                             </td>
                </tr>
	       <?php   }
	        
	         ?>
                

       </table>
       <?php } }?>
       <?php  if(isset($reportdetail[0]['SqInvestigationData'][0])){
    
	        
                  for($i=0;$i<count($reportdetail[0]['SqInvestigationData']);$i++){
        ?>

		       <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">
		  
				 <tr>
				           <td colspan="2" align="center" valign="middle" class="titles">
				                 Incident-<?php echo $reportdetail[0]['SqInvestigationData'][$i]['incident_no'] ?>  Investigation - Data Analysis  <?php echo $reportdetail[0]['SqInvestigationData'][$i]['investigation_no'] ?>
				            </td>
			    
				 </tr> 
				 <tr>
				            <td align="left" width="22%" valign="middle" class="label">Damage:</td>
				            <td align="left"  width="78%" valign="middle"><?php echo $reportdetail[0]['SqInvestigationData'][$i]['SqReportIncident']['SqDamage']['type']; ?></td>
					   
				 </tr>
				 <tr>
				            <td align="left" width="22%" valign="middle" class="label">Incident:</td>
				            <td align="left"  width="78%" valign="middle"><?php echo $reportdetail[0]['SqInvestigationData'][$i]['SqReportIncident']['incident_summary']; ?></td>
					   
				 </tr>
                                 <tr>
				            <td align="left" width="22%" valign="middle" class="label">IMMEDIATE CAUSE:</td>
				            <td align="left"  width="78%" valign="middle"><?php if(isset($reportdetail[0]['SqInvestigationData'][$i]['imd_cause_name']['0'])){ echo $reportdetail[0]['SqInvestigationData'][$i]['imd_cause_name']['0'];} ?></td>
					   
				 </tr>
                                  <tr>
				            <td align="left" width="22%" valign="middle" class="label">IMMEDIATE SUB-CAUSE:</td>
				            <td align="left"  width="78%" valign="middle"><?php  if(isset($reportdetail[0]['SqInvestigationData'][$i]['imd_sub_couse_name']['0'])){ echo $reportdetail[0]['SqInvestigationData'][$i]['imd_sub_couse_name']['0'];}  ?></td>
					   
				 </tr>
                                
                                <?php
				 if(isset($reportdetail[0]['SqInvestigationData'][$i]['cause_name'])){
                                 if(count($reportdetail[0]['SqInvestigationData'][$i]['cause_name'])>0){
                                                                   for($c=0;$c<count($reportdetail[0]['SqInvestigationData'][$i]['cause_name']);$c++){
                                    if($c==0){         
                                ?>
				                 <tr>

							     <td align="left" width="22%" valign="middle" class="label">RootCause:</td>
							     <td align="left"  width="78%" valign="middle"><?php echo $reportdetail[0]['SqInvestigationData'][$i]['cause_name'][$c]; ?></td>
							   
						 </tr>

                                <?php 
                                       }else{
                                       ?>
                                                <tr>

				                             <td align="left" width="22%" valign="middle" class="label">SUB-RootCause:<?php echo $c; ?></td>
				                             <td align="left"  width="78%" valign="middle"><?php echo $reportdetail[0]['SqInvestigationData'][$i]['cause_name'][$c]; ?></td>
					   
				                </tr>

                                      <?php 
                                       }
                                    }

                                  }
		            }?>
                        
                         <tr>

			          <td align="left" width="22%" valign="middle" class="label">Comments:</td>
				  <td align="left"  width="78%" valign="middle"><?php echo $reportdetail[0]['SqInvestigationData'][$i]['comments']; ?></td>
					   
		        </tr>
                        <?php
              
                          if(isset($reportdetail[0]['SqInvestigationData'][$i]['rem_no'])){
		                  if(count($reportdetail[0]['SqInvestigationData'][$i]['rem_no'])>0){     
		                         for($r=0;$r<count($reportdetail[0]['SqInvestigationData'][$i]['rem_no']);$r++){
		                  
		                 ?> 
		                      <tr>
				                  <td align="left" width="22%" valign="middle" class="label">Remedial Action Item:
		 <?php echo $reportdetail[0]['SqInvestigationData'][$i]['rem_no'][$r];?> </td>
						  <td align="left"  width="78%" valign="middle"> <?php echo $reportdetail[0]['SqInvestigationData'][$i]['rem_summary'][$r];?> </td>
		                    </tr>   
		                <?php } 
		                 } 
                         } ?>
		       </table>  
       <?php     }
            }
        if(isset($reportdetail[0]['SqAttachment'][0])){?>

           <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">

                <tr><td colspan="2" align="center" valign="middle" class="titles">Attachments</td></tr>
                <tr><td align="left" width="22%" valign="middle" class="label">Description</td><td align="left" width="78%" valign="middle" class="label">File Name</td></tr>    
                   <?php    for($k=0;$k<count($reportdetail[0]['SqAttachment']);$k++){ ?>

                          <tr>

                                  <td align="left" width="22%" valign="middle" ><?php echo $reportdetail[0]['SqAttachment'][$k]['description'];?> </td>
				  <td align="left"  width="78%" valign="middle"><?php  
				    
				    $file_type_array = array ('pdf', 'xlsx', 'xls','doc','xlsm');
		                    $filetype=explode('.',$reportdetail[0]['SqAttachment'][$k]['file_name']);
				    if(in_array($filetype[1],$file_type_array)){
					    $fileSRC='<a href='.$this->webroot.'img/file_upload/'.$reportdetail[0]['SqAttachment'][$k]['file_name'].'>'.$reportdetail[0]['SqAttachment'][$k]['file_name'].'</a>';
				    
				    }else{
					  
					  
					   $fileSRC='<a href='.$this->webroot.'resize.php?src=img/file_upload/'.$reportdetail[0]['SqAttachment'][$k]['file_name'].'&w=300&h=300 target="_blank">'.$reportdetail[0]['SqAttachment'][$k]['file_name'].'</a>';
					  
					 
				    }
						     
			          echo  $fileSRC ; ?></td>

                          </tr>

                 
                       <?php } ?>
           </table>

       <?php 
          
          }
	// echo '<pre>';
	// print_r($reportdetail[0]['SqRemidial']);
	  
       if(isset($reportdetail[0]['SqRemidial'][0])){
          ?>
          <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">

           <?php
             for($s=0;$s<count($reportdetail[0]['SqRemidial']);$s++){?>
                             <tr><td colspan="4" align="center" valign="middle" class="titles">Remedial Action Item <?php echo $reportdetail[0]['SqRemidial'][$s]['remedial_no']; ?></td></tr> 
                   <tr>

                         <td align="left" width="22%" valign="middle" class="label">Created On:</td>
			 <td align="left"  width="27%" valign="middle"><?php echo $reportdetail[0]['SqRemidial'][$s]['remidial_create'];?></td>
                         <td align="left" width="23%" valign="middle" class="label">Closure Target:</td>
			 <td align="left"  width="28%" valign="middle"><?php echo $reportdetail[0]['SqRemidial'][$s]['rem_cls_trgt'];?></td>

                   </tr>
                  <tr>

                         <td align="left" width="22%" valign="middle" class="label">Created By:</td>
			 <td align="left"  width="27%" valign="middle"><?php echo $reportdetail[0]['SqRemidial'][$s]['AdminMaster']['first_name'].' '.$reportdetail[0]['SqRemidial'][$s]['AdminMaster']['last_name'];?></td>
                         <td align="left" width="23%" valign="middle" class="label">Last Update:</td>
			 <td align="left"  width="28%" valign="middle"><?php echo $reportdetail[0]['SqRemidial'][$s]['modified'];?></td>

                   </tr>
                   <tr>

                         <td align="left" width="22%" valign="middle" class="label">Responsibility:</td>
			 <td align="left"  width="27%" valign="middle"><?php echo $reportdetail[0]['SqRemidial'][$s]['responsibility_person'];?></td>
                         <td align="left" width="23%" valign="middle" class="label">Priority:</td>
			 <td align="left"  width="28%" valign="middle"><?php echo $reportdetail[0]['SqRemidial'][$s]['Priority']['prority_colorcode'];?></td>

                   </tr>
                   <tr>
                         <td align="left" width="22%" valign="middle" class="label">Summary:</td>
			 <td align="left"  width="78%" colspan="3" valign="middle"><?php echo $reportdetail[0]['SqRemidial'][$s]['remidial_summery'];?></td>
                        
                  </tr> 
                  <tr>
                         <td align="left" width="22%" valign="middle" class="label">Action Item:</td>
			 <td align="left"  width="78%" colspan="3" valign="middle"><?php echo $reportdetail[0]['SqRemidial'][$s]['remidial_action'];?></td>
                        
                  </tr> 
                  <tr>
                         <td align="left" width="22%" valign="middle" class="label">Closure Summary:</td>
			 <td align="left"  width="78%" colspan="3" valign="middle"><?php echo $reportdetail[0]['SqRemidial'][$s]['remidial_closer_summary'];?></td>
                        
                  </tr>
                   <tr>

                         <td align="left" width="22%" valign="middle" class="label">Reminder Date:</td>
			 <td align="left"  width="27%" valign="middle"><?php echo $reportdetail[0]['SqRemidial'][$s]['rem_rim_data'];?></td>
                         <td align="left" width="23%" valign="middle" class="label">Closure Date:</td>
			 <td align="left"  width="28%" valign="middle" class="label"><?php echo $reportdetail[0]['SqRemidial'][$s]['closeDate'];?></td>

                   </tr>

                 
           
       <?php
            } 
        ?>
       </table>
   
      <?php 
 
                       

          }
     if($_SESSION['clientfeed_back']=='yes'){ 
       if(isset($reportdetail[0]['SqClientfeedback'][0])){ 
     ?>
          <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">
		     <tr>
		         <td colspan="4" align="center" valign="middle" class="titles">Client Feedback / Comments</td>  
		     </tr>
		    <tr>

		      <td align="left"  colspan="4" valign="middle"><?php echo $reportdetail[0]['SqClientfeedback'][0]['client_summary'];?></td>
		 
		    </tr>
	   
		    <tr>
				      <td width="22%" align="left" valign="middle" class="label">Client Name:</td>
				      <td width="27%" align="left" valign="middle" ><?php echo $reportdetail[0]['SqReportMain']['clientreviewer']; ?></td>
				      <td width="23%" align="left" valign="middle" class="label">Date:</td>
				      <td width="28%" align="left" valign="middle" >
                                     <?php 
                                      $explode_cls=explode("-",$reportdetail[0]['SqClientfeedback'][0]['close_date']);
                                      $client_feeback_close=date("d-M-y", mktime(0, 0, 0, $explode_cls[1],$explode_cls[2], $explode_cls[0])); 
                                      echo $client_feeback_close;
                                      ?> 
                                
                                      </td>
		   </tr>
          </table>


     <?php }
          }  
      //echo '<pre>';
   // print_r($_SESSION);
    // print_r($reportdetail);
     	//echo '<pre>';
	//print_r($reportdetail[0]['SqRemidial']);

       ?>
       


  </div>
 
</section>
