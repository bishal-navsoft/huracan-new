<select  id="immeditaet_sub_cus" name="immeditaet_sub_cus" >
              <option value="0">Select One</option>
        	      <?php for($i=0;$i<count($couseList);$i++){?>
			        <option value='<?php echo $couseList[$i]['ImmediateSubCause']['id']; ?>'><?php echo $couseList[$i]['ImmediateSubCause']['type']; ?></option>
		       <?php } ?>
</select>