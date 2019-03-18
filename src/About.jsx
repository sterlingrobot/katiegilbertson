import React from 'react';

import './About.scss';
import aboutImg from './images/profile-about.jpg';

const About = () =>
	<section className="about">
		<div className="about-image" style={{ backgroundImage: `url(${aboutImg})` }} />
		<div className="about-content">
			<p>An award winning documentary filmmaker and editor based in beautiful Bozeman, Montana. Her work has screened on PBS, TERRA, Current TV, National Parks, and in numerous film festivals worldwide. Additionally, Stories of Trust is being used across the country to promote public engagement in climate change, and is a tool in ongoing litigation for a Climate Recovery Plan that is based on science and human rights, not political agenda.</p>
			<p>Katie holds an MFA in Science and Natural History Filmmaking, a BFA in Theatre, and a minor in biology. The fusion of these artistic mediums creates a complexity in her storytelling offering great attention to audience, emotional engagement, pacing, revelation, and story arc.</p>
		</div>
	</section>

export default About;
