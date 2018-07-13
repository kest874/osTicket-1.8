<?php
//Note that ticket obj is initiated in tickets.php.
if(!defined('OSTSCPINC') || !$thisstaff || !is_object($ticket) || !$ticket->getId()) die('Invalid path');
//Make sure the staff is allowed to access the page.
//if(!@$thisstaff->isStaff() || !$ticket->checkStaffPerm($thisstaff)) die('Access Denied');
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
$topic = $ticket->getHelpTopicId();
if (!$lock && $cfg->getTicketLockMode() == Lock::MODE_ON_VIEW)
    $lock = $ticket->acquireLock($thisstaff->getId());
$mylock = ($lock && $lock->getStaffId() == $thisstaff->getId()) ? $lock : null;
$id    = $ticket->getId();    //Ticket ID.
//Useful warnings and errors the user might want to know!
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

$staffpermission = $ticket->checkStaffPerm($thisstaff);
$assigned = ($team->id == $thisstaff->dept_id ? 1:0);
$haspermission = ($staffpermission == true || $assigned == true ? 1:0);

?>


<div class="subnav">

    <div class="float-left subnavtitle" id="ticketviewtitle">
        <a href="tickets.php?id=<?php echo $ticket->getId(); ?>" title="<?php echo __('Reload'); ?>"><i class="icon-refresh"></i>
            <?php echo sprintf(__('Incident #%s'), $ticket->getNumber()); ?></a>
                
                <span  class=""> - <span style="color: <?php echo $ticket->isOpen() ? '#51c351;' : '#f00;'; ?>">
                <?php echo sprintf(__('%s'), $ticket->getStatus()); ?></span></span>
                
                - <?php $subject_field = TicketForm::getInstance()->getField('subject');
                echo $subject_field->display($ticket->getSubject()); ?> 

    </div>

    <div class="btn-group btn-group-sm float-right m-b-10" role="group" aria-label="Button group with nested dropdown">

        <div class="btn-group btn-group-sm hidden-xs-down" role="group">
            <button id="btnGroupDrop1" type="button" class="btn btn-light dropdown-toggle waves-effect " 
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-placement="bottom" data-toggle="tooltip" 
            title="<?php echo __('Print'); ?>"><i class="fa fa-print"></i>
            </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        
                <a class="dropdown-item" target="_blank" href="tickets.php?id=<?php echo $ticket->getId(); ?>&a=print&notes=0"><i
                            class="fa fa-file"></i> <?php echo __('Incident Timeline'); ?></a>
                            <a class="dropdown-item" target="_blank" href="tickets.php?id=<?php echo $ticket->getId(); ?>&a=print&notes=1"><i
                            class="fa fa-file-alt"></i> <?php echo __('Thread + Notes'); ?></a>
                
                </div>
        </div>
                   
        <?php
            if ($topic){
                // Status change options
                    echo TicketStatus::status_options();
            }
        ?>

        <?php
            // Assign
            if ($ticket->isOpen() && $role->hasPerm(Ticket::PERM_ASSIGN)) {?>

            <div class="btn-group btn-group-sm" role="group">
            <button id="btnGroupDrop1" type="button" class="btn btn-light dropdown-toggle waves-effect" 
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-placement="bottom" data-toggle="tooltip" 
            title="<?php echo $ticket->isAssigned() ? __('Assign') : __('Reassign'); ?>"><i class="fa fa-user"></i>
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
                    <a class="dropdown-item ticket-action" data-redirect="tickets.php" href="#tickets/<?php echo $ticket->getId(); ?>/assign/agents"><i class="fa fa-user"></i> <?php echo __('Agent'); ?></a>
                    <a class="dropdown-item ticket-action" data-redirect="tickets.php" href="#tickets/<?php echo $ticket->getId(); ?>/assign/teams"><i class="fa fa-users"></i> <?php echo __('Team'); ?></a>
            
                </div>
            </div>
      <?php } ?>
                
            <a  class="btn btn-light waves-effect" id="savebutton" onclick="document.getElementById('save').submit();" 
            data-placement="bottom" data-toggle="tooltip" title="<?php echo __('Save'); ?>"><i class="fa fa-save"></i></a>
         
            <a class="btn btn-light waves-effect" id="cancelbutton" href="" onclick="window.location.href="tickets.php?id=<?php echo $ticket->getId(); ?>" 
            data-placement="bottom" data-toggle="tooltip" title="<?php echo __('Cancel');?>" ><i class="fa fa-ban"></i></a>		
                    
            <?php If  ($topic) { ?>
                
                                
                    <a class="btn btn-light waves-effect" href="#note" id="post-note" class="post-response" data-placement="bottom" data-toggle="tooltip"title="<?php echo __('Add Note'); ?>">
                    <i class="fa fa-sticky-note"></i></a>
                
            <?php	}
                
                 if ($thisstaff->hasPerm(Email::PERM_BANLIST)
                        || $role->hasPerm(Ticket::PERM_EDIT)
                        || ($dept && $dept->isManager($thisstaff))) { ?>        
        
                    <div class="btn-group btn-group-sm" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-light dropdown-toggle waves-effect" 
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-placement="bottom" data-toggle="tooltip" 
                    title="<?php echo __('More'); ?>"> <i class="fa fa-cog"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right " aria-labelledby="btnGroupDrop1">
                
                <?php
                     if ($role->hasPerm(Ticket::PERM_EDIT)) { ?>
                        <a class="dropdown-item ticket-action" href="#tickets/<?php
                        echo $ticket->getId(); ?>/change-user"><i class="fa fa-user"></i> <?php
                        echo __('Change Owner'); ?></a>
                    <?php
                     }
                     if($ticket->isOpen() && ($dept && $dept->isManager($thisstaff))) {
                        if($ticket->isAssigned()) { ?>
                            <a class="dropdown-item " id="ticket-release" href="#release"><i class="fa fa-user"></i> <?php
                                echo __('Release (unassign) Ticket'); ?></a>
                        <?php
                        }
                        if(!$ticket->isOverdue()) { ?>
                            <a class="dropdown-item" id="ticket-overdue" href="#overdue"><i class="fa fa-bell"></i> <?php
                                echo __('Mark as Overdue'); ?></a>
                        <?php
                        }
                        if($ticket->isOverdue()) { ?>
                            <a class="dropdown-item" id="ticket-overdue" href="#overdue"><i class="fa fa-bell"></i> <?php
                                echo __('Unmark as Overdue'); ?></a>
                        <?php
                        }
                        if($ticket->isAnswered()) { ?>
                        <a class="dropdown-item" id="ticket-unanswered" href="#unanswered"><i class="fa fa-circle-arrow-left"></i> <?php
                                echo __('Mark as Unanswered'); ?></a>
                        <?php
                        } else { ?>
                        <a class="dropdown-item" id="ticket-answered" href="#answered"><i class="fa fa-circle-arrow-right"></i> <?php
                                echo __('Mark as Answered'); ?></a>
                        <?php
                        }
                    } ?>
                    <?php
                    if ($role->hasPerm(Ticket::PERM_EDIT)) { ?>
                    <a class="dropdown-item" href="#ajax.php/tickets/<?php echo $ticket->getId();
                        ?>/forms/manage" onclick="javascript:
                        $.dialog($(this).attr('href').substr(1), 201);
                        return false"
                        ><i class="fa fa-paste"></i> <?php echo __('Manage Forms'); ?></a>
                    <?php
                    } 
                    if ($thisstaff->hasPerm(Email::PERM_BANLIST)) {
                         if(!$emailBanned) {?>
                            <a class="dropdown-item ticket-action" id="ticket-banemail"
                                href="#banemail"><i class="fa fa-ban"></i> <?php echo sprintf(
                                    Format::htmlchars(__('Ban Email <%s>')),
                                    $ticket->getEmail()); ?></a>
                    <?php
                         } elseif($unbannable) { ?>
                            <a  class="dropdown-item ticket-action" id="ticket-banemail"
                                href="#unbanemail"><i class="fa fa-undo"></i> <?php echo sprintf(
                                    Format::htmlchars(__('Unban Email <%s>')),
                                    $ticket->getEmail()); ?></a>
                        <?php
                         }
                      }
                      if ($role->hasPerm(Ticket::PERM_DELETE)) {
                         ?>
                        <a class="dropdown-item ticket-action" href="#tickets/<?php
                        echo $ticket->getId(); ?>/status/delete"
                        data-redirect="tickets.php"><i class="fa fa-trash"></i> <?php
                        echo __('Delete Incident'); ?></a>
                    <?php
                     }
                    ?>
          
        </div>
      </div>
      <?php
                }
                ?>
        <a class="btn btn-light btn-sm waves-effect" href="#" data-stop="top" data-placement="bottom" data-toggle="tooltip" title="<?php echo __('Scroll Top'); ?>">
                    <i class="fa fa-chevron-up"></i></a>	
                    
        <a class="btn btn-light btn-sm waves-effect" data-placement="bottom"  data-toggle="tooltip" title="<?php echo __('Tickets'); ?>"
                    href="tickets.php<?php ?>"><i class="fa fa-list-alt"></i></a>			
                
    </div>
<div class="clearfix"></div>
          
</div>
 <?php if (!$topic) { ?>
<div class="alert alert-danger">
      <strong>Category!</strong> Please set the category..
</div>
 <?php } ?>

 
 <?php if($alerttext) { ?>
<div class="alert alert-warning">
      <?php echo $alerttext ;?>
</div>
 <?php } ?>
 <form action="tickets.php?id=<?php echo $ticket->getId(); ?>&a=edit" method="post" id="save"  enctype="multipart/form-data" > 
<div class="card-box">
<?php 
 
$class = ($_REQUEST['reponse']) ? 'queue-' : 'ticket-';
         
        foreach (Messages::getMessages() as $M) {
            $bannerclass = $class.strtolower($M->getLevel());
            $bannermsg = (string) $M; }?>
  
             
<?php
  $outstanding = false;
  if ($role->hasPerm(Ticket::PERM_CLOSE)
          && is_string($warning=$ticket->isCloseable())) {
      $outstanding =  true;
     // echo sprintf('<div class="row task-warning-banner">%s</div>', $warning);
  } ?>
  
 
  
  
      <fieldset>
        
        <div class="row m-t-10 boldlabels">
            <div class='col-sm-3'>    
                <div class='form-group'>
                <?php csrf_token(); ?>
				<input type="hidden" name="do" value="update">
				<input type="hidden" name="a" value="edit">
				<input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">	
                    <div><?php csrf_token(); ?>
				<input type="hidden" name="do" value="update">
				<input type="hidden" name="a" value="edit">
				<input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
                
                <label width="100"><?php echo __('Status');?>:</label> 
                    <?php echo ($S = $ticket->getStatus()) ? $S->display() : ''; ?>
                </div>  
        <div>
                <label><?php echo __('Priority');?>:</label>
                    <?php 
                    
                    switch ($ticket->getPriority()){
               
                case 'Low':
                    $badgecolor = 'bg-success';
                    break;
                case 'Normal':
                    $badgecolor = 'bg-warning';
                    break;
                case 'High':
                    $badgecolor = 'bg-danger';
                    break;
                    
                default:
                    }
                    echo '<span class="badge label-table '.$badgecolor.'">'.$ticket->getPriority().'</span>'; ?>
        </div>
                </div>
            </div>
            <div class='col-sm-3'>    
                <div class='form-group'>
        <div>
            <label><?php echo __('Division');?>:</label>
                    <?php echo Format::htmlchars($ticket->getdeptName()); ?></div>
        <div> 
            <label><?php echo __('Create Date');?>:</label>
                <?php echo Format::datetime($ticket->getCreateDate()); ?></div>
         </div></div>
<div class='col-sm-3'>    
                <div class='form-group'>         
        <?php if($ticket->isOpen()) { ?>
        <div> 
            <label label="180"><?php echo __('Assigned To');?>:</label>
                    
                <?php
                if($ticket->isAssigned())
                    echo Format::htmlchars(implode('/', $ticket->getAssignees()));
                else
                    echo '<span class="faded">&mdash; '.__('Unassigned').' &mdash;</span>';
                ?>
        </div>
               
                <?php
                } else { ?>
                
        <div> 
            <label width="100"><?php echo __('Closed By');?>:</label>
                    
                <?php
                if(($staff = $ticket->getStaff()))
                    echo Format::htmlchars($staff->getName());
                else
                    echo '<span class="faded">&mdash; '.__('Unknown').' &mdash;</span>';
                ?>
        </div>
                
        <?php } ?>
        <div>
            <label><?php echo __('Last Update');?>:</label>
                <?php echo Format::datetime($ticket->lastupdate); ?>
        </div>
                </div>
            </div>
            <div class='col-sm-3 '>
                <div class='form-group'>
            
            <label><?php echo __('Submitter'); ?>:</label>
           
            <select id="user_id" name="user_id"  class="form-control form-control-sm requiredfield" <?php if (!$haspermission) echo "disabled";?> >
                    
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
                </select><?php if ($errors['submitter']){ echo '<span class="error">&nbsp;'.$errors['submitter'].'</span>';}?>
            
        </div>
                </div>
            
            
            </div>
            <div class="row boldlabels">
            <div class="col-sm-3 hidden">
            <div class="form-group">

        <div>
        <?php  
            $duedate = date("m/d/Y", strtotime($ticket->getEstDueDate()));
            if ($duedate == '01/01/1970') {$duedate = NULL;};
        ?>
        
        <?php
            if($ticket->isOpen()){ ?>
            <div class=" <?php if ($errors['duedate']){ echo 'has-danger';}?> hidden">
                                                
            <label class="form-control-label"><?php echo __('Due Date');?>:</label>
            
            <div class="input-group date  <?php if ($errors['duedate']){ echo 'has-danger';}?> " id="datepicker1" >
                    <input type='text' id="duedate" name="duedate" class="form-control form-control-sm <?php if ($errors['duedate']){ echo 'form-control-danger';}?>" value="<?php echo $duedate; ?>" />
                    <span class="input-group-addon <?php if ($errors['duedate']){ echo 'has-danger-important';}?>" style="display: inline">
                        <span class="fa fa-calendar"></span>
                    </span>
          
             </div>
            <?php if ($errors['duedate']){ ?>
            <div class="form-control-feedback-danger"><?php echo $errors['duedate'];?></div>
            <?php } ?>
             </div>    
                <?php
                }else { ?>
                    <label><?php echo __('Close Date');?>:</label>
                    <?php echo Format::datetime($ticket->getCloseDate()); ?>
               
                <?php
                }
                ?>
             
            </div>
            </div> </div>
            <div class="col-sm-3">
                <div class="form-group">
                      <div>
                        <div class=" <?php if ($errors['topicId'] || !$topic){ echo 'has-danger';}?>">
                        <label><?php echo __('Category');?>:</label>
                            <input id="cc" name="topicId" class="easyui-combotree " style="width:95%;  border-radius: 2px !important;"></input>
                            <?php if ($errors['topicId'] || !$topic){ ?>
                            <div class="form-control-feedback-danger"><?php echo __('Help topic selection is required');?></div>
                            <?php }?>
                                  </div>    
                        </div>
                    </div>
            </div>
        
            
            
                     <?php 
			foreach (DynamicFormEntry::forTicket($ticket->getId()) as $form) {
				$form->render(true, false, array('mode'=>'edit','modal'=>'ticketedit','width'=>140,'entry'=>$form));
		} ?>
             
        
    </fieldset>
  </form>

<div class="card-box m-t-10">
<?php
$tcount = $ticket->getThreadEntries($types)->count();
?>
<ul class="nav nav-tabs" id="ticket_tabs" >
    <li class="nav-item "><a class="nav-link active" id="ticket-thread-tab" href="#ticket_thread"  data-toggle="tab"><?php
        echo sprintf(__('Incident Timeline <span class="badge badge-primary badge-pill">%d</span>'), $tcount); ?></a>
    <li class="nav-item"><a class="nav-link" id="ticket-tasks-tab" href="#tasks" data-toggle="tab" ><?php
        echo __('Countermeasures');
        if ($ticket->getNumTasks())
            echo sprintf('&nbsp; <span class="badge badge-primary badge-pill">%d</span>', $ticket->getNumTasks());
        ?></a>
</ul>
<div class="tab-content">
 <div id="tasks" class="tab-pane">
<div id="ticket-tasks">
<?php include STAFFINC_DIR . 'ticket-tasks.inc.php'; ?>
</div>
</div>
<div id="ticket_thread" class="tab-pane active">
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
<div id="updatearea"  <?php if (!$topic) { echo ' class="hidden"';} ?>>
<div class="sticky bar stop actions" id="response_options">
<div id="ReponseTabs" >
    <ul  class="nav nav-pills"  id="ticket_tabs">
		
			<li  class="nav-item" ><a  class="nav-link active" id="ticket-tasks-tab" href="#note" data-toggle="tab" <?php echo isset($errors['note']) ? 'error' : ''; ?>><?php echo __('Add Note');?></a>
			
			
		</ul>
			<div class="tab-content clearfix">

				<div class="tab-pane active" id="note">
                    <form class="spellcheck exclusive save"
        data-lock-object-id="ticket/<?php echo $ticket->getId(); ?>"
        data-lock-id="<?php echo $mylock ? $mylock->getId() : ''; ?>"
        action="tickets.php?<?php echo$qurl.$purl.$qfurl
		?>&id=<?php echo $ticket->getId(); ?>#note"
        name="note" method="post" enctype="multipart/form-data">
        <?php csrf_token(); ?>
        <div class="form-group">
        
        <input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
        <input type="hidden" name="locktime" value="<?php echo $cfg->getLockTime() * 60; ?>">
        <input type="hidden" name="a" value="postnote">
        <input type="hidden" name="lockCode" value="<?php echo $mylock ? $mylock->getCode() : ''; ?>">
        
           
            <div class="form-group">
               
                    <label><strong><?php echo __('Note'); ?>:</strong><span class='error'>&nbsp;*</span></label>
               
                        <div class="faded" style="padding-left:0.15em"><?php
                        echo __('Note title - summary of the note (optional)'); ?></div>
                        <input type="text" name="title" id="title" size="60" value="<?php echo $info['title']; ?>" >
                        <br/>
                        <?php
            if($errors['title']) {?>
                <div class="alert alert-danger">
                    <?php echo $errors['title']; ;?>
                </div>
                    
            <?php
            } ?>
                        
            </div>
           </div>
           <div class="form-group">
            <?php
            if($errors['note']) {?>
                <div class="alert alert-danger">
                    <?php echo $errors['note']; ;?>
                </div>
                    
            <?php
            } ?>
                    
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
                </div>
            <div class="form-group">
                    <label><?php echo __('Incident Status');?>:</label>
                
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
               </div>
        <div>
           <input class="btn btn-primary btn-sm" type="submit" value="<?php echo __('Add Note');?>">
           <input class="btn btn-warning btn-sm" type="reset" value="<?php echo __('Reset');?>">
        </div>
      
   </form>
				</div>
        
			</div>
            </div>
  </div>
   
   </div> <!-- Sticky bar stop -->
 </div> <!-- update area -->
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
            <input type="checkbox" id="notes" name="notes" value="1"> <?php echo __('Print Notes/Comments');?>
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
                <input class="btn btn-warning btn-sm" type="reset" value="<?php echo __('Reset');?>">
                <input class="btn btn-danger btn-sm" type="button" value="<?php echo __('Cancel');?>" class="close">
            </span>
            <span class="buttons pull-right">
                <input class="btn btn-primary btn-sm" type="submit" value="<?php echo __('Print');?>">
            </span>
         </p>
    </form></div>
    <div class="clear"></div>
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
                <input class="btn btn-primary btn-sm" type="submit" value="<?php echo __('OK');?>">
            </span>
         </p>
    </form>
    </div>
</div>
<script type="text/javascript">
    var keytrigger = false;     
            
 $("#datepicker1").on("dp.change", function (e) {
  
    var setduedate = $("#datepicker1").data('date');
    var duedate = '<?php echo date("m/d/Y", strtotime($ticket->getEstDueDate())); ?>';
    
        if (setduedate !== duedate){
    
             var charCode = e.which || e.keyCode; 
             if (!(charCode === 9)){
                $("#savebutton").css("background-color", "#52bb56");
                $("#savebutton").css("color", "#fff");
                $("#cancelbutton").css("background-color", "#ef5350");
                $("#cancelbutton").css("color", "#fff");
                $("i.fa.fa-reply").css("color", "#eeeeee");
                $("i.fa.fa-pencil-square-o").css("color", "#eeeeee");
                $("#updatearea").css("display", "none");
                $("#detailschanged").css("display", "inherit");
                if (!keytrigger) {
                $.notify({
                    text: 'Changes made please click the save <i class="icon-save"></i> or cancel <i //class="icon-remove"></i> button on the ribbon.',
                    image: '<i class="icon-save"></i>'
                }, {
                    style: 'metro',
                    className: 'error',
                    autoHide: false,
                    clickToHide: true
                });
                        }
                        
                keytrigger = true;
             }
        };
 });       
            
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
    
    $('.nav-tabs li a').click(function(e){
  e.preventDefault();
  e.stopImmediatePropagation();
  $(this).tab('show');
});
    
    $('#post-note').click(function(e){
    	e.preventDefault();
        $('#ticket_tabs a[href="#note"]').tab('show');
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
        $('#ticket_tabs a[href="#reply"]').tab('show');
        // Scroll to the response section.
            var $stop = $(document).height();
            var $s = $('div#response_options');
            if ($s.length)
                $stop = $s.offset().top-125
            $('html, body').animate({scrollTop: $stop}, 'fast');
                        
    })
    $.extend($.fn.tree.methods,{
    getLevel: function(jq, target){
        return $(target).find('span.tree-indent,span.tree-hit').length;
    }
});
    $(document).ready(function(){
        var val = <?php echo Topic::getHelpTopicsTree();?> ;
        $('#cc').combotree({ 
            onLoadSuccess : function(){
                
                var c = $('#cc');
                c.combotree('setValue','<?php echo $ticket->getHelpTopicId(); ?>');
                var t = c.combotree('tree');  // get tree object
                var node = t.tree('find', '<?php echo $ticket->getHelpTopicId(); ?>');  // find the specify node
                if (node){
                t.tree('expandTo', node.target);
                } else {
                $('#cc').combotree('setText', '— <?php echo __('Select Help Topic'); ?> —');   
                };
                
                
            }
        }); 
        $(function(){
          var hash = window.location.hash;
          hash && $('ul.nav a[href="' + hash + '"]').tab('show');
          $('.nav-tabs a').click(function (e) {
            $(this).tab('show');
            var scrollmem = $('body').scrollTop();
            window.location.hash = this.hash;
            $('html,body').scrollTop(scrollmem);
          });
        });
        
         $('#cc').combotree({ 
            onChange : function(){
                
                var c = $('#cc');
                var t = c.combotree('tree');  // get tree object
                var node = t.tree('getSelected');
                var nodeLevel = t.tree('getLevel',node.target);
                parentArry = new Array();
                var parentArry = new Array();
                var parents = getParentArry(t,node,nodeLevel,parentArry);
                var parentStr = "";
                if(parents.length > 0){
                    var parentStr = "";
                    for(var i = 0; i < parents.length; i++){
                        parentStr += parents[i].text + " / ";
                    }
                
                
                }
             $('#cc').combotree('setText', parentStr + node.text);
            
                
            }
        }); 
       
        $('#cc').combotree('loadData', val);
        
        function getParentArry(tree,selectedNode,nodeLevel,parentArry){
            //end condition: level of selected node equals 1, means it's root
           if(nodeLevel == 1){
              return parentArry;
           }else{//if selected node isn't root
              nodeLevel -= 1;
              //the parent of the node
              var parent = $(tree).tree('getParent',selectedNode.target);
              //record the parent of selected to a array
              parentArry.unshift(parent);
              //recursive, to judge whether parent of selected node has more parent
              return getParentArry(tree,parent,nodeLevel,parentArry);
            }
        }
      
            $('#datepicker1').datetimepicker({
                   useCurrent: false,
                   format: 'MM/DD/YYYY',
                   showClear: true,
                   showTodayButton: true
                   
               });
               <?php //if ($msg){echo "$.Notification.notify('warning','top right', 'Warning', '".$msg."');";} ?>
               <?php if ($errors['err']){echo "$.Notification.notify('warning','top right', 'Warning', '".$errors['err']."');";} ?>
               <?php if ($outstanding !== false){echo "$.Notification.notify('warning','top right', 'Warning', '".$warning."');";} ?>     
               <?php if ($warn)   {echo "$.Notification.notify('warning','top right', 'Overdue', '".$warn."');";} ?>
               <?php // if ($bannermsg){echo "$.Notification.notify('success','top right', 'Success', '".$bannermsg."');";} ?>
               
              
           
            
            
                     
    });
     $('#cc').combotree({ 
        onSelect: function (r) { 
        
            $("#savebutton").css("background-color", "#52bb56");
            $("#savebutton").css("color", "#fff");
            $("#cancelbutton").css("background-color", "#ef5350");
            $("#cancelbutton").css("color", "#fff");
            $("i.fa.fa-reply").css("color", "#eeeeee");
            $("i.fa.fa-pencil-square-o").css("color", "#eeeeee");
            $("#updatearea").css("display", "none");
            $("#detailschanged").css("display", "inherit");
            
            $("#help-topic-error").css("display", "none"); 
            $.notify({
            text: 'Changes made please click the save <i class="icon-save"></i> or cancel <i //class="icon-remove"></i> button on the ribbon.',
            image: '<i class="icon-save"></i>'
        }, {
            style: 'metro',
            className: 'error',
            autoHide: false,
            clickToHide: true
        });            
        } 
    });
});
// Hide form buttons By Default
$('#save').find('input, select, text').change(function(){
    $("#savebutton").css("background-color", "#52bb56");
    $("#savebutton").css("color", "#fff");
    $("#cancelbutton").css("background-color", "#ef5350");
    $("#cancelbutton").css("color", "#fff");
    $("i.fa.fa-reply").css("color", "#eeeeee");
    $("i.fa.fa-pencil-square-o").css("color", "#eeeeee");
    $("#updatearea").css("display", "none");
    $("#detailschanged").css("display", "inherit");
    if (!keytrigger) {
   
    $.notify({
            text: 'Changes made please click the save <i class="icon-save"></i> or cancel <i //class="icon-remove"></i> button on the ribbon.',
            image: '<i class="icon-save"></i>'
        }, {
            style: 'metro',
            className: 'error',
            autoHide: false,
            clickToHide: true
        });
    }
    keytrigger = true;
});
$("#save").keyup(function(e){
    var charCode = e.which || e.keyCode; 
    if (!(charCode === 9)){
        
        $("#savebutton").css("background-color", "#52bb56");
        $("#savebutton").css("color", "#fff");
        $("#cancelbutton").css("background-color", "#ef5350");
        $("#cancelbutton").css("color", "#fff");
        $("i.fa.fa-reply").css("color", "#eeeeee");
        $("i.fa.fa-pencil-square-o").css("color", "#eeeeee");
        $("#updatearea").css("display", "none");
        $("#detailschanged").css("display", "inherit");
    if (!keytrigger) {
   
    $.notify({
            text: 'Changes made please click the save <i class="icon-save"></i> or cancel <i //class="icon-remove"></i> button on the ribbon.',
            image: '<i class="icon-save"></i>'
        }, {
            style: 'metro',
            className: 'error',
            autoHide: false,
            clickToHide: true
        });
    }
    keytrigger = true;
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
$(".dropdown-menu a").click(function() {
    $(this).closest(".dropdown-menu").prev().dropdown("toggle");
});
</script>
