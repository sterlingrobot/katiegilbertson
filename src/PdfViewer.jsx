import React, { useMemo } from 'react';

import './PdfViewer.scss';

/** Extracts a Google Drive file ID from common link formats */
function extractGoogleDriveFileId(url) {
    const match = url.match(/\/d\/([a-zA-Z0-9_-]+)/) || url.match(/[?&]id=([a-zA-Z0-9_-]+)/);
    return match ? match[1] : null;
}

/** Detects whether the URL appears to be a Google Drive link */
function isGoogleDriveUrl(url) {
    return /drive\.google\.com/.test(url);
}

export function PdfViewer({ url, title, fallbackHeight }) {
    const { src, type } = useMemo(() => {
        if (isGoogleDriveUrl(url)) {
            const fileId = extractGoogleDriveFileId(url);
            if (!fileId) return { src: null, type: 'invalid' };
            return {
                src: `https://drive.google.com/file/d/${fileId}/preview`,
                type: 'google',
            };
        }

        const lowerUrl = url.toLowerCase();
        if (lowerUrl.endsWith('.pdf') || lowerUrl.includes('application/pdf')) {
            return { src: url, type: 'direct' };
        }

        return { src: null, type: 'invalid' };
    }, [url]);

    if (!src) {
        return <div>Invalid or unsupported PDF URL</div>;
    }

    return (
        <div className="pdf-viewer" style={{ minHeight: fallbackHeight || 400 }}>
            {type === 'google' ? (
                <iframe src={src} allow="autoplay" title={title} />
            ) : (
                <object data={src} type="application/pdf" title={title}>
                    <embed src={src} type="application/pdf" title={title} />
                    <p>
                        Your browser does not support viewing PDFs inline.{' '}
                        <a href={src} target="_blank" rel="noopener noreferrer">
                            Click here to download.
                        </a>
                    </p>
                </object>
            )}
        </div>
    );
}
