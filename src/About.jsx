import React from 'react';

import './About.scss';
import aboutImg from './images/profile-about.jpg';

const About = () => (
    <section className="about">
        <div className="about-image" style={{ backgroundImage: `url(${aboutImg})` }} />
        <div className="about-content">
            <p>
                Katie Lose Gilbertson is an Emmy Award winning film and television editor. Her work has screened on
                Smithsonian Channel, Animal Planet, Disney, PBS, Independent Lens, in National Parks, and in numerous
                film festivals worldwide.
            </p>
            <p>
                Katie has 16 years experience crafting natural history films and documentaries. After receiving a
                Bachelor of Fine Arts Degree in Acting and Directing (Theatre/Dance) with a minor in Biology, she went
                on to a Master of Fine Arts in Science and Natural History Filmmaking where she discovered and honed her
                talents as a storyteller. The fusion of these artistic mediums weaves character, movement, emotional
                engagement, pacing, and story arc into compelling journeys. Katie is an Editor, a Director, and a
                Producer. She is a storyteller.
            </p>
        </div>
    </section>
);

export default About;
