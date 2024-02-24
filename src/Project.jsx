import React from 'react';
import { Link } from 'react-router-dom';

import Video from './Video.jsx';
// import Slider from './Slider.jsx';
import Award from './Award.jsx';
import Block from './Block.jsx';

import './Project.scss';
import Button from './Button.jsx';
import Slider from './Slider.jsx';

function SubProjects({ subprojects, withHeading }) {
    return (
        <div className="project-subprojects">
            {withHeading && <h2>Related Work</h2>}
            {subprojects.map((project, i) => (
                <Link key={project.id} className="project-link" to={`/projects/${project.slug}`}>
                    <Project view="list" {...project} />
                </Link>
            ))}
        </div>
    );
}

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
        images = [],
        links = [],
        awards = [],
        blocks = [],
        subprojects = [],
        onClick,
    } = props;

    const hasContent = Boolean(description || blocks.length || links.length);
    const hasVideoOrSubprojects = Boolean((video_link && !is_gated) || (subprojects.length && !hasContent));

    return (
        <article className={`project project-${view}`} onClick={onClick}>
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

                {view === 'list' && awards.length ? (
                    <div className="project-awards">
                        <Award provider={awards.length} />
                    </div>
                ) : null}

                <span className="project-employer">
                    {employer}
                    {customer ? ' / ' + customer : ''}
                </span>
                <span className="project-customer"></span>
                <span className="project-date">{date_completed}</span>
            </header>

            {view === 'detail' ? (
                <div className={`project-content ${hasVideoOrSubprojects ? 'has-video' : 'no-video'}`}>
                    {awards.length ? (
                        <div className="project-awards">
                            {awards.map((award) => (
                                <Award key={award.id} {...award} />
                            ))}
                        </div>
                    ) : null}

                    <div className={hasVideoOrSubprojects ? 'project-video' : 'project-images'}>
                        {video_link ? (
                            <Video title={name} src={video_link} img={image} links={links} gated={is_gated} />
                        ) : subprojects.length ? (
                            <SubProjects subprojects={subprojects} />
                        ) : images.length ? (
                            <Slider images={images} />
                        ) : null}

                        {hasContent && video_link && subprojects.length ? (
                            <SubProjects withHeading subprojects={subprojects} />
                        ) : null}
                    </div>

                    {hasContent && (
                        <div className="project-description">
                            {links.map((link) => (
                                <Button key={link.url} type={link.type} text={link.text} url={link.url} />
                            ))}
                            <Block content={description} classArr={['project-block']} />
                            {blocks.length
                                ? blocks.map(({ content }, i) => (
                                      <Block key={i} content={content} classArr={['project-block']} />
                                  ))
                                : null}
                        </div>
                    )}
                </div>
            ) : null}
        </article>
    );
}

export default Project;
