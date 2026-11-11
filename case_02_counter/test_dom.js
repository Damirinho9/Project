"use strict";

const test = require("node:test");
const assert = require("node:assert/strict");

function createButton() {
    return {
        disabled: false,
        listeners: {},
        addEventListener(type, handler) { this.listeners[type] = handler; },
        click() {
            if (!this.disabled && this.listeners.click) this.listeners.click();
        }
    };
}

function loadCounterDom() {
    const elements = {
        result: { textContent: "0", className: "result result--zero" },
        "plus-button": createButton(),
        "minus-button": createButton(),
        "limit-message": { textContent: "" }
    };
    global.document = { getElementById(id) { return elements[id]; } };
    delete require.cache[require.resolve("./script.js")];
    require("./script.js");
    return elements;
}

test("DOM: начальное состояние счетчика", function () {
    const el = loadCounterDom();
    assert.equal(el.result.textContent, "0");
    assert.match(el.result.className, /result--zero/);
    assert.equal(el["plus-button"].disabled, false);
    assert.equal(el["minus-button"].disabled, false);
    assert.equal(el["limit-message"].textContent, "");
});

test("DOM: достижение верхней и нижней границы", function () {
    const el = loadCounterDom();
    for (let i = 0; i < 10; i += 1) el["plus-button"].click();
    assert.equal(el.result.textContent, "10");
    assert.match(el.result.className, /result--positive/);
    assert.equal(el["plus-button"].disabled, true);
    assert.equal(el["minus-button"].disabled, false);
    assert.equal(el["limit-message"].textContent, "Вы достигли экстремального значения");

    for (let i = 0; i < 20; i += 1) el["minus-button"].click();
    assert.equal(el.result.textContent, "-10");
    assert.match(el.result.className, /result--negative/);
    assert.equal(el["plus-button"].disabled, false);
    assert.equal(el["minus-button"].disabled, true);
});
