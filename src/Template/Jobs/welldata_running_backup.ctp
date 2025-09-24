 <script language="javascript" type="text/javascript">

 function isNumberKey(evt)
    {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
	     return false;
             return true;
     }	     

 
	     
function add_job_welldata()
{   
	      
	    var report_id ='<?php echo $report_id;?>';
            var dataStr = $("#add_job_well_form").serialize();
            var rootpath='<?php echo $this->webroot ?>';
	    
	    var depth= document.getElementById('depth').value;
	    var devi= document.getElementById('devi').value;
	    var bhtemp= document.getElementById('bhtemp').value;
	    var shtemp= document.getElementById('shtemp').value;
	    var depth= document.getElementById('depth').value;
	    var density= document.getElementById('density').value;
	    var whpres= document.getElementById('whpres').value;
	    var bhpres= document.getElementById('bhpres').value;
	    var depth= document.getElementById('depth').value;
	    var hts= document.getElementById('hts').value;
	    var cot= document.getElementById('cot').value;
	    
	    
	    if(depth!=''){
	
		     if(!depth.match(/^\d+(?:\.\d+)?$/)){
			  document.getElementById('depth_error').innerHTML = 'Enter valid depth';
			  document.getElementById('depth').focus();
			  return false;
		   }else{
			 document.getElementById('depth_error').innerHTML = '';
			 document.getElementById('depth').focus();
		   }
	      
	      }
	       
	     if(devi!=''){
	
		     document.getElementById('loader').innerHTML='';
		     if(!devi.match(/^\d+(?:\.\d+)?$/)){
			        alert(devi);
			  document.getElementById('devi_error').innerHTML = 'Enter valid Devi';
			  document.getElementById('devi').focus();
			  return false;
		   }else{
			 document.getElementById('devi_error').innerHTML = '';
			 document.getElementById('devi').focus();
		   }
	      
	      }
	    

	     if(shtemp!=''){

		     if(!shtemp.match(/^\d+(?:\.\d+)?$/)){
			
			  document.getElementById('shtemp_error').innerHTML = 'Enter valid SHTemp';
			  document.getElementById('shtemp').focus();
			  return false;
		   }else{
			 document.getElementById('shtemp_error').innerHTML = '';
			 document.getElementById('shtemp').focus();
		   }
	      
	      }
	    if(bhtemp!=''){
	
		     if(!bhtemp.match(/^\d+(?:\.\d+)?$/)){
			  document.getElementById('bhtemp_error').innerHTML = 'Enter valid BHTemp';
			  document.getElementById('bhtemp').focus();
			  return false;
		   }else{
			 document.getElementById('bhtemp_error').innerHTML = '';
			 document.getElementById('bhtemp').focus();
		   }
	      
	      }
	    if(density!=''){
	
		     if(!density.match(/^\d+(?:\.\d+)?$/)){
			  document.getElementById('density_error').innerHTML = 'Enter valid Density';
			  document.getElementById('density').focus();
			  return false;
		   }else{
			 document.getElementById('density_error').innerHTML = '';
			 document.getElementById('density').focus();
		   }
	      
	      }
	    if(whpres!=''){
	
		     if(!whpres.match(/^\d+(?:\.\d+)?$/)){
			  document.getElementById('whpres_error').innerHTML = 'Enter valid WH Pres';
			  document.getElementById('whpres').focus();
			  return false;
		   }else{
			 document.getElementById('whpres_error').innerHTML = '';
			 document.getElementById('whpres').focus();
		   }
	      
	      }
	      if(bhpres!=''){
		
		     if(!bhpres.match(/^\d+(?:\.\d+)?$/)){
			  document.getElementById('bhpres_error').innerHTML = 'Enter valid BH Pres';
			  document.getElementById('bhpres').focus();
			  return false;
		   }else{
			 document.getElementById('bhpres_error').innerHTML = '';
			 document.getElementById('bhpres').focus();
		   }
	      
	      }
	       if(hts!=''){
	
		     if(!hts.match(/^\d+(?:\.\d+)?$/)){
			  document.getElementById('hts_error').innerHTML = 'Enter valid H₂S';
			  document.getElementById('hts').focus();
			  return false;
		   }else{
			 document.getElementById('hts_error').innerHTML = '';
			 document.getElementById('hts').focus();
		   }
	      
	      }
	      if(cot!=''){
		
		     if(!cot.match(/^\d+(?:\.\d+)?$/)){
			  document.getElementById('cot_error').innerHTML = 'Enter valid CO2';
			  document.getElementById('cot').focus();
			  return false;
		   }else{
			 document.getElementById('cot_error').innerHTML = '';
			 document.getElementById('cot').focus();
		   }
	      
	      }
	    document.getElementById('loader').innerHTML='<img src="<?php echo $this->webroot; ?>img/loader.gif" />';	
              $.ajax({
			  type: "POST",
			  url: rootpath+"Jobs/welldataprocess/",
			  data:"data="+dataStr+"&report_id="+report_id,
			  success: function(res)
			  {
			      
			      
			   if(res=='fail'){
				   document.getElementById('loader').innerHTML='<font color="red">Please try again</font>';  
	                     }else if(res=='add'){
				   document.getElementById('loader').innerHTML='<font color="green">Welldata Added Successfully</font>';
				 //  document.location='<?php echo $this->webroot; ?>Sqreports/welldata/<?php echo base64_encode($report_id); ?>';
			     }else if(res=='update'){
				   document.getElementById('loader').innerHTML='<font color="green">Welldata Update Successfully</font>';
				//   document.location='<?php echo $this->webroot; ?>Sqreports/welldata/<?php echo base64_encode($report_id); ?>';
				   
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

         echo $this->Element('jobtab');
   
  ?>
     <script language="javascript" type="text/javascript">
     
      
     $(document).ready(function() {
	   $("#main").removeClass("selectedtab");
	   $("#job_data").addClass("selectedtab");
	   $("#gyro_job_data").removeClass("selectedtab");
	   $("#gauge_data").removeClass("selectedtab");
	   $("#action_item").removeClass("selectedtab");
	   $("#attachments").removeClass("selectedtab");
	   $("#link").removeClass("selectedtab");
	   $("#csr").removeClass("selectedtab");
	   $("#view").removeClass("selectedtab");
           $("#print").removeClass("selectedtab");
	 
      });
     
     
  </script>   
    <?php  
    echo $this->Form->create('add_job_well_form', array('controller' => 'Jobs','name'=>"add_job_well_form", 'id'=>"add_job_well_form", 'method'=>'post','class'=>'adminform'));
    echo $this->Form->input('id', array('type'=>'hidden', 'id'=>'id', 'value'=>$id));
 ?>

 <h2><?php echo $heading; ?>&nbsp;&nbsp;(<?php echo $report_number; ?>)<span class="textcmpul">Field marked with * are compulsory  </span></h2>
 <br/>
     
<label><?PHP echo __("Well Name:");?></label><?PHP echo $this->Form->input('well_name', array('type'=>'text', 'id'=>'well_name','name'=>'well_name','value'=>$well_name,'label' => false,'maxlength'=>30,'div' => false)); ?>
<div class="clearflds"></div>
<label><?PHP echo __("Rig Name:");?></label><?PHP echo $this->Form->input('rig_name', array('type'=>'text', 'id'=>'rig_name','name'=>'rig_name','value'=>$rigname,'label' => false,'maxlength'=>30,'div' => false)); ?>&nbsp;<span class="textcmpul" id="rig_name_error"></span>
<div class="clearflds"></div>
<label><?PHP echo __("Fluid:");?></label>
<span id="fluid_section">
	 <select id="fluid_name" name="fluid_name">
	  <option value="0" >Select One</option>
	  <?php
	    for($w=0;$w<count($welldataList);$w++){?>
		   <option value="<?php echo $welldataList[$w]['Welldata']['id']; ?>" <?php if($fluid==$welldataList[$w]['Welldata']['id']){echo "selected";}else{} ?>><?php echo $welldataList[$w]['Welldata']['type']; ?></option>
	       
	   <?php } ?>
	</select>
 </span>		  
<div class="clearflds"></div>
<label><?PHP echo __("Depth:");?></label><?PHP echo $this->Form->input('depth', array('type'=>'text', 'id'=>'depth','name'=>'depth','value'=>$depth,'label' => false,'maxlength'=>30,'div' => false)); ?>&nbsp;m<span class="textcmpul" id="depth_error"></span>
<div class="clearflds"></div>
<label><?PHP echo __("Devi:");?></label><?PHP echo $this->Form->input('devi', array('type'=>'text', 'id'=>'devi','name'=>'devi','value'=>$devi,'label' => false,'maxlength'=>30,'div' => false)); ?>&nbsp; &deg;<span class="textcmpul" id="devi_error"></span>
<div class="clearflds"></div>
<label><?PHP echo __("BHTemp:");?></label><?PHP echo $this->Form->input('bhtemp', array('type'=>'text', 'id'=>'bhtemp','name'=>'bhtemp','value'=>$bhtemp,'label' => false,'maxlength'=>30,'div' => false)); ?>&nbsp;<sup>&deg;</sup>C<span class="textcmpul" id="bhtemp_error"></span>
<div class="clearflds"></div>
<label><?PHP echo __("SHTemp:");?></label><?PHP echo $this->Form->input('shtemp', array('type'=>'text', 'id'=>'shtemp','name'=>'shtemp','value'=>$shtemp,'label' => false,'maxlength'=>30,'div' => false)); ?>&nbsp;<sup>&deg;</sup>C<span class="textcmpul" id="shtemp_error"></span>
<div class="clearflds"></div>
<label><?PHP echo __("Density:");?></label><?PHP echo $this->Form->input('density', array('type'=>'text', 'id'=>'density','name'=>'density','value'=>$density,'label' => false,'maxlength'=>30,'div' => false)); ?>&nbsp;lb/gal<span class="textcmpul" id="density_error"></span>
<div class="clearflds"></div>
<label><?PHP echo __("WH Pres:");?></label><?PHP echo $this->Form->input('whpres', array('type'=>'text', 'id'=>'whpres','name'=>'whpres','value'=>$whpres,'label' => false,'maxlength'=>30,'div' => false)); ?>&nbsp;psi<span class="textcmpul" id="whpres_error"></span>
<div class="clearflds"></div>
<label><?PHP echo __("BH Pres:");?></label><?PHP echo $this->Form->input('bhpres', array('type'=>'text', 'id'=>'bhpres','name'=>'bhpres','value'=>$bhpres,'label' => false,'maxlength'=>30,'div' => false)); ?>&nbsp;psi<span class="textcmpul" id="bhpres_error"></span>
<div class="clearflds"></div>
<label><?PHP echo __("Status:");?></label>
<span id="fluid_section">
	 <select id="staus_name" name="staus_name">
	  <option value="0" >Select One</option>
	  <?php
	    for($w=0;$w<count($wellstatus);$w++){?>
		   <option value="<?php echo $wellstatus[$w]['WellStatus']['id']; ?>" <?php if($wellstatusval==$wellstatus[$w]['WellStatus']['id']){echo "selected";}else{} ?>><?php echo $wellstatus[$w]['WellStatus']['type']; ?></option>
	       
	   <?php } ?>
	</select>
 </span>
<div class="clearflds"></div>
<label><?PHP echo __("H₂S:");?></label><?PHP echo $this->Form->input('hts', array('type'=>'text', 'id'=>'hts','name'=>'hts','value'=>$hts,'label' => false,'maxlength'=>30,'div' => false)); ?>&nbsp;ppm<span class="textcmpul" id="hts_error"></span>
<div class="clearflds"></div>
<label><?PHP echo __("CO2:");?></label><?PHP echo $this->Form->input('cot', array('type'=>'text', 'id'=>'cot','name'=>'cot','value'=>$cot,'label' => false,'maxlength'=>30,'div' => false)); ?>&nbsp;ppm<span class="textcmpul" id="cot_error"></span>
<div class="clearflds"></div>
<div class="buttonpanel">
<span id="loader" style="float:left;font-size: 13px;"></span><input type="button" name="save" id="save" class="buttonsave" onclick="add_job_welldata();" value="<?php echo $button; ?>" />
</div>
<?php echo $this->Form->end(); ?>
</section>