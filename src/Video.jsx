import React, { Component } from 'react';

// import Form from './Form';
import Button from './Button';
import Icon from './Icon';

import './Video.scss';

const Overlay = ({ step, img, title, children }) =>
	<div className={`video-overlay ${step}`} style={{ backgroundImage: `url(${img})` }} >
		{children}
	</div>

class Video extends Component {

	static propTypes = {
	}

	constructor(props) {
		super(props);
		this.state = {
			step: 0
		};
		this.steps = [
			'video-gated-initial',
			'video-gated-request',
			'video-gated-pending',
			'video-gated-success'
		];
		this.providers = {
			default: {
				to: 'mailto:',
				subject: '?subject'
			},
			gmail: {
				to: 'https://mail.google.com/mail/?view=cm&fs=1&to=',
				subject: '&su'
			},
			yahoo: {
				to: 'https://compose.mail.yahoo.com/?to=',
				subject: '&subj'
			},
			outlook: {
				to: 'https://outlook.live.com/default.aspx?rru=compose&to=',
				subject: '&subject'
			}
		};
		this.generateUrl = this.generateUrl.bind(this);
	}

	generateUrl(provider) {
		return encodeURI([
			this.providers[provider].to,
			// yahoo is dumb
			provider === 'yahoo' ? 'katie@katiegilbertson.com' : 'Katie Lose Gilbertson <katie@katiegilbertson.com>',
			`${this.providers[provider].subject}=Video Access Request: ${this.props.title}`,
			`&body=Hello,\r\n\r\nI'd like the password view this video.`,
		].join(''));
	}

	render() {
		const {
			generateUrl,
			onLinkClick,
			props: { title, src, img, gated=0, width=1920, height=1080 }
		} = this;
		return (
			<div className="video-wrap">

				{ gated && this.steps[this.state.step] !== 'video-gated-success' ?

					<Overlay step={this.steps[this.state.step]} img={img} title={title}>

						<div className="gated-initial">
							<p>Sorry, this is not a public video.</p>
							<p>Please contact me to view.</p>
							<Button
								type="outline"
								size="sm"
								text="Request access"
								icons={[0, Icon.MAIL]}
								onClick={() => this.setState({step: 1})}
							/>
							<Button
								type="link"
								size="sm"
								text="I have a password"
								onClick={onLinkClick}
							/>
						</div>

						<div className="gated-request">
							<p>Pick an email method:</p>
							<Button
								type="icon"
								size="sm"
								text="Email"
								icons={[Icon.MAIL]}
								url={generateUrl('default')}
							/>
							<Button
								type="icon"
								size="sm"
								text="Gmail"
								icons={[Icon.GMAIL]}
								url={generateUrl('gmail')}
							/>
							<Button
								type="icon"
								size="sm"
								text="Yahoo"
								icons={[Icon.YAHOO]}
								url={generateUrl('yahoo')}
							/>
							<Button
								type="icon"
								size="sm"
								text="Outlook"
								icons={[Icon.OUTLOOK]}
								url={generateUrl('outlook')}
							/>
							<br />
							<Button
								type="link"
								size="sm"
								text="I have a password"
								onClick={ () => this.setState({ step: this.steps.length - 1 }) }
							/>
						</div>

					</Overlay>
					: null
				}

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
		);
	}
}

export default Video;
