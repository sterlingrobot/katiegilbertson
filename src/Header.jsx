import React from 'react';

import './Header.scss';

const Header = ({ children }) => (
    <header className="app-header">
        <h1 className="company-name">
            story <span>ARC</span>itech
        </h1>
        <h2>Katie Lose Gilbertson</h2>
        <h5>
            <span>Filmmaker</span>
            <span>Editor</span>
            <span>Story Consultant</span>
        </h5>
        {children}
        <h6>
            <span>Bozeman, Montana</span>
            <span>Louisville, Kentucky</span>
        </h6>
    </header>
);

export default Header;
