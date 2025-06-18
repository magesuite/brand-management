import React, { Component } from 'react';

class Brand extends Component {
    render() {
        const {
            props : {
                item
            }
        } = this;

        return (
            <a href={item.url} title={item.name} key={item.url} onMouseDown={(e) => e.preventDefault()} >
                <dd className={item.row_class} role="option">
                    <span className="qs-option-name">{item.name}</span>
                </dd>
            </a>
        );
    }
}

export default Brand;
