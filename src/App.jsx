import React, { Component } from 'react';
// import { BrowserRouter as Router, Route,  Switch, Link } from 'react-router-dom';
//
import Projects from './Projects';
// import projectsData from './data/projects.json';
import './App.scss';

class App extends Component {

	constructor(props) {
		super(props);
		this.state = {
			init: false,
			projectsData: []
		};
	}

	componentDidMount() {
		fetch('//api.katie.local:8005')
			.then(response => response.json())
			.then(data => this.setState({ projectsData: data }))
	}

	render() {
		return (
			<main className="app">
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
				<section className="app-content">
					<Projects projects={this.state.projectsData} />
				</section>
			</main>
		);
	}
}

export default App;

