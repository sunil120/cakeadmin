<form method="post" name="updateModulePermissions" action="<?php echo $this->webroot . '/permission/save'; ?>" class="mkjax" id="updateModulePermissions">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="no-margin pull-left">Module Permissions</h4>
            <h4 class="no-margin pull-right checkbox">
                <?php if (!empty($userId)) { ?>
                    <a href="<?php echo $this->webroot . 'permission/clearpermissions/' . $userId; ?>" class="mkjax btn btn-flat btn-success">Reset Permissions</a>
                <?php } ?>
                <input type="checkbox" id="expand-collapse-all-modules" class="exclude-bulk-check" /> <label for="expand-collapse-all-modules">Expand/Collapse All</label>
                &nbsp;&nbsp;<input type="checkbox" id="select-deselect-all-modules" /> <label for="select-deselect-all-modules">Select/De-select All</label>
            </h4>
            <div class="clear"></div>
        </div>
        <div class="panel-body">
            <?php if (!empty($userId)) { ?>
                <input type="hidden" name="userId" value="<?php echo $userId; ?>">
            <?php } else if (!empty($roleId)) { ?>
                <input type="hidden" name="roleId" value="<?php echo $roleId; ?>">
            <?php } ?>
            <div class="panel-group" id="accordion">
                <?php
                $i = 1;
                foreach ($modules as $module) {
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="font-size:14px">
                                <a class="block-collapse module-title" data-parent="#accordion" data-toggle="collapse" href="#child-link-<?php echo $i; ?>"><i class="fa <?php echo $module['module_icon']; ?>"></i> <?php echo $module['module_name']; ?>
                                </a>
                            </h3>
                        </div>
                        <div id="child-link-<?php echo $i; ?>" class="collapse parent-link">
                            <div class="panel-body">
                                <?php
                                if (!empty($module['sub'])) {
                                    $totalSubModules = 0;
                                    $leastPermission = 0;
                                    foreach ($module['sub'] as $tempSubModule) {
                                        $permittedTempSmActions = json_decode($tempSubModule['permissions']);
                                        if (count($permittedTempSmActions) > 0) {
                                            $leastPermission++;
                                        }
                                        $totalSubModules++;
                                    }
                                    $permitAllChecked = ($totalSubModules == $leastPermission) ? 'ALL' : '';
                                    $checkAllId = 'check-all-' . rand(111111, 9999999);
                                    ?>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="hidden" name="menu[<?php echo $module['id'] ?>][permissions][]" value="<?php echo $permitAllChecked; ?>" class="parent-link-check-all" <?php echo $permitAllChecked; ?>>
                                        <span class="checkbox">
                                            <input type="checkbox" value="" id="<?php echo $checkAllId; ?>" class="check-all-submodules"> <label for="<?php echo $checkAllId; ?>">Check All</label>
                                        </span>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="child-links">
                                        <?php
                                        $smCnt = 1;
                                        foreach ($module['sub'] as $subModule) {
                                            $permittedSmActions = (!empty($subModule['permissions'])) ? json_decode($subModule['permissions']) : array();
                                            $smAllActionChecked = (in_array('ALL', $permittedSmActions)) ? 'checked="checked"' : '';
                                            $allRightsCheckId = 'all-rights-' . rand(111111, 9999999);
                                            ?>
                                            <div class="col-md-6 col-sm-6 child-link">
                                                <div class="the-box bg-dark no-border child-menu-permission">
                                                    <div class="pull-left">
                                                        <i class="fa <?php echo $subModule['module_icon']; ?>"></i> <?php echo $subModule['module_name']; ?>
                                                    </div>
                                                    <div class="pull-right">
                                                        <span class="checkbox">
                                                            <input type="checkbox" id="<?php echo $allRightsCheckId; ?>" name="menu[<?php echo $subModule['id'] ?>][permissions][]" value="ALL" class="child-link-check-all" <?php echo $smAllActionChecked; ?>>
                                                            <label for="<?php echo $allRightsCheckId; ?>">All Rights</label>
                                                        </span>
                                                    </div>
                                                    <div class="clear"></div>
                                                </div>
                                                <?php
                                                if (!empty($subModule['actions'])) {
                                                    echo '<div>';
                                                    $subModuleActions = json_decode($subModule['actions'], true);
                                                    $actionCnt = 1;
                                                    foreach ($subModuleActions as $smActionKey => $smActionValue) {
                                                        $smActionChecked = (in_array($smActionKey, $permittedSmActions)) ? 'checked="checked"' : '';
                                                        $actionCheckId = 'action-' . rand(111111, 9999999);
                                                        ?>
                                                        <div class="col-md-4 col-sm-4 menu-action">
                                                            <div class="checkboxes">
                                                                <span class="checkbox">
                                                                    <input type="checkbox" id="<?php echo $actionCheckId; ?>" name="menu[<?php echo $subModule['id'] ?>][permissions][]" value="<?php echo $smActionKey; ?>" class="child-link-check-action" <?php echo $smActionChecked; ?>>
                                                                    <label for="<?php echo $actionCheckId; ?>"><?php echo $smActionValue; ?></label>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        if ($actionCnt % 3 == 0) {
                                                            echo '<div class="clear"></div>';
                                                        }
                                                        $actionCnt++;
                                                    }
                                                    echo '</div>';
                                                }
                                                ?>
                                            </div>
                                            <?php
                                            if ($smCnt % 2 == 0) {
                                                echo '<div class="clear"></div>';
                                            }

                                            $smCnt++;
                                        }
                                        echo '</div>';
                                    } else {

                                        if (!empty($module['actions'])) {
                                            $permittedModuleActions = (!empty($module['permissions'])) ? json_decode($module['permissions']) : array();
                                            $moduleActions = json_decode($module['actions'], true);
                                            $moduleAllActionChecked = (in_array('ALL', $permittedModuleActions)) ? 'checked="checked"' : '';

                                            echo '<div class="child-link">';

                                            $moduleActionCnt = 1;
                                            foreach ($moduleActions as $moduleActionKey => $moduleActionValue) {
                                                $linkActionChecked = (in_array($moduleActionKey, $permittedModuleActions)) ? 'checked="checked"' : '';
                                                $linkActionCheckId = 'link-action-' . rand(111111, 9999999);
                                                ?>
                                                <div class="col-md-3 col-sm-3 menu-action">
                                                    <div class="checkboxes">
                                                        <span class="checkbox">
                                                            <input type="checkbox" id="<?php echo $linkActionCheckId; ?>" name="menu[<?php echo $module['id'] ?>][permissions][]" value="<?php echo $moduleActionKey; ?>" class="child-link-check-action" <?php echo $linkActionChecked; ?>>
                                                            <label for="<?php echo $linkActionCheckId; ?>"><?php echo $moduleActionValue; ?></label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <?php
                                                $moduleActionCnt++;
                                            }
                                            echo '</div>';
                                        } else {
                                            echo '<div class="child-link">';
                                            echo '<div class="col-md-3 col-sm-3 menu-action">
                                        <div class="checkboxes">
                                            <input type="checkbox" name="menu[' . $module['id'] . '][permissions][]" value="ALL" class="child-link-check-all">All Rights
                                        </div>
                                    </div>';
                                            echo '</div>';
                                        }
                                    }
                                    ?>
                                </div><!-- /.panel-body -->
                            </div><!-- /.collapse in -->
                        </div><!-- /.panel panel-default -->
                        <?php
                        $i++;
                    }
                    ?>
                </div>
            </div>
            <div class="panel-footer">
                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-flat">
                        <i class="icomoon-disk"></i> Save
                    </button>
                    <a class="btn btn-flat btn-default"
                       href="<?php echo $this->Session->read('http_referer'); ?>"
                       title="Cancel">Cancel</a>
                </div>
            </div>
        </div>
</form>