<section class="adminwrap"  id="printdiv">
<script language="javascript" type="text/javascript">
            

    function Clickheretoprint()
{
     document.getElementById('print_button_area').style.display="none";

     var disp_setting="toolbar=yes,location=no,directories=yes,menubar=yes,"; 
     disp_setting+="scrollbars=yes,width=830, height=600, left=100, top=25"; 
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

<div class="view_sub_contentwrap">
      
 <table width="100%" border="0" cellspacing="1" cellpadding="0" class="reporttableHeader" >
      <tr>
	    <td colspan="2" rowspan="2" width="49%" style="margin-left: 20px;"><img src="<?php echo $this->webroot; ?>images/huracan_logo.png" alt="Huracan" title="Huracan"></td> <td colspan="2" rowspan="2" width="49%" style="padding-bottom:20px;color: #75309F;font-size: 24px;font-weight:600">Trip Report
             </td>
      </tr>
  </table>       
  <table width="100%" border="0" cellspacing="1" cellpadding="0" class="reporttableHeader">
    <tr>
      <td width="22%" align="left" valign="middle" class="titles">Trip Date:</td>
      <td width="27%" align="left" valign="middle" class="titles" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JnReportMain']['trip_date']; ?></td>
      <td width="23%" align="left" valign="middle" class="titles">Trip Number:</td>
      <td width="28%" align="left" valign="middle" class="titles" style="color:red;border-right: 2px solid #C9A5E4;""><?php echo $reportdetail[0]['JnReportMain']['trip_number']; ?>&nbsp;<span id="print_button_area"><input type="button" name="save" id="print_button" class="buttonview" onclick="Clickheretoprint();"  value="Print" /></span></td>
    </tr>
     </table>
      <table width="100%" border="0" cellspacing="1" cellpadding="0" class="reporttablewithoutborder">
    <tr>
      <td align="left" width="22%" valign="middle" class="label">Departure Time:</td>
      <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JnReportMain']['departure_time']; ?></td>
      <td align="left" width="23%" valign="middle" class="label">Created By:</td>
      <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['AdminMaster']['first_name'].' '.$reportdetail[0]['AdminMaster']['last_name']; ?></td>
    </tr>
    <tr>
      <td align="left" valign="middle" class="label">Business Unit:</td>
      <td align="left" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['BusinessType']['type']; ?></td>
      <td align="left" valign="middle" class="label">Client:</td>
      <td align="left" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['Client']['name']; ?></td>
      </tr>
    <tr>
      <td align="left" valign="middle" class="label">Field Location:</td>
      <td align="left" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['Fieldlocation']['type']; ?></td>
      <td align="left" valign="middle" class="label">Well:</td>
      <td align="left" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JnReportMain']['well']; ?></td>
      </tr>
    <tr>
      <td align="left" valign="middle" class="label" >Country:</td>
      <td align="left" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['Country']['name']; ?></td>
      <td align="left" valign="middle" class="label" >Rig:</td>
      <td align="left" valign="middle" class="label1" style="border-right:2px solid #C9A5E4;"><?php echo $reportdetail[0]['JnReportMain']['rig']; ?></td>
     </tr>
    
     <tr>
      <td align="left" valign="middle" class="label">Weed Hygiene:</td>
      <td align="left" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JnReportMain']['weed_hygiene_value']; ?></td>
      <td align="left" valign="middle" class="label">Cert Number:</td>
      <td align="left" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JnReportMain']['cert_number']; ?></td>
     </tr>
  </table>
  <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">
    <tr>
      <td colspan="4" align="center" valign="middle" class="titles">Trip Details</td>
    </tr>
    <tr>
      <td align="left" width="22%" valign="middle" class="label">Summary:</td>
      <td colspan="3"  width="78%" align="left" valign="middle" class="label1"><?php echo $reportdetail[0]['JnReportMain']['summary']; ?></td>
      </tr>
    <tr>
      <td align="left" width="22%" valign="middle" class="label">Trip Details:</td>
      <td colspan="3" width="78%" align="left" valign="middle" class="label1"><?php echo $reportdetail[0]['JnReportMain']['details']; ?></td>
      </tr>
  </table>
    <?php
     if(isset($reportdetail[0]['JnPersonnel'][0])){?>
   <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">
	  <tr>
	    <td colspan="5" align="center" valign="middle" class="titles">Trip Personnel</td>
	  </tr>
	  <tr><td width="22%" class="label">Name</td><td width="18%" class="label">Phone Number</td><td width="20%" class="label">Vehicle</td><td width="20%" class="label">Hrs Last Sleep</td><td width="20%" class="label">Hrs since Sleep</td></tr>
      <?php
         for($i=0;$i<count($reportdetail[0]['JnPersonnel']);$i++){
	  ?>
	   <tr><td width="20%" ><?php echo  $reportdetail[0]['JnPersonnel'][$i]['AdminMaster']['first_name'].' '.$reportdetail[0]['JnPersonnel'][$i]['AdminMaster']['last_name']; ?></td><td width="20%" ><?php  echo  $reportdetail[0]['JnPersonnel'][$i]['phone_no']; ?></td><td width="20%" ><?php  echo  $reportdetail[0]['JnPersonnel'][$i]['Vehicle']['type']; ?></td><td width="20%" ><?php  echo  $reportdetail[0]['JnPersonnel'][$i]['last_sleep']; ?></td><td width="20%" ><?php  echo  $reportdetail[0]['JnPersonnel'][$i]['since_sleep']; ?></td></tr>
	  
       <?php	  
	 }
       
      ?>
     </table>	  
     <?php }
     if(isset($reportdetail[0]['JnChecklist'][0])){?>
       <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">
	       <tr>
		      <td colspan="2" align="center" valign="middle" class="titles">Checklist</td>
		</tr>
	        <tr>
		      <td width="22%"  valign="middle" class="label">Pre-departure Performed?</td>
		      <td width="78%"  valign="middle" ><?php if($reportdetail[0]['JnChecklist'][0]['departure_performed']==1){echo 'Yes';}else{echo 'No';} ?></td>
		</tr>
		<tr>
		      <td width="22%"  valign="middle" class="label">Night Driving Required?</td>
		      <td width="78%"   valign="middle" ><?php if($reportdetail[0]['JnChecklist'][0]['night_drive_required']==1){echo 'Yes';}else{echo 'No';} ?></td>
		</tr>
      </table>	  
     
    <?php }
    
      if(isset($reportdetail[0]['JnAttachment'][0])){?>

           <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">

                <tr><td colspan="2" align="center" valign="middle" class="titles">Attachments</td></tr>
                <tr><td align="left" width="22%" valign="middle" class="label">Description</td><td align="left" width="78%" valign="middle" class="label">File Name</td></tr>    
                   <?php    for($k=0;$k<count($reportdetail[0]['JnAttachment']);$k++){ ?>

                          <tr>

                                  <td align="left" width="22%" valign="middle" ><?php echo $reportdetail[0]['JnAttachment'][$k]['description'];?> </td>
				  <td align="left"  width="78%" valign="middle"><a href="<?php echo $this->webroot; ?>img/file_upload/<?php echo $reportdetail[0]['JnAttachment'][$k]['file_name']; ?>" ><?php echo $reportdetail[0]['JnAttachment'][$k]['file_name'];?></a></td>

                          </tr>

                 
                       <?php } ?>
           </table>

       <?php 
          
          }
    
    ?>
     
      <?php
    
      if(isset($reportdetail[0]['JnChecklist'][0])){?>
       <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">
	       <tr><td colspan="2"  valign="middle" class="titles">Authorisation</td></tr>
	       <tr><td width="22%"  valign="middle" class="label">Comment:</td><td width="78%">
		    <?php if($reportdetail[0]['JnChecklist'][0]['night_drive_required']==1){echo 'Oprations Manager Approval Required'; }else{}  ?>
	       </td></tr>
	       <tr><td width="22%"  valign="middle" class="label">Driver Signature/s:</td><td width="78%"></td></tr>
	       <tr><td width="22%"  valign="middle" class="label">Approvers Signature:</td><td width="78%"></td></tr>
      </table>
      
    <?php }
      ?>
</div>

</section>
