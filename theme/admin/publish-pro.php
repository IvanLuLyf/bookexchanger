<?php include('header.php'); ?>
<div class="container admin">
	<div class="row">
		<div class="col-sm-3 col user-center-side">
			<?php include('side.php'); ?>
		</div>
		<div class="col-sm-9 col admin-body">
<link href="<?php echo $redis->get('site_url'); ?>/public/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<div class="editpro">
	<form method="post" action="<?php echo $redis->get('site_url'); ?>/do/pubform-pro.php">
		<input type="hidden" name="id" value="<?php echo $id; ?>">
		<div class="row">
			<div class="col-xs-8 col">
				<ul class="nav nav-tabs nav-justified mb-20" role="tablist">
					<li role="presentation" class="active">
						<a href="#editpro-1" aria-controls="editpro-1" role="tab" data-toggle="tab">
							基本信息
						</a>
					</li>
					<li role="presentation">
						<a href="#editpro-2" aria-controls="editpro-2" role="tab" data-toggle="tab">
							促销信息
						</a>
					</li>
					<li role="presentation">
						<a href="#editpro-3" aria-controls="editpro-3" role="tab" data-toggle="tab">
							文章推送
						</a>
					</li>
					<li role="presentation">
						<a href="#editpro-4" aria-controls="editpro-4" role="tab" data-toggle="tab">
							杂项
						</a>
					</li>
				</ul>
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane active" id="editpro-1">
						<div class="form-group">
							<label>
								名称
							</label>
							<input type="text" name="page[title]" class="form-control" value="<?php if($id) echo $redis->hget('pro:'.$id,'title'); ?>">
						</div>
						<div class="form-group">
							<label>
								简介
							</label>
							<textarea name="page[excerpt]" class="form-control" rows="3"><?php if($id) echo $redis->hget('pro:'.$id,'excerpt'); ?></textarea>
						</div>
						<link href="<?php echo $redis->get('site_url'); ?>/public/sn/summernote.css" rel="stylesheet">
						<script src="<?php echo $redis->get('site_url'); ?>/public/sn/summernote.js"></script>
						<script src="<?php echo $redis->get('site_url'); ?>/public/sn/lang/summernote-zh-CN.min.js"></script>
						<div class="form-group">
							<label>
								详情
							</label>
							<div id="summernote"><?php if($id) echo $redis->hget('pro:'.$id,'content'); ?></div>
							<textarea name="page[content]" id="post-content-textarea" class="hidden"><?php if($id) echo $redis->hget('pro:'.$id,'content'); ?></textarea>
						</div>
						<div class="form-group">
							<label>
								分类
							</label>
							<select name="page[term]" class="form-control">
								<option>
									选择分类...
								</option>
								<?php foreach($redis->zrange('term:pro',0,-1) as $title) : $term_id = $redis->zscore('term:pro',$title); ?>
								<option value="<?php echo $term_id; ?>" <?php if($redis->hget('pro:'.$id,'term')==$term_id) echo 'selected'; ?>>
									<?php echo $title; ?>
								</option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="form-group">
							<label>
								运费
							</label>
							<input type="text" name="page[express]" class="form-control" value="<?php if(is_numeric($redis->hget('pro:'.$id,'express'))) : echo $redis->hget('pro:'.$id,'express'); else : echo $redis->get('express'); endif; ?>">
						</div>
						<div class="form-group">
			                <label for="dtp_input1" class="control-label pull-left">下架时间</label>
			                <div class="input-group date form_datetime pull-left input" data-date="<?php echo date('Y-m-d',strtotime("now")); ?>T00:00:00Z" data-link-field="dtp_input1">
			                    <input class="form-control" size="16" type="text" value="<?php if($redis->hget('pro:'.$id,'deadline')) echo date('Y-m-d H:i',$redis->hget('pro:'.$id,'deadline')); ?>" readonly>
								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar icon-calendar"></span></span>
			                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove icon-remove-circle"></span></span>
			                </div>
							<input type="hidden" id="dtp_input1" value="<?php if($redis->hget('pro:'.$id,'deadline')) echo date('Y-m-d H:i',$redis->hget('pro:'.$id,'deadline')); ?>" name="page[deadline]">
							<div class="clearfix"></div>
			            </div>
						<div class="form-group">
							<label>
								参数
							</label>
							<div id="reward-list">
							<?php $parameters = unserialize($redis->hget('pro:'.$id,'parameter')); ?>
							<?php if($parameters) : ?>
							<?php foreach($parameters as $parameter_num=>$parameter) : $rewardnum++; if($parameter['price']>0) : ?>
							<div class="row mb-10">
								<div class="col-xs-6 col">
									<input type="text" class="form-control" name="page[parameter][<?php echo $parameter_num; ?>][name]" value="<?php echo $parameter['name']; ?>" placeholder="参数名称">
								</div>
								<div class="col-xs-3 col">
									<input type="text" class="form-control" name="page[parameter][<?php echo $parameter_num; ?>][price]" value="<?php echo $parameter['price']; ?>" placeholder="价格">
								</div>
								<div class="col-xs-3 col">
									<input type="text" class="form-control" name="page[parameter][<?php echo $parameter_num; ?>][stock]" value="<?php echo $parameter['stock']; ?>" placeholder="库存">
								</div>
							</div>
							<?php else : ?>
							<div class="row mb-10">
								<div class="col-xs-6 col">
									<input type="text" class="form-control" name="page[parameter][<?php echo $parameter_num; ?>][name]" value="" placeholder="参数名称">
								</div>
								<div class="col-xs-3 col">
									<input type="text" class="form-control" name="page[parameter][<?php echo $parameter_num; ?>][price]" value="" placeholder="价格">
								</div>
								<div class="col-xs-3 col">
									<input type="text" class="form-control" name="page[parameter][<?php echo $parameter_num; ?>][stock]" value="" placeholder="库存">
								</div>
							</div>
							<?php endif; endforeach; ?>
                            <?php endif; ?>
							<div class="row mb-10">
								<div class="col-xs-6 col">
									<input type="text" class="form-control" name="page[parameter][<?php echo $rewardnum+1; ?>][name]" value="" placeholder="参数名称">
								</div>
								<div class="col-xs-3 col">
									<input type="text" class="form-control" name="page[parameter][<?php echo $rewardnum+1; ?>][price]" value="" placeholder="价格">
								</div>
								<div class="col-xs-3 col">
									<input type="text" class="form-control" name="page[parameter][<?php echo $rewardnum+1; ?>][stock]" value="" placeholder="库存">
								</div>
							</div>
                            </div>
                            <button type="button" id="reward-btn" class="btn btn-default btn-block" num-data="<?php echo $rewardnum+1; ?>">更多</button>
                                <script>
                                    $('#reward-btn').click(function(){
                                        var num = $(this).attr('num-data')*1+1;
                                        $('#reward-list').append('<div class="row mb-10"><div class="col-xs-6 col"><input type="text" class="form-control" name="page[parameter]['+num+'][name]" value="" placeholder="参数名称"></div><div class="col-xs-3 col"><input type="text" class="form-control" name="page[parameter]['+num+'][price]" value="" placeholder="价格"></div><div class="col-xs-3 col"><input type="text" class="form-control" name="page[parameter]['+num+'][stock]" value="" placeholder="库存"></div></div>');
                                        $(this).attr('num-data',num);
                                    });
                                </script>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane" id="editpro-2">
						<div class="form-group">
							<label>
								购买获得积分 暂未开发完成
							</label>
							<input type="text" name="page[coins]" class="form-control" value="<?php echo $redis->hget('pro:'.$id,'coins'); ?>">
						</div>
						<div class="form-group">
							<label>
								推广返利
							</label>
							<input type="text" name="page[cash_back]" class="form-control" value="<?php echo $redis->hget('pro:'.$id,'cash_back'); ?>">
						</div>
						<div class="form-group">
							<label>
								折扣 暂未开发完成
							</label>
							<select class="form-control" name="page[sale_off]">
								<option>请选择...</option>
								<?php $sale_off_array = array(9,8,7,6,5,4,3,2,1); foreach($sale_off_array as $sale_off) : ?>
								<option value="<?php echo $sale_off; ?>" <?php if($sale_off==$redis->hget('pro:'.$id,'sale_off')) echo 'selected'; ?>>
									<?php echo $sale_off; ?>折
								</option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="form-group">
							<label for="dtp_input2" class="control-label pull-left">折扣到期时间</label>
			                <div class="input-group date form_datetime pull-left input" data-date="<?php echo date('Y-m-d',strtotime("now")); ?>T00:00:00Z" data-link-field="dtp_input2">
			                    <input class="form-control" size="16" type="text" value="<?php if($redis->hget('pro:'.$id,'sale_off_date')) echo date('Y-m-d H:i',$redis->hget('pro:'.$id,'sale_off_date')); ?>" readonly>
								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar icon-calendar"></span></span>
			                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove icon-remove-circle"></span></span>
			                </div>
							<input type="hidden" id="dtp_input2" value="<?php if($redis->hget('pro:'.$id,'sale_off_date')) echo date('Y-m-d H:i',$redis->hget('pro:'.$id,'sale_off_date')); ?>" name="page[sale_off_date]">
							<div class="clearfix"></div>
						</div>
						<!--div class="form-group">
							<label>
								套餐
							</label>
							<select class="form-control" name="pro[package]">
								<option>选择套餐...</option>
								<?php foreach($packages as $package) : ?>
								<option value="<?php echo $package['id']; ?>" <?php if($package['id']==$pro['package']) echo 'selected'; ?>>
									<?php echo $package['title']; ?> (<?php echo $package['sale_off']; ?>折)
								</option>
								<?php endforeach; ?>
							</select>
						</div-->
					</div>
					<div role="tabpanel" class="tab-pane" id="editpro-3">
						<div class="form-group">
							<label>
								推送到文章列表
							</label>
							<select name="page[post_term]" class="form-control">
								<option>
									选择分类...
								</option>
								<?php foreach($redis->zrange('term:post',0,-1) as $title) : $term_id = $redis->zscore('term:post',$title); ?>
								<option value="<?php echo $term_id; ?>" <?php if($redis->hget('pro:'.$id,'post_term')==$term_id) echo 'selected'; ?>>
									<?php echo $title; ?>
								</option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="form-group">
							<label>
								评级 数值越高排名越靠前
							</label>
							<input type="text" name="page[rank]" class="form-control" value="<?php if($id) : echo $redis->hget('pro:'.$id,'rank');  endif; ?>">
						</div>
					</div>
					<div role="tabpanel" class="tab-pane" id="editpro-4">
						<div class="form-group">
							<label>
								SEO - 商品关键词
							</label>
							<input type="text" name="page[keywords]" class="form-control" value="<?php echo $redis->hget('pro:'.$id,'keywords'); ?>">
						</div>
						<div class="form-group">
							<label>
								SEO - 商品描述
							</label>
							<textarea name="page[description]" class="form-control" rows="3"><?php echo $redis->hget('pro:'.$id,'description'); ?></textarea>
						</div>
						<div class="form-group">
							<label>
								排序权重
							</label>
							<input type="text" name="page[inlist]" class="form-control" value="<?php echo $redis->hget('pro:'.$id,'inlist'); ?>">
						</div>
					</div>
				</div>
				<button type="submit" class="btn btn-lg btn-block btn-default">
					提交
				</button>
			</div>
			<div class="col-xs-4 col">
				<div class="form-group cover-image">
					<label>
						封面图片
					</label>
					<?php $cover_images = unserialize($redis->hget('pro:'.$id,'cover_image')); ?>
					<?php if($cover_images) : ?>
					<?php foreach($cover_images as $cover_image_key=>$cover_image_val) : ?>

					<div class="cover-image-show mb-10" id="cover-image-show-<?php echo $cover_image_key; ?>">
						<div class="img-div">
							<img src="<?php if($cover_image_val) : echo $cover_image_val; else : echo $redis->get('site_url').'/public/img/upload.jpg'; endif; ?>">
						</div>
					</div>
					<textarea id="cover-image-<?php echo $cover_image_key; ?>" class="hidden" name="page[cover_image][<?php echo $cover_image_key; ?>]"><?php if($cover_image_val) : echo $cover_image_val; endif; ?></textarea>
					<div id="upload-button-row-<?php echo $cover_image_key; ?>" class="mb-20">
						<div class="row">
							<div class="col-xs-6 col col-1">
								<div class="pub-imgadd">
									<button type="button" class="btn btn-default btn-block">上传</button>
									<input type="file" class="picfile" onchange="readFile(this,<?php echo $cover_image_key; ?>)" />
								</div>
							</div>
							<div class="col-xs-6 col col-2">
								<input type="button" id="upload-button-del-<?php echo $cover_image_key; ?>" cover-image-data="<?php echo $cover_image_key; ?>" class="btn btn-default btn-block upload-button-del" value="删除">
							</div>
						</div>
					</div>
					<?php endforeach; ?>
					<?php else : ?>
					<?php $cover_image_num_array = array(1,2,3,4,5); ?>
					<?php foreach($cover_image_num_array as $cover_image_num) : ?>
					<div class="cover-image-show mb-10" id="cover-image-show-<?php echo $cover_image_num; ?>">
						<div class="img-div">
							<img src="<?php echo $redis->get('site_url'); ?>/public/img/upload.jpg">
						</div>
					</div>
					<textarea id="cover-image-<?php echo $cover_image_num; ?>" class="hidden" name="page[cover_image][<?php echo $cover_image_num; ?>]"></textarea>
					<div id="upload-button-row-<?php echo $cover_image_num; ?>" class="mb-20">
						<div class="row">
							<div class="col-xs-6 col col-1">
								<div class="pub-imgadd">
									<button type="button" class="btn btn-default btn-block">上传</button>
									<input type="file" class="picfile" onchange="readFile(this,<?php echo $cover_image_num; ?>)" />
								</div>
							</div>
							<div class="col-xs-6 col col-2">
								<input type="button" id="upload-button-del-<?php echo $cover_image_num; ?>" cover-image-data="<?php echo $cover_image_num; ?>" class="btn btn-default btn-block upload-button-del" value="删除">
							</div>
						</div>
					</div>
					<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</form>
</div>
		</div>
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
					$('.upload-button-del').click(function(){
						var num = $(this).attr('cover-image-data');
						$('#cover-image-show-'+num+' img').attr('src','<?php echo $redis->get('site_url'); ?>/public/img/upload.jpg');
						$('#cover-image-'+num).val('');
					});
						function readFile(obj,id){
									$('#cover-image-show-'+id+' img').attr('src','<?php echo $redis->get('site_url'); ?>/public/img/loading.gif');
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
											url: "<?php echo $redis->get('site_url'); ?>/do/imgupload.php",
											cache: false,
											contentType: false,
											processData: false,
											success: function(url) {
												$('#cover-image-show-'+id+' img').attr('src',url);
												$('#cover-image-'+id).html(url);
											},
											error : function(data) {
												alert('上传失败');
												$('#cover-image-show-'+id+' img').attr('src','<?php echo $redis->get('site_url'); ?>/public/img/upload.jpg');
											}
									});
					}
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
