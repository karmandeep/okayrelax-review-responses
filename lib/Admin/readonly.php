<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>WHMCS - Reviews - Responses</title>

  <link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600" rel="stylesheet">
  <link href="templates/blend/css/all.min.css?v=adcb9b" rel="stylesheet" />
</head>
<body data-phone-cc-input="1">
     
  <div class="row">
      <div class="col-md-12" style="margin:15px;">
     
          
              <table class="form" width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tbody>
                  
                      <tr>
                          <td class=" text-left" colspan="4"> <h1><?php echo $LANG['review_heading'] ?></h1> </td>
                      </tr>	
                      
                      <tr>
                          <td class="fieldlabel text-left" ></td>
                          <td class="fieldlabel text-left" width="15%" ><?php echo $LANG['review_title'] ?>:</td>
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
                          <td class="fieldarea text-left" ><label 
                            	 <?php if($review->status == 0): ?> class="label label-info"> <?php echo $LANG['unpublished'] ?> <?php endif; ?>
                            	 <?php if($review->status == 1): ?> class="label label-warning"> <?php echo $LANG['underreview'] ?> <?php endif; ?>
                            	 <?php if($review->status == 2): ?> class="label label-success"> <?php echo $LANG['accepted'] ?> <?php endif; ?>
                            	 <?php if($review->status == 3): ?> class="label label-danger"> <?php echo $LANG['rejected'] ?> <?php endif; ?>
                          		 </label>
                          </td>
                          <td class="fieldlabel text-left" ></td>
                      </tr>
                      
                      <tr>
                          <td class="fieldlabel text-left" ></td>
                          <td class="fieldlabel text-left" ><?php echo $LANG['task_status_change'] ?>:</td>
                          <td class="fieldarea text-left" >
                          <?php if($review->approved == 0): ?>
                          	<label class="label label-default"><?php echo $review->taskstatuschange; ?></label>
                          <?php elseif($review->approved == 2): ?>
                          	<label class="label label-danger"><?php echo $review->taskstatuschange; ?></label>
						  <?php else: ?>
                          	<label class="label label-success"><?php echo $review->taskstatuschange; ?></label>
                          <?php endif; ?>
                          <?php if(!$review->taskstatuschange): ?>
                          	No Change
                          <?php endif; ?>
                          </td>
                          <td class="fieldlabel text-left" ></td>
                      </tr>
                      
                      <?php if($review->notes): ?>
                     
                      <tr>
                          <td class="fieldlabel text-left" ></td>
                          <td class="fieldlabel text-left" ><?php echo $LANG['review_reason'] ?>:</td>
                          <td class="fieldarea text-left" ><?php echo nl2br($review->notes); ?></td>
                          <td class="fieldlabel text-left" ></td>
                      </tr>
                      
                     <?php endif; ?>
                     
                  </tbody>
              </table>    	
          


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
                        	<td class="fieldlabel text-right" width="15%"> <?php echo getAdminName($value->admin_id); ?>: 
                        <?php endif; ?>
                        <?php if($value->reviewer_id > 0): ?>
                        	<td class="fieldlabel text-right" width="15%"> <?php echo getAdminName($value->reviewer_id); ?>: 
                        <?php endif; ?>
                        	<br />
                        	<small><?php echo fromMySQLDate($value->created_at , 1); ?></small>
                        </td>
                        
                        <td class="fieldarea text-left"> <?php echo nl2br($value->message); ?> </td>
                        <td class="fieldlabel text-left" ></td>
                   </tr>	
				   <?php endforeach; ?>
                   <?php else: ?>
                   
                   	  <tr>
                        <td class="fieldlabel text-left" ></td>
                        <td class="fieldlabel text-left" ></td>
                        <td class="fieldarea text-center" ><?php echo $LANG['no_review_msgs'] ?></td>
                        <td class="fieldlabel text-left" ></td>
                      </tr>
                   
                   
                   <?php endif; ?>   
					<tr>    
                        <td class="text-center" colspan="4"><input class="btn btn-default" type="button" name="cancel" onClick="window.close();" value="Close"></td>
                    </tr>
                                     
                  </tbody>
              </table>    	
          
          
          
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
                              <td class="fieldlabel text-right" ></td>
                          </tr>
                  	<?php endforeach; ?>
                  <?php endif; ?>
                   
                  </tbody>
              </table>    	
          
          
      </div>
  </div>
</body>
</html>