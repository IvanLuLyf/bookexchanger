var ChatList = React.createClass({
  render: function() {
    var commentNodes = this.props.data.map(function (comment) {
      return (
        <Chat userLink={comment.userLink} userAvatar={comment.userAvatar} userName={comment.userName} time={comment.time}>
          {comment.content}
        </Chat>
      );
    });
    return (
      <ul className="list-group">
        {commentNodes}
      </ul>
    );
  }
});
var Chat = React.createClass({
  render: function() {
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
										<a href={this.props.userLink}>{this.props.userName}</a> 说：
										<div className="pull-right time">
											{this.props.time}
										</div>
									</h5>
									<div dangerouslySetInnerHTML={{__html: this.props.children}} />
								</div>
							</div>
						</li>
    );
  }
});
var ChatForm = React.createClass({
  handleSubmit: function(e) {
    e.preventDefault();
    var text = this.refs.text.getDOMNode().value.trim();
    if (!text) {
      return;
    }
    this.props.onCommentSubmit({text: text});
    this.refs.text.getDOMNode().value = '';
    return;
  },
  render: function() {
    return (
      <form onSubmit={this.handleSubmit}>
        <div className="form-group">
          <input className="form-control" placeholder="说点什么吧～" ref="text" />
        </div>
        <button type="submit" className="btn btn-primary">发送</button>
      </form>
    );
  }
});
var ChatBox = React.createClass({
  loadCommentsFromServer: function() {
    $.ajax({
      url: this.props.url,
      dataType: 'json',
      type: 'GET',
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
    var comments = this.state.data;
    var newComments = comments.concat([comment]);
    this.setState({data: newComments});
    $.ajax({
      url: this.props.url,
      dataType: 'json',
      type: 'POST',
      data: comment,
      success: function(data) {
        this.setState({data: data});
      }.bind(this),
      error: function(xhr, status, err) {
        console.error(this.props.url, status, err.toString());
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
    return (
      <div className="panel panel-default chatBox">
      	<div className="panel-heading">
      		<i className="glyphicon glyphicon-volume-up"></i> 快速留言
      	</div>
        <div className="panel-body">
          <ChatForm onCommentSubmit={this.handleCommentSubmit} />
        </div>
        <ChatList data={this.state.data} />
      </div>
    );
  }
});