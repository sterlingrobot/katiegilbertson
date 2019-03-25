import React from 'react';

import Button from './Button';
import Icon from './Icon';

import './Tags.scss';

const Tags = ({ tags=[], onClick }) =>
	<div className="tags-container">
		{ tags.map(({ tag }, i) =>
				<Button
					key={i}
					text={tag}
					activeClass='tag-active'
					type='tag'
					size='sm'
					icons={[Icon.CROSS]}
					url={`/projects/tags/${tag.replace(' ', '+')}`}
					onClick={onClick}
				/>
			)
		}
	</div>

	export default Tags;
