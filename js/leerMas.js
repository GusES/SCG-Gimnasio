function leerMas(x) {
  var x = x;
  var dots = document.getElementById("dots" + x);
  var moreText = document.getElementById("more" + x);
  var btnText = document.getElementById("myBtn" + x);
  if (dots.style.display === "none") {
    dots.style.display = "inline";
    btnText.innerHTML = "Leer Mas";
    moreText.style.display = "none";
  } else {
    dots.style.display = "none";
    btnText.innerHTML = "Leer menos";
    moreText.style.display = "inline";
  }
}