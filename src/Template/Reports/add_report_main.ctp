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
	       document.getElementById('event_date_error').innerHTML='Event date always less than current date';
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
            //var closer_date = jQuery.trim(document.getElementById('closer_date').value);
	    var incident_type = jQuery.trim(document.getElementById('incident_type').value);
            var business_unit = jQuery.trim(document.getElementById('business_unit').value);
	    var client = jQuery.trim(document.getElementById('client').value);
	    var field_location = jQuery.trim(document.getElementById('field_location').value);
	    var country = jQuery.trim(document.getElementById('country').value);
	    var reporter = jQuery.trim(document.getElementById('reporter').value);
	    var incident_severity = jQuery.trim(document.getElementById('incident_severity').value);
	    var recorable = jQuery.trim(document.getElementById('recorable').value);
	    var potential = jQuery.trim(document.getElementById('potential').value);
            var residual = jQuery.trim(document.getElementById('residual').value);
	    var summary = jQuery.trim(document.getElementById('summary').value);
	    var details = jQuery.trim(document.getElementById('details').value);
	    var currentdate='<?php echo date('m-d-Y'); ?>';
	    if(event_date==''){
	       document.getElementById('event_date').focus();
	       document.getElementById('event_date_error').innerHTML='Please enter event date';
	       return false;
	     }else if(event_date!=''){
	        if(currentdate<event_date){
		    document.getElementById('event_date').focus();
		    document.getElementById('event_date_error').innerHTML='Event date always less than current date';
		    return false;
		    }else{
			 document.getElementById('event_date_error').innerHTML='';
		    }
	     } 
	    

	    
	    var dataStr = $("#add_report_main_form").serialize();
            var rootpath='<?php echo $this->webroot ?>';
	    document.getElementById('loader').innerHTML='<img src="<?php echo $this->webroot; ?>img/loader.gif" />';	
              $.ajax({
			  type: "POST",
			  url: rootpath+"Reports/reportprocess/",
			  data:"data="+dataStr+"&report_no="+report_no+"&incident_type="+incident_type+"&business_unit="+business_unit+"&client="+client+"&field_location="+field_location+"&country="+country+"&reporter="+reporter+"&incident_severity="+incident_severity+"&recorable="+recorable+"&since_event="+since_event+"&reporter="+reporter+"&potential="+potential,
			  success: function(res)
			  {
				      
	         	    var resval=res.split("~");
			      if(resval[0]=='fail'){
				   document.getElementById('loader').innerHTML='<font color="red">Please try again</font>';  
	                     }else if(resval[0]=='add'){
				   document.getElementById('loader').innerHTML='<font color="green">Main Added Successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Reports/add_report_main/'+resval[1];
				   
                             }else if(resval[0]=='update'){
				   document.getElementById('loader').innerHTML='<font color="green">Main Updated Successfully</font>';
				  document.location='<?php echo $this->webroot; ?>Reports/add_report_main/'+resval[1];
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
         echo $this->Element('hssetab');
     }

    echo $this->Form->create('add_report_main_form', array('controller' => 'Reports','name'=>"add_report_main_form", 'id'=>"add_report_main_form", 'method'=>'post','class'=>'adminform'));
    echo $this->Form->input('id', array('type'=>'hidden', 'id'=>'id', 'value'=>$id));
 ?>

 <h2><?php echo $heading; ?><span class="textcmpul">Field marked with * are compulsory  </span></h2>
 <br/>
     
<label><?PHP echo __("Event Date:");?><span>*</span></label>

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


<label><?PHP echo __("Incident Type:");?></label>
<span id="incident_section">
			 
	<select id="incident_type" name="incident_type">	
                             <?php for($i=0;$i<count($incidentDetail);$i++){?>
			     <option value="<?php echo $incidentDetail[$i]['Incident']['id']; ?>" <?php if($incident_type==$incidentDetail[$i]['Incident']['id']){echo "selected";}else{} ?>><?php echo $incidentDetail[$i]['Incident']['type']; ?></option>
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
          <span id="client_section">
			   <span id="client_section">
			    <select id="client" name="client">	
                             <?php for($i=0;$i<count($clientDetail);$i++){?>
			     <option value="<?php echo $clientDetail[$i]['Client']['id']; ?>" <?php if($client==$clientDetail[$i]['Client']['id']){echo "selected";}else{} ?>><?php echo $clientDetail[$i]['Client']['name']; ?></option>
			     <?php } ?>
			      </select>
		             
		   </span>	
		</span>
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
<label><?PHP echo __("Reporter:");?></label>
                         <span id="report_section">
			    <select id="reporter" name="reporter">	
                             <?php for($i=0;$i<count($userDetail);$i++){?>
			     <option value="<?php echo $userDetail[$i]['AdminMaster']['id']; ?>" <?php if($reporter==$userDetail[$i]['AdminMaster']['id']){echo "selected";}else{} ?>><?php echo $userDetail[$i]['AdminMaster']['first_name']." ".$userDetail[$i]['AdminMaster']['last_name']; ?></option>
			     <?php } ?>
			     </select>

		       </span>	
	  
	  
	  
	  
<div class="clearflds"></div>
<h2>Classification</h2>

<div class="clearflds"></div>


<label><?PHP echo __("Incident Severity:");?></label>

                         <span id="incident_severity_section">
			    <select id="incident_severity" name="incident_severity">
			
                             <?php for($i=0;$i<count($incidentSeverityDetail);$i++){?>
			     <option  value="<?php echo $incidentSeverityDetail[$i]['IncidentSeverity']['id']; ?>" <?php if($incident_severity==$incidentSeverityDetail[$i]['IncidentSeverity']['id']){echo "selected";}else{} ?> ><?php echo $incidentSeverityDetail[$i]['IncidentSeverity']['type']; ?></option>
			     <?php } ?>
			     </select>
		             
		       </span>
			 
<div class="clearflds"></div>
<label><?PHP echo __("Recordable:");?></label>
                         <span id="recordable_section">
			        <select id="recorable" name="recorable">	
                                <option value="1" <?php if($recordable==1){echo "selected";}else{} ?> >Yes</option>
				<option value="2" <?php if($recordable==2){echo "selected";}else{} ?>>No</option>
			    </select>
		             
		       </span>
			 
<div class="clearflds"></div>
<label><?PHP echo __("Potential:");?></label>
                         <span id="potential_section">
			    <select id="potential" name="potential">	
                 
				   <?php for($i=0;$i<count($potentialDetail);$i++){?>
				   <option value="<?php echo $potentialDetail[$i]['Potential']['id']; ?>" <?php if($potential==$potentialDetail[$i]['Potential']['id']){echo "selected";}else{} ?>><?php echo $potentialDetail[$i]['Potential']['type']; ?></option>
				   <?php } ?>
			    </select>
		
		            
		       </span>
<div class="clearflds"></div>			 
<label><?PHP echo __("Residual:");?></label>
                         <span id="residual_section">
			    <select id="residual" name="residual">	
                             <?php for($i=0;$i<count($residualDetail);$i++){?>
			     <option value="<?php echo $residualDetail[$i]['Residual']['id']; ?>" <?php if($residual==$residualDetail[$i]['Residual']['id']){echo "selected";}else{} ?>><?php echo $residualDetail[$i]['Residual']['type']; ?></option>
			     <?php } ?>
			    </select>
		       </span>
<div class="clearflds"></div>

<div class="clearflds"></div>			 
<label><?PHP echo __("Summary:");?></label> <?PHP echo $this->Form->input('summary', array('type'=>'textarea', 'id'=>'summary','value'=>$summary,'label' => false,'div' => false, "onkeyup" =>"check_character();")); ?>

<div class="clearflds"></div>	
<label>&nbsp;</label><lable style="font-size: 11px;">Only 100 characters allow for summary</lable>
  
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