<?php echo $this->Html->css('calender') ?>
 <script language="javascript" type="text/javascript">


 function isNumberKey(evt)
    {
       var cert_date = jQuery.trim(document.getElementById('cert_date').value);
       
       if(cert_date!=''){
           
         var charCode = (evt.which) ? evt.which : event.keyCode
           if (charCode > 31 && (charCode < 48 || charCode > 57)){
               return false;
           }else{
                                   
               return true;
           }
      
       }else if(cert_date==''){
           
           document.getElementById('valid_date').value='';
           document.getElementById('cert_date_error').innerHTML='Please enter cert date';
           document.getElementById('cert_date').focus();
       }
     }	     
	
        
   function calculate_date(){
            var valid_date=document.getElementById('valid_date').value;
            var cert_date=document.getElementById('cert_date').value;
            var rootpath='<?php echo $this->webroot; ?>';
            $.ajax({
			  type: "POST",
			  url: rootpath+"Certifications/date_calculate/",
			  data:"data=a&valid_date="+valid_date+"&cert_date="+cert_date,
			  success: function(res)
			  {
			  if(res=='fail'){
                                 document.getElementById('expire_date_content').innerHTML='';
                                 document.getElementById('hepd').innerHTML='';
                                 
                            }else{
                                 document.getElementById('expire_date_content').innerHTML=res;
                                 document.getElementById('hepd').value=res;
                                 
                            }
                              
			    
                          }
			  
		 
	           });
        }

function add_report_main()
{
          
            var cert_user = jQuery.trim(document.getElementById('cert_user').value);
            var cretficate_name = jQuery.trim(document.getElementById('cretficate_name').value);
	    var cert_date = jQuery.trim(document.getElementById('cert_date').value);
     	    var hepd = jQuery.trim(document.getElementById('hepd').value);
            var valid_date = jQuery.trim(document.getElementById('valid_date').value);
	    var currentdate='<?php echo date('m-d-Y'); ?>';
  
	    var dataStr = $("#add_certificate_main_form").serialize();
            var rootpath='<?php echo $this->webroot ?>';
            if(cert_date==''){
                document.getElementById('cert_date_error').innerHTML='Please enter cert date';
                document.getElementById('cert_date').focus();
                return false;      
            }else{
                document.getElementById('cert_date_error').innerHTML='';      
            }
            if(valid_date==''){
                document.getElementById('valid_date_error').innerHTML='Please enter valid days';
                document.getElementById('valid_date').focus();
                return false;      
            }else{
                document.getElementById('valid_date_error').innerHTML='';       
            }
	    document.getElementById('loader').innerHTML='<img src="<?php echo $this->webroot; ?>img/loader.gif" />';	
              $.ajax({
			  type: "POST",
			  url: rootpath+"Certifications/certificationprocess/",
			  data:"data="+dataStr+"&cretficate_name="+cretficate_name+"&cert_date="+cert_date+"&hepd="+hepd+"&cert_user="+cert_user,
			  success: function(res)
			  {
		
                                                                   
	         	   var resval=res.split("~");
			      if(resval[0]=='fail'){
				   document.getElementById('loader').innerHTML='<font color="red">Please try again</font>';  
	                     }else if(resval[0]=='add'){
				   document.getElementById('loader').innerHTML='<font color="green">Main Added Successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Certifications/add_certification_main/'+resval[1];
				   
                             }else if(resval[0]=='update'){
				   document.getElementById('loader').innerHTML='<font color="green">Main Updated Successfully</font>';
				  document.location='<?php echo $this->webroot; ?>Certifications/add_certification_main/'+resval[1];
			     }else if(resval[0]=='avl'){
				   document.getElementById('loader').innerHTML='<font color="red">Certificate already exist</font>';
			     }
				 
                          }
		 
	});
	

	return false;
 
}

 </script>

<aside>
<?php echo $this->Element('left_menu'); ?>
</aside>
 
 <section>
 <?php
 
   echo $this->Element('certificationtab');


    echo $this->Form->create('add_certificate_main_form', array('controller' => 'Certifications','name'=>"add_certificate_main_form", 'id'=>"add_certificate_main_form", 'method'=>'post','class'=>'adminform'));
    echo $this->Form->input('id', array('type'=>'hidden', 'id'=>'id', 'value'=>$id));
 ?>

 <h2><?php echo $heading; ?><span class="textcmpul">Field marked with * are compulsory  </span></h2>
 <br/>
 
 
 <div class="clearflds"></div>
<label><?PHP echo __("Certification List for:");?></label>
			    <select id="cert_user" name="cert_user">	
                             <?php for($i=0;$i<count($userDetail);$i++){?>
			     <option value="<?php echo $userDetail[$i]['AdminMaster']['id']; ?>" <?php if($user==$userDetail[$i]['AdminMaster']['id']){echo "selected";}else{} ?>><?php echo $userDetail[$i]['AdminMaster']['first_name']." ".$userDetail[$i]['AdminMaster']['last_name']; ?></option>
			     <?php } ?>
			     </select>
 <div class="clearflds"></div>
 <label><?PHP echo __("Cert Name:");?></label>
	<select id="cretficate_name" name="cretficate_name">	
                             <?php for($i=0;$i<count($cList);$i++){?>
			     <option value="<?php echo $cList[$i]['CertificationList']['id']; ?>" <?php if($cretficate_id==$cList[$i]['CertificationList']['id']){echo "selected";}else{} ?>><?php echo $cList[$i]['CertificationList']['type']; ?></option>
			     <?php } ?>
	</select>

<div class="clearflds"></div>
     
<label><?PHP echo __("Cert Date:");?><span>*</span></label>

<input type="text" id="cert_date" name="cert_date"  readonly="readonly" ,  onclick="displayDatePicker('cert_date',this);" value="<?php echo $cert_date; ?>" /><span class="textcmpul" id="cert_date_error"></span>
<div class="clearflds"></div>

<div class="clearflds"></div>			 
<label>Valid (Days):<span>*</span></label> <?PHP echo $this->Form->input('valid_date', array('type'=>'text', 'id'=>'valid_date','value'=>$valid_date,'label' => false,'div' => false, 'onkeypress'=>'javascript:return isNumberKey(event);','onkeyup'=>"calculate_date();")); ?><span class="textcmpul" id="valid_date_error"></span>
<div class="clearflds"></div>
<label><?PHP echo __("Expiry Date:");?></label><span id="expire_date_content" style="font-size: 12px;"><?php echo $expire_date; ?></span><input type="hidden" name="hepd" id="hepd" value="<?php echo $expire_date_value; ?>" /></label>
<div class="clearflds"></div>
<label><?PHP echo __("Trainer:");?></label>

			   <?PHP echo $this->Form->input('trainer', array('type'=>'text', 'id'=>'trainer','value'=>$trainer,'label' => false,'div' => false)); ?>
<div class="clearflds"></div>
  
<?php echo $this->Form->end(); ?>
<div class="buttonpanel">
<span id="loader" style="float:left;font-size: 13px;"></span><input type="button" name="save" id="save" class="buttonsave" onclick="add_report_main();" value="<?php echo $button; ?>" />
</div>
</section>