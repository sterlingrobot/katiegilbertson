import React from 'react';

const Header = ({ children }) =>
	<header className="app-header">
		<h1>Story Architech</h1>
		<h2>Katie Lose Gilbertson</h2>
		<h5>
			<span>Filmmaker</span>
			<span>Editor</span>
			<span>Story Consultant</span>
		</h5>
		{children}
		<h6>Bozeman, Montana</h6>
	</header>

	export default Header;
