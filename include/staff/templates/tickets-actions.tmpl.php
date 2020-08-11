<div class="btn-group btn-group-sm float-right m-b-10" role="group" aria-label="Button group with nested dropdown">

   <a class="btn btn-icon waves-effect waves-light btn-success" id="tickets-helptopic" data-placement="bottom"
    data-toggle="tooltip" title="<?php echo __('New Ticket'); ?>"
   href="<?php echo ROOT_PATH ?>scp/tickets.php?a=open"><i class="fa fa-plus-square"></i></a>

<?php
if (!$count) {
// Status change
if ($agent->canManageTickets())
    echo TicketStatus::status_options();
// Mass Priority Change
if ($agent->hasPerm(Ticket::PERM_EDIT, false)) { ?>


<div class="btn-group btn-group-sm hidden" role="group">
        <button id="btnGroupDrop1" type="button" class="btn btn-light dropdown-toggle" 
        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-placement="bottom" data-toggle="tooltip" 
         title="<?php echo __('Change Priority'); ?>"><i class="fa fa-exclamation"></i>
        </button>
    <div class="dropdown-menu " aria-labelledby="btnGroupDrop1" id="action-dropdown-change-priority">
                
           <?php foreach (Priority::getPriorities() as $Pid => $Pname) { ?>
     <a class="dropdown-item no-pjax tickets-action"
        href="#tickets/mass/priority/<?php echo $Pid; ?>"><i
        class="fa fa-level-up"></i> <?php echo $Pname; ?></a>
<?php } ?>
           
        <?php } ?>   
    </div>
</div>

<?php
// Mass Topic Change
if ($agent->hasPerm(Ticket::PERM_EDIT, false)) {?>

        <a class="btn btn-light tickets-action hidden" id="tickets-helptopic" data-placement="bottom"
    data-toggle="tooltip" title="<?php echo __('Change Category'); ?>"
   href="#tickets/mass/topic"><i class="fa fa-bookmark"></i></a>

<?php } ?>


<?php
}
// Mass Transfer
if ($agent->hasPerm(Ticket::PERM_TRANSFER, false)) {?>

 <a class="btn btn-light tickets-action" id="tickets-transfer" data-placement="bottom"
    data-toggle="tooltip" title="<?php echo __('Change Division'); ?>"
    href="#tickets/mass/transfer"><i class="fa fa-map-marker"></i></a>

<?php
}
// Mass Delete
if ($agent->hasPerm(Ticket::PERM_DELETE, false)) {?>

 <a class="btn btn-icon waves-effect waves-light btn-danger tickets-action" id="tickets-delete" data-placement="bottom"
    data-toggle="tooltip" title="<?php echo __('Delete'); ?>"
    href="#tickets/mass/delete"><i class="fa fa-trash"></i></a>

<?php

}
?>

</div>
<script type="text/javascript">
$(function() {
    $(document).off('.tickets');
    $(document).on('click.tickets', 'a.tickets-action', function(e) {
        e.preventDefault();
        var $form = $('form#tickets');
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
            console.log(tids);
            $.dialog(url, [201], function (xhr) {
                //$.pjax.reload('#pjax-container');
                location.reload();
             });
        }
        return false;
    });
});
</script>