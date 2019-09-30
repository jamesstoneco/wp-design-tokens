import React from "react";
import { __ } from "../util/i18n";
import "./style.scss";

const Widget: React.FunctionComponent<{}> = () => (
    <div className="react-boilerplate-widget">
        <h3>{__("WP Design Tokens")}</h3>
        <p>{__("This is something I will replace later")}</p>
    </div>
);

export { Widget };
