<?php
if ($ticket->isChild())
    $parent = Ticket::lookup($ticket->getPid());
else
    $children = Ticket::getChildTickets($ticket->getId());
    ?>
   <table class="table table-striped table-hover table-condensed table-sm" border="0" cellspacing="1" cellpadding="2" width="920">
         <thead>
             <tr>
                 <th width="8px">&nbsp;</th>
                 <th width="70"><?php echo __('Number'); ?></th>
                 <th width="100"><?php echo __('Subject'); ?></th>
                 <th width="100"><?php echo __('Department'); ?></th>
                 <th width="300"><?php echo __('Assignee'); ?></th>
                 <th width="200"><?php echo __('Create Date'); ?></th>
             </tr>
         </thead>
         <tbody class="tasks">
         <?php
         if ($children) {
             foreach($children as $child) {
                 $child = Ticket::lookup($child[0]);
                 echo $child->getRelatedTickets();
             }
         } elseif ($parent)
             echo $parent->getRelatedTickets();
         ?>
         </tbody>
     </table>

