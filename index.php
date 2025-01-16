<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utube</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <!-- Main Content -->
        <div class="main-content">
            <div id="video-player">
                <iframe width="100%" height="400" src="https://www.youtube.com/embed/dQw4w9WgXcQ" 
                        title="YouTube video player" frameborder="0" allowfullscreen></iframe>
            </div>

            <div id="comments-section">
                <h2>Comments for: <span id="video-title">Rick & Roll</span></h2>
                <form id="comment-form">
                    <input type="text" id="name" name="name" placeholder="Name" required>
                    <input type="email" id="email" name="email" placeholder="Email" required>
                    <textarea id="message" name="message" placeholder="Message" required></textarea>
                    <input type="hidden" id="video_id" name="video_id" value="1"> <!-- Dynamic Video ID -->
                    <button type="submit">Submit</button>
                </form>

                <ul id="comments-list"></ul>
            </div>
        </div>

        <!-- Sidebar with Similar Videos -->
        <div class="sidebar">
            <div id="video-search">
                <h2>Search for Videos</h2>
                <input type="text" id="search-query" placeholder="Search for videos">
                <button id="search-btn">Search</button>
                <ul id="video-results"></ul>
            </div>

            <div id="similar-videos">
                <h2>Similar Videos</h2>
                <ul id="similar-videos-list"></ul>
            </div>
        </div>
    </div>

    <script>
    const apiKey = 'AIzaSyAiGHWk0Cx40XPFEoYUqXNZgytbOdA2hB4'; // Replace with your actual YouTube API key

    // Submit comment form
    document.getElementById('comment-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('save_comment.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Comment added successfully!');
                loadComments(document.getElementById('video_id').value); // Refresh comments
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => console.error('Error submitting comment:', error));
    });

    // Load video and fetch comments
    function loadVideo(videoId, title) {
        // Update video player and title
        document.querySelector('#video-player iframe').src = `https://www.youtube.com/embed/${videoId}`;
        document.getElementById('video-title').textContent = title;
        document.getElementById('video_id').value = videoId;

        // Fetch and display comments for the new video
        loadComments(videoId);
    }

    // Fetch comments for a video
    function loadComments(videoId) {
        fetch(`get_comments.php?video_id=${videoId}`)
            .then(response => response.json())
            .then(data => {
                const commentsList = document.getElementById('comments-list');
                commentsList.innerHTML = ''; // Clear old comments
                data.forEach(comment => {
                    const li = document.createElement('li');
                    li.innerHTML = `<strong>${comment.name}</strong> (${comment.email}): ${comment.message}`;
                    commentsList.appendChild(li);
                });
            })
            .catch(error => console.error('Error fetching comments:', error));
    }

    // Search for videos
    document.getElementById('search-btn').addEventListener('click', function () {
        const query = document.getElementById('search-query').value;
        fetch(`https://www.googleapis.com/youtube/v3/search?part=snippet&q=${encodeURIComponent(query)}&type=video&key=${apiKey}`)
            .then(response => response.json())
            .then(data => {
                const resultsList = document.getElementById('video-results');
                resultsList.innerHTML = ''; // Clear previous results
                data.items.forEach(item => {
                    const listItem = document.createElement('li');
                    listItem.innerHTML = `
                        <img src="${item.snippet.thumbnails.default.url}" alt="${item.snippet.title}" />
                        <span>${item.snippet.title}</span>
                        <button onclick="loadVideo('${item.id.videoId}', '${item.snippet.title}')">Select</button>
                    `;
                    resultsList.appendChild(listItem);

                  console.log(data);
                });
            })
            .catch(error => console.error('Error fetching videos:', error));
    });

    // Initial load of default video comments
    document.addEventListener('DOMContentLoaded', () => {
        const defaultVideoId = document.getElementById('video_id').value;
        loadComments(defaultVideoId);


    });
</script>

</body>
</html>
