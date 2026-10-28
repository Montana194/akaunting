const assert = require('node:assert/strict');
const { test } = require('node:test');
const fs = require('node:fs');
const path = require('node:path');
const Module = require('node:module');
const { transformSync } = require('@babel/core');

const filename = path.resolve(__dirname, '../../resources/assets/js/plugins/currency.js');
const compiled = transformSync(fs.readFileSync(filename, 'utf8'), {
    babelrc: false,
    configFile: false,
    plugins: ['@babel/plugin-transform-modules-commonjs'],
}).code;
const currencyModule = new Module(filename, module);
currencyModule.filename = filename;
currencyModule.paths = Module._nodeModulePaths(path.dirname(filename));
currencyModule._compile(compiled, filename);
const Currency = currencyModule.exports.default;

const moneyFilename = path.resolve(__dirname, '../../resources/assets/js/plugins/money.js');
const moneyCode = transformSync(fs.readFileSync(moneyFilename, 'utf8'), {
    babelrc: false,
    configFile: false,
    plugins: ['@babel/plugin-transform-modules-commonjs'],
}).code;
const moneyModule = new Module(moneyFilename, module);
moneyModule.filename = moneyFilename;
moneyModule.paths = Module._nodeModulePaths(path.dirname(moneyFilename));
const originalRequire = moneyModule.require.bind(moneyModule);
moneyModule.require = name => name === './currency' ? currencyModule.exports : originalRequire(name);
moneyModule._compile(moneyCode, moneyFilename);
const Money = moneyModule.exports.default;

test('uses the requested currency and its precision', () => {
    assert.equal(new Currency('EUR').getCode(), 978);
    assert.equal(new Currency('JPY').getPrecision(), 0);
    assert.equal(new Currency('PHP').getSymbol(), '₱');
});

test('normalizes currency codes and preserves the default', () => {
    assert.equal(new Currency(' eur ').getCurrency(), 'EUR');
    assert.equal(new Currency().getCurrency(), 'USD');
    assert.equal(new Currency('EUR').equals(new Currency('USD')), false);
});

test('rejects unknown currency codes', () => {
    assert.throws(() => new Currency('INVALID'), /Invalid currency/);
});

test('formats document amounts without masking the format method', () => {
    assert.equal(new Money('USD', 1234.5, true).format(), '$1,234.50');
    assert.equal(new Money('EUR', -12.5).getValue(), -12.5);
    assert.equal(new Money('JPY', 1234).format(), '¥1,234');
});

test('exchange-rate calculations preserve the destination currency', () => {
    const original = new Money('JPY', 125);
    const converted = original.multiply(2);
    assert.equal(converted.currency.getCurrency(), 'JPY');
    assert.equal(converted.getAmount(), 250);
    assert.equal(original.getAmount(), 125);
    assert.equal(new Money('USD', 1.235).getAmount(true), 1.24);
    assert.throws(() => original.divide(0), /Division by zero/);
});
