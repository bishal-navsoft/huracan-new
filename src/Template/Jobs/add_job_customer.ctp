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
	        $("#job_data").removeClass("selectedtab");
	        $("#gyro_job_data").removeClass("selectedtab");
	        $("#gauge_data").removeClass("selectedtab");
	        $("#action_item").removeClass("selectedtab");
	        $("#attachments").removeClass("selectedtab");
	        $("#link").removeClass("selectedtab");
	        $("#csr").addClass("selectedtab");
	        $("#view").removeClass("selectedtab");
                $("#print").removeClass("selectedtab");
	      
	   });

   function collectID(sv)
   {
    var rd_val= document.getElementById("csr_table").getElementsByTagName("input");
    var sumholder=0;
    var nameHolder=new Array();
    for(var k=0;k<rd_val.length;k++){

      if(rd_val[k].type=="radio"){
 	  if(rd_val[k].checked==true){
	       var sumholder=parseInt(sumholder)+parseInt(rd_val[k].value);
	        document.getElementById("total_csr").innerHTML=sumholder;
	        document.getElementById("total_csr_val").value=sumholder;
	        nameHolder.push(rd_val[k].id+'~'+rd_val[k].value);
	  }
	  
      }
     
     
    }
    var nameHolderString=nameHolder.toString();
    document.getElementById("name_value").value=nameHolderString;
   
   }
   
   function add_job_custom()
{
          
            var total_csr_val = jQuery.trim(document.getElementById('total_csr_val').value);
	    var report_no ='<?php echo $report_id;?>';
	    var comments = jQuery.trim(document.getElementById('comments').value); 
    

	    
	    var dataStr = $("#add_job_customer_form").serialize();

            var rootpath='<?php echo $this->webroot ?>';
	    document.getElementById('loader').innerHTML='<img src="<?php echo $this->webroot; ?>img/loader.gif" />';	
              $.ajax({
			  type: "POST",
			  url: rootpath+"Jobs/customsaticationprocess/",
			  data:"data="+dataStr+"&report_no="+report_no,
			  success: function(res)
			  {
		                 
	         	    var resval=res.split("~");
			      if(resval[0]=='fail'){
				   document.getElementById('loader').innerHTML='<font color="red">Please try again</font>';  
	                     }else if(resval[0]=='add'){
				   document.getElementById('loader').innerHTML='<font color="green">Customer satisfaction report added  successfully</font>';
				   
				   document.location='<?php echo $this->webroot; ?>Jobs/add_job_customer/<?php echo $report_val; ?>';
                             }else if(resval[0]=='update'){
				   document.getElementById('loader').innerHTML='<font color="green">Customer satisfaction report updated  successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Jobs/add_job_customer/<?php echo $report_val; ?>';
				  }
                          }
                          
		 
	});
	

	return false;
 
}
  </script>   

<?php
	 
    echo $this->Form->create('add_job_customer_form', array('controller' => 'add_job_customer_form','name'=>"add_job_customer_form", 'id'=>"add_job_customer_form", 'method'=>'post','class'=>'investigation'));
 ?>

 <h2><?php echo $heading; ?>&nbsp;&nbsp;<?php echo $report_number; ?><span class="textcmpul">Field marked with * are compulsory  </span></h2>
		<div class="sub_contentwrap">
		 <table width="100%" border="0" cellspacing="1" cellpadding="0" class="investigationtable" id="csr_table">
		     <tr>
		     <td colspan="6" align="center" valign="middle" class="titles">Customer Satisfaction Report</td>
		     </tr>
		     <tr>
			 <td align="left" width="50%" valign="middle">&nbsp;</td>
			 <td align="left" width="10%" valign="middle"  class="label">Excellent</td>
			 <td align="left" width="10%" valign="middle"  class="label">Good</td>
			 <td align="left" width="10%" valign="middle"  class="label">Satisfactory</td>
			 <td align="left" width="10%" valign="middle"  class="label">Poor</td>
			 <td align="left" width="10%" valign="middle"  class="label">Unacceptable</td>
		    </tr>
		    <?php
		    for($c=0;$c<count($jsl);$c++){
			
			 
			 ?>
		     <tr>
			 <td align="left" width="50%" valign="middle"><?php echo $jsl[$c]['JobCustomerFeedbackElement']['type']; ?></td>
			 <td align="left" width="10%" valign="middle"><input type="radio" name="csrv_<?php echo $jsl[$c]['JobCustomerFeedbackElement']['id']; ?>" id="<?php echo $jsl[$c]['JobCustomerFeedbackElement']['id'].'_E'; ?>" onclick="collectID('<?php echo $jsl[$c]['JobCustomerFeedbackElement']['id'].'_'.$jsl[$c]['JobCustomerFeedbackElement']['E'].'_E'; ?>');"   <?php if(in_array($jsl[$c]['JobCustomerFeedbackElement']['id'].'_E',$explode_name) && in_array($jsl[$c]['JobCustomerFeedbackElement']['E'],$explode_value)){ echo "checked";} ?> value="<?php  echo $jsl[$c]['JobCustomerFeedbackElement']['E']; ?>" /></td>
			 <td align="left" width="10%" valign="middle"><input type="radio" name="csrv_<?php echo $jsl[$c]['JobCustomerFeedbackElement']['id']; ?>" id="<?php echo $jsl[$c]['JobCustomerFeedbackElement']['id'].'_G'; ?>" onclick="collectID('<?php echo $jsl[$c]['JobCustomerFeedbackElement']['id'].'_'.$jsl[$c]['JobCustomerFeedbackElement']['G'].'_G'; ?>');"  <?php if(in_array($jsl[$c]['JobCustomerFeedbackElement']['id'].'_G',$explode_name) && in_array($jsl[$c]['JobCustomerFeedbackElement']['G'],$explode_value)){ echo "checked";} ?> value="<?php echo $jsl[$c]['JobCustomerFeedbackElement']['G']; ?>" /></td>
			 <td align="left" width="10%" valign="middle"><input type="radio" name="csrv_<?php echo $jsl[$c]['JobCustomerFeedbackElement']['id']; ?>" id="<?php echo $jsl[$c]['JobCustomerFeedbackElement']['id'].'_S'; ?>" onclick="collectID('<?php echo $jsl[$c]['JobCustomerFeedbackElement']['id'].'_'.$jsl[$c]['JobCustomerFeedbackElement']['S'].'_S'; ?>');"  <?php if(in_array($jsl[$c]['JobCustomerFeedbackElement']['id'].'_S',$explode_name) && in_array($jsl[$c]['JobCustomerFeedbackElement']['S'],$explode_value)){ echo "checked";} ?> value="<?php echo $jsl[$c]['JobCustomerFeedbackElement']['S']; ?>" /></td>
			 <td align="left" width="10%" valign="middle"><input type="radio" name="csrv_<?php echo $jsl[$c]['JobCustomerFeedbackElement']['id']; ?>" id="<?php echo $jsl[$c]['JobCustomerFeedbackElement']['id'].'_P'; ?>" onclick="collectID('<?php echo $jsl[$c]['JobCustomerFeedbackElement']['id'].'_'.$jsl[$c]['JobCustomerFeedbackElement']['P'].'_P'; ?>');"  <?php if(in_array($jsl[$c]['JobCustomerFeedbackElement']['id'].'_P',$explode_name) && in_array($jsl[$c]['JobCustomerFeedbackElement']['P'],$explode_value)){ echo "checked";} ?> value="<?php echo $jsl[$c]['JobCustomerFeedbackElement']['P']; ?>" /></td>
			 <td align="left" width="10%" valign="middle"><input type="radio" name="csrv_<?php echo $jsl[$c]['JobCustomerFeedbackElement']['id']; ?>" id="<?php echo $jsl[$c]['JobCustomerFeedbackElement']['id'].'_U'; ?>" onclick="collectID('<?php echo $jsl[$c]['JobCustomerFeedbackElement']['id'].'_'.$jsl[$c]['JobCustomerFeedbackElement']['U'].'_U'; ?>');"  <?php if(in_array($jsl[$c]['JobCustomerFeedbackElement']['id'].'_U',$explode_name) && in_array($jsl[$c]['JobCustomerFeedbackElement']['U'],$explode_value)){ echo "checked";} ?> value="<?php echo $jsl[$c]['JobCustomerFeedbackElement']['U']; ?>" /></td>
		    </tr>
		    
			 
		   <?php  }    ?>
		    <tr>
			 <td align="right" colspan="5" width="90%" valign="middle" >Total</td>
			 <td align="left" width="10%" valign="middle"  class="label" id="total_csr"><?php echo $csr_value; ?></td>
			 <?PHP echo $this->Form->input('total_csr_val', array('type'=>'hidden', 'id'=>'total_csr_val','value'=>$total_csr_val,'label' => false,  'div' => false)); ?>
			 <?PHP echo $this->Form->input('name_value', array('type'=>'hidden', 'id'=>'name_value','value'=>$name_value,'label' => false,  'div' => false)); ?>
		    </tr>
		      <tr>
			 <td align="left"  width="50%" valign="top" >Comment</td>
			 <td align="left" width="50%" colspan="5" valign="middle"  ><?PHP echo $this->Form->input('comments', array('type'=>'textarea', 'id'=>'comments','value'=>$comments,'label' => false, 'rows' =>5,'cols' =>70,  'div' => false)); ?></td>
			 
		    </tr>
		 </table>
		</div>

<div class="buttonpanel">
<span id="loader" style="float:left;font-size: 13px;"></span>
<input type="button" name="save" id="save" class="buttonsave" onclick="add_job_custom();" value="<?php echo $button; ?>" />
</span>
</div>
<?php echo $this->Form->end();?>
</section>