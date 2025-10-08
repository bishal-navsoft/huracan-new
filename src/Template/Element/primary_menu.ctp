<?php
$path = $this->Url->build('/'); // Base path

// Ensure arrays exist
$admin_menus_parrentdata = $admin_menus_parrentdata ?? [];
$admin_menus_children = $admin_menus_children ?? [];
?>

<script type="text/javascript">
function redirection(rdroot) {
    var path = '<?= $path ?>';
    document.location = path + rdroot;
}
</script>

<div class="stepBase">
    <nav>
        <?php if (!empty($admin_menus_parrentdata)) : ?>
            <ul class="primary-menu">
                <?php foreach ($admin_menus_parrentdata as $parent) :
                    // Make sure parent exists
                    if (!isset($parent[0]['AdminMenu'])) continue;
                    $parentMenu = $parent[0]['AdminMenu'];

                    // Safely get children
                    $children = $admin_menus_children[$parentMenu['id']] ?? [];
                ?>
                    <li>
                        <a href="javascript:void(0);"><?= h($parentMenu['menu_name']) ?></a>

                        <?php if (!empty($children)) : ?>
                            <div class="dropdown_box">
                                <div class="innerdrop">
                                    <ul>
                                        <?php foreach ($children as $childGroup) :
                                            // Ensure child group is array
                                            if (!is_array($childGroup)) continue;

                                            foreach ($childGroup as $child) :
                                                if (!isset($child['AdminMenu'])) continue;
                                                $childMenu = $child['AdminMenu'];
                                        ?>
                                            <li>
                                                <a href="<?= h($path . $childMenu['url']) ?>">
                                                    <?= h($childMenu['menu_name']) ?>
                                                </a>
                                            </li>
                                        <?php endforeach; endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        <?php endif; ?>

                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </nav>
</div>
