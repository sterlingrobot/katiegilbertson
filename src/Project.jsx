import React, { Component } from 'react';
import { BrowserRouter as Router, Route } from 'react-router-dom';

import Video from './Video.jsx';
import Award from './Award.jsx';

import './Project.scss';

class Project extends Component {

	constructor(props) {
		super(props);
		this.state = {
			awardIndex: 0
		}
	}

	onTimer() {
		let next = this.state.awardIndex + 1;
		next = next > this.props.awards.length - 1 ? 0 : next;
		this.setState({ awardIndex: next });
	}

	componentDidMount() {
		this.intervalId = setInterval(this.onTimer.bind(this), Math.ceil(Math.random() * (6400 - 4800) + 4800));
	}
	componentWillUnmount(){
		clearInterval(this.intervalId);
	}

	render(props) {
		const {
			props: { view, name, employer, role, date_completed, image, description, video_link, awards, onClick }
		} = this;
		return (
			<article className={`project project-${view}`} onClick={onClick} >
				<span className="icn-close"></span>
				<div className="project-image" style={{ backgroundImage: `url(${image})` }}	></div>
				<Route render={ () =>
					view === 'detail' && awards.length ?
						<div className="project-awards">
							{
								awards.map((award, i) =>
									<Award
										show={ this.state.awardIndex === i }
										key={award.id}
										{...award}
									/>
								)
							}
						</div>
						: null
					}
				/>
				<Route render={ () =>
					view === 'list' && awards.length ?
						<div className="project-awards">
							<Award show	/>
						</div>
						: null
					}
				/>
				<header className="project-heading">
					<h4 className="project-name">
						<span className="project-role">{role}</span>
						{name}
					</h4>
					<span className="project-employer">{employer}</span>
					<span className="project-date">{date_completed}</span>
				</header>
				<div className="project-content">
					<div className="project-video">
						<Video title={name} src={video_link} img={image} />
					</div>
					<div className="project-description">{description}</div>
				</div>
			</article>
		)
	}
}

export default Project;
