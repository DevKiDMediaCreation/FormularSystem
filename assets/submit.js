// Get all submit buttons
const submitButtons = document.querySelectorAll('button[type="submit"]')

submitButtons.addEventListener('click', event => {
    event.target.textContent = 'Loading...'
    event.target.classList.add('disabled')
}