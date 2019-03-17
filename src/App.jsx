import React, { Component } from 'react';
import { BrowserRouter as Router, Route, Link } from 'react-router-dom';

import Project from './Project';
import './App.scss';

class App extends Component {

	constructor(props) {
		super(props);
		this.state = {
			noscroll: /projects\/\S+\/\S+$/.test(window.location.href) ? 'noscroll' : '',
			projectsData: []
		};
		this.onClick = this.onClick.bind(this);
	}

	componentDidMount() {
		fetch(/development/.test(process.env.NODE_ENV) ? '//api.katie.local:8005' : '//api.katiegilbertson.com')
			.then(response => response.json())
			.then(data => this.setState({
				projectsData: [...data.sort((a, b) => a.id - b.id)]
			}))
			.then(() => (document.getElementById('root').className = 'init'))
	}

	onClick(e) {
		return this.setState({
			noscroll: e.target.className === 'icn-close' ?
								'' : 'noscroll'
		});
	}

	render() {
		const {
			onClick
		} = this;
		return (
			<Router>
				<main className={ `app ${this.state.noscroll}` } >

					<nav className="app-nav">
						<ul>
							<li><Link to="/">Home</Link></li>
							<li><Link to="/projects">Projects</Link></li>
							<li><Link to="/about">About</Link></li>
							<li><Link to="/contact">Contact</Link></li>
						</ul>
					</nav>

					<Route
						exact path="/"
						render={ () =>
							<header className="app-header">
								<h1>Story Architech</h1>
								<h2>Katie Lose Gilbertson</h2>
								<h5>
									<span>Filmmaker</span>
									<span>Editor</span>
									<span>Story Consultant</span>
								</h5>
								<h6>Bozeman, Montana</h6>
							</header>
						}
					/>

					<section className="app-content">
						<Route
							path="/projects"
							render={ ({ history }) =>
								<div className="projects">

									<Route
										exact path="/projects/:name/:role"
										render={ ({ match, history }) => {
											const slug = [match.params.name, match.params.role].join('/'),
														project = this.state.projectsData.length &&
																			this.state.projectsData
																				.filter((p) => p.attributes.slug === slug)
																				.shift();
											return project ?
												<section className="project-wrap">
													<Project
														view="detail"
														awards={project.awards}
														blocks={project.blocks}
														{ ...project.attributes }
														onClick={onClick}
													/>
												</section>
												: null;
										}}
									/>

								{ this.state.projectsData.map((project, i) => {
										return project.id && !project.attributes.is_subproject ?
											<Link key={project.id}
												className="project-link"
												to={`/projects/${project.attributes.slug}`}
											>
												<Project
													view="list"
													awards={project.awards}
													{ ...project.attributes }
													onClick={onClick}
												/>
											</Link>
											: null
									})
								}

								</div>
							}
						/>
					</section>
				</main>
			</Router>
		);
	}
}

export default App;
