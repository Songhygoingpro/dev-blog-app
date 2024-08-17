
const profile_pic = document.querySelector('.profile-pic');
const profile_items =document.querySelector('.profile-items');

profile_pic.addEventListener('click', function() {

if(profile_items )
profile_items.classList.toggle('hidden');
profile_items.classList.toggle('block');

})



