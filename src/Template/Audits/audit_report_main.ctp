<?php echo $this->Html->css('calender') ?>
 <script language="javascript" type="text/javascript">


 function isNumberKey(evt)
    {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
	     return false;
             return true;
     }	     
	     
  
  function check_diff(dateString){
            
            var current_date='<?php echo date("m-d-Y"); ?>';
	    var rootpath='<?php echo $this->webroot; ?>';
	    if(current_date<dateString){
	       document.getElementById('event_date_error').innerHTML='Date of report always less than current date';
	       return false;
	    }else{
	    	   
	               $.ajax({
			  type: "POST",
			  url: rootpath+"Reports/date_calculate/",
			  data:"data=a&lowre_date="+dateString+"&current_date="+current_date,
			  success: function(res)
			  {
			      document.getElementById('since_event').innerHTML=res; 
			     document.getElementById('since_event_hidden').value=res; 
                          }
			  
		 
	           });
		  return false;     
	  
	    }
	     
  }
function add_report_main()
{
          
            var event_date = jQuery.trim(document.getElementById('event_date').value);
	    var since_event = jQuery.trim(document.getElementById('since_event_hidden').value);
	    var report_no ='<?php echo $reportno;?>';
	    var reporter = jQuery.trim(document.getElementById('reporter').value);
	    var audit_type = jQuery.trim(document.getElementById('audit_type').value);
            var business_unit = jQuery.trim(document.getElementById('business_unit').value);
	    var client_data = jQuery.trim(document.getElementById('client_data').value);
	    var field_location = jQuery.trim(document.getElementById('field_location').value);
	    var country = jQuery.trim(document.getElementById('country').value);
	    var reporter = jQuery.trim(document.getElementById('reporter').value);
	    var op = jQuery.trim(document.getElementById('official_permission').value);
	    var summary = jQuery.trim(document.getElementById('summary').value);
	    var details = jQuery.trim(document.getElementById('details').value);
	    var currentdate='<?php echo date('m-d-Y'); ?>';
	    if(event_date==''){
	       document.getElementById('event_date').focus();
	       document.getElementById('event_date_error').innerHTML='Please enter date of report';
	       return false;
	     }else if(event_date!=''){
	        if(currentdate<event_date){
		    document.getElementById('event_date').focus();
		    document.getElementById('event_date_error').innerHTML='Date of report always less than current date always less than current date';
		    return false;
		    }else{
			 document.getElementById('event_date_error').innerHTML='';
		    }
	     } 
	    


	    var dataStr = $("#add_audit_main_form").serialize();
            var rootpath='<?php echo $this->webroot ?>';
	    document.getElementById('loader').innerHTML='<img src="<?php echo $this->webroot; ?>img/loader.gif" />';	
              $.ajax({
			  type: "POST",
			  url: rootpath+"Audits/reportprocess/",
			  data:"data="+dataStr+"&report_no="+report_no+"&audit_type="+audit_type+"&business_unit="+business_unit+"&client="+client_data+"&field_location="+field_location+"&country="+country+"&reporter="+reporter+"&since_event="+since_event+"&reporter="+reporter+"&op="+op,
			  success: function(res)
			  {
						      
	         	    var resval=res.split("~");
			      if(resval[0]=='fail'){
				   document.getElementById('loader').innerHTML='<font color="red">Please try again</font>';  
	                     }else if(resval[0]=='add'){
				   document.getElementById('loader').innerHTML='<font color="green">Audit Added Successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Audits/audit_report_main/'+resval[1];
				   
                             }else if(resval[0]=='update'){
				   document.getElementById('loader').innerHTML='<font color="green">Audit Updated Successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Audits/audit_report_main/'+resval[1];
				  }
                          }
		 
	});
	

	return false;
 
}

function check_character(){
  
        var summary =document.getElementById('summary').value;
        if(summary.length>5){
	    var summary_holder=summary.substring(0,100);
	    document.getElementById('summary').value=summary_holder;
	  	  
      }
}

 </script>

<aside>
<?php echo $this->Element('left_menu'); ?>
</aside>
 
 <section>
 <?php

     
    if($id!=0){
         echo $this->Element('audittab');
	 ?>
	<script language="javascript"  type="text/javascript">
	  $(document).ready(function() {
			$("#main").addClass("selectedtab");
			$("#client").removeClass("selectedtab");
			$("#action").removeClass("selectedtab");
			$("#attachments").removeClass("selectedtab");
			$("#link").removeClass("selectedtab");
			$("#view").removeClass("selectedtab");
			$("#print").removeClass("selectedtab");
            });
	 </script>
	 <?php
	 
     }

    echo $this->Form->create('add_audit_main_form', array('controller' => 'Audits','name'=>"add_audit_main_form", 'id'=>"add_audit_main_form", 'method'=>'post','class'=>'adminform'));
    echo $this->Form->input('id', array('type'=>'hidden', 'id'=>'id', 'value'=>$id));
 ?>

 <h2><?php echo $heading; ?><span class="textcmpul">Field marked with * are compulsory  </span></h2>
 <br/>
     
<label><?PHP echo __("Date of Report:");?><span>*</span></label>

<input type="text" id="event_date" name="event_date"  readonly="readonly" onclick="displayDatePicker('event_date',this);" value="<?php echo $event_date; ?>" /><span class="textcmpul" id="event_date_error"></span>
<div class="clearflds"></div>

         <label><?PHP echo __("Report No:");?><span>*</span></label>
         <label><?PHP echo $reportno;?></label>
         <div class="clearflds"></div>  
         <label><?PHP echo __("Days Since Event:");?><span>*</span></label>
         <label id="since_event"><?PHP echo $since_event_hidden;?></label>
	 <input type="hidden" id="since_event_hidden" name="since_event_hidden" value="<?PHP echo $since_event_hidden;?>" />
         <div class="clearflds"></div>
<!--

<label><?PHP echo __("Closing Date:");?><span>*</span></label><?PHP echo $this->Form->input('closer_date', array('type'=>'text', 'id'=>'closer_date','name'=>'closer_date', 'value'=>$closer_date,'size'=>30,'maxlength'=>'40',"onclick"=>"displayDatePicker('closer_date',this);", 'label' => false,'div' => false)); ?><span class="textcmpul" id="closing_date_error"></span>
<div class="clearflds"></div>

-->


<label><?PHP echo __("Audit Type:");?></label>
<span id="incident_section">
			 
	<select id="audit_type" name="audit_type">	
                             <?php for($i=0;$i<count($auditTypeDetail);$i++){?>
			     <option value="<?php echo $auditTypeDetail[$i]['AuditType']['id']; ?>" <?php if($audit_type==$auditTypeDetail[$i]['AuditType']['id']){echo "selected";}else{} ?>><?php echo $auditTypeDetail[$i]['AuditType']['type']; ?></option>
			     <?php } ?>
	</select>
			    
			
        </span>


<div class="clearflds"></div>
<label><?PHP echo __("Created By:");?><span>*</span></label><span  style="font-size: 13px;color:#666666"><?PHP echo $created_by;?></span>
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
<label><?PHP echo __("Client:");?></label>

			    <select id="client_data" name="client_data">	
                             <?php for($i=0;$i<count($clientDetail);$i++){?>
			     <option value="<?php echo $clientDetail[$i]['Client']['id']; ?>" <?php if($client==$clientDetail[$i]['Client']['id']){echo "selected";}else{} ?>><?php echo $clientDetail[$i]['Client']['name']; ?></option>
			     <?php } ?>
			    </select>

<div class="clearflds"></div>
<label><?PHP echo __("Field Location:");?></label>
          <span id="field_section">
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
<label><?PHP echo __("Reporter:");?></label>
                         <span id="report_section">
			    <select id="reporter" name="reporter">	
                             <?php for($i=0;$i<count($userDetail);$i++){?>
			     <option value="<?php echo $userDetail[$i]['AdminMaster']['id']; ?>" <?php if($reporter==$userDetail[$i]['AdminMaster']['id']){echo "selected";}else{} ?>><?php echo $userDetail[$i]['AdminMaster']['first_name']." ".$userDetail[$i]['AdminMaster']['last_name']; ?></option>
			     <?php } ?>
			     </select>

		       </span>	
 
<div class="clearflds"></div>
<label><?PHP echo __("Official?");?></label>
                         <span id="recordable_section">
			        <select id="official_permission" name="official_permission">	
                                <option value="1" <?php if($official==1){echo "selected";}else{} ?> >Yes</option>
				<option value="2" <?php if($official==2){echo "selected";}else{} ?>>No</option>
			    </select>
		             
		       </span>
			 
<div class="clearflds"></div>
<h2>Classification</h2>
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
<!--<input type="submit" name="button" value="Cancel" class="buttoncancel" />-->
</div>
</section>