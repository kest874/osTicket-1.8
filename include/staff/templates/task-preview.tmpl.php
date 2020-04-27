<?php
$error=$msg=$warn=null;

if (!$task->checkStaffPerm($thisstaff))
     $warn.= sprintf(__('You do not have access to %s'), __('this task'));
elseif ($task->isOverdue())
    $warn.='&nbsp;<span class="Icon overdueTicket">'.__('Marked overdue!').'</span>';

echo sprintf(
        '<div>
         <h2>'.__('Task #%s').': %s</h2>',
         $task->getNumber(),
         $task->getNumber(),
         Format::htmlchars($task->getTitle()));

if($error)
    echo sprintf('<div id="msg_error">%s</div>',$error);
elseif($msg)
    echo sprintf('<div id="msg_notice">%s</div>',$msg);
elseif($warn)
    echo sprintf('<div id="msg_warning">%s</div>',$warn);
?>
<ul class="nav nav-tabs" id="ticket-preview">
        <li class="nav-item"><a class="nav-link active" id="preview_tab" href="#summary" data-toggle="tab"><i class="icon-list-alt"></i>&nbsp;Task Summary</a></li>
        <li class="nav-item"><a class="nav-link" id="collab_tab" href="#collab" data-toggle="tab"><i class="icon-fixed-width icon-group
            faded"></i>&nbsp;Collaborators <span class="badge badge-primary badge-pill"><?php echo $task->getThread()->getNumCollaborators(); ?></span></a></li>
            	
</ul>
			
			
			<div class="tab-content clearfix">
				<div class="tab-pane active" id="summary">
								<table border="0" cellspacing="" cellpadding="1" width="100%" class="ticket_info">

<?php
$status=sprintf('<span>%s</span>',ucfirst($task->getStatus()));
echo sprintf('
        <tr>
            <th width="100">'.__('Status').':</th>
            <td>%s</td>
        </tr>
        <tr>
            <th>'.__('Created').':</th>
            <td>%s</td>
        </tr>',$status,
        Format::datetime($task->getCreateDate()));

if ($task->isClosed()) {

    echo sprintf('
            <tr>
                <th>'.__('Completed').':</th>
                <td>%s</td>
            </tr>',
            Format::datetime($task->getCloseDate()));

} elseif ($task->isOpen() && $task->duedate) {
    echo sprintf('
            <tr>
                <th>'.__('Due Date').':</th>
                <td>%s</td>
            </tr>',
            Format::datetime($task->duedate));
} ?>
</table>


<hr>
    <table border="0" cellspacing="" cellpadding="1" width="100%" class="ticket_info">
<?php
if ($task->isOpen()) {
    echo sprintf('
            <tr>
                <th width="100">'.__('Assigned To').':</th>
                <td>%s</td>
            </tr>', $task->getAssigned() ?: ' <span class="faded">&mdash; '.__('Unassigned').' &mdash;</span>');
}
echo sprintf(
    '
        <tr>
            <th width="100">'.__('Department').':</th>
            <td>%s</td>
        </tr>',
    Format::htmlchars($task->dept->getName())
    );
?>
</table>			
	</div>	

<div class="tab-pane" id="collab">
<table border="0" cellspacing="" cellpadding="1">
        <colgroup><col style="min-width: 250px;"></col></colgroup>
        <?php
        if (($collabs=$task->getThread()->getCollaborators())) {?>
        <?php
            foreach($collabs as $collab) {
                echo sprintf('<tr><td %s><i class="icon-%s"></i>
                        <a href="users.php?id=%d" class="no-pjax">%s</a> <em>&lt;%s&gt;</em></td></tr>',
                        ($collab->isActive()? '' : 'class="faded"'),
                        ($collab->isActive()? 'comments' :  'comment-alt'),
                        $collab->getUserId(),
                        $collab->getName(),
                        $collab->getEmail());
            }
        }  else {
            echo __("Task doesn't have any collaborators.");
        }?>
    </table>
    <br>
    <?php
    echo sprintf('<span><a class="collaborators"
                            href="#thread/%d/collaborators/1">%s</a></span>',
                            $task->getThreadId(),
                            $task->getThread()->getNumCollaborators()
                                ? __('Manage Collaborators') : __('Add Collaborator')
                                );
    ?>	
</div>


</div>
