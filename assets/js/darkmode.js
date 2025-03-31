import feather from "feather-icons";

const htmlEl = document.documentElement;
const toggleButton = document.getElementById('darkModeToggle');

function updateIcon(theme) {
    if (!toggleButton) return;
    const iconName = theme === 'dark' ? 'sun' : 'moon';
    toggleButton.innerHTML = `<i data-feather="${iconName}" id="darkModeToggleIcon"></i>`;
    feather.replace();
}

function toggleTheme() {
    if (!toggleButton) return;
    const currentTheme = htmlEl.getAttribute('data-bs-theme') || 'light';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    htmlEl.setAttribute('data-bs-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    updateIcon(newTheme);
}

// Check if the toggle button exists before adding the event listener
if (toggleButton) toggleButton.addEventListener('click', toggleTheme);

const savedTheme = localStorage.getItem('theme') === 'dark' ? 'dark' : 'light';
htmlEl.setAttribute('data-bs-theme', savedTheme);
updateIcon(savedTheme);
