<?php
//Note that ticket obj is initiated in tickets.php.
if(!defined('OSTSCPINC') || !$thisstaff || !is_object($ticket) || !$ticket->getId()) die('Invalid path');

//Make sure the staff is allowed to access the page.
if(!@$thisstaff->isStaff() || !$ticket->checkStaffPerm($thisstaff)) die('Access Denied');

//Re-use the post info on error...savekeyboards.org (Why keyboard? -> some people care about objects than users!!)
$info=($_POST && $errors)?Format::input($_POST):array();

$type = array('type' => 'viewed');
Signal::send('object.view', $ticket, $type);

//Get the goodies.
$dept     = $ticket->getDept();  //Dept
$role     = $ticket->getRole($thisstaff);
$staff    = $ticket->getStaff(); //Assigned or closed by..
$user     = $ticket->getOwner(); //Ticket User (EndUser)
$team     = $ticket->getTeam();  //Assigned team.
$sla      = $ticket->getSLA();
$lock     = $ticket->getLock();  //Ticket lock obj
$topicset = $ticket->getHelpTopicId();
$children = Ticket::getChildTickets($ticket->getId());
$thread = $ticket->getThread();
if (!$lock && $cfg->getTicketLockMode() == Lock::MODE_ON_VIEW)
    $lock = $ticket->acquireLock($thisstaff->getId());
$mylock = ($lock && $lock->getStaffId() == $thisstaff->getId()) ? $lock : null;
$id    = $ticket->getId();    //Ticket ID.
$isManager = $dept->isManager($thisstaff); //Check if Agent is Manager
$canRelease = ($isManager || $role->hasPerm(Ticket::PERM_RELEASE)); //Check if Agent can release tickets
$blockReply = $ticket->isChild() && $ticket->getMergeType() != 'visual';
$canMarkAnswered = ($isManager || $role->hasPerm(Ticket::PERM_MARKANSWERED)); //Check if Agent can mark as answered/unanswered
$ticket->setStaffLastVisitNow($thisstaff); //reset last visit time

//Useful warnings and errors the user might want to know!
if ($ticket->isClosed() && !$ticket->isReopenable())
    $warn = sprintf(
            __('Current ticket status (%s) does not allow the end user to reply.'),
            $ticket->getStatus());
elseif ($blockReply)
    $warn = __('Child Tickets do not allow the end user or agent to reply.');
elseif ($ticket->isAssigned()
        && (($staff && $staff->getId()!=$thisstaff->getId())
            || ($team && !$team->hasMember($thisstaff))
        ))
    $warn.= sprintf('&nbsp;&nbsp;<span class="Icon assignedTicket">%s</span>',
            sprintf(__('Ticket is assigned to %s'),
                implode('/', $ticket->getAssignees())
                ));

if (!$errors['err']) {

    if ($lock && $lock->getStaffId()!=$thisstaff->getId())
        $errors['err'] = sprintf(__('%s is currently locked by %s'),
                __('This ticket'),
                $lock->getStaffName());
    elseif (($emailBanned=Banlist::isBanned($ticket->getEmail())))
        $errors['err'] = __('Email is in banlist! Must be removed before any reply/response');
    elseif (!Validator::is_valid_email($ticket->getEmail()))
        $errors['err'] = __('EndUser email address is not valid! Consider updating it before responding');
}

$unbannable=($emailBanned) ? BanList::includes($ticket->getEmail()) : false;

 ?>

<!-- Begin Subnav -->
<div class="subnav">

    <div class="float-left subnavtitle" id="ticketviewtitle">
        <a href="tickets.php?id=<?php echo $ticket->getId(); ?>" title="<?php echo __('Reload'); ?>"><i class="icon-refresh"></i>
            <?php echo sprintf(__('Ticket #%s'), $ticket->getNumber()); ?></a>
                
                <?php
                if (count($children) != 0)
                    echo sprintf('- <span style="font-weight: 700;">%s</span>', __('PARENT') );
                elseif ($ticket->isChild())
                    echo sprintf('- <span style="font-weight: 700;">%s</span>', __('CHILD'));
                    ?>
                
                <span  class=""> - <span style="color: <?php echo $ticket->isOpen() ? '#51c351;' : '#f00;'; ?>">
                <?php echo sprintf(__('%s'), $ticket->getStatus()); ?></span></span>
                
                - <?php $subject_field = TicketForm::getInstance()->getField('subject'); ?>
               <span id="subject">
	                        <?php echo Format::htmlchars( $subject_field->display($ticket->getSubject()));
	                        ?></span> 

    </div>

    <div class="btn-group btn-group-sm float-right m-b-10" role="group" aria-label="Button group with nested dropdown">

        <div class="btn-group btn-group-sm hidden-xs-down" role="group">
            <button id="btnGroupDrop1" type="button" class="btn btn-light dropdown-toggle waves-effect " 
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-placement="bottom" data-toggle="tooltip" 
            title="<?php echo __('Print'); ?>"><i class="icon-print"></i>
            </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        
                <a class="dropdown-item no-pjax" target="_blank" href="tickets.php?id=<?php echo $ticket->getId(); ?>&a=print&notes=0"><i
                	 class="icon-file-alt"></i> <?php echo __('Ticket Thread'); ?></a>
                <a class="dropdown-item  no-pjax" target="_blank" href="tickets.php?id=<?php echo $ticket->getId(); ?>&a=print&notes=1"><i
                   class="icon-file-text-alt"></i> <?php echo __('Thread + Internal Notes'); ?></a>
                <a class="dropdown-item no-pjax" target="_blank" href="tickets.php?id=<?php echo $ticket->getId(); ?>&a=print&notes=1&events=1"><i
                 class="icon-list-alt"></i> <?php echo __('Thread + Internal Notes + Events'); ?></a>
                <?php if (extension_loaded('zip')) { ?>
                <a class="dropdown-item no-pjax" target="_blank" href="tickets.php?id=<?php echo $ticket->getId(); ?>&a=zip&notes=1"><i
                 class="icon-download-alt"></i> <?php echo __('Export with Notes + Attachments'); ?></a>
                <a class="dropdown-item no-pjax" target="_blank" href="tickets.php?id=<?php echo $ticket->getId(); ?>&a=zip&notes=1&tasks=1"><i
                 class="icon-download"></i> <?php echo __('Export with Notes + Attachments + Tasks'); ?></a>
                 <?php } ?> 
                </div>
        </div>
                   
         <?php
            // Assign
            if ($ticket->isOpen() && $role->hasPerm(Ticket::PERM_ASSIGN)) {?>

            <div class="btn-group btn-group-sm" role="group">
            <button id="btnGroupDrop1" type="button" class="btn btn-light dropdown-toggle waves-effect" 
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-placement="bottom" data-toggle="tooltip" 
            title="<?php echo $ticket->isAssigned() ? __('Assign') : __('Reassign'); ?>"><i class="icon-user"></i>
            </button>
                <div class="dropdown-menu " aria-labelledby="btnGroupDrop1">
                    
                <?php   
                    // Agent can claim team assigned ticket
                    if (!$ticket->getStaff()
                            && (!$dept->assignMembersOnly()
                                || $dept->isMember($thisstaff))
                            ) { ?>
                    <a class="dropdown-item ticket-action" data-redirect="tickets.php" href="#tickets/<?php echo $ticket->getId(); ?>/claim"><i class="icon-chevron-sign-down"></i> <?php echo __('Claim'); ?></a>
                    <?php
                    } ?>
                    <a class="dropdown-item ticket-action" data-redirect="tickets.php" href="#tickets/<?php echo $ticket->getId(); ?>/assign/agents"><i class="icon-user"></i> <?php echo __('Agent'); ?></a>
                    <a class="dropdown-item ticket-action" data-redirect="tickets.php" href="#tickets/<?php echo $ticket->getId(); ?>/assign/teams"><i class="icon-group"></i> <?php echo __('Team'); ?></a>
            
                </div>
            </div>
      <?php } ?>
                
              
           
                
                <?php if ($role->hasPerm(Ticket::PERM_REPLY)) { ?>
                    
                    <a class="btn btn-light waves-effect  <?php If  (!$topicset) { echo "hidden";} ?>" href="#reply" class="post-response" id="post-reply" data-placement="bottom" data-toggle="tooltip" title="<?php echo __('Post Reply'); ?>">
                    <i class="fa fa-reply"></i></a>
                         
                <?php }  ?> 
                
                    <a class="btn btn-light waves-effect <?php If  (!$topicset) { echo "hidden";} ?>" href="#note" id="post-note" class="post-response" data-placement="bottom" data-toggle="tooltip"title="<?php echo __('Post Internal Note'); ?>">
                    <i class="fa fa-pencil-square-o"></i></a>
                   <!-- <a class="btn btn-light waves-effect" href="#tasks" id="quicktask" class="post-response" data-placement="bottom" data-toggle="tooltip"title="<?php echo __('Tasks'); ?>">
                    <i class="fa fa-check-square-o"></i></a> -->
                
           			 <?php	
                
                 if ($thisstaff->hasPerm(Email::PERM_BANLIST)
                        || $role->hasPerm(Ticket::PERM_EDIT)
                        || ($dept && $dept->isManager($thisstaff))) { ?>        
        
                    <div class="btn-group btn-group-sm" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-light dropdown-toggle waves-effect" 
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-placement="bottom" data-toggle="tooltip" 
                    title="<?php echo __('More'); ?>"> <i class="icon-cog"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right " aria-labelledby="btnGroupDrop1">
                
                <?php
                     if ($role->hasPerm(Ticket::PERM_EDIT)) { ?>
                        <a class="dropdown-item ticket-action" href="#tickets/<?php
                        echo $ticket->getId(); ?>/change-user"><i class="icon-user"></i> <?php
                        echo __('Change Owner'); ?></a>
                    <?php
                     }
                     if($ticket->isOpen() && ($dept && $dept->isManager($thisstaff))) {
                        if($ticket->isAssigned()) { ?>
                            <a class="dropdown-item " id="ticket-release" href="#release"><i class="icon-user"></i> <?php
                                echo __('Release (unassign) Ticket'); ?></a>
                        <?php
                        }
                        if(!$ticket->isOverdue()) { ?>
                            <a class="dropdown-item" id="ticket-overdue" href="#overdue"><i class="icon-bell"></i> <?php
                                echo __('Mark as Overdue'); ?></a>
                        <?php
                        }
                        if($ticket->isOverdue()) { ?>
                            <a class="dropdown-item" id="ticket-overdue" href="#overdue"><i class="icon-bell"></i> <?php
                                echo __('Unmark as Overdue'); ?></a>
                        <?php
                        }
                        if($ticket->isAnswered()) { ?>
                        <a class="dropdown-item" id="ticket-unanswered" href="#unanswered"><i class="icon-circle-arrow-left"></i> <?php
                                echo __('Mark as Unanswered'); ?></a>
                        <?php
                        } else { ?>
                        <a class="dropdown-item" id="ticket-answered" href="#answered"><i class="icon-circle-arrow-right"></i> <?php
                                echo __('Mark as Answered'); ?></a>
                        <?php
                        }
                    } 
                    
                    if ($role->hasPerm(Ticket::PERM_MERGE) && !$ticket->isChild()) { ?>
                     <li><a href="#ajax.php/tickets/<?php echo $ticket->getId();
                         ?>/merge" onclick="javascript:
                         $.dialog($(this).attr('href').substr(1), 201);
                         return false"
                         ><i class="icon-code-fork"></i> <?php echo __('Merge Tickets'); ?></a></li>
                 <?php
                  }

                 if ($role->hasPerm(Ticket::PERM_LINK) && $ticket->getMergeType() == 'visual') { ?>
                     <li><a href="#ajax.php/tickets/<?php echo $ticket->getId();
                         ?>/link" onclick="javascript:
                         $.dialog($(this).attr('href').substr(1), 201);
                         return false"
                         ><i class="icon-link"></i> <?php echo __('Link Tickets'); ?></a></li>
                 
                                 <?php
                if ($role->hasPerm(Ticket::PERM_REFER)) { ?>
                <li><a href="#tickets/<?php echo $ticket->getId();
                    ?>/referrals" class="dropdown-item ticket-action"
                     data-redirect="tickets.php?id=<?php echo $ticket->getId(); ?>" >
                       <i class="icon-exchange"></i> <?php echo __('Manage Referrals'); ?></a></li>
                <?php
                } ?>
                 <?php
                 }
                    if ($role->hasPerm(Ticket::PERM_EDIT)) { ?>
                    <a class="dropdown-item" href="#ajax.php/tickets/<?php echo $ticket->getId();
                        ?>/forms/manage" onclick="javascript:
                        $.dialog($(this).attr('href').substr(1), 201);
                        return false"
                        ><i class="icon-paste"></i> <?php echo __('Manage Forms'); ?></a>
                    <?php
                    } 
                    
                    
                                    if ($role->hasPerm(Ticket::PERM_REPLY) && $thread && $ticket->getId() == $thread->getObjectId()) {
                    ?>
                   <?php
                    $recipients = __(' Manage Collaborators');

                    echo sprintf('<a class="dropdown-item collaborators manage-collaborators"
                            href="#thread/%d/collaborators/1"><i class="icon-group"></i>%s</a>',
                            $ticket->getThreadId(),
                            $recipients);
                   
               			}                 
                    if ($thisstaff->hasPerm(Email::PERM_BANLIST)) {
                         if(!$emailBanned) {?>
                            <a class="dropdown-item ticket-action" id="ticket-banemail"
                                href="#banemail"><i class="icon-ban-circle"></i> <?php echo sprintf(
                                    Format::htmlchars(__('Ban Email <%s>')),
                                    $ticket->getEmail()); ?></a>
                    <?php
                         } elseif($unbannable) { ?>
                            <a  class="dropdown-item ticket-action" id="ticket-banemail"
                                href="#unbanemail"><i class="icon-undo"></i> <?php echo sprintf(
                                    Format::htmlchars(__('Unban Email <%s>')),
                                    $ticket->getEmail()); ?></a>
                        <?php
                         }
                      }
                      if ($role->hasPerm(Ticket::PERM_DELETE)) {
                         ?>
                        <a class="dropdown-item ticket-action" href="#tickets/<?php
                        echo $ticket->getId(); ?>/status/delete"
                        data-redirect="tickets.php"><i class="icon-trash"></i> <?php
                        echo __('Delete Ticket'); ?></a>
                    <?php
                     }
                    ?>
          
        </div>
      </div>
      <?php
                }
                ?>
        <a class="btn btn-light btn-sm waves-effect" href="#" data-stop="top" data-placement="bottom" data-toggle="tooltip" title="<?php echo __('Scroll Top'); ?>">
                    <i class="icon-chevron-up"></i></a>	
                    
        <a class="btn btn-light btn-sm waves-effect" data-placement="bottom"  data-toggle="tooltip" title="<?php echo __('Tickets'); ?>"
                    href="tickets.php<?php ?>"><i class="icon-list-alt"></i></a>			
                
    </div>
<div class="clearfix"></div>
          
</div>
<!--End of Subnav -->

<?php
if (!$topicset) { ?>
<div id="topicwarning" class="alert alert-danger">
      <strong>Type!</strong> Please set the Type..
</div>
 <?php } 


if($ticket->isOverdue()) { ?>
<div class="alert alert-warning">
      <strong>Overdue!</strong> Ticket is maked overdue..
</div>
 <?php }

if ($errors['err'] && isset($_POST['a'])) {
    // Reflect errors back to the tab.
    $errors[$_POST['a']] = $errors['err'];
} elseif($warn) { ?>
    <div class="alert alert-warning"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <?php echo $warn; ?></div>
<?php
} ?>
<div id="msg_notice" class="alert alert-success" style="display: none;"><i class="fa fa-check-square" aria-hidden="true"></i> <span id="msg-txt"><?php echo $msg ?: ''; ?></span></div>
 
<div class="card-box"> <!--ticketinfo-->
<fieldset>
<div class="row ticketform boldlabels">
	<div class='col-sm-3'>   
		<div>
			<label><?php echo __('Issue Summary');?>:</label>
			<a class="inline-edit" data-placement="bottom" data-toggle="tooltip" title="<?php echo __('Update'); ?>"
		                        href="#tickets/<?php echo $ticket->getId(); ?>/field/20/edit">
		                        <span id="field_20">
		                        <?php echo Format::htmlchars( $subject_field->display($ticket->getSubject()));
		                        ?></span>
		                    </a>         
		</div>
		<div>
				<label><?php echo __('Status');?>:</label>
				
				<?php	                   
				// Map states to actions
				$actions= array(
				        'closed' => array(
				            'icon'  => 'icon-ok-circle',
				            'action' => 'close',
				            'href' => 'tickets.php'
				            ),
				        'open' => array(
				            'icon'  => 'icon-undo',
				            'action' => 'reopen'
				            ),
				        );

				$states = array('open');
				if (!$ticket || $ticket->isCloseable())
				    $states[] = 'closed';

				$statusId = $ticket ? $ticket->getStatusId() : 0;
				$nextStatuses = array();
				foreach (TicketStatusList::getStatuses(
				            array('states' => $states)) as $status) {
				    if (!isset($actions[$status->getState()])
				            || $statusId == $status->getId())
				        continue;
				    $nextStatuses[] = $status;
				}

				if (!$nextStatuses)
				    return;
				?>

				<div class="btn-group btn-group-sm" role="group">
				<button id="btnGroupDrop1" type="button" class="btn m-l--10" style="top: -1px; box-shadow: 0 0 0 0;"
				data-toggle="dropdown" data-placement="bottom" data-toggle="dropdown" 
				 title="<?php echo __('Change Status'); ?>"><?php echo ($S = $ticket->getStatus()) ? $S->display() : ''; ?>
				</button>
				    <div class="dropdown-menu " aria-labelledby="btnGroupDrop1">
				        
				   <?php foreach ($nextStatuses as $status) { ?>
				
				    <a class="dropdown-item no-pjax <?php

				        echo $ticket? 'ticket-action' : 'tickets-action'; ?>"
				        href="<?php
				            echo sprintf('#%s/status/%s/%d',
				                    $ticket ? ('tickets/'.$ticket->getId()) : 'tickets',
				                    $actions[$status->getState()]['action'],
				                    $status->getId()); ?>"
				        <?php
				        if (isset($actions[$status->getState()]['href']))
				            echo sprintf('data-redirect="%s"',
				                    $actions[$status->getState()]['href']);
				        ?>
				        ><i class="<?php
				                echo $actions[$status->getState()]['icon'] ?: 'icon-tag';
				            ?>"></i> <?php
				                echo __($status->getName()); ?></a>
				
				<?php
				} ?>
				    
				    </div>
				</div>
		</div>		
		<div>
			<label><?php echo __('Priority');?>:</label>
				<?php
				if ($role->hasPerm(Ticket::PERM_EDIT)
				  && ($pf = $ticket->getPriorityField())) { ?>
				     
				       <a class="inline-edit" data-placement="bottom" data-toggle="tooltip" title="<?php echo __('Update'); ?>"
				           href="#tickets/<?php echo $ticket->getId();?>/field/<?php echo $pf->getId();?>/edit">
				           <span id="field_<?php echo $pf->getId(); ?>"><?php echo $pf->getAnswer()->display(); ?></span>
				       </a>
				   
				<?php } else { ?>
				     <?php echo $ticket->getPriority(); ?>
				<?php } ?>

		</div>	
		<div>
			<label><?php echo __('Department');?>:</label>
				<?php
	          if ($role->hasPerm(Ticket::PERM_TRANSFER)) {?>
	           
	                 <a class="ticket-action" data-placement="bottom" data-toggle="tooltip" title="<?php echo __('Transfer'); ?>"
	                   data-redirect="tickets.php?id=<?php echo $ticket->getId(); ?>"
	                   href="#tickets/<?php echo $ticket->getId(); ?>/transfer"
	                   onclick="javascript:
	                       saveDraft();"
	                   ><?php echo Format::htmlchars($ticket->getDeptName()); ?>
	               </a>
	            
	         <?php
	         }else {?>
	           <?php echo Format::htmlchars($ticket->getDeptName()); ?>
	         <?php } ?>
		</div>
		
		<div>
			
			<?php
	    if($ticket->isOpen()) { ?>
	    
	        <label><?php echo __('Assigned To');?>:</label>
	        <?php
	        if ($role->hasPerm(Ticket::PERM_ASSIGN)) {?>
	     
	            <a class="inline-edit" data-placement="bottom" data-toggle="tooltip" title="<?php echo __('Update'); ?>"
	                href="#tickets/<?php echo $ticket->getId(); ?>/assign">
	                <span id="field_assign">
	                    <?php if($ticket->isAssigned())
	                            echo Format::htmlchars(implode('/', $ticket->getAssignees()));
	                          else
	                            echo '<span class="faded">&mdash; '.__('Unassigned').' &mdash;</span>';
	            ?></span>
	            </a>
	       
	        <?php
	        } else { ?>
	    
	          <?php
	          if($ticket->isAssigned())
	              echo Format::htmlchars(implode('/', $ticket->getAssignees()));
	          else
	              echo '<span class="faded">&mdash; '.__('Unassigned').' &mdash;</span>';
	        
	        } 
	    } else { ?>
	    
	        <label><?php echo __('Closed By');?>:</label>
	       
	            <?php
	            if(($staff = $ticket->getStaff()))
	                echo Format::htmlchars($staff->getName());
	            else
	                echo '<span class="faded">&mdash; '.__('Unknown').' &mdash;</span>';
	            ?>
	    
	    <?php
	    } ?>
		
		</div>		
				
	</div>


	<div class='col-sm-3'>
		<div>
			<label><?php echo __('Create Date');?>:</label>
				<?php echo Format::datetime($ticket->getCreateDate()); ?>
		</div>
	  	  <?php
	      if($ticket->isOpen()){ ?>
	      <div>
	          <label><?php echo __('Due Date');?>:</label>
	          <?php
	               if ($role->hasPerm(Ticket::PERM_EDIT)) {
	                   $duedate = $ticket->getField('duedate'); ?>
	               
	            <a class="inline-edit" data-placement="bottom"
	                href="#tickets/<?php echo $ticket->getId();
	                 ?>/field/duedate/edit">
	                 <span id="field_duedate"><?php echo Format::datetime($ticket->getEstDueDate()); ?></span>
	            </a>
	         
	            <?php } else { ?>
	                 <?php echo Format::datetime($ticket->getEstDueDate()); ?>
	            <?php } ?>
	      </div>
	      <?php
	      }else { ?>
	      <div>
	          <label><?php echo __('Close Date');?>:</label>
	        <?php echo Format::datetime($ticket->getCloseDate()); ?>
	      </div>
	      <?php
	      }
	      ?>
		<div>
			<label><?php echo __('SLA Plan');?>:</label>
			 <?php
	           if ($role->hasPerm(Ticket::PERM_EDIT)) {
	               $slaField = $ticket->getField('sla'); ?>
	            <a class="inline-edit" data-placement="bottom" data-toggle="tooltip" title="<?php echo __('Update'); ?>"
	            href="#tickets/<?php echo $ticket->getId(); ?>/field/sla/edit">
	            <span id="field_sla"><?php echo $sla ?: __('None'); ?></span>
	        </a>
	        <?php } else { ?>
	          <span id="field_sla"><?php echo $sla ?: __('None'); ?></span>
	        <?php } ?>
		</div>	
		<div>
		<label><?php echo __('Help Topic');?>:</label>
			<?php
	                         if ($role->hasPerm(Ticket::PERM_EDIT)) {
	                             $topic = $ticket->getField('topic'); ?>
	                          
	                      <a class="inline-edit" data-placement="bottom"
	                          data-toggle="tooltip" title="<?php echo __('Update'); ?>"
	                          href="#tickets/<?php echo $ticket->getId(); ?>/field/topic/edit">
	                          <span id="field_topic">
	                              <?php echo $ticket->getHelpTopic() ?: __('None'); ?>
	                          </span>
	                      </a>
	                   
	                      <?php } else { ?>
	                           <?php echo Format::htmlchars($ticket->getHelpTopic()); ?>
	                      <?php } ?>
		</div>	 
		<div>
			<label></label>
				
		</div>	 
	</div>	
	
	<div class='col-sm-3'> 
				<div>
			<label><?php echo __('Last Message');?>:</label>
				<?php echo Format::datetime($ticket->getLastMsgDate()); ?>
		</div>	 
				<div>
			<label><?php echo __('Last Response');?>:</label>
				<?php echo Format::datetime($ticket->getLastRespDate()); ?>
		</div>	 
				<div>
			<label><?php echo __('Days Open');?>:</label>
			<span class="badge badge-danger "><?php echo $ticket->getDaysOpen(); ?></span>
				
		</div>	 
				<div>
			<label><?php echo __('Time Spent');?>:</label>
			<?php 
			$elapsedtime = $ticket->getTimeSpent();
			if (strlen($elapsedtime)==0) {$elapsedtime = '0 Minutes';} ?>
			<span class="badge badge-danger "><?php echo $elapsedtime;?></span>
				
		</div>	 
	</div>	
	
  <div class='col-sm-3'>  
		<div>
			<label><?php echo __('User'); ?>:</label>
				<a href="#tickets/<?php echo $ticket->getId(); ?>/user"
	       onclick="javascript:
	           saveDraft();
	           $.userLookup('ajax.php/tickets/<?php echo $ticket->getId(); ?>/user',
	                   function (user) {
	                       $('#user-'+user.id+'-name').text(user.name);
	                       $('#user-'+user.id+'-email').text(user.email);
	                       $('#user-'+user.id+'-phone').text(user.phone);
	                       $('select#emailreply option[value=1]').text(user.name+' <'+user.email+'>');
	                   });
	           return false;
	           "><i class="icon-user"></i> <span id="user-<?php echo $ticket->getOwnerId(); ?>-name"
	           ><?php echo Format::htmlchars($ticket->getName());
	       ?></span></a>
	       <?php
	       if ($user) { ?>
	           	            
	            <div class="btn-group btn-group-sm" role="group">
				       	
				       	<button type="button" class="btn btn-sm m-l--10" data-toggle="dropdown" data-placement="bottom" 
				       		title="Related Tickets" aria-expanded="false" style="top: -1px; box-shadow: 0 0 0 0;">
				       		
				       		<a class="" href="" 
				        	title="<?php echo __('Related Tickets'); ?>"><span class="badge label-table badge-primary"><?php echo $user->getNumTickets(); ?></span></a>				
				       		</button>
	           
		           <div id="action-dropdown-stats" class="dropdown-menu">
		               
		                   <?php
		                   if(($open=$user->getNumOpenTickets()))
		                       echo sprintf('<a class="dropdown-item" href="tickets.php?a=search&status=open&uid=%s"><i class="icon-folder-open-alt icon-fixed-width"></i> %s</a></li>',
		                               $user->getId(), sprintf(_N('%d Open Ticket', '%d Open Tickets', $open), $open));

		                   if(($closed=$user->getNumClosedTickets()))
		                       echo sprintf('<a class="dropdown-item" href="tickets.php?a=search&status=closed&uid=%d"><i
		                               class="icon-folder-close-alt icon-fixed-width"></i> %s</a></li>',
		                               $user->getId(), sprintf(_N('%d Closed Ticket', '%d Closed Tickets', $closed), $closed));
		                   ?>
		                   <a class="dropdown-item" href="tickets.php?a=search&uid=<?php echo $ticket->getOwnerId(); ?>"><i class="icon-double-angle-right icon-fixed-width"></i> <?php echo __('All Tickets'); ?></a>
										<?php   if ($thisstaff->hasPerm(User::PERM_DIRECTORY)) { ?>
		                   <a class="dropdown-item" href="users.php?id=<?php echo
		                   $user->getId(); ?>"><i class="icon-user
		                   icon-fixed-width"></i> <?php echo __('Manage User'); ?></a>
										<?php   } ?>
		               
		           </div>
	           </div>
	           <?php
	           if ($role->hasPerm(Ticket::PERM_EDIT) && $thread && $ticket->getId() == $thread->getObjectId()) {
	               if ($thread) {
	                   $numCollaborators = $thread->getNumCollaborators();
	                   if ($thread->getNumCollaborators())
	                       $recipients = sprintf(__('%d'),
	                               $numCollaborators);
	               } else
	                 $recipients = 0;

	            echo sprintf('<span><a class="manage-collaborators preview"
	                   href="#thread/%d/collaborators/1"><span id="t%d-recipients"><i class="icon-group"></i> <span class="badge badge-warning">%s</span></span></a></span>',
	                   $ticket->getThreadId(),
	                   $ticket->getThreadId(),
	                   $recipients);
	            }?>
						<?php                   } # end if ($user) ?>
							</div>	 
			<div>
				<label><?php echo __('Email'); ?>:</label>
					<span id="user-<?php echo $ticket->getOwnerId(); ?>-email"><?php echo $ticket->getEmail(); ?></span>
			</div>	 	
			
			<?php   if ($user->getOrganization()) { ?>
				<div>
				    <label><?php echo __('Organization'); ?>:</label>
				    
				    <?php 
				    switch($user->getOrganization()->getName()){
				    	
				    case 'CAN':
							$badge = 'bg-warning';
						break;
						case 'EXT':
							$badge = 'bg-flatBrown';
						break;
						case 'IND':
							$badge = 'bg-primary';
						break;
						case 'MEX':
							$badge = 'bg-purple';
						break;
						case 'NTC':
							$badge = 'bg-flatOrange';
						break;
						case 'OH':
							$badge = 'bg-flatpurple';
						break;
						case 'SS':
							$badge = 'bg-flatgrey';
						break;
						case 'TNN1':
							$badge = 'bg-flatbrown';
						break;
						case 'TNN2':
							$badge = 'bg-flatred';
						break;
						case 'TNS':
							$badge = 'bg-flatgreen';
						break;
						case 'VIP':
							$badge = 'bg-vipred';
						break;
						case 'RVC':
							$badge = 'bg-flatbluealt1';
						break;
						case 'RTA':
							$badge = 'bg-flatorangealt1';
						break;
						case 'PAU':
							$badge = 'bg-flatpurplealt1';
						break;
						case 'BRY':
							$badge = 'bg-flatgreenalt3';
				    break;	
				    	
				    }
				    
				     ?>
				    <?php echo '<span class="badge label-table '.$badge.'">'.Format::htmlchars($user->getOrganization()->getName()).'</span>'; ?>
				       <div class="btn-group btn-group-sm" role="group">
				       	
				       	<button type="button" class="btn btn-sm m-l--10" data-toggle="dropdown" data-placement="bottom" 
				       		title="Related Tickets" aria-expanded="false" style="top: -1px; box-shadow: 0 0 0 0;">
				       		
				       		<a class="" href="" 
				        	title="<?php echo __('Related Tickets'); ?>"><span class="badge label-table badge-primary"><?php echo $user->getNumOrganizationTickets(); ?></span></a>				
				       		</button>
				 
				            <div id="action-dropdown-org-stats" class="dropdown-menu">
				                
													<?php   if ($open = $user->getNumOpenOrganizationTickets()) { ?>
				                    
				                    <a class="dropdown-item" href="tickets.php?<?php echo Http::build_query(array(
				                        'a' => 'search', 'status' => 'open', 'orgid' => $user->getOrgId()
				                    )); ?>"><i class="icon-folder-open-alt icon-fixed-width"></i>
				                    <?php echo sprintf(_N('%d Open Ticket', '%d Open Tickets', $open), $open); ?>
				                    </a>
													
													<?php   }
													        if ($closed = $user->getNumClosedOrganizationTickets()) { ?>
				                    
				                    <a class="dropdown-item" href="tickets.php?<?php echo Http::build_query(array(
				                        'a' => 'search', 'status' => 'closed', 'orgid' => $user->getOrgId()
				                    )); ?>"><i class="icon-folder-close-alt icon-fixed-width"></i>
				                    <?php echo sprintf(_N('%d Closed Ticket', '%d Closed Tickets', $closed), $closed); ?>
				                    </a>
				                    
				                    <a class="dropdown-item" href="tickets.php?<?php echo Http::build_query(array(
				                        'a' => 'search', 'orgid' => $user->getOrgId()
				                    )); ?>"><i class="icon-double-angle-right icon-fixed-width"></i> <?php echo __('All Tickets'); ?></a>
													
													<?php   }
													        if ($thisstaff->hasPerm(User::PERM_DIRECTORY)) { ?>
				                    <a class="dropdown-item" href="orgs.php?id=<?php echo $user->getOrgId(); ?>"><i
				                        class="icon-building icon-fixed-width"></i> <?php
				                        echo __('Manage Organization'); ?></a>
													<?php   } ?>
				               				                
										</div>
							 </div>
	  		</div>
												<?php   } # end if (user->org) ?>
																			
				<div>
					<label><?php echo __('Source'); ?>:</label>
						<?php
				                       if ($role->hasPerm(Ticket::PERM_EDIT)) {
				                           $source = $ticket->getField('source');?>
				                  <a class="inline-edit" data-placement="bottom" data-toggle="tooltip" title="<?php echo __('Update'); ?>"
				                      href="#tickets/<?php echo $ticket->getId(); ?>/field/source/edit">
				                      <span id="field_source">
				                      <?php echo Format::htmlchars($ticket->getSource());
				                      ?></span>
				                  </a>
				                    <?php
				                       } else {
				                          echo Format::htmlchars($ticket->getSource());
				                      }

				                  if (!strcasecmp($ticket->getSource(), 'Web') && $ticket->getIP())
				                      echo '&nbsp;&nbsp; <span class="faded">('.Format::htmlchars($ticket->getIP()).')</span>';
				                  ?>
				</div>	 	
		</div> 
</div>	


</div> <!-- ticket info -->


<?php
foreach (DynamicFormEntry::forTicket($ticket->getId()) as $form) {
    $form->addMissingFields();
    //Find fields to exclude if disabled by help topic
    $disabled = Ticket::getMissingRequiredFields($ticket, true);

    // Skip core fields shown earlier in the ticket view
    // TODO: Rewrite getAnswers() so that one could write
    //       ->getAnswers()->filter(not(array('field__name__in'=>
    //           array('email', ...))));
    $answers = $form->getAnswers()->exclude(Q::any(array(
        'field__flags__hasbit' => DynamicFormField::FLAG_EXT_STORED,
        'field__name__in' => array('subject', 'priority'),
        'field__id__in' => $disabled,
    )));
    $displayed = array();
    foreach($answers as $a) {
        if (!$a->getField()->isVisibleToStaff())
            continue;
        $displayed[] = $a;
    }
    if (count($displayed) == 0)
        continue;
    ?>
    <table class="ticket_info custom-data" cellspacing="0" cellpadding="0" width="940" border="0">
    <thead>
        <th colspan="2"><?php echo Format::htmlchars($form->getTitle()); ?></th>
    </thead>
    <tbody>
<?php
    foreach ($displayed as $a) {
        $id =  $a->getLocal('id');
        $label = $a->getLocal('label');
        $v = $a->display();
        $field = $a->getField();
        $class = (Format::striptags($v)) ? '' : 'class="faded"';
        $clean = (Format::striptags($v)) ? $v : '&mdash;' . __('Empty') .  '&mdash;';
        $isFile = ($field instanceof FileUploadField);
?>
        <tr>
            <td width="200"><?php echo Format::htmlchars($label); ?>:</td>
            <td id="<?php echo sprintf('inline-answer-%s', $field->getId()); ?>">
            <?php if ($role->hasPerm(Ticket::PERM_EDIT)
                    && $field->isEditableToStaff()) {
                    $isEmpty = strpos($v, 'Empty');
                    if ($isFile && !$isEmpty) {
                        echo sprintf('<span id="field_%s" %s >%s</span><br>', $id,
                            $class,
                            $clean);
                    }
                         ?>
                  <a class="inline-edit" data-placement="bottom" data-toggle="tooltip" title="<?php echo __('Update'); ?>"
                      href="#tickets/<?php echo $ticket->getId(); ?>/field/<?php echo $id; ?>/edit">
                  <?php
                    if ($isFile && !$isEmpty) {
                      echo "<i class=\"icon-edit\"></i>";
                    } elseif (strlen($v) > 200) {
                      $clean = Format::truncate($v, 200);
                      echo sprintf('<span id="field_%s" %s >%s</span>', $id, $class, $clean);
                      echo "<br><i class=\"icon-edit\"></i>";
                    } else
                        echo sprintf('<span id="field_%s" %s >%s</span>', $id, $class, $clean);

                    $a = $field->getAnswer();
                    $hint = ($field->isRequiredForClose() && $a && !$a->getValue() && get_class($field) != 'BooleanField') ?
                        sprintf('<i class="icon-warning-sign help-tip warning field-label" data-title="%s" data-content="%s"
                        /></i>', __('Required to close ticket'),
                        __('Data is required in this field in order to close the related ticket')) : '';
                    echo $hint;
                  ?>
              </a>
            <?php
            } else {
                echo $clean;
            } ?>
            </td>
        </tr>
<?php } ?>
    </tbody>
    </table>
<?php } ?>
<div class="clear"></div>
<div class="card-box p-b-0">
<?php
$tcount = $ticket->getThreadEntries($types) ? $ticket->getThreadEntries($types)->count() : 0;
?>
<ul  class="tabs nav nav-tabs" id="ticket_tabs" >
    <li class="nav-item active"><a class="nav-link" id="ticket-thread-tab" href="#ticket_thread"><?php
         echo sprintf(__('Ticket Thread <span class="badge badge-primary badge-pill">%d</span>'), $tcount); ?></a></li>
    <li class="nav-item"><a class="nav-link" id="ticket-tasks-tab" href="#tasks"
            data-url="<?php
        echo sprintf('#tickets/%d/tasks', $ticket->getId()); ?>"><?php
        echo __('Tasks');
        if ($ticket->getNumTasks())
            echo sprintf('&nbsp;<span class="badge badge-primary badge-pill" id="ticket-tasks-count">%d</span>', $ticket->getNumTasks());
        ?></a></li>
    <?php
    if ((count($children) != 0 || $ticket->isChild())) { ?>
    <li class="nav-item"><a class="nav-link" href="#relations" id="ticket-relations-tab"
        data-url="<?php
        echo sprintf('#tickets/%d/relations', $ticket->getId()); ?>"
        ><?php echo __('Related Tickets');
        if (count($children))
            echo sprintf('&nbsp;<span class="badge badge-primary badge-pill" id="ticket-relations-count">%d</span>', count($children));
        elseif ($ticket->isChild())
            echo sprintf('&nbsp;<span class="badge badge-primary badge-pill" id="ticket-relations-count">%d</span>', 1);
        ?></a></li>
    <?php
    }
    ?>

</ul>

<div id="ticket_tabs_container">
<div id="ticket_thread" class="tab_content">

<?php
    // Render ticket thread
    if ($thread)
        $thread->render(
                array('M', 'R', 'N'),
                array(
                    'html-id'   => 'ticketThread',
                    'mode'      => Thread::MODE_STAFF,
                    'sort'      => $thisstaff->thread_view_order
                    )
                );
?>
<div class="clear"></div>

<div id="UpdateArea"  <?php if (!$topicset) { echo ' class="hidden"';} ?>>
<div class="sticky bar stop actions " id="response_options">
	<div id="ReponseTabs">   
   
    <ul  class="nav nav-pills" id="response-tabs">
        <?php
        if ($role->hasPerm(Ticket::PERM_REPLY) && !($blockReply)) { ?>
        <li class="nav-item" <?php
            echo isset($errors['reply']) ? 'error' : ''; ?>"><a class="nav-link active"
            href="#reply" data-toggle="tab" ><?php echo __('Post Reply');?></a></li>
        <?php
        }
        if (!($blockReply)) { ?>
        <li class="nav-item <?php
            echo isset($errors['postnote']) ?  'error' : ''; ?>"><a class="nav-link" href="#note" data-toggle="tab"><?php echo __('Post Internal Note');?></a></li>
        <?php
        } ?>
    </ul>

    	<div class="tab-content clearfix">
					<div class="tab-pane active" id="reply">
							    <?php
							    if ($role->hasPerm(Ticket::PERM_REPLY) && !($blockReply)) {
							        $replyTo = $_POST['reply-to'] ?: 'all';
							        $emailReply = ($replyTo != 'none');
							        ?>
							    <form class="spellcheck exclusive save"
							        data-lock-object-id="ticket/<?php echo $ticket->getId(); ?>"
							        data-lock-id="<?php echo $mylock ? $mylock->getId() : ''; ?>"
							        action="tickets.php?id=<?php
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
							                   <label><strong><?php echo __('From'); ?>:</strong></label>
							               </td>
							               <td>
							                   <select id="from_email_id" name="from_email_id">
							                     <?php
							                     // Department email (default).
							                     if (($e=$dept->getEmail())) {
							                        echo sprintf('<option value="%s" selected="selected">%s</option>',
							                                 $e->getId(),
							                                 Format::htmlchars($e->getAddress()));
							                     }
							                     // Optional SMTP addreses user can send email via
							                     if (($emails = Email::getAddresses(array('smtp' =>
							                                 true), false)) && count($emails)) {
							                         echo '<option value=""
							                             disabled="disabled">&nbsp;</option>';
							                         $emailId = $_POST['from_email_id'] ?: 0;
							                         foreach ($emails as $e) {
							                             if ($dept->getEmail()->getId() == $e->getId())
							                                 continue;
							                             echo sprintf('<option value="%s" %s>%s</option>',
							                                     $e->getId(),
							                                      $e->getId() == $emailId ?
							                                      'selected="selected"' : '',
							                                      Format::htmlchars($e->getAddress()));
							                         }
							                     }
							                     ?>
							                   </select>
							               </td>
							           </tr>
							            </tbody>
							            <tbody id="recipients">
							             <tr id="user-row">
							                <td width="120">
							                    <label><strong><?php echo __('Recipients'); ?>:</strong></label>
							                </td>
							                <td><a href="#tickets/<?php echo $ticket->getId(); ?>/user"
							                    onclick="javascript:
							                        $.userLookup('ajax.php/tickets/<?php echo $ticket->getId(); ?>/user',
							                                function (user) {
							                                    window.location = 'tickets.php?id='<?php $ticket->getId(); ?>
							                                });
							                        return false;
							                        "><span ><?php
							                            echo Format::htmlchars($ticket->getOwner()->getEmail()->getAddress());
							                    ?></span></a>
							                </td>
							              </tr>
							               <tr><td>&nbsp;</td>
							                   <td>
							                   <div style="margin-bottom:2px;">
							                    <?php
							                    if ($ticket->getThread()->getNumCollaborators())
							                        $recipients = sprintf(__('(%d of %d)'),
							                                $ticket->getThread()->getNumActiveCollaborators(),
							                                $ticket->getThread()->getNumCollaborators());

							                         echo sprintf('<span"><a id="show_ccs">
							                                 <i id="arrow-icon" class="icon-caret-right"></i>&nbsp;%s </a>
							                                 &nbsp;
							                                 <a class="manage-collaborators
							                                 collaborators preview noclick %s"
							                                  href="#thread/%d/collaborators/1">
							                                 %s</a></span>',
							                                 __('Collaborators'),
							                                 $ticket->getNumCollaborators()
							                                  ? '' : 'hidden',
							                                 $ticket->getThreadId(),
							                                         sprintf('<span id="t%d-recipients">%s</span></a></span>',
							                                             $ticket->getThreadId(),
							                                             $recipients)
							                         );
							                    ?>
							                   </div>
							                   <div id="ccs" class="hidden">
							                     <div>
							                        <span style="margin: 10px 5px 1px 0;" class="faded pull-left"><?php echo __('Select or Add New Collaborators'); ?>&nbsp;</span>
							                        <?php
							                        if ($role->hasPerm(Ticket::PERM_REPLY) && $thread && $ticket->getId() == $thread->getObjectId()) { ?>
																				<div class="btn-group btn-group-sm show" role="group">
														            	<button id="btnGroupDrop1" type="button" class="btn btn-light dropdown-toggle waves-effect" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" 
														            		data-placement="bottom" title="<?php echo __('Manage Collaborators'); ?>"><i class="icon-group"></i>
														            	</button>
														                <div class="dropdown-menu ">
														                    
														                    <a class="dropdown-item ticket-action" href="#thread/<?php echo
							                                $ticket->getThreadId(); ?>/add-collaborator/addcc"><i class="icon-plus"></i> <?php echo __('Add New'); ?></a>
														                    <a class="dropdown-item ticket-action" href="#thread/<?php echo
							                                $ticket->getThreadId(); ?>/collaborators/1"><i class="icon-cog"></i> <?php echo __('Manage Collaborators'); ?></a>
														            
														                </div>
														            </div>
							                         <?php
							                        }  ?>
							                         <span class="error">&nbsp;&nbsp;<?php echo $errors['ccs']; ?></span>
							                        </div>
							                        
							                     <div class="clearfix">
							                      <select class="form-control" id="collabselection" name="ccs[]" multiple="multiple"
							                          data-placeholder="<?php echo __('Select Active Collaborators'); ?>"> <?php
							                          if ($collabs = $ticket->getCollaborators()) {
							                              foreach ($collabs as $c) {
							                                  echo sprintf('<option value="%s" %s class="%s">%s</option>',
							                                          $c->getUserId(),
							                                          $c->isActive() ?
							                                          'selected="selected"' : '',
							                                          $c->isActive() ?
							                                          'active' : 'disabled',
							                                          $c->getName());
							                              }
							                          }
							                          ?>
							                      </select>
							                     </div>
							                     </div>
							                 </td>
							             </tr>
							             <tr>
							                <td width="120">
							                    <label><strong><?php echo __('Reply To'); ?>:</strong></label>
							                </td>
							                <td>
							                    <?php
							                    // Supported Reply Types
							                    $replyTypes = array(
							                            'all'   =>  __('All Active Recipients'),
							                            'user'  =>  sprintf('%s (%s)',
							                                __('Ticket Owner'),
							                                Format::htmlchars($ticket->getOwner()->getEmail())),
							                            'none'  =>  sprintf('&mdash; %s  &mdash;',
							                                __('Do Not Email Reply'))
							                            );

							                    $replyTo = $_POST['reply-to'] ?: 'all';
							                    $emailReply = ($replyTo != 'none');
							                    ?>
							                    <select id="reply-to" name="reply-to">
							                        <?php
							                        foreach ($replyTypes as $k => $v) {
							                            echo sprintf('<option value="%s" %s>%s</option>',
							                                    $k,
							                                    ($k == $replyTo) ?
							                                    'selected="selected"' : '',
							                                    $v);
							                        }
							                        ?>
							                    </select>
							                    <i class="help-tip icon-question-sign" href="#reply_types"></i>
							                </td>
							             </tr>
							           
							             <tr>
							                <td width="120" style="vertical-align:top">
							                    <label><strong><?php echo __('Response');?>:</strong></label>
							                </td>
							                <td>
							                <?php
							                if ($errors['response'])
							                    echo sprintf('<div class="error">%s</div>',
							                            $errors['response']);

							                if ($cfg->isCannedResponseEnabled()) { ?>           
						                  <div>
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
						                    </div>
						                    </td></tr>
						                    <tr><td colspan="2">
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
							                        'Start writing your response here. Use canned responses from the drop-down above'
							                        ); ?>"
							                        rows="9" wrap="soft"
							                        class="hidden <?php if ($cfg->isRichTextEnabled()) echo 'richtext';
							                            ?> draft draft-delete fullscreen" <?php
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
							                <td width="120">
							                    <label for="signature" class="left"><?php echo __('Signature');?>:</label>
							                </td>
							                <td>
							                    <?php
							                    $info['signature']=$info['signature']?$info['signature']:$thisstaff->getDefaultSignatureType();
							                    ?>
							                    <label><input type="radio" name="signature" value="none" checked="checked"> <?php echo __('None');?></label>
							                    <?php
							                    if($thisstaff->getSignature()) {?>
							                    <label><input type="radio" name="signature" value="mine"
							                        <?php echo ($info['signature']=='mine')?'checked="checked"':''; ?>> <?php echo __('My Signature');?></label>
							                    <?php
							                    } ?>
							                    <?php
							                    if($dept && $dept->canAppendSignature()) { ?>
							                    <label><input type="radio" name="signature" value="dept"
							                        <?php echo ($info['signature']=='dept')?'checked="checked"':''; ?>>
							                        <?php echo sprintf(__('Department Signature (%s)'), Format::htmlchars($dept->getName())); ?></label>
							                    <?php
							                    } ?>
							                </td>
							            </tr>
							            <tr>
							                <td width="120" style="vertical-align:top">
							                    <label><strong><?php echo __('Ticket Status');?>:</strong></label>
							                </td>
							                <td>
							                    <?php
							                    $outstanding = false;
							                    if ($role->hasPerm(Ticket::PERM_CLOSE)
							                            && is_string($warning=$ticket->isCloseable())) {
							                        $outstanding =  true;
							                        echo sprintf('<div class="warning-banner">%s</div>', $warning);
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
							                                    <?php //if ($cfg->isThreadTime()) {
							            if($ticket->isOpen()) { ?>
							            <div><table><tr>
							                <td width="120">
							                    <label><strong>Time Spent:</strong></label>
							                </td>
							                <td>
							                    <input type="text" name="time_spent" size="5"
							                    value="<?php if(isset($_POST['time_spent'])) echo $_POST['time_spent'];?>" />
							                    (Minutes)
							                    <?php if ($cfg->isThreadTimer()) { ?>
							                    <i class="fa fa-play" title="Start / Resume timer"></i>
							                    <i class="fa fa-pause" title="Pause timer"></i>
							                    <i class="fa fa-undo" title="Reset timer to zero"></i>
							                    <?php } ?>
							                </td>
							            </tr>
							            <tr>
							                <td>
							                    <label for="time_type"><strong>Time Type:</strong></label>
							                </td>
							                <td>
							                    <select id="time_type" name="time_type">
							                    <?php
							                    $list = DynamicList::lookup(['type' => 'time-type']);
							                    foreach ($list->getAllItems() as $item) { ?>
							                        <option value="<?php echo $item->getId(); ?>"> <?php echo $item->getValue(); ?> </option>
							<?php               } ?>
							                    </select>
							                </td>
							            </tr></table></div>
							            <?php }//} ?>  
							        <div class="m-t-5">
							            <input class="save pending btn btn-sm btn-success" type="submit" value="<?php echo __('Post Reply');?>">
							            <input class="btn btn-sm btn-warning" type="reset" value="<?php echo __('Reset');?>">
							        </div>
							    </form>
							    <?php
							    }
							    ?>
    	</div>
				
				
	  					<div class="tab-pane" id="note">
    						    <?php
											    if (!($blockReply)) {
											    ?>
											    <form class="spellcheck exclusive save"
											        data-lock-object-id="ticket/<?php echo $ticket->getId(); ?>"
											        data-lock-id="<?php echo $mylock ? $mylock->getId() : ''; ?>"
											        action="tickets.php?id=<?php echo $ticket->getId(); ?>#note"
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
											                </td></tr>
											                <tr><td colspan="2">
											                    <div class="error"><?php echo $errors['note']; ?></div>
											                    <textarea name="note" id="internal_note" cols="80"
											                        placeholder="<?php echo __('Note details'); ?>"
											                        rows="9" wrap="soft"
											                        class="<?php if ($cfg->isRichTextEnabled()) echo 'richtext';
											                            ?> draft draft-delete fullscreen" <?php
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
											            <tr>
											                <td width="120">
											                    <label><?php echo __('Ticket Status');?>:</label>
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
											        </table>
											                            <?php if ($cfg->isThreadTime()) {
											            if($ticket->isOpen()) { ?>
											            <div><table><tr>
											                <td width="120">
											                    <label><strong>Time Spent:</strong></label>
											                </td>
											                <td>
											                    <input type="text" name="time_spent" size="5"
											                    value="<?php if(isset($_POST['time_spent'])) echo $_POST['time_spent'];?>" />
											                    (Minutes)
											                    <?php if ($cfg->isThreadTimer()) { ?>
											                    <i class="fa fa-play" title="Start / Resume timer"></i>
											                    <i class="fa fa-pause" title="Pause timer"></i>
											                    <i class="fa fa-undo" title="Reset timer to zero"></i>
											                    <?php } ?>
											                </td>
											            </tr>
											            <tr>
											                <td>
											                    <label for="time_type"><strong>Time Type:</strong></label>
											                </td>
											                <td>
											                    <select id="time_type" name="time_type">
											                    <?php
											                    $list = DynamicList::lookup(['type' => 'time-type']);
											                    foreach ($list->getAllItems() as $item) { ?>
											                        <option value="<?php echo $item->getId(); ?>"> <?php echo $item->getValue(); ?> </option>
											<?php               } ?>
											                    </select>
											                </td>
											            </tr></table></div>
											            <?php } }?>  
											       <div class="m-t-5">
											           <input class="save pending  btn btn-sm btn-success" type="submit" value="<?php echo __('Post Note');?>">
											           <input class=" btn btn-sm btn-warning" type="reset" value="<?php echo __('Reset');?>">
											       </div>
											   </form>
											   <?php } ?>
									
 					    </div>
	 		</div>
	</div>
</div>	
<div style="display:none;" class="dialog" id="print-options">
    <h3><?php echo __('Ticket Print Options');?></h3>
    <a class="close" href=""><i class="icon-remove-circle"></i></a>
    <hr/>
    <form action="tickets.php?id=<?php echo $ticket->getId(); ?>"
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
        <fieldset class="events">
            <label class="fixed-size" for="events"><?php echo __('Print Events');?>:</label>
            <label class="inline checkbox">
            <input type="checkbox" id="events" name="events" value="1"> <?php echo __('Print Thread Events');?>
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
        <?php echo sprintf(__('Are you sure you want to <b>claim</b> (self assign) %s?'), __('this ticket'));?>
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

    $(document).on('click', 'a.manage-collaborators', function(e) {
        e.preventDefault();
        var url = 'ajax.php/'+$(this).attr('href').substr(1);
        $.dialog(url, 201, function (xhr) {
           var resp = $.parseJSON(xhr.responseText);
           if (resp.user && !resp.users)
              resp.users.push(resp.user);
            // TODO: Process resp.users
           $('.tip_box').remove();
        }, {
            onshow: function() { $('#user-search').focus(); }
        });
        return false;
     });

    $('#post-note').click(function(e){
    	e.preventDefault();
        $('#response-tabs a[href="#note"]').tab('show');
        // Scroll to the response section.
            var $stop = $(document).height();
            var $s = $('div#response_options');
            if ($s.length)
                $stop = $s.offset().top-125
            $('html, body').animate({scrollTop: $stop}, 'fast');
            $("#title").focus()
    })
     $('#post-reply').click(function(e){
    	e.preventDefault();
        $('#response-tabs a[href="#reply"]').tab('show');
        // Scroll to the response section.
            var $stop = $(document).height();
            var $s = $('div#response_options');
            if ($s.length)
                $stop = $s.offset().top-125
            $('html, body').animate({scrollTop: $stop}, 'fast');
                        
    })
    $('#quicktask').click(function(e){
    	e.preventDefault();
        $('.nav-tabs a[href="#tasks"]').tab('show');
        // Scroll to the response section.
         //   var $stop = $(document).height();
         //   var $s = $('div#ticket_tabs');
         //   if ($s.length)
         //       $stop = $s.offset().top-125
         //   $('html, body').animate({scrollTop: $stop}, 'fast');
    })        
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

  $('#show_ccs').click(function() {
    var show = $('#arrow-icon');
    var collabs = $('a#managecollabs');
    $('#ccs').slideToggle('fast', function(){
        if ($(this).is(":hidden")) {
            collabs.hide();
            show.removeClass('icon-caret-down').addClass('icon-caret-right');
        } else {
            collabs.show();
            show.removeClass('icon-caret-right').addClass('icon-caret-down');
        }
    });
    return false;
   });

  $('.collaborators.noclick').click(function() {
    $('#show_ccs').trigger('click');
   });

  $('#collabselection').select2({
    width: '350px',
    allowClear: true,
    sorter: function(data) {
        return data.filter(function (item) {
                return !item.selected;
                });
    },
    templateResult: function(e) {
        var $e = $(
        '<span><i class="icon-user"></i> ' + e.text + '</span>'
        );
        return $e;
    }
   }).on("select2:unselecting", function(e) {
        if (!confirm(__("Are you sure you want to DISABLE the collaborator?")))
            e.preventDefault();
   }).on("select2:selecting", function(e) {
        if (!confirm(__("Are you sure you want to ENABLE the collaborator?")))
             e.preventDefault();
   }).on('change', function(e) {
    var id = e.currentTarget.id;
    var count = $('li.select2-selection__choice').length;
    var total = $('#' + id +' option').length;
    $('.' + id + '__count').html(count);
    $('.' + id + '__total').html(total);
    $('.' + id + '__total').parent().toggle((total));
   }).on('select2:opening select2:closing', function(e) {
    $(this).parent().find('.select2-search__field').prop('disabled', true);
   });
});
function saveDraft() {
    redactor = $('#response').redactor('plugin.draft');
    if (redactor.opts.draftId)
        $('#response').redactor('plugin.draft.saveDraft');
}



// START - Ticket Time Timer
<?php if ($cfg->isThreadTimer()) { ?>
$('input[name=time_spent]').val(0);        // sets default value to 0 minutes
$('i.fa-play').hide();
var timerOn = true;                        // var to store if the timer is on or off

setInterval(function() {
    $('input[name=time_spent]').each(function() {
        if (timerOn) $(this).val(parseInt($(this).val()) + 1);
    });
}, 60000);

$('i.fa-undo').click(function() {
    $('input[name=time_spent]').val(0);        // sets default value to 0 minutes
    return false;
});

$('i.fa-play').click(function() {
    timerOn = true;
    $('i.fa-play').hide();
    $('i.fa-pause').show();
    return false;
});
$('i.fa-pause').click(function() {
    timerOn = false;
    $('i.fa-pause').hide();
    $('i.fa-play').show();
    return false;
});
<?php } ?>
// END - Ticket Time Timer
</script>