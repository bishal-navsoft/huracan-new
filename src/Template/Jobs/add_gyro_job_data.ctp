<?php echo $this->Html->css('calender')
?>
 <script language="javascript" type="text/javascript">

function add_gyro_data()
{

	    var report_no ='<?php echo $reportno;?>';
            var conveyance=document.getElementById('conveyance').value ;
	    var conveyance_by=document.getElementById('conveyance_by').value ;
	    var conveyance_type=document.getElementById('conveyance_type').value ;
	    var gyro_sn=document.getElementById('gyro_sn').value ;
  
	    
	    
	       
	    var dataStr = $("#add_gyrojob_form").serialize();
            var rootpath='<?php echo $this->webroot ?>';
            document.getElementById('loader').innerHTML='<img src="<?php echo $this->webroot; ?>img/loader.gif" />';	
              $.ajax({
			  type: "POST",
			  url: rootpath+"Jobs/gyroprocess/",
			  data:"data="+dataStr+"&report_no="+report_no+"&conveyance="+conveyance+"&conveyance_by="+conveyance_by+"&conveyance_type="+conveyance_type+"&gyro_sn="+gyro_sn+"&gyro_no=<?php echo $countGyro; ?>",
			  
			  success: function(res)
			  {
              
	         	    var resval=res.split("~");
			      if(resval[0]=='fail'){
				   document.getElementById('loader').innerHTML='<font color="red">Please try again</font>';  
	                     }else if(resval[0]=='add'){
				   document.getElementById('loader').innerHTML='<font color="green">Gyro job main added successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Jobs/add_gyro_job_data/<?php echo base64_encode($reportno); ?>/'+resval[1];
				   
                             }else if(resval[0]=='update'){
				   document.getElementById('loader').innerHTML='<font color="green">Gyro job updated successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Jobs/add_gyro_job_data/<?php echo base64_encode($reportno); ?>/'+resval[1];
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
	        $("#job_data").removeClass("selectedtab");
	        $("#gyro_job_data").addClass("selectedtab");
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
   


    echo $this->Form->create('add_gyrojob_form', array('controller' => 'Jobs','name'=>"add_gyrojob_form", 'id'=>"add_gyrojob_form", 'method'=>'post','class'=>'adminform'));
    echo $this->Form->input('id', array('type'=>'hidden', 'id'=>'id', 'value'=>$id));
 ?>

 <h2><?php echo $heading; ?><span class="textcmpul">Field marked with * are compulsory  </span></h2>
 <br/>
 <span><label><?PHP echo __("Gyro No:");?></label><label><?php echo $countGyro; ?></label></span>
 <div class="clearflds"></div>
<label><?PHP echo __("Gyro SN#:");?></label>
      
			   <span id="gyro_sn_section">
			    <select id="gyro_sn" name="gyro_sn">	
                             <?php for($i=0;$i<count($gyrosnDetail);$i++){?>
			     <option value="<?php echo $gyrosnDetail[$i]['GyroSn']['id']; ?>" <?php if($gsn==$gyrosnDetail[$i]['GyroSn']['id']){echo "selected";}else{} ?>><?php echo $gyrosnDetail[$i]['GyroSn']['type']; ?></option>
			     <?php } ?>
			      </select>
		             
		   </span>	
		
<div class="clearflds"></div>
<label><?PHP echo __("Conveyance:");?></label>
         <span id="conveyance_unit_section">
			    <select id="conveyance" name="conveyance">	
                             <?php for($i=0;$i<count($conveyance);$i++){?>
			     <option value="<?php echo $conveyance[$i]['Conveyance']['id']; ?>" <?php if($cid==$conveyance[$i]['Conveyance']['id']){echo "selected";}else{} ?>><?php echo $conveyance[$i]['Conveyance']['type']; ?></option>
			     <?php } ?>
			     </select>
		             
		</span>
<div class="clearflds"></div>
<label><?PHP echo __("Conveyance By:");?></label>
         <span id="conveyance_unit_section">
			    <select id="conveyance_by" name="conveyance_by">	
                             <?php for($i=0;$i<count($conveyed);$i++){?>
			     <option value="<?php echo $conveyed[$i]['Conveyed']['id']; ?>" <?php if($cby==$conveyed[$i]['Conveyed']['id']){echo "selected";}else{} ?>><?php echo $conveyed[$i]['Conveyed']['type']; ?></option>
			     <?php } ?>
			     </select>
		             
		</span>
<div class="clearflds"></div>
<label><?PHP echo __("Conveyance By:");?></label>
         <span id="conveyance_unit_section">
			    <select id="conveyance_type" name="conveyance_type">	
                             <?php for($i=0;$i<count($conveyanceType);$i++){?>
			     <option value="<?php echo $conveyanceType[$i]['ConveyanceType']['id']; ?>" <?php if($ct==$conveyed[$i]['Conveyed']['id']){echo "selected";}else{} ?>><?php echo $conveyanceType[$i]['ConveyanceType']['type']; ?></option>
			     <?php } ?>
			     </select>
		             
		</span>
<div class="clearflds"></div>

<label><?PHP echo __("Top Survey:");?></label><?PHP echo $this->Form->input('top_survey', array('type'=>'text', 'id'=>'top_survey','value'=>$top_survey,'label' => false,'maxlength'=>30,'div' => false)); ?>
<div class="clearflds"></div>
<label><?PHP echo __("Bottom Survey:");?></label><?PHP echo $this->Form->input('buttom_survey', array('type'=>'text', 'id'=>'buttom_survey','value'=>$buttom_survey,'label' => false,'maxlength'=>30,'div' => false)); ?>
<div class="clearflds"></div>
<label><?PHP echo __("Latitude:");?></label><?PHP echo $this->Form->input('latitude', array('type'=>'text', 'id'=>'latitude','value'=>$latitude,'label' => false,'maxlength'=>30,'div' => false)); ?>
<div class="clearflds"></div>
<label><?PHP echo __("Longitude:");?></label><?PHP echo $this->Form->input('longitude', array('type'=>'text', 'id'=>'longitude','value'=>$longitude,'label' => false,'maxlength'=>30,'div' => false)); ?>
<div class="clearflds"></div>
<label><?PHP echo __("Comment:");?></label><?PHP echo $this->Form->input('comments', array('type'=>'text', 'id'=>'comments','value'=>$comments,'label' => false,'maxlength'=>30,'div' => false)); ?>
<div class="clearflds"></div>
		 
		  
<?php echo $this->Form->end(); ?>
<div class="buttonpanel">
 <span id="loader" style="float:left;font-size: 13px;"></span><input type="button" name="save" id="save" class="buttonsave" onclick="add_gyro_data();" value="<?php echo $button; ?>" />
</div>
</section>
