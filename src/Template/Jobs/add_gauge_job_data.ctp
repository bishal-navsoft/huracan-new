<?php echo $this->Html->css('calender')
?>
 <script language="javascript" type="text/javascript">

function add_gyro_data()
{

	    var report_no ='<?php echo $reportno;?>';
            var gauge_type=document.getElementById('gauge_type').value ;
	    var press_range=document.getElementById('press_range').value ;
	    var manufacture=document.getElementById('manufacture').value ;
	    var tec_cable=document.getElementById('tec_cable').value ;
	    var ysplitre=document.getElementById('ysplitre').value ;
	    var whoconnector=document.getElementById('whoconnector').value ;
	    var sau=document.getElementById('sau').value ; 
  
	    
	    
	       
	    var dataStr = $("#add_gaugejob_form").serialize();
            var rootpath='<?php echo $this->webroot ?>';
            document.getElementById('loader').innerHTML='<img src="<?php echo $this->webroot; ?>img/loader.gif" />';	
              $.ajax({
			  type: "POST",
			  url: rootpath+"Jobs/gaugeprocess/",
			  data:"data="+dataStr+"&report_no=<?php echo $reportno; ?>&gauge_type="+gauge_type+"&press_range="+press_range+"&manufacture="+manufacture+"&tec_cable="+tec_cable+"&ysplitre="+ysplitre+"&whoconnector="+whoconnector+"&sau="+sau+"&gauge_no=<?php echo $countGouge; ?>",
			  
			  success: function(res)
			  {
              
	         	    var resval=res.split("~");
			      if(resval[0]=='fail'){
				   document.getElementById('loader').innerHTML='<font color="red">Please try again</font>';  
	                     }else if(resval[0]=='add'){
				   document.getElementById('loader').innerHTML='<font color="green">Gauge job data added successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Jobs/add_gauge_job_data/<?php echo base64_encode($reportno); ?>/'+resval[1];
				   
                             }else if(resval[0]=='update'){
				   document.getElementById('loader').innerHTML='<font color="green">Gauge job data updated successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Jobs/add_gauge_job_data/<?php echo base64_encode($reportno); ?>/'+resval[1];
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
	        $("#gauge_data").addClass("selectedtab");
	        $("#action_item").removeClass("selectedtab");
	        $("#attachments").removeClass("selectedtab");
	        $("#link").removeClass("selectedtab");
	        $("#csr").removeClass("selectedtab");
	        $("#view").removeClass("selectedtab");
                $("#print").removeClass("selectedtab");
	      
	   });
     
      </script>
     
     <?php
        

   


    echo $this->Form->create('add_gaugejob_form', array('controller' => 'Jobs','name'=>"add_gaugejob_form", 'id'=>"add_gaugejob_form", 'method'=>'post','class'=>'adminform'));
    echo $this->Form->input('id', array('type'=>'hidden', 'id'=>'id', 'value'=>$id));
 ?>

 <h2><?php echo $heading; ?><span class="textcmpul">Field marked with * are compulsory  </span></h2>
 <br/>
 <span><label><?PHP echo __("Gauge No:");?></label><label><?php echo $countGouge; ?></label></span>
 <div class="clearflds"></div>
<label><?PHP echo __("Gauge SN#:");?></label><?PHP echo $this->Form->input('gauge_sn', array('type'=>'text', 'id'=>'gauge_sn','value'=>$gauge_sn,'label' => false,'maxlength'=>30,'div' => false)); ?>
		
<div class="clearflds"></div>
<label><?PHP echo __("Gauge Type:");?></label>
         <span id="gauge_type_section">
			    <select id="gauge_type" name="gauge_type">	
                             <?php for($i=0;$i<count($gaugetype);$i++){?>
			     <option value="<?php echo $gaugetype[$i]['GaugeType']['id']; ?>" <?php if($gt==$gaugetype[$i]['GaugeType']['id']){echo "selected";}else{} ?>><?php echo $gaugetype[$i]['GaugeType']['type']; ?></option>
			     <?php } ?>
			     </select>
		             
		</span>
<div class="clearflds"></div>
<label><?PHP echo __("Press Range:");?></label>
         <span id="press_range_section">
			    <select id="press_range" name="press_range">	
                             <?php for($i=0;$i<count($presrange);$i++){?>
			     <option value="<?php echo $presrange[$i]['PressRange']['id']; ?>" <?php if($pr==$presrange[$i]['PressRange']['id']){echo "selected";}else{} ?>><?php echo $presrange[$i]['PressRange']['type']; ?></option>
			     <?php } ?>
			     </select>
		             
		</span>
<div class="clearflds"></div>
<label><?PHP echo __("Temp Range:");?></label>
         <span id="temp_range_section">
			    <select id="temp_range" name="temp_range">	
                             <?php for($i=0;$i<count($temprange);$i++){?>
			     <option value="<?php echo $temprange[$i]['TempRange']['id']; ?>" <?php if($tr==$temprange[$i]['TempRange']['id']){echo "selected";}else{} ?>><?php echo $temprange[$i]['TempRange']['type']; ?></option>
			     <?php } ?>
			     </select>
		             
		</span>
<div class="clearflds"></div>
<label><?PHP echo __("Manufacturer:");?></label>
         <span id="manufacturer_section">
			    <select id="manufacture" name="manufacture">	
                             <?php for($i=0;$i<count($manufacture);$i++){?>
			     <option value="<?php echo $manufacture[$i]['Manufacture']['id']; ?>" <?php if($mf==$manufacture[$i]['Manufacture']['id']){echo "selected";}else{} ?>><?php echo  $manufacture[$i]['Manufacture']['type']; ?></option>
			     <?php } ?>
			     </select>
		             
		</span>
<div class="clearflds"></div>
<label><?PHP echo __("Gauge Set Depth:");?></label><?PHP echo $this->Form->input('gauge_set_depth', array('type'=>'text', 'id'=>'gauge_set_depth','value'=>$gauge_set_depth,'label' => false,'maxlength'=>30,'div' => false)); ?>
<div class="clearflds"></div>

<label><?PHP echo __("TEC Cable:");?></label>
           <span id="tec_section">
			    <select id="tec_cable" name="tec_cable">	
                             <?php for($i=0;$i<count($techCableDetail);$i++){?>
			     <option value="<?php echo $techCableDetail[$i]['TecCable']['id']; ?>" <?php if($tc==$techCableDetail[$i]['TecCable']['id']){echo "selected";}else{} ?>><?php echo  $techCableDetail[$i]['TecCable']['type']; ?></option>
			     <?php } ?>
			     </select>
		             
		</span>
<div class="clearflds"></div>	   
<label><?PHP echo __("Y-Splitter:");?></label>
           <span id="splitter_section">
			    <select id="ysplitre" name="ysplitre">	
                             <?php for($i=0;$i<count($ysplitter);$i++){?>
			     <option value="<?php echo $ysplitter[$i]['YSplitter']['id']; ?>" <?php if($ys==$ysplitter[$i]['YSplitter']['id']){echo "selected";}else{} ?>><?php echo  $ysplitter[$i]['YSplitter']['type']; ?></option>
			     <?php } ?>
			     </select>
		             
		</span>	
<div class="clearflds"></div>
<label><?PHP echo __("WHO Connector:");?></label>
           <span id="tec_section">
			    <select id="whoconnector" name="whoconnector">	
                             <?php for($i=0;$i<count($whoconnector);$i++){?>
			     <option value="<?php echo $whoconnector[$i]['WhoConnector']['id']; ?>" <?php if($wc==$whoconnector[$i]['WhoConnector']['id']){echo "selected";}else{} ?>><?php echo  $whoconnector[$i]['WhoConnector']['type']; ?></option>
			     <?php } ?>
			     </select>
		             
		</span>	 
<div class="clearflds"></div>
<label><?PHP echo __("SAU:");?></label>
           <span id="sau_section">
			    <select id="sau" name="sau">	
                             <?php for($i=0;$i<count($sauDetail);$i++){?>
			     <option value="<?php echo $sauDetail[$i]['Sau']['id']; ?>" <?php if($su==$sauDetail[$i]['Sau']['id']){echo "selected";}else{} ?>><?php echo  $sauDetail[$i]['Sau']['type']; ?></option>
			     <?php } ?>
			     </select>
		             
		</span>	 
<div class="clearflds"></div>

<label><?PHP echo __("Comments:");?></label><?PHP echo $this->Form->input('comments', array('type'=>'textarea', 'id'=>'comments','value'=>$comments,'label' => false,'div' => false)); ?>
<div class="clearflds"></div>
	 
		  
<?php echo $this->Form->end(); ?>
<div class="buttonpanel">
 <span id="loader" style="float:left;font-size: 13px;"></span><input type="button" name="save" id="save" class="buttonsave" onclick="add_gyro_data();" value="<?php echo $button; ?>" />
</div>
</section>
