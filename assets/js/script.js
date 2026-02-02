/* Project: Student Helpdesk
   File: script.js
   Description: Handles the Live Ajax Popup Search
*/

document.addEventListener("DOMContentLoaded", function() {
    console.log("Script Loaded Successfully");

    const searchInput = document.getElementById("live_search");
    const resultBox = document.getElementById("search_results");

    // Check if elements exist to prevent errors on pages without search
    if (searchInput && resultBox) {
        
        searchInput.addEventListener("keyup", function() {
            let query = this.value.trim();

            if (query.length > 0) {
                // Fetch data from PHP (Ajax)
                fetch(`search.php?ajax_query=${query}`)
                .then(response => response.json())
                .then(data => {
                    resultBox.innerHTML = ""; // Clear old results
                    
                    if (data.length > 0) {
                        resultBox.style.display = "block";
                        
                        data.forEach(ticket => {
                            let div = document.createElement("div");
                            // Display ID and Issue Type in the dropdown
                            div.innerHTML = `<strong>#${ticket.id}</strong> ${ticket.issue_type}`;
                            
                            // Styling for the dropdown items
                            div.style.padding = "10px";
                            div.style.borderBottom = "1px solid #eee";
                            div.style.cursor = "pointer";
                            div.style.color = "#333";
                            
                            // Hover effect
                            div.onmouseover = () => div.style.background = "#f1f1f1";
                            div.onmouseout = () => div.style.background = "white";
                            
                            // Click to go directly to view page
                            div.onclick = () => window.location.href = `view.php?id=${ticket.id}`;
                            
                            resultBox.appendChild(div);
                        });
                    } else {
                        // Show "No matches" message
                        resultBox.innerHTML = "<div style='padding:10px; color:#999'>No matches found</div>";
                        resultBox.style.display = "block";
                    }
                })
                .catch(error => console.error("Error:", error));
            } else {
                // Hide box if input is empty
                resultBox.style.display = "none";
            }
        });

        // Hide results if user clicks outside the box
        document.addEventListener("click", function(e) {
            if (e.target !== searchInput) {
                resultBox.style.display = "none";
            }
        });
    }
});