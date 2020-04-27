<?php
global $thisstaff, $ticket;

$role = $ticket ? $ticket->getRole($thisstaff) : $thisstaff->getRole();
if ($role && !$role->hasPerm(Ticket::PERM_CLOSE))
    return;

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
        <button id="btnGroupDrop1" type="button" class="btn btn-light dropdown-toggle" 
        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-placement="bottom" data-toggle="tooltip" 
         title="<?php echo __('Change Status'); ?>"><i class="icon-flag"></i>
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
