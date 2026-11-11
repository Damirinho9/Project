"use strict";

const test = require("node:test");
const assert = require("node:assert/strict");
const { MIN_VALUE, MAX_VALUE, getCounterState } = require("./script.js");

test("нулевое значение имеет красный класс", function () {
    const state = getCounterState(0);
    assert.equal(state.resultClass, "result--zero");
    assert.equal(state.message, "");
});

test("положительное значение имеет желтый класс", function () {
    assert.equal(getCounterState(4).resultClass, "result--positive");
});

test("отрицательное значение имеет зеленый класс", function () {
    assert.equal(getCounterState(-4).resultClass, "result--negative");
});

test("на верхней границе блокируется кнопка плюс", function () {
    const state = getCounterState(MAX_VALUE);
    assert.equal(state.plusDisabled, true);
    assert.equal(state.minusDisabled, false);
    assert.equal(state.message, "Вы достигли экстремального значения");
});

test("на нижней границе блокируется кнопка минус", function () {
    const state = getCounterState(MIN_VALUE);
    assert.equal(state.minusDisabled, true);
    assert.equal(state.plusDisabled, false);
    assert.equal(state.message, "Вы достигли экстремального значения");
});

test("значения выше и ниже диапазона ограничиваются границами", function () {
    assert.equal(getCounterState(15).value, MAX_VALUE);
    assert.equal(getCounterState(-15).value, MIN_VALUE);
});

test("дробное значение отклоняется", function () {
    assert.throws(() => getCounterState(1.5), TypeError);
});
