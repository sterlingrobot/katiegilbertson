import React, { Component } from 'react';
import {
    BrowserRouter as Router,
    Route,
    Link,
    Routes,
    Outlet,
    useLocation,
    useNavigate,
    useParams,
} from 'react-router-dom';

import Nav from './Nav';
import Header from './Header';
import Icon from './Icon';
import Typeshow from './Typeshow';
import About from './About';
import Contact from './Contact';
import Tags from './Tags';
import Project from './Project';

import './App.scss';

const routes = [
    { name: 'Projects', url: '/projects' },
    { name: 'About', url: '/about' },
    { name: 'Contact', url: '/contact' },
];

// TODO: reenable scroll handling
const noScroll = () => (/projects\/(?!tags)\S+\/\S+$/.test(window.location.href) ? 'noscroll' : '');

class App extends Component {
    constructor(props) {
        super(props);
        this.state = {
            noscroll: noScroll(),
            projectsData: [],
            projectsTags: [],
        };
        this.onTagClick = this.onTagClick.bind(this);
    }

    componentDidMount() {
        const endpoint = /development/.test(process.env.NODE_ENV)
            ? // '//api.katie.local:8005'
              '//api.katiegilbertson.com'
            : '//api.katiegilbertson.com';

        fetch(endpoint)
            .then((response) => response.json())
            .then((data) =>
                this.setState({
                    projectsData: data.projects,
                    projectsTags: data.tags,
                })
            )
            .then(() => (document.getElementById('root').className = 'init'));

        return window.addEventListener('popstate', (e) => this.setState({ noscroll: noScroll() }));
    }

    onTagClick(e, navigate) {
        const tag = e.target.closest('a');
        return tag.classList.contains('tag-active') && navigate('/projects');
    }

    render() {
        const isLoading = !this.state.projectsData.length;
        const { onClick, onTagClick } = this;
        console.log(this.state.projectsData);
        const Projects = () => {
            const location = useLocation();
            const navigate = useNavigate();
            return (
                <div className="projects">
                    <Outlet />
                    <Tags tags={this.state.projectsTags} onClick={(e) => onTagClick.call(this, e, navigate)} />
                    {isLoading && (
                        <>
                            <Icon icon={Icon.SPINNER} size="lg" />
                            <Icon icon={Icon.SPINNER} size="lg" />
                        </>
                    )}

                    {this.state.projectsData.map((project, i) => {
                        // check for tag filter
                        const match = /\/tags\/(.*?)$/.exec(location.pathname),
                            tag = match && match[1].replace(/\+/, ' ');
                        if (tag && project.attributes.tags.indexOf(tag) < 0) {
                            return null;
                        }

                        return (project.id && !project.attributes.is_subproject) || (project.id && !!tag) ? (
                            project.attributes.has_page ? (
                                <Link
                                    key={project.id}
                                    className="project-link"
                                    to={`/projects/${project.attributes.slug}`}
                                >
                                    <Project
                                        view="list"
                                        awards={project.awards}
                                        {...project.attributes}
                                        onClick={onClick}
                                    />
                                </Link>
                            ) : (
                                <div key={project.id} className="project-link static">
                                    <Project
                                        view="list"
                                        awards={project.awards}
                                        {...project.attributes}
                                        onClick={onClick}
                                    />
                                </div>
                            )
                        ) : null;
                    })}
                </div>
            );
        };

        const ActiveProject = () => {
            const params = useParams();
            const slug = [params.name, params.role].join('/'),
                project =
                    this.state.projectsData.length &&
                    this.state.projectsData.filter((p) => p.attributes.slug === slug).shift();
            return project ? (
                <section className="project-wrap">
                    <Project view="detail" awards={project.awards} blocks={project.blocks} {...project.attributes} />
                </section>
            ) : null;
        };

        return (
            <Router>
                <main className="app">
                    <Nav routes={routes} />
                    <Header />
                    <section className={`app-content ${isLoading ? 'loading' : ''}`}>
                        <Routes>
                            <Route
                                exact
                                path="/"
                                element={
                                    <Typeshow className="services">
                                        <h6>Documentary Editing</h6>
                                        <h6>Narrative Editing</h6>
                                        <h6>Story Development</h6>
                                        <h6>Story Consulting</h6>
                                        <h6>Writing</h6>
                                    </Typeshow>
                                }
                            />
                            <Route path="/about" element={<About />} />
                            <Route path="/contact" element={<Contact />} />
                            <Route path="/projects" element={<Projects />}>
                                <Route path=":name/:role" element={<ActiveProject />} />
                            </Route>
                        </Routes>
                    </section>
                </main>
            </Router>
        );
    }
}

export default App;
