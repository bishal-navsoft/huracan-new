          <tr id='tr-<?php echo $parrentid; ?>'>
            <td width="22%" align="left" valign="middle">
	       <select id="<?php echo  'root_cause-'.$parrentid; ?>" name="<?php echo  'root_cause-'.$parrentid; ?>"  onchange="add_root_cause('<?php echo 'root_cause-'.$parrentid; ?>',<?php echo $parrentid; ?>)">
                 <option value="0">Select One</option>	  
		    <?php for($i=0;$i<count($rootChildCauseData);$i++){?>
<option value="<?php echo $rootChildCauseData[$i]['RootCause']['id']; ?>" ><?php echo $rootChildCauseData[$i]['RootCause']['type']; ?></option>
		    <?php } ?>
              </select>
	    </td>
	    <td id="error_msg" width="68%" class="textcmpul" style="color: red;"></td>
	   <td width="10%" align="right" valign="middle" >&nbsp;</td>
          </tr>