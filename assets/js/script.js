const profilePic = document.querySelector('.profile-pic');
const profileItems = document.querySelector('.profile-items');

profilePic.addEventListener('click', function(event) {
    // Prevent the click from propagating to the window
    event.stopPropagation();
    
    if (profileItems) {
        profileItems.classList.toggle('opacity-100');
        profileItems.classList.toggle('scale-y-100');
    }
});

window.addEventListener('click', () => {
    if (profileItems) {
        profileItems.classList.remove('opacity-100');
        profileItems.classList.remove('scale-y-100');
    }
});

// Prevent the window click listener from firing when clicking inside the profile menu
profileItems.addEventListener('click', function(event) {
    event.stopPropagation();
});

function format(command, value) {
    if (command === 'createLink') {
        let url = prompt('Enter the link here: ', 'http://');
        document.execCommand(command, false, url);
    } else if (command === 'insertImage') {
        let url = prompt('Enter the image URL here: ', 'http://');
        document.execCommand(command, false, url);
    } else if (command === 'formatBlock') {
        document.execCommand(command, false, value);
    } else {
        document.execCommand(command, false, null);
    }
}