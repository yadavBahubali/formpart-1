<!DOCTYPE html>
<html>

<head>
    <title>Address Form</title>
    <?php
    include 'assets/_header.php'
        ?>
</head>

<body>
    <section class="wrapper">
        <div class="header text-center">
            <h1>Address Form</h1>
        </div>
        <div class="container">
            <div class="container-fluid">
                <div class="col-md-12">
                    <div class="row">
                        <form class="form-row" id="addressform" action="process_form.php" method="POST">
                            <div class="col-md-12">
                                <div class="col-md-6 form-group">
                                    <select class="form-control" name="countries" id="countries">
                                        <option value="">Select Country</option>

                                    </select>
                                </div>

                                <div class="col-md-6 form-group">
                                    <select class="form-control" name="states" id="states">
                                        <!-- Options for states will be dynamically populated based on the selected country -->

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-6 form-group">
                                    <select class="form-control" name="cities" id="cities">
                                        <!-- Options for cities will be dynamically populated based on the selected state -->

                                    </select>
                                </div>

                                <div class="col-md-6 form-group">
                                    <input class="form-control" type="text" name="pincode" id="pincode"
                                        placeholder="Enter pincode">
                                </div>

                                <div class="col-md-12 form-group justify-content-center">
                                    <input type="submit" class="btn btn-primary" value="Submit">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="wrapper">
        <div class="container-fluid">
            <div class="col-lg-12 ">
                <div class="table-responsive">
                    <button id="fetchDataBtn" class="btn btn-primary">Fetch Data</button>
                    <table id="dataTable" class="table text-center">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Country</th>
                                <th>State</th>
                                <th>City</th>
                                <th>Pincode</th>
                            </tr>
                        </thead>
                        <tbody id="dataBody">
                            <!-- Data will be dynamically populated here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>



    <?php
    include 'assets/_footer.php';
    ?>
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <script>
        const API_KEY = "R0g0OUpySk9VNUFjb01QcWFHVUhtbXY0aFY5ZExsbUVIQXZGNTVJYg==";



        // Function to populate the country dropdown
        function populateCountries() {
            const countriesDropdown = $("#countries");
            const settings = {
                url: "https://api.countrystatecity.in/v1/countries",
                method: "GET",
                headers: {
                    "X-CSCAPI-KEY": API_KEY
                },
            };

            $.ajax(settings).done(function (response) {
                for (var key in response) {
                    if (response.hasOwnProperty(key)) {
                        var country = response[key];
                        var country_iso = country.iso2; // Assuming the ISO code is used
                        var country_name = country.name;

                        // Create an option element for each country and add it to the dropdown
                        countriesDropdown.append($('<option>', {
                            value: country_iso,
                            text: country_name
                        }));
                    }
                }
            });
        }

        // Function to populate states based on the selected country
        function populateStates() {
            const selectedCountryIso = $("#countries").val();
            const statesDropdown = $("#states");

            // Clear existing options in the states dropdown
            statesDropdown.empty();

            if (selectedCountryIso) {
                const settings = {
                    url: `https://api.countrystatecity.in/v1/countries/${selectedCountryIso}/states`,
                    method: "GET",
                    headers: {
                        "X-CSCAPI-KEY": API_KEY
                    },
                };

                $.ajax(settings).done(function (response) {
                    for (var key in response) {
                        if (response.hasOwnProperty(key)) {
                            var state = response[key];
                            var state_iso = state.iso2; // Assuming the ISO code is used
                            var state_name = state.name;

                            // Create an option element for each state and add it to the dropdown
                            statesDropdown.append($('<option>', {
                                value: state_iso,
                                text: state_name
                            }));
                        }
                    }
                });
            }
        }

        // Function to populate cities based on the selected state and country
        function populateCities() {
            const selectedCountryIso = $("#countries").val(); // Assuming the ISO code is used as the value in the country dropdown
            const selectedStateIso = $("#states").val(); // Assuming the ISO code is used as the value in the state dropdown
            const citiesDropdown = $("#cities");

            // Clear existing options in the cities dropdown
            citiesDropdown.empty();

            if (selectedCountryIso && selectedStateIso) {
                const settings = {
                    url: `https://api.countrystatecity.in/v1/countries/${selectedCountryIso}/states/${selectedStateIso}/cities`,
                    method: "GET",
                    headers: {
                        "X-CSCAPI-KEY": API_KEY
                    },
                };

                $.ajax(settings).done(function (response) {
                    for (var key in response) {
                        if (response.hasOwnProperty(key)) {
                            var city = response[key];
                            var city_id = city.id;
                            var city_name = city.name;

                            // Create an option element for each city and add it to the dropdown
                            citiesDropdown.append($('<option>', {
                                value: city_id,
                                text: city_name
                            }));
                        }
                    }
                });
            }
        }

        // Call the populateCountries function to initially populate the country dropdown
        populateCountries();

        // Attach an event listener to the countries dropdown to populate states based on the selected country
        $("#countries").on("change", populateStates);

        // Attach an event listener to the states dropdown to populate cities based on the selected state
        $("#states").on("change", populateCities);
        $("#addressform").on("submit", function (event) {
            event.preventDefault();
            var selectedCountryName = $("#countries option:selected").text();
            var selectedStateName = $("#states option:selected").text();
            var selectedCityName = $("#cities option:selected").text();
            var selectedPincode = $("#pincode").val();
            var formData = {
                country: $("#countries").val(),
                state: $("#states").val(),
                city: $("#cities").val(),
                pincode: $("#pincode").val(),
            };
            console.log(formData);
            $.ajax({
                url: "process_form.php", // The PHP file that will handle the form data
                type: "POST",
                data: formData,
                dataType: "json",
                success: function (response) {
                    // Display success message to the user
                    alert(response.message);

                    // Clear the form fields
                    $("#addressform")[0].reset();

                    // You can also update the table with the latest data here
                    fetchData(); // Assuming fetchData function is defined to update the table
                },
                error: function (error) {
                    console.error(error);
                    // Handle errors if necessary
                },
            });
        });
        // Function to fetch and display data
        function fetchData() {
            $.getJSON("data.json", function (data) {
                // Clear the table body
                $("#dataBody").empty();

                // Loop through the data and populate the table
                $.each(data, function (index, entry) {
                    $("#dataBody").append(
                        `<tr>
                    <td>${index + 1}</td>
                    <td>${entry.Country}</td>
                    <td>${entry.State}</td>
                    <td>${entry.City}</td>
                    <td>${entry.Pincode}</td>
                </tr>`
                    );
                });
            });
        }

        // Attach an event listener to the "Fetch Data" button
        $("#fetchDataBtn").on("click", function () {
            fetchData();
        });

    </script>







</body>

</html>