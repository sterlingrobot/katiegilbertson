import React from 'react';
import { Link } from 'react-router-dom';

import Video from './Video.jsx';
// import Slider from './Slider.jsx';
import Award from './Award.jsx';
import Block from './Block.jsx';

import './Project.scss';

function Project(props) {
    const {
        view,
        name,
        subtitle,
        employer,
        customer,
        role,
        date_completed,
        image,
        description,
        video_link,
        is_gated,
        is_subproject,
        // images = [],
        links = [],
        awards = [],
        blocks = [],
        subprojects = [],
        onClick,
    } = props;

    const descriptionRef = React.useRef(null);

    const handleTransitionEnd = (event) => {
        if (event.propertyName === 'height') {
            descriptionRef.current?.style.removeProperty('--fullHeight');
        }
    };

    React.useEffect(() => {
        const { current } = descriptionRef;
        function calculateFullHeight() {
            if (current) {
                const { scrollHeight } = current;
                current.style.setProperty('--fullHeight', `${scrollHeight}px`);
                current.addEventListener('mouseleave', calculateFullHeight);
            }
        }

        window.addEventListener('resize', calculateFullHeight);
        calculateFullHeight();

        return () => {
            current?.removeEventListener('mouseleave', calculateFullHeight);
            window.removeEventListener('resize', calculateFullHeight);
        };
    }, []);

    return (
        <article className={`project project-${view} ${Boolean(description) ? 'with-description' : ''}`} onClick={onClick}>
            {view === 'detail' ? (
                <Link className="icn-close" to="/projects">
                    <span>Close</span>
                </Link>
            ) : null}

            <div className="project-image" style={{ backgroundImage: `url(${image})` }}></div>

            <header className="project-heading">
                {view === 'detail' && is_subproject ? (
                    <Link className="icn-back" to={`/projects/${is_subproject}`}>
                        <span>Back</span>
                    </Link>
                ) : null}

                <h4 className="project-name">
                    <span className="project-role">{role}</span>
                    {name}
                    {view === 'detail' ? <span className="project-subtitle">{subtitle}</span> : null}
                </h4>

                <div className="project-awards">
                    {view === 'list' && awards.length ? <Award provider={awards.length} /> : null}
                </div>

                <span className="project-employer">
                    {employer}
                    {customer ? ' / ' + customer : ''}
                </span>
                <span className="project-date">{date_completed}</span>

                <Block
                    content={description}
                    classArr={['project-description']}
                    ref={descriptionRef}
                    onTransitionEnd={handleTransitionEnd}
                />
            </header>

            {view === 'detail' ? (
                <div className="project-content">
                    {awards.length ? (
                        <div className="project-awards">
                            {awards.map((award) => (
                                <Award key={award.id} {...award} />
                            ))}
                        </div>
                    ) : null}

                    {
                        // video_link ?
                        <div className="project-video">
                            <Video title={name} src={video_link} img={image} links={links} gated={is_gated} />
                        </div>

                        // : images.length ?
                        // 	<div className="project-images">
                        // 		<Slider images={images} />
                        // 	</div>

                        // : null
                    }

                    {subprojects.length ? (
                        <div className="project-subprojects">
                            <h2>Related Work</h2>
                            {subprojects.map((project, i) => (
                                <Link key={project.id} className="project-link" to={`/projects/${project.slug}`}>
                                    <Project view="list" {...project} />
                                </Link>
                            ))}
                        </div>
                    ) : null}

                    <Block content={description} classArr={['project-description']} />
                    {blocks.length
                        ? blocks.map(({ content }, i) => (
                              <Block key={i} content={content} classArr={['project-block']} />
                          ))
                        : null}
                </div>
            ) : null}
        </article>
    );
}

export default Project;
