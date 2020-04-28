<?php
/*
 * Ticket Preview popup template
 *
 */

$staff=$ticket->getStaff();
$lock=$ticket->getLock();
$role=$ticket->getRole($thisstaff);
$error=$msg=$warn=null;
$thread = $ticket->getThread();

if($lock && $lock->getStaffId()==$thisstaff->getId())
    $warn.='&nbsp;<span class="Icon lockedTicket">'
    .sprintf(__('Ticket is locked by %s'), $lock->getStaffName()).'</span>';
elseif($ticket->isOverdue())
    $warn.='&nbsp;<span class="Icon overdueTicket">'.__('Marked overdue!').'</span>';

echo sprintf(
        '<div style="min-width: 620px;" id="t%s">
         <h1>'.__('Ticket #%s').': %s</h1>',
         $ticket->getNumber(),
         $ticket->getNumber(),
         Format::htmlchars($ticket->getSubject()));

if($error)
    echo sprintf('<div id="msg_error">%s</div>',$error);
elseif($msg)
    echo sprintf('<div id="msg_notice">%s</div>',$msg);
elseif($warn)
    echo sprintf('<div id="msg_warning">%s</div>',$warn);

echo '<ul class="nav nav-tabs" id="ticket-preview">';

echo '
        <li class="nav-item"><a class="nav-link active" id="preview_tab" href="#preview"
             data-toggle="tab" ><i class="icon-list-alt"></i>&nbsp;'.__('Ticket Summary').'</a></li>';
//if ($thread && $thread->getNumCollaborators()) {
echo sprintf('
        <li class="nav-item"><a class="nav-link" id="collab_tab" href="#collab"
             data-toggle="tab"><i class="icon-fixed-width icon-group
            faded"></i>&nbsp;'.__('Collaborators <span class="badge badge-primary badge-pill">%d</span>').'</a></li>',
            $thread->getNumCollaborators());
//}
echo sprintf('<li class="nav-item"><a class="nav-link threadPreviewPane" id="thread_tab" href="#threadPreview"
             data-toggle="tab" ><i class="icon-fixed-width icon-list
            faded"></i>&nbsp;'.__('Thread <span class="badge badge-primary badge-pill">%d</span>').'</a></li>',
             $thread->getNumThreads());


echo sprintf('<li class="nav-item"><a class="nav-link" id="note_tab" href="#notePreview"
             data-toggle="tab" ><i class="icon-fixed-width icon-list
            faded"></i>&nbsp;'.__('Notes <span class="badge badge-primary badge-pill">%d</span>').'</a></li>',
            $thread->getNumEntries()- $thread->getNumThreads());



echo '</ul>';

?>
<div class="tab-content clearfix">
				<div class="tab-pane active" id="preview">
								<table border="0" cellspacing="" cellpadding="1" width="100%" class="ticket_info">

				<?php
				$ticket_state=sprintf('<span>%s</span>',ucfirst($ticket->getStatus()));
				if($ticket->isOpen()) {
				    if($ticket->isOverdue())
				        $ticket_state.=' &mdash; <span>'.__('Overdue').'</span>';
				    else
				        $ticket_state.=sprintf(' &mdash; <span>%s</span>',$ticket->getPriority());
				}

				echo sprintf('
				        <tr>
				            <th width="100">'.__('Ticket State').':</th>
				            <td>%s</td>
				        </tr>
				        <tr>
				            <th>'.__('Created').':</th>
				            <td>%s</td>
				        </tr>',$ticket_state,
				        Format::datetime($ticket->getCreateDate()));
				if($ticket->isClosed()) {
				    echo sprintf('
				            <tr>
				                <th>'.__('Closed').':</th>
				                <td>%s   <span class="faded">by %s</span></td>
				            </tr>',
				            Format::datetime($ticket->getCloseDate()),
				            ($staff?$staff->getName():'staff')
				            );
				} elseif($ticket->getEstDueDate()) {
				    echo sprintf('
				            <tr>
				                <th>'.__('Due Date').':</th>
				                <td>%s</td>
				            </tr>',
				            Format::datetime($ticket->getEstDueDate()));
				}
				echo '</table>';


				echo '<hr>
				    <table border="0" cellspacing="" cellpadding="1" width="100%" class="ticket_info">';
				if($ticket->isOpen()) {
				    echo sprintf('
				            <tr>
				                <th width="100">'.__('Assigned To').':</th>
				                <td>%s</td>
				            </tr>',$ticket->isAssigned()?implode('/', $ticket->getAssignees()):' <span class="faded">&mdash; '.__('Unassigned').' &mdash;</span>');
				}
				echo sprintf(
				    '
				        <tr>
				            <th>'.__('From').':</th>
				            <td><a href="users.php?id=%d" class="no-pjax">%s</a> <span class="faded">%s</span></td>
				        </tr>
				        <tr>
				            <th width="100">'.__('Department').':</th>
				            <td>%s</td>
				        </tr>
				        <tr>
				            <th>'.__('Help Topic').':</th>
				            <td>%s</td>
				        </tr>',
				    $ticket->getUserId(),
				    Format::htmlchars($ticket->getName()),
				    $ticket->getEmail(),
				    Format::htmlchars($ticket->getDeptName()),
				    Format::htmlchars($ticket->getHelpTopic()));

				echo '
				    </table>';
				?>
				<?php
				foreach (DynamicFormEntry::forTicket($ticket->getId()) as $form) {
				    // Skip core fields shown earlier in the ticket preview
				    $answers = $form->getAnswers()->exclude(Q::any(array(
				        'field__flags__hasbit' => DynamicFormField::FLAG_EXT_STORED,
				        'field__name__in' => array('subject', 'priority')
				    )));
				    $displayed = array();
				    foreach($answers as $a) {
				        if (!($v = $a->display()))
				            continue;
				        $displayed[] = array($a->getLocal('label'), $v);
				    }
				    if (count($displayed) == 0)
				        continue;

				    echo '<hr>';
				    echo '<table border="0" cellspacing="" cellpadding="1" width="100%" style="margin-bottom:0px;" class="ticket_info">';
				    echo '<tbody>';

				    foreach ($displayed as $stuff) {
				        list($label, $v) = $stuff;
				        echo '<tr>';
				        echo '<th width="20%" style="white-space: nowrap;">'.Format::htmlchars($label).':</th>';
				        echo '<td>'.$v.'</td>';
				        echo '</tr>';
				    }

				    echo '</tbody>';
				    echo '</table>';
				}

				?>
				</div>	

  			<div class="tab-pane" id="collab">
					<table border="0" cellspacing="" cellpadding="1">
				        <colgroup><col style="min-width: 250px;"></col></colgroup>
				        <?php
				        if ($thread && ($collabs=$thread->getCollaborators())) {?>
				        <?php
				            foreach($collabs as $collab) {
				                echo sprintf('<tr><td %s>%s
				                        <a href="users.php?id=%d" class="no-pjax">%s</a> <em>&lt;%s&gt;</em></td></tr>',
				                        ($collab->isActive()? '' : 'class="faded"'),
				                        (($U = $collab->getUser()) && ($A = $U->getAvatar()))
				                            ? $A->getImageTag(20) : sprintf('<i class="icon-%s"></i>',
				                                $collab->isActive() ? 'comments' :  'comment-alt'),
				                        $collab->getUserId(),
				                        $collab->getName(),
				                        $collab->getEmail());
				            }
				        }  else {
				            echo __("Ticket doesn't have any collaborators.");
				        }?>
				    </table>
				    <br>
				    <?php
				    echo sprintf('<span><a class="collaborators"
				                            href="#thread/%d/collaborators/1">%s</a></span>',
				                            $thread->getId(),
				                            $thread && $thread->getNumCollaborators()
				                                ? __('Manage Collaborators') : __('Add Collaborator')
				                                );
				    ?>
				</div>
				
				<div class="tab-pane" id="threadPreview">
						<div id="ticketThread" class="thread-preview">
				        <div id="thread-items">
				        <?php
				        include STAFFINC_DIR.'templates/thread-entries-preview.tmpl.php';
				        ?>
				        </div>
				    </div>
				</div>
				<div class="tab-pane" id="notePreview">
						<div id="noteThread" class="thread-preview">
				        <div id="note-items">
				        <?php
				      		include STAFFINC_DIR.'templates/note-entries-preview.tmpl.php';
				        ?>
				        </div>
				    </div>
				</div>
			</div>
</div>
<?php
$options = array();

if($ticket->isOpen())
    $options[]=array('action'=>__('Reply'),'url'=>"tickets.php?id=$tid#reply");
		
		$options[]=array('action'=>__('Post Note'),'url'=>"tickets.php?id=$tid#note");

if ($role->hasPerm(Ticket::PERM_ASSIGN))
    $options[]=array('action'=>($ticket->isAssigned()?__('Reassign'):__('Assign')),'url'=>"tickets.php?id=$tid#assign");

if($options) {
    echo '<ul class="tip_menu">';
    foreach($options as $option)
        echo sprintf('<li><a href="%s">%s</a></li>',$option['url'],$option['action']);
    echo '</ul>';
}

echo '</div>';
?>
<script type="text/javascript">
    $('.thread-preview-entry').on('click', function(){
        if($(this).hasClass('collapsed')) {
            $(this).removeClass('collapsed', 500);
        }
    });
    $('.note-preview-entry').on('click', function(){
        if($(this).hasClass('collapsed')) {
            $(this).removeClass('collapsed', 500);
        }
    });

    $('.header').on('click', function(){
        if(!$(this).closest('.thread-preview-entry').hasClass('collapsed')) {
            $(this).closest('.thread-preview-entry').addClass('collapsed', 500);
        }
        if(!$(this).closest('.note-preview-entry').hasClass('collapsed')) {
            $(this).closest('.note-preview-entry').addClass('collapsed', 500);
        }
    });
    
     $('.threadPreviewPane').on('click', function(){
        $.ajax({
          url: "ajax.php/tickets/<?php echo $tid;?>/lastvisit",
          type: "GET"
        });
    });
    
 </script>