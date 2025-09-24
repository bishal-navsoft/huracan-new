<?php
echo $ltype.'~'.$incident_summary.'~'.$immediate_cause;
if(count($couseList)>0){ ?>
~<select  id="immeditaet_sub_cus" name="immeditaet_sub_cus" >
              <option value="0">Select One</option>
        	      <?php for($i=0;$i<count($couseList);$i++){?>
			        <option value='<?php echo $couseList[$i]['ImmediateSubCause']['id']; ?>' <?php if($immediate_sub_cause==$couseList[$i]['ImmediateSubCause']['id']){echo "selected";}else{} ?>><?php echo $couseList[$i]['ImmediateSubCause']['type']; ?></option>
		       <?php } ?>
</select>
<?php }else{
	   echo '~no';
}
echo '~'.$comments;
echo '~';
if(count($remidialList)>0){
for($i=0;$i<count($remidialList);$i++){?>
    
    <tr id='<?php echo 'tr'.$remidialList[$i]['SqRemidial']['remedial_no'] ?>'>
        <td width="12%" align="left" valign="middle"  ><?php echo 'REM-'.$remidialList[$i]['SqRemidial']['id']; ?></td>
        <td width="78%" align="left" valign="middle"  ><?php echo $remidialList[$i]['SqRemidial']['remidial_closer_summary'] ?></td>
        <td width="10%" align="left" valign="middle" >
            <a href="javascript:void(0);" onclick="remove_child(<?php echo 'tr'.$remidialList[$i]['SqRemidial']['remedial_no'] ?>);">Remove</a>
        </td>
    </tr>
    
<?php }}
echo '~';
?>
     <tr id='tr-0'>
	      <td width="22%" align="left" valign="middle">
                            <select id="root_cause" name="root_cause"  onchange="add_root_cause('root_cause',0)">
	                                  <option value="0">Select One</option>	  
	                                   <?php for($i=0;$i<count($zeroParrent);$i++){ ?>
                                           <option value="<?php echo $zeroParrent[$i]['RootCause']['id']; ?>" <?php if($zeroParrentIdIndex==$zeroParrent[$i]['RootCause']['id']){echo "selected";}else{} ?>><?php echo $zeroParrent[$i]['RootCause']['type']; ?></option>
	                               <?php } ?>	    
	                    </select>
              </td>
              <td  width="68%"></td>
              <td width="10%" align="right" valign="middle" >&nbsp;</td>
      </tr>

<?php
if(count($rootCuseListVal)>0){
      
for($i=0;$i<count($rootCuseListVal);$i++)
{
	      
	      if($rootCuseListVal[$i])
	      {
				
			    ?>
			    
			    <tr id='<?php echo 'tr-'.$rootCuseList[$i];?>'>
                                            <td width="22%" align="left" valign="middle">
	                                                  <select id="<?php echo 'root_cause-'.$rootCuseList[$i];?>" name="<?php echo 'root_cause-'.$rootCuseList[$i];?>" onchange="add_root_cause('<?php echo 'root_cause-'.$rootCuseList[$i]; ?>',<?php echo $rootCuseList[$i];?>)">
                                                                        <option value="0">Select One</option>	  
										     <?php for($j=0;$j<count($rootCuseListVal[$i]);$j++){ ?>
										                  <option value="<?php echo $rootCuseListVal[$i][$j]['RootCause']['id'];?>" <?php if($rootCuseListVal[$i][$j]['RootCause']['id']==$rootCuseList[$i+1]){echo "selected";}else{} ?>><?php echo $rootCuseListVal[$i][$j]['RootCause']['type'];?></option>
										      <?php } ?>
							   </select>
	                                     </td>
	                                     <td  width="68%"></td>
	                                     <td width="10%" align="right" valign="middle" >&nbsp;</td>
                           </tr>
			    
	      <?php		   
	      }
}
}else{
?>
   
	      
<?php	      
}
echo '~'.$rootCuseList[0];

?>