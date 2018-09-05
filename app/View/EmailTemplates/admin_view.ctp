<div class="emailTemplates view">
<h2><?php echo __('Email Template'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($emailTemplate['EmailTemplate']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('From Name'); ?></dt>
		<dd>
			<?php echo h($emailTemplate['EmailTemplate']['from_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('From Email'); ?></dt>
		<dd>
			<?php echo h($emailTemplate['EmailTemplate']['from_email']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('To'); ?></dt>
		<dd>
			<?php echo h($emailTemplate['EmailTemplate']['to']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cc'); ?></dt>
		<dd>
			<?php echo h($emailTemplate['EmailTemplate']['cc']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Bcc'); ?></dt>
		<dd>
			<?php echo h($emailTemplate['EmailTemplate']['bcc']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Subject'); ?></dt>
		<dd>
			<?php echo h($emailTemplate['EmailTemplate']['subject']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Alias'); ?></dt>
		<dd>
			<?php echo h($emailTemplate['EmailTemplate']['alias']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($emailTemplate['EmailTemplate']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Status'); ?></dt>
		<dd>
			<?php echo h($emailTemplate['EmailTemplate']['status']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($emailTemplate['EmailTemplate']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($emailTemplate['EmailTemplate']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Email Template'), array('action' => 'edit', $emailTemplate['EmailTemplate']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Email Template'), array('action' => 'delete', $emailTemplate['EmailTemplate']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $emailTemplate['EmailTemplate']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Email Templates'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Email Template'), array('action' => 'add')); ?> </li>
	</ul>
</div>
