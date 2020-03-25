require(
    ["jquery", "mage/calendar"],
    // Make the time picker work for the objects with the class.
    function($)
    {
        // Set datetime picket options.
        $('.datepicker').timepicker(
        {
            timeFormat: "h:mmTT"
        });
    }
);

require(
    ["jquery"],
    function($)
    {
        // When the store is closed we want to disable the fields for the from and to date.
        $(".check-store-closed").click(
            function()
            {
                // Get what day was selected so we know what fields are associated
                var selectedDay = $(this).attr("id").replace("stores_", "");

                if($(this).prop("checked") === true)
                {
                    // Clear and disable fields.
                    clearTimeFields(selectedDay);
                }
                else
                {
                    // Enable fields again.
                    $("#" + selectedDay + "_from").prop("disabled", false);
                    $("#" + selectedDay + "_to").prop("disabled", false);
                }

                // Rebuild json field because we have a new action and the data changed.
                generateStoreHoursJson();
            }
        );

        $(".datepicker").change(
            function ()
            {
                // Rebuild json field because we have a new action and the data changed.
                generateStoreHoursJson();
            }
        );

        // Clear and disable to and from fields.
        // Pass in the day field to know what elements we want to manipulate.
        function clearTimeFields(day)
        {
            $("#" + day + "_from").prop("disabled", true);
            $("#" + day + "_to").prop("disabled", true);
            $("#" + day + "_from").val("");
            $("#" + day + "_to").val("");
        }

        (function () {
            // Get the Json for the store hours from the hidden field.
            var storeHourValue = $("#stores_store_hours").val();

            // If this is a new store we will not have any json
            if(storeHourValue != "")
            {
                // Get the days of the week so we can loop over them.
                var weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']
                var jsonStoreHours = JSON.parse(storeHourValue);

                //Now that we have an object from the json we can loop over each element and fill in the data.
                for (var i = 0; i < weekdays.length; i++) {
                    // Get the current day in the loop
                    var weekday = weekdays[i].toString();
                    // Get the store hours for the day in the loop.
                    var hours = jsonStoreHours[weekday];

                    if (hours != null)
                    {
                        // We have hours so we can populate.
                        $("#" + weekday + "_from").val(hours.Start);
                        $("#" + weekday + "_to").val(hours.Stop);
                    }
                    else
                    {
                        // No hours found so we are closed this day.
                        $("#stores_" + weekday).prop("checked", "checked");
                        $("#stores_" + weekday).val("true");
                        clearTimeFields(weekday);
                    }
                }
            }
        })();

        // Generates the json needed to store the store hours in the database.
        function generateStoreHoursJson()
        {
            // Get each days hours and create objects for them.
            // We also check if a value is set otherwise it is null and null means closed.
            var Monday = ($("#Monday_from").val() === "" || $("#Monday_to").val() ==="") ? null : {Start: $("#Monday_from").val(), Stop: $("#Monday_to").val()};
            var Tuesday = ($("#Tuesday_from").val() === "" || $("#Tuesday_to").val() ==="") ? null : {Start: $("#Tuesday_from").val(), Stop: $("#Tuesday_to").val()};
            var Wednesday = ($("#Wednesday_from").val() === "" || $("#Wednesday_to").val() ==="") ? null : {Start: $("#Wednesday_from").val(), Stop: $("#Wednesday_to").val()};
            var Thursday = ($("#Thursday_from").val() === "" || $("#Thursday_to").val() ==="") ? null : {Start: $("#Thursday_from").val(), Stop: $("#Thursday_to").val()};
            var Friday = ($("#Friday_from").val() === "" || $("#Friday_to").val() ==="") ? null : {Start: $("#Friday_from").val(), Stop: $("#Friday_to").val()};
            var Saturday = ($("#Saturday_from").val() === "" || $("#Saturday_to").val() ==="") ? null : {Start: $("#Saturday_from").val(), Stop: $("#Saturday_to").val()};
            var Sunday = ($("#Sunday_from").val() === "" || $("#Sunday_to").val() ==="") ? null : {Start: $("#Sunday_from").val(), Stop: $("#Sunday_to").val()};

            // Now create the object with all the days as properties with their times.
            var HoursObject = {"Monday": Monday, "Tuesday": Tuesday, "Wednesday":Wednesday, "Thursday":Thursday, "Friday":Friday, "Saturday":Saturday, "Sunday":Sunday};

            // Turn the object into json.
            var weekhoursJson = JSON.stringify(HoursObject);

            // Add the json to the hidden fields so it can be posted to the server when we hit save.
            $("#stores_store_hours").val(weekhoursJson);
        }
    }
);
