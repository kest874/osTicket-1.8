<?php
if(!defined('OSTSTAFFINC') || !$thisstaff) die('Access Denied');
$DeptId = Dept::getParentId($thisstaff->dept_id);?>
<?php $TeamId = $thisstaff->dept_id;
?>

<div class="subnav">

    <div class="float-left subnavtitle">
                          
    <?php echo __('Reports');?>                        
    
    </div>
    <div class="btn-group btn-group-sm float-right m-b-10" role="group" aria-label="Button group with nested dropdown">
   &nbsp;
      </div>   
   <div class="clearfix"></div> 
</div> 


<div class="card-box">
<div class="row">
<div class="col">

<div class="row table">
    <div>
        
            <strong>
                <a href="http://crpsql01/ReportServer/Pages/ReportViewer.aspx?%2fosTicket%2fSuggestions%2fsug_AssociateActivity&rs:Command=Render&Location=<?php echo $DeptId; ?>&Teams=<?php echo $TeamId; ?>" target="_blank">
                Associate Activity</a>
            </strong>
    </div>
    <div>
            <em>&nbsp; Report showing associate system activity.</em>
        
    </div>
</div>    
<div class="row table">
    <div>
        
            <strong>
                <a href="http://crpsql01/ReportServer/Pages/ReportViewer.aspx?%2fosTicket%2fSuggestions%2fsug_AssociateSummary&rs:Command=Render&Location=<?php echo $DeptId; ?>&Teams=<?php echo $TeamId; ?>" target="_blank">
                Associate Suggestion Summary</a>
            </strong>
     </div>
    <div>       
        
            <em>&nbsp; Report showing associate activity by month.</em>
        
    </div>
</div> 
<div class="row table">
    <div>
        
            <strong>
               <a href="http://crpsql01/ReportServer/Pages/ReportViewer.aspx?%2fosTicket%2fSuggestions%2fsug_OpenSuggesitonAge&rs:Command=Render&Location=<?php echo $DeptId; ?>&Teams=<?php echo $TeamId; ?>" target="_blank">
                Suggestions Open Age</a>
            </strong>
    </div>

    <div>        
        
            <em>&nbsp; Report showing Suggestions By Age<</em>
        
    </div>
</div>
<div class="row table">
        <div>
        
            <strong>
                 <a href="http://crpsql01/ReportServer/Pages/ReportViewer.aspx?%2fosTicket%2fSuggestions%2fsug_Suggestions&rs:Command=Render&Location=<?php echo $DeptId; ?>&Teams=<?php echo $TeamId; ?>" target="_blank">
                Suggestions</a>
            </strong>
     </div>
    <div>       
        
            <em>&nbsp; Report showing Suggestions with filters (Catagory, Status)</em>
        
    </div>
    
 </div>
 <div class="row table">
        <div>
        
            <strong>
                 <a href="http://crpsql01/ReportServer/Pages/ReportViewer.aspx?%2fosTicket%2fSuggestions%2fsug_Statistics&rs:Command=Render&Location=<?php echo $DeptId; ?>&Teams=<?php echo $TeamId; ?>" target="_blank">
                Suggestions Statistics</a>
            </strong>
     </div>
    <div>       
        
            <em>&nbsp; Report showing Suggestions Statistics</em>
        
    </div>
    
 </div>


 </div> </div> </div>


