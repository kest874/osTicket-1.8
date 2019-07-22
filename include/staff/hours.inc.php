<script src="/scp/js/jsgrid.js"></script>
<script src="/scp/js/bootbox.js"></script>
<script src="/scp/js/toastr.js"></script>
 <link type="text/css" rel="stylesheet" href="/scp/css/jsgrid.css" />
 <link type="text/css" rel="stylesheet" href="/scp/css/jsgrid-theme.css" />
 <link type="text/css" rel="stylesheet" href="/scp/css/toastr.css" />


<div class="subnav" >

    <div class="float-left subnavtitle m-b-10">
                          
    <?php echo __('Hours'); 	?>       
    
    </div>
	  
   <div class="clearfix"></div> 
</div> 
<div class="card-box">
	<div class="table-responsive">  
		<div id="grid"></div>
   </div>  
</div>
<script>

toastr.options = {
 "closeButton": true,
  "debug": false,
  "newestOnTop": false,
  "progressBar": false,
  "positionClass": "toast-top-right",
  "preventDuplicates": true,
  "onclick": null,
  "showDuration": "600",
  "hideDuration": "2000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}


    $('#grid').jsGrid({

    width: "100%",
    height: "680px",
    filtering: true,
    inserting:true,
    editing: true,
    sorting: true,
    paging: true,
    pageSize: 12,
    autoload: true,
    pmonthSize: 10,
    pmonthButtonCount: 5,
	confirmDeleting: false,
            onItemDeleting: function (args) {
                console.log("onItemDeleting"); 
				
                 if (!args.item.deleteConfirmed) { // custom property for confirmation
				
                    args.cancel = true; // cancel deleting
                    bootbox.confirm({
					centerVertical: "true",
					message: "Delete this Record?",
					buttons: {
						confirm: {
							label: 'Yes',
							className: 'btn-sm btn-danger'
						},
						cancel: {
							label: 'No',
							className: 'btn-sm btn-warning'
						}
					},
					callback: function (result) {
						console.log('This was logged in the callback: ' + result);
						if(result == true){
							args.item.deleteConfirmed = true;
							$("#grid").jsGrid('deleteItem', args.item); //call deleting once more in callback
							toastr["success"]("Record was deleted")
						}
						 if(result == false){
								toastr["info"]("Record was not deleted")
						}
					}
				});
				
                 }
            },

     controller: {
      loadData: function(filter){
       return $.ajax({
        type: "GET",
        url: "/include/staff/hours-data.php",
        data: filter
       });
      },
      insertItem: function(item){
       return $.ajax({
        type: "POST",
        url: "/include/staff/hours-data.php",
        data:item,
		
       }).done(function(response) {
                    $("#grid").jsGrid("loadData");
					toastr.options = {"positionClass": "toast-top-full-width"}
											toastr["success"]("Record was inserted")
	  });
	
      },
      updateItem: function(item){
       return $.ajax({
        type: "PUT",
        url: "/include/staff/hours-data.php",
        data: item
       }).done(function(response) {
                   
				toastr["success"]("Record was updated")
	  });
      },
      deleteItem: function(item){
       return $.ajax({
        type: "DELETE",
        url: "/include/staff/hours-data.php",
        data: item
       });
      },
     },

     fields: [
      {
       name: "id",
    type: "hidden",
    css: 'hidden'
      },
	 {
    name: "Location", 
    type: "select", 
    items: [
     { Location: "", Id: '' },
     { Location: "AST", Id: 'AST' },
     { Location: "BRY", Id: 'BRY' },
     { Location: "CAN", Id: 'CAN' },
     { Location: "IND", Id: 'IND' },
     { Location: "MEX", Id: 'MEX' },
     { Location: "NTC", Id: 'NTC' },
     { Location: "OH", Id: 'OH' }, 
     { Location: "PAU", Id: 'PAU' },
     { Location: "RTA", Id: 'RTA' },
     { Location: "RVC", Id: 'RVC' },
     { Location: "TNN1", Id: 'TNN1' },
     { Location: "TNN2", Id: 'TNN2' },
     { Location: "TNS", Id: 'TNS' },
    ], 
    valueField: "Id", 
    textField: "Location", 
	width: 20,
	align:"center",
	validate: {
		    validator: "required",
            message: function(value, item) {
			toastr["error"]("Please choose a location.")
            },
        }
      },

      {
        name: "Hours", 
		type: "number", 
		width: 35, 
		align:"center",
		validate: {
		    validator: function(value, item) {
				if (isNaN(value) || value <=0){
					return false;
				} else {
					return true;
				}
			},
            message: function(value, item) {
			toastr["error"]("Hours must be a number and greater than 0.")
            },
        }
      },
      {
       name: "Month", 
     type: "select", 
    items: [
		{ Month: "", Id: '' },
		{ Month: "Jan", Id: 'Jan' },
		{ Month: "Feb", Id: 'Feb' },
		{ Month: "Mar", Id: 'Mar' },
		{ Month: "Apr", Id: 'Apr' },
		{ Month: "May", Id: 'May' },
		{ Month: "Jun", Id: 'Jun' },
		{ Month: "Jul", Id: 'Jul' }, 
		{ Month: "Aug", Id: 'Aug' },
		{ Month: "Sep", Id: 'Sep' },
		{ Month: "Oct", Id: 'Oct' },
		{ Month: "Nov", Id: 'Nov' },
		{ Month: "Dec", Id: 'Dec' },
	],
	valueField: "Id", 
	textField: "Month", 
	width: 20, 
    align:"center",
	validate: {
		    validator: "required",
            message: function(value, item) {
			toastr["error"]("Please choose a month.")
            },
        }
      },
      {
       name: "Year", 
    type: "number", 
	align:"center",
    width: 30, 
    validate: {
		    validator: "range",
            message: function(value, item) {
			toastr["error"]("Please enter a valid year from 2018 to <?php echo date('Y')?>.")
            },
            param: [2018, <?php echo date("Y")?>]
        }
      },
      {
       type: "control",
	   width: 30, 
      },

     ]

    });
	
</script>
