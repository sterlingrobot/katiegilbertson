import React from 'react';
import { NavLink } from 'react-router-dom'; //BrowserRouter as Router, Route,

import './Nav.scss';

const Nav = ({ routes }) =>
	<nav className="app-nav">
		<ul>
			{ routes.map((route, i) =>
				<li key={i}><NavLink activeClassName="nav-active" to={route.url}>{route.name}</NavLink></li>
			)}
		</ul>
	</nav>

export default Nav;
