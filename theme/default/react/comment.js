var BbsCommentList = React.createClass({
  handleCommentSubmit: function(comment) {
    this.props.handleCommentSubmit(comment);
    return;
  },
  commentDelete : function(id) {
	  this.props.commentDelete(id);
	  return;
  },
  render: function() {
    var commentNodes = [];
    for(var i=0; i<this.props.data.length; i++) {
	    var comment = this.props.data[i];
	    commentNodes.push(<BbsComment id={comment.id} uid={this.props.uid} ulevel={this.props.ulevel} userId={comment.userId} userLink={comment.userLink} userAvatar={comment.userAvatar} userName={comment.userName} time={comment.time} content={comment.content} child={comment.child} handleCommentSubmit={this.handleCommentSubmit} postId={this.props.postId} url={this.props.url} commentDelete={this.commentDelete} />);
    };
    return (
      <ul className="list-group mb-0">
        {commentNodes}
      </ul>
    );
  }
});
var BbsComment = React.createClass({
  handleReply : function(e) {
	  e.preventDefault();
	  this.setState({show: !this.state.show});
	  return;
  },
  handleDelete : function(e) {
	  e.preventDefault();
	  this.props.commentDelete(this.props.id);
	  return;
  },
  commentDelete : function(id) {
	  this.props.commentDelete(id);
	  return;
  },
  getInitialState: function() {
    return {show: false};
  },
  handleCommentSubmit: function(comment) {
    this.props.handleCommentSubmit(comment);
    this.setState({show: false});
    return;
  },
  render: function() {
  	if(this.state.show) {
	  	var BbsCommentFormShow = <BbsCommentForm postId={this.props.postId} parent={this.props.id} handleCommentSubmit={this.handleCommentSubmit} />;
	  	var ReplyText = '取消回复';
  	} else {
	  	var BbsCommentFormShow = <div />;
	  	var ReplyText = '回复';
  	};
  	if(this.props.ulevel==10 || this.props.uid==this.props.userId) {
	  	var ReplyBtn = <div><a href="#" className="btn-reply" onClick={this.handleReply}>{ReplyText}</a> <a href="#" className="btn-delete" onClick={this.handleDelete}>删除</a></div>;
  	} else if(this.props.uid>0) {
  		var ReplyBtn = <div><a href="#" className="btn-reply" onClick={this.handleReply}>{ReplyText}</a></div>;
  	} else {
		var ReplyBtn = <div />;
  	};
    return (
      <li className="list-group-item">
							<div className="media">
								<div className="media-left">
									<a href={this.props.userLink} className="img-div">
										<img className="media-object" src={this.props.userAvatar} />
									</a>
								</div>
								<div className="media-body">
									<h5 className="media-heading">
										<a href={this.props.userLink}>{this.props.userName}</a>
										<div className="pull-right time">
											{this.props.time}
										</div>
									</h5>
									<div className="content mb-10">
										<div dangerouslySetInnerHTML={{__html: this.props.content}} />
									</div>
									{ReplyBtn}
								</div>
							</div>
							<div className="clearfix"></div>
							<BbsCommentList uid={this.props.uid} ulevel={this.props.ulevel} data={this.props.child} url={this.props.url} handleCommentSubmit={this.handleCommentSubmit} commentDelete={this.commentDelete} />
							{BbsCommentFormShow}
						</li>
    );
  }
});
var BbsCommentForm = React.createClass({
  handleSubmit: function(e) {
    e.preventDefault();
    var text = this.refs.text.getDOMNode().value.trim();
    var pid = this.refs.pid.getDOMNode().value.trim();
    var parent = this.refs.parent.getDOMNode().value.trim();
    if (!text || !pid) {
      return;
    }
    this.props.handleCommentSubmit({pid: pid, text: text, parent: parent});
    this.refs.text.getDOMNode().value = '';
    return;
  },
  render: function() {
    return (
      <form onSubmit={this.handleSubmit}>
      	<input type="hidden" value={this.props.postId} ref="pid" />
      	<input type="hidden" value={this.props.parent} ref="parent" />
        <div className="form-group">
          <textarea rows="5" className="form-control" placeholder="" ref="text"></textarea>
        </div>
        <button type="submit" className="btn btn-warning">评论</button>
      </form>
    );
  }
});
var BbsCommentBox = React.createClass({
  loadCommentsFromServer: function() {
    $.ajax({
      url: this.props.url+'/do/'+this.props.type+'-comment.php',
      dataType: 'json',
      type: 'GET',
      data: {
	    pid: this.props.postId
      },
      success: function(data) {
        this.setState({data: data});
      }.bind(this),
      error: function(xhr, status, err) {
        console.error(this.props.url, status, err.toString());
        console.log(xhr);
      }.bind(this)
    });
  },
  handleCommentSubmit: function(comment) {
    /*
    var comments = this.state.data;
    var newComments = comments.concat([comment]);
    this.setState({data: newComments});
    */
    $.ajax({
      url: this.props.url+'/do/'+this.props.type+'-comment.php',
      dataType: 'json',
      type: 'POST',
      data: comment,
      success: function(data) {
        this.setState({data: data});
        console.log(data);
      }.bind(this),
      error: function(xhr, status, err) {
        console.error(this.props.url, status, err.toString());
        console.log(xhr);
      }.bind(this)
    });
  },
  commentDelete: function(id) {
	 $.ajax({
      url: this.props.url+'/do/'+this.props.type+'-comment.php',
      dataType: 'json',
      type: 'POST',
      data: {
	      del: id,
	      pid: this.props.postId
      },
      success: function(data) {
        this.setState({data: data});
        console.log(data);
      }.bind(this),
      error: function(xhr, status, err) {
        console.error(this.props.url, status, err.toString());
        console.log(xhr);
      }.bind(this)
    });
  },
  getInitialState: function() {
    return {data: []};
  },
  componentDidMount: function() {
    this.loadCommentsFromServer();
    setInterval(this.loadCommentsFromServer, this.props.pollInterval);
  },
  render: function() {
    if(this.props.uid) {
    	var BbsCommentFormShow = <BbsCommentForm postId={this.props.postId} handleCommentSubmit={this.handleCommentSubmit} />;
    } else {
	    var BbsCommentFormShow = <div className="nothing">请在 <a href={this.props.url+'?m=user&a=login'}>登录</a> 或 <a href={this.props.url+'?m=user&a=register'}>注册</a> 后发表评论！</div>;
    }
	return (
      <div className="panel panel-default bbsCommentBox">
        <div className="panel-heading">
        	<i className="glyphicon glyphicon-comment"></i> 发起评论
        </div>
        <div className="panel-body">
          	{BbsCommentFormShow}
        </div>
        <BbsCommentList uid={this.props.uid} ulevel={this.props.ulevel} postId={this.props.postId} data={this.state.data} handleCommentSubmit={this.handleCommentSubmit} url={this.props.url} commentDelete={this.commentDelete} />
      </div>
    );
  }
});
