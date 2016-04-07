var MyButton = React.createClass({
    getInitialState: function() {
        return {
            counter: 0
        }
    },
    handleClick: function () {
      this.setState({
          counter: this.state.counter + 1
      });
    },
    render: function () {
        return (
            <h1 onClick={this.handleClick}>Hello World + {this.state.counter}</h1>
        );
    }
});

//ReactDOM.render(<MyButton/>, document.getElementById("example"));