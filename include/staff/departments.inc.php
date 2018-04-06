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

<div class="subnav">


                        <div class="float-left subnavtitle">
                        
                            <span ><a href="<?php echo $refresh_url; ?>"
                                title="<?php echo __('Refresh'); ?>"><i class="icon-refresh"></i> 
                                </a> &nbsp;
            <?php echo __('Teams');?>
                                
                                </span>
                        
                       
                       
                        </div>
 
        <div class="btn-group btn-group-sm float-right m-b-10" role="group" aria-label="Button group with nested dropdown">
                    
                    <a class="btn btn-icon waves-effect waves-light btn-success"
                       href="departments.php?a=add" data-placement="bottom"
                    data-toggle="tooltip" title="<?php echo __('Add Team'); ?>">
                        <i class="fa fa-plus-square"></i>
                    </a>
                    
        </div>     
        <div class="clearfix"></div>                      
 </div>

<div class="card-box">

<div class="row">
    <div class="col">
        <div class="float-right">
<form  class="form-inline" action="departments.php" method="get"  name="filter"  style="padding-bottom: 10px; margin-top: -5px;">
            <?php csrf_token(); ?>
            
             <div class="input-group input-group-sm">
             <input type="hidden" name="a" value="search">
                <input type="text" id="tm" name="tm" value="<?php echo Format::htmlchars($_REQUEST['query']); ?>" class="form-control form-control-sm"  placeholder="Search Teams">
            <!-- <td>&nbsp;&nbsp;<a href="" id="advanced-user-search">[advanced]</a></td> -->
                
                
            
       <button type="submit" class="input-group-addon"  ><i class="fa fa-search"></i>
                </button>
                
                    <select name="did" id="did" class="form-control form-control-sm" style="height: 34px;">
             <option value="0">&mdash; <?php echo __('All Locations');?> &mdash;</option>
             <?php
                foreach (Dept::getDepartments(array('privateonly'=>1)) as $id=>$name) {
                    $sel=($_REQUEST['did'] && $_REQUEST['did']==$id)?'selected="selected"':'';
                    echo sprintf('<option value="%d" %s>%s</option>',$id,$sel,$name);
                }
             ?>
             <input type="submit" name="submit" value="&#xf0b0;" class="input-group-addon fa" style="padding-top: 7px"/>
        
            </div>
            &nbsp;<i class="help-tip icon-question-sign" href="#apply_filtering_criteria"></i>
        </form>
        </div>
    </div>
</div>
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="clear"></div>
<div>


<form action="departments.php" method="POST" name="depts">
<div class="sticky bar">

</div>
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="mass_process" >
 <input type="hidden" id="action" name="a" value="" >
 <table class="table table-striped table-hover table-condensed table-sm">
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
<div class="row">
<div class="col">
    <div class="float-left">
    <nav>
    <ul class="pagination">   
        <?php
            echo $pageNav->getPageLinks();
        ?>
    </ul>
    </nav>
    </div>

   
    <div class="float-right">
          <span class="faded"><?php echo $pageNav->showing(); ?></span>
    </div>  
</div></div>
</form>
</div>
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

