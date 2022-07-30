import React, { Component } from 'react';

import Icon from './Icon.jsx';
import Button from './Button';

import contactImg from './images/profile-contact.jpg';

import './Contact.scss';

const ContactItem = ({ name, icon, url, content }) => (
    <li data-type={name}>
        <Button type="icon" size="lg" text={content} url={url} icons={[Icon[icon]]} />
    </li>
);

const ContactList = ({ contacts }) => (
    <ul className="contacts">
        {Object.keys(contacts).map((contact, i) => (
            <ContactItem key={i} name={contact} {...contacts[contact]} />
        ))}
    </ul>
);

class Contact extends Component {
    constructor(props) {
        super(props);
        this.state = {
            contacts: {},
        };
    }

    componentDidMount() {
        const host = /development/.test(process.env.NODE_ENV)
            ? // ? '//api.katie.local:8005'
              '//api.katiegilbertson.com'
            : '//api.katiegilbertson.com';

        fetch(host + '/contacts.json')
            .then((response) => response.json())
            .then((data) =>
                this.setState({
                    contacts: data,
                })
            );
    }

    render() {
        return (
            <section className="contact">
                <div className="contact-image" style={{ backgroundImage: `url(${contactImg})` }} />
                <div className="contact-content">
                    <p>
                        Need help finding your story?
                        <br />
                        Or do you already know it?
                        <br />I can help from story development through editing.
                    </p>
                    <h3>Contact Me</h3>
                </div>
                <ContactList contacts={this.state.contacts} />
            </section>
        );
    }
}

export default Contact;
