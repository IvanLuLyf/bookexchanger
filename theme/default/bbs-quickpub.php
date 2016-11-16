<?php if(maoo_user_id()) : ?>
<div class="bbs-quickpub mt-40">
	<h4 class="title"><i class="glyphicon glyphicon-edit"></i> 快速发贴</h4>
		<form method="post" role="form" action="<?php echo $redis->get('site_url'); ?>/do/pubform-bbs.php">
							<div class="row">
								<div class="col-md-4 col">
									<div class="form-group">
										<select id="pub-term" class="form-control" name="page[term]">
											<?php foreach($redis->zrange('term:bbs',0,-1) as $title) : $term_id = $redis->zscore('term:bbs',$title); ?>
											<option value="<?php echo $term_id; ?>" <?php if($term_id==$redis->hget('bbs:'.$id,'term')) echo 'selected'; ?>><?php echo $title; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
								<div class="col-md-8 col">
									<div class="form-group">
										<input id="pub-title" type="text" name="page[title]" class="form-control" placeholder="请输入标题～" value="">
									</div>
								</div>
							</div>
							<link href="<?php echo $redis->get('site_url'); ?>/public/sn/summernote.css" rel="stylesheet">
							<script src="<?php echo $redis->get('site_url'); ?>/public/sn/summernote.js"></script>
							<script src="<?php echo $redis->get('site_url'); ?>/public/sn/lang/summernote-zh-CN.min.js"></script>
							<div class="form-group">
								<div id="summernote"></div>
								<textarea name="page[content]" id="post-content-textarea" class="hidden"></textarea>
							</div>
							<div class="submit-btn-hidden" style="display:none">
								<button type="button" class="btn btn-block btn-default">正在提交...</button>
							</div>
							<div class="submit-btn-show">
								<button type="submit" class="btn btn-block btn-primary" id="publish-btn-submit">
									提交
								</button>
							</div>
				</form>
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
		});
		$('.btn-block').click(function(){
			$('.submit-btn-show').css('display','none');
			$('.submit-btn-hidden').css('display','block');
		});
	</script>
<?php else : ?>
<div class="bbs-quickpub mt-40">
	<h4 class="title"><i class="glyphicon glyphicon-edit"></i> 快速发贴</h4>
	<div class="nothing">
		请在 <a href="<?php echo $redis->get('site_url'); ?>?m=user&a=login">登录</a> 或 <a href="<?php echo $redis->get('site_url'); ?>?m=user&a=register">注册</a> 后发表评论！
	</div>
</div>
<?php endif; ?>
