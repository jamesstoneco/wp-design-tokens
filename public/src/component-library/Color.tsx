import React, { FormEvent, useState } from "react";
import { inject, observer } from "mobx-react";
import { Button, EButtonType } from "../wp/";
import { IColor as IColorType, IColorStore as IColorStoreType } from "../store";
import { __ } from "../util/i18n";

interface IColorItemProps {
    color: IColorType;
}

/**
 * Hooks example with useState. Currently MobX is still in use without useContext() and with inject().
 * That (useContext) is the usual way when going with only-hooks components. You have to rewrite this yourself
 * when you have decided what you take: Class components or hook components.
 * If you decide for hook components see https://mobx-react.js.org/recipes-context
 */
const ColorItem = observer(({ color }: { color: IColorType }) => {
    const [isBold, setBold] = useState(false);
    return (
        <li className={"color-patch"} style={{ backgroundColor: `#${color.text}` }}>
            #{color.text}
            &nbsp;
            <a href="#" onClick={() => color.remove()}>
                {__("Remove")}
            </a>
        </li>
    );
});

interface IColorProps {
    store?: IColorStoreType;
}

interface IColorState {
    inputText: string;
}

/**
 * Quoted from MobX home page:
 *
 * The observer function / decorator can be used to turn ReactJS components into reactive components.
 * It wraps the component's render function in mobx.autorun to make sure that any data that is used
 * during the rendering of a component forces a re-rendering upon change. It is available through the
 * separate mobx-react package.
 *
 * @see https://mobx.js.org/refguide/observer-component.html
 *
 * The mobx-react package also provides the Provider component that can be used to pass down stores using
 * React's context mechanism. To connect to those stores, pass a list of store names to inject, which will
 * make the stores available as props.
 * N.B. the syntax to inject stores has changed since mobx-react 4, always use inject(stores)(component) or @inject(stores) class Component.... Passing store names directly to observer is deprecated.
 *
 * @see https://mobx.js.org/refguide/observer-component.html#connect-components-to-provided-stores-using-inject
 */
@inject("store") // Inject the store from the <Provider>
@observer
class Color extends React.Component<IColorProps, IColorState> {
    constructor(props: IColorProps) {
        super(props);
        this.state = {
            inputText: ""
        };
    }

    public handleAdd = () => {
        this.props.store.addColor(this.state.inputText);
        this.setState({ inputText: "" });
    };

    public handleChange = (e: FormEvent) => {
        this.setState({ inputText: (e.target as HTMLInputElement).value });
    };

    public render() {
        const {
                store: { colors }
            } = this.props,
            { inputText } = this.state;

        return (
            <div className="wp-styleguide--buttons">
                <h2>
                    {__("Color Tokens")} ({colors.length})
                </h2>
                <p>{__("This section demonstrates a mobx-state-tree Color list (no peristence to server).")}</p>
                <input
                    value={inputText}
                    onChange={this.handleChange}
                    type="text"
                    className="regular-text"
                    placeholder={__("FF0000")}
                    maxLength={6}
                    max={6}
                />
                <Button type={EButtonType.Primary} disabled={!inputText.length} onClick={this.handleAdd}>
                    {__("Add Color")}
                </Button>
                <ul>
                    {colors.map((t) => (
                        <ColorItem key={t.id} color={t} />
                    ))}
                    {!colors.length && <li>No entries</li>}
                </ul>
            </div>
        );
    }
}

export { Color };
