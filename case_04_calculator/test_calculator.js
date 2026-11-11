"use strict";

const test = require("node:test");
const assert = require("node:assert/strict");
const { parseNumber, sum, difference, product, division, calculate, formatResult } = require("./script.js");

test("выполняется сложение", function () {
    assert.equal(sum(7, 5), 12);
    assert.equal(calculate("sum", "7", "5"), 12);
});
test("выполняется вычитание", function () {
    assert.equal(difference(7, 5), 2);
    assert.equal(calculate("difference", "7", "5"), 2);
});
test("выполняется умножение", function () {
    assert.equal(product(7, 5), 35);
    assert.equal(calculate("product", "7", "5"), 35);
});
test("выполняется деление", function () {
    assert.equal(division(15, 3), 5);
    assert.equal(calculate("division", "15", "3"), 5);
});
test("поддерживаются отрицательные и дробные числа", function () {
    assert.equal(calculate("sum", "-2.5", "1,5"), -1);
    assert.equal(parseNumber(" 3,25 "), 3.25);
});
test("пустое поле вызывает понятную ошибку", function () {
    assert.throws(() => calculate("sum", "", "2"), /Введите два числа/);
});
test("нечисловой ввод вызывает ошибку", function () {
    assert.throws(() => calculate("sum", "abc", "2"), /только числа/);
});
test("деление на ноль отклоняется", function () {
    assert.throws(() => calculate("division", "5", "0"), /деление на ноль невозможно/);
});
test("неизвестная операция отклоняется", function () {
    assert.throws(() => calculate("power", "2", "3"), /Неизвестная математическая операция/);
});
test("форматирование убирает лишний хвост дроби", function () {
    assert.equal(formatResult(0.1 + 0.2), "0.3");
    assert.equal(formatResult(10), "10");
});
