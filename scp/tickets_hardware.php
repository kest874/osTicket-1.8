<?php
require('staff.inc.php');
require_once(INCLUDE_DIR.'class.ticket.php');

$ticket = $user = null; //clean start.
//LOCKDOWN...See if the id provided is actually valid and if the user has access.
if($_REQUEST['id']) {
    if(!($ticket=Ticket::lookup($_REQUEST['id'])))
         $errors['err']=sprintf(__('%s: Unknown or invalid ID.'), __('ticket'));
    elseif(!$thisstaff->canAccess($ticket)) {
        $errors['err']=__('Access denied. Contact admin if you believe this is in error');
        $ticket=null; //Clear ticket obj.
    }
}

//Navigation & Page Info
$nav->setTabActive('tickets');
$ost->setPageTitle(sprintf(__('Ticket #%s Hardware Management'),$ticket->getNumber()));
$hwform = new TicketHardwareForm($_POST);

if(!$errors) {
	// Retrieve Ticket Information
	$TicketID = $_GET['id'];
	$Subject = $ticket->getSubject();
	$TicketNo = $ticket->getNumber();
	
	if($_POST['ticket_id'] && $hwform->isValid()) {
		// Create and Run SQL Query
        $hwform_data = $hwform->getClean();
        $hardware = $ticket->hardware;
        $hardware->add(new TicketHardware($hwform_data));
        $hardware->saveAll();
	}
}

require_once(STAFFINC_DIR.'header.inc.php');

if(!$errors) {
?>

	<h1>Hardware Management</h1>
	
	<h2>Ticket Information</h2>
	<p><b>Ticket:</b> #<?php echo $TicketNo; ?> <br />
		<b>Subject:</b> <?php echo $Subject; ?> <br />
	</p>
	<p>&nbsp;</p>
	
	<h2>Hardware Details</h2>
	<table class="list" border="0" cellspacing="1" cellpadding="2" width="940">
		<tr>
			<th>Description</th>
			<th>Qty</th>
			<th>Unit Cost (Ex VAT / Taxes)</th>
			<th>Total Cost (Ex VAT / Taxes)</th>
		</tr>
<?php
            foreach ($ticket->hardware as $H) {
				echo '<tr>';
					echo "<td>" . Format::htmlchars($H->description) . "</td>";
					echo "<td>" . Format::htmlchars($H->qty) . "</td>";
					echo "<td>" . Format::htmlchars($H->unit_cost) . "</td>";
					echo "<td>" . Format::htmlchars($H->total_cost) . "</td>";
				echo '</tr>';
			}
		?>
	</table>
	
    <hr/>
	<form action="tickets_hardware.php?id=<?php echo $TicketID; ?>" name="reply" method="post">
		<input type="hidden" name="ticket_id" value="<?php echo $TicketID; ?>">
<?php
        csrf_token();
        echo $hwform->asTable();
?>
<p class="full-width centered">
		<input class="button" type="reset" value="<?php echo __('Reset');?>">
		<input class="button" type="submit" value="<?php echo __('Add Hardware');?>">
</p>
	</form>
	
<?php
} else {
?>
	<h1>Hardware Management</h1>
	<p>You do not have access to this module.</p>
<?php
}
require_once(STAFFINC_DIR.'footer.inc.php');
?>
