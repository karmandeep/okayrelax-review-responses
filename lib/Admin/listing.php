<script type="text/javascript">
	
	function check(id) {
		
		var r = confirm("Confirm Approval!");
		if (r == true) {
			
			//Lets Delete
			window.location = "addonmodules.php?module=review_responses&id="+id+"&action=approvestatus";
			
		} else {
			
			return false;			
		} 
	}
	
	
	function decheck(id) {
		
		var r = confirm("Confirm Rejection!");
		if (r == true) {
			
			//Lets Delete
			window.location = "addonmodules.php?module=review_responses&id="+id+"&action=rejectstatus";
			
		} else {
			
			return false;			
		} 
	}
	
	$(document).ready(function() {

		$(".admin-tabs").tabdrop(); $(window).resize();
		$( "a.tab-top" ).click( function() {
			var tabId = $(this).data('tab-id');
			$("#tab").val(tabId);
			window.location.hash = 'tab=' +  + tabId;
		});
		
		var selectedTab = 0;
		
		if (selectedTab == 0) {
			refreshedTab = window.location.hash;
			if (refreshedTab) {
				refreshedTab = refreshedTab.substring(5);
				$("a[href='#tab" +  + refreshedTab + "']").click();
			}
		}

	} );

</script>
<script type="text/javascript" src="<?php echo $systemurl; ?>assets/js/bootstrap-tabdrop.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $systemurl; ?>assets/css/tabdrop.css" />



<ul class="nav nav-tabs admin-tabs" role="tablist">
    <li class="dropdown pull-right tabdrop hide"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-align-justify"></i> <b class="caret"></b></a><ul class="dropdown-menu"></ul></li>
    <li class="active"><a class="tab-top" href="#tab1" role="tab" data-toggle="tab" id="tabLink1" data-tab-id="1"><?php echo $LANG['title_general']; ?></a></li>
    <li><a class="tab-top" href="#tab2" role="tab" data-toggle="tab" id="tabLink2" data-tab-id="2"><?php echo $LANG['title_localisation']; ?></a></li>
    <li><a class="tab-top" href="#tab3" role="tab" data-toggle="tab" id="tabLink3" data-tab-id="3"><?php echo $LANG['title_ordering']; ?></a></li>
    <li><a class="tab-top" href="#tab4" role="tab" data-toggle="tab" id="tabLink4" data-tab-id="4"><?php echo $LANG['title_review_status']; ?></a></li>
</ul>



<div class="tab-content admin-tabs">
  <li class="dropdown pull-right tabdrop hide">
   	<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-align-justify"></i> <b class="caret"></b></a>
    <ul class="dropdown-menu"></ul>
  </li>
  <div class="tab-pane active" id="tab1">
		
        <table class="display" id="example" style="font-size:14px;" width="100%" border="0" cellspacing="0" cellpadding="0">
        	<thead>
            	<tr>
                    <th><?php echo $LANG['review_title']; ?></th>
                    <!--<th><?php echo $LANG['review_message']; ?></th>-->
                    <th><?php echo $LANG['review_admin']; ?></th>
                    <th><?php echo $LANG['review_status']; ?></th>
                    <th><?php echo $LANG['review_customer']; ?></th>
                    <th><?php echo $LANG['review_reviewer']; ?></th>
                    <th><?php echo $LANG['review_action']; ?></th>
                </tr>            
            </thead>
            <tbody>
    
        <?php if(count($all)): ?>
			<?php foreach($all as $key => $value): ?>            
	        <?php $client_details = getClientsDetails($value->userid); ?>
            	<tr>
                	<td class="fieldarea text-left" width="30%"><a href="tasks.php?action=view&id=<?php echo $value->ticket_id; ?>" target="_blank"><?php echo $value->tid; ?> - <?php echo substr($value->title , 0 , 50); ?></a></td>
                	<!--<td class="fieldarea text-left" width="20%"><?php echo substr($value->message, 0 , 50); ?></td>-->
                	<td class="fieldarea text-left" ><?php echo getAdminName($value->admin_id); ?></td>
                	<td class="fieldarea text-left"><label class="label <?php if($value->status == 0): ?> label-default <?php elseif($value->status == 1): ?> label-warning <?php elseif($value->status == 2): ?> label-success <?php else: ?> label-danger <?php endif; ?>"><?php echo $this->reviewStatus($value->status); ?></label></td>
                	<td class="fieldarea text-left text-capitalize"><a href="clientssummary.php?userid=<?php echo $value->userid; ?>" target="_blank"><?php echo $client_details['fullname']; ?></a></td>
                	<td class="fieldarea text-left"><?php if($reviewer_id == $value->reviewer_id): ?> Me <?php else: ?><?php echo ($value->reviewer_id > 0)?getAdminName($value->reviewer_id):'<label class="label label-success">Open</label>'; ?><?php endif; ?></td>
            		<td><?php echo $this->reviewButton($value->id); ?></td>
            	
        		</tr>
            <?php endforeach; ?>
        <?php endif; ?>
			</tbody>        
        </table>
  
  </div>
  <div class="tab-pane" id="tab2">
		
        <table class="display" id="example2" style="font-size:14px;" width="100%" border="0" cellspacing="0" cellpadding="0">
        	<thead>
            	<tr>
                    <th><?php echo $LANG['review_title']; ?></th>
                    <!--<th><?php echo $LANG['review_message']; ?></th>-->
                    <th><?php echo $LANG['review_admin']; ?></th>
                    <th><?php echo $LANG['review_status']; ?></th>
                    <th><?php echo $LANG['review_customer']; ?></th>
                    <th><?php echo $LANG['review_reviewer']; ?></th>
                    <th><?php echo $LANG['review_action']; ?></th>
                </tr>            
            </thead>
            <tbody>
    
        <?php if(count($pending)): ?>
			<?php foreach($pending as $key => $value): ?>            
	        <?php $client_details = getClientsDetails($value->userid); ?>
            	<tr>
                	<td class="fieldarea text-left" width="30%"><a href="tasks.php?action=view&id=<?php echo $value->ticket_id; ?>" target="_blank"><?php echo $value->tid; ?> - <?php echo substr($value->title , 0 , 50); ?></a></td>
                	<!--<td class="fieldarea text-left" width="20%"><?php echo substr($value->message, 0 , 50); ?></td>-->
                	<td class="fieldarea text-left" ><?php echo getAdminName($value->admin_id); ?></td>
                	<td class="fieldarea text-left"><label class="label <?php if($value->status == 0): ?> label-default <?php elseif($value->status == 1): ?> label-warning <?php elseif($value->status == 2): ?> label-success <?php else: ?> label-danger <?php endif; ?>"><?php echo $this->reviewStatus($value->status); ?></label></td>
                	<td class="fieldarea text-left text-capitalize"><a href="clientssummary.php?userid=<?php echo $value->userid; ?>" target="_blank"><?php echo $client_details['fullname']; ?></a></td>
                	<td class="fieldarea text-left"><?php echo ($value->reviewer_id > 0)?getAdminName($value->reviewer_id):'<label class="label label-success">Open</label>'; ?></td>
            		<td><?php echo $this->reviewButton($value->id); ?></td>
        		</tr>
            <?php endforeach; ?>
        <?php endif; ?>
			</tbody>        
        </table>

  </div>
 
  <div class="tab-pane" id="tab3">

        <table class="display" id="example3" style="font-size:14px;" width="100%" border="0" cellspacing="0" cellpadding="0">
        	<thead>
            	<tr>
                    <th><?php echo $LANG['review_title']; ?></th>
                    <!--<th><?php echo $LANG['review_message']; ?></th>-->
                    <th><?php echo $LANG['review_admin']; ?></th>
                    <th><?php echo $LANG['review_status']; ?></th>
                    <th><?php echo $LANG['review_customer']; ?></th>
                    <!--<th><?php echo $LANG['review_reviewer']; ?></th>-->
                    <th><?php echo $LANG['review_action']; ?></th>
                </tr>            
            </thead>
            <tbody>
    
        <?php if(count($myreview)): ?>
			<?php foreach($myreview as $key => $value): ?>            
	        <?php $client_details = getClientsDetails($value->userid); ?>
            	<tr>
                	<td class="fieldarea text-left" width="30%"><a href="tasks.php?action=view&id=<?php echo $value->ticket_id; ?>" target="_blank"><?php echo $value->tid; ?> - <?php echo substr($value->title , 0 , 50); ?></a></td>
                	<!--<td class="fieldarea text-left" width="20%"><?php echo substr($value->message, 0 , 50); ?></td>-->
                	<td class="fieldarea text-left" ><?php echo getAdminName($value->admin_id); ?></td>
                	<td class="fieldarea text-left"><label class="label <?php if($value->status == 0): ?> label-default <?php elseif($value->status == 1): ?> label-warning <?php elseif($value->status == 2): ?> label-success <?php else: ?> label-danger <?php endif; ?>"><?php echo $this->reviewStatus($value->status); ?></label></td>
                	<td class="fieldarea text-left text-capitalize"><a href="clientssummary.php?userid=<?php echo $value->userid; ?>" target="_blank"><?php echo $client_details['fullname']; ?></a></td>
                	<!--<td class="fieldarea text-left"><?php echo ($value->reviewer_id > 0)?getAdminName($value->reviewer_id):'<label class="label label-success">Open</label>'; ?></td>-->
            		<td><?php echo $this->reviewButton($value->id); ?></td>
        		</tr>
            <?php endforeach; ?>
        <?php endif; ?>
			</tbody>        
        </table>

  </div>
  
  <div class="tab-pane" id="tab4">  
    
    	<table class="display" id="example4" style="font-size:14px;" width="100%" border="0" cellspacing="0" cellpadding="0">
        	<thead>
            	<tr>
                    <th><?php echo $LANG['review_title']; ?></th>
                    <th><?php echo $LANG['review_admin']; ?></th>
                    <th><?php echo $LANG['review_status']; ?></th>
                    <th><?php echo $LANG['review_customer']; ?></th>
                    <th><?php echo $LANG['review_action']; ?></th>
                </tr>            
            </thead>
            <tbody>
    
        <?php if(count($status_query)): ?>
			<?php foreach($status_query as $key => $value): ?>            
	        <?php $client_details = getClientsDetails($value->userid); ?>
            	<tr>
                	<td class="fieldarea text-left" width="30%"><a href="tasks.php?action=view&id=<?php echo $value->ticket_id; ?>" target="_blank"><?php echo $value->tid; ?> - <?php echo substr($value->title , 0 , 50); ?></a></td>
                	<td class="fieldarea text-left" ><?php echo getAdminName($value->adminid); ?></td>
                	<td class="fieldarea text-left"><label class="label <?php if($value->approved == 0): ?> label-default <?php elseif($value->approved == 1): ?> label-success <?php else: ?> label-danger <?php endif; ?>"><?php echo $value->status; ?></label></td>
                	<td class="fieldarea text-left text-capitalize"><a href="clientssummary.php?userid=<?php echo $value->userid; ?>" target="_blank"><?php echo $client_details['fullname']; ?></a></td>
            		<td><?php if($value->approved == 0): ?><button onClick="return check(<?php echo $value->id; ?>)" class="btn btn-success"><i class="fa fa-check"></i> <?php echo $LANG['task_status_approve']; ?></button> <button onClick="return decheck(<?php echo $value->id; ?>)" class="btn btn-danger"><i class="fa fa-remove"></i> <?php echo $LANG['task_status_reject']; ?></button><?php endif; ?></td>
        		</tr>
            <?php endforeach; ?>
        <?php endif; ?>
			</tbody>        
        </table>
        
  </div>
  
</div>

<!--<div class="modal whmcs-modal fade modal-wizard modal-setup-wizard in" id="modalAjax" tabindex="-1" role="dialog" aria-hidden="true" style="display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content panel panel-primary">
            <div class="modal-header panel-heading" id="modalAjaxHeader">
                <button id="modalAjaxCloseSmall" type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="modalAjaxTitle">Review</h4>
            </div>
            <div class="modal-body panel-body" id="modalAjaxBody">
    

			<style>
            
            </style>
            
            </div>
            
        </div>
    </div>
</div>-->