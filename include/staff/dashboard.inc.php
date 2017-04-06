<?php

$agents = Staff::objects()
    ->select_related('dept');
$agents->filter(array('dept'=>$thisstaff->dept_id,'isactive'=>1));

switch ($cfg->getAgentNameFormat()) {
    case 'last':
    case 'lastfirst':
    case 'legal':
    $sortOptions['name'] = array('lastname', 'firstname');
    break;
// Otherwise leave unchanged
}
$agents->order_by('lastname');

$sql='SELECT count(number) as count FROM '.TICKET_TABLE.' ticket '
.'WHERE dept_id = '.$thisstaff->dept_id
.' and status_id != 3 and status_id !=6 ';

$OpenSuggestions = db_fetch_array(db_query($sql)); 

$sql='SELECT count(number) as count FROM '.TICKET_TABLE.' ticket '
.'WHERE dept_id = '.$thisstaff->dept_id
.' and status_id = 1 ';

$SubmittedSuggestions = db_fetch_array(db_query($sql)); 

$sql='SELECT count(number) as count FROM '.TICKET_TABLE.' ticket '
.'WHERE dept_id = '.$thisstaff->dept_id
.' and status_id = 8 ';

$ActiveSuggestions = db_fetch_array(db_query($sql)); 

$sql='SELECT count(number) as count FROM '.TICKET_TABLE.' ticket '
.'WHERE dept_id = '.$thisstaff->dept_id
.' and status_id = 7 ';

$ParkedSuggestions = db_fetch_array(db_query($sql)); 


$sql='SELECT count(number) as count FROM '.TICKET_TABLE.' ticket '
.'WHERE dept_id = '.$thisstaff->dept_id
.' and status_id != 3 and status_id !=6 ';

$OpenSuggestions = db_fetch_array(db_query($sql)); 

$sql='SELECT count(number) as count FROM '.TICKET_TABLE.' ticket '
.'WHERE dept_id = '.$thisstaff->dept_id
.' and status_id = 3';

$ImplmentedSuggestions = db_fetch_array(db_query($sql)); 

$sql='SELECT count(number) as count FROM '.TICKET_TABLE.' ticket '
.'WHERE dept_id = '.$thisstaff->dept_id
.' and status_id = 6';

$NotImplmentedSuggestions = db_fetch_array(db_query($sql)); 

$sql='SELECT count(dept_id) as count FROM '.STAFF_TABLE.' staff '
.'WHERE dept_id = '.$thisstaff->dept_id
.' AND isactive = 1 group by dept_id ';

$DeptMembers = db_fetch_array(db_query($sql));    

$sql='SELECT name FROM '.DEPT_TABLE.' dept '
.'WHERE id = '.$thisstaff->dept_id;

$DeptName = db_fetch_array(db_query($sql));

$sql='SELECT  dept_id, count(number) as count FROM '.TICKET_TABLE.' ticket '
.'WHERE YEAR(created) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)'
.'WHERE MONTH(created) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)'
.'and dept_id = '.$thisstaff->dept_id.' group by dept_id ';

$PMonthSubmitted = db_fetch_array(db_query($sql));

$sql='SELECT  dept_id, count(number) as count FROM '.TICKET_TABLE.' ticket '
.'WHERE YEAR(created) = YEAR(CURRENT_DATE)'
.'AND MONTH(closed) = MONTH(CURRENT_DATE)'
.'and dept_id = '.$thisstaff->dept_id.' group by dept_id ';

$CMonthSubmitted = db_fetch_array(db_query($sql));

$sql='SELECT  dept_id, count(number) as count FROM '.TICKET_TABLE.' ticket '
.'WHERE YEAR(created) = YEAR(CURRENT_DATE)'
.'and dept_id = '.$thisstaff->dept_id.' group by dept_id ';

$YearToDateSubmitted = db_fetch_array(db_query($sql));

$sql='SELECT  dept_id, count(number) as count FROM '.TICKET_TABLE.' ticket '
.'WHERE YEAR(closed) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)'
.'WHERE MONTH(closed) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)'
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

$MemberCount = $DeptMembers["count"];
$OpenSuggestions = $OpenSuggestions["count"];
$SubmittedSuggestions = $SubmittedSuggestions["count"];
$ActiveSuggestions = $ActiveSuggestions["count"];
$ParkedSuggestions = $ParkedSuggestions["count"];
$ImplmentedSuggestions = $ImplmentedSuggestions["count"];
$NotImplmentedSuggestions = $NotImplmentedSuggestions["count"];

$PMSubmitted = (int)$PMonthSubmitted["count"];
$PMTargetSuggestions =  round(($MemberCount * 17) /12 * (int) date('m', strtotime(date('Y-m')." -1 month")));
$PMSugAheadBehind = $PMSubmitted - $PMTargetSuggestions;
$PMSugAheadBehindColor = ($PMSugAheadBehind > -1 ? 'lightgreen':'#ff9999');
$PMSugGoal = number_format($PMSubmitted / $PMTargetSuggestions * 100,2).'%';
            
$PMImplemented = (int)$PMonthImplemented["count"];
$PMTargetImplemented = $MemberCount * 12/12 * (int) date('m', strtotime(date('Y-m')." -1 month"));
$PMImpAheadBehind = $PMImplemented - $PMTargetImplemented;
$PMImpAheadBehindColor = ($PMImpAheadBehind > -1 ? 'lightgreen':'#ff9999');
$PMImpGoal = number_format($PMImplemented / $PMTargetImplemented * 100,2).'%';
            
$CMSubmitted = (int)$CMonthSubmitted["count"];
$CMTargetSuggestions =  round(($MemberCount * 17) /12 * (int) date('m', strtotime(date('Y-m'))));
$CMSugAheadBehind = $CMSubmitted - $CMTargetSuggestions;
$CMSugAheadBehindColor = ($CMSugAheadBehind > -1 ? 'lightgreen':'#ff9999');
$CMSugGoal = number_format($CMSubmitted / $CMTargetSuggestions * 100,2).'%';
            
$CMImplemented = (int)$CMonthImplemented["count"];
$CMTargetImplemented = $MemberCount * 12/12 * (int) date('m', strtotime(date('Y-m')));
$CMImpAheadBehind = $CMImplemented - $CMTargetImplemented;
$CMImpAheadBehindColor = ($CMImpAheadBehind > -1 ? 'lightgreen':'#ff9999');
$CMImpGoal = number_format($CMImplemented / $CMTargetImplemented * 100,2).'%';

$YTDSubmitted = (int)$YearToDateSubmitted["count"];
$YTDTargetSuggestions =  round(($MemberCount * 17));
$YTDSugAheadBehind = $YTDSubmitted - $YTDTargetSuggestions;
$YTDSugAheadBehindColor = ($YTDSugAheadBehind > -1 ? 'lightgreen':'#ff9999');
$YTDSugGoal = number_format($YTDSubmitted / $YTDTargetSuggestions * 100,2).'%';

$YTDImplemented = (int)$YearToDateImplemented["count"];
$YTDTargetImplemented = $MemberCount * 12;
$YTDImpAheadBehind = $YTDImplemented - $YTDTargetImplemented;
$YTDImpAheadBehindColor = ($YTDImpAheadBehind > -1 ? 'lightgreen':'#ff9999');
$YTDImpGoal = number_format($YTDImplemented / $YTDTargetSuggestions * 100,2).'%';

?>

<style>
#dashboard .ui-icon{
    background-image: url("images/ui-icons_777777_256x240.png");
}

</style>

<script>
  $( function() {
    var icons = {
         header: "ui-icon-plus",    // custom icon class
         activeHeader: "ui-icon-minus" // custom icon class
     };
    $( "#dashboard" ).accordion({
       active: false,
       autoHeight: true,
       navigation: true,
       collapsible: true,
       icons: icons
       
    });

  } );
  </script>


<div id="dashboard">
<h3>AI Team Dashboard</h3>
    <table width="100%" style="font-size: smaller">
    
        <tr style="font-weight: bold;">
            <td colspan="2"><h2> <span style="color: red; font-weight: bold;"><?php echo $DeptName["name"]; ?></span> Members: (<span style="color: red; font-weight: bold;"><?php echo $MemberCount ?></span>) </h2></td>
            <td colspan = "7"><h2>Suggestions</h2></td>        
        </tr>

<tr><td colspan = "2"><table>
    <?php
        foreach ($agents as $A) { ?>
           <tr id="<?php echo $A->staff_id; ?>">
                <td><?php echo Format::htmlchars($A->getName()); ?></td>
           </tr>
            <?php
        } // end of foreach
    ?></table>
    </td>
    <td colspan = "7"  valign="top">
        <table>
            <tr style="font-weight: bold;">
                <td rowspan="2" width="50px" style="color: red;"  align="middle"> Open (<?php echo $OpenSuggestions ?>) </td>
                <td width="80px">Submitted</td>
                <td width="50px">Active</td>
                <td width="70px">Parking Lot</td>
                
            </tr>        
            <tr>
                
                <td><?php echo $SubmittedSuggestions ?></td>
                <td><?php echo $ActiveSuggestions ?></td>
                <td><?php echo $ParkedSuggestions ?></td>
                
            </tr>
            
            <tr style="font-weight: bold;" >
                <td rowspan="2" width="50px" style="color: green;">Closed</td>
                <td width="80px">Implemented</td>
                <td width="50px" colspan="2">Not Implemented</td>
                
            </tr>        
            <tr>
                
                <td><?php echo $ImplmentedSuggestions ?></td>
                <td><?php echo $NotImplmentedSuggestions ?></td>
            </tr>
        </table>
    </tr>
        <tr><td>	&nbsp;</td></tr>
        <tr style="font-weight: bold;">
        <td colspan="9">
        <h2>Statistics:</h2></td>
        </tr>
        <tr style="font-weight: bold;">
            <td width="125px">Month</td>
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
            <td style="font-weight: bold;">Month to Date</td>
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
        <tr>
        <td>
        </td>
        </tr>
  
</table>
</div>