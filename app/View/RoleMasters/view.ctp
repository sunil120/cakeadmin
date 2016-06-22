<div class="roleMasters view">
<h2><?php echo __('Role Master'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($roleMaster['RoleMaster']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Role Name'); ?></dt>
		<dd>
			<?php echo h($roleMaster['RoleMaster']['role_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($roleMaster['RoleMaster']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Is Deleted'); ?></dt>
		<dd>
			<?php echo h($roleMaster['RoleMaster']['is_deleted']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date Created'); ?></dt>
		<dd>
			<?php echo h($roleMaster['RoleMaster']['date_created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date Modified'); ?></dt>
		<dd>
			<?php echo h($roleMaster['RoleMaster']['date_modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Role Master'), array('action' => 'edit', $roleMaster['RoleMaster']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Role Master'), array('action' => 'delete', $roleMaster['RoleMaster']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $roleMaster['RoleMaster']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Role Masters'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Role Master'), array('action' => 'add')); ?> </li>
	</ul>
</div>
