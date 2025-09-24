 <script language="javascript" type="text/javascript">
     
     
 function isNumberKey(evt)
    {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
	     return false;
             return true;
     }	     
 



function assign_id(type){
  var path=<?php echo $this->webroot; ?>;
   switch(type){
	 case'incident_loss':
	   
	    var incidentloss=$("#incident_loss").val();
	     if(incidentloss!=0){
	       var passurl="incident_loss&id="+incidentloss;
	     }else if(incidentloss==0){
	       document.getElementById('incident_category_section').innerHTML='';
	       document.getElementById('incident_sub_category_section').innerHTML='';
	       return false;
	          	 
	     }
	 break;
	 case'incident_category':

	     var incidentcategory=$("#incident_category").val();
	     if(incidentcategory!=0){
	      var passurl="incident_category&id="+incidentcategory;
	     }else if(incidentcategory==0){
	       document.getElementById('incident_sub_category_section').innerHTML='';
	       return false;
	     }
	 break;
	
	}
	 
	    $.ajax({
			    type: "POST",
			    url: path+"Reports/displaycontentforloss/",
			    data:"type="+passurl,
			    success: function(res)
			    {
			      
						       
			       	   	var splitvalue=res.split("~");
					switch(splitvalue[0]){
				     	  case 'incident_loss':
					  $("#incident_category_section").html(splitvalue[1]);
					  
					  break;
					  case 'incident_category':
					  $("#incident_sub_category_section").html(splitvalue[1]);
					  break;
					 				
				    }
				
				}
				
	        });

	return false;

			
}

	     
function add_incident_client()
{
             
  
            var incident_time = jQuery.trim(document.getElementById('incident_time').value);
	    var incident_severity = jQuery.trim(document.getElementById('incident_severity').value);
	    var date_incident = jQuery.trim(document.getElementById('date_incident').value);
	    var incident_summary = jQuery.trim(document.getElementById('incident_summary').value);
	    var incident_loss = jQuery.trim(document.getElementById('incident_loss').value);
	    
	    if(document.getElementById('incident_category')){
	       var incident_category = jQuery.trim(document.getElementById('incident_category').value);
	           
	    }else{
	       var incident_category=0
	    }
	    if(document.getElementById('incident_sub_category')){
	       var incident_sub_category = jQuery.trim(document.getElementById('incident_sub_category').value);
	           
	    }else{
	       var incident_sub_category=0
	    }
	  
	    var detail = jQuery.trim(document.getElementById('detail').value);
	    var report_id ='<?php echo $report_id;?>';
	    
	    var curdate='<?php echo date('m-d-Y'); ?>';
            var dataStr = $("#add_report_incident_form").serialize();
            var rootpath='<?php echo $this->webroot ?>';
	    if(date_incident!=''){
	    if(curdate<date_incident){
	       document.getElementById('date_incident_error').innerHTML='Incident date less than current date';
	       document.getElementById('date_incident').focus();
	       return false;
	     }
	    
	    }
  
	     if(incident_time=='0' && incident_severity=='0' && date_incident=='' && incident_loss=='0' && incident_category=='0' && incident_sub_category=='0' && incident_severity=='0' && detail=='' && incident_summary==''){
	      document.getElementById('loader').innerHTML='Please select atleast one value';
	      return false;
	    }
	    
	    document.getElementById('loader').innerHTML='<img src="<?php echo $this->webroot; ?>img/loader.gif" />';	
              $.ajax({
			  type: "POST",
			  url: rootpath+"Reports/hsseincidentprocess/",
			  data:"data="+dataStr+"&report_id="+report_id+"&incident_no=<?php echo $incident_no; ?>",
			  success: function(res)
			  {
			 if(res=='fail'){
				   document.getElementById('loader').innerHTML='<font color="red">Please try again</font>';  
	                     }else if(res=='add'){
				   document.getElementById('loader').innerHTML='<font color="green">Incident Data Added Successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Reports/report_hsse_incident_list/<?php echo base64_encode($report_id); ?>';
			     }else if(res=='update'){
				   document.getElementById('loader').innerHTML='<font color="green">Incident Data Update Successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Reports/report_hsse_incident_list/<?php echo base64_encode($report_id); ?>';
				   
                             }
                          
                           
                             
                 }
		 
	});
	

	return false;
 
}



function check_character(){
  
        var incident_summary =document.getElementById('incident_summary').value;
        if(incident_summary.length>5){
	    var summary_holder=incident_summary.substring(0,100);
	    document.getElementById('incident_summary').value=summary_holder;
	  	  
      }
}




 
 </script>
 
 <div class="wrapall">
<!--steps -->
<!--<div class="stepspanel">
<ul>
<li class="current_step">Main<span>1</span></li>
<li>Client Data<span>2</span></li>
<li>Personnel<span>3</span></li>
<li>Incident<span>4</span></li>
<li>Investigation<span>5</span></li>
<li>Remedial Action<span>6</span></li>
<li>Attachment<span>7</span></li>
<li>Client Feedback<span>8</span></li>
</ul>
</div>-->
<aside>
<?php echo $this->Element('left_menu'); ?>
</aside>
 
 <section>
 <?php
           echo $this->Element('hssetab');

  ?>
     <script language="javascript" type="text/javascript">
     
      
     $(document).ready(function() {
	   $("#main").removeClass("selectedtab");
	   $("#clientdata").removeClass("selectedtab");
	   $("#personnel").removeClass("selectedtab");
	   $("#incident").addClass("selectedtab");
	   $("#investigation").removeClass("selectedtab");
	   $("#investigationdata").removeClass("selectedtab");
	   $("#remidialaction").removeClass("selectedtab");
	   $("#attachment").removeClass("selectedtab");
	   $("#clientfeedback").removeClass("selectedtab");
	   $("#view").removeClass("selectedtab");
	 
      });
     
     
     
  </script>   
    <?php
     
    echo $this->Form->create('add_report_incident_form', array('controller' => 'Reports','name'=>"add_report_incident_form", 'id'=>"add_report_incident_form", 'method'=>'post','class'=>'adminform'));
    echo $this->Form->input('id', array('type'=>'hidden', 'id'=>'id', 'value'=>$incident_id));
 ?>

 <h2><?php echo $heading; ?>&nbsp;&nbsp;(<?php echo $report_number; ?>)</h2>
 <br/>
<span><label><?PHP echo __("Incident No:");?></label><label><?php echo $incident_no; ?></label></span>
<div class="clearflds"></div>
<label><?PHP echo __("Time of Incident:");?></label>
<select name="incident_time" id="incident_time">
     <option value="0">Select One</option>
<?php
for ($i = 0; $i <= 23; $i++)
{ for ($j = 0; $j <= 59; $j+=15)
    {
		$time = str_pad($i, 2, '0', STR_PAD_LEFT).':'.str_pad($j, 2, '0', STR_PAD_LEFT);
	?>
        <option value="<?php echo $time; ?>"<?php if($time_incident==$time){echo "selected";}else{} ?>><?php echo $time; ?></option>
<?php } ?>
<?php } ?>

?>
</select>
<div class="clearflds"></div>
<label><?PHP echo __("Date of Incident:");?></label><?PHP echo $this->Form->input('date_incident', array('type'=>'text', 'id'=>'date_incident','name'=>'date_incident','value'=>$date_incident,'readonly' =>'readonly' ,'label' => false,"onclick"=>"displayDatePicker('date_incident',this);",'div' => false)); ?><span class="textcmpul" id="date_incident_error"></span>


<div class="clearflds"></div>
<label><?PHP echo __("Incident Severity:");?></label><span id="clientreviewed_section">
			   <span id="incident_severity_section">
			       <select id="incident_severity" name="incident_severity">
			     <option value="0">Select One</option>	   
                             <?php for($i=0;$i<count($incidentSeverityDetail);$i++){?>
			     <option value="<?php echo $incidentSeverityDetail[$i]['IncidentSeverity']['id']; ?>" <?php if($incident_severity==$incidentSeverityDetail[$i]['IncidentSeverity']['id']){echo "selected";}else{} ?>><?php echo $incidentSeverityDetail[$i]['IncidentSeverity']['type']; ?></option>
			     <?php } ?>
			     </select>
		        
		   </span>	
		</span>
<div class="clearflds"></div>



<label>
<?PHP
 echo __("Loss:");?></label>
			   <span id="incident_loss_section">
			   <select id="incident_loss" name="incident_loss" onchange="assign_id('incident_loss');">	
                             <option value="0">Select One</option>
			     <?php for($i=0;$i<count($incidentLossDetail);$i++){?>
			     <option value="<?php echo $incidentLossDetail[$i]['Loss']['id']; ?>" <?php if($incident_loss==$incidentLossDetail[$i]['Loss']['id']){echo "selected";}else{} ?>><?php echo $incidentLossDetail[$i]['Loss']['type']; ?></option>
			     <?php } ?>
			     </select>
			    </span>
			   
<div class="clearflds"></div>

<span id="incident_category_section">
     
     <?php

          if($incident_category!=''){?>
	        <label><?PHP echo __("Category:");?></label>
	        <select id="incident_category" name="incident_category" onchange="assign_id('incident_category');">	
                          <option value="0">Select One</option>
		      <?php for($i=0;$i<count($incidentCategoryDetail);$i++){?>
		         <option value="<?php echo $incidentCategoryDetail[$i]['IncidentCategory']['id']; ?>" <?php if($incident_category==$incidentCategoryDetail[$i]['IncidentCategory']['id']){echo "selected";}else{} ?>><?php echo $incidentCategoryDetail[$i]['IncidentCategory']['type']; ?></option>
		      <?php } ?>
	           </select>
	       
	  <?php } ?>
     
          <div class="clearflds"></div>
     
</span>
<span id="incident_sub_category_section">
     
      <?php

          if($incident_sub_category!=''){
		       
	       ?>
	        <label><?PHP echo __("Sub Category:");?></label>
	       <select id="incident_sub_category" name="incident_sub_category" >
			     <option value="0">Select One</option>
                             <?php for($i=0;$i<count($incidentSubCategoryDetail);$i++){?>
			     <option value="<?php echo $incidentSubCategoryDetail[$i]['IncidentSubCategory']['id']; ?>" <?php if($incident_sub_category==$incidentSubCategoryDetail[$i]['IncidentSubCategory']['id']){echo "selected";}else{} ?> ><?php echo $incidentSubCategoryDetail[$i]['IncidentSubCategory']['type']; ?></option>
		    	     <?php } ?>
		     </select>
	       
	  <?php } ?>
          <div class="clearflds"></div>
     
</span>


<label><?PHP echo __("Incident Summary:");?></label><?PHP echo $this->Form->input('incident_summary', array('type'=>'textarea', 'id'=>'incident_summary','name'=>'incident_summary','value'=>$incident_summary,'label' => false,'div' => false,"onkeyup" =>"check_character();")); ?>
<div class="clearflds"></div>
<label>&nbsp;</label><lable style="font-size: 11px;">Only 100 characters allow for summary</lable>
<div class="clearflds"></div>
<label><?PHP echo __("Details:");?></label><?PHP echo $this->Form->input('detail', array('type'=>'textarea', 'id'=>'detail','name'=>'detail','value'=>$detail,'label' => false,'div' => false)); ?>
<div class="clearflds"></div>

<?php echo $this->Form->end(); ?>
<div class="buttonpanel">
<span id="loader" class="textcmpul"  style="float:left;font-size: 13px;"></span><input type="button" name="save" id="save" class="buttonsave" onclick="add_incident_client();" value="<?php echo $button; ?>" />

</div>
</section>