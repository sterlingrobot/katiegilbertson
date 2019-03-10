import React from 'react';

import './Award.scss';
import defaultImg from './images/laurel-default.svg';

const Award = ({ provider, award, laurel_image, show }) =>
	<figure className={`award ${ show ? 'show' :'' }` }>
		<img src={laurel_image || defaultImg} alt={provider} />
		{provider ?
			<figcaption>
				<span className="award-provider">{provider}</span>
				<span className="award-name">{award}</span>
			</figcaption>
			: null
		}
	</figure>

export default Award;
