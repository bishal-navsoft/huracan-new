<?php
    echo $this->Form->create('add_report_personnel_form', array('controller' => 'Reports','name'=>"add_report_personnel_form", 'id'=>"add_report_personnel_form", 'method'=>'post','class'=>'adminform'));
    echo $this->Form->input('id', array('type'=>'hidden', 'id'=>'id', 'value'=>$id));
     echo $this->Form->input('pid', array('type'=>'hidden', 'pid'=>'id', 'value'=>$pid));
 ?>

 <h2><?php echo $heading; ?>&nbsp; &nbsp;(<?php echo $report_number;?>)<span class="textcmpul">Field marked with * are compulsory  </span></h2>
 <br/>
     
<label><?PHP echo __("Name:");?><span>*</span></label>

     <span id="personal_data_section">
     <select id="personal_data" name="personal_data" > <!--onchange="retrive_data();"-->
       <option value="0">Select One</option>	  
      <?php for($i=0;$i<count($userDetail);$i++){?>
      <option value="<?php echo $userDetail[$i]['AdminMaster']['position_seniorty']; ?>" <?php if($userDetail[$i]['AdminMaster']['id']==$person){ echo "selected";} ?>><?php echo $userDetail[$i]['AdminMaster']['first_name']." ".$userDetail[$i]['AdminMaster']['last_name']; ?></option>
      <?php } ?>
      </select>
     </span><span id="personal_data_error" class="textcmpul"></span>
<div class="clearflds"></div>
<span id="postion_seniorty" <?php echo $styledisplay; ?> >
         <!--<label><?PHP //echo __("Position:");?></label>-->
         <!--<label><?php //echo $roll_name; ?></label>-->
         <!--<div class="clearflds"></div>-->  
         <!--<label><?PHP //echo __("Seniority:");?></label>-->
         <!--<label><?php //echo $snr; ?></label>-->
         <!--<div class="clearflds"></div>-->
</span>
<div class="clearflds"></div>
<label><?PHP echo __("Hrs Last Sleep:");?></label>
<select name="last_sleep" id="last_sleep">
<?php

for($i = 0; $i <= 23; $i++){
     if($i<10){
	  $hr="0$i";
	  
     }else{
	$hr="$i";  
     }
     ?>
      <option value="<?php echo $hr; ?>" <?php if($time_last_sleep==$hr){echo "selected";}else{} ?>><?php echo $hr; ?></option>
    <?php  
    
}
?>

</select>

<div class="clearflds"></div>
<label><?PHP echo __("Hrs since Sleep:");?></label>
<select name="since_sleep" id="since_sleep">
<?php

for($i = 0; $i <= 23; $i++){
     if($i<10){
	  $hr="0$i";
	  
     }else{
	$hr="$i";  
     }
     ?>
      <option value="<?php echo $hr; ?>" <?php if($time_since_sleep==$hr){echo "selected";}else{} ?>><?php echo $hr; ?></option>
    <?php  
    
}
?>
</select>
<div class="clearflds"></div>

<div class="buttonpanel">
<span id="loader" style="float:left;font-size: 13px;"></span><input type="button" name="save" id="save" class="buttonsave" onclick="add_report_personal();" value="<?php echo $button; ?>" />
</div>
<?php echo $this->Form->end(); ?>