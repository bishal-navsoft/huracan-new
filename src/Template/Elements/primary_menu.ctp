<script language="javascript" type="text/javascript">
    function redirection(rdroot){
         var path='<?php echo $this->webroot; ?>';
          document.location=path+rdroot;        
    }
    
    
</script>

<?php

for($m=0;$m<count($admin_menus_parrentdata);$m++){
    if(count($admin_menus_parrentdata[$m])>0){
    ?>
    <ul>
      <li><a href="javascript:void(0);" ><?php echo $admin_menus_parrentdata[$m][0]['AdminMenu']['menu_name']; ?></a>
        <?php
        if(isset($admin_menus_children[$admin_menus_parrentdata[$m][0]['AdminMenu']['id']]))
         if(count($admin_menus_children[$admin_menus_parrentdata[$m][0]['AdminMenu']['id']])>0){?>
         
            <div class="dropdown_box">
            <div class="innerdrop">
            <ul>
              <?php
                  for($sm=0;$sm<count($admin_menus_children[$admin_menus_parrentdata[$m][0]['AdminMenu']['id']]);$sm++){?>

          <?php
          
          for($n=0;$n<count($admin_menus_children[$admin_menus_parrentdata[$m][0]['AdminMenu']['id']][$sm]);$n++){
            ?>
          <li><a href="<?php echo $this->webroot.$admin_menus_children[$admin_menus_parrentdata[$m][0]['AdminMenu']['id']][$sm]['AdminMenu']['url']; ?>"><?php echo $admin_menus_children[$admin_menus_parrentdata[$m][0]['AdminMenu']['id']][$sm]['AdminMenu']['menu_name']; ?></a></li>
            
         <?php }
          
          ?>
            
            
                 <?php  }
                 
              
              ?>
              
            </ul>
            </div>
            </div>
          
         <?php }
         
        ?>
      
      </li>
    </ul>
    
<?php }}  ?>
