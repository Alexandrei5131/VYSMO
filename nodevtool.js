
document.addEventListener('contextmenu', function (e) {
  e.preventDefault();
});

document.addEventListener('keydown', function (e) {
  // Prevent F12 (Inspect Element)
  if (e.key === 'F12') {
    e.preventDefault();
  }

  // Prevent Ctrl+Shift+I (Inspect Element)
  if (e.ctrlKey && e.shiftKey && e.key === 'I') {
    e.preventDefault();
  }

  // Prevent Ctrl+Shift+C (Inspect Element)
  if (e.ctrlKey && e.shiftKey && e.key === 'C') {
    e.preventDefault();
  }

  // Prevent Ctrl+Shift+J (Inspect Element)
  if (e.ctrlKey && e.shiftKey && e.key === 'J') {
    e.preventDefault();
  }

  // Prevent Ctrl+U (View Page Source)
  if (e.ctrlKey && e.key === 'U') {
    e.preventDefault();
  }
});