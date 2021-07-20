import React from 'react';

import './About.scss';
import aboutImg from './images/profile-about.jpg';

const About = () =>
	<section className="about">
		<div className="about-image" style={{ backgroundImage: `url(${aboutImg})` }} />
		<div className="about-content">
			<p>Katie is an Emmy Award winning documentary and television editor based in Bozeman, MT. Her work has screened on Smithsonian Channel, Animal Planet, PBS, Independent Lens, in National Parks, and in numerous film festivals worldwide.</p>
			<p>She holds an MFA in Science and Natural History Filmmaking and a BFA in theatre/dance. The fusion of these artistic mediums weaves character, movement, emotional engagement, pacing, and story arc into compelling journeys.</p>
		</div>
	</section>

export default About;
