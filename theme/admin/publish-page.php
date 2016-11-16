<?php include('header.php'); ?>
<div class="container admin">
	<div class="row">
		<div class="col-sm-3 col user-center-side">
			<?php include('side.php'); ?>
		</div>
		<div class="col-sm-9 col admin-body">
			<form method="post" role="form" action="<?php echo $redis->get('site_url'); ?>/do/pubform-page.php">
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="publish-step-1">
							<div class="form-group">
								<label>
									标题
								</label>
								<input type="text" name="page[title]" class="form-control" value="<?php echo $redis->hget('post:page:'.$id,'title'); ?>">
							</div>
							<link href="<?php echo $redis->get('site_url'); ?>/public/sn/summernote.css" rel="stylesheet">
							<script src="<?php echo $redis->get('site_url'); ?>/public/sn/summernote.js"></script>
							<script src="<?php echo $redis->get('site_url'); ?>/public/sn/lang/summernote-zh-CN.min.js"></script>
							<div class="form-group">
								<label>
									内容
								</label>
								<div id="summernote"><?php if($redis->hget('post:page:'.$id,'content')) : echo $redis->hget('post:page:'.$id,'content'); endif; ?></div>
								<textarea name="page[content]" id="post-content-textarea" class="hidden"><?php if($redis->hget('post:page:'.$id,'content')) : echo $redis->hget('post:page:'.$id,'content'); endif; ?></textarea>
							</div>
							<div class="form-group">
								<label>
									排序
								</label>
								<input type="text" name="page[rank]" class="form-control" value="<?php echo $redis->hget('post:page:'.$id,'rank'); ?>">
                                <p class="help-block">数值越大，在页面侧边栏的排序就越靠前。0为不显示</p>
							</div>
							<button type="submit" class="btn btn-block btn-default">
								提交
							</button>
						</div>
					</div>
					<?php if($id) : ?>
					<input type="hidden" name="id" value="<?php echo $id; ?>">
					<?php endif; ?>
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
                            ['insert', ['link', 'picture','video']],
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
				</script>
</div>
<?php include('footer.php'); ?>
