<?php TicketForm::ensureDynamicDataView(); 
?>

<?php

	

		
	$sql="SELECT distinct year(STR_TO_DATE(left(dateofincident,10), '%Y-%m-%d')) as year 
	FROM ost_ticket__cdata order by  year(STR_TO_DATE(left(dateofincident,10), '%Y-%m-%d'));";
	
	$years = db_query($sql);
	?>         
<div class="subnav">
               
    <div class="float-left subnavtitle">
                          
    <?php echo __('Incident and Dart Rates');  
    
    $syear = $_GET['year'];
    
    if (!isset($_GET["year"])) $syear = date("Y");
	?>                        
  </div>
    

    <div class="btn-group btn-group-sm float-right m-b-10" role="group" aria-label="Button group with nested dropdown">
		
			

	  <form class="form-inline">
    <div class="form-group">
        <label class="" for="year">Year: &nbsp;</label>
					<select id="year" name="year" class="form-control form-control-sm">
                     <?php 
											foreach ($years as $year) { ?>
							           <option value="<?php echo $year["year"]?>" <?php if ($syear == $year["year"]) echo 'selected';?>><?php echo $year["year"]?> </option>';
							           <?php }
							        ?>
							       </select>
							                       
    </div>
     
    &nbsp <button type="submit" class="btn btn-primary btn-sm">Refresh</button>
</form>
	  </div>
	  
   <div class="clearfix"></div> 
</div> 

<div class="row">
  <div class="col-lg-12">		
		<div class="card-box" >
			<?php					       
					       
				$sql="select LOCATION,RECORDABLES,DARTS,HOURS,MTH,INCIDENTMONTH from (
select sum(RECORDABLES) as RECORDABLES,sum(DARTS) as DARTS, h.hours as HOURS, data.LOCATION, data.MTH, data.INCIDENTMONTH from

(select sum(isrecordable) as RECORDABLES,sum(isdart) as DARTS, d.name as LOCATION, MONTH(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) as MTH,
DATE_FORMAT(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d'),'%b') as INCIDENTMONTH

from ost_ticket t 
join ost_department d on d.id = t.dept_id
join ost_ticket__cdata tc on t.ticket_id = tc.ticket_id
where t.isrecordable = 1 and year(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')) = ".$syear."
group by d.name, MONTH(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d')), year(STR_TO_DATE(left(tc.dateofincident,10), '%Y-%m-%d'))

union all

select 0 as RECORDABLES,0 as DARTS , d1.name as LOCATION, m.MTH, m.INCIDENTMONTH from ost_department d1 join (
SELECT 1 AS MTH, 'Jan' as INCIDENTMONTH
UNION SELECT 2 AS MTH, 'Feb' as INCIDENTMONTH
UNION SELECT 3 AS MTH, 'Mar' as INCIDENTMONTH
UNION SELECT 4 AS MTH, 'Apr' as INCIDENTMONTH
UNION SELECT 5 AS MTH, 'May' as INCIDENTMONTH
UNION SELECT 6 AS MTH, 'Jun' as INCIDENTMONTH
UNION SELECT 7 AS MTH, 'Jul' as INCIDENTMONTH
UNION SELECT 8 AS MTH, 'Aug' as INCIDENTMONTH
UNION SELECT 9 AS MTH, 'Sep' as INCIDENTMONTH
UNION SELECT 10 AS MTH, 'Oct' as INCIDENTMONTH
UNION SELECT 11 AS MTH, 'Nov' as INCIDENTMONTH
UNION SELECT 12 AS MTH, 'Dec' as INCIDENTMONTH)m on 1=1) data

left join ost_hours h on h.location = data.LOCATION and h.month = data.INCIDENTMONTH and h.year = ".$syear."

group by data.LOCATION, data.MTH, data.INCIDENTMONTH)alldata /*where LOCATION = 'TNN2'*/";		       
	
			  $locsdata = db_query($sql);
			  $locsdata1 = db_query($sql);
			  $locsdata2 = db_query($sql);
			  $locsdata3 = db_query($sql);
			  
			?>
				<table class="table table-hover table-condensed table-sm text-xsmall"><thead>
        <thead>
         <tr class="bg-graphgreen t-120">
         	<th class="bg-graphgreen sticky-top t-120"></th> 	
         	<th class="bg-graphgreen sticky-top t-120"></th>
       		<th class="bg-graphgreen sticky-top t-120">Jan</th>
					<th class="bg-graphgreen sticky-top t-120">Feb</th>
					<th class="bg-graphgreen sticky-top t-120">Mar</th>
					<th class="bg-graphgreen sticky-top t-120">Apr</th>
					<th class="bg-graphgreen sticky-top t-120">May</th>
					<th class="bg-graphgreen sticky-top t-120">Jun</th>
					<th class="bg-graphgreen sticky-top t-120">Jul</th>
					<th class="bg-graphgreen sticky-top t-120">Aug</th>
					<th class="bg-graphgreen sticky-top t-120">Sep</th>
					<th class="bg-graphgreen sticky-top t-120">Oct</th>
					<th class="bg-graphgreen sticky-top t-120">Nov</th>
					<th class="bg-graphgreen sticky-top t-120">Dec</th>
					<th class="bg-graphgreen sticky-top t-120">YTD</th>        
        </thead>
        	 <?php
          $ploc = null;             
          $n=0;     
          
          foreach ($locsdata as $loc) {
        
          		$cloc = $loc["LOCATION"];	
          	
          		if ($cloc != $ploc){ 
          		  echo '</tr><tr>';	
          		  echo '<th rowspan=6 style="vertical-align:middle; transform: rotate(90deg);" >'.$loc["LOCATION"].'</th>'; 
	          	  echo '</tr><tr><td style="font-weight: 600;">Recordable Injuries</td>';
          			
          			if ($loc["LOCATION"] == $cloc){
          				
          			$it = null;
          			foreach ($locsdata1 as $rec) {
										if ($rec["LOCATION"]==$loc["LOCATION"]){
											$it = $rec["RECORDABLES"] + $it;
											if ($rec["RECORDABLES"]!=0) {$style='style=color:red;font-weight:bold;';} else {$style='style=color:black;font-weight:normal;';}
          						echo '<td '.$style.'>'.$rec["RECORDABLES"].'</td>';  
          					}
          					  
          			}
          			$style='style=color:black;font-weight:bold;';
          		  echo '<td '.$style.'>'.$it.'</td>';
          		}
          		  echo '</tr><tr><td style="font-weight: 600;">DART Injuries</td>';
          			$dt = null;
          			foreach ($locsdata3 as $dart) {
									if ($loc["LOCATION"]==$dart["LOCATION"]){
			 					 	$dt = $dart["DARTS"] + $dt;
			 					 	if ($dart["DARTS"]!=0) {$style='style=color:red;font-weight:bold;';} else {$style='style=color:black;font-weight:normal;';}
			 						echo '<td '.$style.'>'.$dart["DARTS"].'</td>';
          				} 
          		
          			}
          			$style='style=color:black;font-weight:bold;';
          			echo '<td '.$style.'>'.$dt.'</td>';
          			echo '</tr><tr><td style="font-weight: 600;">Hours Worked</td>';
          		
          			$ht = null;
          			foreach ($locsdata3 as $hour) {
									if ($loc["LOCATION"]==$hour["LOCATION"]){
									$ht = $hour["HOURS"]+$ht;	
          				echo '<td>'.number_format($hour["HOURS"]).'</td>';
          				} 
          			}
          			$style='style=color:black;font-weight:bold;';
          			echo '<td '.$style.'>'.number_format($ht).'</td>';
          			
          			 			
          			
          			
          			echo '</tr><tr><td style="font-weight: 600;">Incident Rate</td>';
          			$h = null;
          			$i = null;
          			foreach ($locsdata3 as $hour) {
									if ($loc["LOCATION"]==$hour["LOCATION"]){
									$style ='style="font-weight: 600;"';						
									$h = $hour['HOURS'] +$h;
          				$i = $hour['RECORDABLES'] +$i;
          				if ($h !=0){ 
          					$ir = round($i*200000/$h,2);
          					}else{
                      $ir = 0;   
                  } 
          				echo '<td>'.$ir.'</td>';
          				} 
          			}
          			$style='style=color:black;font-weight:bold;';
          			if ($ht !=0){
          				$irt = round($it*200000/$ht,2);
          			}else{
                      $irt = 0;   
                  }	
          			echo '<td '.$style.'>'.$irt.'</td>';
          			
          		
          		  echo '</tr><tr><td style="font-weight: 600;">DART Rate</td>';
          			$h = null;
          			$d = null;
          			foreach ($locsdata3 as $hour) {
									if ($loc["LOCATION"]==$hour["LOCATION"]){
									$style ='style="font-weight: 600;"';						
									$h = $hour['HOURS'] +$h;
          				$d = $hour['DARTS'] +$d;
          				if ($h !=0){ 
          					$dr = round($d*200000/$h,2);
          					}else{
                      $dr = 0;   
                  } 
          				echo '<td>'.$dr.'</td>';
          				} 
          			}
          			$style='style=color:black;font-weight:bold;';
          			if ($ht !=0){
          				$drt = round($dt*200000/$ht,2);
          			}else{
                      $drt = 0;   
                  }	
          			echo '<td '.$style.'>'.$drt.'</td>';
          			
          		  echo '</tr>';
          			
          	}
							if ($cloc != $ploc){
          		echo '<tr><td colspan=15>&nbsp;</td></tr>';
          		}
          		$ploc = $loc["LOCATION"];
          }
				?>
        </tr>
        
        </table> 
   		</div>
	</div>
</div>
          		