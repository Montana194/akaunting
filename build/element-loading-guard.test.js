const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const guard = require('./element-loading-guard');

for (const file of ['element-ui.common.js', 'loading.js']) {
    const source = guard(fs.readFileSync(require.resolve('element-ui/lib/' + file), 'utf8'));
    const start = source.indexOf('var loadingDirective = {};');
    const end = source.indexOf('/* harmony default export */', start);
    let hooks;
    const ticks = [];
    const leaves = [];
    const element = () => ({
        style: {}, classes: new Set(), getAttribute: () => null,
        getBoundingClientRect: () => ({ top: 0, left: 0, height: 10, width: 10 }),
        appendChild(child) { child.parentNode = this; },
        removeChild(child) { child.parentNode = null; },
    });
    const body = element();
    const context = {
        Mask: function ({ el, data }) {
            Object.assign(this, data, {
                $el: el, setText() {}, $emit() {},
                $destroy() { this.destroyed = true; },
            });
        },
        document: { body, documentElement: {}, createElement: element },
        dom_: {
            getStyle: () => 'static',
            addClass: (el, name) => el.classes.add(name),
            removeClass: (el, name) => el.classes.delete(name),
        },
        popup_: { PopupManager: { nextZIndex: () => 2000 } },
        after_leave_default: () => (instance, callback) => leaves.push(callback),
    };
    vm.runInNewContext(source.slice(start, end), context);
    context.loadingDirective.install({
        prototype: {}, nextTick: callback => ticks.push(callback),
        directive: (name, value) => { hooks = value; },
    });
    const flush = () => { while (ticks.length) ticks.shift()(); };
    for (const binding of [undefined, null, {}, { value: true }, { value: true, modifiers: null },
        { value: true, modifiers: { fullscreen: true, lock: true } },
        { value: true, modifiers: { body: true } }]) {
        const el = element();
        hooks.bind(el, binding, { context: {} });
        flush();
        if (binding && binding.value) assert.equal(el.instance.visible, true);
        if (binding && binding.modifiers && binding.modifiers.lock) {
            assert.equal(body.classes.has('el-loading-parent--hidden'), true);
        }
        hooks.update(el, { oldValue: true, value: false });
        while (leaves.length) leaves.shift()();
        assert.equal(el.instance.visible, false);
        hooks.update(el, undefined);
        hooks.unbind(el, undefined);
        while (leaves.length) leaves.shift()();
        assert.equal(el.instance.destroyed, true);
    }
    console.log(file + ': missing bindings/modifiers and normal loading lifecycle passed');
}
