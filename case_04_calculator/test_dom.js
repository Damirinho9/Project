"use strict";

const test = require("node:test");
const assert = require("node:assert/strict");

function createClassList() {
    const values = new Set();
    return {
        toggle(name, force) { if (force) values.add(name); else values.delete(name); },
        contains(name) { return values.has(name); }
    };
}

function createButton(operation) {
    return {
        dataset: { operation },
        listeners: {},
        addEventListener(type, handler) { this.listeners[type] = handler; },
        click() { this.listeners.click(); }
    };
}

function loadCalculatorDom() {
    const firstInput = { value: "" };
    const secondInput = { value: "" };
    const resultOutput = { textContent: "Здесь появится результат" };
    const resultBox = { classList: createClassList() };
    const buttons = ["sum", "difference", "product", "division"].map(createButton);

    global.document = {
        getElementById(id) {
            if (id === "first-number") return firstInput;
            if (id === "second-number") return secondInput;
            if (id === "result") return resultOutput;
            return null;
        },
        querySelector(selector) { return selector === ".result-box" ? resultBox : null; },
        querySelectorAll(selector) { return selector === "[data-operation]" ? buttons : []; }
    };

    delete require.cache[require.resolve("./script.js")];
    require("./script.js");

    function click(operation) {
        buttons.find((button) => button.dataset.operation === operation).click();
    }

    return { firstInput, secondInput, resultOutput, resultBox, click };
}

test("DOM: четыре операции выводят результат", function () {
    const app = loadCalculatorDom();
    const cases = [
        ["sum", "12.5", "3", "15.5"],
        ["difference", "12.5", "3", "9.5"],
        ["product", "-2", "4", "-8"],
        ["division", "15", "3", "5"]
    ];
    for (const [operation, a, b, expected] of cases) {
        app.firstInput.value = a;
        app.secondInput.value = b;
        app.click(operation);
        assert.equal(app.resultOutput.textContent, expected);
        assert.equal(app.resultBox.classList.contains("result-box--error"), false);
    }
});

test("DOM: нечисловой ввод показывает ошибку", function () {
    const app = loadCalculatorDom();
    app.firstInput.value = "abc";
    app.secondInput.value = "2";
    app.click("sum");
    assert.match(app.resultOutput.textContent, /только числа/);
    assert.equal(app.resultBox.classList.contains("result-box--error"), true);
});

test("DOM: деление на ноль показывает ошибку", function () {
    const app = loadCalculatorDom();
    app.firstInput.value = "5";
    app.secondInput.value = "0";
    app.click("division");
    assert.match(app.resultOutput.textContent, /деление на ноль невозможно/);
    assert.equal(app.resultBox.classList.contains("result-box--error"), true);
});
