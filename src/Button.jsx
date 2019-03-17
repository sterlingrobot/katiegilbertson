import React from 'react';
import { Link } from 'react-router-dom';

import Icon from './Icon';

import './Button.scss';

const BtnLink = ({ url, children }) =>
	(url ?
		// external links cannot use Link
		/^https?:\/\//.test(url) ?
			<a href={url} target="_blank" rel="noopener noreferrer">{children}</a>
			: /^(mailto:|tel:)/.test(url) ?
				<a href={url}>{children}</a>
				: <Link to={url}>{children}</Link>
		: <div className="btn-inner">{children}</div>
	);

const Button = ({ text, type='default', size='md', url='', icons=[], onClick }) =>
	<div className={`btn btn-${type} btn-${size}`} onClick={onClick} >
		<BtnLink url={url}>
			{ icons[0] ?  <Icon icon={icons[0]} /> : null }
			<span className="btn-text">{text}</span>
			{ icons[1] ?  <Icon icon={icons[1]} /> : null }
		</BtnLink>
	</div>;

export default Button;
