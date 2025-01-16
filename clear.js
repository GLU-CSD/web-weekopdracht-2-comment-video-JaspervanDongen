$(document).ready(function() {
    $('#clear-comments').on('click', function() {
        // Confirm the action
        if (confirm('Are you sure you want to clear all comments?')) {
            // Send an AJAX request to clear the comments
            $.ajax({
                url: 'clear_comments.php',  // The PHP file that will handle clearing the comments
                method: 'POST',
                success: function(response) {
                    if (response == 'success') {
                        // Clear the comments from the UI
                        $('#comments-list').empty();
                    } else {
                        alert('Failed to clear comments.');
                    }
                }
            });
        }
    });
});
