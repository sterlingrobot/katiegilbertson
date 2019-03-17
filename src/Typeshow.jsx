import React, { Component } from 'react';

import './Typeshow.scss';

class Typeshow extends Component {

	constructor(props) {
		super(props);
		this.interval = null;
		this.state = {
			showIndex: 0
		}
	}

	componentDidMount() {
		this.interval = setInterval(() => {
			let index = this.state.showIndex + 1;
			index = index === this.props.children.length ? 0 : index;
			this.setState({ showIndex: index })
		}, 4500);
	}

	componentWillUnmount() {
		clearInterval(this.interval);
	}

	render() {
		const {
			props: { className, children }
		} = this;
		return (
			<div className={`typeshow ${className}`}>
				{ children.map((child, i) =>
					React.cloneElement(child, {
						key: i,
						className: this.state.showIndex === i ? 'show' : ''
					})
				)}
			</div>
		)
	}
}

export default Typeshow;
