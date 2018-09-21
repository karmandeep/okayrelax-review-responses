<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>WHMCS - Reviews - Responses</title>

  <link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600" rel="stylesheet">
  <link href="templates/blend/css/all.min.css?v=adcb9b" rel="stylesheet" />

  <script type="text/javascript">
	
	function check(id) {
		
		var r = confirm("Confirm Approval!");
		if (r == true) {
			
			//alert(id);
			//Lets Delete
			window.location = "addonmodules.php?module=review_responses&id="+id+"&action=approvestatus&mode=review&review_responses_id="+<?php echo $review->id; ?>;
			
		} else {
			
			return false;			
		} 
	}
	
	
	function decheck(id) {
		
		//alert(id);

		var r = confirm("Confirm Rejection!");
		if (r == true) {
			
			//Lets Delete
			//window.location = "addonmodules.php?module=review_responses&id="+id+"&action=rejectstatus&mode=review";
			window.location = "addonmodules.php?module=review_responses&id="+id+"&action=rejectstatus&mode=review&review_responses_id="+<?php echo $review->id; ?>;
			
		} else {
			
			return false;			
		} 
	}

  </script>  
  <script type="text/javascript">
  	<?php if($review->status == 1): ?>
	   window.opener.location.reload(false);	
  	<?php endif; ?>
	<?php if($messages[(count($messages) - 1)]->msgstatus == 0): ?>
	   window.opener.location.reload(false);	
  	<?php endif; ?>
	
      function checkForm(Form) {
          
         /* var ipAddressRegEx = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;

          if(Form.ipaddress.value == '') {
              alert("Please Enter IP Address.");
              Form.ipaddress.focus();
              return false;
          } else if(!ipAddressRegEx.test(Form.ipaddress.value)) {
              alert("IP Address Invalid.");
              Form.ipaddress.focus();
              return false;
          } else {
              return true;
          }*/
          
          return true;
      }
  
  	  function checkFormMessage(Form) {
		  
		  if(Form.message.value == '') {
              alert("Please Enter Message.");
              Form.message.focus();
              return false;
          }
		  
		  return true;
	  }
  		
	  
	  
  </script>

  <?php
      if(isset($_GET['result']) && $_GET['result'] === 'success'):
  ?>
      <script type="text/javascript">	
          
		  //Don't Need this Here Let Them Close this.
		  //alert('Record Updated Successfully.'); 
          //window.opener.location.reload(false);
          //window.close();
          
      </script>
  <?php	
      endif;
  ?>
  
</head>
<body data-phone-cc-input="1">
     
  <div class="row">
      <div class="col-md-12" style="margin:15px;">
     
          <form name="review" onSubmit="return checkForm(this);" action="addonmodules.php?module=review_responses&action=submit" method="post">
              <input type="hidden" name="mode" value="review">
              <input type="hidden" name="id" value="<?php echo $review->id; ?>">
          
              <table class="form" width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tbody>
                      <tr>
                          <td class=" text-left" colspan="4"> <h1><?php echo $LANG['review_heading'] ?></h1> </td>
                      </tr>	
                      
                      <tr>
                          <td class="fieldlabel text-left" ></td>
                          <td class="fieldlabel text-left" width="15%"><?php echo $LANG['review_title'] ?>:</td>
                          <td class="fieldarea text-left" ><a href="#" onClick="window.open('tasks.php?action=view&id=<?php echo $review->ticket_id; ?>');" ><?php echo $review->tid; ?> - <?php echo $review->title; ?></a></td>
                          <td class="fieldlabel text-left" ></td>
                      </tr>

                      <tr>
                          <td class="fieldlabel text-left" ></td>
                          <td class="fieldlabel text-left" ><?php echo $LANG['review_message'] ?>:</td>
                          <td class="fieldarea text-left" ><?php echo nl2br($review->message); ?></td>
                          <td class="fieldlabel text-left" ></td>
                      </tr>

                      <tr>
                          <td class="fieldlabel text-left" ></td>
                          <td class="fieldlabel text-left" ><?php echo $LANG['dateadded'] ?>:</td>
                          <td class="fieldarea text-left" ><?php echo fromMySQLDate($review->created_at,1); ?></td>
                          <td class="fieldlabel text-left" ></td>
                      </tr>

                      <tr>
                          <td class="fieldlabel text-left" ></td>
                          <td class="fieldlabel text-left" ><?php echo $LANG['datemodified'] ?>:</td>
                          <td class="fieldarea text-left" ><?php echo fromMySQLDate($review->updated_at,1); ?></td>
                          <td class="fieldlabel text-left" ></td>
                      </tr>
                      <tr>
                          <td class="fieldlabel text-left" ></td>
                          <td class="fieldlabel text-left" ><?php echo $LANG['review_reviewer'] ?>:</td>
                          <td class="fieldarea text-left" ><?php echo getAdminName($review->reviewer_id); ?></td>
                          <td class="fieldlabel text-left" ></td>
                      </tr>

                      <tr>
                          <td class="fieldlabel text-left" ></td>
                          <td class="fieldlabel text-left" ><?php echo $LANG['review_admin'] ?>:</td>
                          <td class="fieldarea text-left" ><?php echo getAdminName($review->admin_id); ?></td>
                          <td class="fieldlabel text-left" ></td>
                      </tr>
                      
                      
                      <tr>
                          <td class="fieldlabel text-left" ></td>
                          <td class="fieldlabel text-left" ><?php echo $LANG['review_status'] ?>:</td>
                          <td class="fieldarea text-left" >
                          	<select name="status">
                            	<option disabled <?php if($review->status == 0): ?> selected="selected" <?php endif; ?> value="0"><?php echo $LANG['unpublished'] ?></option>
                            	<option disabled <?php if($review->status == 1): ?> selected="selected" <?php endif; ?>  value="1"><?php echo $LANG['underreview'] ?></option>
                            	<option <?php if($review->status == 2): ?> selected="selected" <?php endif; ?>  value="2"><?php echo $LANG['accepted'] ?></option>
                            	<option <?php if($review->status == 2): ?> disabled <?php endif; ?> <?php if($review->status == 3): ?> selected="selected" <?php endif; ?>  value="3"><?php echo $LANG['rejected'] ?></option>
                          	</select>
                          </td>
                          <td class="fieldlabel text-left" ></td>
                      </tr>

                      <tr>
                          <td class="fieldlabel text-left" ></td>
                          <td class="fieldlabel text-left" ><?php echo $LANG['task_status_change'] ?>:</td>
                          <td class="fieldarea text-left" >
                          <?php if($review->approved == 0): ?>
                          	<input type="hidden" name="ticket_status_request_id" value="<?php echo $review->ticket_status_request_id; ?>" >
                          	<label class="label label-default"><?php echo $review->taskstatuschange; ?></label>
                          <?php elseif($review->approved == 2): ?>
                          	<input type="hidden" name="ticket_status_request_id" value="<?php echo $review->ticket_status_request_id; ?>" >
                          	<label class="label label-danger"><?php echo $review->taskstatuschange; ?></label>
                          <?php else: ?>
                          	<input type="hidden" name="ticket_status_request_id" value="0" >
                          	<label class="label label-success"><?php echo ($review->taskstatuschange?$review->taskstatuschange:'No Change'); ?></label>
                          <?php endif; ?>
                          
                          <?php if(!$review->taskstatuschange): ?>
                          	No Change
                          <?php endif; ?>
                          <!--<button class="label label-success"><?php echo $LANG['task_status_approve'] ?></button>
                          	-->
                          </td>
                          <td class="fieldlabel text-left"></td>
                      </tr>
                      

                      <tr>
                          <td class="fieldlabel text-left"></td>
                          <td class="fieldlabel text-left"><?php echo $LANG['review_reason'] ?>:</td>
                          <td class="fieldarea text-left"><textarea name="notes" cols="50" rows="5"><?php echo $review->notes; ?></textarea></td>
                          <td class="fieldlabel text-left"></td>
                      </tr>
                     
                      
                      <tr>
                          <td class="text-center" colspan="4"><input class="btn btn-default" type="button" name="cancel" onClick="window.close();" value="Close">&nbsp;<input class="btn btn-primary" type="submit" name="submit" value="Save"></td>
                      </tr>
                  </tbody>
              </table>    	
          
          </form>

          <form name="review" onSubmit="return checkFormMessage(this);" action="addonmodules.php?module=review_responses&action=submit" method="post">
              <input type="hidden" name="mode" value="sendmessage">
              <input type="hidden" name="reviewer_id" value="<?php echo $reviewer_id; ?>">
              <input type="hidden" name="admin_id" value="0">
              <input type="hidden" name="id" value="<?php echo $review->id; ?>">

              <table class="form" width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tbody>
                  
                   <tr>
                        <td class=" text-left" colspan="4"> <h1><?php echo $LANG['review_msgs'] ?></h1> </td>
                   </tr>	
				   <?php if(count($messages) > 0): ?>
                   <?php foreach($messages as $key => $value): ?>                       
                   <tr valign="top">
                        <td class="fieldlabel text-left" ></td>
                        <?php if($value->admin_id > 0): ?>
                        	<td class="fieldlabel text-right"  width="15%"> <?php echo getAdminName($value->admin_id); ?>: 
                        <?php endif; ?>
                        <?php if($value->reviewer_id > 0): ?>
                        	<td class="fieldlabel text-right"  width="15%"> <?php echo getAdminName($value->reviewer_id); ?>: 
                        <?php endif; ?>
                        	<br />
                        	<small><?php echo fromMySQLDate($value->created_at , 1); ?></small>
                        </td>
                        
                        <td class="fieldarea text-left"> <?php echo nl2br($value->message); ?> </td>
                        <td class="fieldlabel text-left"></td>
                   </tr>	
				   <?php endforeach; ?>
                   <?php else: ?>
                   <tr>
                        <td class="fieldlabel text-left"></td>
                        <td class="fieldlabel text-left"></td>
                        <td class="fieldarea text-center"><?php echo $LANG['no_review_msgs'] ?></td>
                        <td class="fieldlabel text-left"></td>
                    </tr>
				   
				   <?php endif; ?>   
                    <tr>
                        <td class="fieldlabel text-left"></td>
                        <td class="fieldlabel text-left"><?php echo $LANG['review_msg'] ?>:</td>
                        <td class="fieldarea text-left">
                          	<textarea name="message" cols="50" rows="5"></textarea>
                        </td>
                        <td class="fieldlabel text-left" ></td>
                    </tr>
                    
                    <tr>
                        <td class="text-center" colspan="4"><input class="btn btn-default" type="button" name="cancel" onClick="window.close();" value="Close">&nbsp;<input class="btn btn-primary" type="submit" name="submit" value="Send"></td>
                    </tr>
                  </tbody>
              </table>    	
          
          </form>
          

          <form name="review" onSubmit="return false;" action="addonmodules.php?module=review_responses&action=submit" method="post">
              <!--<input type="hidden" name="mode" value="setstatusonly">
              <input type="hidden" name="reviewer_id" value="<?php echo $reviewer_id; ?>">
              <input type="hidden" name="admin_id" value="0">
              <input type="hidden" name="id" value="<?php echo $review->id; ?>">-->

              <table class="form" width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tbody>
                  	
                   <tr>
                      <td class=" text-left" colspan="4"> <h1><?php echo $LANG['review_msgs_statusonly'] ?><small><i>(Note: These are not associated with any Response from the VA)</i></small></h1></td>
                   </tr>	
                  <?php //approved ?>
                  <?php if( count($status_change) > 0): ?> 
                  	<?php foreach($status_change as $key => $value): ?>
                          <tr>
                              <td class="fieldlabel text-left" width="35%" ><small>By (<?php echo getAdminName($value->adminid); ?>) On [<?php echo fromMySQLDate($value->created_at,1); ?>]</small></td>
                              <td class="fieldlabel text-left small" ><?php echo $LANG['review_msgs_status_qry']; ?>:</td>
                              <td class="fieldarea text-left" ><label class="label <?php if($value->approved == 0): ?> label-default <?php elseif($value->approved == 1): ?> label-success <?php else: ?> label-danger <?php endif; ?>"><?php echo $value->status; ?></label></td>
                              <td class="fieldarea text-right" ><?php if($value->approved == 0): ?><button onClick="return check(<?php echo $value->id; ?>)" class="btn btn-success"><i class="fa fa-check"></i> <?php echo $LANG['task_status_approve']; ?></button> <button onClick="return decheck(<?php echo $value->id; ?>)" class="btn btn-danger"><i class="fa fa-remove"></i> <?php echo $LANG['task_status_reject']; ?></button><?php elseif($value->approved == 1): ?><label class="label label-success">Approved</label><?php else: ?><label class="label label-danger">Rejected</label><?php endif; ?></td>
                          </tr>
                  	<?php endforeach; ?>
                  <?php endif; ?>
                   
                  </tbody>
              </table>    	
          
          </form>

          
      </div>
  </div>
</body>
</html>