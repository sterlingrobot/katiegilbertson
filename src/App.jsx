import React, { Component } from 'react';
import { BrowserRouter as Router, Route, Link } from 'react-router-dom';

import Nav from './Nav';
import Header from './Header';
import Typeshow from './Typeshow';
import About from './About';
import Contact from './Contact';
import Tags from './Tags';
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
			projectsData: [],
			projectsTags: []
		};
		this.onClick = this.onClick.bind(this);
		this.onTagClick = this.onTagClick.bind(this);
	}

	componentDidMount() {

		const endpoint = /development/.test(process.env.NODE_ENV) ?
			 '//api.katie.local:8005'
			// '//api.katiegilbertson.com'
			: '//api.katiegilbertson.com';

		fetch(endpoint)
			.then(response => response.json())
			.then(data => this.setState({
				projectsData: data.projects,
				projectsTags: data.tags
			}))
			.then(() => (document.getElementById('root').className = 'init'))
	}

	onClick(e) {
		return this.setState({
			noscroll: e.target.className === 'icn-close' ? '' : 'noscroll'
		});
	}

	onTagClick(e, history) {
		const tag = e.target.closest('a');
		return tag.className === 'tag-active' && history.push('/projects');
	}

	render() {
		const {
			onClick,
			onTagClick,
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
							render={ ({ location, history }) =>
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

								<Tags tags={this.state.projectsTags} onClick={ (e) => onTagClick.call(this, e, history) } />

								{ this.state.projectsData.map((project, i) => {

										// check for tag filter
										const match = /\/tags\/(.*?)$/.exec(location.pathname),
													tag =  match && match[1].replace(/\+/, ' ');
										if(tag && project.attributes.tags.indexOf(tag) < 0) {
											return null;
										}

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
