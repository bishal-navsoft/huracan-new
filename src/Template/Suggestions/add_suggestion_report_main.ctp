<?php echo $this->Html->css('calender') ?>
 <script language="javascript" type="text/javascript">


 function isNumberKey(evt)
    {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
	     return false;
             return true;
     }	     
function change_type(){
     
      var document_type = document.getElementById('type').value;
      var sp_val=document_type.split("~");
      document.getElementById('type_area').innerHTML=sp_val[1];
 }	     
  

function add_report_main()
{
          
	    var report_no ='<?php echo $reportno;?>';
            //var closer_date = jQuery.trim(document.getElementById('closer_date').value);
	    
	    var document_type = document.getElementById('type').value;
            var sp_val=document_type.split("~");
	    
	    var incident_type = jQuery.trim(document.getElementById('incident_type').value);
            var business_unit = jQuery.trim(document.getElementById('business_unit').value);
	    var service = jQuery.trim(document.getElementById('service').value);
	    var field_location = jQuery.trim(document.getElementById('field_location').value);
	    var country = jQuery.trim(document.getElementById('country').value);
	    var validate_by = jQuery.trim(document.getElementById('validate_by').value);
	    var validation_date = jQuery.trim(document.getElementById('validate_date').value);
	    var revalidation_date = jQuery.trim(document.getElementById('revalidation_date').value);
	    
	    var currentdate='<?php echo date('m-d-Y'); ?>';
	    /*if(validation_date!=''){
	        if(currentdate<validation_date){
		    document.getElementById('validate_date').focus();
		    document.getElementById('validate_date_error').innerHTML='Validate date always less than current date';
		    return false;
		    }else{
			 document.getElementById('validate_date_error').innerHTML='';
			  
			 
		    }
		    
	     } */
	
	    if(revalidation_date!=''){
	                      if(validation_date==''){
				   document.getElementById('validate_date').focus();
				   document.getElementById('validate_date_error').innerHTML='Please enter validate date';
				   return false;
				   }
			     	
			     	var validation_date_obj  = new Date(validation_date);
					var revalidation_date_obj  = new Date(revalidation_date);	   
			      
			      if(revalidation_date_obj<validation_date_obj){
				
				  document.getElementById('revalidation_date').focus();
				  document.getElementById('revalidation_date_error').innerHTML='Validate date always less than revalidate';
				  return false;
				   
				  }else{
				       document.getElementById('revalidation_date_error').innerHTML='';
				      /* if(currentdate<revalidation_date){
					  document.getElementById('revalidation_date').focus();
				           document.getElementById('revalidation_date_error').innerHTML='Revalidate date always less than  current date';
				  return false;
				       }
				       */
				       
				  }
		    
	           }
	    
	    var report_id ='<?php echo $report_id;?>';
   
	    var dataStr = $("#add_suggestion_main_form").serialize();
            var rootpath='<?php echo $this->webroot ?>';
     document.getElementById('loader').innerHTML='<img src="<?php echo $this->webroot; ?>img/loader.gif" />';	
              $.ajax({
			  type: "POST",
			  url: rootpath+"Suggestions/sgreportprocess/",
			  data:"data="+dataStr+"&report_no="+report_no+"&incident_type="+incident_type+"&business_unit="+business_unit+"&field_location="+field_location+"&country="+country+"&validate_by="+validate_by+"&report_id="+report_id+"&service="+service+"&validation_date="+validation_date+"&revalidation_date="+revalidation_date+"&l_type="+sp_val[0],
			  
			  success: function(res)
			  {
			     
                           	    
			   var resval=res.split("~");
			      if(resval[0]=='fail'){
				   document.getElementById('loader').innerHTML='<font color="red">Please try again</font>';  
	                     }else if(resval[0]=='add'){
				   document.getElementById('loader').innerHTML='<font color="green">Suggestion Added Successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Suggestions/add_suggestion_report_main/'+resval[1];
				   
                             }else if(resval[0]=='update'){
				   document.getElementById('loader').innerHTML='<font color="green">Suggestion Updated Successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Suggestions/add_suggestion_report_main/'+resval[1];
				  }
				 
                          }
		 
	});
	

	return false;
 
}

function check_character(){
  
        var summary = document.getElementById('summary').value;
        if(summary.length>5){
	    var summary_holder=summary.substring(0,100);
	    document.getElementById('summary').value=summary_holder;
	  	  
      }
}

  
  function show_client_reviewer(){
      var clientreviewed_value = jQuery.trim(document.getElementById('clientreviewed').value);
      if(clientreviewed_value==3){
	  document.getElementById('client_revier_section').style.display="block";
	  
      }else if(clientreviewed_value!=3){
	  document.getElementById('client_revier_section').style.display="none";
	  
      }
  }
     

 </script>

<aside>
<?php echo $this->Element('left_menu'); ?>
</aside>
 
 <section>
 <?php
     
    if($id!=0){
         echo $this->Element('suggestiontab');
     }

    echo $this->Form->create('add_suggestion_main_form', array('controller' => 'Suggestions','name'=>"add_suggestion_main_form", 'id'=>"add_suggestion_main_form", 'method'=>'post','class'=>'adminform'));
    echo $this->Form->input('id', array('type'=>'hidden', 'id'=>'id', 'value'=>$id));
 ?>

 <h2><?php echo $heading; ?><span class="textcmpul">Field marked with * are compulsory  </span></h2>
 <br/>
     
<label><?PHP echo __("Create Date:");?></label><label><?PHP echo $create_date;?></label>
<div class="clearflds"></div>
<label><?PHP echo __("Report No:");?></label>
         <label><?PHP echo $reportno;?></label>
         <div class="clearflds"></div>
<label><?PHP echo __("Type:");?></label>
		            <select id="type" name="type" onchange="change_type();">	
				   <?php for($i=0;$i<count($lType);$i++){?>
				   <option value="<?php echo $lType[$i]['SuggestionType']['id'].'~'.$lType[$i]['SuggestionType']['type']; ?>" <?php if($l_type==$lType[$i]['SuggestionType']['id']){echo "selected";}else{} ?>><?php echo $lType[$i]['SuggestionType']['type']; ?></option>
				   <?php } ?>
			     </select>
<div class="clearflds"></div>		 
  <label><?PHP echo __("Business Unit:");?></label>
         <span id="Business_unit_section">
			    <select id="business_unit" name="business_unit">	
			      <?php for($i=0;$i<count($businessDetail);$i++){?>
			      <option value="<?php echo $businessDetail[$i]['BusinessType']['id']; ?>" <?php if($business_unit==$businessDetail[$i]['BusinessType']['id']){echo "selected";}else{} ?>><?php echo $businessDetail[$i]['BusinessType']['type']; ?></option>
			      <?php } ?>
			     </select>
		             
		</span>
<div class="clearflds"></div>
<label><?PHP echo __("Service:");?></label>
		            <select id="service" name="service">	
				   <?php for($i=0;$i<count($service);$i++){?>
				   <option value="<?php echo $service[$i]['Service']['id']; ?>" <?php if($service_type==$service[$i]['Service']['id']){echo "selected";}else{} ?>><?php echo $service[$i]['Service']['type']; ?></option>
				   <?php } ?>
			     </select>
<div class="clearflds"></div>		             


<label><?PHP echo __("Incident Type:");?></label>
<span id="incident_section">
			 
	<select id="incident_type" name="incident_type">	
                             <?php for($i=0;$i<count($incidentDetail);$i++){?>
			     <option value="<?php echo $incidentDetail[$i]['Incident']['id']; ?>" <?php if($incident_type==$incidentDetail[$i]['Incident']['id']){echo "selected";}else{} ?>><?php echo $incidentDetail[$i]['Incident']['type']; ?></option>
			     <?php } ?>
	</select>
			    
			
        </span>
<div class="clearflds"></div>
<label><?PHP echo __("Created By:");?></label><span  style="font-size: 13px;color:#666666"><?PHP echo $created_by;?></span>
<div class="clearflds"></div>	


<label><?PHP echo __("Validate By:");?></label>

			    <select id="validate_by" name="validate_by">	
                             <?php for($i=0;$i<count($userDetail);$i++){?>
			     <option value="<?php echo $userDetail[$i]['AdminMaster']['id']; ?>" <?php if($validate_user==$userDetail[$i]['AdminMaster']['id']){echo "selected";}else{} ?>><?php echo $userDetail[$i]['AdminMaster']['first_name']." ".$userDetail[$i]['AdminMaster']['last_name']; ?></option>
			     <?php } ?>
			     </select>
		
<div class="clearflds"></div>
<label><?PHP echo __("Validation Date:");?></label>

<input type="text" id="validate_date" name="validate_date" readonly="readonly"  onclick="displayDatePicker('validate_date',this);" value="<?php echo $validate_date_val; ?>" /><span class="textcmpul" id="validate_date_error"></span>
<div class="clearflds"></div>


<label><?PHP echo __("Revalidation Date:");?></label>

<input type="text" id="revalidation_date" name="revalidation_date" readonly="readonly"  onclick="displayDatePicker('revalidation_date',this);" value="<?php echo $revalidation_date_val; ?>" /><span class="textcmpul" id="revalidation_date_error"></span>
<div class="clearflds"></div>

<label><?PHP echo __("Field Location:");?></label>
          <span id="client_section">
			   <span id="field_location_section">
		             <select id="field_location" name="field_location">	
                             <?php for($i=0;$i<count($fieldlocationDetail);$i++){?>
			     <option value="<?php echo $fieldlocationDetail[$i]['Fieldlocation']['id']; ?>" <?php if($field_location==$fieldlocationDetail[$i]['Fieldlocation']['id']){echo "selected";}else{} ?>><?php echo $fieldlocationDetail[$i]['Fieldlocation']['type']; ?></option>
			     <?php } ?>
			     </select>
		             
		   </span>	
		</span>
<div class="clearflds"></div>
<label><?PHP echo __("Country:");?></label>

			   <span id="country_section">
                             <select id="country" name="country">	
                             <?php for($i=0;$i<count($country);$i++){?>
			     <option value="<?php echo $country[$i]['Country']['id']; ?>" <?php if($country[$i]['Country']['id']==$cnt){echo "selected";}else{} ?>><?php echo $country[$i]['Country']['name']; ?></option>
			     <?php } ?>
			     </select>
		            
		   </span>	

<div class="clearflds"></div>

<h2 id="type_area"><?php echo $l_type_val; ?></h2>
<div class="clearflds"></div>
		 
<label><?PHP echo __("Summary:");?></label> <?PHP echo $this->Form->input('summary', array('type'=>'textarea', 'id'=>'summary','value'=>$summary,'label' => false,'div' => false, "onkeyup" =>"check_character();")); ?>

<div class="clearflds"></div>	
<label>&nbsp;</label><span style="font-size: 11px;">Only 100 characters allow for summary</span>
  
<div class="clearflds"></div>			 
<label><?PHP echo __("Details:");?></label>  <?PHP echo $this->Form->input('details', array('type'=>'textarea', 'id'=>'details','value'=>$details,  'label' => false,'div' => false)); ?>
<div class="clearflds"></div>		  
<?php echo $this->Form->end(); ?>
<div class="buttonpanel">
<?php

if($button=='Update' && $closer_date!='00-00-0000'){
     
}elseif($button=='Update' &&  $closer_date=='00-00-0000' ){?>
  <span id="loader" style="float:left;font-size: 13px;"></span><input type="button" name="save" id="save" class="buttonsave" onclick="add_report_main();" value="<?php echo $button; ?>" />
     
<?php }elseif($button=='Submit'){ ?>

 <span id="loader" style="float:left;font-size: 13px;"></span><input type="button" name="save" id="save" class="buttonsave" onclick="add_report_main();" value="<?php echo $button; ?>" />

<?php } ?>

</div>
</section>
