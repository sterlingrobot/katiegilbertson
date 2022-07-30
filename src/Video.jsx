import React from 'react';

// import Form from './Form';
import Button from './Button';
import Icon from './Icon';

import './Video.scss';

const Overlay = ({ step, img, children }) => (
    <div className={`video-overlay ${step}`} style={{ backgroundImage: `url(${img})` }}>
        {children}
    </div>
);

function Video({ title, src, img, links = [], gated = 0, width = 1920, height = 1080 }) {
    const [step, setStep] = React.useState(0);
    const [loading, setLoading] = React.useState(true);

    const steps = ['video-gated-initial', 'video-gated-request', 'video-gated-pending', 'video-gated-success'];
    const providers = {
        default: {
            to: 'mailto:',
            subject: '?subject',
        },
        gmail: {
            to: 'https://mail.google.com/mail/?view=cm&fs=1&to=',
            subject: '&su',
        },
        yahoo: {
            to: 'https://compose.mail.yahoo.com/?to=',
            subject: '&subj',
        },
        outlook: {
            to: 'https://outlook.live.com/default.aspx?rru=compose&to=',
            subject: '&subject',
        },
    };

    const generateUrl = (provider) => {
        return encodeURI(
            [
                providers[provider].to,
                // yahoo is dumb
                provider === 'yahoo'
                    ? 'katie@katiegilbertson.com'
                    : 'Katie Lose Gilbertson <katie@katiegilbertson.com>',
                `${providers[provider].subject}=Video Access Request: ${title}`,
                `&body=Hello,\r\n\r\nI'd like the password to view this video.`,
            ].join('')
        );
    };

    return (
        <div className={`video-wrap ${!loading ? 'ready' : ''}`}>
            {loading && (
                <Overlay img={img} step="loading">
                    <Icon icon={Icon.SPINNER} size="lg" />
                    <Icon icon={Icon.SPINNER} size="lg" />
                </Overlay>
            )}

            {gated && steps[step] !== 'video-gated-success' ? (
                <Overlay step={steps[step]} img={img} title={title}>
                    <div className="gated-initial">
                        <p>Sorry, this is not a public video.</p>
                        <p>Please contact me to view.</p>
                        <Button
                            type="outline"
                            size="sm"
                            text="Request access"
                            icons={[0, Icon.MAIL]}
                            onClick={() => setStep(1)}
                        />
                        <Button
                            type="link"
                            size="sm"
                            text="I have a password"
                            onClick={() => setStep(steps.length - 1)}
                        />
                    </div>

                    <div className="gated-request">
                        <p>Pick an email method:</p>
                        <Button type="icon" size="sm" text="Email" icons={[Icon.MAIL]} url={generateUrl('default')} />
                        <Button type="icon" size="sm" text="Gmail" icons={[Icon.GMAIL]} url={generateUrl('gmail')} />
                        <Button type="icon" size="sm" text="Yahoo" icons={[Icon.YAHOO]} url={generateUrl('yahoo')} />
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
                            onClick={() => setStep(steps.length - 1)}
                        />
                    </div>
                </Overlay>
            ) : null}

            {src ? (
                <iframe
                    src={src}
                    title={title}
                    width={width}
                    height={height}
                    border="0"
                    poster={img}
                    mozallowfullscreen="true"
                    webkitallowfullscreen="true"
                    allowFullScreen
                    allow="fullscreen"
                    onLoad={() => setLoading(false)}
                    controls
                >
                    <p>Sorry, we're not able to load the video.</p>
                    <p>
                        You can follow <a href={src}>this link</a> instead to view.
                    </p>
                </iframe>
            ) : (
                <Overlay img={img} step="success">
                    {links.map((link) => (
                        <Button key={link.url} type={link.type} text={link.text} url={link.url} />
                    ))}
                </Overlay>
            )}
        </div>
    );
}

export default Video;
