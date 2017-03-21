<?php
if (!defined('OSTADMININC') || !$thisstaff->isAdmin())
    die('Access Denied');

$qs = array();
$sortOptions=array(
    'pid' => 'pid',
    'name' => 'name',
    'members'=> 'members_count',
    'manager'=>'manager__lastname',
    'teamleader'=>'teamleader__lastname'
    );

$orderWays = array('DESC'=>'DESC', 'ASC'=>'ASC');
$sort = ($_REQUEST['sort'] && $sortOptions[strtolower($_REQUEST['sort'])]) ? strtolower($_REQUEST['sort']) : 'name';


if ($sort && $sortOptions[$sort]) {
    $order_column = $sortOptions[$sort];
}

$order_column = $order_column ? $order_column : 'name';

if ($_REQUEST['order'] && isset($orderWays[strtoupper($_REQUEST['order'])])) {
    $order = $orderWays[strtoupper($_REQUEST['order'])];
} else {
    $order = 'ASC';
}

if ($order_column && strpos($order_column,',')) {
    $order_column=str_replace(','," $order,",$order_column);
}
$x=$sort.'_sort';
$$x=' class="'.strtolower($order).'" ';

//Filters
$filters = array();
if ($_REQUEST['did'] && is_numeric($_REQUEST['did'])) {
    $filters += array('pid' => $_REQUEST['did']);
    $qs += array('did' => $_REQUEST['did']);
}
if ($_REQUEST['tm']) {
    $filters += array('name__contains' => $_REQUEST['tm']);
    $qs += array('name' => $_REQUEST['tm']);
}
    
$depts = Dept::objects()
                ->annotate(array(
                        'members_count' => SqlAggregate::COUNT('members', true),
                ));
                //->order_by(sprintf('%s%s',
                  //          strcasecmp($order, 'DESC') ? '' : '-',
                    //        $order_column));
                
$order = strcasecmp($order, 'DESC') ? '' : '-';
foreach ((array) $order_column as $C) {
    $depts->order_by($order.$C);
}

if ($filters)
    $depts->filter($filters);
            

// paginate
$page = ($_GET['p'] && is_numeric($_GET['p'])) ? $_GET['p'] : 1;
$count = $depts->count();
$pageNav = new Pagenate($count, $page, PAGE_LIMIT);
$qs += array('sort' => $_REQUEST['sort'], 'order' => $_REQUEST['order']);
$pageNav->setURL('departments.php', $qs);
$showing = $pageNav->showing().' '._N('agent', 'agents', $count);
$qstr = '&amp;'. Http::build_query($qs);
$qstr .= '&amp;order='.($order=='-' ? 'ASC' : 'DESC');

// add limits.
$depts->limit($pageNav->getLimit())->offset($pageNav->getStart());
?>


<div id="basic_search" >
    <div style="min-height:25px;">
        <div class="pull-left">
            <form action="departments.php" method="GET" name="filter">
                <input type="hidden" name="a" value="filter">
                <div class="attached input">
                <input type="text" class="basic-search" id="tm" name="tm"
                         size="30" value="<?php echo Format::htmlchars($_REQUEST['query']); ?>"
                        autocomplete="off" autocorrect="off" autocapitalize="off">
            <!-- <td>&nbsp;&nbsp;<a href="" id="advanced-user-search">[advanced]</a></td> -->
                <button type="submit" class="attached button"><i class="icon-search"></i>
                </button>
            </div>
                <select name="did" id="did">
                    <option value="0">&mdash;
                        <?php echo __( 'Location');?> &mdash;</option>
                    <?php if (($depts1=Dept::getDepartments(array('privateonly' =>1)))) { foreach ($depts1 as $id=> $name) { $sel=($_REQUEST['did'] && $_REQUEST['did']==$id)?'selected="selected"':''; echo sprintf('
                    <option value="%d" %s>%s</option>',$id,$sel,$name); } } ?>
                </select>
                <input type="submit" name="submit" class="button muted" value="<?php echo __('Apply');?>" />
            </form>
        </div>
    </div>
</div>
<form action="departments.php" method="POST" name="depts">
<div class="sticky bar">
    <div class="content">
        <div class="pull-left">
            <h2><?php echo __('Departments');?></h2>
        </div>
        <div class="pull-right flush-right">
            <a href="departments.php?a=add" class="green button action-button"><i class="icon-plus-sign"></i> <?php echo __('Add New Team');?></a>
            <span class="action-button" data-dropdown="#action-dropdown-more">
                <i class="icon-caret-down pull-right"></i>
                <span ><i class="icon-cog"></i> <?php echo __('More');?></span>
            </span>
            <div id="action-dropdown-more" class="action-dropdown anchor-right">
                <ul id="actions">
                    <li class="danger"><a class="confirm" data-name="delete" href="departments.php?a=delete">
                        <i class="icon-trash icon-fixed-width"></i>
                        <?php echo __('Delete'); ?></a></li>
                </ul>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="mass_process" >
 <input type="hidden" id="action" name="a" value="" >
 <table class="list" border="0" cellspacing="1" cellpadding="0" width="940">
    <thead>
        <tr>
            <th width="4%">&nbsp;</th>
            <th width="8%"><a  <?php echo $users_sort; ?>href="departments.php?<?php echo $qstr; ?>&sort=pid"><?php echo __('Location');?></a></th>
            <th width="28%"><a <?php echo $name_sort; ?> href="departments.php?<?php echo $qstr; ?>&sort=name"><?php echo __('Name');?></a></th>
            <th width="8%"><a  <?php echo $users_sort; ?>href="departments.php?<?php echo $qstr; ?>&sort=members"><?php echo __('Associates');?></a></th>

            <th width="22%"><a  <?php echo $manager_sort; ?> href="departments.php?<?php echo $qstr; ?>&sort=manager"><?php echo __('Mentor');?></a></th>
            <th width="22%"><a  <?php echo $teamleader_sort; ?> href="departments.php?<?php echo $qstr; ?>&sort=teamleader"><?php echo __('Team Leader');?></a></th>
        </tr>
    </thead>
    <tbody>
  <?php
 
        $ids= ($errors && is_array($_POST['ids'])) ? $_POST['ids'] : null;
        if ($count) {
            
            $defaultEmailAddress = (string) $cfg->getDefaultEmail();
            foreach ($depts as $dept) {
                
                $id = $dept->getId();
                $pid = $dept->getPId();
                $sel=false;
                if($ids && in_array($dept->getId(), $ids))
                    $sel=true;
                
                $default= ($defaultId == $dept->getId()) ?' <small>'.__('(Default)').'</small>' : '';
            
            if ($pid != 0){
            ?>
            
            <tr id="<?php echo $id; ?>">
                <td align="center">
                  <input type="checkbox" class="ckb" name="ids[]"
                  value="<?php echo $id; ?>"
                  <?php echo $sel? 'checked="checked"' : ''; ?>
                  <?php echo $default? 'disabled="disabled"' : ''; ?> >
                </td>
                <td>&nbsp;&nbsp;
                    <b>
                    <a href="departments.php?did=<?php echo $pid; ?>"><?php echo $dept->getParentName($pid); ?></a>
                    </b>
                </td>
                
                <td><a href="departments.php?id=<?php echo $id; ?>"><?php
                echo Dept::getDNamebyId($id); ?></a>&nbsp;<?php echo $default; ?></td>
                
                <td>&nbsp;&nbsp;
                    <b>
                    <?php if ($dept->members_count) { ?>
                        <a href="staff.php?did=<?php echo $id; ?>"><?php echo $dept->members_count; ?></a>
                    <?php }else{ ?> 0
                    <?php } ?>
                    </b>
                </td>
                
                <td><a href="staff.php?id=<?php echo $dept->manager_id; ?>"><?php
                    echo $dept->manager_id ? $dept->manager : ''; ?>&nbsp;</a></td>
                <td><a href="staff.php?id=<?php echo $dept->teamleader_id; ?>"><?php
                    echo $dept->teamleader_id ? $dept->teamleader : ''; ?>&nbsp;</a></td>
            </tr>
            <?php
            }
            } //end of foreach.
        } ?>
    <tfoot>
     <tr>
        <td colspan="6">
            <?php
            if ($count) { ?>
            <?php echo __('Select');?>:&nbsp;
            <a id="selectAll" href="#ckb"><?php echo __('All');?></a>&nbsp;&nbsp;
            <a id="selectNone" href="#ckb"><?php echo __('None');?></a>&nbsp;&nbsp;
            <a id="selectToggle" href="#ckb"><?php echo __('Toggle');?></a>&nbsp;&nbsp;
            <?php }else{
                echo __('No teams found!');
            } ?>
        </td>
     </tr>
    </tfoot>
</table>
<?php
if ($count):
    echo '<div>&nbsp;'.__('Page').':'.$pageNav->getPageLinks().'&nbsp;</div>';
?>
<?php
endif;
?>
</form>
<div style="display:none;" class="dialog" id="confirm-action">
    <h3><?php echo __('Please Confirm');?></h3>
    <a class="close" href=""><i class="icon-remove-circle"></i></a>
    <hr/>
    <p class="confirm-action" style="display:none;" id="make_public-confirm">
        <?php echo sprintf(__('Are you sure you want to make %s <b>public</b>?'),
            _N('selected department', 'selected departments', 2));?>
    </p>
    <p class="confirm-action" style="display:none;" id="make_private-confirm">
        <?php echo sprintf(__('Are you sure you want to make %s <b>private</b> (internal)?'),
            _N('selected department', 'selected departments', 2));?>
    </p>
    <p class="confirm-action" style="display:none;" id="delete-confirm">
        <font color="red"><strong><?php echo sprintf(__('Are you sure you want to DELETE %s?'),
            _N('selected department', 'selected departments', 2));?></strong></font>
        <br><br><?php echo __('Deleted data CANNOT be recovered.'); ?>
    </p>
    <div><?php echo __('Please confirm to continue.');?></div>
    <hr style="margin-top:1em"/>
    <p class="full-width">
        <span class="buttons pull-left">
            <input type="button" value="<?php echo __('No, Cancel');?>" class="close">
        </span>
        <span class="buttons pull-right">
            <input type="button" value="<?php echo __('Yes, Do it!');?>" class="confirm">
        </span>
     </p>
    <div class="clear"></div>
</div>

