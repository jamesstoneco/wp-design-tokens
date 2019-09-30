import { types, getRoot, destroy, Instance } from "mobx-state-tree";

/**
 * A single list item within the [[ColorStore]].
 */
const Color = types
    .model({
        text: types.string,
        id: types.identifierNumber
    })
    .actions((self) => ({
        remove() {
            getRoot<IColorStore>(self).removeColor(self as IColor);
        }
    }));

export type IColor = Instance<typeof Color>;

/**
 * A color store which allows you to add and remove [[Color]] items.
 *
 * @see https://github.com/mobxjs/mobx-state-tree/tree/master/packages/mst-example-colormvc
 */
const ColorStore = types
    .model({
        colors: types.array(Color)
    })
    .actions((self) => ({
        /**
         * Add a [[Color]] item by passed text.
         *
         * @param text
         */
        addColor(text: string) {
            const id = self.colors.reduce((maxId, color) => Math.max(color.id, maxId), -1) + 1;
            self.colors.unshift({
                id,
                text
            });
        },

        removeColor(color: IColor) {
            destroy(color);
        }
    }));

export type IColorStore = Instance<typeof ColorStore>;

export { ColorStore, Color };
