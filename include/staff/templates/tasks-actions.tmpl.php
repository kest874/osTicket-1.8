 <div class="btn-group btn-group-sm pull-right" role="group" aria-label="Button group with nested dropdown">
 <?php
// Tasks' mass actions based on logged in agent

$actions = array();






if ($agent->hasPerm(Task::PERM_ASSIGN, false)) {
    $actions += array(
            'claim' => array(
                'icon' => 'fa fa-chevron-circle-down',
                'action' => __('Claim')
            ));
     $actions += array(
            'assign/agents' => array(
                'icon' => 'fa fa-user',
                'action' => __('Assign to Associate')
            ));
    }


if ($agent->hasPerm(Task::PERM_DELETE, false)) {
    $actions += array(
            'delete' => array(
                'class' => 'danger',
                'icon' => 'fa fa-trash',
                'action' => __('Delete')
            ));
}
?>


        
<?php

if ($actions && !isset($options['status'])) {
    $more = $options['morelabel'] ?: __('More');
    ?>
    
        <div class="btn-group btn-group-sm" role="group">
            <button id="btnGroupDrop1" type="button" class="btn btn-light  waves-effect  btn-nbg dropdown-toggle" 
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog" data-placement="bottom" data-toggle="tooltip" 
             title="<?php echo __('More'); ?>"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right " aria-labelledby="btnGroupDrop1" id="action-dropdown-change-priority">
            <?php foreach ($actions as $a => $action) { ?>
            <?php
                //if ($action['class'])
                    //echo sprintf("class='%s'", $action['class']); ?>
                <a class="dropdown-item no-pjax tasks-action"  
                    <?php
                    if ($action['dialog'])
                        echo sprintf("data-dialog-config='%s'", $action['dialog']);
                    if ($action['redirect'])
                        echo sprintf("data-redirect='%s'", $action['redirect']);
                    ?>
                    href="<?php
                    echo sprintf('#tasks/mass/%s', $a); ?>"
                    ><i class="icon-fixed-width <?php
                    echo $action['icon'] ?: 'icon-tag'; ?> style="color:#d9534f;"></i> <?php
                    echo $action['action']; ?></a>
           
        <?php
        } ?>
            </div>
        </div>
 <?php
 } else {
    // Mass Claim/Assignment
    
     if (isset($options['status'])) {
        $status = $options['status'];
  

   if ($agent->hasPerm(Task::PERM_CLOSE, false)) { ?>
    <div class="btn-group btn-group-sm" role="group">
            <button id="btnGroupDrop1" type="button" class="btn btn-light waves-effect dropdown-toggle" 
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-flag" data-placement="bottom" data-toggle="tooltip" 
             title="<?php echo __('Change Status'); ?>"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right " aria-labelledby="btnGroupDrop1" id="action-dropdown-change-priority">
                 <?php
                if (!$status || !strcasecmp($status, 'closed')) { ?>
                     <a class="dropdown-item no-pjax tasks-action"
                        href="#tasks/mass/reopen"><i
                        class="fa fa-undo"></i> <?php
                        echo __('Reopen');?> </a>
                
                <?php
                }
                if (!$status || !strcasecmp($status, 'open')) {
                ?>
              
                    <a class="dropdown-item no-pjax tasks-action"
                        href="#tasks/mass/close"><i
                        class="fa fa-check-circle"></i> <?php
                        echo __('Close');?> </a>
                
                <?php
                } ?>   
            </div>
        </div>
        
     <?php } }
   if ($agent->hasPerm(Task::PERM_ASSIGN, false)) { ?>
 <div class="btn-group btn-group-sm" role="group">
            <button id="btnGroupDrop1" type="button" class="btn btn-light waves-effect dropdown-toggle" 
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user" data-placement="bottom" data-toggle="tooltip" 
             title="<?php echo __('Assign'); ?>"></i>
            </button>
                    <div class="dropdown-menu dropdown-menu-right " aria-labelledby="btnGroupDrop1" id="action-dropdown-change-priority">
                    
                    <a class="dropdown-item no-pjax tasks-action"
            href="#tasks/mass/claim"><i
            class="fa fa-chevron-circle-down"></i> <?php echo __('Claim'); ?></a>
         <a class="dropdown-item no-pjax tasks-action"
            href="#tasks/mass/assign/agents"><i
            class="fa fa-user"></i> <?php echo __('Associate'); ?></a>
        
                    
            </div>
        </div>                   
    
    <?php
    }

 
    // Mass Delete
    if ($agent->hasPerm(Task::PERM_DELETE, false)) {?>
    
     <a class="btn btn-icon waves-effect waves-light btn-danger tasks-action" id="tasks-delete" data-placement="bottom"
        data-toggle="tooltip" title="<?php echo __('Delete'); ?>"
        href="#tasks/mass/delete"><i class="fa fa-trash"></i></a>
   
<?php
    }
} ?>

</div>
<script type="text/javascript">
$(function() {
    $(document).off('.tasks-actions');
    $(document).on('click.tasks-actions', 'a.tasks-action', function(e) {
        e.preventDefault();
        var $form = $('form#tasks');
        var count = checkbox_checker($form, 1);
        if (count) {
            var tids = $('.ckb:checked', $form).map(function() {
                    return this.value;
                    }).get();
            var url = 'ajax.php/'
            +$(this).attr('href').substr(1)
            +'?count='+count
            +'&tids='+tids.join(',')
            +'&_uid='+new Date().getTime();
            var $redirect = $(this).data('redirect');
            $.dialog(url, [201], function (xhr) {
                if (!!$redirect)
                    $.pjax({url: $redirect, container:'#pjax-container'});
                else
                  <?php
                  if (isset($options['callback_url']))
                    echo sprintf("$.pjax({url: '%s', container: '%s', push: false});",
                           $options['callback_url'],
                           @$options['container'] ?: '#pjax-container'
                           );
                  else
                    echo sprintf(" location.reload();",
                            @$options['container'] ?: '#pjax-container');
                 ?>
             });
        }
        return false;
    });
});
</script>
