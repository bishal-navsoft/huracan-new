<?php
	    echo $this->Form->create('add_report_client_data_form', array('controller' => 'Reports','name'=>"add_report_client_data_form", 'id'=>"add_report_client_data_form", 'method'=>'post','class'=>'adminform'));
	    echo $this->Form->input('id', array('type'=>'hidden', 'id'=>'id', 'value'=>$id));
 ?>

    <h2><?php echo $heading; ?>&nbsp; <span class="textcmpul">Field marked with * are compulsory  </span></h2>
    <br/>
     
    <label><?PHP echo __("Name:");?><span>*</span></label><lable><?php echo $clientreviewer; ?></lable>
    <div class="clearflds"></div>
    <label><?PHP echo __("Remark:");?></label> <?PHP echo $this->Form->input('client_summary', array('type'=>'textarea', 'id'=>'client_summary','value'=>$client_summary,'label' => false,'div' => false)); ?>
    <div class="clearflds"></div>
    <label><?PHP echo __("Date:");?></label><input type="text" id="close_date" name="close_date" readonly="readonly" onclick="displayDatePicker('close_date',this);" value="<?php echo $close_date; ?>" /><span class="textcmpul" id="close_date_error"></span>
   <div class="clearflds"></div>
   <div class="buttonpanel">
   <span id="loader" style="float:left;font-size: 13px;"></span><input type="button" name="save" id="save" class="buttonsave" onclick="add_report_feedback();" value="<?php echo $button; ?>" />
</div>
   <?php echo $this->Form->end(); ?>
