<label><?PHP echo __("Sub Category:");?></label>
<select id="sub_category" name="sub_category" >
			     <option value="0">Select One</option>
                             <?php for($i=0;$i<count($sqdamagesubdetail);$i++){?>
			     <option value="<?php echo $sqdamagesubdetail[$i]['SqDamage']['id']; ?>" ><?php echo $sqdamagesubdetail[$i]['SqDamage']['type']; ?></option>
		    	     <?php } ?>
  </select>
<div class="clearflds"></div>