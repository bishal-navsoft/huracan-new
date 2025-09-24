<aside>
<?php echo $this->Element('left_menu'); ?>
</aside>
<section>
      <?php
            echo $this->Element('lessontab');
    
     ?>
       <script language="javascript" type="text/javascript">
     
      
      $(document).ready(function() {
	       $("#main").removeClass("selectedtab");
	       $("#attachment").removeClass("selectedtab");
	       $("#revalidation").removeClass("selectedtab");
	       
	       $("#link").removeClass("selectedtab");
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
			      <td width="22%" align="left" valign="middle" class="titles">Created Date:</td>
			      <td width="27%" align="left" valign="middle" class="titles" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['LessonMain']['report_date_val']; ?></td>
			      <td width="23%" align="left" valign="middle" class="titles">Report Number:</td>
			      <td width="28%" align="left" valign="middle" class="titles" style="color:red;border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['LessonMain']['report_no']; ?></td>
		    </tr>
            
          </table>
          <table width="100%" border="0" cellspacing="1" cellpadding="0" class="reporttablewithoutborder">
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Type:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['LessonType']['type']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Business Unit:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['BusinessType']['type']; ?></td>
                   </tr>
                    <tr>
                           <td align="left" width="22%" valign="middle" class="label">Service:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['Service']['type']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Incident:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['Incident']['type']; ?></td>
                   </tr>
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Created By:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['AdminMaster']['first_name'].' '.$reportdetail[0]['AdminMaster']['last_name']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Validated By:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['LessonMain']['validate_by_person'];?> </td>
                   </tr>
                   <tr>
                           <td align="left" width="22%" valign="middle" class="label">Revalidation Date:</td>
                           <td align="left" width="27%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['LessonMain']['revalidate_date_val']; ?></td>
                           <td align="left" width="23%" valign="middle" class="label">Validated Date:</td>
                           <td align="left"  width="28%" valign="middle" class="label1" style="border-right: 2px solid #C9A5E4;"><?php echo $reportdetail[0]['LessonMain']['validation_date_val']; ?></td>
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
			      <td width="100%" align="center" colspan="2" valign="middle" class="titles"><?php echo $reportdetail[0]['LessonType']['type']; ?></td>
			      
		</tr>
                <tr>
                            <td align="left" width="22%" valign="middle" class="label">Summary:</td>
                            <td align="left" width="78%" valign="middle" class="label1"><?php echo $reportdetail[0]['LessonMain']['summary'];?></td>
                </tr>
                 <tr>
                            <td align="left" width="22%" valign="middle" class="label">Details:</td>
                            <td align="left" width="78%" valign="middle" class="label1"><?php echo $reportdetail[0]['LessonMain']['details'];?></td>
                </tr>
            
          </table>
          <?php  
          if(isset($reportdetail[0]['LessonAttachment'][0])){?>
           <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">

                <tr><td colspan="2" align="center" valign="middle" class="titles">Attachments</td></tr>
                <tr><td align="left" width="22%" valign="middle" class="label">Description</td><td align="left" width="78%" valign="middle" class="label">File Name</td></tr>    
                   <?php    for($k=0;$k<count($reportdetail[0]['LessonAttachment']);$k++){ ?>

                          <tr>

                                  <td align="left" width="22%" valign="middle" ><?php echo $reportdetail[0]['LessonAttachment'][$k]['description'];?> </td>
				  <td align="left"  width="78%" valign="middle"> <?php  
				    
				    $file_type_array = array ('pdf', 'xlsx', 'xls','doc','xlsm');
		                    $filetype=explode('.',$reportdetail[0]['LessonAttachment'][$k]['file_name']);
				    if(in_array($filetype[1],$file_type_array)){
					    $fileSRC='<a href='.$this->webroot.'img/file_upload/'.$reportdetail[0]['LessonAttachment'][$k]['file_name'].'>'.$reportdetail[0]['LessonAttachment'][$k]['file_name'].'</a>';
				    
				    }else{
					  
					  
					   $fileSRC='<a href='.$this->webroot.'resize.php?src=img/file_upload/'.$reportdetail[0]['LessonAttachment'][$k]['file_name'].'&w=300&h=300 target="_blank">'.$reportdetail[0]['LessonAttachment'][$k]['file_name'].'</a>';
					  
					 
				    }
						     
			          echo  $fileSRC ; ?></td>

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
  </div>
  </section>