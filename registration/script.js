function selfiePreview() {
  var fileInput = document.getElementById('selfieID');
  var image = document.getElementById('selfie');
  var file = fileInput.files[0];
  var reader = new FileReader();

  reader.onload = function(e) {
    image.src = e.target.result;
  }

  reader.readAsDataURL(file);
}

function resetSelfie() {
  var image = document.getElementById('selfie');
  var originalSrc = image.getAttribute('data-original');
  image.src = originalSrc;
  var fileInput = document.getElementById('selfieID');
  fileInput.value = '';

  event.preventDefault();
}

function frontPreviewImage() {
  var fileInput = document.getElementById('frontID');
  var image = document.getElementById('validFrontID');
  var file = fileInput.files[0];
  var reader = new FileReader();

  reader.onload = function(e) {
    image.src = e.target.result;
  }

  reader.readAsDataURL(file);
}

function frontPreviewImage1() {
  var fileInput = document.getElementById('frontIDPortrait');
  var image = document.getElementById('validFrontIDportrait');
  var file = fileInput.files[0];
  var reader = new FileReader();

  reader.onload = function(e) {
    image.src = e.target.result;
  }

  reader.readAsDataURL(file);
}

function resetFrontImage() {
  var image = document.getElementById('validFrontID');
  var originalSrc = image.getAttribute('data-original');
  image.src = originalSrc;
  var fileInput = document.getElementById('frontID');
  fileInput.value = '';

  event.preventDefault();
}

function resetFrontImage1() {
  var image = document.getElementById('validFrontIDportrait');
  var originalSrc = image.getAttribute('data-original');
  image.src = originalSrc;
  var fileInput = document.getElementById('frontIDPortrait');
  fileInput.value = '';

  event.preventDefault();
}

function backPreviewImage() {
  var fileInput = document.getElementById('backID');
  var image = document.getElementById('validBackID');
  var file = fileInput.files[0];
  var reader = new FileReader();

  reader.onload = function(e) {
    image.src = e.target.result;
  }

  reader.readAsDataURL(file);
}
function backPreviewImage1() {
  var fileInput = document.getElementById('backIDPortrait');
  var image = document.getElementById('validBackIDportrait');
  var file = fileInput.files[0];
  var reader = new FileReader();

  reader.onload = function(e) {
    image.src = e.target.result;
  }

  reader.readAsDataURL(file);
}

function resetBackImage() {
  var image = document.getElementById('validBackID');
  var originalSrc = image.getAttribute('data-original');
  image.src = originalSrc;
  var fileInput = document.getElementById('backID');
  fileInput.value = '';

  event.preventDefault();
}

function resetBackImage1() {
  var image = document.getElementById('validBackIDportrait');
  var originalSrc = image.getAttribute('data-original');
  image.src = originalSrc;
  var fileInput = document.getElementById('backIDPortrait');
  fileInput.value = '';

  event.preventDefault();
}
