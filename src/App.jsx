import React, { Component } from 'react';
import { BrowserRouter as Router, Route, Link } from 'react-router-dom';

import Nav from './Nav';
import Header from './Header';
import Typeshow from './Typeshow';
import About from './About';
import Contact from './Contact';
import Project from './Project';

import './App.scss';

const routes = [
	{ name: 'Projects', url: '/projects' },
	{ name: 'About', url: '/about' },
	{ name: 'Contact', url: '/contact' }
];

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
		const endpoint = /development/.test(process.env.NODE_ENV) ?
			'//api.katiegilbertson.com' //'//api.katie.local:8005'
			: '//api.katiegilbertson.com';
		fetch(endpoint)
			.then(response => response.json())
			.then(data => this.setState({
				projectsData: data //[...data.sort((a, b) => a.id - b.id)]
			}))
			.then(() => (document.getElementById('root').className = 'init'))
	}

	onClick(e) {
		return this.setState({
			noscroll: e.target.className === 'icn-close' ? '' : 'noscroll'
		});
	}

	render() {
		const {
			onClick
		} = this;
		return (
			<Router>
				<main className={ `app ${this.state.noscroll}` } >

					<Nav routes={routes} />
					<Header />

					<section className="app-content">

						<Route exact path="/" render={ () =>
							<Typeshow className="services">
								<h6>Documentary Editing</h6>
								<h6>Narrative Editing</h6>
								<h6>Story Development</h6>
								<h6>Story Consulting</h6>
								<h6>Writing</h6>
							</Typeshow>
						}/>
						<Route exact path="/about" component={About} />
						<Route exact path="/contact" component={Contact} />

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
