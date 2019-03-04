import React, { Component } from 'react';
import Project from './Project';

class Projects extends Component {

	constructor(props) {
		super(props);
		this.state = {
			activeProject: false
		}
	}

	render(props) {
		const {
			props: { projects }
		} = this;
		return (
			<div className="projects">
				{
					projects.map((project, i) =>
						<Project key={i} { ...project.attributes } />
					)
				}
			</div>
		)
	}
}

export default Projects;
