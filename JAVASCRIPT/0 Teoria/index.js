const txtn1 = document.querySelector("#num1");
const txtn2 = document.getElementById("num2");
const respusta = document.querySelector("#resultado");
const btn = document.querySelector("#sumar");
btn.addEventListener("click", calcular);

function calcular() {
  const n1 = parseFloat(txtn1.value);
  const n2 = parseFloat(txtn2.value);
  let resultado = n1 + n2;
  respusta.innerHTML = `Resultado: ${resultado}`;
}