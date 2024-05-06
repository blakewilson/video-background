/**
 * Versions the FaustWP plugin.
 */

const fs = require("fs/promises");
const path = require("path");

const readFile = (filename) => fs.readFile(filename, { encoding: "utf8" });
const writeFile = fs.writeFile;

/**
 * Runs all WordPress plugin versioning operations for FaustWP
 * including version bumps and readme.txt changelog updates.
 */
async function versionPlugin() {
  const pluginPath = path.join(__dirname, "../");
  const pluginFile = path.join(pluginPath, "candide-vidbg.php");

  const version = await getNewVersion(pluginPath);

  if (version) {
    await bumpPluginHeader(pluginFile, version);
    await bumpStableTag(readmeTxt, version);
    await bumpVersionConstant(pluginFile, version);
  }
}

/**
 * Updates the version constant found in the WPGraphQLContentBlocks.php file.
 *
 * @param {String} pluginFile Full path to a file containing PHP constants.
 * @param {String} version    The new version number.
 */
async function bumpVersionConstant(pluginFile, version) {
  return bumpVersion(
    pluginFile,
    /^\s*define\(\s*'VIDBG_PLUGIN_VERSION', '([0-9.]+)/gm,
    version
  );
}

/**
 * Updates the version number found in the header comment of a given
 * WordPress plugin's main PHP file.
 *
 * @param {String} pluginFile Full path to a file containing a WordPress
 *                            plugin header comment.
 * @param {String} version    The new version number.
 */
async function bumpPluginHeader(pluginFile, version) {
  return bumpVersion(pluginFile, /^\s*\*\s*Version:\s*([0-9.]+)$/gm, version);
}

/**
 * Updates the stable tag found in a given WordPress plugin's readme.txt file.
 *
 * @param {String} pluginFile Full path to a file containing a WordPress
 *                            plugin header comment.
 * @param {String} version    The new version number.
 */
async function bumpStableTag(readmeTxt, version) {
  return bumpVersion(readmeTxt, /^Stable tag:\s*([0-9.]+)$/gm, version);
}

/**
 * Replaces the version number in the first line of a file matching the given
 * regular expression.
 *
 * Note that this function depends on a properly formatted regular expression.
 * The given regex should meet the following criteria:
 *
 *   1. Begins with ^ and ends with $ so that we can match an entire line.
 *   2. Contains one and only one capturing group that matches only the version
 *      number portion of the line. For example, in the line " * Version: 1.0.0"
 *      capturing group 1 of the regex must resolve to "1.0.0".
 *
 * @param {String} file    Full path to the file to update.
 * @param {RegExp} regex   A valid regular expression as noted above.
 * @param {String} version The new version number.
 */
async function bumpVersion(file, regex, version) {
  try {
    let data = await readFile(file);
    const matches = regex.exec(data);

    if (!matches) {
      throw new Error(`Version string does not exist in ${file}`);
    }

    // Replace the version number in the captured line.
    let versionString = matches[0].replace(matches[1], version);

    // Replace the captured line with the new version string.
    data = data.replace(matches[0], versionString);

    return writeFile(file, data);
  } catch (e) {
    console.warn(e);
  }
}

/**
 * Get the current version number from a plugin's package.json file.
 *
 * @param {String} pluginPath Full path to the directory containing the plugin's
 *                            package.json file.
 * @returns The version number string found in the plugin's package.json.
 */
async function getNewVersion(pluginPath) {
  const packageJsonFile = path.join(pluginPath, "package.json");

  try {
    let packageJson = await readFile(packageJsonFile);

    return JSON.parse(packageJson)?.version;
  } catch (e) {
    if (e instanceof SyntaxError) {
      e.message = `${e.message} in ${packageJsonFile}.\n`;
    }

    console.warn(e);
  }
}

versionPlugin();
