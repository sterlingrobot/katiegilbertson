import React from 'react';

const Form = ({ action, method='GET', children }) =>
	<form action={action} method={method}>
		{children}
	</form>

export default Form;
