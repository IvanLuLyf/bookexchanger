<?php include('header.php'); ?>
<div class="container deal-page">
    <div class="row">
        <div class="col-sm-9 col deal-main">
            <div class="panel panel-default panel-deal-single">
                <div class="panel-heading">
                    <h1 class="title mt-0 mb-0"><?php echo $redis->hget('deal:'.$id,'title'); ?></h1>
                </div>
                <div class="panel-body">
                    <?php echo $redis->hget('deal:'.$id,'content'); ?>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="glyphicon glyphicon-tasks"></i> 项目进展
                    <?php if(maoo_user_id()==$author) : ?>
                    <a class="pull-right" href="#" data-toggle="modal" data-target="#updateModal">添加进展</a>
                    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">
                                            &times;
                                        </span>
                                    </button>
                                    <h4 class="modal-title">
                                        添加进展
                                    </h4>
                                </div>
                                <form method="post" action="<?php echo $redis->get('site_url'); ?>/do/dealupdate.php">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <div class="modal-body">
                                        <div class="form-group">
                                                <label>进展详情</label>
												<textarea class="form-control" rows="3" name="content"></textarea>
											</div>
											<div class="form-group mb-0">
												<label>附加图片</label>
												<div class="clearfix"></div>
												<div class="row cover-image">
													<?php $cover_image_num_array = array(1,2,3,4); ?>
													<?php foreach($cover_image_num_array as $cover_image_num) : ?>
													<div class="col-xs-3 col">
														<div class="cover-image-show mb-10" id="cover-image-show-<?php echo $cover_image_num; ?>">
															<div class="img-div">
																<img src="<?php echo $redis->get('site_url'); ?>/public/img/upload.jpg">
															</div>
														</div>
														<textarea id="cover-image-<?php echo $cover_image_num; ?>" class="hidden" name="images[<?php echo $cover_image_num; ?>]"></textarea>
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
													</div>
													<?php endforeach; ?>
												</div>
											</div>
                                        <script>
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
						};
						</script>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">
                                            取消
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            保存
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php 
                $page_size = $redis->get('page_size');
                if(empty($page_now) || $page_now<1) :
                    $page_now = 1;
                else :
                    $page_now = $_GET['page'];
                endif;
                $offset = ($page_now-1)*$page_size;
                $count = $redis->scard('deal:updatelist:'.$id);
                $db = $redis->sort('deal:updatelist:'.$id,array('sort'=>'desc','limit'=>array($offset,$page_size)));
                if($db) : ?>
                <ul class="list-group deal-update-list">
                        <?php foreach($db as $page) : $page_id = $page['id']; ?>
                        <li class="list-group-item">
                            <div class="media mb-0">
                                <div class="media-left media-middle">
                                    <a class="img-div" href="<?php echo maoo_url('user','index',array('id'=>$author)); ?>">
                                        <img class="media-object" src="<?php echo maoo_user_avatar($author); ?>" alt="<?php echo maoo_user_display_name($author); ?>">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading">
                                        <a href="<?php echo maoo_url('user','index',array('id'=>$author)); ?>"><?php echo maoo_user_display_name($author); ?></a> 更新于 <?php echo maoo_format_date($redis->hget('deal:update:'.$page_id,'date')); ?>
                                    </h4>
                                    <div class="mb-20"><?php echo $redis->hget('deal:update:'.$page_id,'content'); ?></div>
                                    <div class="row">
                                    <?php $update_images = unserialize($redis->hget('deal:update:'.$page_id,'images')); foreach($update_images as $update_image) : if($update_image) : ?>
                                        <div class="col-xs-3 col">
                                            <a href="#" class="img-div" data-toggle="modal" data-target="#updateimageModal">
                                                <img src="<?php echo $update_image; ?>">
                                            </a>
                                        </div>
                                    <?php endif; endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <div class="modal fade" id="updateimageModal" tabindex="-1" role="dialog">
						<div class="modal-dialog modal-lg" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">
											&times;
										</span>
									</button>
									<h4 class="modal-title">
										查看图片
									</h4>
								</div>
								<div class="modal-body">
									<div class="img-div">
										<img src="">
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">
										关闭
									</button>
								</div>
							</div>
						</div>
					</div>
                <script>
                    $('.deal-update-list .media-body .img-div').hover(function(){
							var src = $('img',this).attr('src');
							$('#updateimageModal img').attr('src',src);
						});
                </script>
                <?php else : ?>
                <div class="panel-body">
                    <div class="nothing">
                        此项目暂无新进展
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="glyphicon glyphicon-signal"></i> 支持记录
                </div>
                <?php 
                $page_size = $redis->get('page_size');
                if(empty($page_now) || $page_now<1) :
                    $page_now = 1;
                else :
                    $page_now = $_GET['page'];
                endif;
                $offset = ($page_now-1)*$page_size;
                $count = $redis->scard('deal:rewardlist:'.$id);
                $db = $redis->sort('deal:rewardlist:'.$id,array('sort'=>'desc','limit'=>array($offset,$page_size)));
                if($db) : ?>
                    <ul class="list-group deal-reward-list">
                        <?php foreach($db as $page_id) : $reward_user_id = $redis->hget('deal:reward:'.$page_id,'user_id'); ?>
                        <li class="list-group-item">
                            <div class="media mb-0">
                                <div class="media-left media-middle">
                                    <a class="img-div" href="<?php echo maoo_url('user','index',array('id'=>$reward_user_id)); ?>">
                                        <img class="media-object" src="<?php echo maoo_user_avatar($reward_user_id); ?>" alt="<?php echo maoo_user_display_name($reward_user_id); ?>">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading">
                                        <a href="<?php echo maoo_url('user','index',array('id'=>$reward_user_id)); ?>"><?php echo maoo_user_display_name($reward_user_id); ?></a> 于 <?php echo maoo_format_date($redis->hget('deal:reward:'.$page_id,'date')); ?> 支持 <?php echo $redis->hget('deal:reward:'.$page_id,'price'); ?> 元
                                    </h4>
                                    <div class="mb-0"><?php echo $redis->hget('deal:reward:'.$page_id,'somewords'); ?></div>
                                </div>
                            </div>
                            <?php if(maoo_user_id()==$author) : ?>
                            <div class="well mb-0 mt-20">
                                <p>回报内容：<?php echo $rewards[$redis->hget('deal:reward:'.$page_id,'rewardkey')]['content']; ?></p>
                                <p>收货地址：<?php echo $redis->hget('deal:reward:'.$page_id,'address'); ?></p>
                                订单号：<?php echo $redis->hget('deal:reward:'.$page_id,'out_trade_no'); ?>
                            </div>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                <div class="panel-body">
                    <div class="nothing">
                        还没有支持记录
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-sm-3 col">
            <div class="thumbnail text-center deal-author">
                <a class="img-div" href="<?php echo maoo_url('user','index',array('id'=>$author)); ?>">
                    <img class="media-object" src="<?php echo maoo_user_avatar($author); ?>" alt="<?php echo maoo_user_display_name($author); ?>">
                </a>
                <div class="caption">
                    <h4>
                        <a href="<?php echo maoo_url('user','index',array('id'=>$author)); ?>"><?php echo maoo_user_display_name($author); ?></a>
                    </h4>
                    发布于 <?php echo maoo_format_date($redis->hget('deal:'.$id,'date')); ?>
                </div>
            </div>
            <div class="panel panel-default deal-state">
                <?php if($status==2) : ?>
                <div class="panel-heading deal-state-2">
                    <i class="glyphicon glyphicon-stats"></i> 已达成
                </div>
                <?php elseif($status==3) : ?>
                <div class="panel-heading deal-state-3">
                    <i class="glyphicon glyphicon-stats"></i> 已结束
                </div>
                <?php else : ?>
                <div class="panel-heading">
                    <i class="glyphicon glyphicon-stats"></i> 进行中
                </div>
                <?php endif; ?>
                <div class="panel-body">
                    <div class="text-center total mb-10">
                        已筹集<span><?php echo $redis->hget('deal:'.$id,'total'); ?></span>元
                    </div>
                    <p>此项目必须在<span><?php echo date('Y年m月d日',$redis->hget('deal:'.$id,'deadline')); ?></span>前得到<span>￥<?php echo $redis->hget('deal:'.$id,'goal'); ?></span>的支持才可成功！剩余<span><?php echo maoo_deal_remain_day($id); ?></span>天!</p>
                    已达成<span><?php echo maoo_deal_percent($id); ?></span>%
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo maoo_deal_percent($id); ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo maoo_deal_percent($id); ?>%;"></div>
                    </div>
                    <div class="text-center">
                        已有<span><?php echo maoo_deal_reward_count($id); ?></span>名支持者
                    </div>
                </div>
            </div>
            <?php foreach($rewards as $rewardkey=>$reward) : ?>
            <div class="panel panel-default deal-reward">
                <div class="panel-heading">
                    支持 <?php echo $reward['price']; ?> 元
                    <?php if($reward['count']>0) : ?>
                    <span class="pull-right">
                        已支持<?php echo $reward['count']; ?>人  
                    </span>
                    <?php endif; ?>
                </div>
                <div class="panel-body">
                    <ul class="list-inline mb-10">
                        <li>限额 <?php echo $reward['number']; ?> 位</li>
                        <li>|</li>
                        <li>剩余 <?php echo $reward['number']-$reward['count']; ?> 位</li>
                    </ul>
                    <div class="mb-20"><?php echo $reward['content']; ?></div>
                    <?php if($reward['count']>=$reward['number'] || $status==3) : ?>
                    <a href="javascript:;" class="btn btn-dufault btn-lg">支持<?php echo $reward['price']; ?>元</a>
                    <?php else : ?>
                    <a href="<?php echo maoo_url('deal','reward',array('id'=>$id,'reward'=>$rewardkey)); ?>" class="btn btn-danger btn-lg">支持<?php echo $reward['price']; ?>元</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>
