import React from 'react';

import './Slider.scss';

const Slider = ({ images }) => (
    <div className={`images-slider ${images.length < 2 ? 'noscroll' : ''}`}>
        {images.map((img, i) => (
            <img key={i} src={img} alt="" />
        ))}
    </div>
);

export default Slider;
