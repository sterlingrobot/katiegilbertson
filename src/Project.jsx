import React, { Component } from 'react';
import { Route, Link } from 'react-router-dom';

import Video from './Video.jsx';
import Award from './Award.jsx';

import './Project.scss';

class Project extends Component {

	constructor(props) {
		super(props);
		this.state = {
		};
	}

	render(props) {
		const {
			props: {
				view,
				name,
				employer,
				role,
				date_completed,
				image,
				description,
				video_link,
				awards,
				subprojects,
				onClick
			}
		} = this;
		return (
			<article className={`project project-${view}`} onClick={onClick} >
				<span className="icn-close"></span>
				<div className="project-image" style={{ backgroundImage: `url(${image})` }}	></div>
				<Route render={ () =>
					view === 'detail' && awards.length ?
						<div className="project-awards">
							{
								awards.map((award) =>
									<Award
										key={award.id}
										{...award}
									/>
								)
							}
						</div>
						: null
					}
				/>
				<header className="project-heading">
					<h4 className="project-name">
						<span className="project-role">{role}</span>
						{name}
					</h4>
					<Route render={ () =>
						view === 'list' && awards.length ?
							<div className="project-awards">
								<Award show	provider={awards.length} />
							</div>
							: null
						}
					/>
					<span className="project-employer">{employer}</span>
					<span className="project-date">{date_completed}</span>
				</header>
				{ view === 'detail' ?
					<div className="project-content">
						<div className="project-video">
							<Video title={name} src={video_link} img={image} />
						</div>
						<Route render={ ({ history }) =>
							subprojects.length ?
								<div className="project-subprojects">
									{
										subprojects.map((project, i) =>
											<Project
												key={project.id}
												view="list"
												awards={[]}
												onClick={ (e) => history.push(`/projects/${project.id}`) }
												{ ...project }
											/>
										)
									}
								</div>
								: null
							}
						/>
						<div className="project-description">{description}</div>
					</div>
					: null
				}
			</article>
		)
	}
}

export default Project;
