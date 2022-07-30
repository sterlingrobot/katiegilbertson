import React from 'react';
import { NavLink } from 'react-router-dom';

import './Nav.scss';

const Nav = ({ routes }) => (
    <nav className="app-nav">
        <ul>
            {routes.map((route, i) => (
                <li key={i}>
                    <NavLink className={({ isActive }) => (isActive ? 'nav-active' : undefined)} to={route.url}>
                        {route.name}
                    </NavLink>
                </li>
            ))}
        </ul>
    </nav>
);

export default Nav;
