<label>Assign</label>
<div class="role_details">
<div class="role_top">
<div class="role_top01"><p><?php echo __('Menu\'s Name'); ?></p></div>
<div class="role_top02" style="width: 10%;"><p><?php echo __('All'); ?></p></div>
<div class="role_top02" style="width: 10%;"><p><?php echo __('View'); ?></p></div>
<div class="role_top02"><p><?php echo __('Add'); ?></p></div>
<div class="role_top02"><p><?php echo __('Edit'); ?></p></div>
<div class="role_top02" style="width: 11%;"><p><?php echo __('Block'); ?></p></div>
<div class="role_top02" style="width: 10%;"><p><?php echo __('Delete'); ?></p></div>
</div>
<div class="role_bot">
<?php $roleMenuCount = 1; //pr($roleGroupMenus);?>
<?php foreach($roleGroupMenus as $eachRoleGroupMenus): ?>
	<?php if(count($eachRoleGroupMenus['children']) > 0):
	//echo count($eachRoleGroupMenus['children']); ?>
		<div class="role_bot01 text_style">
			<p><strong><?php echo $this->Form->input("RolePermission.{$roleMenuCount}.admin_menu_id", array('type' => 'hidden', 'value' => $eachRoleGroupMenus['AdminMenu']['id'])); ?><?php echo __($eachRoleGroupMenus['AdminMenu']['menu_name']); ?></strong></p>
			<div class="clear"></div>
		</div>
		<?php foreach($eachRoleGroupMenus['children'] as $key => $eachRoleGroupMenusChild): ?>
		<?php $roleMenuCount++; ?>
		<?php 
		if((($key+1) % 2) == 0)
		{
			$className = 'role_bot02alt text_style';
		}
		else
		{
			$className = 'role_bot02 text_style';
		}
		?>
			<div class="<?php echo $className; ?>">
				<fieldset style="border: 0 none;">
				<div class="bot01"><p><?php echo __($eachRoleGroupMenusChild['AdminMenu']['menu_name']); ?></p></div>
				<div class="bot02">
<?php 
$checked = false;
if(isset($rolePermissions))
{
	foreach($rolePermissions as $eachRolePermission)
	{
		if($eachRolePermission['RolePermission']['admin_menu_id'] == $eachRoleGroupMenusChild['AdminMenu']['id'] && $eachRolePermission['RolePermission']['view'] == '1' && $eachRolePermission['RolePermission']['add'] == '1' && $eachRolePermission['RolePermission']['edit'] == '1' && $eachRolePermission['RolePermission']['block'] == '1' && $eachRolePermission['RolePermission']['delete'] == '1' )
		{
			$checked = true;
		}
	}
}
?>
					<?php echo $this->Form->checkbox("RolePermission.{$roleMenuCount}.all", array('label' => false, 'div' => false, 'class' => 'menu_check_all', 'checked' => $checked)); ?>
				</div>
				<div class="bot03">
				<?php 
					$checked = false;
					if(isset($rolePermissions))
					{
						foreach($rolePermissions as $eachRolePermission)
						{
							if($eachRolePermission['RolePermission']['admin_menu_id'] == $eachRoleGroupMenusChild['AdminMenu']['id'] && $eachRolePermission['RolePermission']['view'] == '1')
							{
								$checked = true;
							}
						}
					}
				?>
					<?php echo $this->Form->checkbox("RolePermission.{$roleMenuCount}.view", array('label' => false, 'div' => false, 'checked' => $checked)); ?>
				</div>
				<div class="bot02">
				<?php 
					$checked = false;
					if(isset($rolePermissions))
					{
						foreach($rolePermissions as $eachRolePermission)
						{
							if($eachRolePermission['RolePermission']['admin_menu_id'] == $eachRoleGroupMenusChild['AdminMenu']['id'] && $eachRolePermission['RolePermission']['add'] == '1')
							{
								$checked = true;
							}
						}
					}
					?>
					<?php echo $this->Form->checkbox("RolePermission.{$roleMenuCount}.add", array('label' => false, 'div' => false, 'checked' => $checked)); ?>
				</div>
				<div class="bot04">
				<?php 
					$checked = false;
					if(isset($rolePermissions))
					{
						foreach($rolePermissions as $eachRolePermission)
						{
							if($eachRolePermission['RolePermission']['admin_menu_id'] == $eachRoleGroupMenusChild['AdminMenu']['id'] && $eachRolePermission['RolePermission']['edit'] == '1')
							{
								$checked = true;
							}
						}
					}
					?>
					<?php echo $this->Form->checkbox("RolePermission.{$roleMenuCount}.edit", array('label' => false, 'div' => false, 'checked' => $checked)); ?>
				</div>
				<div class="bot05">
				<?php 
					$checked = false;
					if(isset($rolePermissions))
					{
						foreach($rolePermissions as $eachRolePermission)
						{
							if($eachRolePermission['RolePermission']['admin_menu_id'] == $eachRoleGroupMenusChild['AdminMenu']['id'] && $eachRolePermission['RolePermission']['block'] == '1')
							{
								$checked = true;
							}
						}
					}
					?>
					<?php echo $this->Form->checkbox("RolePermission.{$roleMenuCount}.block", array('label' => false, 'div' => false, 'checked' => $checked)); ?>
				</div>
				<div class="bot06">
				<?php 
					$checked = false;
					if(isset($rolePermissions))
					{
						foreach($rolePermissions as $eachRolePermission)
						{
							if($eachRolePermission['RolePermission']['admin_menu_id'] == $eachRoleGroupMenusChild['AdminMenu']['id'] && $eachRolePermission['RolePermission']['delete'] == '1')
							{
								$checked = true;
							}
						}
					}
					?>
					<?php echo $this->Form->checkbox("RolePermission.{$roleMenuCount}.delete", array('label' => false, 'div' => false, 'checked' => $checked)); ?>
						<?php echo $this->Form->input("RolePermission.{$roleMenuCount}.admin_menu_id", array('type' => 'hidden', 'value' => $eachRoleGroupMenusChild['AdminMenu']['id'])); ?>
				</div>
				</fieldset>
			</div>
		

		<?php foreach($eachRoleGroupMenusChild['children'] as $key => $eachRoleGroupMenusSubChild): ?>
		<?php if(count($eachRoleGroupMenusChild['children']) > 0): ?>
		<?php $roleMenuCount++; ?>	
			
			<?php 
		if((($key+1) % 2) == 0)
		{
			$className = 'role_bot02alt text_style';
		}
		else
		{
			$className = 'role_bot02 text_style';
		}
		?>
			<div class="<?php echo $className; ?>">
				<fieldset style="border: 0 none;">
				<div class="bot01" style="width: 30%; padding-left: 21px;"><p><?php echo __($eachRoleGroupMenusSubChild['AdminMenu']['menu_name']); ?></p></div>
				<div class="bot02">
<?php 
$checked = false;
if(isset($rolePermissions))
{
	foreach($rolePermissions as $eachRolePermission)
	{
		if($eachRolePermission['RolePermission']['admin_menu_id'] == $eachRoleGroupMenusSubChild['AdminMenu']['id'] && $eachRolePermission['RolePermission']['view'] == '1' && $eachRolePermission['RolePermission']['add'] == '1' && $eachRolePermission['RolePermission']['edit'] == '1' && $eachRolePermission['RolePermission']['block'] == '1' && $eachRolePermission['RolePermission']['delete'] == '1' )
		{
			$checked = true;
		}
	}
}
?>
					<?php echo $this->Form->input("RolePermission.{$roleMenuCount}.all", array('type' => 'checkbox', 'label' => false, 'div' => false, 'class' => 'menu_check_all', 'checked' => $checked)); ?>
				</div>
				<div class="bot03">
				<?php 
					$checked = false;
					if(isset($rolePermissions))
					{
						foreach($rolePermissions as $eachRolePermission)
						{
							if($eachRolePermission['RolePermission']['admin_menu_id'] == $eachRoleGroupMenusSubChild['AdminMenu']['id'] && $eachRolePermission['RolePermission']['view'] == '1')
							{
								$checked = true;
							}
						}
					}
				?>
					<?php echo $this->Form->input("RolePermission.{$roleMenuCount}.view", array('type' => 'checkbox', 'label' => false, 'div' => false, 'checked' => $checked)); ?>
				</div>
				<div class="bot02">
				<?php 
					$checked = false;
					if(isset($rolePermissions))
					{
						foreach($rolePermissions as $eachRolePermission)
						{
							if($eachRolePermission['RolePermission']['admin_menu_id'] == $eachRoleGroupMenusSubChild['AdminMenu']['id'] && $eachRolePermission['RolePermission']['add'] == '1')
							{
								$checked = true;
							}
						}
					}
					?>
					<?php echo $this->Form->input("RolePermission.{$roleMenuCount}.add", array('type' => 'checkbox', 'label' => false, 'div' => false, 'checked' => $checked)); ?>
				</div>
				<div class="bot04">
				<?php 
					$checked = false;
					if(isset($rolePermissions))
					{
						foreach($rolePermissions as $eachRolePermission)
						{
							if($eachRolePermission['RolePermission']['admin_menu_id'] == $eachRoleGroupMenusSubChild['AdminMenu']['id'] && $eachRolePermission['RolePermission']['edit'] == '1')
							{
								$checked = true;
							}
						}
					}
					?>
					<?php echo $this->Form->input("RolePermission.{$roleMenuCount}.edit", array('type' => 'checkbox', 'label' => false, 'div' => false, 'checked' => $checked)); ?>
				</div>
				<div class="bot05">
				<?php 
					$checked = false;
					if(isset($rolePermissions))
					{
						foreach($rolePermissions as $eachRolePermission)
						{
							if($eachRolePermission['RolePermission']['admin_menu_id'] == $eachRoleGroupMenusSubChild['AdminMenu']['id'] && $eachRolePermission['RolePermission']['block'] == '1')
							{
								$checked = true;
							}
						}
					}
					?>
					<?php echo $this->Form->input("RolePermission.{$roleMenuCount}.block", array('type' => 'checkbox', 'label' => false, 'div' => false, 'checked' => $checked)); ?>
				</div>
				<div class="bot06">
				<?php 
					$checked = false;
					if(isset($rolePermissions))
					{
						foreach($rolePermissions as $eachRolePermission)
						{
							if($eachRolePermission['RolePermission']['admin_menu_id'] == $eachRoleGroupMenusSubChild['AdminMenu']['id'] && $eachRolePermission['RolePermission']['delete'] == '1')
							{
								$checked = true;
							}
						}
					}
					?>
					<?php echo $this->Form->input("RolePermission.{$roleMenuCount}.delete", array('type' => 'checkbox', 'label' => false, 'div' => false, 'checked' => $checked)); ?>
						<?php echo $this->Form->input("RolePermission.{$roleMenuCount}.admin_menu_id", array('type' => 'hidden', 'value' => $eachRoleGroupMenusSubChild['AdminMenu']['id'])); ?>
				</div>
				</fieldset>
			</div>
			<?php endif; ?>
			<?php endforeach ?>
			
			
		<?php endforeach ?>
	<?php endif; ?>
<?php $roleMenuCount++; ?>	
<?php endforeach ?>
</div>
</div>
<script type="text/javascript">
function chkall()
{
	jQuery("INPUT[type='checkbox']").attr('checked', true);
}
function unchkall()
{
	jQuery("INPUT[type='checkbox']").attr('checked', false);
}
jQuery(function () 
{
	jQuery('.menu_check_all').click(function () {
		jQuery(this).parents('fieldset:eq(0)').find(':checkbox').attr('checked', this.checked);
	});
});
</script>
<?php 
unset($roleMenuCount);
unset($eachRoleGroupMenus);
unset($className);
unset($eachRoleGroupMenusChild);
unset($eachRoleGroupMenusSubChild);
unset($checked);
unset($eachRolePermission);
?>