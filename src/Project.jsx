import React, { Component } from 'react';
import { Route, Link } from 'react-router-dom';

import Video from './Video.jsx';
import Award from './Award.jsx';
import Block from './Block.jsx';

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
				awards=[],
				blocks=[],
				subprojects=[],
				onClick
			}
		} = this;
		return (
			<article className={`project project-${view}`} onClick={onClick} >
				{ view === 'detail' ?
					<Link className="icn-close" to="/projects">
						<span>Close</span>
					</Link>
					: null
				}
				<div className="project-image" style={{ backgroundImage: `url(${image})` }}	></div>
					{ view === 'detail' && awards.length ?
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
				<header className="project-heading">
					<h4 className="project-name">
						<span className="project-role">{role}</span>
						{name}
					</h4>
					{ view === 'list' && awards.length ?
							<div className="project-awards">
								<Award provider={awards.length} />
							</div>
							: null
					}
					<span className="project-employer">{employer}</span>
					<span className="project-date">{date_completed}</span>
				</header>

				{ view === 'detail' ?

					<div className="project-content">
						{ video_link ?
							<div className="project-video">
								<Video title={name} src={video_link} img={image} />
							</div>
							: null
						}

						<Route render={ ({ history }) =>
							subprojects.length ?
								<div className="project-subprojects">
									{
										subprojects.map((project, i) =>
											<Link key={project.id}
												className="project-link"
												to={`/projects/${project.slug}`}
											>
												<Project
													view="list"
													{ ...project }
												/>
											</Link>
										)
									}
								</div>
								: null
							}
						/>

						<Block content={description} classArr={['project-description']} />
						{ blocks.length ?
								blocks.map(({ content }, i) =>
									<Block key={i} content={content} classArr={['project-block']} />
								)
								: null
						}
					</div>

					: null
				}
			</article>
		)
	}
}

export default Project;
