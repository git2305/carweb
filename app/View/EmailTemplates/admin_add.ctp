<div class="emailTemplates form">
<?php echo $this->Form->create('EmailTemplate'); ?>
	<fieldset>
		<legend><?php echo __('Admin Add Email Template'); ?></legend>
	<?php
		echo $this->Form->input('from_name');
		echo $this->Form->input('from_email');
		echo $this->Form->input('to');
		echo $this->Form->input('cc');
		echo $this->Form->input('bcc');
		echo $this->Form->input('subject');
		echo $this->Form->input('alias');
		echo $this->Form->input('description');
		echo $this->Form->input('status');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Email Templates'), array('action' => 'index')); ?></li>
	</ul>
</div>
