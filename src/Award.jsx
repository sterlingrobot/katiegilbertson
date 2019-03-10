import React from 'react';

import './Award.scss';
import defaultImg from './images/laurel-default.svg';

const Award = ({ provider, award, laurel_image }) =>
	<figure className="award" >
		<img src={laurel_image || defaultImg} alt={provider} />
		{provider ?
			<figcaption>
				<span className="award-provider">{provider}</span>
				{award ?
					<span className="award-name">{award}</span>
					: null
				}
			</figcaption>
			: null
		}
	</figure>

export default Award;
