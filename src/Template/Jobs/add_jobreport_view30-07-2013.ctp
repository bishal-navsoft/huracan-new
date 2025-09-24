<aside>
<?php echo $this->Element('left_menu'); ?>
</aside>
<section>
      <?php
            echo $this->Element('jobtab');
    
     ?>
       <script language="javascript" type="text/javascript">
       $(document).ready(function() {
		$("#main").removeClass("selectedtab");
	        $("#job_data").addClass("selectedtab");
	        $("#gyro_job_data").removeClass("selectedtab");
	        $("#gauge_data").removeClass("selectedtab");
	        $("#action_item").removeClass("selectedtab");
	        $("#attachments").removeClass("selectedtab");
	        $("#link").removeClass("selectedtab");
	        $("#csr").removeClass("selectedtab");
	        $("#view").addClass("selectedtab");
                $("#print").removeClass("selectedtab");
	      
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
    
     
    
  </script>
       
       
       
  <div class="view_sub_contentwrap">
	  <table width="100%" border="0" cellspacing="1" cellpadding="0" class="reporttableHeader">
                <tr>
			      <td width="22%" align="left" valign="middle" class="titles">Report Date:</td>
			      <td width="27%" align="left" valign="middle" class="titles" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JobReportMain']['report_date_val']; ?></td>
			      <td width="23%" align="left" valign="middle" class="titles">Report Number:</td>
			      <td width="28%" align="left" valign="middle" class="titles" style="color:red;border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JobReportMain']['report_no']; ?></td>
		    </tr>
            
          </table>
            <table width="100%" border="0" cellspacing="1" cellpadding="0" class="reporttablewithoutborder">
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Created By:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['AdminMaster']['first_name']." ".$reportdetail[0]['AdminMaster']['last_name']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Client:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['Client']['name']; ?></td>
                   </tr>
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Business Unit:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php if(isset($reportdetail[0]['BusinessType'])){echo $reportdetail[0]['BusinessType']['type'];}else{} ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Well:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JobReportMain']['well']; ?></td>
                   </tr>
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Depart Base:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JobReportMain']['return_deprt_val']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Rig:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JobReportMain']['rig']; ?></td>
                   </tr>
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Start Job:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JobReportMain']['start_job_val']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Wellsite Rep:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JobReportMain']['wellsite_rep']; ?></td>
                   </tr>
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">End Job:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JobReportMain']['end_job_val']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Field Ticket:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JobReportMain']['field_ticket']; ?></td>
                   </tr>
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Return Base:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JobReportMain']['return_base_val'];?></td>
                           <td align="left" width="23%" valign="middle" class="label">Revenue:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JobReportMain']['revenue']; ?></td>
                   </tr>
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Operating Time:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JobReportMain']['operating_time']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Field Location:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['Fieldlocation']['type']; ?></td>
                   </tr>
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Lost Time:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JobReportMain']['loss_time']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Country:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['Country']['name']; ?></td>
                   </tr>
                  
            </table>
             <table width="100%" border="0" cellspacing="1" cellpadding="0" class="reporttableHeader">
                    <tr>
                            <td colspan="2" align="center" valign="middle" class="titles">Job Comments:</td>
                     </tr>
                     <tr>
                            <td align="left" width="22%" valign="middle" class="label">Comments:</td>
                            <td align="left" width="78%" valign="middle" class="label1"><?php echo $reportdetail[0]['JobReportMain']['comment'];?></td>
                     </tr>
             </table>
	     <?php if(count($reportdetail[0]['JobWellData'])>0){ ?>
             <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">
		    <tr>
		          <td colspan="6" align="center" valign="middle" class="titles">Well Data</td>
		    </tr>
                   <tr>
		           <td align="left" width="22%" valign="middle" class="label">Well Name:</td>
                           <td align="left" width="18%" valign="middle" ><?php echo $reportdetail[0]['JobWellData'][0]['well_name']; ?></td>
                           <td align="left" width="16%" valign="middle" class="label">Rig Name:</td>
                           <td align="left" width="13%" valign="middle" >Details:</td>
                           <td align="left" width="18%" valign="middle" class="label">Fluid:</td>
                           <td align="left" width="23%" valign="middle" ><?php if(isset($reportdetail[0]['JobWellData'][0]['Welldata']['type'])){echo $reportdetail[0]['JobWellData'][0]['Welldata']['type'];} ?></td>
		   </tr>
                    <tr>
		           <td align="left" width="22%" valign="middle" class="label">Depth:</td>
                           <td align="left" width="18%" valign="middle" ><?php echo $reportdetail[0]['JobWellData'][0]['depth']; ?>&nbsp;m</td>
                           <td align="left" width="16%" valign="middle" class="label">Devi:</td>
                           <td align="left" width="13%" valign="middle" ><?php echo $reportdetail[0]['JobWellData'][0]['devi']; ?>&deg;</td>
                           <td align="left" width="18%" valign="middle" class="label">BHTemp:</td>
                           <td align="left" width="23%" valign="middle" ><?php echo $reportdetail[0]['JobWellData'][0]['bhtemp']; ?>&deg;C</td>
		   </tr>
                   <tr>
		           <td align="left" width="22%" valign="middle" class="label">SHTemp:</td>
                           <td align="left" width="18%" valign="middle" ><?php echo $reportdetail[0]['JobWellData'][0]['shtemp']; ?></td>
                           <td align="left" width="16%" valign="middle" class="label">Density:</td>
                           <td align="left" width="13%" valign="middle" ><?php echo $reportdetail[0]['JobWellData'][0]['density']; ?>&nbsp;ppg</td>
                           <td align="left" width="18%" valign="middle" class="label">WH Pres:</td>
                           <td align="left" width="23%" valign="middle" ><?php echo $reportdetail[0]['JobWellData'][0]['whpres']; ?>&nbsp;psi</td>
		   </tr>
                   <tr>
		           <td align="left" width="22%" valign="middle" class="label">BH Pres:</td>
                           <td align="left" width="18%" valign="middle" ><?php echo $reportdetail[0]['JobWellData'][0]['bhpres']; ?></td>
                           <td align="left" width="16%" valign="middle" class="label">Status:</td>
                           <td align="left" width="13%" valign="middle" ><?php echo $reportdetail[0]['JobWellData'][0]['WellStatus']['type']; ?></td>
                           <td align="left" width="18%" valign="middle" class="label">H₂S:</td>
                           <td align="left" width="23%" valign="middle" ><?php echo $reportdetail[0]['JobWellData'][0]['hts']; ?>&nbsp;ppm</td>
		   </tr>
                   <tr> 
                           <td align="left" width="20%" valign="middle" class="label">CO₂:</td>
                           <td align="left" colspan="5" width="80%" valign="middle" ><?php echo $reportdetail[0]['JobWellData'][0]['cot']; ?>&nbsp;ppm</td>
                   </tr>
              
         </table> 
       <?php }  
        if(isset($reportdetail[0]['JobRemidial'][0])){
          ?>
          <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">

           <?php
             for($s=0;$s<count($reportdetail[0]['JobRemidial']);$s++){?>
                   <tr><td colspan="4" align="center" valign="middle" class="titles">Follow Up Action Item <?php echo $reportdetail[0]['JobRemidial'][$s]['remedial_no']; ?></td></tr> 
                   <tr>

                         <td align="left" width="22%" valign="middle" class="label">Created On:</td>
			 <td align="left"  width="27%" valign="middle"><?php echo $reportdetail[0]['JobRemidial'][$s]['remidial_create'];?></td>
                         <td align="left" width="23%" valign="middle" class="label">Closure Target:</td>
			 <td align="left"  width="28%" valign="middle"><?php echo $reportdetail[0]['JobRemidial'][$s]['rem_cls_trgt'];?></td>

                   </tr>
                  <tr>

                         <td align="left" width="22%" valign="middle" class="label">Created By:</td>
			 <td align="left"  width="27%" valign="middle"><?php echo $reportdetail[0]['JobRemidial'][$s]['AdminMaster']['first_name'].' '.$reportdetail[0]['JobRemidial'][$s]['AdminMaster']['last_name'];?></td>
                         <td align="left" width="23%" valign="middle" class="label">Last Update:</td>
			 <td align="left"  width="28%" valign="middle"><?php echo $reportdetail[0]['JobRemidial'][$s]['modified'];?></td>

                   </tr>
                   <tr>

                         <td align="left" width="22%" valign="middle" class="label">Responsibility:</td>
			 <td align="left"  width="27%" valign="middle"><?php echo $reportdetail[0]['JobRemidial'][$s]['responsibility_person'];?></td>
                         <td align="left" width="23%" valign="middle" class="label">Priority:</td>
			 <td align="left"  width="28%" valign="middle"><?php echo $reportdetail[0]['JobRemidial'][$s]['Priority']['prority_colorcode'];?></td>

                   </tr>
                   <tr>
                         <td align="left" width="22%" valign="middle" class="label">Summary:</td>
			 <td align="left"  width="78%" colspan="3" valign="middle"><?php echo $reportdetail[0]['JobRemidial'][$s]['remidial_summery'];?></td>
                        
                  </tr> 
                  <tr>
                         <td align="left" width="22%" valign="middle" class="label">Action Item:</td>
			 <td align="left"  width="78%" colspan="3" valign="middle"><?php echo $reportdetail[0]['JobRemidial'][$s]['remidial_action'];?></td>
                        
                  </tr> 
                  <tr>
                         <td align="left" width="22%" valign="middle" class="label">Closure Summary:</td>
			 <td align="left"  width="78%" colspan="3" valign="middle"><?php echo $reportdetail[0]['JobRemidial'][$s]['remidial_closer_summary'];?></td>
                        
                  </tr>
                   <tr>

                         <td align="left" width="22%" valign="middle" class="label">Reminder Date:</td>
			 <td align="left"  width="27%" valign="middle"><?php echo $reportdetail[0]['JobRemidial'][$s]['rem_rim_data'];?></td>
                         <td align="left" width="23%" valign="middle" class="label">Closure Date:</td>
			 <td align="left"  width="28%" valign="middle" class="label"><?php echo $reportdetail[0]['JobRemidial'][$s]['closeDate'];?></td>

                   </tr>
          
                 
           
       <?php
            } 
        ?>
       </table>
   
      <?php 
          }
	   
	    if(isset($reportdetail[0]['GyroJobData'][0]) && $_SESSION['business_type']==1){
          ?>
          <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">

           <?php
             for($s=0;$s<count($reportdetail[0]['GyroJobData']);$s++){?>
                   <tr><td colspan="4" align="center" valign="middle" class="titles">Gyro Job Data <?php echo $reportdetail[0]['GyroJobData'][$s]['gyro_no']; ?></td></tr> 
                   <tr>

                         <td align="left" width="22%" valign="middle" class="label">Gyro SN#:</td>
			 <td align="left"  width="27%" valign="middle"><?php echo $reportdetail[0]['GyroJobData'][$s]['GyroSn']['type'];?></td>
                         <td align="left" width="23%" valign="middle" class="label">Conveyance:</td>
			 <td align="left"  width="28%" valign="middle"><?php echo $reportdetail[0]['GyroJobData'][$s]['Conveyance']['type'];?></td>

                   </tr>
                  <tr>

                         <td align="left" width="22%" valign="middle" class="label">Conveyed By:</td>
			 <td align="left"  width="27%" valign="middle"><?php echo $reportdetail[0]['GyroJobData'][$s]['Conveyed']['type'];?></td>
                         <td align="left" width="23%" valign="middle" class="label">Conveyance Type:</td>
			 <td align="left"  width="28%" valign="middle"><?php echo $reportdetail[0]['GyroJobData'][$s]['ConveyanceType']['type'];?></td>

                   </tr>
                   <tr>

                         <td align="left" width="22%" valign="middle" class="label">Top Survey:</td>
			 <td align="left"  width="27%" valign="middle"><?php echo $reportdetail[0]['GyroJobData'][$s]['top_survey'];?></td>
                         <td align="left" width="23%" valign="middle" class="label">Bottom Survey:</td>
			 <td align="left"  width="28%" valign="middle"><?php echo $reportdetail[0]['GyroJobData'][$s]['buttom_survey'];?></td>

                   </tr>
		    <tr>

                         <td align="left" width="22%" valign="middle" class="label">Latitude:</td>
			 <td align="left"  width="27%" valign="middle"><?php echo $reportdetail[0]['GyroJobData'][$s]['latitude'];?></td>
                         <td align="left" width="23%" valign="middle" class="label">longitude:</td>
			 <td align="left"  width="28%" valign="middle"><?php echo $reportdetail[0]['GyroJobData'][$s]['longitude'];?></td>

                   </tr>
                   <tr>
                         <td align="left" width="22%" valign="middle" class="label">Comments:</td>
			 <td align="left"  width="78%" colspan="3" valign="middle"><?php echo $reportdetail[0]['GyroJobData'][$s]['comments'];?></td>
                        
                  </tr> 
                                   
           
       <?php
            } 
        ?>
       </table>
   
      <?php 
          }
	   if(isset($reportdetail[0]['GaugeData'][0]) && $_SESSION['business_type']==2){
          ?>
          <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">

           <?php
             for($s=0;$s<count($reportdetail[0]['GaugeData']);$s++){?>
                   <tr><td colspan="4" align="center" valign="middle" class="titles">Gauge Data <?php echo $reportdetail[0]['GaugeData'][$s]['gauge_no']; ?></td></tr> 
                   <tr>

                         <td align="left" width="22%" valign="middle" class="label">Gauge SN#:</td>
			 <td align="left"  width="27%" valign="middle"><?php echo $reportdetail[0]['GaugeData'][$s]['gauge_sn'];?></td>
                         <td align="left" width="23%" valign="middle" class="label">Gauge Type:</td>
			 <td align="left"  width="28%" valign="middle"><?php echo $reportdetail[0]['GaugeData'][$s]['GaugeType']['type'];?></td>

                   </tr>
                  <tr>

                         <td align="left" width="22%" valign="middle" class="label">Pres Range:</td>
			 <td align="left"  width="27%" valign="middle"><?php echo $reportdetail[0]['GaugeData'][$s]['PressRange']['type'];?></td>
                         <td align="left" width="23%" valign="middle" class="label">Temp Range:</td>
			 <td align="left"  width="28%" valign="middle"><?php echo $reportdetail[0]['GaugeData'][$s]['TempRange']['type'];?><sup>&deg;</sup>C</td>

                   </tr>
                   <tr>

                         <td align="left" width="22%" valign="middle" class="label">Manufacturer:</td>
			 <td align="left"  width="27%" valign="middle"><?php echo $reportdetail[0]['GaugeData'][$s]['Manufacture']['type'];?></td>
                         <td align="left" width="23%" valign="middle" class="label">Gauge Set Depth:</td>
			 <td align="left"  width="28%" valign="middle"><?php echo $reportdetail[0]['GaugeData'][$s]['gauge_set_depth'];?>m</td>

                   </tr>
		    <tr>

                         <td align="left" width="22%" valign="middle" class="label">TEC Cable:</td>
			 <td align="left"  width="27%" valign="middle"><?php echo $reportdetail[0]['GaugeData'][$s]['TecCable']['type'];?></td>
                         <td align="left" width="23%" valign="middle" class="label">Y-Splitter:</td>
			 <td align="left"  width="28%" valign="middle"><?php echo $reportdetail[0]['GaugeData'][$s]['YSplitter']['type'];?></td>

                   </tr>
		    <tr>

                         <td align="left" width="22%" valign="middle" class="label">WHO Connector:</td>
			 <td align="left"  width="27%" valign="middle"><?php echo $reportdetail[0]['GaugeData'][$s]['WhoConnector']['type'];?></td>
                         <td align="left" width="23%" valign="middle" class="label">SAU:</td>
			 <td align="left"  width="28%" valign="middle"><?php echo $reportdetail[0]['GaugeData'][$s]['Sau']['type'];?></td>

                   </tr>
                   <tr>
                         <td align="left" width="22%" valign="middle" class="label">Comments:</td>
			 <td align="left"  width="78%" colspan="3" valign="middle"><?php echo $reportdetail[0]['GaugeData'][$s]['comments'];?></td>
                        
                  </tr> 
                                   
           
       <?php
            } 
        ?>
       </table>
   
      <?php 
          }
	  if(isset($reportdetail[0]['JobAttachment'][0])){?>

           <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">

                <tr><td colspan="2" align="center" valign="middle" class="titles">Attachments</td></tr>
                <tr><td align="left" width="22%" valign="middle" class="label">Description</td><td align="left" width="78%" valign="middle" class="label">File Name</td></tr>    
                   <?php    for($k=0;$k<count($reportdetail[0]['JobAttachment']);$k++){ ?>

                          <tr>

                                  <td align="left" width="22%" valign="middle" ><?php echo $reportdetail[0]['JobAttachment'][$k]['description'];?> </td>
				  <td align="left"  width="78%" valign="middle">
				    
				    <?php  
				    
				    $file_type_array = array ('pdf', 'xlsx', 'xls','doc','xlsm');
		                    $filetype=explode('.',$reportdetail[0]['JobAttachment'][$k]['file_name']);
				    if(in_array($filetype[1],$file_type_array)){
					    $fileSRC='<a href='.$this->webroot.'img/file_upload/'.$reportdetail[0]['JobAttachment'][$k]['file_name'].'>'.$reportdetail[0]['JobAttachment'][$k]['file_name'].'</a>';
				    
				    }else{
					  
					   $fileSRC='<a href='.$this->webroot.'resize.php?src=img/file_upload/'.$reportdetail[0]['JobAttachment'][$k]['file_name'].'&w=300&h=300 target="_blank">'.$reportdetail[0]['JobAttachment'][$k]['file_name'].'</a>';
					  
					 
				    }
						     
			          echo  $fileSRC ; ?>		    
				    
				    
				    
				   </a>
				  
				  
				  
				  </td>

                          </tr>

                 
                       <?php } ?>
           </table>

       <?php 
          
          }
	 
	 
	  if(count($linkDataHolder)>0){
	    ?>
	    <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">

                <tr><td colspan="2" align="center" valign="middle" class="titles">Link</td></tr>
                <tr><td align="left" width="22%" valign="middle" class="label">Report No</td><td align="left" width="78%" valign="middle" class="label">Summary</td></tr>    
                   <?php    for($k=0;$k<count($linkDataHolder['rep_no']);$k++){ ?>

                          <tr>

                                  <td align="left" width="22%" valign="middle" ><?php echo $linkDataHolder['rep_no'][$k];?> </td>
				  <td align="left"  width="78%" valign="middle"><?php echo $linkDataHolder['rep_summary'][$k];?></td>

                          </tr>

                 
                       <?php } ?>
           </table>

	   <?php 
	       }
	       ?>
	        <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">
		  <tr><td colspan="6" align="center" valign="middle" class="titles">Customer Satisfaction Report</td></tr>
		   <tr>
			 <td align="left" width="40%" valign="middle">&nbsp;</td>
			 <td align="left" width="12%" valign="middle"  class="label">Excellent</td>
			 <td align="left" width="12%" valign="middle"  class="label">Good</td>
			 <td align="left" width="12%" valign="middle"  class="label">Satisfactory</td>
			 <td align="left" width="12%" valign="middle"  class="label">Poor</td>
			 <td align="left" width="12%" valign="middle"  class="label">Unacceptable</td>
		    </tr>
		    <?php

		    for($c=0;$c<count($jsl);$c++){
			
			 
			 ?>
		     <tr>
			 <td align="left" width="40%" valign="middle"><?php echo $jsl[$c]['JobCustomerFeedbackElement']['type']; ?></td>
			 <td align="left" width="12%" valign="middle"><?php if(in_array($jsl[$c]['JobCustomerFeedbackElement']['id'].'_E',$explode_name) && in_array($jsl[$c]['JobCustomerFeedbackElement']['E'],$explode_value)){ echo "X";} ?></td>
			 <td align="left" width="12%" valign="middle"><?php if(in_array($jsl[$c]['JobCustomerFeedbackElement']['id'].'_G',$explode_name) && in_array($jsl[$c]['JobCustomerFeedbackElement']['G'],$explode_value)){ echo "X";} ?></td>
			 <td align="left" width="12%" valign="middle"><?php if(in_array($jsl[$c]['JobCustomerFeedbackElement']['id'].'_S',$explode_name) && in_array($jsl[$c]['JobCustomerFeedbackElement']['S'],$explode_value)){ echo "X";} ?></td>
			 <td align="left" width="12%" valign="middle"><?php if(in_array($jsl[$c]['JobCustomerFeedbackElement']['id'].'_P',$explode_name) && in_array($jsl[$c]['JobCustomerFeedbackElement']['P'],$explode_value)){ echo "X";} ?></td>
			 <td align="left" width="12%" valign="middle"><?php if(in_array($jsl[$c]['JobCustomerFeedbackElement']['id'].'_U',$explode_name) && in_array($jsl[$c]['JobCustomerFeedbackElement']['U'],$explode_value)){ echo "X";} ?></td>
		    </tr>
		    
			 
		   <?php  }    ?>
		    <tr>
			 <td align="right" colspan="5" width="90%" valign="middle" >Total</td>
			 <td align="left" width="10%" valign="middle"  class="label" ><?php  if(isset($reportdetail[0]['JobCustomerFeedback'][0]['total_val'])){ echo $reportdetail[0]['JobCustomerFeedback'][0]['total_val']; }else{} ?></td>
			
		    </tr>
		      <tr>
			 <td align="left"  width="50%" valign="top" >Comment</td>
			 <td align="left" width="50%" colspan="5" valign="middle"><?php  if(isset($reportdetail[0]['JobCustomerFeedback'][0]['comments'])){ echo $reportdetail[0]['JobCustomerFeedback'][0]['comments']; }else{} ?></td>
			 
		    </tr>
		</table>
	       
<?php
       
       echo '<pre>';
       print_r($reportdetail[0]['JobReportMain']);
       
       ?>
  </div>
  
  
  </section>