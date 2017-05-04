<?php
//Note that ticket obj is initiated in tickets.php.
if(!defined('OSTSCPINC') || !$thisstaff || !is_object($ticket) || !$ticket->getId()) die('Invalid path');

//Make sure the staff is allowed to access the page.
//if(!@$thisstaff->isStaff() || !$ticket->checkStaffPerm($thisstaff)) die('Access Denied');
if(!@$thisstaff->isStaff()) die('Access Denied');

//Re-use the post info on error...savekeyboards.org (Why keyboard? -> some people care about objects than users!!)
$info=($_POST && $errors)?Format::input($_POST):array();

					
//Get the goodies.
$dept  = $ticket->getDept();  //Dept
$role  = $thisstaff->getRole($dept);
$staff = $ticket->getStaff(); //Assigned or closed by..
$user  = $ticket->getOwner(); //Ticket User (EndUser)
$team  = $ticket->getTeam();  //Assigned team.
$sla   = $ticket->getSLA();
$lock  = $ticket->getLock();  //Ticket lock obj

if (!$lock && $cfg->getTicketLockMode() == Lock::MODE_ON_VIEW)
    $lock = $ticket->acquireLock($thisstaff->getId());
$mylock = ($lock && $lock->getStaffId() == $thisstaff->getId()) ? $lock : null;
$id    = $ticket->getId();    //Ticket ID.

//Useful warnings and errors the user might want to know!
if ($ticket->isClosed() && !$ticket->isReopenable())
    $warn = sprintf(
            __('Current ticket status (%s) does not allow the end user to reply.'),
            $ticket->getStatus());
elseif ($ticket->isAssigned()
        && (($staff && $staff->getId()!=$thisstaff->getId())
            || ($team && !$team->isMember($thisstaff))
        ))
    $warn.= sprintf('&nbsp;&nbsp;%s</span>',
            sprintf(__('Suggestion is assigned to %s'),
                implode('/', $ticket->getAssignees())
                ));

if (!$errors['err']) {

    if ($lock && $lock->getStaffId()!=$thisstaff->getId())
        $errors['err'] = sprintf(__('This ticket is currently locked by %s'),
                $lock->getStaffName());
    elseif (($emailBanned=Banlist::isBanned($ticket->getEmail())))
        $errors['err'] = __('Email is in banlist! Must be removed before any reply/response');
    elseif (!Validator::is_valid_email($ticket->getEmail()))
        $errors['err'] = __('EndUser email address is not valid! Consider updating it before responding');
}

$unbannable=($emailBanned) ? BanList::includes($ticket->getEmail()) : false;


//permissions checkStaffPerm

$staffpermission = $ticket->checkStaffPerm($thisstaff);
$assigned = ($team->id == $thisstaff->dept_id ? 1:0);
$haspermission = ($staffpermission == true || $assigned == true ? 1:0);
                            
if($ticket->isOverdue())
    $warn.='&nbsp;&nbsp;<span class="Icon overdueTicket">'.__('Marked overdue!').'</span>';
?>


<div id="threaddata">
<form action="tickets.php?id=<?php echo $ticket->getId(); ?>&a=edit" method="post" id="save"  enctype="multipart/form-data" >
<div>
    <div class="thread sticky bar">
	<div class="thread_content_top">
       <div class="thread_content">
        <div class="pull-right flush-right">
			<span class="action-button pull-right only sticky">
				<a href="#" data-stop="top" data-placement="bottom" data-toggle="tooltip" title="<?php echo __('Scroll Top'); ?>">
				<i class="icon-chevron-up"></i></a>
			</span>             
			<a class="action-button pull-right" data-placement="bottom"  data-toggle="tooltip" title="<?php echo __('Suggestions'); ?>"
				href="tickets.php"><i class="icon-list-alt"></i></a>
<?php if ($haspermission){ ?>
            <span class="action-button pull-right" id="cancelbutton">
				<a href="" onclick="window.location.href="tickets.php?id=<?php echo $ticket->getId(); ?>" data-placement="bottom" data-toggle="tooltip" title="<?php echo __('Cancel');?>" >
				<i class="icon-remove"></i></a>
			</span> 
            <span class="action-button pull-right" id="savebutton">
				<a href="#" onclick="document.getElementById('save').submit();" data-placement="bottom" data-toggle="tooltip" title="<?php echo __('Save'); ?>">
				<i class="icon-save"></i></a>
			</span> 
<?php }?>
				<span class="action-button pull-right">
					<a href="#post-note" id="post-note" class="post-response" data-placement="bottom" data-toggle="tooltip"title="<?php echo __('Post Internal Note'); ?>">
					<i class="icon-edit"></i></a>
                </span>
			<?php
            if ($haspermission){
				if ($haspermission) { ?>
				<span class="action-button pull-right">
					<a href="#post-reply" class="post-response" id="post-reply" data-placement="bottom" data-toggle="tooltip" title="<?php echo __('Post Update'); ?>">
					<i class="icon-mail-reply"></i></a>
				</span>
           
                <?php }} 
                if ($haspermission){
                // Status change options
                echo TicketStatus::status_options();
                }
                ?>
			
			<?php
            if ($haspermission){
                if ($thisstaff->hasPerm(Email::PERM_BANLIST)
                        || $role->hasPerm(Ticket::PERM_EDIT)
                        || ($dept && $dept->isManager($thisstaff))) { ?>
                <span class="action-button pull-right" data-placement="bottom" data-dropdown="#action-dropdown-more" data-toggle="tooltip" title="<?php echo __('More');?>">
                    <i class="icon-caret-down pull-right"></i>
                    <span ><i class="icon-cog"></i></span>
                </span>
                <?php
                }
            }
			?>
           
            <span class="action-button pull-right" data-placement="bottom" data-dropdown="#action-dropdown-print" data-toggle="tooltip" title="<?php echo __('Print'); ?>">
                <i class="icon-caret-down pull-right"></i>
                <a id="ticket-print" href="tickets.php?id=<?php echo $ticket->getId(); ?>&a=print"><i class="icon-print"></i></a>
            </span>
            <div id="action-dropdown-print" class="action-dropdown anchor-right">
              <ul>
                 <li><a class="no-pjax" target="_blank" href="tickets.php?id=<?php echo $ticket->getId(); ?>&a=print&notes=0"><i
                 class="icon-file-alt"></i> <?php echo __('Suggestion Thread'); ?></a>
                 <li><a class="no-pjax" target="_blank" href="tickets.php?id=<?php echo $ticket->getId(); ?>&a=print&notes=1"><i
                 class="icon-file-text-alt"></i> <?php echo __('Thread + Internal Notes'); ?></a>
              </ul>
            </div>
            <?php
            // Transfer
            if ($ticket->checkStaffPerm($thisstaff)){ 
            if ($role->hasPerm(Ticket::PERM_TRANSFER)) {?>
            <span class="action-button pull-right">
            <a class="ticket-action" id="ticket-transfer" data-placement="bottom" data-toggle="tooltip" title="<?php echo __('Transfer Ownership'); ?>"
                data-redirect="tickets.php"
                href="#tickets/<?php echo $ticket->getId(); ?>/transfer"><i class="icon-share"></i></a>
            </span>
            <?php
            } }?>

            <?php
            // Assign
            
            if ($haspermission){ 
            if ($role->hasPerm(Ticket::PERM_ASSIGN)) {?>
            <span class="action-button pull-right">
            <a class="ticket-action" id="ticket-transfer" data-placement="bottom" data-toggle="tooltip" title="<?php echo __('Assign'); ?>"
                data-redirect="tickets.php"
                href="#tickets/<?php echo $ticket->getId(); ?>/assign/teams"><i class="icon-group"></i></a>
            </span>
            <?php
            } } if ($role->hasPerm(Ticket::PERM_EDIT)){?>
            <div id="action-dropdown-more" class="action-dropdown anchor-right">
              <ul>
                <?php

                 if($ticket->isOpen() && ($dept && $dept->isManager($thisstaff))) {

                   
                    if(!$ticket->isOverdue()) { ?>
                        <li><a class="confirm-action" id="ticket-overdue" href="#overdue"><i class="icon-bell"></i> <?php
                            echo __('Mark as Overdue'); ?></a></li>
                    <?php
                    }

                } ?>
                    <li style="display:none;"><a href="#ajax.php/tickets/<?php echo $ticket->getId();
                    ?>/forms/manage" onclick="javascript:
                    $.dialog($(this).attr('href').substr(1), 201);
                    return false"
                    ><i class="icon-paste"></i> <?php echo __('Manage Forms'); ?></a></li>
                <?php
                } ?>

<?php           if ($thisstaff->hasPerm(Email::PERM_BANLIST)) {
                     if(!$emailBanned) {?>
                        <li><a class="confirm-action" id="ticket-banemail"
                            href="#banemail"><i class="icon-ban-circle"></i> <?php echo sprintf(
                                Format::htmlchars(__('Ban Email <%s>')),
                                $ticket->getEmail()); ?></a></li>
                <?php
                     } elseif($unbannable) { ?>
                        <li><a  class="confirm-action" id="ticket-banemail"
                            href="#unbanemail"><i class="icon-undo"></i> <?php echo sprintf(
                                Format::htmlchars(__('Unban Email <%s>')),
                                $ticket->getEmail()); ?></a></li>
                    <?php
                     }
                  }
                  if ($role->hasPerm(Ticket::PERM_DELETE)) {
                     ?>
                    <li class="danger"><a class="ticket-action" href="#tickets/<?php
                    echo $ticket->getId(); ?>/status/delete"
                    data-redirect="tickets.php"><i class="icon-trash"></i> <?php
                    echo __('Delete Ticket'); ?></a></li>

              </ul>
			</div>
           <?php
            }
           ?>
           </div>
        <div class="flush-left">
             <h2><a href="tickets.php?id=<?php echo $ticket->getId(); ?>"
             title="<?php echo __('Reload'); ?>"><i class="icon-refresh"></i>
             <?php echo sprintf(__('Suggestion #%s'), $ticket->getNumber()); ?></a>
            <span  class="only sticky"> - <span style="color: <?php echo
                $ticket->isOpen() ? '#51c351;' : '#f00;'; ?>"><?php echo sprintf(__('%s'), $ticket->getStatus()); ?></span></span></h2>

        </div></div>
    </div>
  </div>
</div>



<div class="tixTitle has_bottom_border">
    <h3>
    <?php $subject_field = TicketForm::getInstance()->getField('subject');
        echo $subject_field->display($ticket->getSubject()); ?>
    </h3>
</div>

<div id="detailschanged">
   <span> Changes made please click the save <i class="icon-save"></i> or cancel <i class="icon-remove"></i> button on the ribbon.</span>
   <div>	&nbsp;</div>
</div>
    
<?php If  (!$haspermission) { ?>
            <div class="permissions-error"><?php echo $warn." (view only)."; ?></div>
             <?php
            }?>
            

<table class="ticket_info" cellspacing="0" cellpadding="0" width="100%" border="0">
    <tr>
        <td width="50%">
            <table border="0" cellspacing="" cellpadding="4" width="100%">
                <tr>
                    <th width="100"><?php echo __('Status');?>:</th>
                    <td><?php echo ($S = $ticket->getStatus()) ? $S->display() : ''; ?></td>
                </tr>
                <tr>
                    <th><?php echo __('Priority');?>:</th>
                    <td><?php echo $ticket->getPriority(); ?></td>
                </tr>
                <tr>
                    <th><?php echo __('Owned By');?>:</th>
                    <td><?php echo Format::htmlchars($ticket->getDeptName()); ?></td>
                </tr>
                <tr>
                    <th><?php echo __('Create Date');?>:</th>
                    <td><?php echo Format::datetime($ticket->getCreateDate()); ?></td>
                </tr>
				<?php
                if($ticket->isOpen()) { ?>
                <tr>
                    <th width="180"><?php echo __('Assigned To');?>:</th>
                    <td>
                        <?php
                        if($ticket->isAssigned())
                            echo Format::htmlchars(implode('/', $ticket->getAssignees()));
                        else
                            echo '<span class="faded">&mdash; '.__('Unassigned').' &mdash;</span>';
                        ?>
                    </td>
                </tr>
                <?php
                } else { ?>
                <tr>
                    <th width="100"><?php echo __('Closed By');?>:</th>
                    <td>
                        <?php
                        if(($staff = $ticket->getStaff()))
                            echo Format::htmlchars($staff->getName());
                        else
                            echo '<span class="faded">&mdash; '.__('Unknown').' &mdash;</span>';
                        ?>
                    </td>
                </tr>
                <?php
                } ?>
            </table>
        </td>
        <td width="50%" style="vertical-align:top">
            <table border="0" cellspacing="" cellpadding="4" width="100%">
                <tr>
                    <th width="180"><?php echo __('Submitter'); ?>:</th>
                    
                   <td> <select id="user_id" name="user_id"  class="requiredfield" <?php if (!$haspermission) echo "disabled";?> >
                    
                    <?php
                    $associate = $ticket->GetOwnerId();
                    
                    if(($users=Staff::getAvailableStaffMembers())) {
                        
                        foreach ($users as $k => $v)
                        echo sprintf('<option value="%s" %s>%s</option>',
                                $k,
                                ($associate == $k ) ? 'selected="selected"' : '',
                                $v);
                    
                        }
                     ?>
                </select>&nbsp;<span class='error'><b>*</b>&nbsp;<?php echo $errors['submitter']; ?></span>
                
                </td>
                </tr>
               <tr><td></td></tr>
			                   <tr>
                    <th nowrap><?php echo __('Last Message');?>:</th>
                    <td><?php echo Format::datetime($ticket->getLastMsgDate()); ?></td>
                </tr>
                
            </table>
        </td>
    </tr>
</table>


				<?php csrf_token(); ?>
				<input type="hidden" name="do" value="update">
				<input type="hidden" name="a" value="edit">
				<input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">					

            <table border="0" cellspacing="" cellpadding="0" width="100%"  class="ticket_info"  id="ticket_info"> 	
				<tr style="display:none;">
					<th width="180">
						<?php echo __('Ticket Source');?>:
					</th>
					<td >
						<select name="source" class="requiredfield" <?php if (!$haspermission){ echo " disabled ";} ?>>
							<option value="" selected >&mdash; <?php
								echo __('Select Source');?> &mdash;</option>
							<?php
							$source = Format::htmlchars($ticket->getSource()) ?: 'Phone';
							foreach (Ticket::getSources() as $k => $v) {
								echo sprintf('<option value="%s" %s>%s</option>',
										$k,
										($source == $k ) ? 'selected="selected"' : '',
										$v);
							}
							?>
						</select>
						&nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['source']; ?></font>
					</td>
				</tr> 
                <tr style="display:none;">
					<th width="180">
						<?php echo __('SLA Plan');?>:
					</th>
					<td>
					<?php $id = $ticket->getSLAId() ?>
						<select name="slaId"  <?php if (!$haspermission) { echo " disabled ";} ?>>
							<option value="0" selected="selected" >&mdash; <?php echo __('None');?> &mdash;</option>
							<?php
							if($slas=SLA::getSLAs()) {
								foreach($slas as $id =>$name) {
									echo sprintf('<option value="%d" %s>%s</option>',
											$id, ($ticket->getSLAId()==$id)?'selected="selected"':'',$name);
								}
							}
							//$sla?Format::htmlchars($sla->getName()):'<span class="faded">&mdash; '.__('None').' &mdash;</span>' ?>
						</select>
						&nbsp;<font class="error">&nbsp;<?php echo $errors['slaId']; ?></font>
					</td>
				</tr>
	            				
				<?php
                if($ticket->isOpen()){ ?>
                <tr>
                    <th width="180"><?php echo __('Due Date');?>:</th>
                    <td>
						<input <?php if ($haspermission){ echo ' class="dp" ';} ?> id="duedate" name="duedate" value="<?php echo Format::date($ticket->getEstDueDate()); ?>" size="12" autocomplete=OFF  
                        <?php if (!$haspermission){ echo " disabled ";} ?>>
						&nbsp;&nbsp;
						<?php
						 if ($haspermission){
                            $min=$hr=null;
                            if(Format::time($ticket->getEstDueDate()))
							list($hr, $min)=explode(':', Format::time($ticket->getEstDueDate()));
                            echo Misc::timeDropdown($hr, $min, 'time');
                        }
						?>
						&nbsp;<font class="error">&nbsp;<?php echo $errors['duedate']; ?>&nbsp;<?php echo $errors['time']; ?></font>
						<em style="color:gray;"><?php echo __('Time is based on your time zone');?>
							(<?php echo $cfg->getTimezone($thisstaff); ?>)</em>
					</td>
                </tr>
                <?php
                }else { ?>
                <tr>
                    <th width="180"><?php echo __('Close Date');?>:</th>
                    <td><?php echo Format::datetime($ticket->getCloseDate()); ?></td>
                </tr>
                <?php
                }
                ?>
				<tr>
        			<th width="180">
						<?php echo __('Category');?>:
					</th>
					<td>
						<?php $id = $ticket->getHelpTopicId(); ?>
							<select name="topicId"  class="requiredfield" <?php if (!$haspermission){ echo " disabled ";} ?>>
								<option value="" selected >&mdash; <?php echo __('Select Category');?> &mdash;</option>
								<?php
								if($topics=Topic::getHelpTopics()) {
									foreach($topics as $id =>$name) {
										echo sprintf('<option value="%d" %s>%s</option>',
												$id, ($ticket->getHelpTopicId()==$id)?'selected="selected"':'',$name);
									}
								}
								?>
							</select> 
							
							&nbsp;<font class="error">* <br>&nbsp;<?php echo $errors['topicId']; ?></font>
					</td>
				</tr>
			
		

						<?php 
                            foreach (DynamicFormEntry::forTicket($ticket->getId()) as $form) {
                                $form->addMissingFields();
                                if ($haspermission){
								$form->render(true, false, array('mode'=>'edit','width'=>140,'entry'=>$form));
                                } else {
                                $form->render(true, false, array('mode'=>'edit','width'=>140,'disabled'=>1,'entry'=>$form));    
                                }
						} ?>

	</table>
   
</form>

<div id="saveneeded">
   <span> Changes made please click the save <i class="icon-save"></i> or cancel <i class="icon-remove"></i> button on the ribbon.</span>
   <div>	&nbsp;</div>
</div>
    <div id="updatearea">
<?php
$tcount = $ticket->getThreadEntries($types)->count();
?>
<ul  class="tabs clean threads" id="ticket_tabs" >
    <li class="active"><a id="ticket-thread-tab" href="#ticket_thread"><?php
        echo sprintf(__('Suggestion History (%d)'), $tcount); ?></a></li>
     <?php if ($haspermission) {?>
    <li><a id="ticket-tasks-tab" href="#tasks"
            data-url="<?php
        echo sprintf('#tickets/%d/tasks', $ticket->getId()); ?>"><?php
        echo __('Tasks');
        if ($ticket->getNumTasks())
            echo sprintf('&nbsp;(<span id="ticket-tasks-count">%d</span>)', $ticket->getNumTasks());
        ?></a></li>
     <?php } ?>
</ul>

<div id="ticket_tabs_container">
<div id="ticket_thread" class="tab_content">

<?php
    // Render ticket thread
    $ticket->getThread()->render(
            array('M', 'R', 'N'),
            array(
                'html-id'   => 'ticketThread',
                'mode'      => Thread::MODE_STAFF,
                'sort'      => $thisstaff->thread_view_order
                )
            );
?>
<div class="clear"></div>
<?php
if ($errors['err'] && isset($_POST['a'])) {
    // Reflect errors back to the tab.
    $errors[$_POST['a']] = $errors['err'];
} elseif($msg) { ?>
    <div id="thread_msg_notice"><?php echo $msg; ?></div>
<?php
} elseif($warn) { ?>
    <div id="thread_msg_warning"><?php echo $warn; ?></div>
<?php
} ?>

<div class="sticky bar stop actions" id="response_options">

    
    <ul class="tabs" id="response-tabs">
        <?php
        
        if ($haspermission){ ?>
        <li class="active <?php
            echo isset($errors['reply']) ? 'error' : ''; ?>"><a
            href="#reply" id="post-reply-tab"><?php echo __('Post Update');?></a></li>
        <?php
        } ?>
        <li><a href="#note" <?php
            echo isset($errors['postnote']) ?  'class="error"' : ''; ?>
            id="post-note-tab"><?php echo __('Post Internal Note');?></a></li>
    </ul>
   
    <?php

    if ($role->hasPerm(Ticket::PERM_REPLY)) { ?>
    <form id="reply" class="tab_content spellcheck exclusive save"
        data-lock-object-id="ticket/<?php echo $ticket->getId(); ?>"
        data-lock-id="<?php echo $mylock ? $mylock->getId() : ''; ?>"
        action="tickets.php?<?php echo$qurl.$purl.$qfurl
		?>&id=<?php
        echo $ticket->getId(); ?>#reply" name="reply" method="post" enctype="multipart/form-data">
        <?php csrf_token(); ?>
        <input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
        <input type="hidden" name="msgId" value="<?php echo $msgId; ?>">
        <input type="hidden" name="a" value="reply">
        <input type="hidden" name="lockCode" value="<?php echo $mylock ? $mylock->getCode() : ''; ?>">
        <table style="width:100%" border="0" cellspacing="0" cellpadding="3">
            <?php
            if ($errors['reply']) {?>
            <tr><td width="120">&nbsp;</td><td class="error"><?php echo $errors['reply']; ?>&nbsp;</td></tr>
            <?php
            }?>
           <tbody id="to_sec">
            <tr>
                <td width="120">
                    <label><strong><?php echo __('To'); ?>:</strong></label>
                </td>
                <td>
                    <?php
                    # XXX: Add user-to-name and user-to-email HTML ID#s
                    $to =sprintf('%s &lt;%s&gt;',
                            Format::htmlchars($ticket->getName()),
                            $ticket->getReplyToEmail());
                    $emailReply = (!isset($info['emailreply']) || $info['emailreply']);
                    ?>
                    <select id="emailreply" name="emailreply">
                        <option value="1" <?php echo !$emailReply ?  'selected="selected"' : ''; ?>><?php echo $to; ?></option>
                        <option value="0" <?php echo $emailReply ? 'selected="selected"' : ''; ?>>&mdash; <?php echo __('Do Not Email Reply'); ?> &mdash;</option>
                    </select>
                </td>
            </tr>
            </tbody>

            <tbody id="resp_sec">
            <?php
            if($errors['response']) {?>
            <tr><td width="120">&nbsp;</td><td class="error"><?php echo $errors['response']; ?>&nbsp;</td></tr>
            <?php
            }?>
            <tr>
                <td width="120" style="vertical-align:top">
                    <label><strong><?php echo __('Update');?>:</strong></label>
                </td>
                <td>
<?php if (!$cfg->isCannedResponseEnabled()) { ?>
                    <select id="cannedResp" name="cannedResp">
                        <option value="0" selected="selected"><?php echo __('Select a canned response');?></option>
                        <option value='original'><?php echo __('Original Message'); ?></option>
                        <option value='lastmessage'><?php echo __('Last Message'); ?></option>
                        <?php
                        if(($cannedResponses=Canned::responsesByDeptId($ticket->getDeptId()))) {
                            echo '<option value="0" disabled="disabled">
                                ------------- '.__('Premade Replies').' ------------- </option>';
                            foreach($cannedResponses as $id =>$title)
                                echo sprintf('<option value="%d">%s</option>',$id,$title);
                        }
                        ?>
                    </select>
                    <br>
<?php } # endif (canned-resonse-enabled)
                    $signature = '';
                    switch ($thisstaff->getDefaultSignatureType()) {
                    case 'dept':
                        if ($dept && $dept->canAppendSignature())
                           $signature = $dept->getSignature();
                       break;
                    case 'mine':
                        $signature = $thisstaff->getSignature();
                        break;
                    } ?>
                    <input type="hidden" name="draft_id" value=""/>
                    <textarea name="response" id="response" cols="50"
                        data-signature-field="signature" data-dept-id="<?php echo $dept->getId(); ?>"
                        data-signature="<?php
                            echo Format::htmlchars(Format::viewableImages($signature)); ?>"
                        placeholder="<?php echo __(
                        'Enter your update here.'
                        ); ?>"
                        rows="9" wrap="soft"
                        class="<?php if ($cfg->isRichTextEnabled()) echo 'richtext';
                            ?> draft draft-delete" <?php
    list($draft, $attrs) = Draft::getDraftAndDataAttrs('ticket.response', $ticket->getId(), $info['response']);
    echo $attrs; ?>><?php echo $_POST ? $info['response'] : $draft;
                    ?></textarea>
                <div id="reply_form_attachments" class="attachments">
                <?php
                    print $response_form->getField('attachments')->render();
                ?>
                </div>
                </td>
            </tr>
            
            <tr>
                <td width="120" style="vertical-align:top">
                    <label><strong><?php echo __('Suggestion Status');?>:</strong></label>
                </td>
                <td>
                    <?php
                    $outstanding = false;
                    if ($role->hasPerm(Ticket::PERM_CLOSE)
                            && is_string($warning=$ticket->isCloseable())) {
                        $outstanding =  true;
                        echo sprintf('<div class="thread_warning-banner">%s</div>', $warning);
                    } ?>
                    <select name="reply_status_id">
                    <?php
                    $statusId = $info['reply_status_id'] ?: $ticket->getStatusId();
                    $states = array('open');
                    if ($role->hasPerm(Ticket::PERM_CLOSE) && !$outstanding)
                        $states = array_merge($states, array('closed'));

                    foreach (TicketStatusList::getStatuses(
                                array('states' => $states)) as $s) {
                        if (!$s->isEnabled()) continue;
                        $selected = ($statusId == $s->getId());
                        echo sprintf('<option value="%d" %s>%s%s</option>',
                                $s->getId(),
                                $selected
                                 ? 'selected="selected"' : '',
                                __($s->getName()),
                                $selected
                                ? (' ('.__('current').')') : ''
                                );
                    }
                    ?>
                    </select>
                </td>
            </tr>
         </tbody>
        </table>
        <p  style="text-align:center;">
            <input class="save pending" type="submit" value="<?php echo __('Post Update');?>">
            <input class="" type="reset" value="<?php echo __('Reset');?>">
        </p>
    </form>
    <?php
    } ?>
    <form id="note" class="hidden tab_content spellcheck exclusive save"
        data-lock-object-id="ticket/<?php echo $ticket->getId(); ?>"
        data-lock-id="<?php echo $mylock ? $mylock->getId() : ''; ?>"
        action="tickets.php?<?php echo$qurl.$purl.$qfurl
		?>&id=<?php echo $ticket->getId(); ?>#note"
        name="note" method="post" enctype="multipart/form-data">
        <?php csrf_token(); ?>
        <input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
        <input type="hidden" name="locktime" value="<?php echo $cfg->getLockTime() * 60; ?>">
        <input type="hidden" name="a" value="postnote">
        <input type="hidden" name="lockCode" value="<?php echo $mylock ? $mylock->getCode() : ''; ?>">
        <table width="100%" border="0" cellspacing="0" cellpadding="3">
            <?php
            if($errors['postnote']) {?>
            <tr>
                <td width="120">&nbsp;</td>
                <td class="error"><?php echo $errors['postnote']; ?></td>
            </tr>
            <?php
            } ?>
            <tr>
                <td width="120" style="vertical-align:top">
                    <label><strong><?php echo __('Internal Note'); ?>:</strong><span class='error'>&nbsp;*</span></label>
                </td>
                <td>
                    <div>
                        <div class="faded" style="padding-left:0.15em"><?php
                        echo __('Note title - summary of the note (optional)'); ?></div>
                        <input type="text" name="title" id="title" size="60" value="<?php echo $info['title']; ?>" >
                        <br/>
                        <span class="error">&nbsp;<?php echo $errors['title']; ?></span>
                    </div>
                    <br/>
                    <div class="error"><?php echo $errors['note']; ?></div>
                    <textarea name="note" id="internal_note" cols="80"
                        placeholder="<?php echo __('Note details'); ?>"
                        rows="9" wrap="soft"
                        class="<?php if ($cfg->isRichTextEnabled()) echo 'richtext';
                            ?> draft draft-delete" <?php
    list($draft, $attrs) = Draft::getDraftAndDataAttrs('ticket.note', $ticket->getId(), $info['note']);
    echo $attrs; ?>><?php echo $_POST ? $info['note'] : $draft;
                        ?></textarea>
                <div class="attachments">
                <?php
                    print $note_form->getField('attachments')->render();
                ?>
                </div>
                </td>
            </tr>
            <tr><td colspan="2">&nbsp;</td></tr>
            <?php if ($ticket->checkStaffPerm($thisstaff)) {?>
            <tr>
                <td width="125">
                    <label><strong><?php echo __('Suggestion Status');?>:</strong></label>
                </td>
                <td>
                    <div class="faded"></div>
                    <select name="note_status_id">
                        <?php
                        $statusId = $info['note_status_id'] ?: $ticket->getStatusId();
                        $states = array('open');
                        if ($ticket->isCloseable() === true
                                && $role->hasPerm(Ticket::PERM_CLOSE))
                            $states = array_merge($states, array('closed'));
                        foreach (TicketStatusList::getStatuses(
                                    array('states' => $states)) as $s) {
                            if (!$s->isEnabled()) continue;
                            $selected = $statusId == $s->getId();
                            echo sprintf('<option value="%d" %s>%s%s</option>',
                                    $s->getId(),
                                    $selected ? 'selected="selected"' : '',
                                    __($s->getName()),
                                    $selected ? (' ('.__('current').')') : ''
                                    );
                        }
                        ?>
                    </select>
                    &nbsp;<span class='error'>*&nbsp;<?php echo $errors['note_status_id']; ?></span>
                </td>
            </tr>
            <?php } ?>
        </table>

       <p style="text-align:center;">
           <input class="save pending" type="submit" value="<?php echo __('Post Note');?>">
           <input class="" type="reset" value="<?php echo __('Reset');?>">
       </p>
   </form></div>
   </div>
   </div>
 </div>
 </div>
</div>

<div style="display:none;" class="dialog" id="print-options">
    <h3><?php echo __('Ticket Print Options');?></h3>
    <a class="close" href=""><i class="icon-remove-circle"></i></a>
    <hr/>
    <form action="tickets.php?id=<?php echo $ticket->getId(); ?>
	&queue=<?php 
				if (isset($_REQUEST['queue'])) {
					echo $_REQUEST['queue'];		}
				
			 ?>"
        method="post" id="print-form" name="print-form" target="_blank">
        <?php csrf_token(); ?>
        <input type="hidden" name="a" value="print">
        <input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
        <fieldset class="notes">
            <label class="fixed-size" for="notes"><?php echo __('Print Notes');?>:</label>
            <label class="inline checkbox">
            <input type="checkbox" id="notes" name="notes" value="1"> <?php echo __('Print <b>Internal</b> Notes/Comments');?>
            </label>
        </fieldset>
        <fieldset>
            <label class="fixed-size" for="psize"><?php echo __('Paper Size');?>:</label>
            <select id="psize" name="psize">
                <option value="">&mdash; <?php echo __('Select Print Paper Size');?> &mdash;</option>
                <?php
                  $psize =$_SESSION['PAPER_SIZE']?$_SESSION['PAPER_SIZE']:$thisstaff->getDefaultPaperSize();
                  foreach(Export::$paper_sizes as $v) {
                      echo sprintf('<option value="%s" %s>%s</option>',
                                $v,($psize==$v)?'selected="selected"':'', __($v));
                  }
                ?>
            </select>
        </fieldset>
        <hr style="margin-top:3em"/>
        <p class="full-width">
            <span class="buttons pull-left">
                <input type="reset" value="<?php echo __('Reset');?>">
                <input type="button" value="<?php echo __('Cancel');?>" class="close">
            </span>
            <span class="buttons pull-right">
                <input type="submit" value="<?php echo __('Print');?>">
            </span>
         </p>
    </form>
    <div class="clear"></div>
</div>
<div style="display:none;" class="dialog" id="confirm-action">
    <h3><?php echo __('Please Confirm');?></h3>
    <a class="close" href=""><i class="icon-remove-circle"></i></a>
    <hr/>
    <p class="confirm-action" style="display:none;" id="claim-confirm">
        <?php echo __('Are you sure you want to <b>claim</b> (self assign) this ticket?');?>
    </p>
    <p class="confirm-action" style="display:none;" id="answered-confirm">
        <?php echo __('Are you sure you want to flag the ticket as <b>answered</b>?');?>
    </p>
    <p class="confirm-action" style="display:none;" id="unanswered-confirm">
        <?php echo __('Are you sure you want to flag the ticket as <b>unanswered</b>?');?>
    </p>
    <p class="confirm-action" style="display:none;" id="overdue-confirm">
        <?php echo __('Are you sure you want to flag the ticket as <font color="red"><b>overdue</b></font>?');?>
    </p>
    <p class="confirm-action" style="display:none;" id="banemail-confirm">
        <?php echo sprintf(__('Are you sure you want to <b>ban</b> %s?'), $ticket->getEmail());?> <br><br>
        <?php echo __('New tickets from the email address will be automatically rejected.');?>
    </p>
    <p class="confirm-action" style="display:none;" id="unbanemail-confirm">
        <?php echo sprintf(__('Are you sure you want to <b>remove</b> %s from ban list?'), $ticket->getEmail()); ?>
    </p>
    <p class="confirm-action" style="display:none;" id="release-confirm">
        <?php echo sprintf(__('Are you sure you want to <b>unassign</b> ticket from <b>%s</b>?'), $ticket->getAssigned()); ?>
    </p>
    <p class="confirm-action" style="display:none;" id="changeuser-confirm">
        <span id="msg_warning" style="display:block;vertical-align:top">
        <?php echo sprintf(Format::htmlchars(__('%s <%s> will longer have access to the ticket')),
            '<b>'.Format::htmlchars($ticket->getName()).'</b>', Format::htmlchars($ticket->getEmail())); ?>
        </span>
        <?php echo sprintf(__('Are you sure you want to <b>change</b> ticket owner to %s?'),
            '<b><span id="newuser">this guy</span></b>'); ?>
    </p>
    <p class="confirm-action" style="display:none;" id="delete-confirm">
        <font color="red"><strong><?php echo sprintf(
            __('Are you sure you want to DELETE %s?'), __('this ticket'));?></strong></font>
        <br><br><?php echo __('Deleted data CANNOT be recovered, including any associated attachments.');?>
    </p>
    <div><?php echo __('Please confirm to continue.');?></div>
    <form action="tickets.php?id=<?php echo $ticket->getId(); ?>" method="post" id="confirm-form" name="confirm-form">
        <?php csrf_token(); ?>
        <input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
        <input type="hidden" name="a" value="process">
        <input type="hidden" name="do" id="action" value="">
        <hr style="margin-top:1em"/>
        <p class="full-width">
            <span class="buttons pull-left">
                <input type="button" value="<?php echo __('Cancel');?>" class="close">
            </span>
            <span class="buttons pull-right">
                <input type="submit" value="<?php echo __('OK');?>">
            </span>
         </p>
    </form>
    <div class="clear"></div>
</div>
<script type="text/javascript">
$(function() {
    $(document).on('click', 'a.change-user', function(e) {
        e.preventDefault();
        var tid = <?php echo $ticket->getOwnerId(); ?>;
        var cid = <?php echo $ticket->getOwnerId(); ?>;
        var url = 'ajax.php/'+$(this).attr('href').substr(1);
        $.userLookup(url, function(user) {
            if(cid!=user.id
                    && $('.dialog#confirm-action #changeuser-confirm').length) {
                $('#newuser').html(user.name +' &lt;'+user.email+'&gt;');
                $('.dialog#confirm-action #action').val('changeuser');
                $('#confirm-form').append('<input type=hidden name=user_id value='+user.id+' />');
                $('#overlay').show();
                $('.dialog#confirm-action .confirm-action').hide();
                $('.dialog#confirm-action p#changeuser-confirm')
                .show()
                .parent('div').show().trigger('click');
            }
        });
    });

    // Post Reply or Note action buttons.
    $('a.post-response').click(function (e) {
        var $r = $('ul.tabs > li > a'+$(this).attr('href')+'-tab');
        if ($r.length) {
            // Make sure ticket thread tab is visiable.
            var $t = $('ul#ticket_tabs > li > a#ticket-thread-tab');
            if ($t.length && !$t.hasClass('active'))
                $t.trigger('click');
            // Make the target response tab active.
            if (!$r.hasClass('active'))
                $r.trigger('click');

            // Scroll to the response section.
            var $stop = $(document).height();
            var $s = $('div#response_options');
            if ($s.length)
                $stop = $s.offset().top-125

            $('html, body').animate({scrollTop: $stop}, 'fast');
        }

        return false;
    });

});

// Hide form buttons By Default

$('#save').find('input, select').change(function(){
$("#savebutton").css("backgroundColor", "rgba(76, 175, 80, 0.5);");
$("#cancelbutton").css("backgroundColor", "rgb(246, 193, 193)");
$("#updatearea").css("display", "none");
$("#detailschanged").css("display", "inherit");
$("#saveneeded").css("display", "inherit");
});

$("#save").keyup(function(e){
    var charCode = e.which || e.keyCode; 
    if (!(charCode === 9)){
        $("#savebutton").css("backgroundColor", "rgba(76, 175, 80, 0.5);");
        $("#cancelbutton").css("backgroundColor", "rgb(246, 193, 193)");
        $("#updatearea").css("display", "none");
        $("#detailschanged").css("display", "inherit");
        $("#saveneeded").css("display", "inherit");
    }
});

$('#reply').find('input, select').change(function(){
$("#savebutton").css("pointer-events", "none");
});

$('#reply').keyup(function(e){
    var charCode = e.which || e.keyCode; 
    if (!(charCode === 9)){
     $("#savebutton").css("pointer-events", "none");
     
    }
});   

$('#note').find('input, select').change(function(){
$("#savebutton").css("pointer-events", "none");
});
 
$('#note').keyup(function(e){
    var charCode = e.which || e.keyCode; 
    if (!(charCode === 9)){
     $("#savebutton").css("pointer-events", "none");
     
    }
});       

    
</script>
