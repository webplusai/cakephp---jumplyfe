<?php echo $this->element('menu');?>
<div class="right_profile bgcolor">
	<div class="container">
		<div class="row">
			<?php if(isset($left_part_user_id) && !empty($left_part_user_id)){ ?>
				<?php echo $this->element('user_profile_left_bar');?>
			<?php } ?>
			<div class="col-md-6 col-sm-6">
				<div class="my_timeline">
					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#my_timeline1">Update Status</a></li>
						<li><a data-toggle="tab" href="#my_timeline2">Add Image</a></li>
						<li><a data-toggle="tab" href="#my_timeline3">Add Video</a></li>
					</ul>
					 <div class="tab-content">
						<div id="my_timeline1" class="tab-pane fade in active" >
							<?php echo $this->Form->create('UserFeed',array('url'=>array('controller'=>'welcomes','action'=>'addPost'),'class'=>'ajax_form','type'=>'file')); ?>	
							<div class="alert ajax_alert alert-danger display-hide">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-close"></i></button>
								<span class="ajax_message"></span>	
							</div>
							<div class="timeline_body">
								<div class="indi_comment">
									<?php echo $this->Form->textarea("UserFeed.description",array('class'=>'form-control','label'=>false,'placeholder'=>"Where to Next ?")); ?>
								</div>
								<div class="indi_comment">
									<?php echo $this->Form->button(__d("user_feeds", "Post", true),array("class"=>"btn btn-primary pull-right",'type' => 'button', 'onclick' => 'checkFeedsBlank(this)')); ?>
								</div>
							</div>
							<?php echo $this->Form->end(); ?>
						</div>
						<div id="my_timeline2" class="tab-pane fade" >
							
								<?php echo $this->Form->create('UserFeed',array('url'=>array('controller'=>'welcomes','action'=>'addImagePost'),'class'=>'ajax_form','type'=>'file')); ?>	
								<div class="alert ajax_alert alert-danger display-hide">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-close"></i></button>
									<span class="ajax_message"></span>	
								</div>
								<div class="timeline_body">
									<div class="indi_comment">
										<?php echo $this->Form->textarea("UserFeed.description",array('class'=>'form-control','label'=>false,'placeholder'=>"Where to Next ?")); ?>
									</div>
									<div class="indi_comment">
										<?php echo $this->Form->file("UserFeed.image",array('class'=>'check_size','data-max_size' => Configure::read("Site.max_upload_image_size"),'data-media_type'=>'Image','label'=>false)); ?>
									</div>
									<div class="indi_comment">
										<?php echo $this->Form->button(__d("user_feeds", "Post", true),array("class"=>"btn btn-primary pull-right")); ?>
									</div>
								</div>
								<?php echo $this->Form->end(); ?>
						</div>
						<div id="my_timeline3" class="tab-pane fade" >
								<?php echo $this->Form->create('UserFeed',array('url'=>array('controller'=>'welcomes','action'=>'addVideoPost'),'class'=>'ajax_form','type'=>'file')); ?>	
								<div class="alert ajax_alert alert-danger display-hide">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-close"></i></button>
									<span class="ajax_message"></span>	
								</div>
								<div class="timeline_body">
									<div class="indi_comment">
										<?php echo $this->Form->textarea("UserFeed.description",array('class'=>'form-control','label'=>false,'placeholder'=>"Where to Next ?")); ?>
									</div>
									<?php $video_type = array('Upload'=>'Upload','Embeded'=>'Embeded'); ?>
									<div class="indi_comment">
										<?php echo $this->Form->select("UserFeed.video_type",$video_type,array('class'=>'form-control videoType','label'=>false,'empty'=>false)); ?>
									</div>
									<div class="indi_comment" id="Embeded_Url">
										<?php echo $this->Form->textarea("UserFeed.video",array('class'=>'form-control','label'=>false,'placeholder'=>"Enter YouTube Embeded Url Here..")); ?>
										<small><b>Example Embeded Code:</b><br> &lt;iframe width=&quot;560&quot; height=&quot;315&quot; src=&quot;https://www.youtube.com/embed/TFWwireGl5o&quot; frameborder=&quot;0&quot; allowfullscreen&gt;&lt;/iframe&gt;</small>
									</div>
									<div class="indi_comment" id="Upload_Video">
										<?php echo $this->Form->file("UserFeed.upload_video",array('class'=>'check_size','data-max_size' => Configure::read("Site.max_upload_video_size"),'data-media_type'=>'Video','label'=>false)); ?>
									</div>
									<div class="indi_comment">
										<?php echo $this->Form->button(__d("user_feeds", "Post", true),array("class"=>"btn btn-primary pull-right")); ?>
									</div>
								</div>
								<?php echo $this->Form->end(); ?>
						</div>
					</div>
				</div>
				<div class="timelineContent">
				
				</div>
			</div>
			<div class="col-md-3 col-sm-3">
				<div class="right_advertisement">
					<?php echo $this->element('advertisement_right'); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function(){
	timelineContentText();
	var type = $(this).val();
	if(type == 'Embeded')
	{
		$('#Embeded_Url').show();
		$('#Upload_Video').hide();
	}
	else
	{
		$('#Embeded_Url').hide();
		$('#Upload_Video').show();
	}
	$('.videoType').change(function(){
		var type = $(this).val();
		if(type == 'Embeded')
		{
			$('#Embeded_Url').show();
			$('#Upload_Video').hide();
		}
		else
		{
			$('#Embeded_Url').hide();
			$('#Upload_Video').show();
		}
	});
	
	$(document).on("click", '.do_like', function(event) { 
		$this = $(this);
		var id = $(this).attr("data-id")
		if(id != ''){
			addBorderClass($($this).closest('.my_timeline'));
			$.ajax({
				type: "post",
				url: "<?php echo Router::url(array("plugin"=>false,"controller"=>"welcomes",'action' => 'user_feed_like')); ?>",
				data:{'id':id},
				dataType:"json",
				success:function(data){
					if(data.success)
					{
						$($this).closest('.my_timeline').find('.likes .counter').text(data.like_counter);
						$($this).find('.like_text').text(data.next_task);
					}
					removeBorderClass($($this).closest('.my_timeline'));
				}
			});
		}
	});
	
	$("input.check_size").change(function () {
		
		var max_size = $(this).attr('data-max_size');
		var show_size = Math.round(max_size / 1024);
		var media_type = $(this).attr('data-media_type');
		
		if ($(this).val() !== "") 
		{
			
			var file = $(this)[0].files[0];
			var file_size = file.size / 1024;
			
			if(file_size > max_size)
			{
				$(this).closest("form").find('.ajax_alert').slideDown().find('.ajax_message').text(media_type+' must be less than '+show_size+'MB');
				return false;
			}
			else
			{
				$(this).closest("form").find('.ajax_alert').slideUp();
				return true;
			}
		}
	});
});

function timelineContentText(){
		var id = "<?php echo $timeline_id; ?>";
		if(id != ""){
			$.ajax({
				type: "post",
				url: "<?php echo Router::url(array("plugin"=>false,"controller"=>"welcomes",'action' => 'timeline_content')); ?>",
				data:{'id':id,'friends':false},
				success: function(data) {
					$('.timelineContent').html(data);
				}
			});
		}
	}
	
function actionAfterTimeLineAddPost(data){
	if(data.success){
		timelineContentText();
	}
}
function checkFeedsBlank($this)
{
	if($($this).closest('form').find('textarea').val())
	{
		$($this).closest('form').submit();
	}
	else
	{
		$($this).closest('form').find('textarea').focus();
	}
	return false;
}
</script>

