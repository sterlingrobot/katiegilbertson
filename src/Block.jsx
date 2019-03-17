import React from 'react';

function createMarkup(content) {
	 return { __html: decodeHtml(content) };
}

// api uses htmlentities
function decodeHtml(str) {
		const map =
		{
			'&amp;': '&',
			'&lt;': '<',
			'&gt;': '>',
			'&quot;': '"',
			'&#039;': "'"
		};
		return str && str.replace(new RegExp(Object.keys(map).join('|'), 'g'), (m) => map[m]);
}

const Block  = ({ content, classArr=[] }) =>
	<div
		className={ classArr.reduce((str, cls) => [str, cls].join(' '), '') }
		dangerouslySetInnerHTML={createMarkup(content)}
	>
	</div>

export default Block;
