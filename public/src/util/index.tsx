/**
 * See PHP file inc/general/Assets.class.php.
 */
interface IOpts {}

interface ICommonOpts {
    slug: string;
    textDomain: string;
    version: string;
    restUrl?: string;
    restRoot?: string;
    restQuery?: {};
    restNonce?: string;
    publicUrl?: string;
    others: IOpts;
}

const pluginOptions = (window as any).wprjssOpts as ICommonOpts,
    process = (window as any).process;

const untrailingslashit = (str: string): string =>
    str.endsWith("/") || str.endsWith("\\") ? untrailingslashit(str.slice(0, -1)) : str;
const trailingslashit = (str: string): string => untrailingslashit(str) + "/";

export { pluginOptions, process, trailingslashit, untrailingslashit };
export * from "./ajax";
