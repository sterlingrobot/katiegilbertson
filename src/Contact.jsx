import React from 'react';
import Icon from './Icon.jsx';

import Button from './Button';

import './Contact.scss';

const ContactItem = ({ name, icon, url, content }) =>
	<li data-type={name} >
		<Button type="icon" size="sm" text={content} url={url} icons={[Icon[icon]]} />
	</li>

const Contact = ({ contacts }) =>
	<ul className="contacts">
		{ Object.keys(contacts).map((contact, i) =>
			<ContactItem key={i} name={contact} { ...contacts[contact] } />
		)}
	</ul>

export default Contact;
