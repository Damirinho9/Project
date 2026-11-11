(function () {
    "use strict";

    function parseNumber(value) {
        const normalized = String(value).trim().replace(",", ".");
        if (normalized === "") throw new Error("Введите два числа.");
        const number = Number(normalized);
        if (!Number.isFinite(number)) throw new Error("Ошибка: в поля необходимо вводить только числа.");
        return number;
    }

    function sum(a,b) { return a + b; }
    function difference(a,b) { return a - b; }
    function product(a,b) { return a * b; }
    function division(a,b) {
        if (b === 0) throw new Error("Ошибка: деление на ноль невозможно.");
        return a / b;
    }

    function calculate(operation, firstValue, secondValue) {
        const firstNumber = parseNumber(firstValue);
        const secondNumber = parseNumber(secondValue);
        switch (operation) {
            case "sum": return sum(firstNumber, secondNumber);
            case "difference": return difference(firstNumber, secondNumber);
            case "product": return product(firstNumber, secondNumber);
            case "division": return division(firstNumber, secondNumber);
            default: throw new Error("Неизвестная математическая операция.");
        }
    }

    function formatResult(value) {
        if (!Number.isFinite(value)) throw new Error("Результат вычисления некорректен.");
        return Number.isInteger(value) ? String(value) : String(Number(value.toFixed(10)));
    }

    if (typeof document === "undefined") return;

    const firstInput = document.getElementById("first-number");
    const secondInput = document.getElementById("second-number");
    const resultOutput = document.getElementById("result");
    const resultBox = document.querySelector(".result-box");

    document.querySelectorAll("[data-operation]").forEach(function (button) {
        button.addEventListener("click", function () {
            try {
                const value = calculate(button.dataset.operation, firstInput.value, secondInput.value);
                resultOutput.textContent = formatResult(value);
                resultBox.classList.remove("result-box--error");
            } catch (error) {
                resultOutput.textContent = error.message;
                resultBox.classList.add("result-box--error");
            }
        });
    });
}());
