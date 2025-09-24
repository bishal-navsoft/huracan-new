<aside>
<?php echo $this->Element('left_menu'); ?>
</aside>
<section>
      <?php
            echo $this->Element('jhatab');
    
     ?>
       <script language="javascript" type="text/javascript">
     
      
      $(document).ready(function() {
	       $(document).ready(function() {
                    $("#main").removeClass("selectedtab");
	            $("#attachment").removeClass("selectedtab");
	            $("#link").removeClass("selectedtab");
	            $("#view").addClass("selectedtab");
	            $("#print").removeClass("selectedtab");
                
             });
         });



     
    
  </script>
       
       
  <div class="view_sub_contentwrap">
     	  <table width="100%" border="0" cellspacing="1" cellpadding="0" class="reporttableHeader">
                <tr>
			      <td width="22%" align="left" valign="middle" class="titles">Created Date:</td>
			      <td width="27%" align="left" valign="middle" class="titles" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JhaMain']['report_date_val']; ?></td>
			      <td width="23%" align="left" valign="middle" class="titles">Report Number:</td>
			      <td width="28%" align="left" valign="middle" class="titles" style="color:red;border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JhaMain']['report_no']; ?></td>
		    </tr>
            
          </table>
          <table width="100%" border="0" cellspacing="1" cellpadding="0" class="reporttablewithoutborder">
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Type:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JhaType']['type']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Business Unit:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['BusinessType']['type']; ?></td>
                   </tr>
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Created By:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['AdminMaster']['first_name'].' '.$reportdetail[0]['AdminMaster']['last_name']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Validated By:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JhaMain']['validate_by_person'];?> </td>
                   </tr>
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Revalidation Date:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JhaMain']['revalidate_date_val']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Validated Date:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['JhaMain']['validation_date_val']; ?></td>
                   </tr>
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Field Location:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['Fieldlocation']['type']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Country:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['Country']['name']; ?></td>
                   </tr> 
          </table>
           <table width="100%" border="0" cellspacing="1" cellpadding="0" class="reporttableHeader">
                <tr>
			      <td width="100%" align="center" colspan="2" valign="middle" class="titles"><?php echo $reportdetail[0]['JhaType']['type']; ?></td>
			      
		</tr>
                <tr>
                            <td align="left" width="22%" valign="middle" class="label">Summary:</td>
                            <td align="left" width="78%" valign="middle" class="label1"><?php echo $reportdetail[0]['JhaMain']['summary'];?></td>
                </tr>
                 <tr>
                            <td align="left" width="22%" valign="middle" class="label">Details:</td>
                            <td align="left" width="78%" valign="middle" class="label1"><?php echo $reportdetail[0]['JhaMain']['details'];?></td>
                </tr>
            
          </table>
	  <?php
	  
 
	  
             if(isset($reportdetail[0]['JhaRemidial'][0])){
          ?>
          <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">

           <?php
             for($s=0;$s<count($reportdetail[0]['JhaRemidial']);$s++){?>
                   <tr><td colspan="4" align="center" valign="middle" class="titles">Follow Up Action Item <?php echo $reportdetail[0]['JhaRemidial'][$s]['remedial_no']; ?></td></tr> 
                   <tr>

                         <td align="left" width="22%" valign="middle" class="label">Created On:</td>
			 <td align="left"  width="27%" valign="middle"><?php echo $reportdetail[0]['JhaRemidial'][$s]['rem_crt_val'];?></td>
                         <td align="left" width="23%" valign="middle" class="label">Closure Target:</td>
			 <td align="left"  width="28%" valign="middle"><?php echo $reportdetail[0]['JhaRemidial'][$s]['rem_cls_trgt'];?></td>

                   </tr>
                  <tr>

                         <td align="left" width="22%" valign="middle" class="label">Created By:</td>
			 <td align="left"  width="27%" valign="middle"><?php echo $reportdetail[0]['JhaRemidial'][$s]['AdminMaster']['first_name'].' '.$reportdetail[0]['SuggestionRemidial'][$s]['AdminMaster']['last_name'];?></td>
                         <td align="left" width="23%" valign="middle" class="label">Last Update:</td>
			 <td align="left"  width="28%" valign="middle"><?php echo $reportdetail[0]['JhaRemidial'][$s]['modified'];?></td>

                   </tr>
                   <tr>

                         <td align="left" width="22%" valign="middle" class="label">Responsibility:</td>
			 <td align="left"  width="27%" valign="middle"><?php echo $reportdetail[0]['JhaRemidial'][$s]['responsibility_person'];?></td>
                         <td align="left" width="23%" valign="middle" class="label">Priority:</td>
			 <td align="left"  width="28%" valign="middle"><?php echo $reportdetail[0]['JhaRemidial'][$s]['Priority']['prority_colorcode'];?></td>

                   </tr>
                   <tr>
                         <td align="left" width="22%" valign="middle" class="label">Summary:</td>
			 <td align="left"  width="78%" colspan="3" valign="middle"><?php echo $reportdetail[0]['JhaRemidial'][$s]['remidial_summery'];?></td>
                        
                  </tr> 
                  <tr>
                         <td align="left" width="22%" valign="middle" class="label">Action Item:</td>
			 <td align="left"  width="78%" colspan="3" valign="middle"><?php echo $reportdetail[0]['JhaRemidial'][$s]['remidial_action'];?></td>
                        
                  </tr> 
                  <tr>
                         <td align="left" width="22%" valign="middle" class="label">Closure Summary:</td>
			 <td align="left"  width="78%" colspan="3" valign="middle"><?php echo $reportdetail[0]['JhaRemidial'][$s]['remidial_closer_summary'];?></td>
                        
                  </tr>
                   <tr>

                         <td align="left" width="22%" valign="middle" class="label">Reminder Date:</td>
			 <td align="left"  width="27%" valign="middle"><?php echo $reportdetail[0]['JhaRemidial'][$s]['rem_rim_data'];?></td>
                         <td align="left" width="23%" valign="middle" class="label">Closure Date:</td>
			 <td align="left"  width="28%" valign="middle" class="label"><?php echo $reportdetail[0]['JhaRemidial'][$s]['closeDate'];?></td>

                   </tr>
          
                 
           
       <?php
            } 
        ?>
       </table>
   
      <?php 
          }
	 
	  
          if(isset($reportdetail[0]['JhaAttachment'][0])){?>
           <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">

                <tr><td colspan="2" align="center" valign="middle" class="titles">Attachments</td></tr>
                <tr><td align="left" width="22%" valign="middle" class="label">Description</td><td align="left" width="78%" valign="middle" class="label">File Name</td></tr>    
                   <?php    for($k=0;$k<count($reportdetail[0]['JhaAttachment']);$k++){ ?>

                          <tr>

                                  <td align="left" width="22%" valign="middle" ><?php echo $reportdetail[0]['JhaAttachment'][$k]['description'];?> </td>
				  <td align="left"  width="78%" valign="middle"><a href="<?php echo $this->webroot; ?>img/file_upload/<?php echo $reportdetail[0]['JhaAttachment'][$k]['file_name']; ?>" ><?php echo $reportdetail[0]['JhaAttachment'][$k]['file_name'];?></a></td>

                          </tr>

                 
                       <?php } ?>
           </table>

       <?php 
          
          }
          
	 /* if(count($linkDataHolder)>0){
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
	    */
          ?>
  </div>
  </section>