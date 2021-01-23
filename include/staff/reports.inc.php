<?php
if(!defined('OSTSTAFFINC') || !$thisstaff) die('Access Denied');

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
                <a href="rates.php">
                Incident and Dart Rates</a>
            </strong>
    </div>
    <div>
            <em>&nbsp; Report showing Incident and Date Rates.</em>
        
    </div>
</div>    


<div class="row table <?php if ($_SESSION['cv19case'] == 0) echo 'class="hidden"';?>">
    <div>
        
            <strong>
                <a href="http://sssql01/SSRS_ReportServer/Pages/ReportViewer.aspx?%2fSafety%2fOpenCases&rs:Format=Excel">
                Open Covid Cases (Excel)</a>
            </strong>
    </div>
    <div>
            <em>&nbsp; Report showing Incident and Date Rates.</em>
        
    </div>
</div>  


 </div> </div> </div>


