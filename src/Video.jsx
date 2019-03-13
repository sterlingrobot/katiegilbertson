import React from 'react';

import './Video.scss';

const Video = ({ title, src, img, width=1920, height=1080 }) =>
		<div className="video-wrap">
			<iframe
				src={src}
				title={title}
				width={width}
				height={height}
				border="0"
				poster={img}
				controls>
				<p>Sorry, we're not able to load the video.</p>
				<p>You can follow <a href={src}>this link</a> instead to view.</p>
			</iframe>
		</div>

export default Video;
