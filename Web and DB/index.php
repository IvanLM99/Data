<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Final Project - Storage and Data Recovery</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* CSS to adjust the width of the keywords table columns */
        
		#keywords-table-container {
            
			margin-left: 0px;
        }
		
		#keywords-table {
            width: 20%;
            margin: 0 auto; 
        }
		
        #superfluous-table-container {
            text-align: center;
        }

        #superfluous-table {
            width: 20%; 
            margin: 0 auto;
        }
		
		#change-table-container {
            text-align: center;
        }

        #change-table {
            width: 20%;
            margin: 0 auto; 
        }
		
		#changes-table-container {
            text-align: center;
        }

        #changes-table {
            width: 20%;
            margin: 0 auto; 
        }		
		
		#report-table-container {
			text-align: center;
			margin: 0 auto; 
			width: 70%;
        }
		
    </style>
    <script>
        $(document).ready(function() {
            // Submit form via AJAX when Insert URL button is clicked
            $("#insert-url-form").submit(function(event) {
                event.preventDefault(); // Prevent default form submission
                var url = $("#url").val();
                // Check if the URL input is empty
                if (url.trim() === "") {
                    alert("URL cannot be empty");
                    return; // Stop form submission if URL is empty
                }

                $.ajax({
                    type: "POST",
                    url: "insert_url.php",
                    data: {url: url},
                    success: function(response) {
                        if (response === "success") {
                            fetchAndUpdateTable(); // Update the table after successful insertion
							fetchKeywordsTable(); // Update the keywords list after inserting URLs
							fetchSuperfluousTable();
							fetchChangesTable();
							updateReport();
                            $("#url").val(""); // Clear the input field after successful insertion
                        } else {
                            alert("Failed to insert URL");
                        }
                    }
                });
            });
			
			// Initial display on page load
			$("#urls-table-container").show();
			$("#keywords-table-container").hide();
			$("#superfluous-table-container").hide();
			$("#changes-table-container").hide();
			$("#report-table-container").hide();

            // Function to fetch and update the table
            function fetchAndUpdateTable() {
                $.ajax({
                    type: "GET",
                    url: "fetch_urls.php",
                    success: function(response) {
                        $("#urls-table tbody").html(response);
                    }
                });
            }

            // AJAX for Scrap button
            $("#scrap").click(function(event) {
                event.preventDefault(); // Prevent the default form submission
                $.ajax({
                    type: "POST",
                    url: "scrap.php",
                    success: function(response) {
                        fetchAndUpdateTable(); // Update table after scraping
                        alert("Scraping completed");
                    }
                });
            });

            // AJAX for Split button
            $("#split").click(function(event) {
                event.preventDefault(); // Prevent the default form submission
                $.ajax({
                    type: "POST",
                    url: "split.php",
                    success: function(response) {
                        fetchAndUpdateTable(); // Update table after splitting
						fetchKeywordsTable(); // Update the keywords list after splitting URLs
						fetchSuperfluousTable();
						fetchChangesTable();
						updateReport();
                        alert("Splitting completed");
                    }
                });
            });

            // AJAX for URLs button click
            $("#urls").click(function(event) {
                event.preventDefault(); // Prevent the default form submission

                // Display URLs table and hide Keywords table
                $("#urls-table-container").show();
                $("#keywords-table-container").hide();
				$("#superfluous-table-container").hide();
				$("#changes-table-container").hide();
				$("#report-table-container").hide();
            });

            // AJAX for Keywords button click
            $("#keywords").click(function(event) {
                event.preventDefault(); // Prevent the default form submission

                // Display Keywords table and hide URLs table
                $("#urls-table-container").hide();
                $("#keywords-table-container").show();
				$("#superfluous-table-container").hide();
				$("#changes-table-container").hide();
				$("#report-table-container").hide();

                // Fetch Keywords table if it's empty
                if ($("#keywords-table").is(':empty')) {
                    fetchKeywordsTable();
                }
            });
			
			// AJAX for Superfluous button click
			$("#superfluous").click(function(event) {
				event.preventDefault(); // Prevent the default form submission

				// Hide URLs and Keywords tables, show Superfluous table
				$("#urls-table-container").hide();
				$("#keywords-table-container").hide();
				$("#superfluous-table-container").show();
				$("#changes-table-container").hide();
				$("#report-table-container").hide();

				if ($("#superfluous-table").is(':empty')) {
					fetchSuperfluousTable(); // Fetch the superfluous table if it's empty
				}
			});
			
			// AJAX for Change button click
			$("#changes").click(function(event) {
				event.preventDefault(); // Prevent the default form submission

				// Display Changes table and hide other tables
				$("#urls-table-container").hide();
				$("#keywords-table-container").hide();
				$("#superfluous-table-container").hide();
				$("#changes-table-container").show();
				$("#report-table-container").hide();

				// Fetch Changes table if it's empty
				if ($("#changes-table").is(':empty')) {
					fetchChangesTable(); // Make a GET request here
				}
			});
			
			// AJAX for Clean button click
			$("#clean").click(function(event) {
				event.preventDefault(); // Prevent the default form submission

				// Clean superfluous words and update Changes table
				$.ajax({
					type: "POST",
					url: "clean.php",
					success: function(response) {
						if (response === "success") {
							// Refresh Keywords table to see the changes
							fetchKeywordsTable();
							fetchChangesTable();
							fetchSuperfluousTable();
							updateReport();
							alert("Cleaned successfully");
						} else {
							alert("Failed to clean");
						}
					}
				});
			});
			
			// Function to render keyword counts in a table
			function renderKeywordCountTable(data) {
				const tableBody = $("#keyword-count-table tbody");
				tableBody.empty(); // Clear previous content

				// Loop through the data to populate the table
				data.forEach(item => {
					tableBody.append(`<tr><td>${item.keyword}</td><td>${item.count}</td></tr>`);
				});
			}

			// Function to update the report (chart and table) with the latest data
			function updateReport() {
				$.ajax({
					type: "GET",
					url: "report.php",
					success: function(response) {
						const keywordData = JSON.parse(response);
						// Update the chart with new keyword data
						renderKeywordChart(keywordData);
						// Update the keyword count table
						renderKeywordCountTable(keywordData);
					},
					error: function(xhr, status, error) {
						console.error(xhr.responseText);
						alert("Error fetching keyword counts");
					}
				});
			}

			// AJAX for Report button click
			$("#report").click(function(event) {
				event.preventDefault();

				// Hide existing containers
				$("#urls-table-container").hide();
				$("#keywords-table-container").hide();
				$("#superfluous-table-container").hide();
				$("#changes-table-container").hide();

				// Show the report container
				$("#report-table-container").show();

				// Update the report (chart and table) with the latest data
				updateReport();
			});



			// Function to fetch and update the keywords table
			function fetchKeywordsTable() {
				$.ajax({
					type: "GET",
					url: "keywords.php",
					success: function(response) {
						$("#keywords-table").html(response);
					}
				});
			}

			// Form submission for inserting superfluous words into URL table
			$("#insert-superfluous-form-url").submit(function(event) {
				event.preventDefault();
				var superfluousWord = $("#superfluous_word_url").val();
				if (superfluousWord === "") {
					alert("Superfluous word cannot be empty");
					return;
				}
				// Send an additional parameter to specify the table
				$.ajax({
					type: "POST",
					url: "superfluous.php?table=urls",
					data: { word: superfluousWord },
					success: function(response) {
						if (response.trim() !== "") {
							$("#superfluous-table tbody").html(response); // Update table content
							$("#superfluous_word_url").val(""); // Clear the input field
							alert("Superfluous word added correctly");
						}
					},
					error: function(xhr, status, error) {
						console.error(xhr.responseText);
						alert("Error: Failed to add superfluous word");
					}
				});
			});

			// Form submission for inserting superfluous words into Keywords table
			$("#insert-superfluous-form-key").submit(function(event) {
				event.preventDefault();
				var superfluousWord = $("#superfluous_word_key").val();
				if (superfluousWord === "") {
					alert("Superfluous word cannot be empty");
					return;
				}
				// Send an additional parameter to specify the table
				$.ajax({
					type: "POST",
					url: "superfluous.php?table=keywords",
					data: { word: superfluousWord },
					success: function(response) {
						if (response.trim() !== "") {
							$("#superfluous-table tbody").html(response); // Update table content
							$("#superfluous_word_key").val(""); // Clear the input field
							alert("Superfluous word added correctly");
						}
					},
					error: function(xhr, status, error) {
						console.error(xhr.responseText);
						alert("Error: Failed to add superfluous word");
					}
				});
			});

			// AJAX for inserting superfluous words
			$("#insert-superfluous-form").submit(function(event) {
				event.preventDefault();
				var superfluousWord = $("#superfluous_word").val();
				if (superfluousWord === "") {
					alert("Superfluous word cannot be empty");
					return;
				}
				// Send an additional parameter to specify the table
				$.ajax({
					type: "POST",
					url: "superfluous.php",
					data: { word: superfluousWord },
					success: function(response) {
						if (response.trim() !== "") {
							$("#superfluous-table tbody").html(response); // Update table content
							$("#superfluous_word").val(""); // Clear the input field
							alert("Superfluous word added correctly");
						}
					},
					error: function(xhr, status, error) {
						console.error(xhr.responseText);
						alert("Error: Failed to add superfluous word");
					}
				});
			});

			// Function to fetch and update the superfluous table
			function fetchSuperfluousTable() {
				$.ajax({
					type: "GET",
					url: "superfluous.php",
					success: function(response) {
						$("#superfluous-table tbody").html(response);
					}
				});
			}
					
			// Form submission for inserting change words into Changes table
			$("#insert-change-form").submit(function(event) {
				event.preventDefault();
				var originalWord = $("#change_word").val();
				var finalWord = $("#to_word").val();

				// Check if either of the words is empty
				if (originalWord.trim() === "" || finalWord.trim() === "") {
					alert("Both original and final words are required");
					return;
				}

				// Send the original and final words to changes.php
				$.ajax({
					type: "POST",
					url: "changes.php",
					data: { original: originalWord, final: finalWord },
					success: function(response) {
						if (response === "success") {
							fetchChangesTable(); // Update the Changes table after successful insertion
							$("#change_word").val(""); // Clear the input field for original word
							$("#to_word").val(""); // Clear the input field for final word
							alert("Changed word added correctly");
						} else {
							alert("Failed to add change");
						}
					},
					error: function(xhr, status, error) {
						console.error(xhr.responseText); // Log any errors in the console
						alert("Error: Failed to add change");
					}
				});
			});
			
			// Function to fetch and update the changes table
			function fetchChangesTable() {
				$.ajax({
					type: "GET",
					url: "changes.php",
					success: function(response) {
						$("#changes-table tbody").html(response);
					}
				});
			}
			
			// Function to render keyword counts in a table
			function renderKeywordCountTable(data) {
				const tableBody = $("#keyword-count-table tbody");
				tableBody.empty(); // Clear previous content

				// Loop through the data to populate the table
				data.forEach(item => {
					tableBody.append(`<tr><td>${item.keyword}</td><td>${item.count}</td></tr>`);
				});
			}

			let myChart; // Define a global variable to hold the chart instance

			// Function to render Chart.js bar plot
			function renderKeywordChart(data) {
				// Destroy the previous chart instance if it exists
				if (myChart) {
					myChart.destroy();
				}

				// Sort data by count (ascending order)
				data.sort((a, b) => a.count - b.count);

				// Extract labels and counts for chart data
				const labels = data.map(item => item.keyword);
				const counts = data.map(item => item.count);

				const ctx = document.getElementById('keyword-chart').getContext('2d');
				myChart = new Chart(ctx, {
					type: 'bar',
					data: {
						labels: labels,
						datasets: [{
							label: 'Keyword Counts',
							data: counts,
							backgroundColor: 'rgba(0, 116, 228, 0.8)',
							borderColor: 'rgba(0, 0, 0, 1)',
							borderWidth: 0
						}]
					},
					options: {
						scales: {
							yAxes: [{
								ticks: {
									beginAtZero: true,
									precision: 0,
									font: {
										weight: 'bold'
									}
								},
								scaleLabel: {
									display: true,
									labelString: 'Counts',
									font: {
										weight: 'bold'
									}
								}
							}],
							xAxes: [{
								ticks: {
									font: {
										weight: 'bold'
									}
								},
								scaleLabel: {
									display: true,
									labelString: 'Keywords',
									font: {
										weight: 'bold'
									}
								}
							}]
						}
					}
				});
			}

			// Function to download keywords list and counts in XML format
			$("#download_xml").click(function(event) {
				event.preventDefault();

				// AJAX call to download XML
				$.ajax({
					type: "GET",
					url: "download_xml.php",
					dataType: "text", // Ensure the response is treated as text
					success: function(response) {
						// Create a Blob object with the XML content
						const blob = new Blob([response], { type: 'application/xml' });
						const url = window.URL.createObjectURL(blob);

						// Create a temporary anchor element to trigger the download
						const a = document.createElement('a');
						a.href = url;
						a.download = 'keywords.xml';
						document.body.appendChild(a);
						a.click();

						// Clean up
						document.body.removeChild(a);
						window.URL.revokeObjectURL(url);
					},
					error: function(xhr, status, error) {
						console.error(xhr.responseText);
						alert("Error downloading XML");
					}
				});
			});


			// Function to download keywords list and counts in JSON format
			$("#download_json").click(function(event) {
				event.preventDefault();

				// AJAX call to download JSON
				$.ajax({
					type: "GET",
					url: "download_json.php",
					dataType: "json", // Ensure the response is treated as JSON
					success: function(response) {
						// Convert JSON object to string
						const jsonString = JSON.stringify(response);

						// Create a Blob object with the JSON content
						const blob = new Blob([jsonString], { type: 'application/json' });
						const url = window.URL.createObjectURL(blob);

						// Create a temporary anchor element to trigger the download
						const a = document.createElement('a');
						a.href = url;
						a.download = 'keywords.json';
						document.body.appendChild(a);
						a.click();

						// Clean up
						document.body.removeChild(a);
						window.URL.revokeObjectURL(url);
					},
					error: function(xhr, status, error) {
						console.error(xhr.responseText);
						alert("Error downloading JSON");
					}
				});
			});

			// Function to download keywords list and counts in XLS format
			$("#download_xls").click(function(event) {
				event.preventDefault();

				// AJAX call to download XLS
				$.ajax({
					type: "GET",
					url: "download_xls.php",
					success: function(response) {
						// Trigger download of XLS file
						const blob = new Blob([response], { type: 'application/vnd.ms-excel' });
						const url = window.URL.createObjectURL(blob);
						const a = document.createElement('a');
						a.href = url;
						a.download = 'keywords.xls';
						document.body.appendChild(a);
						a.click();
						document.body.removeChild(a);
						window.URL.revokeObjectURL(url);
					},
					error: function(xhr, status, error) {
						console.error(xhr.responseText);
						alert("Error downloading XLS");
					}
				});
			});


	
            // Initial table update when the page loads
            fetchAndUpdateTable();
            fetchKeywordsTable();
            fetchSuperfluousTable();
			fetchChangesTable();
			updateReport();
        });
		
    </script>
</head>
<body>
    <h1>Final Project - Storage and Data Recovery</h1>

    <!-- Buttons for different functionalities -->
    <div class="function-buttons" style="text-align: center;">
		<button type="button" id="urls">URLs</button>
        <button type="button" id="scrap">Scrap</button>
        <button type="button" id="split">Split</button>
		<button type="button" id="keywords">Keywords</button>
        <button type="button" id="superfluous">Superfluous</button>
        <button type="button" id="changes">Changes</button>
        <button type="button" id="clean">Clean</button>
        <button type="button" id="report">Report</button>
        <button type="button" id="download_xml">Download XML</button>
        <button type="button" id="download_json">Download JSON</button>
        <button type="button" id="download_xls">Download XLS</button>
    </div>

    <!-- Display table of inserted URLs -->
    <div id="urls-table-container" style="text-align: center;">
	
			<!-- Form to insert URLs -->
		<form id="insert-url-form">
			<input type="text" id="url" name="url" placeholder="Enter URL">
			<button type="submit">Insert URL</button>
		</form>
		<!-- Form to insert Superfluous words -->
		<form id="insert-superfluous-form-url">
			<input type="text" id="superfluous_word_url" name="superfluous_word_url" placeholder="Enter superfluous word">
			<button type="submit">Insert word</button>
		</form>
		
        <table id="urls-table">
            <thead>
                <tr>
                    <th>URL</th>
                    <th>Title</th>
                    <th>Scraped</th>
                    <th>Splitted</th>
                </tr>
            </thead>
            <tbody>
                <!-- This part will be dynamically updated -->
            </tbody>
        </table>
    </div>

    <div id="keywords-table-container" style="text-align: center;">
	
		<!-- Form to insert Superfluous words -->
		<form id="insert-superfluous-form-key">
			<input type="text" id="superfluous_word_key" name="superfluous_word_key" placeholder="Enter superfluous word">
			<button type="submit">Insert word</button>
		</form>
	
        <!-- Table for keywords will be displayed here -->
		<form id="insert-change-form">
			<span>Change </span>
			<input type="text" id="change_word" name="change_word" placeholder="Original keyword">
			<span>to </span>
			<input type="text" id="to_word" name="to_word" placeholder="Final keyword">
			<button type="submit">Change word</button>
		</form>
		
		<table id="keywords-table">
			<thead>
			</thead>
			<tbody>
			</tbody>
		</table>
		
    </div>
    
		<!-- Display table of inserted Superfluous words -->
	<div id="superfluous-table-container" style="text-align: center;">
	
		<form id="insert-superfluous-form">
			<input type="text" id="superfluous_word" name="superfluous_word" placeholder="Enter superfluous word">
			<button type="submit">Insert word</button>
		</form>
	
		<table id="superfluous-table">
			<thead>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
	
	<!-- Display table of inserted changes words -->
	<div id="changes-table-container" style="text-align: center;">

		<table id="changes-table">
			<thead>
				<tr>
					<th>Original Word</th>
					<th>Final Word</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
	
	<!-- Display table of keywords plot -->
	<div id="report-table-container" style="text-align: center; display: none;">
		<canvas id="keyword-chart"></canvas>
		<table id="keyword-count-table">
			<!-- Table content will be dynamically updated -->
		</table>
	</div>
	
</body>
</html>
