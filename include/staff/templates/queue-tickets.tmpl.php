<?php
// Calling convention (assumed global scope):
// $tickets - <QuerySet> with all columns and annotations necessary to
//      render the full page
// For searches, some staff members may be able to see everything
$view_all_tickets = $queue->ignoreVisibilityConstraints();
// Impose visibility constraints
// ------------------------------------------------------------
// if (!$view_all_tickets) {
    // // -- Open and assigned to me
    // $assigned = Q::any(array(
        // 'staff_id' => $thisstaff->getId(),
    // ));
    // // -- Open and assigned to a team of mine
    // if ($teams = array_filter($thisstaff->getTeams()))
        // $assigned->add(array('team_id__in' => $teams));
    // $visibility = Q::any(new Q(array('status__state'=>'open', $assigned)));
    // // -- Routed to a department of mine
    // if (!$thisstaff->showAssignedOnly() && ($depts=$thisstaff->getDepts()))
        // $visibility->add(array('dept_id__in' => $depts));
    // $tickets->filter($visibility);
// }
// Make sure the cdata materialized view is available
TicketForm::ensureDynamicDataView();
// Identify columns of output
$columns = $queue->getColumns();
// Figure out REFRESH url — which might not be accurate after posting a
// response
list($path,) = explode('?', $_SERVER['REQUEST_URI'], 2);
$args = array();
parse_str($_SERVER['QUERY_STRING'], $args);
// Remove commands from query
unset($args['id']);
if ($args['a'] !== 'search') unset($args['a']);
$refresh_url = $path . '?' . http_build_query($args);
// Establish the selected or default sorting mechanism
if (isset($_GET['sort']) && is_numeric($_GET['sort'])) {
    $sort = $_SESSION['sort'][$queue->getId()] = array(
        'col' => (int) $_GET['sort'],
        'dir' => (int) $_GET['dir'],
    );
}
elseif (isset($_GET['sort'])
    // Drop the leading `qs-`
    && (strpos($_GET['sort'], 'qs-') === 0)
    && ($sort_id = substr($_GET['sort'], 3))
    && is_numeric($sort_id)
    && ($sort = QueueSort::lookup($sort_id))
) {
    $sort = $_SESSION['sort'][$queue->getId()] = array(
        'queuesort' => $sort,
        'dir' => (int) $_GET['dir'],
    );
}
elseif (isset($_SESSION['sort'][$queue->getId()])) {
    $sort = $_SESSION['sort'][$queue->getId()];
}
elseif ($queue_sort = $queue->getDefaultSort()) {
    $sort = $_SESSION['sort'][$queue->getId()] = array(
        'queuesort' => $queue_sort,
        'dir' => (int) $_GET['dir'] ?: 0,
    );
}
// Handle current sorting preferences
$sorted = false;
foreach ($columns as $C) {
    // Sort by this column ?
    if (isset($sort['col']) && $sort['col'] == $C->id) {
        $tickets = $C->applySort($tickets, $sort['dir']);
        $sorted = true;
    }
}
if (!$sorted && isset($sort['queuesort'])) {
    // Apply queue sort-dropdown selected preference
    $sort['queuesort']->applySort($tickets, $sort['dir']);
}
// Apply pagination
if (isset($_REQUEST['query']) and  !isset($_REQUEST['p'])) $page = 1;
If (!$page){
$page = ($_GET['p'] && is_numeric($_GET['p']))?$_GET['p']:$_SESSION['pageno'];};
$_SESSION['pageno'] = $page;
$pageNav = new Pagenate(PHP_INT_MAX, $page, PAGE_LIMIT);
$tickets = $pageNav->paginateSimple($tickets);
$count = $tickets->total();
$pageNav->setTotal($count);
$pageNav->setURL('tickets.php', $args);

$filters = array('id' =>$thisstaff ->getDeptId());
$depts = Dept::objects()
    ->annotate(array(
            'members_count' => SqlAggregate::COUNT('members', true),
    ));

$depts->filter($filters);

foreach ($depts as $dept) {
    
    $id = $dept->getId();
    $pid = $dept->getPId(); 
    
    
    
$sql='SELECT  dept_id, count(number) as count FROM '.TICKET_TABLE.' ticket '
.'WHERE YEAR(created) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)'
.'AND MONTH(created) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)'
.'and dept_id = '.$thisstaff->dept_id.' group by dept_id ';

$PMonthSubmitted = db_fetch_array(db_query($sql));

$sql='SELECT  dept_id, count(number) as count FROM '.TICKET_TABLE.' ticket '
.'WHERE YEAR(created) = YEAR(CURRENT_DATE)'
.'AND MONTH(created) = MONTH(CURRENT_DATE)'
.'and dept_id = '.$thisstaff->dept_id.' group by dept_id ';

$CMonthSubmitted = db_fetch_array(db_query($sql));

$sql='SELECT  dept_id, count(number) as count FROM '.TICKET_TABLE.' ticket '
.'WHERE YEAR(created) = YEAR(CURRENT_DATE)'
.'and dept_id = '.$thisstaff->dept_id.' group by dept_id ';

$YearToDateSubmitted = db_fetch_array(db_query($sql));

$sql='SELECT  dept_id, count(number) as count FROM '.TICKET_TABLE.' ticket '
.'WHERE YEAR(closed) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)'
.'AND MONTH(closed) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)'
.'and status_id = 3 and dept_id = '.$thisstaff->dept_id.' group by dept_id ';

$PMonthImplemented = db_fetch_array(db_query($sql));

                        $sql='SELECT  dept_id, count(number) as count FROM '.TICKET_TABLE.' ticket '
.'WHERE YEAR(closed) = YEAR(CURRENT_DATE)'
.'AND MONTH(closed) = MONTH(CURRENT_DATE)'
.'and status_id = 3 and dept_id = '.$thisstaff->dept_id.' group by dept_id ';

$CMonthImplemented = db_fetch_array(db_query($sql));

$sql='SELECT  dept_id, count(number) as count FROM '.TICKET_TABLE.' ticket '
.'WHERE YEAR(closed) = YEAR(CURRENT_DATE)'
.'and status_id = 3 and dept_id = '.$thisstaff->dept_id.' group by dept_id ';

$YearToDateImplemented = db_fetch_array(db_query($sql));

$MemberCount = $dept->members_count;

$PMSubmitted = (int)$PMonthSubmitted["count"];
$PMTargetSuggestions =  round(($MemberCount * 17) /12 * (int) date('m', strtotime(date('Y-m')." -1 month")));
$PMSugAheadBehind = $PMSubmitted - $PMTargetSuggestions;
$PMSugAheadBehindColor = ($PMSugAheadBehind > 0 ? 'lightgreen':'#ff9999');
$PMSugGoal = number_format($PMSubmitted / $PMTargetSuggestions * 100,2).'%';
            
$PMImplemented = (int)$PMonthImplemented["count"];
$PMTargetImplemented = $MemberCount * 12/12 * (int) date('m', strtotime(date('Y-m')." -1 month"));
$PMImpAheadBehind = $PMImplemented - $PMTargetImplemented;
$PMImpAheadBehindColor = ($PMImpAheadBehind > 0 ? 'lightgreen':'#ff9999');
$PMImpGoal = number_format($PMImplemented / $PMTargetImplemented * 100,2).'%';
            
$CMSubmitted = (int)$CMonthSubmitted["count"];
$CMTargetSuggestions =  round(($MemberCount * 17) /12 * (int) date('m', strtotime(date('Y-m'))));
$CMSugAheadBehind = $CMSubmitted - $CMTargetSuggestions;
$CMSugAheadBehindColor = ($CMSugAheadBehind > 0 ? 'lightgreen':'#ff9999');
$CMSugGoal = number_format($CMSubmitted / $CMTargetSuggestions * 100,2).'%';
            
$CMImplemented = (int)$CMonthImplemented["count"];
$CMTargetImplemented = $MemberCount * 12/12 * (int) date('m', strtotime(date('Y-m')));
$CMImpAheadBehind = $CMImplemented - $CMTargetImplemented;
$CMImpAheadBehindColor = ($CMImpAheadBehind > 0 ? 'lightgreen':'#ff9999');
$CMImpGoal = number_format($CMImplemented / $CMTargetImplemented * 100,2).'%';

$YTDSubmitted = (int)$YearToDateSubmitted["count"];
$YTDTargetSuggestions =  round(($MemberCount * 17));
$YTDSugAheadBehind = $YTDSubmitted - $YTDTargetSuggestions;
$YTDSugAheadBehindColor = ($YTDSugAheadBehind > 0 ? 'lightgreen':'#ff9999');
$YTDSugGoal = number_format($YTDSubmitted / $YTDTargetSuggestions * 100,2).'%';

$YTDImplemented = (int)$YearToDateImplemented["count"];
$YTDTargetImplemented = $MemberCount * 12;
$YTDImpAheadBehind = $YTDImplemented - $YTDTargetImplemented;
$YTDImpAheadBehindColor = ($YTDImpAheadBehind > 0 ? 'lightgreen':'#ff9999');
$YTDImpGoal = number_format($YTDImplemented / $YTDTargetSuggestions * 100,2).'%';
   
}
?>
<div id="dashboard">
    <table width="100%" style="font-size: smaller">
        <tr style="font-weight: bold;">
            <td width="10px"> </td>
            <td width="120px">Team Members: <span style="color: red; font-weight: bold;"><?php echo $MemberCount ?></span></td>
            <td width="100px">Suggestions</td> 
            <td width="90px">Target</td>
            <td width="100px">Ahead/Behind</td>
            <td width="100px">Goal</td>
            <td width="100px">Implementations</td>
            <td width="90px">Target</td>
            <td width="100px">Ahead/Behind</td>
            <td width="100px">Goal</td>
            <td></td>
            
        </tr>
        <tr>
        <td> </td>
        <td style="font-weight: bold;">Last Month</td>
            <td><?php echo $PMSubmitted;?></td>
            <td><?php echo $PMTargetSuggestions;?></td>
            <td style="background-color:<?php echo $PMSugAheadBehindColor ?>"><?php echo $PMSugAheadBehind;?></td>
            <td><?php echo $PMSugGoal;?></td>
            <td><?php echo $PMImplemented;?></td>
            <td><?php echo $PMTargetImplemented;?></td>
            <td style="background-color:<?php echo $PMImpAheadBehindColor ?>"><?php echo $PMImpAheadBehind;?></td>
            <td><?php echo $PMImpGoal;?></td>
            <td></td>
        <tr>
        <td> </td>
            <td style="font-weight: bold;">This Month</td>
            <td><?php echo $CMSubmitted;?></td>
            <td><?php echo $CMTargetSuggestions;?></td>
            <td style="background-color:<?php echo $CMSugAheadBehindColor ?>"><?php echo $CMSugAheadBehind;?></td>
            <td><?php echo $CMSugGoal;?></td>
            <td><?php echo $CMImplemented;?></td>
            <td><?php echo $CMTargetImplemented;?></td>
            <td style="background-color:<?php echo $CMImpAheadBehindColor ?>"><?php echo $CMImpAheadBehind;?></td>
            <td><?php echo $CMImpGoal;?></td>
            <td></td>
        </tr>
        <tr>
        <td> </td>
            <td style="font-weight: bold;">Year To Date</td>
            <td><?php echo $YTDSubmitted;?></td>
            <td><?php echo $YTDTargetSuggestions;?></td>
            <td style="background-color:<?php echo $YTDSugAheadBehindColor ?>"><?php echo $YTDSugAheadBehind;?></td>
            <td><?php echo $YTDSugGoal;?></td>
            <td><?php echo $YTDImplemented;?></td>
            <td><?php echo $YTDTargetImplemented;?></td>
            <td style="background-color:<?php echo $YTDImpAheadBehindColor ?>"><?php echo $YTDImpAheadBehind;?></td>
            <td><?php echo $YTDImpGoal;?></td>
            <td></td>
        </tr>
        
    </table>

</div>
<!-- SEARCH FORM START -->
<div id='basic_search'>
  <div class="pull-right" style="height:25px">
    <span class="valign-helper"></span>
    <?php
    require 'queue-quickfilter.tmpl.php';
    if (count($queue->sorts))
        require 'queue-sort.tmpl.php';
    ?>
  </div>
    <form action="tickets.php" method="get" onsubmit="javascript:
  $.pjax({
    url:$(this).attr('action') + '?' + $(this).serialize(),
    container:'#pjax-container',
    timeout: 2000
  });
return false;">
    <input type="hidden" name="a" value="search">
    <input type="hidden" name="search-type" value=""/>
    <div class="attached input">
      <input type="text" class="basic-search" data-url="ajax.php/tickets/lookup" name="query"
        autofocus size="30" value="<?php echo Format::htmlchars($_REQUEST['query'], true); ?>"
        autocomplete="off" autocorrect="off" autocapitalize="off">
      <button type="submit" class="attached button"><i class="icon-search"></i>
      </button>
    </div>
    <a href="#" onclick="javascript:
        $.dialog('ajax.php/tickets/search', 201);"
        >[<?php echo __('advanced'); ?>]</a>
        <i class="help-tip icon-question-sign" href="#advanced"></i>
    </form>
</div>
<!-- SEARCH FORM END -->

<div class="clear"></div>
<div style="margin-bottom:20px; padding-top:5px;">
    <div class="sticky bar opaque">
        <div class="content">
            <div class="pull-left flush-left">
                <h2><a href="<?php echo $refresh_url; ?>"
                    title="<?php echo __('Refresh'); ?>"><i class="icon-refresh"></i> 
					
<?php if (isset($queue->id)) { ?> 
<?php echo $queue->getFullName();} ?>
					
					</a></h2>
            </div>
            <div class="configureQ">
                <i class="icon-cog"></i>
                <div class="noclick-dropdown anchor-left">
                    <ul>
<?php
if ($queue->isPrivate()) { ?>
                        <li>
                            <a class="no-pjax" href="#"
                              data-dialog="ajax.php/tickets/search/<?php echo
                              urlencode($queue->getId()); ?>"><i
                            class="icon-fixed-width icon-save"></i>
                            <?php echo __('Edit'); ?></a>
                        </li>
<?php }
else {
    if ($thisstaff->isAdmin()) { ?>
                        <li>
                            <a class="no-pjax"
                            href="queues.php?id=<?php echo $queue->id; ?>"><i
                            class="icon-fixed-width icon-pencil"></i>
                            <?php echo __('Edit'); ?></a>
                        </li>
<?php }
# Anyone has permission to create personal sub-queues
?>
                        <li>
                            <a class="no-pjax" href="#"
                              data-dialog="ajax.php/tickets/search?parent_id=<?php
                              echo $queue->id; ?>"><i
                            class="icon-fixed-width icon-plus-sign"></i>
                            <?php echo __('Add Personal Queue'); ?></a>
                        </li>
<?php
}
if ($thisstaff->isAdmin()) { ?>
                        <li>
                            <a class="no-pjax"
                            href="queues.php?a=sub&amp;id=<?php echo $queue->id; ?>"><i
                            class="icon-fixed-width icon-level-down"></i>
                            <?php echo __('Add Sub Queue'); ?></a>
                        </li>
                        <li>
                            <a class="no-pjax"
                            href="queues.php?a=clone&amp;id=<?php echo $queue->id; ?>"><i
                            class="icon-fixed-width icon-copy"></i>
                            <?php echo __('Clone'); ?></a>
                        </li>
<?php }
if (
    $queue->id > 0
    && (
        ($thisstaff->isAdmin() && $queue->parent_id)
        || $queue->isPrivate()
)) { ?>
                        <li class="danger">
                            <a class="no-pjax confirm-action" href="#"
                                data-dialog="ajax.php/queue/<?php
                                echo $queue->id; ?>/delete"><i
                            class="icon-fixed-width icon-trash"></i>
                            <?php echo __('Delete'); ?></a>
                        </li>
<?php } ?>
                    </ul>
                </div>
            </div>

          <div class="pull-right flush-right">
            <?php
            // TODO: Respect queue root and corresponding actions
            if ($count) {
                Ticket::agentActions($thisstaff, array('status' => $status));
            }?>
            </div>
        </div>
    </div>
</div>
<div class="clear"></div>

<form action="?" method="POST" name='tickets' id="tickets">
<?php csrf_token(); ?>
 <input type="hidden" name="a" value="mass_process" >
 <input type="hidden" name="do" id="action" value="" >

<table class="list queue tickets" border="0" cellspacing="1" cellpadding="2" width="100%">
  <thead>
    <tr>
<?php
$canManageTickets = $thisstaff->canManageTickets();
if ($canManageTickets) { ?>
        <th style="width:12px"></th>
<?php
}
foreach ($columns as $C) {
    $heading = Format::htmlchars($C->getLocalHeading());
    if ($C->isSortable()) {
        $args = $_GET;
        $dir = $sort['col'] != $C->id ?: ($sort['dir'] ? 'desc' : 'asc');
        $args['dir'] = $sort['col'] != $C->id ?: (int) !$sort['dir'];
        $args['sort'] = $C->id;
        $heading = sprintf('<a href="?%s" class="%s">%s</a>',
            Http::build_query($args), $dir, $heading);
    }
    echo sprintf('<th width="%s" data-id="%d">%s</th>',
        $C->getWidth(), $C->id, $heading);
}
?>
    </tr>
  </thead>
  <tbody>
<?php
foreach ($tickets as $T) {
    echo '<tr>';
    if ($canManageTickets) { ?>
        <td><input type="checkbox" class="ckb" name="tids[]"
            value="<?php echo $T['ticket_id']; ?>" /></td>
<?php
    }
    foreach ($columns as $C) {
        list($contents, $styles) = $C->render($T);
        if ($style = $styles ? 'style="'.$styles.'"' : '') {
            echo "<td $style><div $style>$contents</div></td>";
        }
        else {
            echo "<td>$contents</td>";
        }
    }
    echo '</tr>';
}
?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="<?php echo count($columns)+1; ?>">
        <?php if ($count && $canManageTickets) {
        echo __('Select');?>:&nbsp;
        <a id="selectAll" href="#ckb"><?php echo __('All');?></a>&nbsp;&nbsp;
        <a id="selectNone" href="#ckb"><?php echo __('None');?></a>&nbsp;&nbsp;
        <a id="selectToggle" href="#ckb"><?php echo __('Toggle');?></a>&nbsp;&nbsp;
        <?php }else{
            echo '<i>';
            echo $ferror?Format::htmlchars($ferror):__('Query returned 0 results.');
            echo '</i>';
        } ?>
      </td>
    </tr>
  </tfoot>
</table>

<?php
    if ($count > 0) { //if we actually had any tickets returned.
?>  <div>
      <span class="faded pull-right"><?php echo $pageNav->showing(); ?></span>
<?php
        echo __('Page').':'.$pageNav->getPageLinks().'&nbsp;';
        echo sprintf('<a class="export-csv no-pjax" href="?%s">%s</a>',
                Http::build_query(array(
                        'a' => 'export', 'queue' => $_REQUEST['queue'],
                        'status' => $_REQUEST['status'])),
                __('Export'));
        ?>
        <i class="help-tip icon-question-sign" href="#export"></i>
    </div>
<?php
    } ?>

</form>