const assert = require('node:assert/strict');
const { test } = require('node:test');
const fs = require('node:fs');
const path = require('node:path');
const Module = require('node:module');
const { transformSync } = require('@babel/core');

const filename = path.resolve(__dirname, '../../resources/assets/js/plugins/functions.js');
const compiled = transformSync(fs.readFileSync(filename, 'utf8'), {
    babelrc: false,
    configFile: false,
    plugins: ['@babel/plugin-transform-modules-commonjs'],
}).code;
const functionsModule = new Module(filename, module);
functionsModule.filename = filename;
functionsModule.paths = Module._nodeModulePaths(path.dirname(filename));
functionsModule._compile(compiled, filename);
const { calculationToQuantity } = functionsModule.exports;

test('quantity expressions preserve arithmetic and decimal results', () => {
    assert.equal(calculationToQuantity('1.5 * 4'), 6);
    assert.equal(calculationToQuantity('(12 + 8) / 4'), 5);
    assert.equal(calculationToQuantity(0), 0);
    assert.equal(calculationToQuantity('-2 * 3'), -6);
});

test('incomplete expressions report an error', () => {
    assert.throws(() => calculationToQuantity('2 +'));
});
