import React from 'react';

import Button from './Button';
import Icon from './Icon';

import './Tags.scss';

const initialize = (e) => {
	const active = e.target.closest('.tag-active');
	return active && active.classList.add('initialized');
}

const Tags = ({ tags=[], onClick }) =>
	<div className="tags-container">
		{ tags.map(({ tag }, i) =>
				<Button
					key={i}
					text={tag}
					activeClass='tag-active'
					type='tag'
					size='sm'
					icons={[Icon.CROSS, Icon.CHECK]}
					url={`/projects/tags/${tag.replace(' ', '+')}`}
					onClick={onClick}
					onMouseEnter={initialize}
					onMouseLeave={initialize}
				/>
			)
		}
	</div>

	export default Tags;
