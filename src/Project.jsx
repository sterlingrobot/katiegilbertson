import React, { Component } from 'react';

import './Projects.scss';

class Project extends Component {

	constructor(props) {
		super(props);
		this.state = {
			isPlaying: false
		}
	}

	render(props) {
		const {
			props: { name, employer, role, date_completed, image, description, video_link }
		} = this;
		return (
			<article className="project">
				<div className="project-image">
					<img src={image} alt={name} />
				</div>
				<h4>{name}</h4>
				<span className="project-employer">{employer}</span>
				<span className="project-role">{role}</span>
				<span className="project-date">{date_completed}</span>
				<div className="project-description">{description}</div>
				<div className="project-video">
					{video_link}
				</div>
			</article>
		)
	}
}

export default Project;
