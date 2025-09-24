<aside>
<?php echo $this->Element('left_menu'); ?>
</aside>
<section>
     <?php
            echo $this->Element('journeytab');
    
     ?>
      
  <script language="javascript" type="text/javascript">
       
      $(document).ready(function() {

	   $("#main").removeClass("selectedtab");
	   $("#personnel").removeClass("selectedtab");
	   $("#checklist").removeClass("selectedtab");
	   $("#attachments").removeClass("selectedtab");
	   $("#link").removeClass("selectedtab");
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
 

    
  </script>

<div class="view_sub_contentwrap">
  <table width="100%" border="0" cellspacing="1" cellpadding="0" class="reporttableHeader">
    <tr>
      <td width="22%" align="left" valign="middle" class="titles">Trip Date:</td>
      <td width="27%" align="left" valign="middle" class="titles" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JnReportMain']['trip_date']; ?></td>
      <td width="23%" align="left" valign="middle" class="titles">Trip Number:</td>
      <td width="28%" align="left" valign="middle" class="titles" style="color:red;border-right: 2px solid #C9A5E4;""><?php echo $reportdetail[0]['JnReportMain']['trip_number']; ?></td>
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
      <td colspan="4" align="center" valign="middle" class="titles">Trip Details &nbsp;&nbsp;&nbsp;  <a href="http://maps.google.com.au/" target='_blank' style="font-size: 12px;">Get Directions</a></td>
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
				  <td align="left"  width="78%" valign="middle"><?php  
				     $file_type_array = array ('pdf', 'xlsx', 'xls','doc','xlsm');
		                    $filetype=explode('.',$reportdetail[0]['JnAttachment'][$k]['file_name']);
				    if(in_array($filetype[1],$file_type_array)){
					    $fileSRC='<a href='.$this->webroot.'img/file_upload/'.$reportdetail[0]['JnAttachment'][$k]['file_name'].'>'.$reportdetail[0]['JnAttachment'][$k]['file_name'].'</a>';
				    
				    }else{
					  
					   $fileSRC='<a href='.$this->webroot.'resize.php?src=img/file_upload/'.$reportdetail[0]['JnAttachment'][$k]['file_name'].'&w=300&h=300 target="_blank">'.$reportdetail[0]['JnAttachment'][$k]['file_name'].'</a>';
					  
					 
				    }
						     
			          echo  $fileSRC ; ?></td>

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
