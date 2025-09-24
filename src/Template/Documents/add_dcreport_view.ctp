<aside>
<?php echo $this->Element('left_menu'); ?>
</aside>
<section>
      <?php
            echo $this->Element('documentationtab');
    
     ?>
       <script language="javascript" type="text/javascript">
     
      
      $(document).ready(function() {
	       $("#main").removeClass("selectedtab");
	       $("#attachment").removeClass("selectedtab");
	       $("#link").removeClass("selectedtab");
	       $("#view").addClass("selectedtab");
	       $("#print").removeClass("selectedtab");
         });

    
     
    
  </script>
       
       
  <div class="view_sub_contentwrap">

	  <table width="100%" border="0" cellspacing="1" cellpadding="0" class="reporttableHeader">
                <tr>
			      <td width="22%" align="left" valign="middle" class="titles">Created Date:</td>
			      <td width="27%" align="left" valign="middle" class="titles" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['DocumentMain']['report_date_val']; ?></td>
			      <td width="23%" align="left" valign="middle" class="titles">Report Number:</td>
			      <td width="28%" align="left" valign="middle" class="titles" style="color:red;border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['DocumentMain']['report_no']; ?></td>
		    </tr>
            
          </table>
          <table width="100%" border="0" cellspacing="1" cellpadding="0" class="reporttablewithoutborder">
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Type:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['DocumentationType']['type']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Business Unit:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['BusinessType']['type']; ?></td>
                   </tr>
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Created By:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['AdminMaster']['first_name'].' '.$reportdetail[0]['AdminMaster']['last_name']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Validated By:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['DocumentMain']['validate_by_person'];?> </td>
                   </tr>
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Revalidation Date:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['DocumentMain']['revalidate_date_val']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Validated Date:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['DocumentMain']['validation_date_val']; ?></td>
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
			      <td width="100%" align="center" colspan="2" valign="middle" class="titles"><?php echo $reportdetail[0]['DocumentationType']['type']; ?></td>
			      
		</tr>
                <tr>
                            <td align="left" width="22%" valign="middle" class="label">Summary:</td>
                            <td align="left" width="78%" valign="middle" class="label1"><?php echo $reportdetail[0]['DocumentMain']['summary'];?></td>
                </tr>
                 <tr>
                            <td align="left" width="22%" valign="middle" class="label">Details:</td>
                            <td align="left" width="78%" valign="middle" class="label1"><?php echo $reportdetail[0]['DocumentMain']['details'];?></td>
                </tr>
            
          </table>
	   
	<?php
	 if(isset($reportdetail[0]['DocumentAttachment'][0])){?>

           <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">

                <tr><td colspan="2" align="center" valign="middle" class="titles">Attachments</td></tr>
                <tr><td align="left" width="22%" valign="middle" class="label">Description</td><td align="left" width="78%" valign="middle" class="label">File Name</td></tr>    
                   <?php    for($k=0;$k<count($reportdetail[0]['DocumentAttachment']);$k++){ ?>

                          <tr>
                                  <td align="left" width="22%" valign="middle" ><?php echo $reportdetail[0]['DocumentAttachment'][$k]['description'];?> </td>
				  <td align="left"  width="78%" valign="middle"> <?php  
				    
				    $file_type_array = array ('pdf', 'xlsx', 'xls','doc','xlsm');
		                    $filetype=explode('.',$reportdetail[0]['DocumentAttachment'][$k]['file_name']);
				    if(in_array($filetype[1],$file_type_array)){
					    $fileSRC='<a href='.$this->webroot.'img/file_upload/'.$reportdetail[0]['DocumentAttachment'][$k]['file_name'].'>'.$reportdetail[0]['DocumentAttachment'][$k]['file_name'].'</a>';
				    
				    }else{
					  
					  
					   $fileSRC='<a href='.$this->webroot.'resize.php?src=img/file_upload/'.$reportdetail[0]['DocumentAttachment'][$k]['file_name'].'&w=300&h=300 target="_blank">'.$reportdetail[0]['DocumentAttachment'][$k]['file_name'].'</a>';
					  
					 
				    }
						     
			          echo  $fileSRC ; ?></td>

                          </tr>

                 
                       <?php } ?>
           </table>

       <?php 
          
          }
	   
	 ?>  
	   
   </div>
  
  </section>