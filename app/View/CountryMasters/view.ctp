<div class="countryMasters view">
<h2><?php echo __('Country Master'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($countryMaster['CountryMaster']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($countryMaster['CountryMaster']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Is Deleted'); ?></dt>
		<dd>
			<?php echo h($countryMaster['CountryMaster']['is_deleted']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Status'); ?></dt>
		<dd>
			<?php echo h($countryMaster['CountryMaster']['status']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Country Master'), array('action' => 'edit', $countryMaster['CountryMaster']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Country Master'), array('action' => 'delete', $countryMaster['CountryMaster']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $countryMaster['CountryMaster']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Country Masters'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Country Master'), array('action' => 'add')); ?> </li>
	</ul>
</div>
