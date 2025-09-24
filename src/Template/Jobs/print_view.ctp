<section class="adminwrap" id="printdiv">
    
<script language="javascript" type="text/javascript">
            

    function Clickheretoprint()
{
    
      document.getElementById('print_button_area').style.display="none";     
      var disp_setting="toolbar=yes,location=no,directories=yes,menubar=yes,"; 
     disp_setting+="scrollbars=yes,width=830, height=600, left=100, top=25"; 
     //jQuery('#checkout-review-submit').hide();
  var content_vlue = document.getElementById("printdiv").innerHTML; 
 var docprint=window.open("","",disp_setting); 
   docprint.document.open();
    
  docprint.document.write('<html xmlns="http://www.w3.org/1999/xhtml">');
  docprint.document.write('<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title></title></head>');
   docprint.document.write('<style type="text/css">@charset "utf-8";section.adminwrap{ float:none; background:#f2e3ff; position:relative; width:auto; padding:10px; z-index:1; /*min-height:575px*/height: auto; min-height: 487px;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px; behavior: url(PIE.htc);}section.adminwrap h2.headingtop{ font-size:25px; color:#fff; background:#4a0585; position:relative; display:block;border-bottom:none !important; padding:5px; margin:0 0 5px 0;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px; behavior: url(PIE.htc);}*+html section.adminwrap h2.headingtop{ font-weight:normal;}.admintable_wrap{ float:left; width:83.5%; background:#fff; position:relative; border:1px solid #7b07de;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px; behavior: url(PIE.htc);}.admintable_wrap input[type=checkbox]{ float:none;}.admintable_wrap table tr:hover{ background:#f9f2ff;}.admintable_wrap table tr td.topcell{ background:#e6e6e6; padding:6px; font-size:14px; color:#7b07de; border-bottom:1px solid #c584ff;}.admintable_wrap table tr.sunheadingbg{ background:#f1f1f1;}.admintable_wrap table tr td.subcellheading{ padding:5px; font-size:13px; color:#7b07de; font-weight:bold;}.admintable_wrap table tr td.cellinfo{ padding:5px; font-size:13px; color:#444; padding:5px 5px 5px 20px;}.admintable_wrap table tr td input[type=checkbox]{ float:none;}.view_sub_contentwrap{clear:both; padding:0 0 0 0;border: 2px solid #c9a5e4;margin-top: 10px;}table.viewreporttable{ background:#ccc;}table.viewreporttable tr td.titles{ background:#d8beeb; padding:4px; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; font-weight:bold;}table.viewreporttable tr td{ background:#fff; padding:4px; font-size:12px; color:#666;border-bottom: 2px solid #C9A5E4;border-right: 2px solid #C9A5E4;}table.viewreporttable tr td.label{ font-size:12px; color:#333; font-weight:bold;border-bottom: 2px solid #C9A5E4;border-right: 2px solid #C9A5E4;} table.viewreporttable tr td.label1{ font-size:12px; color:#333; font-weight:normal;border-bottom: 2px solid #C9A5E4;border-right: 2px solid #C9A5E4;font-family: arial}table.reporttableHeader{ background:#ccc;}table.reporttableHeader tr td.titles{ background:#d8beeb; padding:4px; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; font-weight:bold;}table.reporttableHeader tr td{ background:#fff; padding:4px; font-size:12px; color:#666;}table.reporttableHeader tr td.label{ font-size:12px; color:#333; font-weight:bold;}table.reporttablewithoutborder{ background:#ccc;}table.reporttablewithoutborder tr td.titles{ background:#d8beeb; padding:4px; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; font-weight:bold;}table.reporttablewithoutborder tr td{ background:#fff; padding:4px; font-size:12px; color:#666;border-bottom: 2px solid #FFFFFF;border-right: 2px solid #FFFFFF;}table.reporttablewithoutborder tr td.label{ font-size:12px; color:#333; font-weight:bold;border-bottom: 2px solid #FFFFFF;border-right: 2px solid #FFFFFF;}table.viewreporttable{ background:#ccc;}table.viewreporttable tr td.titles{ background:#d8beeb; padding:4px; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; font-weight:bold;}table.viewreporttable tr td{ background:#fff; padding:4px; font-size:12px; color:#666;border-bottom: 2px solid #C9A5E4; border-right: 2px solid #C9A5E4;}table.viewreporttable tr td.label{ font-size:12px; color:#333; font-weight:bold;border-bottom: 2px solid #C9A5E4;    border-right: 2px solid #C9A5E4;}table.viewreporttable tr td.label1{ font-size:12px; color:#333; font-weight:normal;border-bottom: 2px solid #C9A5E4; border-right: 2px solid #C9A5E4;font-family: arial}.buttonview{ position:relative; border:none; background:#5200a6; padding:4px 10px; text-align:center; font-size:13px; color:#fff; cursor:pointer;-webkit-border-radius: 3px;-moz-border-radius:3px;border-radius: 3px; behavior: url(PIE.htc);}.buttonview:hover{ background:#222;}table { border-collapse: collapse; border-spacing: 0;}table.reporttableHeader{ background:#ccc;}table.reporttableHeader tr td.titles{ background:#d8beeb; padding:4px; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; font-weight:bold;}table.reporttableHeader tr td{ background:#fff; padding:4px; font-size:12px; color:#666;}table.reporttableHeader tr td.label{ font-size:12px; color:#333; font-weight:bold;}</style>')
   docprint.document.write('<body onLoad="self.print()" style="-webkit-print-color-adjust:exact;">');
   docprint.document.write(content_vlue);          
   docprint.document.write('</html>');
   docprint.document.close(); 
   docprint.focus();

    
}
</script>


      
<div class="view_sub_contentwrap" >
  <table width="100%" border="0" cellspacing="1" cellpadding="0" class="reporttableHeader" >
      <tr>
	    <td colspan="2" rowspan="2" width="49%" style="margin-left: 20px;"><img src="<?php echo $this->webroot; ?>images/huracan_logo.png" alt="Huracan" title="Huracan"></td> <td colspan="2" rowspan="2" width="49%" style="padding-bottom:20px;color: #75309F;font-size: 24px;font-weight:600">Job Report
</td>
      </tr>
  </table>    
	  <table width="100%" border="0" cellspacing="1" cellpadding="0" class="reporttableHeader">
                <tr>
			      <td width="22%" align="left" valign="middle" class="titles">Report Date:</td>
			      <td width="27%" align="left" valign="middle" class="titles" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JobReportMain']['report_date_val']; ?></td>
			      <td width="23%" align="left" valign="middle" class="titles">Report Number:</td>
			      <td width="28%" align="left" valign="middle" class="titles" style="color:red;border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JobReportMain']['report_no']; ?>&nbsp;<span id="print_button_area"><input type="button" name="save" id="print_button" class="buttonview" onclick="Clickheretoprint();"  value="Print" /></span></td>
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
                           <td align="left" width="23%" valign="middle" class="label">Rig:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php if(isset($jobwelldata[0]['JobWellData']['rig'])){ echo $jobwelldata[0]['JobWellData']['rig'];} ?></td>
                   </tr>
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Depart Base:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JobReportMain']['return_deprt_val']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Wellsite Rep:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"<?php echo $reportdetail[0]['JobReportMain']['wellsite_rep']; ?></td>
                   </tr>
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Start Job:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JobReportMain']['start_job_val']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Field Ticket:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JobReportMain']['field_ticket']; ?></td>
                   </tr>
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">End Job:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JobReportMain']['end_job_val']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Revenue:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JobReportMain']['revenue']; ?></td>
                   </tr>
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Return Base:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JobReportMain']['return_base_val'];?></td>
                           <td align="left" width="23%" valign="middle" class="label">Field Location:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['Fieldlocation']['type']; ?></td>
                   </tr>
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Operating Time:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JobReportMain']['operating_time']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Country:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['Country']['name']; ?></td>
                   </tr>
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Lost Time:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JobReportMain']['loss_time']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label"></td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"></td><!-- <?php echo $reportdetail[0]['Country']['name']; ?>-->
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
		           <td align="left" width="22%" valign="middle" class="label">Well:</td><!--Well Name: -->
                           <td align="left" width="18%" valign="middle" ><?php echo $jobwelldata[0]['JobWellData']['well']; ?></td><!-- <?php //echo $reportdetail[0]['JobWellData'][0]['well_name']; ?>-->
                           <td align="left" width="16%" valign="middle" class="label">Devi:</td> <!--Rig Name:-->
                           <td align="left" width="13%" valign="middle" ><?php if($reportdetail[0]['JobWellData'][0]['devi']!=''){ echo $reportdetail[0]['JobWellData'][0]['devi'].'&nbsp;&deg;';  } ?></td><!--<?php //if(isset($reportdetail[0]['JobWellData'][0]['rig_name'])){ echo $reportdetail[0]['JobWellData'][0]['rig_name'];} ?>-->
                           <td align="left" width="18%" valign="middle" class="label">Fluid:</td>
                           <td align="left" width="23%" valign="middle" ><?php if(isset($reportdetail[0]['JobWellData'][0]['Welldata']['type'])){echo $reportdetail[0]['JobWellData'][0]['Welldata']['type'];} ?></td>
		   </tr>
                    <tr>
		           <td align="left" width="22%" valign="middle" class="label">Depth:</td>
                           <td align="left" width="18%" valign="middle" >
			    <?php if($reportdetail[0]['JobWellData'][0]['depth']!=''){ echo $reportdetail[0]['JobWellData'][0]['depth'].'&nbsp;m';  } ?>
			    </td>
                           <td align="left" width="16%" valign="middle" class="label">Density:</td>
                           <td align="left" width="13%" valign="middle" >
			       <?php if($reportdetail[0]['JobWellData'][0]['density']!=''){ echo $reportdetail[0]['JobWellData'][0]['density'].'&nbsp;ppg';  } ?>	   
			  </td>
                           <td align="left" width="18%" valign="middle" class="label">BHTemp:</td>
                           <td align="left" width="23%" valign="middle" >
			    <?php if($reportdetail[0]['JobWellData'][0]['bhtemp']!=''){ echo $reportdetail[0]['JobWellData'][0]['bhtemp'].'&nbsp;&deg;C';  } ?>
			   </td>
		   </tr>
                   <tr>
		           <td align="left" width="22%" valign="middle" class="label">SHTemp:</td>
                           <td align="left" width="18%" valign="middle" >
			   
			   <?php if($reportdetail[0]['JobWellData'][0]['shtemp']!=''){ echo $reportdetail[0]['JobWellData'][0]['shtemp'].'&nbsp;&deg;C';  } ?>
			   
                           </td>
                           <td align="left" width="16%" valign="middle" class="label">Status:</td>
                           <td align="left" width="13%" valign="middle" >
			   
			 <?php if(isset($reportdetail[0]['JobWellData'][0]['WellStatus']['type'])){echo $reportdetail[0]['JobWellData'][0]['WellStatus']['type']; }?>   
</td>
                           <td align="left" width="18%" valign="middle" class="label">WH Pres:</td>
                           <td align="left" width="23%" valign="middle" >
			   
			   
			   <?php if($reportdetail[0]['JobWellData'][0]['whpres']!=''){ echo $reportdetail[0]['JobWellData'][0]['whpres'].'&nbsp;psi';  } ?>  	   
						   
			   </td>
		   </tr>
                   <tr>
		           <td align="left" width="22%" valign="middle" class="label">BH Pres:</td>
                           <td align="left" width="18%" valign="middle" >
			   <?php if($reportdetail[0]['JobWellData'][0]['bhpres']!=''){ echo $reportdetail[0]['JobWellData'][0]['bhpres'].'&nbsp;psi';  } ?>  
			   
			   
			  </td>
                           <td align="left" width="16%" valign="middle" class="label"></td>
                           <td align="left" width="13%" valign="middle" ></td><!-- <?php //if(isset($reportdetail[0]['JobWellData'][0]['WellStatus']['type'])){echo $reportdetail[0]['JobWellData'][0]['WellStatus']['type']; }?>-->
                           <td align="left" width="18%" valign="middle" class="label">H₂S:</td>
                           <td align="left" width="23%" valign="middle" >
			
		 <?php if($reportdetail[0]['JobWellData'][0]['hts']!=''){ echo $reportdetail[0]['JobWellData'][0]['hts'].'&nbsp;ppm';  } ?>	
		  
			  </td>
		   </tr>
                   <tr> 
                           <td align="left" width="20%" valign="middle" class="label">CO₂:</td>
                           <td align="left" colspan="5" width="80%" valign="middle" >
			   
			    <?php if($reportdetail[0]['JobWellData'][0]['cot']!=''){ echo $reportdetail[0]['JobWellData'][0]['cot'].'&nbsp;ppm';  } ?>
			   
			   
			   </td>
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
				  <td align="left"  width="78%" valign="middle"><a href="<?php echo $this->webroot; ?>img/file_upload/<?php echo $reportdetail[0]['JobAttachment'][$k]['file_name']; ?>" ><?php echo $reportdetail[0]['JobAttachment'][$k]['file_name'];?></a></td>

                          </tr>

                 
                       <?php } ?>
           </table>

       <?php 
          
          }
	 
	 
	  if(count($linkDataHolder)>0){
	    ?>
	    <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">

                <tr><td colspan="2" align="center" valign="middle" class="titles">Link</td></tr>
                <tr><td align="left" width="22%" valign="middle" class="label">Summary</td><td align="left" width="78%" valign="middle" class="label">File Name</td></tr>    
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
			 <td align="left" width="10%" valign="middle"  class="label" ><?php if(isset($reportdetail[0]['JobCustomerFeedback'][0]['total_val'])){ echo $reportdetail[0]['JobCustomerFeedback'][0]['total_val'];} ?></td>
			
		    </tr>
		      <tr>
			 <td align="left"  width="50%" valign="top" >Comment</td>
			 <td align="left" width="50%" colspan="5" valign="middle"  ><?php if(isset($reportdetail[0]['JobCustomerFeedback'][0]['comments'])){ echo $reportdetail[0]['JobCustomerFeedback'][0]['comments'];} ?></td>
			 
		    </tr>
		</table>
	        
  </div>      
  </section>