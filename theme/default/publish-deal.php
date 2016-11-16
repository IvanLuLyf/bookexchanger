<?php include('header.php'); ?>
	<div class="container">
		<div class="row">
            <form method="post" role="form" action="<?php echo $redis->get('site_url'); ?>/do/pubform-deal.php">
                <div class="col-sm-9 col">
							<div class="form-group">
								<label>
									标题
								</label>
								<input id="pub-title" type="text" name="page[title]" class="form-control" value="<?php if($id) : echo $redis->hget('deal:'.$id,'title'); endif; ?>">
							</div>
                            <div class="form-group">
								<label>
									分类
								</label>
								<div class="clearfix"></div>
								<?php foreach($redis->zrange('term:deal',0,-1) as $title) : $term_id = $redis->zscore('term:deal',$title); ?>
								<label class="radio-inline">
									<input type="radio" name="page[term]" value="<?php echo $term_id; ?>" <?php if($redis->hget('deal:'.$id,'term')==$term_id) : ?>checked<?php endif; ?>> <?php echo $title; ?>
								</label>
								<?php endforeach; ?>
							</div>
                            <div class="row">
                                <div class="col-sm-6 col">
                                    <div class="form-group">
                                        <label>
                                            筹款目标
                                        </label>
                                        <div class="input-group">
                                            <input type="text" name="page[goal]" class="form-control" placeholder="请填写大于500的整数" value="<?php if($id) : echo $redis->hget('deal:'.$id,'goal'); endif; ?>">
                                            <span class="input-group-addon">元</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col">
                                    <link href="<?php echo $redis->get('site_url'); ?>/public/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
                                    <div class="form-group">
                                        <label for="dtp_input1" class="control-label pull-left">截至时间</label>
                                        <div class="input-group date form_datetime pull-left input" data-date="<?php echo date('Y-m-d',strtotime("now")); ?>T00:00:00Z" data-link-field="dtp_input1">
                                            <input class="form-control" size="16" type="text" value="<?php if($redis->hget('deal:'.$id,'deadline')) echo date('Y-m-d H:i',$redis->hget('deal:'.$id,'deadline')); ?>" readonly>
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar icon-calendar"></span></span>
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove icon-remove-circle"></span></span>
                                        </div>
                                        <input type="hidden" id="dtp_input1" value="<?php if($redis->hget('deal:'.$id,'deadline')) echo date('Y-m-d H:i',$redis->hget('deal:'.$id,'deadline')); ?>" name="page[deadline]">
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
							<link href="<?php echo $redis->get('site_url'); ?>/public/sn/summernote.css" rel="stylesheet">
							<script src="<?php echo $redis->get('site_url'); ?>/public/sn/summernote.js"></script>
							<script src="<?php echo $redis->get('site_url'); ?>/public/sn/lang/summernote-zh-CN.min.js"></script>
							<div class="form-group">
								<label>
									项目介绍
								</label>
								<div id="summernote"><?php if($id) : echo $redis->hget('deal:'.$id,'content'); endif; ?></div>
								<textarea name="page[content]" id="post-content-textarea" class="hidden"><?php if($id) : echo $redis->hget('deal:'.$id,'content'); endif; ?></textarea>
							</div>
                            <div class="form-group cover-image">
                                <label>
                                    项目回报
                                </label>
                                <div id="reward-list">
                                    <?php if($id) : $rewards = unserialize($redis->hget('deal:'.$id,'reward')); foreach($rewards as $rewardkey=>$reward) : $rewardnum++; ?>
                                    <div class="row mb-10">
                                        <div class="col-xs-3 col">
                                            <div class="input-group">
                                                <input type="text" name="reward[<?php echo $rewardkey; ?>][price]" class="form-control" value="<?php echo $reward['price']; ?>" placeholder="支持金额" />
                                                <span class="input-group-addon">元</span>
                                            </div>
                                        </div>
                                        <div class="col-xs-3 col">
                                            <input type="text" name="reward[<?php echo $rewardkey; ?>][number]" class="form-control" value="<?php echo $reward['number']; ?>" placeholder="最高数量" />
                                        </div>
                                        <div class="col-xs-6 col">
                                            <textarea name="reward[<?php echo $rewardkey; ?>][content]" class="form-control" rows="1" placeholder="回报内容"><?php echo $reward['content']; ?></textarea>
                                        </div>
                                    </div>
                                    <?php endforeach; endif; ?>
                                    <div class="row mb-10">
                                        <div class="col-xs-3 col">
                                            <div class="input-group">
                                                <input type="text" name="reward[<?php echo $rewardnum+1; ?>][price]" class="form-control" placeholder="支持金额" />
                                                <span class="input-group-addon">元</span>
                                            </div>
                                        </div>
                                        <div class="col-xs-3 col">
                                            <input type="text" name="reward[<?php echo $rewardnum+1; ?>][number]" class="form-control" placeholder="最高数量" />
                                        </div>
                                        <div class="col-xs-6 col">
                                            <textarea name="reward[<?php echo $rewardnum+1; ?>][content]" class="form-control" rows="1" placeholder="回报内容"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="reward-btn" class="btn btn-default btn-block" num-data="<?php echo $rewardnum+1; ?>">更多</button>
                                <script>
                                    $('#reward-btn').click(function(){
                                        var num = $(this).attr('num-data')*1+1;
                                        $('#reward-list').append('<div class="row mb-10"><div class="col-xs-3 col"><div class="input-group"><input type="text" name="reward['+num+'][price]" class="form-control" placeholder="支持金额" /><span class="input-group-addon">元</span></div></div><div class="col-xs-3 col"><input type="text" name="reward['+num+'][number]" class="form-control" placeholder="最高数量" /></div><div class="col-xs-6 col"><textarea name="reward['+num+'][content]" class="form-control" rows="1" placeholder="回报内容"></textarea></div></div>');
                                        $(this).attr('num-data',num);
                                    });
                                </script>
                            </div>
							<div class="submit-btn-hidden" style="display:none">
								<button type="button" class="btn btn-block btn-default">正在提交...</button>
							</div>
							<div class="submit-btn-show">
                                <div class="row publist-btns">
                                    <div class="col-xs-12 col">
                                        <button type="submit" class="btn btn-block btn-primary btn-submit" id="publish-btn-submit">
                                            <?php if($id) : echo '审核通过'; else : echo '提交审核'; endif; ?>
                                        </button>
                                    </div>
                                </div>
							</div>
						
					<?php if($id) : ?>
					<input type="hidden" name="id" value="<?php echo $id; ?>">
					<?php endif; ?>
				</div>
                <div class="col-sm-3 col">
                    <div class="form-group">
								<label>
									封面图片
								</label>
								<div class="clearfix"></div>
								<?php
									if($id) :
										$fmimg_full = $redis->hget('deal:'.$id,'fmimg');
									endif;
									if($fmimg_full=='') :
										$fmimg_full = $redis->get('site_url').'/public/img/upload.jpg';
									endif;
								?>
								<img id="defaultx-img1" class="mb-10" src="<?php echo $fmimg_full; ?>" width="300">
								<div class="pub-imgadd">
									<button type="button" class="btn btn-default btn-block">上传图片</button>
									<input type="file" class="picfile" onchange="readFilex(this,1)" />
								</div>
								<div class="clearfix"></div>
								<textarea name="page[fmimg]" rows="1" class="form-control hidden" id="pubx-input1"><?php if($id) : if($redis->hget('deal:'.$id,'fmimg')) : echo $fmimg_full; endif; endif; ?></textarea>
								<script>
								function readFilex(obj,id){
											$('#defaultx-img'+id).attr('src','<?php echo $redis->get('site_url'); ?>/public/img/loading.gif');
											var file = obj.files[0];
											//判断类型是不是图片
											if(!/image\/\w+/.test(file.type)){
															alert("请确保文件为图像类型");
															return false;
											}

											data = new FormData();
    									data.append("file", file);
											$.ajax({
													data: data,
													type: "POST",
													url: "<?php echo $redis->get('site_url'); ?>/do/imgupload-sm.php",
													cache: false,
													contentType: false,
        									processData: false,
													success: function(url) {
														$('#defaultx-img'+id).attr('src',url);
														$('#pubx-input'+id).html(url);
													},
													error : function(data) {
														alert('上传失败');
														$('#defaultx-img'+id).attr('src','<?php echo $redis->get('site_url'); ?>/public/img/upload.jpg');
													}
											});
							}
								</script>
							</div>
                </div>
			</form>
		</div>
	</div>
	<script src="<?php echo $redis->get('site_url'); ?>/public/js/jquery.pin.min.js"></script>
				<script>
				$(document).ready(function() {
        $('#summernote').summernote({
					fontNames: ['Microsoft Yahei','Helvetica Neue', 'Arial', 'Arial Black' ],
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture'<?php if($redis->hget('user:'.maoo_user_id(),'user_level')>7) : ?>,'video'<?php endif; ?>]],
                        ['view', ['codeview']]
                    ],
					lang: 'zh-CN',
					callbacks: {
				    onImageUpload: function(files) {
							var file = files[0];
					 	  if(!/image\/\w+/.test(file.type)){
					 	   	return;
					 	  }

							var dataNumber = Math.floor(Math.random()*999+1);
							$('#summernote').summernote('insertImage', '<?php echo $redis->get('site_url'); ?>/public/img/loading.gif', function ($image) {
								$image.addClass('imgplaceholder'+dataNumber);
							});

							data = new FormData();
							data.append("file", file);
							$.ajax({
									data: data,
									type: "POST",
									url: "<?php echo $redis->get('site_url'); ?>/do/imgupload.php",
									cache: false,
									contentType: false,
									processData: false,
									success: function(url) {
										$('#summernote').summernote('insertImage', url);
										$('.note-editable img.imgplaceholder'+dataNumber).remove();
									},
									error : function(data) {
										alert('上传失败');
										$('.note-editable img.imgplaceholder'+dataNumber).remove();
									}
							});
							/*
						 	var reader = new FileReader();
						 	reader.readAsDataURL(file);
						 	reader.onload = function(e){
								var dataNumber = Math.floor(Math.random()*999+1);
								$('#summernote').summernote('insertImage', '<?php echo $redis->get('site_url'); ?>/public/img/loading.gif', function ($image) {
								  $image.addClass('imgplaceholder'+dataNumber);
								});
								$.ajax({
						        data: {
											image: this.result
										},
						        type: "POST",
						        url: "<?php echo $redis->get('site_url'); ?>/do/imgupload.php",
						        cache: false,
						        success: function(url) {
						            $('#summernote').summernote('insertImage', url);
												$('.note-editable img.imgplaceholder'+dataNumber).remove();
						        }
						    });

						 	}
							*/
				    },
						onChange: function(contents, $editable) {
							$('#post-content-textarea').val(contents);
							$(".note-toolbar").pin({containerSelector: ".note-editor"});
				    },
						onInit: function() {
				      $(".note-toolbar").pin({containerSelector: ".note-editor"});
				    }
				  }
				});
				$('#summernote2').summernote({
					fontNames: ['Microsoft Yahei','Helvetica Neue', 'Arial', 'Arial Black' ],
					lang: 'zh-CN',
					callbacks: {
				    onImageUpload: function(files) {
							var file = files[0];
					 	  if(!/image\/\w+/.test(file.type)){
					 	   	return;
					 	  }
							var dataNumber = Math.floor(Math.random()*999+1);
							$('#summernote2').summernote('insertImage', '<?php echo $redis->get('site_url'); ?>/public/img/loading.gif', function ($image) {
								$image.addClass('imgplaceholder'+dataNumber);
							});

							data = new FormData();
							data.append("file", file);
							$.ajax({
									data: data,
									type: "POST",
									url: "<?php echo $redis->get('site_url'); ?>/do/imgupload.php",
									cache: false,
									contentType: false,
									processData: false,
									success: function(url) {
										$('#summernote2').summernote('insertImage', url);
										$('.note-editable img.imgplaceholder'+dataNumber).remove();
									},
									error : function(data) {
										alert('上传失败');
										$('.note-editable img.imgplaceholder'+dataNumber).remove();
									}
							});
				    },
						onChange: function(contents, $editable) {
							$('#post-content-textarea2').val(contents);
							$(".note-toolbar").pin({containerSelector: ".note-editor"});
				    },
						onInit: function() {
				      $(".note-toolbar").pin({containerSelector: ".note-editor"});
				    }
				  }
				});
    });
		$('.btn-submit').click(function(){
			$('.submit-btn-show').css('display','none');
			$('.submit-btn-hidden').css('display','block');
		});
	</script>
<script type="text/javascript" src="<?php echo $redis->get('site_url'); ?>/public/js/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>
                            <script type="text/javascript" src="<?php echo $redis->get('site_url'); ?>/public/js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
	<script type="text/javascript">
	    $('.form_datetime').datetimepicker({
	        language:  'zh-CN',
	        weekStart: 1,
	        todayBtn:  1,
			autoclose: 1,
			todayHighlight: 1,
			startView: 2,
			forceParse: 0,
	        showMeridian: 1,
	        format: 'yyyy-mm-dd hh:ii'
	    });
	</script>
<?php include('footer.php'); ?>
