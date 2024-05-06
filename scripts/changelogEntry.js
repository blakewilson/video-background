const { getReleaseLine: changesetGetReleaseLine, getDependencyReleaseLine } =
  require("@changesets/cli/changelog").default;

async function getReleaseLine(changeset, type, options) {
  // Remove commit from release line since this will be published and distributed.
  const modifiedChangeset = { ...changeset, commit: undefined };
  return changesetGetReleaseLine(modifiedChangeset, type, options);
}

module.exports = {
  getReleaseLine,
  getDependencyReleaseLine,
};
