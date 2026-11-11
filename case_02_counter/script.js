(function () {
    "use strict";

    const MIN_VALUE = -10;
    const MAX_VALUE = 10;
    let currentValue = 0;

    function getCounterState(value) {
        if (!Number.isInteger(value)) {
            throw new TypeError("Значение счетчика должно быть целым числом.");
        }
        const clampedValue = Math.min(MAX_VALUE, Math.max(MIN_VALUE, value));
        let resultClass = "result--zero";
        if (clampedValue > 0) resultClass = "result--positive";
        else if (clampedValue < 0) resultClass = "result--negative";
        const isLimit = clampedValue === MIN_VALUE || clampedValue === MAX_VALUE;
        return {
            value: clampedValue,
            resultClass,
            plusDisabled: clampedValue === MAX_VALUE,
            minusDisabled: clampedValue === MIN_VALUE,
            message: isLimit ? "Вы достигли экстремального значения" : ""
        };
    }

    function changeValue(delta) {
        currentValue = getCounterState(currentValue + delta).value;
        render();
    }

    function render() {
        const state = getCounterState(currentValue);
        const result = document.getElementById("result");
        const plusButton = document.getElementById("plus-button");
        const minusButton = document.getElementById("minus-button");
        const message = document.getElementById("limit-message");
        result.textContent = String(state.value);
        result.className = `result ${state.resultClass}`;
        plusButton.disabled = state.plusDisabled;
        minusButton.disabled = state.minusDisabled;
        message.textContent = state.message;
    }

    if (typeof document === "undefined") return;

    document.getElementById("plus-button").addEventListener("click", () => changeValue(1));
    document.getElementById("minus-button").addEventListener("click", () => changeValue(-1));
    render();
}());
