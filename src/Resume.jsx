import React from 'react';

import './Resume.scss';
import { PdfViewer } from './PdfViewer';

const Resume = () => (
    <section className="resume">
        <PdfViewer title="Resume" url="https://drive.google.com/file/d/1vsNK3FreoJMBuEs_l5qDItOykUWzuHHc/view?usp=sharing" />
    </section>
);

export default Resume;
