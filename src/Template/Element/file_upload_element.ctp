<label>Description</label>
<?PHP echo $this->Form->input('attachment_description', array('type'=>'text', 'id'=>'attachment_description','name'=>'attachment_description','value'=>$description,'label' => false, 'class'=>'textclass', 'div' => false)); ?>
<div class="clearflds"></div>
<label><?PHP echo __("Upload File");?></label>
<div class="upload-here">
      <input type="file" size="24"  name="data[Suggestions][file_upload]" id="BrowserHidden" onchange="getElementById('FileField').value = getElementById('BrowserHidden').value;" />
      <div id="BrowserVisible"><input type="text" id="FileField" /></div><input type="button" name="save" id="save" class="buttonview" onclick="return uploadFile();" value="Upload" />
</div>
<div class="clearflds"></div>
<label>&nbsp;</label>
<div class="upload_filewrap">
      <span id="displayFile"></span>
      <div class="picnav"><a href="javascript:void(0);" id="del" <?php echo $attachmentstyle; ?>  onclick="return confirmFun(this);">Delete</a></div>
      <div class="clearupload"></div>
<div class="upload_success" id="image_upload_res"></div>
<div class="clear"></div>
</div>
<div class="clearflds"></div>
<?php echo $this->Form->input('hiddenFile', array('type'=>'hidden','label' => false,'id'=>'hiddenFile','name'=>'hiddenFile','value'=>$imagename)); ?>
 <iframe id="imgFrame" name="imgFrame" src="" style="width:100px;height:100px;border:1px solid #D5E278; display:none"></iframe>
<?php echo $this->Form->end(); ?>
<div class="buttonpanel">
<span id="loader" style="font-size: 13px;"></span> <input type="button" name="save" id="save" class="buttonsave" onclick="add_sg_attach();" value="<?php echo $button; ?>" />
</div>