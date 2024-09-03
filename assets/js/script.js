const profilePic = document.querySelector('.profile-pic');
const profileItems = document.querySelector('.profile-items');

if (profilePic) {
    profilePic.addEventListener('click', function(event) {
      // Prevent the click from propagating to the window
      event.stopPropagation();
  
      if (profileItems) {
        profileItems.classList.toggle('opacity-100');
        profileItems.classList.toggle('scale-y-100');
      }
    });
  }
  
  window.addEventListener('click', () => {
    if (profileItems) {
      profileItems.classList.remove('opacity-100');
      profileItems.classList.remove('scale-y-100');
    }
  });

// Prevent the window click listener from firing when clicking inside the profile menu
if(profileItems){
profileItems.addEventListener('click', function(event) {
    event.stopPropagation();
});
}

 // Attach event listener to the input field for searching posts
 document.querySelector('.search-post').addEventListener('input', function() {
  const query = document.querySelector('.search-post').value.trim().toLowerCase(); // Get the search query and convert it to lowercase

  // Filter the posts based on the input query
  const filteredPosts = postTitles
      .filter(post => post.title.toLowerCase().includes(query)) // Filter posts
      .sort((a, b) => {
          const aStartsWithQuery = a.title.toLowerCase().startsWith(query);
          const bStartsWithQuery = b.title.toLowerCase().startsWith(query);

          // Prioritize titles that start with the query
          if (aStartsWithQuery && !bStartsWithQuery) return -1;
          if (!aStartsWithQuery && bStartsWithQuery) return 1;

          // For titles that both start with or don't start with the query, sort alphabetically
          return a.title.localeCompare(b.title);
      });

  showPostSuggestions(filteredPosts); // Display the filtered suggestions
});

// Function to display post suggestions
function showPostSuggestions(posts) { // Parameter is named `posts` to represent an array of objects
  const suggestionsContainer = document.querySelector('.post-suggestion'); // Use class selector
  suggestionsContainer.innerHTML = ''; // Clear any previous suggestions

  if (posts.length === 0) {
      suggestionsContainer.classList.add('hidden'); // Hide the suggestions container if there are no results
      return;
  }

  posts.forEach(post => { // Loop through `posts` (filteredPosts)
      const suggestion_link = document.createElement('a');
      suggestion_link.href = post.post_url; // Set the href attribute to the post URL

      const suggestion = document.createElement('div'); // Create a div element for each suggestion
      suggestion.className = 'post-suggestions p-2 hover:bg-gray-100'; // Add the 'post-suggestion' class and additional styling
      suggestion.textContent = post.title; // Set the suggestion's text content

      suggestion_link.appendChild(suggestion); // Append the suggestion div to the link
      suggestionsContainer.appendChild(suggestion_link); // Add the link to the suggestions container
  });

  suggestionsContainer.classList.remove('hidden'); // Show the suggestions container
}

document.querySelector('.search-post').addEventListener('click', function(event) {

  event.stopPropagation();

  if (document.querySelector('.post-suggestion') && document.querySelector('.search-post').value) {
      document.querySelector('.post-suggestion').classList.toggle('hidden');
  }

});

window.addEventListener('click', () => {
if (document.querySelector('.post-suggestion')) {
  document.querySelector('.post-suggestion').classList.add('hidden');
}
});

document.querySelector('.search-post').addEventListener('input', function(event) {

if (document.querySelector('.search-post').value === '') {
      document.querySelector('.post-suggestion').classList.add('hidden');
  }

});