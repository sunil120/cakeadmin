<div class="stateMasters view">
<h2><?php echo __('State Master'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($stateMaster['StateMaster']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Country Master'); ?></dt>
		<dd>
			<?php echo $this->Html->link($stateMaster['CountryMaster']['country_master'], array('controller' => 'country_masters', 'action' => 'view', $stateMaster['CountryMaster']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('State Name'); ?></dt>
		<dd>
			<?php echo h($stateMaster['StateMaster']['state_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($stateMaster['StateMaster']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($stateMaster['StateMaster']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit State Master'), array('action' => 'edit', $stateMaster['StateMaster']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete State Master'), array('action' => 'delete', $stateMaster['StateMaster']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $stateMaster['StateMaster']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List State Masters'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New State Master'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Country Masters'), array('controller' => 'country_masters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Country Master'), array('controller' => 'country_masters', 'action' => 'add')); ?> </li>
	</ul>
</div>
