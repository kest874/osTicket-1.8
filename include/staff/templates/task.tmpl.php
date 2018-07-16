<?php

if (!$info['title'])
    $info['title'] = __('New Countermeasure');

$namespace = 'task.add';
if ($ticket)
    $namespace = sprintf('ticket.%d.task', $ticket->getId());

?>
<div id="task-form">
<h3 class="drag-handle"><?php echo $info['title']; ?></h3>
<b><a class="close" href="#"><i class="icon-remove-circle"></i></a></b>
<hr/>
<?php

if ($info['error']) {
    echo sprintf('<p id="msg_error">%s</p>', $info['error']);
} elseif ($info['warning']) {
    echo sprintf('<p id="msg_warning">%s</p>', $info['warning']);
} elseif ($info['msg']) {
    echo sprintf('<p id="msg_notice">%s</p>', $info['msg']);
} ?>
<div id="new-task-form" style="display:block;">
<form method="post" class="org" action="<?php echo $info['action'] ?: '#tasks/add'; ?>">
    <?php
        $form = $form ?: TaskForm::getInstance();
        echo $form->getForm()->asTable(' ',
                array('draft-namespace' => $namespace)
                );

        $iform = $iform ?: TaskForm::getInternalForm();
        echo $iform->asTable(("")); 
?>
    <hr>
    <p class="full-width">
        <span class="buttons pull-left">
            <input class="btn btn-warning btn-sm" type="reset" value="<?php echo __('Reset'); ?>">
            <input type="button" name="cancel" class="close btn btn-danger btn-sm"
                value="<?php echo __('Cancel'); ?>">
        </span>
        <span class="buttons pull-right">
            <input  class="btn btn-primary btn-sm" type="submit" value="<?php echo __('Create Countermeasure'); ?>">
        </span>
     </p>
</form>
</div>
<div class="clear"></div>
</div>
