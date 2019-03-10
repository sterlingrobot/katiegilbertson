import React, { Component } from 'react';
import { BrowserRouter as Router, Route, Link } from 'react-router-dom';
// import { withRouter } from 'react-router';

import Project from './Project';
import './constants.js';
import './App.scss';

class App extends Component {

	constructor(props) {
		super(props);
		this.state = {
			noscroll: '',
			projectsData: []
		};
		this.onClick = this.onClick.bind(this);
	}

	componentDidMount() {
		fetch(/development/.test(process.env.NODE_ENV) ? '//api.katie.local:8005' : '//api.katiegilbertson.com')
			.then(response => response.json())
			.then(data => this.setState({
				projectsData: data.sort((a, b) => a.id - b.id)
			}))
			.then(() => (document.getElementById('root').className = 'init'))
	}

	onClick(params) {
		const { e, id, history } = params;
		return (
			e.target.className === 'icn-close' ?
				!history.push('/projects') &&
				this.setState({ noscroll: '' }) :
				id ?
					!history.push(`/projects/${id}`) &&
					this.setState({ noscroll: 'noscroll' }) :
					null
		)
	}

	urlgenerate = (str) => {
		return str.trim()
						.replace(/~[^\w\d]+~/, '-')
						.replace(/-$/, '')
						.replace(/^-/, '')
						.toLowerCase();
	}

	render() {
		const {
			onClick
		} = this;
		return (
			<Router>
				<main className={ `app ${this.state.noscroll}` } >
					<header className="app-header">
						<nav className="app-nav">
							<ul>
								<li><Link to="/">Home</Link></li>
								<li><Link to="/projects">Projects</Link></li>
								<li><Link to="/about">About</Link></li>
								<li><Link to="/contact">Contact</Link></li>
							</ul>
						</nav>
						<h1>Story Architech</h1>
						<h2>Katie Lose Gilbertson</h2>
						<h5>
							<span>Filmmaker</span>
							<span>Editor</span>
							<span>Story Consultant</span>
						</h5>
						<h6>Bozeman, Montana</h6>
					</header>
					<section className="app-content">
						<div className="projects">
							<Route
								exact path="/projects/:name"
								render={ ({ match, history }) => {
									const name = match.params.name,
												project = this.state.projectsData.length &&
																	this.state.projectsData
																		.filter((p) => p.id === parseInt(name))
																		.shift();
									return ( project &&
										<Project
											view="detail"
											awards={project.awards}
											onClick={ (e) => onClick({ e: e, history: history }) }
											{ ...project.attributes }
										/> ) || null;
								}}
							/>
							<Route
								path="/projects"
								render={ ({ history }) =>
									this.state.projectsData.map((project, i) =>
										<Project
											key={project.id}
											view="list"
											awards={project.awards}
											onClick={ (e) => onClick({ e: e, id: project.id, history: history }) }
											{ ...project.attributes }
										/>
									)
								}
							/>
						</div>
					</section>
				</main>
			</Router>
		);
	}
}

export default App;
