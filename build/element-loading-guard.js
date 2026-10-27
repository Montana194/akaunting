// Element UI ships precompiled code, so patch the module consumed by webpack.
// Keep this scoped to the loading directive, without modifying node_modules.
module.exports = function elementLoadingGuard(source) {
    const start = source.indexOf('var loadingDirective = {};');
    const end = source.indexOf('/* harmony default export */', start);

    if (start < 0 || end < 0) {
        throw new Error('Element UI loading directive changed; review the binding guard.');
    }

    let count = 0;
    const directive = source.slice(start, end).replace(
        /function (?:toggleLoading|insertDom|bind|update|unbind)\([^)]*\) \{/g,
        (signature) => {
            count++;
            return signature + '\n    binding = Object.assign({}, binding, { modifiers: (binding && binding.modifiers) || {} });';
        }
    );

    if (count !== 5) {
        throw new Error('Expected five Element UI loading directive entry points; found ' + count);
    }

    return source.slice(0, start) + directive + source.slice(end);
};
