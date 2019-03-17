import React from 'react';
import { Link } from 'react-router-dom'; //BrowserRouter as Router, Route,

const Nav = ({ routes }) =>
	<nav className="app-nav">
		<ul>
			{ routes.map((route, i) =>
				<li key={i}><Link to={route.url}>{route.name}</Link></li>
			)}
		</ul>
	</nav>

export default Nav;
