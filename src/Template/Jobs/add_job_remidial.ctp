<?php echo $this->Html->css('calender') ?>
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
	        $("#action_item").addClass("selectedtab");
	        $("#attachments").removeClass("selectedtab");
	        $("#link").removeClass("selectedtab");
	        $("#csr").removeClass("selectedtab");
	        $("#view").removeClass("selectedtab");
                $("#print").removeClass("selectedtab");
	      
	   });
   
     
  </script>   

<?php 
    echo $this->Form->create('add_report_remidial_form', array('controller' => 'Jobs','name'=>"add_report_remidial_form", 'id'=>"add_report_remidial_form", 'method'=>'post','class'=>'adminform'));
    echo $this->Form->input('id', array('type'=>'hidden', 'id'=>'id', 'value'=>$id));
 ?>

 <h2><?php echo $heading; ?>&nbsp;&nbsp;<?php echo $report_number; ?><span class="textcmpul">Field marked with * are compulsory  </span></h2>
 <br/>
<?php echo $this->Element('remidial_element'); ?>
<div class="buttonpanel">
<span id="loader" style="float:left;font-size: 13px;"></span>
<span id="remidial_button_area" <?php echo $remidial_button_style; ?>>
<input type="button" name="save" id="save" class="buttonsave" onclick="add_report_remidial('<?php echo $this->webroot; ?>Jobs/remidialprocess/');" value="<?php echo $button; ?>" />
</span>
</div>
<?php echo $this->Form->end(); ?>
</section>